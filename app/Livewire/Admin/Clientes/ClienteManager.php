<?php

namespace App\Livewire\Admin\Clientes;

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
    public string $newTelefono   = '';
    public string $newEmail      = '';
    public string $newNit        = '';
    public string $newCiudad     = '';
    public string $newProvincia  = '';
    public string $newMunicipio  = '';
    public string $newDireccion  = '';
    public ?int   $newVendedorId = null;  // user_id del vendedor
    public bool   $newActive     = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId     = null;
    public string $editNombre    = '';
    public string $editTelefono  = '';
    public string $editEmail     = '';
    public string $editNit       = '';
    public string $editCiudad    = '';
    public string $editProvincia = '';
    public string $editMunicipio = '';
    public string $editDireccion = '';
    public ?int   $editVendedorId = null;
    public bool   $editActive    = true;

    // ── Correlativo config ────────────────────────────────────────────────────
    public bool   $showCorrelativo    = false;
    public string $cfgPrefijo         = 'LN';
    public string $cfgSiguienteNumero = '1';
    public string $cfgLongitud        = '6';

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
        $this->showCorrelativo = false;
        $this->newCi         = '';
        $this->newNombre     = '';
        $this->newTelefono   = '';
        $this->newEmail      = '';
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
            'newCi'        => ['required', 'string', 'max:20', Rule::unique('clientes', 'ci')],
            'newNombre'    => 'required|string|min:2|max:120',
            'newTelefono'  => 'required|string|max:20',
            'newEmail'     => ['required', 'string', 'min:3', 'max:80',
                               'regex:/^[a-zA-Z0-9._@-]+$/',
                               Rule::unique('users', 'email')],
            'newCiudad'    => 'required|string|max:80',
            'newProvincia' => 'required|string|max:80',
            'newMunicipio' => 'required|string|max:80',
            'newDireccion' => 'required|string|max:255',
            'newVendedorId'=> 'nullable|integer|exists:users,id',
        ], [], [
            'newCi'        => 'CI',
            'newNombre'    => 'nombre completo',
            'newTelefono'  => 'teléfono',
            'newEmail'     => 'usuario',
            'newCiudad'    => 'ciudad',
            'newProvincia' => 'provincia',
            'newMunicipio' => 'municipio',
            'newDireccion' => 'dirección',
        ]);

        // 1. Crear usuario
        $user = User::create([
            'name'  => trim($this->newNombre),
            'email' => trim($this->newEmail),
            'password' => Hash::make($this->newTelefono),
            'tipo'     => 'cliente',
            'active'   => $this->newActive,
        ]);
        $user->assignRole('cliente');

        // 2. Generar ID_LN y crear cliente
        Cliente::create([
            'usuario_id'  => $user->id,
            'vendedor_id' => $this->newVendedorId,
            'id_ln'       => ConfiguracionCorrelativo::generarIdLN(),
            'ci'          => trim($this->newCi),
            'nit'         => trim($this->newNit) ?: null,
            'telefono'    => trim($this->newTelefono),
            'ciudad'      => trim($this->newCiudad),
            'provincia'   => trim($this->newProvincia),
            'municipio'   => trim($this->newMunicipio),
            'direccion'   => trim($this->newDireccion),
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
        $this->editNombre    = $c->usuario->name ?? '';
        $this->editTelefono  = $c->telefono;
        $this->editEmail     = $c->usuario->email    ?? '';
        $this->editNit       = $c->nit               ?? '';
        $this->editCiudad    = $c->ciudad;
        $this->editProvincia = $c->provincia;
        $this->editMunicipio = $c->municipio;
        $this->editDireccion = $c->direccion;
        $this->editVendedorId = $c->vendedor_id;
        $this->editActive    = (bool) $c->active;
        $this->showAddForm   = false;
        $this->showCorrelativo = false;
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
            'editNombre'    => 'required|string|min:2|max:120',
            'editTelefono'  => 'required|string|max:20',
            'editEmail'     => ['required', 'string', 'min:3', 'max:80',
                                'regex:/^[a-zA-Z0-9._@-]+$/',
                                Rule::unique('users', 'email')->ignore($c->usuario_id)],
            'editCiudad'    => 'required|string|max:80',
            'editProvincia' => 'required|string|max:80',
            'editMunicipio' => 'required|string|max:80',
            'editDireccion' => 'required|string|max:255',
            'editVendedorId'=> 'nullable|integer|exists:users,id',
        ], [], [
            'editNombre'    => 'nombre completo',
            'editTelefono'  => 'teléfono',
            'editEmail'     => 'usuario',
            'editCiudad'    => 'ciudad',
            'editProvincia' => 'provincia',
            'editMunicipio' => 'municipio',
            'editDireccion' => 'dirección',
        ]);

        // Actualizar usuario
        $c->usuario->update([
            'name'   => trim($this->editNombre),
            'email'  => trim($this->editEmail),
            'active'   => $this->editActive,
        ]);

        // Actualizar cliente
        $c->update([
            'vendedor_id' => $this->editVendedorId,
            'nit'         => trim($this->editNit) ?: null,
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
        $c->update(['active' => !$c->active]);
        $c->usuario?->update(['active' => !$c->active]);
    }

    // ── Correlativo ───────────────────────────────────────────────────────────

    public function openCorrelativo(): void
    {
        $cfg = ConfiguracionCorrelativo::where('activo', true)->first();
        $this->cfgPrefijo         = $cfg?->prefijo         ?? 'LN';
        $this->cfgSiguienteNumero = (string)($cfg?->siguiente_numero ?? 1);
        $this->cfgLongitud        = (string)($cfg?->longitud         ?? 6);
        $this->showCorrelativo    = true;
        $this->showAddForm        = false;
        $this->editingId          = null;
    }

    public function saveCorrelativo(): void
    {
        $this->validate([
            'cfgPrefijo'         => 'required|string|max:10',
            'cfgSiguienteNumero' => 'required|integer|min:1',
            'cfgLongitud'        => 'required|integer|min:1|max:10',
        ]);

        ConfiguracionCorrelativo::updateOrCreate(
            ['activo' => true],
            [
                'prefijo'          => strtoupper(trim($this->cfgPrefijo)),
                'siguiente_numero' => (int) $this->cfgSiguienteNumero,
                'longitud'         => (int) $this->cfgLongitud,
            ]
        );

        $this->showCorrelativo = false;
        session()->flash('success', 'Correlativo actualizado.');
    }

    public function cancelCorrelativo(): void { $this->showCorrelativo = false; }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $clientes = Cliente::with('usuario', 'vendedorUsuario')
            ->when($this->search, function ($q) {
                $s = $this->search;
                $q->where('ci', 'like', "%{$s}%")
                  ->orWhere('id_ln', 'like', "%{$s}%")
                  ->orWhereHas('usuario', fn($u) =>
                      $u->where('name',     'like', "%{$s}%")
                        ->orWhere('apellido','like', "%{$s}%")
                  );
            })
            ->when($this->filterCiudad, fn($q) => $q->where('ciudad', $this->filterCiudad))
            ->when($this->filterActivo !== '', fn($q) => $q->where('active', (bool)$this->filterActivo))
            ->orderByDesc('id')
            ->paginate(20);

        $ciudades  = Cliente::select('ciudad')->distinct()->orderBy('ciudad')->pluck('ciudad');
        $vendedores = User::where('tipo', 'vendedor')->where('active', true)->orderBy('name')->get(['id','name']);
        $esVendedor = auth()->user()->hasRole('vendedor');

        $ciudadesAll    = Ciudad::orderBy('nombre')->get();
        $newCiudadObj   = Ciudad::where('nombre', $this->newCiudad)->first();
        $newProvincias  = $newCiudadObj ? Provincia::where('ciudad_id', $newCiudadObj->id)->orderBy('nombre')->get() : collect();
        $newProvObj     = Provincia::where('nombre', $this->newProvincia)->where('ciudad_id', $newCiudadObj?->id)->first();
        $newMunicipios  = $newProvObj ? Municipio::where('provincia_id', $newProvObj->id)->orderBy('nombre')->get() : collect();

        $editCiudadObj  = Ciudad::where('nombre', $this->editCiudad)->first();
        $editProvincias = $editCiudadObj ? Provincia::where('ciudad_id', $editCiudadObj->id)->orderBy('nombre')->get() : collect();
        $editProvObj    = Provincia::where('nombre', $this->editProvincia)->where('ciudad_id', $editCiudadObj?->id)->first();
        $editMunicipios = $editProvObj ? Municipio::where('provincia_id', $editProvObj->id)->orderBy('nombre')->get() : collect();

        return view('livewire.admin.clientes.cliente-manager',
            compact('clientes', 'ciudades', 'vendedores', 'esVendedor',
                    'ciudadesAll', 'newProvincias', 'newMunicipios',
                    'editProvincias', 'editMunicipios'));
    }
}
