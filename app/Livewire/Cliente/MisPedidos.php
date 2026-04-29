<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class MisPedidos extends Component
{
    use WithPagination;

    public string $mode       = 'list';
    public ?int   $viewingId  = null;

    public bool $sinCliente  = false;
    protected ?int $clienteId = null;

    public function mount(): void
    {
        $cliente = Cliente::where('usuario_id', auth()->id())->first();
        $this->clienteId = $cliente?->id;
        $this->sinCliente = ($this->clienteId === null);
    }

    public function ver(int $id): void
    {
        // Verificar que el pedido pertenece a este cliente
        if (Pedido::where('id', $id)->where('cliente_id', $this->clienteId)->exists()) {
            $this->viewingId = $id;
            $this->mode = 'detail';
        }
    }

    public function backToList(): void
    {
        $this->viewingId = null;
        $this->mode = 'list';
    }

    public function render()
    {
        $pedidos = collect();
        $pedidoDetalle = null;

        if ($this->clienteId) {
            $pedidos = Pedido::where('cliente_id', $this->clienteId)
                ->with('planPago')
                ->orderByDesc('created_at')
                ->paginate(10);

            if ($this->mode === 'detail' && $this->viewingId) {
                $pedidoDetalle = Pedido::with([
                    'items.product',
                    'planPago.cuotas',
                ])->where('cliente_id', $this->clienteId)
                  ->find($this->viewingId);
            }
        }

        return view('livewire.cliente.mis-pedidos', compact('pedidos', 'pedidoDetalle'));
    }
}
