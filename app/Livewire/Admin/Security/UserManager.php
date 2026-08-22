<?php

namespace App\Livewire\Admin\Security;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\User;
use App\Models\Vendedor;
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
    public string $sortBy       = 'name';
    public string $sortDir      = 'asc';

    // Filtros por columna en thead
    public string $colFilterNombre  = '';
    public string $colFilterUsuario = '';
    public string $colFilterTipo    = '';
    public string $colFilterRol     = '';
    public string $colFilterEstado  = '';

    public function updatingColFilterNombre():  void { $this->resetPage(); }
    public function updatingColFilterUsuario(): void { $this->resetPage(); }
    public function updatingColFilterTipo():    void { $this->resetPage(); }
    public function updatingColFilterRol():     void { $this->resetPage(); }
    public function updatingColFilterEstado():  void { $this->resetPage(); }

    public function refrescarGrilla(): void {}

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

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

    // ── Selección de fila ─────────────────────────────────────────────────────
    public ?int $selectedUserId = null;

    public function selectUser(int $id): void
    {
        $this->selectedUserId = $this->selectedUserId === $id ? null : $id;
    }

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
        $vendedorVinculado = Vendedor::where('user_id', $this->editingId)->first();

        $usuarioRules = ['required', 'string', 'min:3', 'max:80',
                          'regex:/^[a-zA-Z0-9._@-]+$/',
                          Rule::unique('users', 'email')->ignore($this->editingId)];
        if ($vendedorVinculado) {
            $usuarioRules[] = Rule::unique('vendedores', 'codigo_usuario')->ignore($vendedorVinculado->id);
        }

        $this->validate([
            'editName'    => 'required|string|min:2',
            'editUsuario' => $usuarioRules,
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

        // El código de usuario del vendedor debe seguir sincronizado con su login.
        if ($vendedorVinculado) {
            $vendedorVinculado->update(['codigo_usuario' => trim($this->editUsuario)]);
        }

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
            ->when($this->colFilterNombre,           fn($q) => $q->where('name',  'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterUsuario,          fn($q) => $q->where('email', 'like', "%{$this->colFilterUsuario}%"))
            ->when($this->colFilterTipo,             fn($q) => $q->where('tipo', $this->colFilterTipo))
            ->when($this->colFilterRol,              fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', $this->colFilterRol)))
            ->when($this->colFilterEstado !== '',    fn($q) => $q->where('active', (bool) $this->colFilterEstado))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(20);

        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.security.user-manager', compact('users', 'roles'));
    }
}
