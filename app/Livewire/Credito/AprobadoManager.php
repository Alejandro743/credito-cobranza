<?php

namespace App\Livewire\Credito;

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

    public function updatingSearch(): void      { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->viewingId          = $id;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->mode               = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId          = null;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
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

        return view('livewire.credito.aprobado-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
