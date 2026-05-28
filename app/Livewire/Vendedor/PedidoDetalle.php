<?php
namespace App\Livewire\Vendedor;

use App\Models\Pedido;
use Livewire\Component;

class PedidoDetalle extends Component
{
    public int $pedidoId;

    public function mount(int $pedidoId): void
    {
        $this->pedidoId = $pedidoId;
    }

    public function render()
    {
        $pedido = Pedido::with([
            'cliente.usuario',
            'items.product',
            'planPago.cuotas',
        ])->findOrFail($this->pedidoId);

        return view('livewire.vendedor.pedido-detalle', compact('pedido'));
    }
}
