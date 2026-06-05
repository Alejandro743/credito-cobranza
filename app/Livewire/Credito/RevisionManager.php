<?php

namespace App\Livewire\Credito;

use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Pedido;
use App\Models\Provincia;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RevisionManager extends Component
{
    use WithPagination, WithFileUploads;

    public string $mode               = 'list';
    public string $search             = '';
    public ?int   $viewingId          = null;
    public bool   $confirmandoRechazo = false;
    public string $notaRechazo        = '';
    public string $sortBy             = '';
    public string $sortDir            = 'asc';

    // Documentos subibles
    public $docAnversoCi  = null;
    public $docReversoCi  = null;
    public $docAnversoDoc = null;
    public $docReversoDoc = null;
    public $docAvisoLuz   = null;

    // Dirección de entrega
    public string $editTipoEntrega = 'domicilio';
    public string $editCiudad      = '';
    public string $editProvincia   = '';
    public string $editMunicipio   = '';
    public string $editDireccion   = '';
    public string $editReferencia  = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function updatedEditCiudad(): void   { $this->editProvincia = ''; $this->editMunicipio = ''; }
    public function updatedEditProvincia(): void { $this->editMunicipio = ''; }

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

    public function ver(int $id): void
    {
        $this->viewingId          = $id;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->docAnversoCi       = null;
        $this->docReversoCi       = null;
        $this->docAnversoDoc      = null;
        $this->docReversoDoc      = null;
        $this->docAvisoLuz        = null;

        $pedido = Pedido::find($id);
        $this->editTipoEntrega = $pedido?->tipo_entrega     ?? 'domicilio';
        $this->editCiudad      = $pedido?->entrega_ciudad    ?? '';
        $this->editProvincia   = $pedido?->entrega_provincia ?? '';
        $this->editMunicipio   = $pedido?->entrega_municipio ?? '';
        $this->editDireccion   = $pedido?->entrega_direccion ?? '';
        $this->editReferencia  = $pedido?->entrega_referencia ?? '';

        $this->mode = 'detail';
    }

    public function subirDocumento(string $campo): void
    {
        $propMap = [
            'doc_anverso_ci'  => 'docAnversoCi',
            'doc_reverso_ci'  => 'docReversoCi',
            'doc_anverso_doc' => 'docAnversoDoc',
            'doc_reverso_doc' => 'docReversoDoc',
            'doc_aviso_luz'   => 'docAvisoLuz',
        ];

        $prop = $propMap[$campo] ?? null;
        if (!$prop || !$this->$prop) return;

        $this->validateOnly($prop, [
            $prop => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pedido = Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail();

        $path = $this->$prop->store("pedidos/{$pedido->id}/docs", 'public');
        $pedido->update([$campo => $path]);

        $this->$prop = null;
    }

    public function resetDireccionEdit(): void
    {
        if (!$this->viewingId) return;
        $pedido = Pedido::find($this->viewingId);
        $this->editTipoEntrega = $pedido?->tipo_entrega      ?? 'domicilio';
        $this->editCiudad      = $pedido?->entrega_ciudad    ?? '';
        $this->editProvincia   = $pedido?->entrega_provincia ?? '';
        $this->editMunicipio   = $pedido?->entrega_municipio ?? '';
        $this->editDireccion   = $pedido?->entrega_direccion ?? '';
        $this->editReferencia  = $pedido?->entrega_referencia ?? '';
        $this->dispatch('reset-dir', tipo: $this->editTipoEntrega);
    }

    public function guardarDireccion(string $tipoEntrega = ''): void
    {
        if ($tipoEntrega) $this->editTipoEntrega = $tipoEntrega;

        $pedido = Pedido::with('cliente')
            ->where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail();

        if ($this->editTipoEntrega === 'domicilio') {
            $pedido->update([
                'tipo_entrega'      => 'domicilio',
                'entrega_ciudad'    => $pedido->cliente->ciudad,
                'entrega_provincia' => $pedido->cliente->provincia,
                'entrega_municipio' => $pedido->cliente->municipio,
                'entrega_direccion' => $pedido->cliente->direccion ?? '',
                'entrega_referencia'=> null,
            ]);
        } else {
            $this->validate([
                'editDireccion' => 'required|string|max:500',
                'editCiudad'    => 'nullable|string|max:150',
                'editProvincia' => 'nullable|string|max:150',
                'editMunicipio' => 'nullable|string|max:150',
                'editReferencia'=> 'nullable|string|max:500',
            ]);

            $pedido->update([
                'tipo_entrega'      => 'nuevo',
                'entrega_ciudad'    => trim($this->editCiudad)    ?: null,
                'entrega_provincia' => trim($this->editProvincia) ?: null,
                'entrega_municipio' => trim($this->editMunicipio) ?: null,
                'entrega_direccion' => trim($this->editDireccion),
                'entrega_referencia'=> trim($this->editReferencia) ?: null,
            ]);
        }

        $this->dispatch('direccion-guardada');
    }

    public function devolverEspera(): void
    {
        Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail()
            ->update(['estado' => 'en_espera', 'revisado_por' => null]);

        session()->flash('success', 'Pedido devuelto a En Espera.');
        $this->backToList();
    }

    public function aprobar(): void
    {
        Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail()
            ->update(['estado' => 'aprobado', 'notas' => null]);

        session()->flash('success', 'Pedido aprobado correctamente.');
        $this->backToList();
    }

    public function rechazar(): void
    {
        $this->validate(['notaRechazo' => 'required|min:5'], [
            'notaRechazo.required' => 'Ingresá el motivo del rechazo.',
            'notaRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail()
            ->update(['estado' => 'rechazado', 'notas' => $this->notaRechazo]);

        session()->flash('success', 'Pedido rechazado.');
        $this->backToList();
    }

    public function backToList(): void
    {
        $this->viewingId          = null;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->docAnversoCi       = null;
        $this->docReversoCi       = null;
        $this->docAnversoDoc      = null;
        $this->docReversoDoc      = null;
        $this->docAvisoLuz        = null;
        $this->editTipoEntrega    = 'domicilio';
        $this->editCiudad         = '';
        $this->editProvincia      = '';
        $this->editMunicipio      = '';
        $this->editDireccion      = '';
        $this->editReferencia     = '';
        $this->mode               = 'list';
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user'])
            ->where('estado', 'revision')
            ->where('revisado_por', auth()->id())
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name', 'like', "%{$this->search}%")
            )->orWhere('numero', 'like', "%{$this->search}%"))
            ->when($this->sortBy === 'cliente',  fn($q) => $q->orderBy(DB::table('users')->join('clientes','clientes.usuario_id','=','users.id')->whereColumn('clientes.id','pedidos.cliente_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'vendedor', fn($q) => $q->orderBy(DB::table('users')->join('vendedores','vendedores.user_id','=','users.id')->whereColumn('vendedores.id','pedidos.vendedor_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'ci',       fn($q) => $q->orderBy(DB::table('clientes')->whereColumn('clientes.id','pedidos.cliente_id')->select('ci'), $this->sortDir))
            ->when(in_array($this->sortBy, ['numero','fecha','total']), fn($q) => $q->orderBy(match($this->sortBy) { 'numero'=>'pedidos.numero', 'fecha'=>'pedidos.created_at', 'total'=>'pedidos.total_pagar' }, $this->sortDir))
            ->when(!$this->sortBy, fn($q) => $q->orderByDesc('created_at'))
            ->paginate(15);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product', 'planPago.cuotas',
            ])->find($this->viewingId);
        }

        $ciudadesAll     = Ciudad::orderBy('nombre')->get();
        $ciudadObj       = Ciudad::where('nombre', $this->editCiudad)->first();
        $editProvincias  = $ciudadObj ? Provincia::where('ciudad_id', $ciudadObj->id)->orderBy('nombre')->get() : collect();
        $provObj         = Provincia::where('nombre', $this->editProvincia)->where('ciudad_id', $ciudadObj?->id)->first();
        $editMunicipios  = $provObj ? Municipio::where('provincia_id', $provObj->id)->orderBy('nombre')->get() : collect();

        $editTipoEntrega = $this->editTipoEntrega;

        return view('livewire.credito.revision-manager', compact('pedidos', 'pedidoDetalle', 'ciudadesAll', 'editProvincias', 'editMunicipios', 'editTipoEntrega'));
    }
}
