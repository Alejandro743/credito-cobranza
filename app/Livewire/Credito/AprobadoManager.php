<?php

namespace App\Livewire\Credito;

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
    public string $search            = '';
    public string $filtroEstado      = '';
    public ?int   $viewingId         = null;
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

    public function updatingSearch(): void      { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

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
        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        $pedido->update(['estado' => 'revision', 'notas' => null]);
        session()->flash('success', 'Pedido devuelto a Revisión.');
        $this->backToList();
    }

    public function aprobar(): void
    {
        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        $pedido->update(['estado' => 'aprobado', 'notas' => null]);
        session()->flash('success', 'Pedido aprobado.');
        $this->backToList();
    }

    public function rechazar(): void
    {
        $this->validate(['notaRechazo' => 'required|min:5'], [
            'notaRechazo.required' => 'Ingresá el motivo del rechazo.',
            'notaRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::whereIn('estado', ['aprobado', 'rechazado'])
            ->findOrFail($this->viewingId);

        $pedido->update(['estado' => 'rechazado', 'notas' => $this->notaRechazo]);
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
        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user', 'cierre.motivoCierre'])
            ->selectRaw('`pedidos`.*, (SELECT COALESCE(SUM(`c`.`monto`), 0) FROM `cuotas` `c` INNER JOIN `plan_pagos` `pp` ON `pp`.`id` = `c`.`plan_pago_id` WHERE `pp`.`pedido_id` = `pedidos`.`id` AND `c`.`estado` = ?) AS `total_pagado`', ['pagado'])
            ->whereIn('pedidos.estado', ['aprobado', 'rechazado', 'cerrado'])
            ->when($this->filtroEstado, fn($q) => $q->where('pedidos.estado', $this->filtroEstado))
            ->when($this->search, fn($q) => $q->whereHas('cliente.usuario', fn($c) =>
                $c->where('name', 'like', "%{$this->search}%")
            )->orWhere('pedidos.numero', 'like', "%{$this->search}%"))
            ->when($this->sortBy === 'cliente',  fn($q) => $q->orderBy(DB::table('users')->join('clientes','clientes.usuario_id','=','users.id')->whereColumn('clientes.id','pedidos.cliente_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'vendedor', fn($q) => $q->orderBy(DB::table('users')->join('vendedores','vendedores.user_id','=','users.id')->whereColumn('vendedores.id','pedidos.vendedor_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'ci',       fn($q) => $q->orderBy(DB::table('clientes')->whereColumn('clientes.id','pedidos.cliente_id')->select('ci'), $this->sortDir))
            ->when(in_array($this->sortBy, ['numero','fecha','total','estado']), fn($q) => $q->orderBy(match($this->sortBy) { 'numero'=>'pedidos.numero', 'fecha'=>'pedidos.updated_at', 'total'=>'pedidos.total_pagar', 'estado'=>'pedidos.estado' }, $this->sortDir))
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
