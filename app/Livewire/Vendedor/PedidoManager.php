<?php

namespace App\Livewire\Vendedor;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\Pedido;
use App\Models\Vendedor;
use Livewire\Component;
use Livewire\WithPagination;

class PedidoManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode         = 'list';
    public string $search       = '';
    public string $filtroEstado = '';
    public ?int   $viewingId    = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedFiltroEstado(): void { $this->resetPage(); }

    public function ver(int $id): void
    {
        $this->viewingId = $id;
        $this->mode = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId = null;
        $this->mode = 'list';
    }

    public function render()
    {
        $vendedorId = Vendedor::delUsuario()?->id;

        $pedidos = Pedido::with(['cliente.usuario'])
            ->when($vendedorId, fn($q) => $q->where('vendedor_id', $vendedorId))
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name',     'like', "%{$this->search}%")
                  ->orWhere('apellido','like', "%{$this->search}%")
            ))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->orderByDesc('created_at')
            ->paginate(10);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario',
                'items.product',
                'planPago.cuotas',
            ])->find($this->viewingId);
        }

        return view('livewire.vendedor.pedido-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
