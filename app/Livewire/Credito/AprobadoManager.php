<?php

namespace App\Livewire\Credito;

use App\Models\MotivoCierre;
use App\Models\PedidoCierre;
use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class AprobadoManager extends Component
{
    use WithPagination;

    public string $mode              = 'list';
    public string $search            = '';
    public string $filtroEstado      = '';
    public ?int   $viewingId         = null;
    public bool   $confirmandoRechazo = false;
    public string $notaRechazo       = '';

    // Cierre de crédito
    public bool   $confirmandoCierre  = false;
    public ?int   $motivoCierreId     = null;
    public string $observacionCierre  = '';

    public function updatingSearch(): void      { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->viewingId          = $id;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->confirmandoCierre  = false;
        $this->motivoCierreId     = null;
        $this->observacionCierre  = '';
        $this->mode               = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId          = null;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->confirmandoCierre  = false;
        $this->motivoCierreId     = null;
        $this->observacionCierre  = '';
        $this->mode               = 'list';
    }

    public function devolverRevision(): void
    {
        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        // Si venía de rechazado, la nota de rechazo se borra
        $pedido->update(['estado' => 'revision', 'notas' => null]);
        session()->flash('success', 'Pedido devuelto a Revisión.');
        $this->backToList();
    }

    public function aprobar(): void
    {
        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        // Si venía de rechazado, la nota de rechazo se borra
        $pedido->update(['estado' => 'aprobado', 'notas' => null]);
        session()->flash('success', 'Pedido aprobado.');
        $this->backToList();
    }

    public function rechazar(): void
    {
        $this->validate(['notaRechazo' => 'required|min:5'], [
            'notaRechazo.required' => 'Ingresá el motivo del rechazo.',
            'notaRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        $pedido->update(['estado' => 'rechazado', 'notas' => $this->notaRechazo]);
        session()->flash('success', 'Pedido rechazado.');
        $this->backToList();
    }

    public function cerrar(): void
    {
        $this->validate([
            'motivoCierreId'    => 'required|exists:motivo_cierres,id',
            'observacionCierre' => 'nullable|string|max:500',
        ], [
            'motivoCierreId.required' => 'Seleccioná un motivo de cierre.',
            'motivoCierreId.exists'   => 'Motivo inválido.',
        ]);

        $pedido = Pedido::where('estado', 'aprobado')->findOrFail($this->viewingId);
        $plan   = $pedido->planPago;

        // Cerrar plan de pagos
        $plan->update(['estado' => 'cerrado']);

        // Cerrar pedido
        $pedido->update(['estado' => 'cerrado']);

        // Registrar transacción de cierre
        PedidoCierre::create([
            'pedido_id'       => $pedido->id,
            'plan_pago_id'    => $plan->id,
            'motivo_cierre_id'=> $this->motivoCierreId,
            'observacion'     => $this->observacionCierre ?: null,
            'cerrado_por'     => auth()->id(),
        ]);

        session()->flash('success', 'Crédito cerrado correctamente.');
        $this->backToList();
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user'])
            ->whereIn('estado', ['aprobado', 'rechazado'])
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name', 'like', "%{$this->search}%")
            )->orWhere('numero', 'like', "%{$this->search}%"))
            ->orderByDesc('updated_at')
            ->paginate(15);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product', 'planPago.cuotas',
            ])->find($this->viewingId);
        }

        $motivosCierre = MotivoCierre::activos();

        return view('livewire.credito.aprobado-manager', compact('pedidos', 'pedidoDetalle', 'motivosCierre'));
    }
}
