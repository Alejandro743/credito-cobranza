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

    public string $mode = 'list';

    // Detalle
    public ?int $detalleCampanaId = null;

    // Filtros list
    public string $search       = '';
    public string $filtroEstado = '';

    // Form campaña
    public int    $campanaId       = 0;
    public string $nombre          = '';
    public int    $tipoContactoId  = 0;
    public int    $accionId        = 0;
    public string $fechaProgramada = '';
    public int    $responsableId   = 0;
    public string $observacion     = '';

    // Selección de casos
    public string $filtroCasoEstado = 'asignado';
    public string $filtroCiclo      = '';
    public array  $selectedCasoIds  = [];

    protected $queryString = [
        'search'       => ['except' => ''],
        'filtroEstado' => ['except' => ''],
    ];

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingFiltroEstado(): void { $this->resetPage(); }

    public function verDetalle(int $id): void
    {
        $this->detalleCampanaId = $id;
        $this->mode = 'detail';
    }

    public function create(): void
    {
        $this->resetForm();
        $this->responsableId   = auth()->id();
        $this->fechaProgramada = now()->format('Y-m-d');
        $this->mode = 'form';
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
        $this->selectedCasoIds = [];
        $this->mode = 'form';
    }

    public function save(): void
    {
        $this->validate([
            'nombre'         => 'required|string|max:150',
            'tipoContactoId' => 'required|integer|min:1',
            'accionId'       => 'required|integer|min:1',
            'fechaProgramada'=> 'required|date',
            'responsableId'  => 'required|integer|min:1',
        ], [
            'nombre.required'          => 'El nombre es obligatorio.',
            'tipoContactoId.min'       => 'Seleccioná un tipo de contacto.',
            'accionId.min'             => 'Seleccioná una acción.',
            'fechaProgramada.required' => 'La fecha programada es obligatoria.',
            'responsableId.min'        => 'Seleccioná un responsable.',
        ]);

        $campana = Campana::updateOrCreate(
            ['id' => $this->campanaId ?: null],
            [
                'nombre'          => $this->nombre,
                'tipo_contacto_id'=> $this->tipoContactoId,
                'accion_id'       => $this->accionId,
                'fecha_programada'=> $this->fechaProgramada,
                'responsable_id'  => $this->responsableId,
                'observacion'     => $this->observacion ?: null,
                'creado_por'      => $this->campanaId ? null : auth()->id(),
            ]
        );

        // Generar actividades para los casos seleccionados (solo nuevas)
        if (!empty($this->selectedCasoIds)) {
            $existentes = CobranzaActividad::where('campana_id', $campana->id)
                ->pluck('caso_id')->toArray();

            foreach ($this->selectedCasoIds as $casoId) {
                if (in_array($casoId, $existentes)) continue;

                $caso = CobranzaCaso::find($casoId);
                if (!$caso) continue;

                $ultimo = CobranzaActividad::where('caso_id', $casoId)->max('numero') ?? 0;

                CobranzaActividad::create([
                    'caso_id'          => $casoId,
                    'campana_id'       => $campana->id,
                    'numero'           => $ultimo + 1,
                    'tipo_contacto_id' => $this->tipoContactoId,
                    'accion_id'        => $this->accionId,
                    'fecha_programada' => $this->fechaProgramada,
                    'responsable_id'   => $this->responsableId,
                    'observacion'      => $this->observacion ?: null,
                    'estado'           => 'abierta',
                ]);

                // Pasar caso a en_gestion si estaba asignado
                if ($caso->estado === 'asignado') {
                    $caso->update(['estado' => 'en_gestion']);
                }
            }
        }

        $this->backToList();
    }

    public function cambiarEstado(int $id, string $estado): void
    {
        Campana::findOrFail($id)->update(['estado' => $estado]);
    }

    public function delete(int $id): void
    {
        $c = Campana::findOrFail($id);
        if ($c->actividades()->count() > 0) return;
        $c->delete();
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->detalleCampanaId = null;
        $this->mode = 'list';
    }

    private function resetForm(): void
    {
        $this->campanaId       = 0;
        $this->nombre          = '';
        $this->tipoContactoId  = 0;
        $this->accionId        = 0;
        $this->fechaProgramada = '';
        $this->responsableId   = 0;
        $this->observacion     = '';
        $this->selectedCasoIds = [];
        $this->filtroCasoEstado = 'asignado';
        $this->filtroCiclo      = '';
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

        $tiposContacto = CobranzaCatalogo::where('tipo', 'tipo_contacto')->where('activo', true)->orderBy('nombre')->get();
        $acciones      = CobranzaCatalogo::where('tipo', 'accion')->where('activo', true)->orderBy('nombre')->get();
        $usuarios      = User::where('active', true)->orderBy('name')->get();

        // Casos disponibles para seleccionar
        $today   = now()->toDateString();
        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1)';

        $casosQuery = CobranzaCaso::with(['pedido.cliente', 'pedido.vendedor'])
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
            'campanas', 'tiposContacto', 'acciones', 'usuarios', 'casosQuery', 'campanaDetalle'
        ));
    }
}
