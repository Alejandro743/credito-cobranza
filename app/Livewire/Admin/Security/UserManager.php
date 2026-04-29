<?php

namespace App\Livewire\Admin\Security;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Filtros ───────────────────────────────────────────────────────────────
    public string $search       = '';
    public string $filterTipo   = '';
    public string $filterRole   = '';
    public string $filterStatus = '';

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm = false;
    public string $newName     = '';
    public string $newUsuario  = '';   // columna email
    public string $newPassword = '';
    public string $newTipo     = 'administrativo';
    public string $newRole     = '';
    public bool   $newActive   = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId  = null;
    public string $editName   = '';
    public string $editUsuario = '';   // columna email
    public string $editTipo   = 'administrativo';
    public string $editRole   = '';
    public bool   $editActive = true;

    // ── Modal cambio de contraseña ────────────────────────────────────────────
    public bool   $showPasswordModal     = false;
    public ?int   $passwordModalId       = null;
    public string $passwordModalUsuario  = '';
    public string $passwordModalNombre   = '';
    public string $passwordModalNew      = '';
    public bool   $passwordModalShow     = false;  // toggle mostrar/ocultar texto

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm = true;
        $this->newName     = '';
        $this->newUsuario  = '';
        $this->newPassword = '';
        $this->newTipo     = 'administrativo';
        $this->newRole     = '';
        $this->newActive   = true;
        $this->editingId   = null;
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
            'newName'     => 'required|string|min:2',
            'newUsuario'  => ['required', 'string', 'min:3', 'max:80',
                              'regex:/^[a-zA-Z0-9._@-]+$/',
                              Rule::unique('users', 'email')],
            'newPassword' => 'required|string|min:6',
            'newTipo'     => 'required|in:administrativo,vendedor,cliente',
            'newRole'     => 'required|string|exists:roles,name',
        ], [], [
            'newName'     => 'nombre completo',
            'newUsuario'  => 'usuario',
            'newPassword' => 'contraseña',
            'newTipo'     => 'tipo',
            'newRole'     => 'rol',
        ]);

        $user = User::create([
            'name'     => trim($this->newName),
            'email'    => trim($this->newUsuario),
            'password' => Hash::make($this->newPassword),
            'tipo'     => $this->newTipo,
            'active'   => $this->newActive,
        ]);
        $user->assignRole($this->newRole);

        $this->showAddForm = false;
        session()->flash('success', 'Usuario creado.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $u = User::findOrFail($id);
        $this->editingId   = $id;
        $this->editName    = $u->name;
        $this->editUsuario = $u->email;
        $this->editTipo    = $u->tipo ?? 'administrativo';
        $this->editRole    = $u->getRoleNames()->first() ?? '';
        $this->editActive  = (bool) $u->active;
        $this->showAddForm = false;
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
            'editName'    => 'required|string|min:2',
            'editUsuario' => ['required', 'string', 'min:3', 'max:80',
                              'regex:/^[a-zA-Z0-9._@-]+$/',
                              Rule::unique('users', 'email')->ignore($this->editingId)],
            'editTipo'    => 'required|in:administrativo,vendedor,cliente',
            'editRole'    => 'required|string|exists:roles,name',
        ], [], [
            'editName'    => 'nombre completo',
            'editUsuario' => 'usuario',
            'editTipo'    => 'tipo',
            'editRole'    => 'rol',
        ]);

        $user = User::findOrFail($this->editingId);
        $user->update([
            'name'   => trim($this->editName),
            'email'  => trim($this->editUsuario),
            'tipo'   => $this->editTipo,
            'active' => $this->editActive,
        ]);
        $user->syncRoles([$this->editRole]);

        $this->editingId = null;
        session()->flash('success', 'Usuario actualizado.');
    }

    public function toggleActive(int $id): void
    {
        $u = User::findOrFail($id);
        $u->update(['active' => !$u->active]);
    }

    // ── Modal cambio de contraseña ────────────────────────────────────────────

    public function openPasswordModal(int $id): void
    {
        $u = User::findOrFail($id);
        $this->passwordModalId      = $id;
        $this->passwordModalUsuario = $u->email;
        $this->passwordModalNombre  = $u->name;
        $this->passwordModalNew     = '';
        $this->passwordModalShow    = false;
        $this->showPasswordModal    = true;
        $this->resetValidation();
    }

    public function savePasswordModal(): void
    {
        $this->validate(
            ['passwordModalNew' => 'required|string|min:6'],
            [],
            ['passwordModalNew' => 'nueva contraseña']
        );

        User::findOrFail($this->passwordModalId)->update([
            'password' => Hash::make($this->passwordModalNew),
        ]);

        $this->showPasswordModal = false;
        session()->flash('success', 'Contraseña actualizada correctamente.');
    }

    public function closePasswordModal(): void
    {
        $this->showPasswordModal = false;
        $this->passwordModalNew  = '';
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, fn($q) =>
                $q->where('name',  'like', "%{$this->search}%")
                  ->orWhere('email','like', "%{$this->search}%"))
            ->when($this->filterTipo,   fn($q) => $q->where('tipo', $this->filterTipo))
            ->when($this->filterRole,   fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', $this->filterRole)))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(20);

        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.security.user-manager', compact('users', 'roles'));
    }
}
