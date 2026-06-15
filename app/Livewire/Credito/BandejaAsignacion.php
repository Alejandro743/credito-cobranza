<?php

namespace App\Livewire\Credito;

use App\Models\CobranzaCaso;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BandejaAsignacion extends Component
{
    use WithPagination;

    public string $search            = '';
    public string $filtroCiclo       = '';
    public string $filtroEstado      = '';
    public string $filtroResponsable = '';

    public array $selectedIds = [];

    public bool   $showModal          = false;
    public array  $asignarIds         = [];
    public int    $asignarResponsable  = 0;
    public string $asignarObservacion  = '';

    protected $queryString = [
        'search'            => ['except' => ''],
        'filtroCiclo'       => ['except' => ''],
        'filtroEstado'      => ['except' => ''],
        'filtroResponsable' => ['except' => ''],
    ];

    public function updatingSearch(): void            { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFiltroCiclo(): void       { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFiltroEstado(): void      { $this->resetPage(); $this->selectedIds = []; }
    public function updatingFiltroResponsable(): void { $this->resetPage(); $this->selectedIds = []; }

    public function abrirAsignar(int $pedidoId): void
    {
        $this->asignarIds         = [$pedidoId];
        $this->asignarResponsable = 0;
        $this->asignarObservacion = '';
        $this->showModal          = true;
    }

    public function asignarMasivo(): void
    {
        if (empty($this->selectedIds)) return;
        $this->asignarIds         = array_values($this->selectedIds);
        $this->asignarResponsable = 0;
        $this->asignarObservacion = '';
        $this->showModal          = true;
    }

    public function confirmarAsignacion(): void
    {
        $this->validate([
            'asignarResponsable' => 'required|integer|min:1',
        ], [
            'asignarResponsable.required' => 'Selecciona un responsable.',
            'asignarResponsable.min'      => 'Selecciona un responsable.',
        ]);

        $count = count($this->asignarIds);
        foreach ($this->asignarIds as $pedidoId) {
            CobranzaCaso::updateOrCreate(
                ['pedido_id' => $pedidoId],
                [
                    'responsable_id'         => $this->asignarResponsable,
                    'asignado_por'           => auth()->id(),
                    'fecha_asignacion'       => now(),
                    'estado'                 => 'asignado',
                    'observacion_asignacion' => $this->asignarObservacion ?: null,
                ]
            );
        }

        $this->showModal   = false;
        $this->selectedIds = [];
        $this->asignarIds  = [];
        session()->flash('success', $count > 1 ? "{$count} casos asignados correctamente." : 'Caso asignado correctamente.');
    }

    public function selectAllFiltered(): void
    {
        $today = now()->toDateString();
        $this->selectedIds = $this->buildBaseQuery($today)
            ->pluck('pedidos.id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
    }

    private function buildBaseQuery(string $today)
    {
        return Pedido::select('pedidos.id')
            ->where('pedidos.estado', 'aprobado')
            ->whereExists(fn($q) =>
                $q->select(DB::raw(1))
                  ->from('cuotas as c')
                  ->join('plan_pagos as pp', 'pp.id', '=', 'c.plan_pago_id')
                  ->whereColumn('pp.pedido_id', 'pedidos.id')
                  ->where(fn($q2) =>
                      $q2->where('c.estado', 'vencido')
                         ->orWhere(fn($q3) => $q3->where('c.estado', 'pendiente')->where('c.fecha_vencimiento', '<', $today))
                  )
            )
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->whereHas('cliente.usuario', fn($c) => $c->where('name', 'like', "%{$this->search}%"))
                       ->orWhere('pedidos.numero', 'like', "%{$this->search}%")
                       ->orWhereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                )
            )
            ->when($this->filtroCiclo, fn($q) =>
                $q->whereExists(fn($sub) =>
                    $sub->select(DB::raw(1))
                        ->from('pedido_items as pi')
                        ->join('lista_maestra_items as lmi', 'lmi.id', '=', 'pi.lista_maestra_item_id')
                        ->join('lista_maestra as lm', 'lm.id', '=', 'lmi.lista_maestra_id')
                        ->join('commercial_cycles as cc', 'cc.id', '=', 'lm.cycle_id')
                        ->whereColumn('pi.pedido_id', 'pedidos.id')
                        ->where('cc.code', 'like', "%{$this->filtroCiclo}%")
                )
            )
            ->when($this->filtroEstado === 'sin_asignar', fn($q) =>
                $q->where(fn($q2) =>
                    $q2->whereDoesntHave('cobranzaCaso')
                       ->orWhereHas('cobranzaCaso', fn($c) => $c->where('estado', 'sin_asignar'))
                )
            )
            ->when($this->filtroEstado === 'asignado', fn($q) =>
                $q->whereHas('cobranzaCaso', fn($c) => $c->where('estado', 'asignado'))
            )
            ->when($this->filtroResponsable, fn($q) =>
                $q->whereHas('cobranzaCaso', fn($c) => $c->where('responsable_id', $this->filtroResponsable))
            );
    }

    public function render()
    {
        $today = now()->toDateString();

        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1)';
        $diasSub  = "COALESCE(DATEDIFF(NOW(), (SELECT MIN(c.fecha_vencimiento) FROM cuotas c INNER JOIN plan_pagos pp ON pp.id = c.plan_pago_id WHERE pp.pedido_id = pedidos.id AND (c.estado = 'vencido' OR (c.estado = 'pendiente' AND c.fecha_vencimiento < '$today')))), 0)";
        $cntSub   = "(SELECT COUNT(*) FROM cuotas c INNER JOIN plan_pagos pp ON pp.id = c.plan_pago_id WHERE pp.pedido_id = pedidos.id AND (c.estado = 'vencido' OR (c.estado = 'pendiente' AND c.fecha_vencimiento < '$today')))";

        $pedidos = Pedido::with(['cliente.usuario', 'vendedor', 'cobranzaCaso.responsable', 'cobranzaCaso.asignadoPor'])
            ->select('pedidos.*')
            ->addSelect(DB::raw("$cicloSub as ciclo_code"))
            ->addSelect(DB::raw("$diasSub as dias_vencimiento"))
            ->addSelect(DB::raw("$cntSub as cuotas_vencidas_count"))
            ->where('pedidos.estado', 'aprobado')
            ->whereExists(fn($q) =>
                $q->select(DB::raw(1))
                  ->from('cuotas as c')
                  ->join('plan_pagos as pp', 'pp.id', '=', 'c.plan_pago_id')
                  ->whereColumn('pp.pedido_id', 'pedidos.id')
                  ->where(fn($q2) =>
                      $q2->where('c.estado', 'vencido')
                         ->orWhere(fn($q3) => $q3->where('c.estado', 'pendiente')->where('c.fecha_vencimiento', '<', $today))
                  )
            )
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->whereHas('cliente.usuario', fn($c) => $c->where('name', 'like', "%{$this->search}%"))
                       ->orWhere('pedidos.numero', 'like', "%{$this->search}%")
                       ->orWhereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                )
            )
            ->when($this->filtroCiclo, fn($q) =>
                $q->whereExists(fn($sub) =>
                    $sub->select(DB::raw(1))
                        ->from('pedido_items as pi')
                        ->join('lista_maestra_items as lmi', 'lmi.id', '=', 'pi.lista_maestra_item_id')
                        ->join('lista_maestra as lm', 'lm.id', '=', 'lmi.lista_maestra_id')
                        ->join('commercial_cycles as cc', 'cc.id', '=', 'lm.cycle_id')
                        ->whereColumn('pi.pedido_id', 'pedidos.id')
                        ->where('cc.code', 'like', "%{$this->filtroCiclo}%")
                )
            )
            ->when($this->filtroEstado === 'sin_asignar', fn($q) =>
                $q->where(fn($q2) =>
                    $q2->whereDoesntHave('cobranzaCaso')
                       ->orWhereHas('cobranzaCaso', fn($c) => $c->where('estado', 'sin_asignar'))
                )
            )
            ->when($this->filtroEstado === 'asignado', fn($q) =>
                $q->whereHas('cobranzaCaso', fn($c) => $c->where('estado', 'asignado'))
            )
            ->when($this->filtroResponsable, fn($q) =>
                $q->whereHas('cobranzaCaso', fn($c) => $c->where('responsable_id', $this->filtroResponsable))
            )
            ->orderByDesc(DB::raw($diasSub))
            ->paginate(20);

        $usuarios = User::where('active', true)->orderBy('name')->get();

        return view('livewire.credito.bandeja-asignacion', compact('pedidos', 'usuarios'));
    }
}
