<?php

namespace App\Livewire\Credito;

use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Pedido;
use App\Models\Provincia;
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

    // Documentos subibles
    public $docAnversoCi  = null;
    public $docReversoCi  = null;
    public $docAnversoDoc = null;
    public $docReversoDoc = null;
    public $docAvisoLuz   = null;

    // Dirección de entrega
    public string $editCiudad    = '';
    public string $editProvincia = '';
    public string $editMunicipio = '';
    public string $editDireccion = '';
    public string $editReferencia = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function updatedEditCiudad(): void   { $this->editProvincia = ''; $this->editMunicipio = ''; }
    public function updatedEditProvincia(): void { $this->editMunicipio = ''; }

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
        $this->editCiudad    = $pedido?->entrega_ciudad    ?? '';
        $this->editProvincia = $pedido?->entrega_provincia ?? '';
        $this->editMunicipio = $pedido?->entrega_municipio ?? '';
        $this->editDireccion = $pedido?->entrega_direccion ?? '';
        $this->editReferencia= $pedido?->entrega_referencia ?? '';

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

    public function guardarDireccion(): void
    {
        $this->validate([
            'editDireccion' => 'required|string|max:500',
            'editCiudad'    => 'nullable|string|max:150',
            'editProvincia' => 'nullable|string|max:150',
            'editMunicipio' => 'nullable|string|max:150',
            'editReferencia'=> 'nullable|string|max:500',
        ]);

        Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail()
            ->update([
                'entrega_ciudad'    => trim($this->editCiudad)    ?: null,
                'entrega_provincia' => trim($this->editProvincia) ?: null,
                'entrega_municipio' => trim($this->editMunicipio) ?: null,
                'entrega_direccion' => trim($this->editDireccion),
                'entrega_referencia'=> trim($this->editReferencia) ?: null,
            ]);

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
            ->orderByDesc('created_at')
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

        return view('livewire.credito.revision-manager', compact('pedidos', 'pedidoDetalle', 'ciudadesAll', 'editProvincias', 'editMunicipios'));
    }
}
