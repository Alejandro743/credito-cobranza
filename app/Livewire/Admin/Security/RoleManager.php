<?php
namespace App\Livewire\Admin\Security;

use App\Models\Modulo;
use App\Models\RolSubmoduloPermiso;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode   = 'list';   // list | permissions
    public string $search = '';

    // Inline add
    public bool   $showAddForm  = false;
    public string $newRoleName  = '';
    public bool   $newActivo    = true;

    // Inline edit
    public ?int   $editingId    = null;
    public string $editRoleName = '';
    public bool   $editActivo   = true;

    // Permissions tree
    public ?int   $permissionsRoleId   = null;
    public string $permissionsRoleName = '';
    public array  $permissions         = [];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm  = true;
        $this->newRoleName  = '';
        $this->newActivo    = true;
        $this->editingId    = null;
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newRoleName' => ['required', 'string', 'min:2', 'max:50',
                              Rule::unique('roles', 'name')],
        ], [], ['newRoleName' => 'nombre del rol']);

        Role::create(['name' => strtolower(trim($this->newRoleName)), 'guard_name' => 'web', 'activo' => $this->newActivo]);

        $this->showAddForm = false;
        session()->flash('success', 'Rol creado.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $role               = Role::findOrFail($id);
        $this->editingId    = $id;
        $this->editRoleName = $role->name;
        $this->editActivo   = (bool) ($role->activo ?? true);
        $this->showAddForm  = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editRoleName' => ['required', 'string', 'min:2', 'max:50',
                               Rule::unique('roles', 'name')->ignore($this->editingId)],
        ], [], ['editRoleName' => 'nombre del rol']);

        $role = Role::findOrFail($this->editingId);

        if ($role->name === 'admin') {
            $role->update(['name' => 'admin']); // no cambia nombre de admin
        } else {
            $role->update([
                'name'   => strtolower(trim($this->editRoleName)),
                'activo' => $this->editActivo,
            ]);
        }

        $this->editingId = null;
        session()->flash('success', 'Rol actualizado.');
    }

    public function toggleActivo(int $id): void
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'admin') return;
        $role->update(['activo' => !($role->activo ?? true)]);
    }

    // ── Árbol de permisos ─────────────────────────────────────────────────────

    public function openPermissions(int $roleId): void
    {
        $role                      = Role::findOrFail($roleId);
        $this->permissionsRoleId   = $roleId;
        $this->permissionsRoleName = $role->name;
        $this->loadPermissions($roleId);
        $this->mode = 'permissions';
    }

    private function loadPermissions(int $roleId): void
    {
        $submodulos = \App\Models\Submodulo::where('active', true)->whereNotNull('route_name')->get();
        $existentes = RolSubmoduloPermiso::where('role_id', $roleId)->get()->keyBy('submodulo_id');

        $this->permissions = [];
        foreach ($submodulos as $sub) {
            $p = $existentes->get($sub->id);
            $this->permissions[(string) $sub->id] = [
                'puede_ver' => (bool) ($p?->puede_ver ?? false),
            ];
        }
    }

    public function savePermissions(): void
    {
        foreach ($this->permissions as $submoduloId => $perms) {
            RolSubmoduloPermiso::updateOrCreate(
                ['role_id' => $this->permissionsRoleId, 'submodulo_id' => (int) $submoduloId],
                ['puede_ver' => (bool) ($perms['puede_ver'] ?? false)]
            );
        }
        session()->flash('success', "Permisos guardados para \"{$this->permissionsRoleName}\".");
        $this->backToList();
    }

    public function toggleModulo(int $moduloId, bool $value): void
    {
        $subIds = \App\Models\Submodulo::where('modulo_id', $moduloId)->where('active', true)->pluck('id');
        foreach ($subIds as $subId) {
            $key = (string) $subId;
            if (array_key_exists($key, $this->permissions)) {
                $this->permissions[$key]['puede_ver'] = $value;
            }
            $childIds = \App\Models\Submodulo::where('parent_id', $subId)->where('active', true)->pluck('id');
            foreach ($childIds as $childId) {
                $childKey = (string) $childId;
                if (array_key_exists($childKey, $this->permissions)) {
                    $this->permissions[$childKey]['puede_ver'] = $value;
                }
            }
        }
    }

    public function toggleTodos(bool $value): void
    {
        foreach (array_keys($this->permissions) as $key) {
            $this->permissions[$key]['puede_ver'] = $value;
        }
    }

    public function backToList(): void
    {
        $this->reset(['editingId', 'showAddForm', 'newRoleName', 'permissions', 'permissionsRoleId', 'permissionsRoleName']);
        $this->mode = 'list';
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $roles = Role::withCount('users')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(20);

        $modulosArbol = Modulo::with([
                'submodulos' => fn($q) => $q->where('active', true)->orderBy('sort_order'),
                'submodulos.children' => fn($q) => $q->where('active', true)->orderBy('sort_order'),
            ])
            ->where('active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.security.role-manager', compact('roles', 'modulosArbol'));
    }
}
