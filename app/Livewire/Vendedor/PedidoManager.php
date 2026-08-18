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

    public string $colFilterNumero    = '';
    public string $colFilterCiclo     = '';
    public string $colFilterCi        = '';
    public string $colFilterNombre    = '';
    public string $colFilterEstado    = '';
    public string $colFilterFecha     = '';
    public string $colFilterFechaCont = '';

    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    public ?int $selectedPedidoId = null;

    public string $mode      = 'list';
    public ?int   $viewingId = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function ver(int $id): void
    {
        $this->viewingId        = $id;
        $this->selectedPedidoId = $id;
        $this->mode              = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId = null;
        $this->mode       = 'list';
    }

    public function updatingColFilterNumero(): void    { $this->resetPage(); }
    public function updatingColFilterCiclo(): void     { $this->resetPage(); }
    public function updatingColFilterCi(): void        { $this->resetPage(); }
    public function updatingColFilterNombre(): void    { $this->resetPage(); }
    public function updatingColFilterEstado(): void    { $this->resetPage(); }
    public function updatingColFilterFecha(): void     { $this->resetPage(); }
    public function updatingColFilterFechaCont(): void { $this->resetPage(); }

    public function toggleSort(string $col): void
    {
        $this->sortDir = $this->sortBy === $col && $this->sortDir === 'asc' ? 'desc' : 'asc';
        $this->sortBy  = $col;
        $this->resetPage();
    }

    public function selectPedido(int $id): void
    {
        $this->selectedPedidoId = $this->selectedPedidoId === $id ? null : $id;
    }

    public function render()
    {
        $vendedorId = Vendedor::delUsuario()?->id;

        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1) as ciclo_code';

        $pedidos = Pedido::with(['cliente.usuario'])
            ->select('pedidos.*')
            ->addSelect(DB::raw($cicloSub))
            ->when($vendedorId, fn($q) => $q->where('vendedor_id', $vendedorId))
            ->when($this->colFilterNumero, fn($q) => $q->where('numero', 'like', "%{$this->colFilterNumero}%"))
            ->when($this->colFilterCiclo, fn($q) => $q->whereHas(
                'items.listaMaestraItem.listaMaestra.cycle',
                fn($c) => $c->where('code', 'like', "%{$this->colFilterCiclo}%")
            ))
            ->when($this->colFilterCi, fn($q) => $q->whereHas(
                'cliente', fn($c) => $c->where('ci', 'like', "%{$this->colFilterCi}%")
            ))
            ->when($this->colFilterNombre, fn($q) => $q->whereHas(
                'cliente.usuario', fn($c) => $c->where('name', 'like', "%{$this->colFilterNombre}%")
                    ->orWhere('apellido', 'like', "%{$this->colFilterNombre}%")
            ))
            ->when($this->colFilterEstado, fn($q) => $q->where('estado', $this->colFilterEstado))
            ->when($this->colFilterFecha, fn($q) => $q->whereDate('created_at', $this->colFilterFecha))
            ->when($this->colFilterFechaCont, fn($q) => $q->whereDate('updated_at', $this->colFilterFechaCont))
            ->when(in_array($this->sortBy, ['numero', 'created_at', 'estado', 'total_pagar']), function ($q) {
                $q->orderBy($this->sortBy, $this->sortDir);
            }, function ($q) {
                $q->orderByDesc('created_at');
            })
            ->paginate(10);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with(['cliente.usuario', 'items.product', 'planPago.cuotas'])
                ->find($this->viewingId);
        }

        return view('livewire.vendedor.pedido-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
