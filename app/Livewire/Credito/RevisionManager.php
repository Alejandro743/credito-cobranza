<?php

namespace App\Livewire\Credito;

use App\Models\Ciudad;
use App\Models\ListaAcceso;
use App\Models\ListaMaestra;
use App\Models\Municipio;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\PesoIndicador;
use App\Models\Provincia;
use App\Models\RangoCalificacion;
use App\Services\ClienteCalificacionService;
use App\Services\VendedorCalificacionService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RevisionManager extends Component
{
    use WithPagination, WithFileUploads;

    /** Otra pestaña/pantalla cambió pedidos compartidos: refrescar esta grilla. */
    #[On('pedidos-actualizados')]
    public function refrescarPorEvento(): void {}

    public string $mode               = 'list';
    public ?int   $viewingId          = null;
    public bool   $confirmandoRechazo = false;
    public string $notaRechazo        = '';
    public string $sortBy             = '';
    public string $sortDir            = 'asc';

    // ── Filtros por columna ──────────────────────────────────────────────────
    public string $colFilterCiclo    = '';
    public string $colFilterNumero   = '';
    public string $colFilterCi       = '';
    public string $colFilterCliente  = '';
    public string $colFilterVendedor = '';
    public string $colFilterFechaPlan     = '';
    public string $colFilterFechaRevisar  = '';
    public string $colFilterAsignadoPor = '';

    public function updatingColFilterCiclo():         void { $this->resetPage(); }
    public function updatingColFilterNumero():        void { $this->resetPage(); }
    public function updatingColFilterCi():            void { $this->resetPage(); }
    public function updatingColFilterCliente():       void { $this->resetPage(); }
    public function updatingColFilterVendedor():      void { $this->resetPage(); }
    public function updatingColFilterFechaPlan():     void { $this->resetPage(); }
    public function updatingColFilterFechaRevisar():  void { $this->resetPage(); }
    public function updatingColFilterAsignadoPor(): void { $this->resetPage(); }

    public ?int $selectedPedidoId = null;

    public function selectPedido(int $id): void
    {
        $this->selectedPedidoId = $this->selectedPedidoId === $id ? null : $id;
    }

    // Documentos subibles
    public $docAnversoCi  = null;
    public $docReversoCi  = null;
    public $docAnversoDoc = null;
    public $docReversoDoc = null;
    public $docAvisoLuz   = null;

    // Dirección de entrega
    public string $editTipoEntrega = 'domicilio';
    public string $editCiudad      = '';
    public string $editProvincia   = '';
    public string $editMunicipio   = '';
    public string $editDireccion   = '';
    public string $editReferencia  = '';

    // Editar artículos
    public bool   $editandoArticulos    = false;
    public array  $articulosEdit        = [];
    public array  $articulosDisponibles = [];
    public string $searchProductoEdit   = '';

    public function updatedEditCiudad(): void   { $this->editProvincia = ''; $this->editMunicipio = ''; }
    public function updatedEditProvincia(): void { $this->editMunicipio = ''; }

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
        $this->viewingId          = $id;
        $this->selectedPedidoId   = $id;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->docAnversoCi       = null;
        $this->docReversoCi       = null;
        $this->docAnversoDoc      = null;
        $this->docReversoDoc      = null;
        $this->docAvisoLuz        = null;
        $this->editandoArticulos    = false;
        $this->articulosEdit        = [];
        $this->articulosDisponibles = [];
        $this->searchProductoEdit   = '';

        $pedido = Pedido::find($id);
        $this->editTipoEntrega = $pedido?->tipo_entrega     ?? 'domicilio';
        $this->editCiudad      = $pedido?->entrega_ciudad    ?? '';
        $this->editProvincia   = $pedido?->entrega_provincia ?? '';
        $this->editMunicipio   = $pedido?->entrega_municipio ?? '';
        $this->editDireccion   = $pedido?->entrega_direccion ?? '';
        $this->editReferencia  = $pedido?->entrega_referencia ?? '';

        $this->mode = 'detail';
    }

    public function verSeleccionado(): void
    {
        if ($this->selectedPedidoId) {
            $this->ver($this->selectedPedidoId);
        }
    }

    public function subirDocumento(string $campo): void
    {
        $propMap = [
            'doc_anverso_ci'  => 'docAnversoCi',
            'doc_reverso_ci'  => 'docReversoCi',
            'doc_anverso_doc' => 'docAnversoDoc',
            'doc_reverso_doc' => 'docReversoDoc',
            'doc_aviso_luz'   => 'docAvisoLuz',
        ];

        $prop = $propMap[$campo] ?? null;
        if (!$prop || !$this->$prop) return;

        $this->validateOnly($prop, [
            $prop => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pedido = Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail();

        $path = $this->$prop->store("pedidos/{$pedido->id}/docs", 'public');
        $pedido->update([$campo => $path]);

        $this->$prop = null;
    }

    public function resetDireccionEdit(): void
    {
        if (!$this->viewingId) return;
        $pedido = Pedido::find($this->viewingId);
        $this->editTipoEntrega = $pedido?->tipo_entrega      ?? 'domicilio';
        $this->editCiudad      = $pedido?->entrega_ciudad    ?? '';
        $this->editProvincia   = $pedido?->entrega_provincia ?? '';
        $this->editMunicipio   = $pedido?->entrega_municipio ?? '';
        $this->editDireccion   = $pedido?->entrega_direccion ?? '';
        $this->editReferencia  = $pedido?->entrega_referencia ?? '';
        $this->dispatch('reset-dir', tipo: $this->editTipoEntrega);
    }

    public function guardarDireccion(string $tipoEntrega = ''): void
    {
        if ($tipoEntrega) $this->editTipoEntrega = $tipoEntrega;

        $pedido = Pedido::with('cliente')
            ->where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail();

        if ($this->editTipoEntrega === 'domicilio') {
            $pedido->update([
                'tipo_entrega'      => 'domicilio',
                'entrega_ciudad'    => $pedido->cliente->ciudad,
                'entrega_provincia' => $pedido->cliente->provincia,
                'entrega_municipio' => $pedido->cliente->municipio,
                'entrega_direccion' => $pedido->cliente->direccion ?? '',
                'entrega_referencia'=> null,
            ]);
        } else {
            $this->validate([
                'editDireccion' => 'required|string|max:500',
                'editCiudad'    => 'nullable|string|max:150',
                'editProvincia' => 'nullable|string|max:150',
                'editMunicipio' => 'nullable|string|max:150',
                'editReferencia'=> 'nullable|string|max:500',
            ]);

            $pedido->update([
                'tipo_entrega'      => 'nuevo',
                'entrega_ciudad'    => trim($this->editCiudad)    ?: null,
                'entrega_provincia' => trim($this->editProvincia) ?: null,
                'entrega_municipio' => trim($this->editMunicipio) ?: null,
                'entrega_direccion' => trim($this->editDireccion),
                'entrega_referencia'=> trim($this->editReferencia) ?: null,
            ]);
        }

        $this->dispatch('direccion-guardada');
    }

    public function abrirEditarArticulos(): void
    {
        $pedido = Pedido::with(['items.product', 'items.listaMaestraItem.product', 'items.listaMaestraItem.maestroArticulo', 'vendedor.user', 'cliente.usuario'])->find($this->viewingId);
        if (!$pedido) return;

        $this->articulosEdit = $pedido->items->map(fn($item) => [
            'item_id'               => $item->id,
            'lista_maestra_item_id' => $item->lista_maestra_item_id,
            'product_id'            => $item->product_id,
            'nombre'                => $item->product?->name
                ?? $item->listaMaestraItem?->product?->name
                ?? $item->listaMaestraItem?->maestroArticulo?->nombre
                ?? '—',
            'codigo'                => $item->product?->code
                ?? $item->listaMaestraItem?->product?->code
                ?? $item->listaMaestraItem?->maestroArticulo?->codigo
                ?? '',
            'cantidad'              => (int) $item->cantidad,
            'cantidad_original'     => (int) $item->cantidad,
            'precio_unitario'       => (float) $item->precio_unitario,
            'puntos'                => (int) $item->puntos,
            'subtotal'              => round((float) $item->precio_unitario * (int) $item->cantidad, 2),
            'stock_disponible'      => (float) ($item->listaMaestraItem?->stock_actual ?? 999),
        ])->values()->toArray();

        $this->articulosDisponibles = $this->cargarArticulosDisponibles($pedido);
        $this->searchProductoEdit   = '';
        $this->editandoArticulos    = true;
        $this->dispatch('art-modal-open');
    }

    private function cargarArticulosDisponibles(Pedido $pedido): array
    {
        $vendedorUserId = $pedido->vendedor?->user_id;
        $clienteUserId  = $pedido->cliente?->usuario_id;

        $todasIds = ListaMaestra::where('active', true)->pluck('id');
        if ($todasIds->isEmpty()) return [];

        $listasConVendedores      = ListaAcceso::where('tipo', 'vendedor')->whereIn('lista_maestra_id', $todasIds)->pluck('lista_maestra_id')->unique();
        $listasVendedorExplicito  = ListaAcceso::where('tipo', 'vendedor')->whereIn('lista_maestra_id', $todasIds)->where('user_id', $vendedorUserId)->pluck('lista_maestra_id');
        $accesoVendedor           = $listasVendedorExplicito->merge($todasIds->diff($listasConVendedores))->unique();

        $listasConClientes        = ListaAcceso::where('tipo', 'cliente')->whereIn('lista_maestra_id', $todasIds)->pluck('lista_maestra_id')->unique();
        $listasClienteExplicito   = ListaAcceso::where('tipo', 'cliente')->whereIn('lista_maestra_id', $todasIds)->where('user_id', $clienteUserId)->pluck('lista_maestra_id');
        $accesoCliente            = $listasClienteExplicito->merge($todasIds->diff($listasConClientes))->unique();

        $comunes = $accesoVendedor->intersect($accesoCliente);
        if ($comunes->isEmpty()) return [];

        $listas = ListaMaestra::whereIn('id', $comunes)->where('active', true)
            ->with(['items' => fn($q) => $q->where('active', true)->where('stock_actual', '>', 0)->with([
                'product' => fn($p) => $p->where('active', true),
                'maestroArticulo',
            ])])
            ->get();

        $disponibles = [];
        foreach ($listas as $lista) {
            foreach ($lista->items as $item) {
                $nombre = $item->product?->name ?? $item->maestroArticulo?->nombre;
                if (!$nombre) continue;
                $codigo = $item->product?->code ?? $item->maestroArticulo?->codigo ?? '';
                $key = $item->product_id ? 'p'.$item->product_id : 'i'.$item->id;
                $precioFinal = (float) $item->precio_final;
                if (!isset($disponibles[$key]) || $precioFinal < $disponibles[$key]['precio']) {
                    $disponibles[$key] = [
                        'item_id'     => $item->id,
                        'product_id'  => $item->product_id,
                        'nombre'      => $nombre,
                        'codigo'      => $codigo,
                        'precio'      => $precioFinal,
                        'puntos'      => (int) $item->puntos,
                        'stock'       => (float) $item->stock_actual,
                        'lista_id'    => (string) $lista->id,
                        'lista_nombre'=> $lista->name,
                        'lista_code'  => $lista->code ?? '',
                    ];
                }
            }
        }

        return array_values($disponibles);
    }

    public function agregarOActualizarEdit(int $itemId, int $cantidad = 1): void
    {
        $cantidad = max(1, $cantidad);
        $prod = collect($this->articulosDisponibles)->firstWhere('item_id', $itemId);
        if (!$prod) return;

        foreach ($this->articulosEdit as $i => $a) {
            if ($a['lista_maestra_item_id'] == $itemId) {
                $this->articulosEdit[$i]['cantidad'] = $cantidad;
                $this->articulosEdit[$i]['subtotal']  = round((float) $a['precio_unitario'] * $cantidad, 2);
                return;
            }
        }

        $this->articulosEdit[] = [
            'item_id'               => null,
            'lista_maestra_item_id' => $prod['item_id'],
            'product_id'            => $prod['product_id'],
            'nombre'                => $prod['nombre'],
            'codigo'                => $prod['codigo'],
            'cantidad'              => $cantidad,
            'cantidad_original'     => 0,
            'precio_unitario'       => $prod['precio'],
            'puntos'                => $prod['puntos'],
            'subtotal'              => round($prod['precio'] * $cantidad, 2),
            'stock_disponible'      => $prod['stock'],
        ];
    }

    public function quitarPorProductoEdit(int $itemId): void
    {
        if (count($this->articulosEdit) <= 1) return;
        foreach ($this->articulosEdit as $i => $a) {
            if ($a['lista_maestra_item_id'] == $itemId) {
                array_splice($this->articulosEdit, $i, 1);
                return;
            }
        }
    }

    public function agregarArticuloEdit(int $itemId): void
    {
        $this->agregarOActualizarEdit($itemId, 1);
    }

    public function updatedArticulosEdit($value, $key): void
    {
        if (!str_ends_with($key, '.cantidad')) return;

        $index    = (int) explode('.', $key)[0];
        $cantidad = max(1, (int) $value);

        $this->articulosEdit[$index]['cantidad'] = $cantidad;
        $this->articulosEdit[$index]['subtotal']  = round(
            (float) $this->articulosEdit[$index]['precio_unitario'] * $cantidad,
            2
        );
    }

    public function quitarArticuloEdit(int $index): void
    {
        if (count($this->articulosEdit) <= 1) return;
        array_splice($this->articulosEdit, $index, 1);
    }

    public function guardarArticulos(): void
    {
        if (empty($this->articulosEdit)) {
            session()->flash('error', 'Debe haber al menos un artículo.');
            return;
        }

        foreach ($this->articulosEdit as $art) {
            if ((int) $art['cantidad'] < 1) {
                session()->flash('error', 'Todas las cantidades deben ser al menos 1.');
                return;
            }
        }

        DB::transaction(function () {
            $pedido = Pedido::with(['items.listaMaestraItem', 'planPago.cuotas'])
                ->find($this->viewingId);

            if (!$pedido || $pedido->estado !== 'revision') return;

            $nuevosItems = collect($this->articulosEdit)->keyBy('item_id');

            foreach ($pedido->items as $item) {
                if (!$nuevosItems->has($item->id)) {
                    if ($item->listaMaestraItem) {
                        $lmi = $item->listaMaestraItem;
                        $lmi->stock_comprometido = max(0, (float)$lmi->stock_comprometido - $item->cantidad);
                        $lmi->save();
                    }
                    $item->delete();
                } else {
                    $nuevo = $nuevosItems->get($item->id);
                    $diff  = (int) $nuevo['cantidad'] - (int) $nuevo['cantidad_original'];
                    if ($diff !== 0 && $item->listaMaestraItem) {
                        $lmi = $item->listaMaestraItem;
                        $lmi->stock_comprometido = max(0, (float)$lmi->stock_comprometido + $diff);
                        $lmi->save();
                    }
                    $item->update([
                        'cantidad' => (int) $nuevo['cantidad'],
                        'subtotal' => round((float) $item->precio_unitario * (int) $nuevo['cantidad'], 2),
                    ]);
                }
            }

            // Nuevos items (cantidad_original = 0)
            foreach ($this->articulosEdit as $art) {
                if ($art['item_id'] !== null || (int) $art['cantidad_original'] !== 0) continue;
                $lmiModel = \App\Models\ListaMaestraItem::find($art['lista_maestra_item_id']);
                if ($lmiModel) {
                    $lmiModel->stock_comprometido = (float)$lmiModel->stock_comprometido + (int) $art['cantidad'];
                    $lmiModel->save();
                }
                PedidoItem::create([
                    'pedido_id'             => $pedido->id,
                    'lista_maestra_item_id' => $art['lista_maestra_item_id'],
                    'product_id'            => $art['product_id'],
                    'cantidad'              => (int) $art['cantidad'],
                    'precio_unitario'       => (float) $art['precio_unitario'],
                    'puntos'                => (int) $art['puntos'],
                    'subtotal'              => round((float) $art['precio_unitario'] * (int) $art['cantidad'], 2),
                ]);
            }

            $pedido->refresh();
            $nuevoTotal = round($pedido->items->sum('subtotal'), 2);
            $pedido->update(['total' => $nuevoTotal, 'total_pagar' => $nuevoTotal]);

            $plan = $pedido->planPago;
            if ($plan) {
                $cuotaInicial  = $plan->cuotas->firstWhere('numero', 0);
                $montoInicial  = $cuotaInicial ? (float) $cuotaInicial->monto : 0.0;
                $saldo         = max(0, $nuevoTotal - $montoInicial);
                $cuotasReg     = $plan->cuotas->where('numero', '>', 0)->sortBy('numero')->values();
                $cantCuotas    = $cuotasReg->count();

                if ($cantCuotas > 0) {
                    $montoCuota  = $cantCuotas > 0 ? floor(($saldo / $cantCuotas) * 100) / 100 : 0;
                    $redondeo    = round($saldo - ($montoCuota * $cantCuotas), 2);

                    foreach ($cuotasReg as $i => $cuota) {
                        $monto = $montoCuota + ($i === $cantCuotas - 1 ? $redondeo : 0);
                        $cuota->update(['monto' => round($monto, 2)]);
                    }

                    $plan->update([
                        'saldo_financiar' => $saldo,
                        'monto_cuota'     => $montoCuota,
                        'total_pagar'     => $nuevoTotal,
                        'cantidad_cuotas' => $cantCuotas,
                    ]);
                } else {
                    $plan->update([
                        'saldo_financiar' => $saldo,
                        'total_pagar'     => $nuevoTotal,
                    ]);
                }
            }
        });

        $this->editandoArticulos    = false;
        $this->articulosEdit        = [];
        $this->articulosDisponibles = [];
        $this->searchProductoEdit   = '';
        $this->dispatch('art-modal-close');
        session()->flash('success', 'Artículos actualizados correctamente.');
    }

    public function cerrarEditarArticulos(): void
    {
        $this->editandoArticulos    = false;
        $this->articulosEdit        = [];
        $this->articulosDisponibles = [];
        $this->searchProductoEdit   = '';
        $this->dispatch('art-modal-close');
    }

    public function devolverEspera(): void
    {
        Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->firstOrFail()
            ->update(['estado' => 'en_espera', 'revisado_por' => null, 'revisado_en' => null]);

        session()->flash('success', 'Pedido devuelto a En Espera.');
        $this->dispatch('pedidos-actualizados');
        $this->backToList();
    }

    public function aprobar(): void
    {
        $pedido = Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->with('items')
            ->firstOrFail();

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $lmi = \App\Models\ListaMaestraItem::find($item->lista_maestra_item_id);
                if ($lmi) {
                    $lmi->stock_comprometido = max(0, (float)$lmi->stock_comprometido - $item->cantidad);
                    $lmi->stock_actual       = max(0, (float)$lmi->stock_actual - $item->cantidad);
                    $lmi->stock_consumido    = (float)$lmi->stock_consumido + $item->cantidad;
                    $lmi->save();
                }
            }
            $pedido->update(['estado' => 'aprobado', 'notas' => null, 'aprobado_por_id' => auth()->id()]);
        });

        session()->flash('success', 'Pedido aprobado correctamente.');
        $this->dispatch('pedidos-actualizados');
        $this->backToList();
    }

    public function rechazar(): void
    {
        $this->validate(['notaRechazo' => 'required|min:5'], [
            'notaRechazo.required' => 'Ingresá el motivo del rechazo.',
            'notaRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::where('id', $this->viewingId)
            ->where('estado', 'revision')
            ->with('items')
            ->firstOrFail();

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->items as $item) {
                $lmi = \App\Models\ListaMaestraItem::find($item->lista_maestra_item_id);
                if ($lmi) {
                    $lmi->stock_comprometido = max(0, (float)$lmi->stock_comprometido - $item->cantidad);
                    $lmi->save();
                }
            }
            $pedido->update(['estado' => 'rechazado', 'notas' => $this->notaRechazo]);
        });

        session()->flash('success', 'Pedido rechazado.');
        $this->dispatch('pedidos-actualizados');
        $this->backToList();
    }

    public function backToList(): void
    {
        $this->viewingId          = null;
        $this->confirmandoRechazo = false;
        $this->notaRechazo        = '';
        $this->docAnversoCi       = null;
        $this->docReversoCi       = null;
        $this->docAnversoDoc      = null;
        $this->docReversoDoc      = null;
        $this->docAvisoLuz        = null;
        $this->editTipoEntrega    = 'domicilio';
        $this->editCiudad         = '';
        $this->editProvincia      = '';
        $this->editMunicipio      = '';
        $this->editDireccion      = '';
        $this->editReferencia     = '';
        $this->editandoArticulos    = false;
        $this->articulosEdit        = [];
        $this->articulosDisponibles = [];
        $this->searchProductoEdit   = '';
        $this->mode               = 'list';
    }

    public function render()
    {
        $cicloSub = '(SELECT cc.code FROM pedido_items pi INNER JOIN lista_maestra_items lmi ON lmi.id = pi.lista_maestra_item_id INNER JOIN lista_maestra lm ON lm.id = lmi.lista_maestra_id INNER JOIN commercial_cycles cc ON cc.id = lm.cycle_id WHERE pi.pedido_id = pedidos.id LIMIT 1) as ciclo_code';

        $pedidos = Pedido::with(['cliente.usuario', 'vendedor.user', 'asignadoPor'])
            ->select('pedidos.*')
            ->addSelect(DB::raw($cicloSub))
            ->where('estado', 'revision')
            ->where('asignado_a_id', auth()->id())
            ->when($this->colFilterNumero,   fn($q) => $q->where('pedidos.numero', 'like', "%{$this->colFilterNumero}%"))
            ->when($this->colFilterCi,       fn($q) => $q->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->colFilterCi}%")))
            ->when($this->colFilterCliente,  fn($q) => $q->whereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->colFilterCliente}%")))
            ->when($this->colFilterVendedor, fn($q) => $q->whereHas('vendedor.user', fn($u) => $u->where('name', 'like', "%{$this->colFilterVendedor}%")))
            ->when($this->colFilterCiclo,    fn($q) => $q->whereHas('items.listaMaestraItem.listaMaestra.cycle', fn($c) => $c->where('code', 'like', "%{$this->colFilterCiclo}%")))
            ->when($this->colFilterFechaPlan,    fn($q) => $q->whereDate('pedidos.created_at', $this->colFilterFechaPlan))
            ->when($this->colFilterFechaRevisar, fn($q) => $q->whereDate('pedidos.revisado_en', $this->colFilterFechaRevisar))
            ->when($this->colFilterAsignadoPor, fn($q) => $q->whereHas('asignadoPor', fn($u) => $u->where('name', 'like', "%{$this->colFilterAsignadoPor}%")))
            ->when($this->sortBy === 'cliente',  fn($q) => $q->orderBy(DB::table('users')->join('clientes','clientes.usuario_id','=','users.id')->whereColumn('clientes.id','pedidos.cliente_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'vendedor', fn($q) => $q->orderBy(DB::table('users')->join('vendedores','vendedores.user_id','=','users.id')->whereColumn('vendedores.id','pedidos.vendedor_id')->select('users.name'), $this->sortDir))
            ->when($this->sortBy === 'ci',       fn($q) => $q->orderBy(DB::table('clientes')->whereColumn('clientes.id','pedidos.cliente_id')->select('ci'), $this->sortDir))
            ->when(in_array($this->sortBy, ['numero','fecha_plan','fecha_revisar','total']), fn($q) => $q->orderBy(match($this->sortBy) { 'numero'=>'pedidos.numero', 'fecha_plan'=>'pedidos.created_at', 'fecha_revisar'=>'pedidos.revisado_en', 'total'=>'pedidos.total_pagar' }, $this->sortDir))
            ->when(!$this->sortBy, fn($q) => $q->orderByDesc('created_at'))
            ->paginate(15);

        $pedidoDetalle      = null;
        $clienteCalificacion = null;
        $clienteHistorial    = collect();
        $vendedorCalificacion = null;
        $vendedorHistorial    = collect();
        if ($this->mode === 'detail' && $this->viewingId) {
            $pedidoDetalle = Pedido::with([
                'cliente.usuario', 'vendedor.user',
                'items.product', 'planPago.cuotas',
            ])->find($this->viewingId);

            if ($pedidoDetalle) {
                $pesos  = PesoIndicador::vigente() ?? PesoIndicador::porDefecto();
                $rangos = RangoCalificacion::vigente() ?? RangoCalificacion::porDefecto();

                $calService = new ClienteCalificacionService();
                $clienteCalificacion = $calService->calcularParaCliente($pedidoDetalle->cliente, $pesos, $rangos);
                $clienteHistorial    = $calService->calcularDetallePedidos($pedidoDetalle->cliente_id);

                if ($pedidoDetalle->vendedor) {
                    $vendService = new VendedorCalificacionService();
                    $vendedorCalificacion = $vendService->calcularParaVendedor($pedidoDetalle->vendedor, $pesos, $rangos);
                    $vendedorHistorial    = $vendService->calcularDetallePedidos($pedidoDetalle->vendedor_id);
                }
            }
        }

        $ciudadesAll     = Ciudad::orderBy('nombre')->get();
        $ciudadObj       = Ciudad::where('nombre', $this->editCiudad)->first();
        $editProvincias  = $ciudadObj ? Provincia::where('ciudad_id', $ciudadObj->id)->orderBy('nombre')->get() : collect();
        $provObj         = Provincia::where('nombre', $this->editProvincia)->where('ciudad_id', $ciudadObj?->id)->first();
        $editMunicipios  = $provObj ? Municipio::where('provincia_id', $provObj->id)->orderBy('nombre')->get() : collect();

        $editTipoEntrega      = $this->editTipoEntrega;
        $articulosEdit        = $this->articulosEdit;
        $searchProductoEdit   = $this->searchProductoEdit;

        $idsEnEdit = collect($articulosEdit)->pluck('product_id')->filter()->toArray();
        $q = strtolower(trim($searchProductoEdit));
        $articulosAgrupados = collect($this->articulosDisponibles)
            ->filter(fn($p) => !in_array($p['product_id'], $idsEnEdit))
            ->when($q, fn($c) => $c->filter(fn($p) => str_contains(strtolower($p['nombre']), $q) || str_contains(strtolower($p['codigo']), $q)))
            ->groupBy('lista_id')
            ->map(fn($items, $listaId) => [
                'lista_id'    => $listaId,
                'lista_nombre'=> $items->first()['lista_nombre'],
                'lista_code'  => $items->first()['lista_code'],
                'productos'   => $items->values()->toArray(),
            ])
            ->values()
            ->toArray();

        $articulosTodos = collect($this->articulosDisponibles)
            ->when($q, fn($c) => $c->filter(fn($p) => str_contains(strtolower($p['nombre']), $q) || str_contains(strtolower($p['codigo']), $q)))
            ->groupBy('lista_id')
            ->map(fn($items, $listaId) => [
                'lista_id'    => $listaId,
                'lista_nombre'=> $items->first()['lista_nombre'],
                'lista_code'  => $items->first()['lista_code'],
                'productos'   => $items->values()->toArray(),
            ])
            ->values()
            ->toArray();

        return view('livewire.credito.revision-manager', compact('pedidos', 'pedidoDetalle', 'clienteCalificacion', 'clienteHistorial', 'vendedorCalificacion', 'vendedorHistorial', 'ciudadesAll', 'editProvincias', 'editMunicipios', 'editTipoEntrega', 'articulosEdit', 'articulosAgrupados', 'articulosTodos', 'searchProductoEdit'));
    }
}
