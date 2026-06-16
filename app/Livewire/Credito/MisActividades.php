<?php

namespace App\Livewire\Credito;

use App\Models\CobranzaActividad;
use App\Models\CobranzaCaso;
use App\Models\CobranzaCatalogo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MisActividades extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filtroEstado = '';

    public bool $showModalEditarAct   = false;
    public bool $showModalCerrarAct   = false;
    public bool $showModalCancelarAct = false;

    public int    $actividadId       = 0;
    public int    $actCasoId         = 0;
    public string $actEstado         = '';
    public int    $tipoContactoId    = 0;
    public int    $accionId          = 0;
    public int    $tipoRespuestaId   = 0;
    public int    $actResponsable    = 0;
    public string $actFechaProg      = '';
    public string $actObservacion    = '';
    public string $actObsCierre      = '';
    public string $actMotivoCancelac = '';

    public string $cerrarOpcion      = 'solo';
    public int    $nuevaTipoContacto = 0;
    public int    $nuevaAccion       = 0;
    public int    $nuevaResponsable  = 0;
    public string $nuevaFechaProg    = '';
    public string $nuevaObs          = '';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    public function abrirEditarActividad(int $id): void
    {
        $act = CobranzaActividad::findOrFail($id);
        $this->actividadId     = $id;
        $this->actCasoId       = $act->caso_id;
        $this->actEstado       = $act->estado;
        $this->tipoContactoId  = $act->tipo_contacto_id ?? 0;
        $this->accionId        = $act->accion_id ?? 0;
        $this->actResponsable  = $act->responsable_id ?? auth()->id();
        $this->actFechaProg    = $act->fecha_programada?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->actObservacion  = $act->observacion ?? '';
        $this->tipoRespuestaId = $act->tipo_respuesta_id ?? 0;
        $this->actObsCierre    = $act->observacion_cierre ?? '';
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
            'tipoContactoId.min'    => 'Selecciona el tipo de contacto.',
            'accionId.min'          => 'Selecciona la acción.',
            'actFechaProg.required' => 'La fecha programada es requerida.',
            'tipoRespuestaId.min'   => 'Selecciona el tipo de respuesta.',
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

    public function iniciarActividad(int $id): void
    {
        CobranzaActividad::findOrFail($id)->update([
            'estado'       => 'en_proceso',
            'fecha_inicio' => now(),
        ]);
        session()->flash('success', 'Actividad iniciada.');
    }

    public function abrirCerrarActividad(int $id): void
    {
        $act = CobranzaActividad::findOrFail($id);
        $this->actividadId       = $id;
        $this->actCasoId         = $act->caso_id;
        $this->tipoRespuestaId   = 0;
        $this->actObsCierre      = '';
        $this->cerrarOpcion      = 'solo';
        $this->nuevaTipoContacto = 0;
        $this->nuevaAccion       = 0;
        $this->nuevaResponsable  = auth()->id();
        $this->nuevaFechaProg    = now()->addDay()->format('Y-m-d');
        $this->nuevaObs          = '';
        $this->showModalCerrarAct = true;
    }

    public function confirmarCerrarActividad(): void
    {
        $this->validate([
            'tipoRespuestaId' => 'required|integer|min:1',
        ], ['tipoRespuestaId.min' => 'Selecciona el tipo de respuesta.']);

        $act = CobranzaActividad::findOrFail($this->actividadId);
        $act->update([
            'estado'             => 'cerrada',
            'tipo_respuesta_id'  => $this->tipoRespuestaId,
            'observacion_cierre' => $this->actObsCierre ?: null,
            'fecha_cierre'       => now(),
            'cerrado_por'        => auth()->id(),
        ]);

        if ($this->cerrarOpcion === 'actividad_y_nueva') {
            $siguiente = CobranzaActividad::where('caso_id', $this->actCasoId)->max('numero') + 1;
            CobranzaActividad::create([
                'caso_id'             => $this->actCasoId,
                'numero'              => $siguiente,
                'actividad_origen_id' => $act->id,
                'tipo_contacto_id'    => $this->nuevaTipoContacto ?: null,
                'accion_id'           => $this->nuevaAccion ?: null,
                'responsable_id'      => $this->nuevaResponsable ?: auth()->id(),
                'fecha_programada'    => $this->nuevaFechaProg,
                'observacion'         => $this->nuevaObs ?: null,
                'estado'              => 'abierta',
                'created_by'          => auth()->id(),
            ]);
        } elseif ($this->cerrarOpcion === 'actividad_y_caso') {
            CobranzaCaso::find($this->actCasoId)?->update([
                'estado'       => 'cerrado',
                'fecha_cierre' => now(),
                'cerrado_por'  => auth()->id(),
            ]);
        }

        $this->showModalCerrarAct = false;
        session()->flash('success', 'Actividad cerrada.');
    }

    public function abrirCancelarActividad(int $id): void
    {
        $this->actividadId       = $id;
        $this->actMotivoCancelac = '';
        $this->actObsCierre      = '';
        $this->showModalCancelarAct = true;
    }

    public function confirmarCancelarActividad(): void
    {
        CobranzaActividad::findOrFail($this->actividadId)->update([
            'estado'             => 'cancelada',
            'motivo_cancelacion' => $this->actMotivoCancelac ?: null,
            'observacion_cierre' => $this->actObsCierre ?: null,
            'fecha_cierre'       => now(),
            'cerrado_por'        => auth()->id(),
        ]);
        $this->showModalCancelarAct = false;
        session()->flash('success', 'Actividad cancelada.');
    }

    private function resetActForm(): void
    {
        $this->actividadId = $this->actCasoId = $this->tipoContactoId = 0;
        $this->accionId = $this->tipoRespuestaId = $this->actResponsable = 0;
        $this->actFechaProg = $this->actObservacion = $this->actObsCierre = '';
        $this->actMotivoCancelac = $this->actEstado = '';
        $this->cerrarOpcion = 'solo';
        $this->resetValidation();
    }

    public function render()
    {
        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1)';

        $actividades = CobranzaActividad::with([
            'tipoContacto', 'accion', 'tipoRespuesta', 'responsable', 'actividadOrigen',
            'caso.pedido.cliente.usuario',
        ])
        ->join('cobranza_casos', 'cobranza_casos.id', '=', 'cobranza_actividades.caso_id')
        ->join('pedidos', 'pedidos.id', '=', 'cobranza_casos.pedido_id')
        ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
        ->join('users', 'users.id', '=', 'clientes.user_id')
        ->select('cobranza_actividades.*')
        ->addSelect(DB::raw("$cicloSub as ciclo_code"))
        ->addSelect(DB::raw('pedidos.numero as pedido_numero'))
        ->addSelect(DB::raw('clientes.ci as cliente_ci'))
        ->addSelect(DB::raw('users.name as cliente_nombre'))
        ->where('cobranza_actividades.responsable_id', auth()->id())
        ->when($this->filtroEstado, fn($q) => $q->where('cobranza_actividades.estado', $this->filtroEstado))
        ->when($this->search, fn($q) =>
            $q->where(fn($q2) =>
                $q2->where('pedidos.numero', 'like', "%{$this->search}%")
                   ->orWhere('users.name', 'like', "%{$this->search}%")
                   ->orWhere('clientes.ci', 'like', "%{$this->search}%")
            )
        )
        ->orderBy('cobranza_actividades.fecha_programada')
        ->paginate(20);

        $tiposContacto  = CobranzaCatalogo::activos('tipo_contacto');
        $acciones       = CobranzaCatalogo::activos('accion');
        $tiposRespuesta = CobranzaCatalogo::activos('tipo_respuesta');
        $usuarios       = User::where('active', true)->orderBy('name')->get();

        return view('livewire.credito.mis-actividades', compact(
            'actividades', 'tiposContacto', 'acciones', 'tiposRespuesta', 'usuarios'
        ));
    }
}
