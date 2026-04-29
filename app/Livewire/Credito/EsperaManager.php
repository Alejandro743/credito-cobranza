<?php

namespace App\Livewire\Credito;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class EsperaManager extends Component
{
    use WithPagination;

    public string $mode    = 'list';
    public string $search  = '';
    public ?int   $viewingId = null;

    public function updatingSearch(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->viewingId = $id;
        $this->mode = 'detail';
    }

    public function tomarRevision(int $id): void
    {
        $pedido = Pedido::where('id', $id)->where('estado', 'en_espera')->firstOrFail();
        $pedido->update([
            'estado'      => 'revision',
            'revisado_por' => auth()->id(),
        ]);
        session()->flash('success', 'Pedido tomado para revisión. Ya aparece en tu bandeja.');
        $this->backToList();
    }

    public function backToList(): void
    {
        $this->viewingId = null;
        $this->mode = 'list';
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user'])
            ->where('estado', 'en_espera')
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

        return view('livewire.credito.espera-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
