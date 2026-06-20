<?php

namespace App\Livewire\Credito;

use App\Models\CobranzaActividad;
use App\Models\CobranzaCaso;
use App\Models\CobranzaCatalogo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MisCasos extends Component
{
    use WithPagination;
    public ?int $selectedCasoId = null;

    public string $searchCasos      = '';
    public string $filtroEstadoCaso = '';
    public string $filtroCiclo      = '';

    // Modales
    public bool $showModalNuevaAct      = false;
    public bool $showModalEditarAct     = false;
    public bool $showModalCerrarAct     = false;
    public bool $showModalCancelarAct   = false;
    public bool $showModalCerrarCaso    = false;
    public bool $showModalCancelarCaso  = false;

    // Formulario actividad
    public int    $actividadId        = 0;
    public string $actEstado          = '';
    public int    $actOrigenId        = 0;
    public int    $tipoContactoId     = 0;
    public int    $accionId           = 0;
    public int    $tipoRespuestaId    = 0;
    public int    $tipoCancelacionId  = 0;
    public int    $actResponsable     = 0;
    public string $actFechaProg       = '';
    public string $actObservacion     = '';
    public string $actObsCierre       = '';
    public string $actMotivoCancelac  = '';

    // Cerrar actividad: opciones 1/2/3
    public string $cerrarOpcion       = 'solo';
    public int    $nuevaTipoContacto  = 0;
    public int    $nuevaAccion        = 0;
    public int    $nuevaResponsable   = 0;
    public string $nuevaFechaProg     = '';
    public string $nuevaObs           = '';

    // Cerrar / cancelar caso
    public int    $motivoCierreId     = 0;
    public string $obsCierreCaso      = '';
    public int    $motivoCancelacionId = 0;
    public string $obsCancelCaso      = '';

    public function selectCaso(int $id): void
    {
        $this->selectedCasoId = $id;
        $this->resetPage('actsPage');
    }

    // ── Nueva actividad ────────────────────────────────────────────
    public function abrirNuevaActividad(int $origenId = 0): void
    {
        $this->resetActForm();
        $this->actOrigenId   = $origenId;
        $this->actFechaProg  = now()->format('Y-m-d');
        $this->actResponsable = auth()->id();
        $this->showModalNuevaAct = true;
    }

    public function guardarActividad(): void
    {
        $this->validate([
            'tipoContactoId' => 'required|integer|min:1',
            'accionId'       => 'required|integer|min:1',
            'actFechaProg'   => 'required|date',
        ], [
            'tipoContactoId.min' => 'Selecciona el tipo de contacto.',
            'accionId.min'       => 'Selecciona la acción.',
            'actFechaProg.required' => 'La fecha programada es requerida.',
        ]);

        $siguienteNumero = CobranzaActividad::where('caso_id', $this->selectedCasoId)->max('numero') + 1;

        CobranzaActividad::create([
            'caso_id'            => $this->selectedCasoId,
            'numero'             => $siguienteNumero,
            'actividad_origen_id'=> $this->actOrigenId ?: null,
            'tipo_contacto_id'   => $this->tipoContactoId,
            'accion_id'          => $this->accionId,
            'responsable_id'     => $this->actResponsable ?: auth()->id(),
            'fecha_programada'   => $this->actFechaProg,
            'observacion'        => $this->actObservacion ?: null,
            'estado'             => 'abierta',
            'created_by'         => auth()->id(),
        ]);

        $caso = CobranzaCaso::find($this->selectedCasoId);
        if ($caso && $caso->estado === 'asignado') {
            $caso->update(['estado' => 'en_gestion']);
        }

        $this->showModalNuevaAct = false;
        $this->resetActForm();
        session()->flash('success', 'Actividad creada correctamente.');
    }

    // ── Editar actividad ───────────────────────────────────────────
    public function abrirEditarActividad(int $id): void
    {
        $act = CobranzaActividad::findOrFail($id);
        $this->actividadId    = $id;
        $this->actEstado      = $act->estado;
        $this->tipoContactoId = $act->tipo_contacto_id ?? 0;
        $this->accionId       = $act->accion_id ?? 0;
        $this->actResponsable = $act->responsable_id ?? auth()->id();
        $this->actFechaProg   = $act->fecha_programada?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->actObservacion = $act->observacion ?? '';
        $this->tipoRespuestaId = $act->tipo_respuesta_id ?? 0;
        $this->actObsCierre   = $act->observacion_cierre ?? '';
        $this->resetValidation();
        $this->showModalEditarAct = true;
    }

    public function guardarEditarActividad(): void
    {
        $rules = [
            'tipoContactoId' => 'required|integer|min:1',
            'accionId'       => 'required|integer|min:1',
            'actFechaProg'   => 'required|date',
        ];
        if ($this->actEstado === 'cerrada') {
            $rules['tipoRespuestaId'] = 'required|integer|min:1';
        }
        $this->validate($rules, [
            'tipoContactoId.min'  => 'Selecciona el tipo de contacto.',
            'accionId.min'        => 'Selecciona la acción.',
            'actFechaProg.required' => 'La fecha programada es requerida.',
            'tipoRespuestaId.min' => 'Selecciona el tipo de respuesta.',
        ]);

        $data = [
            'tipo_contacto_id' => $this->tipoContactoId,
            'accion_id'        => $this->accionId,
            'responsable_id'   => $this->actResponsable ?: auth()->id(),
            'fecha_programada' => $this->actFechaProg,
            'observacion'      => $this->actObservacion ?: null,
        ];
        if ($this->actEstado === 'cerrada') {
            $data['tipo_respuesta_id']  = $this->tipoRespuestaId;
            $data['observacion_cierre'] = $this->actObsCierre ?: null;
        }

        CobranzaActividad::findOrFail($this->actividadId)->update($data);

        $this->showModalEditarAct = false;
        $this->resetActForm();
        session()->flash('success', 'Actividad actualizada.');
    }

    // ── Iniciar actividad ──────────────────────────────────────────
    public function iniciarActividad(int $id): void
    {
        CobranzaActividad::findOrFail($id)->update([
            'estado'       => 'en_proceso',
            'fecha_inicio' => now(),
        ]);
        session()->flash('success', 'Actividad iniciada.');
    }

    // ── Cerrar actividad ───────────────────────────────────────────
    public function abrirCerrarActividad(int $id): void
    {
        $this->actividadId     = $id;
        $this->tipoRespuestaId = 0;
        $this->actObsCierre    = '';
        $this->cerrarOpcion    = 'solo';
        $this->nuevaTipoContacto = 0;
        $this->nuevaAccion     = 0;
        $this->nuevaResponsable = auth()->id();
        $this->nuevaFechaProg  = now()->addDay()->format('Y-m-d');
        $this->nuevaObs        = '';
        $this->showModalCerrarAct = true;
    }

    public function confirmarCerrarActividad(): void
    {
        $this->validate([
            'tipoRespuestaId' => 'required|integer|min:1',
        ], ['tipoRespuestaId.min' => 'Selecciona el tipo de respuesta.']);

        $act = CobranzaActividad::findOrFail($this->actividadId);
        $act->update([
            'estado'           => 'cerrada',
            'tipo_respuesta_id'=> $this->tipoRespuestaId,
            'observacion_cierre' => $this->actObsCierre ?: null,
            'fecha_cierre'     => now(),
            'cerrado_por'      => auth()->id(),
        ]);

        if ($this->cerrarOpcion === 'actividad_y_nueva') {
            $siguienteNumero = CobranzaActividad::where('caso_id', $this->selectedCasoId)->max('numero') + 1;
            CobranzaActividad::create([
                'caso_id'            => $this->selectedCasoId,
                'numero'             => $siguienteNumero,
                'actividad_origen_id'=> $act->id,
                'tipo_contacto_id'   => $this->nuevaTipoContacto ?: null,
                'accion_id'          => $this->nuevaAccion ?: null,
                'responsable_id'     => $this->nuevaResponsable ?: auth()->id(),
                'fecha_programada'   => $this->nuevaFechaProg,
                'observacion'        => $this->nuevaObs ?: null,
                'estado'             => 'abierta',
                'created_by'         => auth()->id(),
            ]);
        } elseif ($this->cerrarOpcion === 'actividad_y_caso') {
            CobranzaCaso::find($this->selectedCasoId)?->update([
                'estado'       => 'cerrado',
                'fecha_cierre' => now(),
                'cerrado_por'  => auth()->id(),
            ]);
        }

        $this->showModalCerrarAct = false;
        session()->flash('success', 'Actividad cerrada.');
    }

    // ── Cancelar actividad ─────────────────────────────────────────
    public function abrirCancelarActividad(int $id): void
    {
        $this->actividadId       = $id;
        $this->tipoCancelacionId = 0;
        $this->actMotivoCancelac = '';
        $this->actObsCierre      = '';
        $this->resetValidation();
        $this->showModalCancelarAct = true;
    }

    public function confirmarCancelarActividad(): void
    {
        $this->validate([
            'tipoCancelacionId' => 'required|integer|min:1',
        ], ['tipoCancelacionId.min' => 'Selecciona el tipo de cancelación.']);

        CobranzaActividad::findOrFail($this->actividadId)->update([
            'estado'              => 'cancelada',
            'tipo_cancelacion_id' => $this->tipoCancelacionId,
            'motivo_cancelacion'  => $this->actMotivoCancelac ?: null,
            'observacion_cierre'  => $this->actObsCierre ?: null,
            'fecha_cierre'        => now(),
            'cerrado_por'         => auth()->id(),
        ]);
        $this->showModalCancelarAct = false;
        session()->flash('success', 'Actividad cancelada.');
    }

    // ── Cerrar caso ────────────────────────────────────────────────
    public function abrirCerrarCaso(): void
    {
        $this->motivoCierreId = 0;
        $this->obsCierreCaso  = '';
        $this->showModalCerrarCaso = true;
    }

    public function confirmarCerrarCaso(): void
    {
        $this->validate([
            'motivoCierreId' => 'required|integer|min:1',
        ], ['motivoCierreId.min' => 'Selecciona un motivo de cierre.']);

        $pendientes = CobranzaActividad::where('caso_id', $this->selectedCasoId)
            ->whereIn('estado', ['abierta', 'en_proceso'])
            ->count();

        if ($pendientes > 0) {
            $this->addError('motivoCierreId', "No se puede cerrar el caso: hay {$pendientes} actividad(es) abierta(s) o en proceso.");
            return;
        }

        CobranzaCaso::findOrFail($this->selectedCasoId)->update([
            'estado'            => 'cerrado',
            'motivo_cierre'     => CobranzaCatalogo::find($this->motivoCierreId)?->nombre,
            'observacion_cierre'=> $this->obsCierreCaso ?: null,
            'fecha_cierre'      => now(),
            'cerrado_por'       => auth()->id(),
        ]);

        $this->showModalCerrarCaso = false;
        session()->flash('success', 'Caso cerrado correctamente.');
    }

    // ── Cancelar caso ──────────────────────────────────────────────
    public function abrirCancelarCaso(): void
    {
        $this->motivoCancelacionId = 0;
        $this->obsCancelCaso       = '';
        $this->resetValidation();
        $this->showModalCancelarCaso = true;
    }

    public function confirmarCancelarCaso(): void
    {
        $this->validate([
            'motivoCancelacionId' => 'required|integer|min:1',
        ], ['motivoCancelacionId.min' => 'Selecciona un motivo de cancelación.']);

        $pendientes = CobranzaActividad::where('caso_id', $this->selectedCasoId)
            ->whereIn('estado', ['abierta', 'en_proceso'])
            ->count();

        if ($pendientes > 0) {
            $this->addError('motivoCancelacionId', "No se puede cancelar: hay {$pendientes} actividad(es) abierta(s) o en proceso.");
            return;
        }

        CobranzaCaso::findOrFail($this->selectedCasoId)->update([
            'estado'             => 'cancelado',
            'motivo_cierre'      => CobranzaCatalogo::find($this->motivoCancelacionId)?->nombre,
            'observacion_cierre' => $this->obsCancelCaso ?: null,
            'fecha_cierre'       => now(),
            'cerrado_por'        => auth()->id(),
        ]);

        $this->showModalCancelarCaso = false;
        session()->flash('success', 'Caso cancelado.');
    }

    private function resetActForm(): void
    {
        $this->actividadId = $this->actOrigenId = $this->tipoContactoId = 0;
        $this->accionId = $this->tipoRespuestaId = $this->tipoCancelacionId = $this->actResponsable = 0;
        $this->actFechaProg = $this->actObservacion = $this->actObsCierre = $this->actMotivoCancelac = $this->actEstado = '';
        $this->cerrarOpcion = 'solo';
        $this->resetValidation();
    }

    public function render()
    {
        $today    = now()->toDateString();
        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1)';
        $diasSub  = "COALESCE(DATEDIFF(NOW(), (SELECT MIN(c.fecha_vencimiento) FROM cuotas c INNER JOIN plan_pagos pp ON pp.id = c.plan_pago_id WHERE pp.pedido_id = pedidos.id AND (c.estado = 'vencido' OR (c.estado = 'pendiente' AND c.fecha_vencimiento < '$today')))), 0)";
        $cntSub   = "(SELECT COUNT(*) FROM cuotas c INNER JOIN plan_pagos pp ON pp.id = c.plan_pago_id WHERE pp.pedido_id = pedidos.id AND (c.estado = 'vencido' OR (c.estado = 'pendiente' AND c.fecha_vencimiento < '$today')))";

        $casos = CobranzaCaso::with(['pedido.cliente.usuario', 'pedido.vendedor.user', 'asignadoPor'])
            ->join('pedidos', 'pedidos.id', '=', 'cobranza_casos.pedido_id')
            ->where('cobranza_casos.responsable_id', auth()->id())
            ->select('cobranza_casos.*')
            ->addSelect(DB::raw("$cicloSub as ciclo_code"))
            ->addSelect(DB::raw("$diasSub as dias_vencimiento"))
            ->addSelect(DB::raw("$cntSub as cuotas_vencidas_count"))
            ->when($this->searchCasos, fn($q) =>
                $q->whereHas('pedido', fn($p) =>
                    $p->where('pedidos.numero', 'like', "%{$this->searchCasos}%")
                      ->orWhereHas('cliente.usuario', fn($c) => $c->where('name', 'like', "%{$this->searchCasos}%"))
                      ->orWhereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->searchCasos}%"))
                )
            )
            ->when($this->filtroEstadoCaso, fn($q) => $q->where('cobranza_casos.estado', $this->filtroEstadoCaso))
            ->when($this->filtroCiclo, fn($q) => $q->whereRaw("$cicloSub LIKE ?", ["%{$this->filtroCiclo}%"]))
            ->orderByDesc(DB::raw($diasSub))
            ->paginate(20, pageName: 'casosPage');

        $actividades      = collect();
        $actividadesAll   = collect();
        $casoSeleccionado = null;
        if ($this->selectedCasoId) {
            $casoSeleccionado = CobranzaCaso::with(['pedido.cliente.usuario', 'pedido.vendedor.user', 'responsable'])->find($this->selectedCasoId);
            if ($casoSeleccionado) {
                $actividadesAll = CobranzaActividad::with(['tipoContacto', 'accion'])
                    ->where('caso_id', $this->selectedCasoId)
                    ->orderBy('numero')->get();
                $actividades = CobranzaActividad::with([
                    'tipoContacto', 'accion', 'tipoRespuesta',
                    'responsable', 'creadoPor', 'cerradoPor', 'actividadOrigen',
                ])->where('caso_id', $this->selectedCasoId)
                  ->orderBy('numero')
                  ->paginate(20, pageName: 'actsPage');
            }
        }

        $tiposContacto    = CobranzaCatalogo::activos('tipo_contacto');
        $acciones         = CobranzaCatalogo::activos('accion');
        $tiposRespuesta   = CobranzaCatalogo::activos('tipo_respuesta');
        $motivosCierre       = CobranzaCatalogo::activos('motivo_cierre');
        $motivosCancelacion  = CobranzaCatalogo::activos('motivo_cancelacion');
        $tiposCancelacion    = CobranzaCatalogo::activos('tipo_cancelacion');
        $usuarios            = User::where('active', true)->orderBy('name')->get();

        return view('livewire.credito.mis-casos', compact(
            'casos', 'actividades', 'actividadesAll', 'casoSeleccionado',
            'tiposContacto', 'acciones', 'tiposRespuesta', 'motivosCierre', 'motivosCancelacion', 'tiposCancelacion', 'usuarios'
        ));
    }
}
