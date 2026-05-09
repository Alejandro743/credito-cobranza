<?php

namespace App\Livewire\Credito;

use App\Models\Pedido;
use App\Models\PedidoCierre;
use Livewire\Component;
use Livewire\WithPagination;

class CerradoManager extends Component
{
    use WithPagination;

    public string $mode    = 'list';
    public string $search  = '';
    public ?int   $viewingId = null;

    // Reversión
    public bool   $confirmandoReversion = false;
    public string $motivoReversion      = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->viewingId            = $id;
        $this->confirmandoReversion = false;
        $this->motivoReversion      = '';
        $this->mode                 = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId            = null;
        $this->confirmandoReversion = false;
        $this->motivoReversion      = '';
        $this->mode                 = 'list';
    }

    public function revertir(): void
    {
        $this->validate([
            'motivoReversion' => 'required|min:5',
        ], [
            'motivoReversion.required' => 'Ingresá el motivo de la reversión.',
            'motivoReversion.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::where('estado', 'cerrado')->findOrFail($this->viewingId);

        $cierre = PedidoCierre::where('pedido_id', $pedido->id)
            ->whereNull('revertido_at')
            ->latest()
            ->firstOrFail();

        // Revertir plan de pagos
        $pedido->planes()->where('id', $cierre->plan_pago_id)->update(['estado' => 'activo']);

        // Revertir pedido
        $pedido->update(['estado' => 'aprobado']);

        // Marcar cierre como revertido
        $cierre->update([
            'revertido_at'     => now(),
            'revertido_por'    => auth()->id(),
            'motivo_reversion' => $this->motivoReversion,
        ]);

        session()->flash('success', 'Cierre revertido. El pedido volvió a Aprobado.');
        $this->backToList();
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user', 'cierre.motivoCierre', 'cierre.cerradoPor'])
            ->where('estado', 'cerrado')
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name', 'like', "%{$this->search}%")
            )->orWhere('numero', 'like', "%{$this->search}%"))
            ->orderByDesc('updated_at')
            ->paginate(15);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product',
                'planes.cuotas',
                'cierre.motivoCierre', 'cierre.cerradoPor',
            ])->find($this->viewingId);
        }

        return view('livewire.credito.cerrado-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
