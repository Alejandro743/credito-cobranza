<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\Group;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Hash;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class VendedorManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $search       = '';
    public string $filtroGrupo  = '';
    public string $filtroActivo = '';

    public string $mode = 'list';

    public bool   $editing   = false;
    public ?int   $editingId = null;

    public string $nombre   = '';
    public string $apellido = '';
    public string $telefono = '';
    public string $email    = '';
    public string $grupoId  = '';
    public bool   $activo   = true;

    // Acceso al sistema (usuario vinculado)
    public bool   $tieneAcceso  = false;
    public ?int   $userIdActual = null;   // si ya tiene usuario al editar
    public string $userEmail    = '';
    public string $userPassword = '';
    public string $userRol      = '';

    protected function rules(): array
    {
        $emailUnico = $this->userIdActual
            ? "unique:users,email,{$this->userIdActual}"
            : 'unique:users,email';

        return [
            'nombre'      => 'required|string|min:2|max:100',
            'apellido'    => 'required|string|min:2|max:100',
            'telefono'    => 'nullable|string|max:30',
            'email'       => 'nullable|email|max:150',
            'grupoId'     => 'nullable|exists:groups,id',
            'userEmail'   => $this->tieneAcceso ? "required|email|max:150|{$emailUnico}" : 'nullable',
            'userPassword'=> $this->tieneAcceso && !$this->userIdActual ? 'required|min:6' : 'nullable|min:6',
            'userRol'     => $this->tieneAcceso ? 'required|exists:roles,name' : 'nullable',
        ];
    }

    protected $messages = [
        'userEmail.required'    => 'El email de acceso es obligatorio.',
        'userEmail.unique'      => 'Ese email ya está en uso.',
        'userPassword.required' => 'La contraseña es obligatoria.',
        'userPassword.min'      => 'Mínimo 6 caracteres.',
        'userRol.required'      => 'Seleccioná un rol.',
    ];

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch():       void { $this->resetPage(); }
    public function updatingFiltroGrupo():  void { $this->resetPage(); }
    public function updatingFiltroActivo(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->reset(['nombre','apellido','telefono','email','grupoId',
                      'tieneAcceso','userIdActual','userEmail','userPassword','userRol',
                      'editingId','editing']);
        $this->activo = true;
        $this->mode   = 'form';
    }

    public function edit(int $id): void
    {
        $v = Vendedor::with('user')->findOrFail($id);

        $this->editingId = $id;
        $this->editing   = true;
        $this->nombre    = $v->nombre;
        $this->apellido  = $v->apellido;
        $this->telefono  = $v->telefono ?? '';
        $this->email     = $v->email    ?? '';
        $this->grupoId   = (string) ($v->grupo_id ?? '');
        $this->activo    = $v->activo;

        if ($v->user) {
            $this->tieneAcceso   = true;
            $this->userIdActual  = $v->user->id;
            $this->userEmail     = $v->user->email;
            $this->userPassword  = '';
            $this->userRol       = $v->user->getRoleNames()->first() ?? '';
        } else {
            $this->tieneAcceso  = false;
            $this->userIdActual = null;
            $this->userEmail    = '';
            $this->userPassword = '';
            $this->userRol      = '';
        }

        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate();

        // ── Usuario del sistema ───────────────────────────────────────────────
        $userId = null;

        if ($this->tieneAcceso) {
            if ($this->userIdActual) {
                // Actualizar usuario existente
                $user = User::findOrFail($this->userIdActual);
                $user->email = $this->userEmail;
                if ($this->userPassword) {
                    $user->password = Hash::make($this->userPassword);
                }
                $user->save();
                $user->syncRoles([$this->userRol]);
            } else {
                // Crear nuevo usuario
                $user = User::create([
                    'name'     => "{$this->nombre} {$this->apellido}",
                    'email'    => $this->userEmail,
                    'password' => Hash::make($this->userPassword),
                ]);
                $user->assignRole($this->userRol);
            }
            $userId = $user->id;
        } else {
            // Sin acceso: desvincula si tenía usuario antes
            $userId = null;
        }

        // ── Vendedor ──────────────────────────────────────────────────────────
        $data = [
            'nombre'   => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono ?: null,
            'email'    => $this->email    ?: null,
            'grupo_id' => $this->grupoId  ?: null,
            'user_id'  => $userId,
            'activo'   => $this->activo,
        ];

        if ($this->editing) {
            Vendedor::findOrFail($this->editingId)->update($data);
            $msg = 'Vendedor actualizado.';
        } else {
            Vendedor::create($data);
            $msg = 'Vendedor creado.';
        }

        session()->flash('success', $msg);
        $this->backToList();
    }

    public function backToList(): void
    {
        $this->reset(['nombre','apellido','telefono','email','grupoId',
                      'tieneAcceso','userIdActual','userEmail','userPassword','userRol',
                      'editingId','editing']);
        $this->activo = true;
        $this->mode   = 'list';
    }

    public function toggleActivo(int $id): void
    {
        $v = Vendedor::findOrFail($id);
        $v->update(['activo' => !$v->activo]);
        session()->flash('success', $v->fresh()->activo ? 'Vendedor activado.' : 'Vendedor desactivado.');
    }

    public function render()
    {
        $vendedores = Vendedor::with(['grupo', 'user'])
            ->when($this->search, fn($q) => $q->where(fn($q) =>
                $q->where('nombre',    'like', "%{$this->search}%")
                  ->orWhere('apellido','like', "%{$this->search}%")
                  ->orWhere('email',   'like', "%{$this->search}%")
            ))
            ->when($this->filtroGrupo,  fn($q) => $q->where('grupo_id', $this->filtroGrupo))
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderBy('apellido')->orderBy('nombre')
            ->paginate(10);

        $grupos = Group::where('active', true)
            ->where('type', 'vendedores')
            ->orderBy('name')->get();

        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.vendedores.vendedor-manager',
            compact('vendedores', 'grupos', 'roles'));
    }
}
