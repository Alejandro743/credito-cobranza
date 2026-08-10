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

class ClienteManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Ordenación ────────────────────────────────────────────────────────────
    public string $sortBy  = '';
    public string $sortDir = 'asc';

    // ── Filtros por columna ──────────────────────────────────────────────────
    public string $colFilterIdLn      = '';
    public string $colFilterCi        = '';
    public string $colFilterNombre    = '';
    public string $colFilterApellido  = '';
    public string $colFilterTelefono  = '';
    public string $colFilterNit       = '';
    public string $colFilterCorreo    = '';
    public string $colFilterCiudad    = '';
    public string $colFilterProvincia = '';
    public string $colFilterMunicipio = '';
    public string $colFilterDireccion = '';
    public string $colFilterVendedor  = '';
    public string $colFilterEstado    = '';

    public function updatingColFilterIdLn():      void { $this->resetPage(); }
    public function updatingColFilterCi():        void { $this->resetPage(); }
    public function updatingColFilterNombre():    void { $this->resetPage(); }
    public function updatingColFilterApellido():  void { $this->resetPage(); }
    public function updatingColFilterTelefono():  void { $this->resetPage(); }
    public function updatingColFilterNit():       void { $this->resetPage(); }
    public function updatingColFilterCorreo():    void { $this->resetPage(); }
    public function updatingColFilterCiudad():    void { $this->resetPage(); }
    public function updatingColFilterProvincia(): void { $this->resetPage(); }
    public function updatingColFilterMunicipio(): void { $this->resetPage(); }
    public function updatingColFilterDireccion(): void { $this->resetPage(); }
    public function updatingColFilterVendedor():  void { $this->resetPage(); }
    public function updatingColFilterEstado():    void { $this->resetPage(); }

    public ?int $selectedClienteId = null;

    public function selectCliente(int $id): void
    {
        $this->selectedClienteId = $this->selectedClienteId === $id ? null : $id;
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

    // ── Modal Ver ────────────────────────────────────────────────────────────
    public ?int $viewingClienteId = null;

    public function openViewModal(int $id): void { $this->viewingClienteId = $id; }
    public function closeViewModal(): void       { $this->viewingClienteId = null; }

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
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

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

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $clientes = Cliente::with(['usuario', 'vendedorUsuario'])
            ->when($this->colFilterIdLn,     fn($q) => $q->where('id_ln', 'like', "%{$this->colFilterIdLn}%"))
            ->when($this->colFilterCi,       fn($q) => $q->where('ci', 'like', "%{$this->colFilterCi}%"))
            ->when($this->colFilterNombre,   fn($q) => $q->whereHas('usuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterNombre}%")))
            ->when($this->colFilterApellido, fn($q) => $q->where('apellido', 'like', "%{$this->colFilterApellido}%"))
            ->when($this->colFilterTelefono, fn($q) => $q->where('telefono', 'like', "%{$this->colFilterTelefono}%"))
            ->when($this->colFilterNit,       fn($q) => $q->where('nit', 'like', "%{$this->colFilterNit}%"))
            ->when($this->colFilterCorreo,    fn($q) => $q->where('correo', 'like', "%{$this->colFilterCorreo}%"))
            ->when($this->colFilterCiudad,    fn($q) => $q->where('ciudad', 'like', "%{$this->colFilterCiudad}%"))
            ->when($this->colFilterProvincia, fn($q) => $q->where('provincia', 'like', "%{$this->colFilterProvincia}%"))
            ->when($this->colFilterMunicipio, fn($q) => $q->where('municipio', 'like', "%{$this->colFilterMunicipio}%"))
            ->when($this->colFilterDireccion, fn($q) => $q->where('direccion', 'like', "%{$this->colFilterDireccion}%"))
            ->when($this->colFilterVendedor,  fn($q) => $q->whereHas('vendedorUsuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterVendedor}%")))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('active', $this->colFilterEstado === '1'))
            ->when($this->sortBy === 'nombre',   fn($q) => $q->orderBy(User::select('name')->whereColumn('id', 'clientes.usuario_id'), $this->sortDir))
            ->when($this->sortBy === 'vendedor',  fn($q) => $q->orderBy(User::select('name')->whereColumn('id', 'clientes.vendedor_id'), $this->sortDir))
            ->when($this->sortBy && !in_array($this->sortBy, ['nombre','vendedor']), fn($q) => $q->orderBy($this->sortBy, $this->sortDir))
            ->when(!$this->sortBy, fn($q) => $q->orderByDesc('active')->orderBy('apellido'))
            ->paginate(20);

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

        $viewingClienteId = $this->viewingClienteId;

        return view('livewire.credito.cliente-manager', compact(
            'clientes', 'vendedores',
            'ciudadesAll', 'newProvincias', 'newMunicipios',
            'editProvincias', 'editMunicipios',
            'viewingClienteId'
        ));
    }
}
