<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\Ciudad;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class VendedorManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $sortBy  = 'apellido';
    public string $sortDir = 'asc';

    public string $colFilterNombre   = '';
    public string $colFilterApellido = '';
    public string $colFilterTelefono = '';
    public string $colFilterEmail    = '';
    public string $colFilterCiudad   = '';
    public string $colFilterEstado   = '';
    public string $colFilterAcceso   = '';
    public string $colFilterUsuario  = '';
    public string $colFilterRol      = '';

    public ?int $selectedVendedorId = null;

    public function selectVendedor(int $id): void
    {
        $this->selectedVendedorId = $this->selectedVendedorId === $id ? null : $id;
    }

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

    public function updatingColFilterNombre():   void { $this->resetPage(); }
    public function updatingColFilterApellido(): void { $this->resetPage(); }
    public function updatingColFilterTelefono(): void { $this->resetPage(); }
    public function updatingColFilterEmail():    void { $this->resetPage(); }
    public function updatingColFilterCiudad():   void { $this->resetPage(); }
    public function updatingColFilterEstado():   void { $this->resetPage(); }
    public function updatingColFilterAcceso():   void { $this->resetPage(); }
    public function updatingColFilterUsuario():  void { $this->resetPage(); }
    public function updatingColFilterRol():      void { $this->resetPage(); }

    // ── Panel alta ───────────────────────────────────────────────────────────
    public bool   $showAddForm      = false;
    public string $newNombre        = '';
    public string $newApellido      = '';
    public string $newTelefono      = '';
    public string $newEmail         = '';
    public string $newCiudadId      = '';
    public int    $newActivo        = 1;
    public bool   $newTieneAcceso   = false;
    public string $newUserEmail     = '';
    public string $newUserPassword  = '';
    public string $newUserRol       = '';

    // ── Fila edición inline ──────────────────────────────────────────────────
    public ?int   $editingId         = null;
    public string $editNombre        = '';
    public string $editApellido      = '';
    public string $editTelefono      = '';
    public string $editEmail         = '';
    public string $editCiudadId      = '';
    public int    $editActivo        = 1;
    public bool   $editTieneAcceso   = false;
    public ?int   $editUserIdActual  = null;
    public string $editUserEmail     = '';
    public string $editUserPassword  = '';
    public string $editUserRol       = '';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Alta ─────────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm     = true;
        $this->editingId       = null;
        $this->newNombre       = '';
        $this->newApellido     = '';
        $this->newTelefono     = '';
        $this->newEmail        = '';
        $this->newCiudadId     = '';
        $this->newActivo       = 1;
        $this->newTieneAcceso  = false;
        $this->newUserEmail    = '';
        $this->newUserPassword = '';
        $this->newUserRol      = '';
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
            'newNombre'       => 'required|string|min:2|max:100',
            'newApellido'     => 'required|string|min:2|max:100',
            'newTelefono'     => 'nullable|string|max:30',
            'newEmail'        => 'nullable|email|max:150',
            'newCiudadId'     => 'nullable|exists:ciudades,id',
            'newUserEmail'    => $this->newTieneAcceso
                ? ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z0-9._@-]+$/', Rule::unique('users', 'email')]
                : 'nullable',
            'newUserPassword' => $this->newTieneAcceso ? 'required|min:6' : 'nullable|min:6',
            'newUserRol'      => $this->newTieneAcceso ? 'required|exists:roles,name' : 'nullable',
        ], [
            'newUserEmail.unique' => 'Ese usuario ya está en uso.',
            'newUserEmail.regex'  => 'Solo letras, números, punto, guión, guión bajo o @.',
        ], [
            'newNombre'       => 'nombre',
            'newApellido'     => 'apellido',
            'newTelefono'     => 'teléfono',
            'newEmail'        => 'email',
            'newCiudadId'     => 'ciudad',
            'newUserEmail'    => 'usuario',
            'newUserPassword' => 'contraseña',
            'newUserRol'      => 'rol',
        ]);

        $userId = null;
        if ($this->newTieneAcceso) {
            $user = User::create([
                'name'     => "{$this->newNombre} {$this->newApellido}",
                'email'    => $this->newUserEmail,
                'password' => Hash::make($this->newUserPassword),
                'tipo'     => 'vendedor',
            ]);
            $user->assignRole($this->newUserRol);
            $userId = $user->id;
        }

        Vendedor::create([
            'nombre'   => $this->newNombre,
            'apellido' => $this->newApellido,
            'telefono'  => $this->newTelefono ?: null,
            'email'     => $this->newEmail    ?: null,
            'ciudad_id' => $this->newCiudadId ?: null,
            'user_id'   => $userId,
            'activo'    => $this->newActivo,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Vendedor creado.');
    }

    // ── Edición inline ───────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $v = Vendedor::with('user')->findOrFail($id);

        $this->editingId    = $id;
        $this->editNombre   = $v->nombre;
        $this->editApellido = $v->apellido;
        $this->editTelefono = $v->telefono ?? '';
        $this->editEmail    = $v->email    ?? '';
        $this->editCiudadId = (string) ($v->ciudad_id ?? '');
        $this->editActivo   = $v->activo ? 1 : 0;

        if ($v->user) {
            $this->editTieneAcceso  = true;
            $this->editUserIdActual = $v->user->id;
            $this->editUserEmail    = $v->user->email;
            $this->editUserPassword = '';
            $this->editUserRol      = $v->user->getRoleNames()->first() ?? '';
        } else {
            $this->editTieneAcceso  = false;
            $this->editUserIdActual = null;
            $this->editUserEmail    = '';
            $this->editUserPassword = '';
            $this->editUserRol      = '';
        }

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
        $emailUnico = $this->editUserIdActual
            ? Rule::unique('users', 'email')->ignore($this->editUserIdActual)
            : Rule::unique('users', 'email');

        $this->validate([
            'editNombre'       => 'required|string|min:2|max:100',
            'editApellido'     => 'required|string|min:2|max:100',
            'editTelefono'     => 'nullable|string|max:30',
            'editEmail'        => 'nullable|email|max:150',
            'editCiudadId'     => 'nullable|exists:ciudades,id',
            'editUserEmail'    => $this->editTieneAcceso
                ? ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z0-9._@-]+$/', $emailUnico]
                : 'nullable',
            'editUserPassword' => $this->editTieneAcceso && !$this->editUserIdActual ? 'required|min:6' : 'nullable|min:6',
            'editUserRol'      => $this->editTieneAcceso ? 'required|exists:roles,name' : 'nullable',
        ], [
            'editUserEmail.unique' => 'Ese usuario ya está en uso.',
            'editUserEmail.regex'  => 'Solo letras, números, punto, guión, guión bajo o @.',
        ], [
            'editNombre'       => 'nombre',
            'editApellido'     => 'apellido',
            'editTelefono'     => 'teléfono',
            'editEmail'        => 'email',
            'editCiudadId'     => 'ciudad',
            'editUserEmail'    => 'usuario',
            'editUserPassword' => 'contraseña',
            'editUserRol'      => 'rol',
        ]);

        $userId = null;
        if ($this->editTieneAcceso) {
            if ($this->editUserIdActual) {
                $user = User::findOrFail($this->editUserIdActual);
                $user->email = $this->editUserEmail;
                $user->tipo  = 'vendedor';
                if ($this->editUserPassword) {
                    $user->password = Hash::make($this->editUserPassword);
                }
                $user->save();
                $user->syncRoles([$this->editUserRol]);
                $userId = $user->id;
            } else {
                $user = User::create([
                    'name'     => "{$this->editNombre} {$this->editApellido}",
                    'email'    => $this->editUserEmail,
                    'password' => Hash::make($this->editUserPassword),
                    'tipo'     => 'vendedor',
                ]);
                $user->assignRole($this->editUserRol);
                $userId = $user->id;
            }
        }

        Vendedor::findOrFail($this->editingId)->update([
            'nombre'   => $this->editNombre,
            'apellido' => $this->editApellido,
            'telefono'  => $this->editTelefono ?: null,
            'email'     => $this->editEmail    ?: null,
            'ciudad_id' => $this->editCiudadId ?: null,
            'user_id'   => $userId,
            'activo'    => $this->editActivo,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Vendedor actualizado.');
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        $vendedores = Vendedor::with(['user.roles', 'ciudad'])
            ->when($this->colFilterNombre,   fn($q) => $q->where('nombre',   'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterApellido, fn($q) => $q->where('apellido', 'like', "%{$this->colFilterApellido}%"))
            ->when($this->colFilterTelefono, fn($q) => $q->where('telefono', 'like', "%{$this->colFilterTelefono}%"))
            ->when($this->colFilterEmail,    fn($q) => $q->where('email',    'like', "%{$this->colFilterEmail}%"))
            ->when($this->colFilterCiudad,   fn($q) => $q->whereHas('ciudad',
                fn($qq) => $qq->where('nombre', 'like', "%{$this->colFilterCiudad}%")))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('activo', $this->colFilterEstado === '1'))
            ->when($this->colFilterAcceso !== '', fn($q) =>
                $this->colFilterAcceso === '1' ? $q->whereNotNull('user_id') : $q->whereNull('user_id'))
            ->when($this->colFilterUsuario, fn($q) => $q->whereHas('user',
                fn($qq) => $qq->where('email', 'like', "%{$this->colFilterUsuario}%")))
            ->when($this->colFilterRol, fn($q) => $q->whereHas('user.roles',
                fn($qq) => $qq->where('name', $this->colFilterRol)))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(15);

        $roles   = Role::orderBy('name')->get();
        $ciudades = Ciudad::orderBy('nombre')->get();

        return view('livewire.admin.vendedores.vendedor-manager',
            compact('vendedores', 'roles', 'ciudades'));
    }
}
