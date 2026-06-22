<?php

namespace App\Livewire\Credito;

use App\Models\Campana;
use App\Models\CobranzaActividad;
use App\Models\CobranzaCaso;
use App\Models\CobranzaCatalogo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ActividadesMasivas extends Component
{
    use WithPagination;

    public string $mode = 'list'; // list | detail

    // Detalle
    public ?int $detalleCampanaId = null;

    // Filtros list
    public string $search       = '';
    public string $filtroEstado = '';

    // Form inline campaña
    public bool   $showAddForm   = false;
    public int    $campanaId     = 0;
    public string $nombre        = '';
    public int    $tipoContactoId  = 0;
    public int    $accionId        = 0;
    public string $fechaProgramada = '';
    public int    $responsableId   = 0;
    public string $observacion     = '';

    // Modal agregar casos
    public bool   $showModalCasos      = false;
    public int    $modalCasosCampanaId = 0;
    public string $filtroCasoEstado    = '';
    public string $filtroCiclo         = '';
    public array  $selectedCasoIds     = [];

    // Modales cierre/cancelación campaña
    public bool   $showModalCerrar   = false;
    public bool   $showModalCancelar = false;
    public int    $modalCampanaId    = 0;
    public int    $cierreTipoRespuestaId = 0;
    public string $cierreObs            = '';
    public int    $cancelTipoId         = 0;
    public string $cancelMotivo         = '';
    public string $cancelObs            = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'filtroEstado' => ['except' => ''],
    ];

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    public function verDetalle(int $id): void
    {
        $this->detalleCampanaId = $id;
        $this->mode = 'detail';
    }

    public function create(): void
    {
        $this->resetFormFields();
        $this->responsableId   = auth()->id();
        $this->fechaProgramada = now()->format('Y-m-d');
        $this->showAddForm     = true;
    }

    public function edit(int $id): void
    {
        $c = Campana::findOrFail($id);
        $this->campanaId       = $c->id;
        $this->nombre          = $c->nombre;
        $this->tipoContactoId  = $c->tipo_contacto_id ?? 0;
        $this->accionId        = $c->accion_id ?? 0;
        $this->fechaProgramada = $c->fecha_programada?->format('Y-m-d') ?? '';
        $this->responsableId   = $c->responsable_id ?? auth()->id();
        $this->observacion     = $c->observacion ?? '';
        $this->showAddForm     = true;
    }

    public function cancelForm(): void
    {
        $this->resetFormFields();
        $this->showAddForm = false;
    }

    public function save(): void
    {
        $this->validate([
            'nombre'          => 'required|string|max:150',
            'tipoContactoId'  => 'required|integer|min:1',
            'accionId'        => 'required|integer|min:1',
            'fechaProgramada' => 'required|date',
            'responsableId'   => 'required|integer|min:1',
        ], [
            'nombre.required'         => 'El nombre es obligatorio.',
            'tipoContactoId.min'      => 'Seleccioná un tipo de contacto.',
            'accionId.min'            => 'Seleccioná una acción.',
            'fechaProgramada.required'=> 'La fecha programada es obligatoria.',
            'responsableId.min'       => 'Seleccioná un responsable.',
        ]);

        $datos = [
            'nombre'           => $this->nombre,
            'tipo_contacto_id' => $this->tipoContactoId,
            'accion_id'        => $this->accionId,
            'fecha_programada' => $this->fechaProgramada,
            'responsable_id'   => $this->responsableId,
            'observacion'      => $this->observacion ?: null,
        ];

        if ($this->campanaId) {
            $campana = Campana::findOrFail($this->campanaId);
            $campana->update($datos);
            // Sincronizar actividades abierta con nuevos datos
            $campana->actividades()->where('estado', 'abierta')->update([
                'tipo_contacto_id' => $this->tipoContactoId,
                'accion_id'        => $this->accionId,
                'fecha_programada' => $this->fechaProgramada,
                'responsable_id'   => $this->responsableId,
                'observacion'      => $this->observacion ?: null,
            ]);
        } else {
            Campana::create(array_merge($datos, ['creado_por' => auth()->id()]));
        }

        $this->resetFormFields();
        $this->showAddForm = false;
    }

    public function abrirCasos(int $id): void
    {
        $this->modalCasosCampanaId = $id;
        $this->selectedCasoIds     = [];
        $this->filtroCasoEstado    = '';
        $this->filtroCiclo         = '';
        $this->showModalCasos      = true;
    }

    public function guardarCasos(): void
    {
        if (empty($this->selectedCasoIds)) {
            $this->showModalCasos = false;
            return;
        }

        $campana   = Campana::findOrFail($this->modalCasosCampanaId);
        $existentes = CobranzaActividad::where('campana_id', $campana->id)
            ->pluck('caso_id')->map(fn($v) => (int)$v)->toArray();

        foreach ($this->selectedCasoIds as $casoId) {
            $casoId = (int)$casoId;
            if (in_array($casoId, $existentes)) continue;

            $caso = CobranzaCaso::find($casoId);
            if (!$caso) continue;

            $ultimo = CobranzaActividad::where('caso_id', $casoId)->max('numero') ?? 0;

            CobranzaActividad::create([
                'caso_id'          => $casoId,
                'campana_id'       => $campana->id,
                'numero'           => $ultimo + 1,
                'tipo_contacto_id' => $campana->tipo_contacto_id,
                'accion_id'        => $campana->accion_id,
                'fecha_programada' => $campana->fecha_programada,
                'responsable_id'   => $campana->responsable_id,
                'observacion'      => $campana->observacion,
                'estado'           => 'abierta',
            ]);

            if ($caso->estado === 'asignado') {
                $caso->update(['estado' => 'en_gestion']);
            }
        }

        $this->showModalCasos      = false;
        $this->modalCasosCampanaId = 0;
        $this->selectedCasoIds     = [];
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        if ($estado === 'en_proceso') {
            $campana = Campana::findOrFail($id);
            $campana->update(['estado' => 'en_proceso']);
            $campana->actividades()->where('estado', 'abierta')->update([
                'estado'       => 'en_proceso',
                'fecha_inicio' => now(),
            ]);
            return;
        }

        if ($estado === 'cerrada') {
            $this->modalCampanaId        = $id;
            $this->cierreTipoRespuestaId = 0;
            $this->cierreObs             = '';
            $this->showModalCerrar       = true;
            return;
        }

        if ($estado === 'cancelada') {
            $this->modalCampanaId    = $id;
            $this->cancelTipoId      = 0;
            $this->cancelMotivo      = '';
            $this->cancelObs         = '';
            $this->showModalCancelar = true;
            return;
        }
    }

    public function confirmarCierre(): void
    {
        $this->validate([
            'cierreTipoRespuestaId' => 'required|integer|min:1',
        ], [
            'cierreTipoRespuestaId.min' => 'Seleccioná un tipo de respuesta.',
        ]);

        $campana = Campana::findOrFail($this->modalCampanaId);
        $campana->update(['estado' => 'cerrada']);
        $campana->actividades()->whereIn('estado', ['abierta', 'en_proceso'])->update([
            'estado'             => 'cerrada',
            'tipo_respuesta_id'  => $this->cierreTipoRespuestaId,
            'observacion_cierre' => $this->cierreObs ?: null,
            'fecha_cierre'       => now(),
            'cerrado_por'        => auth()->id(),
        ]);

        $this->showModalCerrar = false;
        $this->modalCampanaId  = 0;
    }

    public function confirmarCancelacion(): void
    {
        $campana = Campana::findOrFail($this->modalCampanaId);
        $campana->update(['estado' => 'cancelada']);
        $campana->actividades()->whereIn('estado', ['abierta', 'en_proceso'])->update([
            'estado'              => 'cancelada',
            'tipo_cancelacion_id' => $this->cancelTipoId ?: null,
            'motivo_cancelacion'  => $this->cancelMotivo ?: null,
            'observacion_cierre'  => $this->cancelObs ?: null,
            'fecha_cierre'        => now(),
            'cerrado_por'         => auth()->id(),
        ]);

        $this->showModalCancelar = false;
        $this->modalCampanaId    = 0;
    }

    public function backToList(): void
    {
        $this->detalleCampanaId = null;
        $this->mode             = 'list';
    }

    private function resetFormFields(): void
    {
        $this->campanaId        = 0;
        $this->nombre           = '';
        $this->tipoContactoId   = 0;
        $this->accionId         = 0;
        $this->fechaProgramada  = '';
        $this->responsableId    = 0;
        $this->observacion      = '';
        $this->resetValidation();
    }

    public function render()
    {
        $campanas = Campana::with(['tipoContacto', 'accion', 'responsable'])
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->withCount('actividades')
            ->orderByDesc('created_at')
            ->paginate(15);

        $tiposContacto    = CobranzaCatalogo::where('tipo', 'tipo_contacto')->where('activo', true)->orderBy('nombre')->get();
        $acciones         = CobranzaCatalogo::where('tipo', 'accion')->where('activo', true)->orderBy('nombre')->get();
        $tiposRespuesta   = CobranzaCatalogo::where('tipo', 'tipo_respuesta')->where('activo', true)->orderBy('nombre')->get();
        $tiposCancelacion = CobranzaCatalogo::where('tipo', 'tipo_cancelacion')->where('activo', true)->orderBy('nombre')->get();
        $usuarios         = User::where('active', true)->orderBy('name')->get();

        $casosQuery = CobranzaCaso::with(['pedido.cliente'])
            ->whereIn('estado', ['asignado', 'en_gestion'])
            ->where('responsable_id', auth()->id())
            ->when($this->filtroCasoEstado, fn($q) => $q->where('estado', $this->filtroCasoEstado))
            ->when($this->filtroCiclo, fn($q) =>
                $q->whereHas('pedido', fn($p) =>
                    $p->whereExists(fn($sub) =>
                        $sub->select(DB::raw(1))
                            ->from('pedido_items as pi')
                            ->join('lista_maestra_items as lmi', 'lmi.id', '=', 'pi.lista_maestra_item_id')
                            ->join('lista_maestra as lm', 'lm.id', '=', 'lmi.lista_maestra_id')
                            ->join('commercial_cycles as cc', 'cc.id', '=', 'lm.cycle_id')
                            ->whereColumn('pi.pedido_id', 'pedidos.id')
                            ->where('cc.code', 'like', "%{$this->filtroCiclo}%")
                    )
                )
            )
            ->get();

        $campanaDetalle = $this->detalleCampanaId
            ? Campana::with([
                'tipoContacto', 'accion', 'responsable',
                'actividades.tipoContacto', 'actividades.accion',
                'actividades.responsable', 'actividades.caso.pedido.cliente',
              ])->find($this->detalleCampanaId)
            : null;

        return view('livewire.credito.actividades-masivas', compact(
            'campanas', 'tiposContacto', 'acciones', 'tiposRespuesta', 'tiposCancelacion',
            'usuarios', 'casosQuery', 'campanaDetalle'
        ));
    }
}
