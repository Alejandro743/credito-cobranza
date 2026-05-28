<?php
namespace App\Livewire\Vendedor;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\Pedido;
use App\Models\Vendedor;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PedidoManager extends Component
{
    use WithPagination, WithoutUrlPagination, HasModuleColor;

    public string $search       = '';
    public string $filtroEstado = '';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $vendedorId = Vendedor::delUsuario()?->id;

        $pedidos = Pedido::with(['cliente.usuario'])
            ->when($vendedorId, fn($q) => $q->where('vendedor_id', $vendedorId))
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name', 'like', "%{$this->search}%")
                  ->orWhere('apellido', 'like', "%{$this->search}%")
            ))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.vendedor.pedido-manager', compact('pedidos'));
    }
}
