<?php

namespace App\Livewire\Vendedor;

use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\ConfiguracionCorrelativo;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class ClienteManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Filtros por columna ──────────────────────────────────────────────────
    public string $colFilterIdLn     = '';
    public string $colFilterCi       = '';
    public string $colFilterNombre   = '';
    public string $colFilterApellido = '';
    public string $colFilterTelefono  = '';
    public string $colFilterNit       = '';
    public string $colFilterCorreo    = '';
    public string $colFilterCiudad    = '';
    public string $colFilterProvincia = '';
    public string $colFilterMunicipio = '';
    public string $colFilterDireccion = '';
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
    public function updatingColFilterEstado():    void { $this->resetPage(); }

    public ?int $selectedClienteId = null;

    public function selectCliente(int $id): void
    {
        $this->selectedClienteId = $this->selectedClienteId === $id ? null : $id;
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
    public bool   $newActive     = true;

    // ── Ver detalle (modal) ───────────────────────────────────────────────────
    public ?int   $viewingId     = null;

    public function ver(int $id): void
    {
        $this->viewingId = $id;
    }

    public function verSeleccionado(): void
    {
        if ($this->selectedClienteId) {
            $this->ver($this->selectedClienteId);
        }
    }

    public function closeModal(): void
    {
        $this->viewingId = null;
    }

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId     = null;
    public string $editNombre    = '';
    public string $editApellido  = '';
    public string $editTelefono  = '';
    public string $editCorreo    = '';
    public string $editNit       = '';
    public string $editCiudad    = '';
    public string $editProvincia = '';
    public string $editMunicipio = '';
    public string $editDireccion = '';
    public bool   $editActive    = true;

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
        $this->showAddForm  = true;
        $this->editingId    = null;
        $this->newCi        = '';
        $this->newNombre    = '';
        $this->newApellido  = '';
        $this->newTelefono  = '';
        $this->newCorreo    = '';
        $this->newNit       = '';
        $this->newCiudad    = '';
        $this->newProvincia = '';
        $this->newMunicipio = '';
        $this->newDireccion = '';
        $this->newActive    = true;
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
            'newCi'        => ['required','string','max:20',
                               Rule::unique('clientes','ci'),
                               Rule::unique('users','email')],
            'newNombre'    => ['required','string','min:2','max:120'],
            'newApellido'  => ['required','string','min:2','max:120'],
            'newTelefono'  => ['required','string','max:30'],
            'newCorreo'    => ['nullable','email','max:191'],
            'newNit'       => ['nullable','string','max:30'],
            'newCiudad'    => ['required','string','max:100'],
            'newProvincia' => ['required','string','max:100'],
            'newMunicipio' => ['required','string','max:100'],
            'newDireccion' => ['required','string','max:255'],
        ], [], [
            'newCi'        => 'CI',
            'newNombre'    => 'nombre',
            'newApellido'  => 'apellido',
            'newTelefono'  => 'teléfono',
            'newCiudad'    => 'ciudad',
            'newProvincia' => 'provincia',
            'newMunicipio' => 'municipio',
            'newDireccion' => 'dirección',
        ]);

        // 1. Crear usuario — el CI es el usuario de acceso (email)
        $user = User::create([
            'name'     => trim($this->newNombre),
            'email'    => trim($this->newCi),
            'password' => Hash::make($this->newTelefono),
            'tipo'     => 'cliente',
            'active'   => $this->newActive,
        ]);
        $user->assignRole('cliente');

        // 2. Crear cliente
        Cliente::create([
            'usuario_id'  => $user->id,
            'vendedor_id' => auth()->id(),
            'id_ln'       => ConfiguracionCorrelativo::generarIdLN(),
            'ci'          => trim($this->newCi),
            'apellido'    => trim($this->newApellido),
            'nit'         => trim($this->newNit) ?: null,
            'correo'      => trim($this->newCorreo) ?: null,
            'telefono'    => trim($this->newTelefono),
            'ciudad'      => trim($this->newCiudad),
            'provincia'   => trim($this->newProvincia),
            'municipio'   => trim($this->newMunicipio),
            'direccion'   => trim($this->newDireccion),
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Cliente registrado correctamente.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = Cliente::with('usuario')->findOrFail($id);
        $this->editingId     = $id;
        $this->editNombre    = $c->usuario->name ?? '';
        $this->editApellido  = $c->apellido ?? '';
        $this->editTelefono  = $c->telefono;
        $this->editCorreo    = $c->correo ?? '';
        $this->editNit       = $c->nit ?? '';
        $this->editCiudad    = $c->ciudad;
        $this->editProvincia = $c->provincia;
        $this->editMunicipio = $c->municipio;
        $this->editDireccion = $c->direccion;
        $this->editActive    = (bool) $c->active;
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
        $c = Cliente::with('usuario')->findOrFail($this->editingId);

        $this->validate([
            'editNombre'   => ['required','string','min:2','max:120'],
            'editApellido' => ['required','string','min:2','max:120'],
            'editTelefono' => ['required','string','max:30'],
            'editCorreo'   => ['nullable','email','max:191'],
            'editNit'      => ['nullable','string','max:30'],
            'editCiudad'   => ['required','string','max:100'],
            'editProvincia'=> ['required','string','max:100'],
            'editMunicipio'=> ['required','string','max:100'],
            'editDireccion'=> ['required','string','max:255'],
        ], [], [
            'editNombre'   => 'nombre',
            'editApellido' => 'apellido',
            'editTelefono' => 'teléfono',
            'editCiudad'   => 'ciudad',
            'editProvincia'=> 'provincia',
            'editMunicipio'=> 'municipio',
            'editDireccion'=> 'dirección',
        ]);

        $c->usuario->update([
            'name'   => trim($this->editNombre),
            'active' => $this->editActive,
        ]);

        $c->update([
            'apellido'    => trim($this->editApellido),
            'nit'         => trim($this->editNit) ?: null,
            'correo'      => trim($this->editCorreo) ?: null,
            'telefono'    => trim($this->editTelefono),
            'ciudad'      => trim($this->editCiudad),
            'provincia'   => trim($this->editProvincia),
            'municipio'   => trim($this->editMunicipio),
            'direccion'   => trim($this->editDireccion),
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Cliente actualizado.');
    }

    public function toggleActive(int $id): void
    {
        $c = Cliente::with('usuario')->findOrFail($id);
        $newVal = !$c->active;
        $c->update(['active' => $newVal]);
        $c->usuario?->update(['active' => $newVal]);
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $clientes = Cliente::with('usuario')
            ->where('vendedor_id', auth()->id())
            ->when($this->colFilterIdLn,     fn($q) => $q->where('id_ln', 'like', "%{$this->colFilterIdLn}%"))
            ->when($this->colFilterCi,       fn($q) => $q->where('ci', 'like', "%{$this->colFilterCi}%"))
            ->when($this->colFilterNombre,   fn($q) => $q->whereHas('usuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterNombre}%")))
            ->when($this->colFilterApellido, fn($q) => $q->where('apellido', 'like', "%{$this->colFilterApellido}%"))
            ->when($this->colFilterTelefono,  fn($q) => $q->where('telefono', 'like', "%{$this->colFilterTelefono}%"))
            ->when($this->colFilterNit,       fn($q) => $q->where('nit', 'like', "%{$this->colFilterNit}%"))
            ->when($this->colFilterCorreo,    fn($q) => $q->where('correo', 'like', "%{$this->colFilterCorreo}%"))
            ->when($this->colFilterCiudad,    fn($q) => $q->where('ciudad', 'like', "%{$this->colFilterCiudad}%"))
            ->when($this->colFilterProvincia, fn($q) => $q->where('provincia', 'like', "%{$this->colFilterProvincia}%"))
            ->when($this->colFilterMunicipio, fn($q) => $q->where('municipio', 'like', "%{$this->colFilterMunicipio}%"))
            ->when($this->colFilterDireccion, fn($q) => $q->where('direccion', 'like', "%{$this->colFilterDireccion}%"))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('active', $this->colFilterEstado === '1'))
            ->orderByDesc('active')
            ->orderBy('apellido')
            ->paginate(15);

        $ciudadesAll    = Ciudad::orderBy('nombre')->get();
        $newCiudadObj   = Ciudad::where('nombre', $this->newCiudad)->first();
        $newProvincias  = $newCiudadObj ? Provincia::where('ciudad_id', $newCiudadObj->id)->orderBy('nombre')->get() : collect();
        $newProvObj     = Provincia::where('nombre', $this->newProvincia)->where('ciudad_id', $newCiudadObj?->id)->first();
        $newMunicipios  = $newProvObj ? Municipio::where('provincia_id', $newProvObj->id)->orderBy('nombre')->get() : collect();

        $editCiudadObj  = Ciudad::where('nombre', $this->editCiudad)->first();
        $editProvincias = $editCiudadObj ? Provincia::where('ciudad_id', $editCiudadObj->id)->orderBy('nombre')->get() : collect();
        $editProvObj    = Provincia::where('nombre', $this->editProvincia)->where('ciudad_id', $editCiudadObj?->id)->first();
        $editMunicipios = $editProvObj ? Municipio::where('provincia_id', $editProvObj->id)->orderBy('nombre')->get() : collect();

        $viewingCliente = $this->viewingId
            ? Cliente::with('usuario')->find($this->viewingId)
            : null;

        return view('livewire.vendedor.cliente-manager', compact(
            'clientes', 'ciudadesAll',
            'newProvincias', 'newMunicipios',
            'editProvincias', 'editMunicipios',
            'viewingCliente'
        ));
    }
}
