<?php

namespace App\Livewire\Credito;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class HistoricoCreditosManager extends Component
{
    use WithPagination;

    /** Otra pestaña/pantalla cambió pedidos compartidos: refrescar esta grilla. */
    #[On('pedidos-actualizados')]
    public function refrescarPorEvento(): void {}

    public string $mode      = 'list';
    public ?int   $viewingId = null;
    public string $sortBy    = '';
    public string $sortDir   = 'asc';

    // ── Filtros por columna ──────────────────────────────────────────────────
    public string $colFilterCiclo    = '';
    public string $colFilterNumero   = '';
    public string $colFilterCi       = '';
    public string $colFilterCliente  = '';
    public string $colFilterVendedor = '';
    public string $colFilterFechaPlan       = '';
    public string $colFilterFechaAsignacion = '';
    public string $colFilterAsignacion = '';
    public string $colFilterAsignadoPor = '';
    public string $colFilterAsignadoA   = '';
    public string $colFilterEstado      = '';

    public function updatingColFilterCiclo():           void { $this->resetPage(); }
    public function updatingColFilterNumero():          void { $this->resetPage(); }
    public function updatingColFilterCi():              void { $this->resetPage(); }
    public function updatingColFilterCliente():         void { $this->resetPage(); }
    public function updatingColFilterVendedor():        void { $this->resetPage(); }
    public function updatingColFilterFechaPlan():       void { $this->resetPage(); }
    public function updatingColFilterFechaAsignacion(): void { $this->resetPage(); }
    public function updatingColFilterAsignacion():      void { $this->resetPage(); }
    public function updatingColFilterAsignadoPor():     void { $this->resetPage(); }
    public function updatingColFilterAsignadoA():       void { $this->resetPage(); }
    public function updatingColFilterEstado():          void { $this->resetPage(); }

    // ── Selección múltiple ────────────────────────────────────────────────────
    public array $selectedIds = [];

    public function toggleSelect(int $id): void
    {
        if (in_array($id, $this->selectedIds, true)) {
            $this->selectedIds = array_values(array_diff($this->selectedIds, [$id]));
        } else {
            $this->selectedIds[] = $id;
        }
    }

    public function toggleSelectAll(): void
    {
        if (!empty($this->selectedIds)) {
            $this->selectedIds = [];
            return;
        }
        $this->selectedIds = $this->baseQuery()->pluck('pedidos.id')->toArray();
    }

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function ver(int $id): void
    {
        $this->viewingId   = $id;
        $this->selectedIds = [$id];
        $this->mode         = 'detail';
    }

    public function verSeleccionado(): void
    {
        if (count($this->selectedIds) === 1) {
            $this->ver($this->selectedIds[0]);
        }
    }

    // ── Asignar Credito (bulk) ───────────────────────────────────────────────
    public bool   $showAsignarModal = false;
    public string $searchAdmin      = '';
    public ?int   $selectedAdminId  = null;

    public function abrirAsignar(): void
    {
        if (empty($this->selectedIds)) return;
        $this->searchAdmin      = '';
        $this->selectedAdminId  = null;
        $this->showAsignarModal = true;
    }

    public function cerrarAsignar(): void
    {
        $this->showAsignarModal = false;
        $this->searchAdmin      = '';
        $this->selectedAdminId  = null;
    }

    public function seleccionarAdmin(int $id): void
    {
        $this->selectedAdminId = $this->selectedAdminId === $id ? null : $id;
    }

