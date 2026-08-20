<?php

namespace App\Livewire\Credito;

use App\Models\ListaMaestraItem;
use App\Models\MotivoCierre;
use App\Models\PedidoCierre;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AprobadoManager extends Component
{
    use WithPagination;

    public string $mode              = 'list';
    public ?int   $viewingId         = null;

    // ── Filtros por columna ──────────────────────────────────────────────────
    public string $colFilterCiclo       = '';
    public string $colFilterNumero      = '';
    public string $colFilterCi          = '';
    public string $colFilterCliente     = '';
    public string $colFilterVendedor    = '';
    public string $colFilterFechaRevision = '';
    public string $colFilterEstado      = '';
    public string $colFilterAsignadoPor = '';
    public string $colFilterRevisadoPor = '';

    public function updatingColFilterCiclo():          void { $this->resetPage(); }
    public function updatingColFilterNumero():         void { $this->resetPage(); }
    public function updatingColFilterCi():             void { $this->resetPage(); }
    public function updatingColFilterCliente():        void { $this->resetPage(); }
    public function updatingColFilterVendedor():       void { $this->resetPage(); }
    public function updatingColFilterFechaRevision():  void { $this->resetPage(); }
    public function updatingColFilterEstado():         void { $this->resetPage(); }
    public function updatingColFilterAsignadoPor():    void { $this->resetPage(); }
    public function updatingColFilterRevisadoPor():    void { $this->resetPage(); }
    public bool   $confirmandoRechazo = false;
    public string $notaRechazo       = '';

    // Cierre de crédito
    public bool   $confirmandoCierre  = false;
    public ?int   $motivoCierreId     = null;
    public string $observacionCierre  = '';

    // Reversión de cierre
    public bool   $confirmandoReversion = false;
    public string $motivoReversion      = '';

    public string $sortBy  = '';
    public string $sortDir = 'asc';

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
        $this->viewingId             = $id;
        $this->confirmandoRechazo    = false;
        $this->notaRechazo           = '';
        $this->confirmandoCierre     = false;
        $this->motivoCierreId        = null;
        $this->observacionCierre     = '';
        $this->confirmandoReversion  = false;
        $this->motivoReversion       = '';
        $this->mode                  = 'detail';
    }

    public function backToList(): void
    {
        $this->viewingId             = null;
        $this->confirmandoRechazo    = false;
        $this->notaRechazo           = '';
        $this->confirmandoCierre     = false;
        $this->motivoCierreId        = null;
        $this->observacionCierre     = '';
        $this->confirmandoReversion  = false;
        $this->motivoReversion       = '';
        $this->mode                  = 'list';
    }

    public function devolverRevision(): void
    {
        $pedido = Pedido::with('items')
            ->whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $lmi = ListaMaestraItem::find($item->lista_maestra_item_id);
                if (!$lmi) continue;

                if ($pedido->estado === 'aprobado') {
                    // Revertir aprobación: desconsumir y volver a comprometer
                    $lmi->stock_consumido    = max(0, (float)$lmi->stock_consumido - $item->cantidad);
                    $lmi->stock_actual       = (float)$lmi->stock_actual + $item->cantidad;
                    $lmi->stock_comprometido = (float)$lmi->stock_comprometido + $item->cantidad;
                } else {
                    // Revertir rechazo: volver a comprometer
                    $lmi->stock_comprometido = (float)$lmi->stock_comprometido + $item->cantidad;
                }
                $lmi->save();
            }

            $pedido->update(['estado' => 'revision', 'notas' => null]);
        });

        session()->flash('success', 'Pedido devuelto a Revisión.');
        $this->backToList();
    }

    public function aprobar(): void
    {
        $pedido = Pedido::with('items')
            ->where('estado', 'rechazado')
            ->findOrFail($this->viewingId);

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $lmi = ListaMaestraItem::find($item->lista_maestra_item_id);
                if (!$lmi) continue;

                // Rechazo ya liberó el comprometido; aquí consumimos directamente
                $lmi->stock_actual    = max(0, (float)$lmi->stock_actual - $item->cantidad);
                $lmi->stock_consumido = (float)$lmi->stock_consumido + $item->cantidad;
                $lmi->save();
            }

            $pedido->update(['estado' => 'aprobado', 'notas' => null]);
        });

        session()->flash('success', 'Pedido aprobado.');
        $this->backToList();
    }

    public function rechazar(): void
    {
        $this->validate(['notaRechazo' => 'required|min:5'], [
            'notaRechazo.required' => 'Ingresá el motivo del rechazo.',
            'notaRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::with('items')
            ->where('estado', 'aprobado')
            ->findOrFail($this->viewingId);

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $lmi = ListaMaestraItem::find($item->lista_maestra_item_id);
                if (!$lmi) continue;

                // Revertir aprobación al rechazar
                $lmi->stock_consumido = max(0, (float)$lmi->stock_consumido - $item->cantidad);
                $lmi->stock_actual    = (float)$lmi->stock_actual + $item->cantidad;
                $lmi->save();
            }

            $pedido->update(['estado' => 'rechazado', 'notas' => $this->notaRechazo]);
        });

        session()->flash('success', 'Pedido rechazado.');
        $this->backToList();
    }

    public function cerrar(): void
    {
        $this->validate([
            'motivoCierreId'    => 'required|exists:motivo_cierres,id',
            'observacionCierre' => 'nullable|string|max:500',
        ], [
            'motivoCierreId.required' => 'Seleccioná un motivo de cierre.',
            'motivoCierreId.exists'   => 'Motivo inválido.',
        ]);

        $pedido = Pedido::where('estado', 'aprobado')->findOrFail($this->viewingId);
        $plan   = $pedido->planPago;

        $plan->update(['estado' => 'cerrado']);
        $pedido->update(['estado' => 'cerrado']);

        PedidoCierre::create([
            'pedido_id'        => $pedido->id,
            'plan_pago_id'     => $plan->id,
            'motivo_cierre_id' => $this->motivoCierreId,
            'observacion'      => $this->observacionCierre ?: null,
            'cerrado_por'      => auth()->id(),
        ]);

        session()->flash('success', 'Crédito cerrado correctamente.');
        $this->backToList();
    }

    public function revertir(): void
    {
        $this->validate(['motivoReversion' => 'required|min:5'], [
            'motivoReversion.required' => 'Ingresá el motivo de la reversión.',
            'motivoReversion.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::where('estado', 'cerrado')->findOrFail($this->viewingId);

        $cierre = PedidoCierre::where('pedido_id', $pedido->id)
            ->whereNull('revertido_at')
            ->latest()
            ->firstOrFail();

        $pedido->planes()->where('id', $cierre->plan_pago_id)->update(['estado' => 'activo']);
        $pedido->update(['estado' => 'aprobado']);

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
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user', 'cierre.motivoCierre', 'asignadoPor', 'revisadoPor'])
            ->selectRaw('`pedidos`.*, (SELECT COALESCE(SUM(`c`.`monto`), 0) FROM `cuotas` `c` INNER JOIN `plan_pagos` `pp` ON `pp`.`id` = `c`.`plan_pago_id` WHERE `pp`.`pedido_id` = `pedidos`.`id` AND `c`.`estado` = ?) AS `total_pagado`', ['pagado'])
            ->addSelect(DB::raw('(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1) as ciclo_code'))
            ->whereIn('pedidos.estado', ['aprobado', 'rechazado', 'cerrado'])
            ->when($this->colFilterEstado,   fn($q) => $q->where('pedidos.estado', $this->colFilterEstado))
            ->when($this->colFilterNumero,   fn($q) => $q->where('pedidos.numero', 'like', "%{$this->colFilterNumero}%"))
            ->when($this->colFilterCi,       fn($q) => $q->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->colFilterCi}%")))
            ->when($this->colFilterCliente,  fn($q) => $q->whereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterCliente}%")))
            ->when($this->colFilterVendedor, fn($q) => $q->whereHas('vendedor.user', fn($u) => $u->where('name', 'like', "%{$this->colFilterVendedor}%")))
            ->when($this->colFilterCiclo,    fn($q) => $q->whereHas('items.listaMaestraItem.listaMaestra.cycle', fn($c) => $c->where('code', 'like', "%{$this->colFilterCiclo}%")))
            ->when($this->colFilterFechaRevision, fn($q) => $q->whereDate('pedidos.revisado_en', $this->colFilterFechaRevision))
            ->when($this->colFilterAsignadoPor, fn($q) => $q->whereHas('asignadoPor', fn($u) => $u->where('name', 'like', "%{$this->colFilterAsignadoPor}%")))
            ->when($this->colFilterRevisadoPor, fn($q) => $q->whereHas('revisadoPor', fn($u) => $u->where('name', 'like', "%{$this->colFilterRevisadoPor}%")))
            ->when($this->sortBy === 'cliente',  fn($q) => $q->orderBy(DB::table('users')->join('clientes','clientes.usuario_id','=','users.id')->whereColumn('clientes.id','pedidos.cliente_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'vendedor', fn($q) => $q->orderBy(DB::table('users')->join('vendedores','vendedores.user_id','=','users.id')->whereColumn('vendedores.id','pedidos.vendedor_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'ci',       fn($q) => $q->orderBy(DB::table('clientes')->whereColumn('clientes.id','pedidos.cliente_id')->select('ci'), $this->sortDir))
            ->when(in_array($this->sortBy, ['numero','fecha_revision','total','estado']), fn($q) => $q->orderBy(match($this->sortBy) { 'numero'=>'pedidos.numero', 'fecha_revision'=>'pedidos.revisado_en', 'total'=>'pedidos.total_pagar', 'estado'=>'pedidos.estado' }, $this->sortDir))
            ->when(!$this->sortBy, fn($q) => $q->orderByDesc('pedidos.updated_at'))
            ->paginate(15);

        $pedidoDetalle = null;
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product', 'planPago.cuotas', 'planes.cuotas',
                'cierre.motivoCierre', 'cierre.cerradoPor',
                'cierres.motivoCierre', 'cierres.cerradoPor', 'cierres.revertidoPor',
            ])->find($this->viewingId);
        }

        $motivosCierre = MotivoCierre::activos();

        return view('livewire.credito.aprobado-manager', compact('pedidos', 'pedidoDetalle', 'motivosCierre'));
    }
}
