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

    public string $mode = 'list'; // list | detail | casos

    // Detalle
    public ?int $detalleCampanaId = null;

    // Casos vinculados a campaña
    public ?int $casosCampanaId = null;

    // Filtros list
    public string $search       = '';
    public string $filtroEstado = '';

    // Form inline campaña (nueva)
    public bool   $showAddForm   = false;
    public string $nombre        = '';
    public int    $tipoContactoId  = 0;
    public int    $accionId        = 0;
    public string $fechaProgramada = '';
    public int    $responsableId   = 0;
    public string $observacion     = '';

    // Edición inline en fila
    public ?int   $editingCampanaId   = null;
    public string $editNombre         = '';
    public int    $editTipoContactoId = 0;
    public int    $editAccionId       = 0;
    public string $editFechaProgramada = '';
    public int    $editResponsableId  = 0;
    public string $editObservacion    = '';

    // Filtros modo casos
    public string $filtroCasoEstado = '';
    public string $filtroBusqueda   = '';
    public array  $selectedCasoIds  = [];
    public bool   $casosSaved       = false;

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
        $this->editingCampanaId    = $c->id;
        $this->editNombre          = $c->nombre;
        $this->editTipoContactoId  = $c->tipo_contacto_id ?? 0;
        $this->editAccionId        = $c->accion_id ?? 0;
        $this->editFechaProgramada = $c->fecha_programada?->format('Y-m-d') ?? '';
        $this->editResponsableId   = $c->responsable_id ?? auth()->id();
        $this->editObservacion     = $c->observacion ?? '';
    }

    public function saveEdit(): void
    {
        $campana = Campana::findOrFail($this->editingCampanaId);
        if ($campana->estado !== 'abierta') {
            $this->cancelEdit();
            return;
        }

        $this->validate([
            'editNombre'          => 'required|string|max:150',
            'editTipoContactoId'  => 'required|integer|min:1',
            'editAccionId'        => 'required|integer|min:1',
            'editFechaProgramada' => 'required|date',
            'editResponsableId'   => 'required|integer|min:1',
        ], [
            'editNombre.required'         => 'El nombre es obligatorio.',
            'editTipoContactoId.min'      => 'Seleccioná un tipo de contacto.',
            'editAccionId.min'            => 'Seleccioná una acción.',
            'editFechaProgramada.required'=> 'La fecha es obligatoria.',
            'editResponsableId.min'       => 'Seleccioná un responsable.',
        ]);

        $campana->update([
            'nombre'           => $this->editNombre,
            'tipo_contacto_id' => $this->editTipoContactoId,
            'accion_id'        => $this->editAccionId,
            'fecha_programada' => $this->editFechaProgramada,
            'responsable_id'   => $this->editResponsableId,
            'observacion'      => $this->editObservacion ?: null,
        ]);
        $campana->actividades()->where('estado', 'abierta')->update([
            'tipo_contacto_id' => $this->editTipoContactoId,
            'accion_id'        => $this->editAccionId,
            'fecha_programada' => $this->editFechaProgramada,
            'responsable_id'   => $this->editResponsableId,
            'observacion'      => $this->editObservacion ?: null,
        ]);

        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->editingCampanaId    = null;
        $this->editNombre          = '';
        $this->editTipoContactoId  = 0;
        $this->editAccionId        = 0;
        $this->editFechaProgramada = '';
        $this->editResponsableId   = 0;
        $this->editObservacion     = '';
        $this->resetValidation();
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

        Campana::create([
            'nombre'           => $this->nombre,
            'tipo_contacto_id' => $this->tipoContactoId,
            'accion_id'        => $this->accionId,
            'fecha_programada' => $this->fechaProgramada,
            'responsable_id'   => $this->responsableId,
            'observacion'      => $this->observacion ?: null,
            'creado_por'       => auth()->id(),
        ]);

        $this->resetFormFields();
        $this->showAddForm = false;
    }

    public function verCasos(int $id): void
    {
        $this->casosCampanaId   = $id;
        $this->filtroCasoEstado = '';
        $this->filtroCiclo      = '';
        // Pre-seleccionar casos ya vinculados
        $this->selectedCasoIds  = CobranzaActividad::where('campana_id', $id)
            ->pluck('caso_id')->map(fn($v) => (int)$v)->toArray();
        $this->mode = 'casos';
    }

    public function guardarCasos(): void
    {
        $campana    = Campana::findOrFail($this->casosCampanaId);
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

        $this->casosSaved = true;
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        if ($estado === 'en_proceso') {
            $campana = Campana::findOrFail($id);
            if ($campana->actividades()->count() === 0) return;
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
        $this->casosCampanaId   = null;
        $this->selectedCasoIds  = [];
        $this->mode             = 'list';
    }

    private function resetFormFields(): void
    {
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

        $casosQuery = CobranzaCaso::with(['pedido.cliente', 'pedido.items.listaMaestraItem.listaMaestra.cycle'])
            ->whereIn('estado', ['asignado', 'en_gestion'])
            ->where('responsable_id', auth()->id())
            ->when($this->filtroCasoEstado, fn($q) => $q->where('estado', $this->filtroCasoEstado))
            ->when($this->filtroBusqueda, function($q) {
                $b = $this->filtroBusqueda;
                $q->where(function($sub) use ($b) {
                    // por número de pedido
                    $sub->whereHas('pedido', fn($p) => $p->where('numero', 'like', "%{$b}%"))
                    // por nombre o CI del cliente
                    ->orWhereHas('pedido.cliente', fn($p) =>
                        $p->where('nombre_completo', 'like', "%{$b}%")
                          ->orWhere('ci', 'like', "%{$b}%")
                    )
                    // por código de ciclo
                    ->orWhereHas('pedido', fn($p) =>
                        $p->whereExists(fn($ex) =>
                            $ex->select(DB::raw(1))
                               ->from('pedido_items as pi')
                               ->join('lista_maestra_items as lmi', 'lmi.id', '=', 'pi.lista_maestra_item_id')
                               ->join('lista_maestra as lm', 'lm.id', '=', 'lmi.lista_maestra_id')
                               ->join('commercial_cycles as cc', 'cc.id', '=', 'lm.cycle_id')
                               ->whereColumn('pi.pedido_id', 'pedidos.id')
                               ->where('cc.code', 'like', "%{$b}%")
                        )
                    );
                });
            })
            ->get();

        $casosCampana = $this->casosCampanaId
            ? Campana::find($this->casosCampanaId)
            : null;

        $campanaDetalle = $this->detalleCampanaId
            ? Campana::with([
                'tipoContacto', 'accion', 'responsable',
                'actividades.tipoContacto', 'actividades.accion',
                'actividades.responsable',
                'actividades.caso.pedido.cliente',
                'actividades.caso.pedido.items.listaMaestraItem.listaMaestra.cycle',
              ])->find($this->detalleCampanaId)
            : null;

        return view('livewire.credito.actividades-masivas', compact(
            'campanas', 'tiposContacto', 'acciones', 'tiposRespuesta', 'tiposCancelacion',
            'usuarios', 'casosQuery', 'casosCampana', 'campanaDetalle'
        ));
    }
}
