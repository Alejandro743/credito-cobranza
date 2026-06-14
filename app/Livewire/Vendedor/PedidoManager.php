<?php
namespace App\Livewire\Vendedor;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\Pedido;
use App\Models\Vendedor;
use Illuminate\Support\Facades\DB;
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

        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1) as ciclo_code';

        $pedidos = Pedido::with(['cliente.usuario'])
            ->addSelect(DB::raw($cicloSub))
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
