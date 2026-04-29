<?php

namespace App\Livewire\Credito;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\ConfiguracionCorrelativo;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ClienteManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Filtros ───────────────────────────────────────────────────────────────
    public string $search      = '';
    public string $filterCiudad = '';
    public string $filterActivo = '';

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm   = false;
    public string $newCi         = '';
    public string $newNombre     = '';
    public string $newApellido   = '';
    public string $newTelefono   = '';
    public string $newCorreo     = '';
    public string $newNit        = '';
    public string $newCiudad     = '';
    public string $newProvincia  = '';
    public string $newMunicipio  = '';
    public string $newDireccion  = '';
    public ?int   $newVendedorId = null;
    public bool   $newActive     = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId      = null;
    public string $editCi         = '';
    public string $editNombre     = '';
    public string $editApellido   = '';
    public string $editTelefono   = '';
    public string $editCorreo     = '';
    public string $editNit        = '';
    public string $editCiudad     = '';
    public string $editProvincia  = '';
    public string $editMunicipio  = '';
    public string $editDireccion  = '';
    public ?int   $editVendedorId = null;
    public bool   $editActive     = true;

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCiudad(): void { $this->resetPage(); }
    public function updatingFilterActivo(): void { $this->resetPage(); }

    public function updatedNewCiudad(): void   { $this->newProvincia = ''; $this->newMunicipio = ''; }
    public function updatedNewProvincia(): void { $this->newMunicipio = ''; }
    public function updatedEditCiudad(): void   { $this->editProvincia = ''; $this->editMunicipio = ''; }
    public function updatedEditProvincia(): void { $this->editMunicipio = ''; }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm   = true;
        $this->editingId     = null;
        $this->newCi         = '';
        $this->newNombre     = '';
        $this->newApellido   = '';
        $this->newTelefono   = '';
        $this->newCorreo     = '';
        $this->newNit        = '';
        $this->newCiudad     = '';
        $this->newProvincia  = '';
        $this->newMunicipio  = '';
        $this->newDireccion  = '';
        $this->newVendedorId = null;
        $this->newActive     = true;
    }

    public function cancelAdd(): void { $this->showAddForm = false; }

    public function saveNew(): void
    {
        $this->validate([
            'newCi'        => ['required','string','max:20','unique:clientes,ci','unique:users,email'],
            'newNombre'    => ['required','string','max:120'],
            'newApellido'  => ['required','string','max:120'],
            'newTelefono'  => ['required','string','max:30'],
            'newCorreo'    => ['nullable','email','max:191'],
            'newNit'       => ['nullable','string','max:30'],
            'newCiudad'    => ['required','string','max:100'],
            'newProvincia' => ['required','string','max:100'],
            'newMunicipio' => ['required','string','max:100'],
            'newDireccion' => ['required','string','max:255'],
            'newVendedorId'=> ['nullable','exists:users,id'],
        ]);

        $user = User::create([
            'name'     => $this->newNombre,
            'email'    => $this->newCi,
            'password' => Hash::make($this->newTelefono),
            'tipo'     => 'cliente',
            'active'   => $this->newActive,
        ]);
        $user->assignRole('cliente');

        Cliente::create([
            'usuario_id'  => $user->id,
            'vendedor_id' => $this->newVendedorId,
            'id_ln'       => ConfiguracionCorrelativo::generarIdLN(),
            'ci'          => $this->newCi,
            'apellido'    => $this->newApellido,
            'nit'         => $this->newNit ?: null,
            'correo'      => $this->newCorreo ?: null,
            'telefono'    => $this->newTelefono,
            'ciudad'      => $this->newCiudad,
            'provincia'   => $this->newProvincia,
            'municipio'   => $this->newMunicipio,
            'direccion'   => $this->newDireccion,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Cliente creado correctamente.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = Cliente::with('usuario')->findOrFail($id);
        $this->editingId     = $id;
        $this->editCi        = $c->ci;
        $this->editNombre    = $c->usuario->name ?? '';
        $this->editApellido  = $c->apellido ?? '';
        $this->editTelefono  = $c->telefono;
        $this->editCorreo    = $c->correo ?? '';
        $this->editNit       = $c->nit ?? '';
        $this->editCiudad    = $c->ciudad;
        $this->editProvincia = $c->provincia;
        $this->editMunicipio = $c->municipio;
        $this->editDireccion = $c->direccion;
        $this->editVendedorId= $c->vendedor_id;
        $this->editActive    = $c->active;
        $this->showAddForm   = false;
    }

    public function cancelEdit(): void { $this->editingId = null; }

    public function saveEdit(): void
    {
        $cliente = Cliente::with('usuario')->findOrFail($this->editingId);

        $this->validate([
            'editCi'       => ['required','string','max:20',
                               Rule::unique('clientes','ci')->ignore($this->editingId),
                               Rule::unique('users','email')->ignore($cliente->usuario_id)],
            'editNombre'   => ['required','string','max:120'],
            'editApellido' => ['required','string','max:120'],
            'editTelefono' => ['required','string','max:30'],
            'editCorreo'   => ['nullable','email','max:191'],
            'editNit'      => ['nullable','string','max:30'],
            'editCiudad'   => ['required','string','max:100'],
            'editProvincia'=> ['required','string','max:100'],
            'editMunicipio'=> ['required','string','max:100'],
            'editDireccion'=> ['required','string','max:255'],
            'editVendedorId'=>['nullable','exists:users,id'],
        ]);

        // Actualizar usuario (CI puede cambiar → actualiza email=login)
        $cliente->usuario->update([
            'name'   => $this->editNombre,
            'email'  => $this->editCi,
            'active' => $this->editActive,
        ]);

        $cliente->update([
            'ci'          => $this->editCi,
            'apellido'    => $this->editApellido,
            'nit'         => $this->editNit ?: null,
            'correo'      => $this->editCorreo ?: null,
            'telefono'    => $this->editTelefono,
            'ciudad'      => $this->editCiudad,
            'provincia'   => $this->editProvincia,
            'municipio'   => $this->editMunicipio,
            'direccion'   => $this->editDireccion,
            'vendedor_id' => $this->editVendedorId,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Cliente actualizado.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActivo(int $id): void
    {
        $c = Cliente::with('usuario')->findOrFail($id);
        $nuevo = !$c->active;
        $c->update(['active' => $nuevo]);
        $c->usuario?->update(['active' => $nuevo]);
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $clientes = Cliente::with(['usuario', 'vendedorUsuario'])
            ->when($this->search, fn($q) =>
                $q->where('ci', 'like', "%{$this->search}%")
                  ->orWhere('id_ln', 'like', "%{$this->search}%")
                  ->orWhere('apellido', 'like', "%{$this->search}%")
                  ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterCiudad, fn($q) => $q->where('ciudad', $this->filterCiudad))
            ->when($this->filterActivo !== '', fn($q) => $q->where('active', (bool) $this->filterActivo))
            ->orderBy('id_ln')
            ->paginate(20);

        $ciudades  = Cliente::select('ciudad')->distinct()->orderBy('ciudad')->pluck('ciudad');
        $vendedores = User::where('tipo', 'vendedor')->orderBy('name')->get(['id','name']);

        $ciudadesAll    = Ciudad::orderBy('nombre')->get();
        $newCiudadObj   = Ciudad::where('nombre', $this->newCiudad)->first();
        $newProvincias  = $newCiudadObj ? Provincia::where('ciudad_id', $newCiudadObj->id)->orderBy('nombre')->get() : collect();
        $newProvObj     = Provincia::where('nombre', $this->newProvincia)->where('ciudad_id', $newCiudadObj?->id)->first();
        $newMunicipios  = $newProvObj ? Municipio::where('provincia_id', $newProvObj->id)->orderBy('nombre')->get() : collect();

        $editCiudadObj  = Ciudad::where('nombre', $this->editCiudad)->first();
        $editProvincias = $editCiudadObj ? Provincia::where('ciudad_id', $editCiudadObj->id)->orderBy('nombre')->get() : collect();
        $editProvObj    = Provincia::where('nombre', $this->editProvincia)->where('ciudad_id', $editCiudadObj?->id)->first();
        $editMunicipios = $editProvObj ? Municipio::where('provincia_id', $editProvObj->id)->orderBy('nombre')->get() : collect();

        return view('livewire.credito.cliente-manager', compact(
            'clientes', 'ciudades', 'vendedores',
            'ciudadesAll', 'newProvincias', 'newMunicipios',
            'editProvincias', 'editMunicipios'
        ));
    }
}