    public function administrativos()
    {
        $q = trim($this->searchAdmin);

        return User::where('tipo', 'administrativo')
            ->when($q !== '', fn ($w) => $w->where(function ($w2) use ($q) {
                $w2->where('name', 'like', "%{$q}%")
                   ->orWhere('apellido', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->get()
            ->map(fn ($u) => [
                'id'     => $u->id,
                'rol'    => ucfirst(preg_replace('/_\d+$/', '', $u->getRoleNames()->first() ?? '—')),
                'usuario'=> $u->email,
                'nombre' => trim($u->name.' '.($u->apellido ?? '')),
            ]);
    }

    public function asignarCredito(): void
    {
        if (empty($this->selectedIds) || !$this->selectedAdminId) return;

        Pedido::whereIn('id', $this->selectedIds)
            ->where('estado', 'en_espera')
            ->update([
                'asignado_por_id' => auth()->id(),
                'asignado_a_id'   => $this->selectedAdminId,
                'asignado_en'     => now(),
            ]);

        $this->selectedIds      = [];
        $this->showAsignarModal = false;
        $this->searchAdmin      = '';
        $this->selectedAdminId  = null;
        session()->flash('success', 'Crédito asignado correctamente.');
        $this->dispatch('pedidos-actualizados');
    }

    public function backToList(): void
    {
        $this->viewingId = null;
        $this->mode = 'list';
    }

    private function baseQuery()
    {
        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1) as ciclo_code';

        return Pedido::with(['cliente.usuario', 'vendedor.user', 'asignadoPor', 'asignadoA'])
            ->select('pedidos.*')
            ->addSelect(DB::raw($cicloSub))
            ->when($this->colFilterAsignacion === '1', fn($q) => $q->whereNotNull('asignado_a_id'))
            ->when($this->colFilterAsignacion === '0', fn($q) => $q->whereNull('asignado_a_id'))
            ->when($this->colFilterNumero,   fn($q) => $q->where('pedidos.numero', 'like', "%{$this->colFilterNumero}%"))
            ->when($this->colFilterCi,       fn($q) => $q->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->colFilterCi}%")))
            ->when($this->colFilterCliente,  fn($q) => $q->whereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterCliente}%")))
            ->when($this->colFilterVendedor, fn($q) => $q->whereHas('vendedor.user', fn($u) => $u->where('name', 'like', "%{$this->colFilterVendedor}%")))
            ->when($this->colFilterCiclo,    fn($q) => $q->whereHas('items.listaMaestraItem.listaMaestra.cycle', fn($c) => $c->where('code', 'like', "%{$this->colFilterCiclo}%")))
            ->when($this->colFilterFechaPlan,       fn($q) => $q->whereDate('pedidos.created_at', $this->colFilterFechaPlan))
            ->when($this->colFilterFechaAsignacion, fn($q) => $q->whereDate('pedidos.asignado_en', $this->colFilterFechaAsignacion))
            ->when($this->colFilterAsignadoPor, fn($q) => $q->whereHas('asignadoPor', fn($u) => $u->where('name', 'like', "%{$this->colFilterAsignadoPor}%")))
            ->when($this->colFilterAsignadoA,   fn($q) => $q->whereHas('asignadoA', fn($u) => $u->where('name', 'like', "%{$this->colFilterAsignadoA}%")))
            ->when($this->colFilterEstado,      fn($q) => $q->where('estado', $this->colFilterEstado))
            ->when($this->sortBy === 'cliente',  fn($q) => $q->orderBy(DB::table('users')->join('clientes','clientes.usuario_id','=','users.id')->whereColumn('clientes.id','pedidos.cliente_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'vendedor', fn($q) => $q->orderBy(DB::table('users')->join('vendedores','vendedores.user_id','=','users.id')->whereColumn('vendedores.id','pedidos.vendedor_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'ci',       fn($q) => $q->orderBy(DB::table('clientes')->whereColumn('clientes.id','pedidos.cliente_id')->select('ci'), $this->sortDir))
            ->when(in_array($this->sortBy, ['numero','fecha_plan','fecha_asignacion','total']), fn($q) => $q->orderBy(match($this->sortBy) { 'numero'=>'pedidos.numero', 'fecha_plan'=>'pedidos.created_at', 'fecha_asignacion'=>'pedidos.asignado_en', 'total'=>'pedidos.total_pagar' }, $this->sortDir))
            ->when(!$this->sortBy, fn($q) => $q->orderByDesc('created_at'));
    }

    public function render()
    {
        $pedidos = $this->baseQuery()->paginate(15);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product', 'planPago.cuotas',
            ])->find($this->viewingId);
        }

        return view('livewire.credito.historico-creditos-manager', compact('pedidos', 'pedidoDetalle'));
    }
}
