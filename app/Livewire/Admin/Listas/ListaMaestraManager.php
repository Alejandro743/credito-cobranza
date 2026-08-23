<?php

namespace App\Livewire\Admin\Listas;

use App\Models\Categoria;
use App\Models\CommercialCycle;
use App\Models\ConfiguracionPuntos;
use App\Models\ListaAcceso;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\MaestroArticulo;
use App\Models\Product;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListaMaestraManager extends Component
{
    use WithPagination, HasModuleColor, WithFileUploads;

    public string $mode      = 'list';  // list | items | acceso
    public ?int   $viewingId = null;

    // ── Filtros list ──────────────────────────────────────────────────────────
    public string $search        = '';
    public string $filterCycleId = '';
    public string $filterStatus  = '';
    public bool   $mostrarInactivas = true;
    public string $sortBy        = 'cycle_id';
    public string $sortDir       = 'asc';

    // Filtros por columna en thead
    public string $colFilterId           = '';
    public string $colFilterCodigo       = '';
    public string $colFilterNombre       = '';
    public string $colFilterCiclo        = '';
    public string $colFilterCuotas       = '';
    public string $colFilterEstado       = '';
    public string $colFilterCuotaInicial = '';
    public string $colFilterIncremento   = '';
    public string $colFilterDias         = '';

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

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm          = false;
    public string $newCode              = '';
    public string $newName              = '';
    public ?int   $newCycleId           = null;
    public bool   $newActive            = true;
    public string $newTipoIncremento    = '';
    public string $newValorIncremento   = '0';
    public string $newCantidadCuotas    = '';
    public string $newDiasEntreCuotas   = '30';
    public string $newTipoCuotaInicial  = 'ninguna';
    public string $newValorCuotaInicial = '0';

    // ── Ver detalle (modal) ───────────────────────────────────────────────────
    public bool  $showViewModal   = false;
    public array $viewMaestraData = [];


    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId             = null;
    public string $editCode              = '';
    public string $editName              = '';
    public ?int   $editCycleId           = null;
    public bool   $editActive            = true;
    public string $editTipoIncremento    = '';
    public string $editValorIncremento   = '0';
    public string $editCantidadCuotas    = '';
    public string $editDiasEntreCuotas   = '30';
    public string $editTipoCuotaInicial  = 'ninguna';
    public string $editValorCuotaInicial = '0';

    public ?int $selectedMaestraId = null;

    public function selectMaestra(int $id): void
    {
        $this->selectedMaestraId = $this->selectedMaestraId === $id ? null : $id;
    }

    // ── Items mode ────────────────────────────────────────────────────────────
    public string $filterCodigo   = '';
    public string $filterProducto = '';
    public string $filterEnLista  = '';

    // Agregar maestro artículo inline
    public bool   $showAddMaestroForm    = false;
    public ?int   $addMaestroId          = null;

    // Import CSV
    public bool   $showImportModal       = false;
    public        $importFile            = null;
    public bool   $showImportResultModal = false;
    public array  $importResult          = [];

    // Modal: crear nuevo maestro artículo desde autocomplete
    public bool   $showCreateMaestroModal   = false;
    public string $newMaestroCodigo         = '';
    public string $newMaestroNombreModal    = '';
    public ?int   $newMaestroCategoriaId    = null;
    public ?int   $newMaestroUnidadId       = null;
    public string $addMaestroStock       = '';
    public string $addMaestroPrecio      = '0';
    public string $addMaestroPuntos      = '0';
    public string $maestroCategoria      = '';
    public string $maestroUnidad         = '';
    public ?int   $selectedMaestroItemId = null;

    public ?int   $selectedProductId    = null;
    public string $itemSortBy           = 'name';
    public string $itemSortDir          = 'asc';
    public string $itemColFilterCodigo    = '';
    public string $itemColFilterNombre    = '';
    public string $itemColFilterTipoInc   = '';
    public string $itemColFilterEnLista   = '';
    public string $itemColFilterPrecioBase  = '';
    public string $itemColFilterFactorInc  = '';
    public string $itemColFilterPrecioFinal = '';
    public string $itemColFilterPuntos     = '';
    public string $itemColFilterStock      = '';

    public function selectProduct(int $id): void
    {
        $this->selectedProductId = $this->selectedProductId === $id ? null : $id;
    }

    public function toggleItemSort(string $col): void
    {
        if ($this->itemSortBy === $col) {
            $this->itemSortDir = $this->itemSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->itemSortBy  = $col;
            $this->itemSortDir = 'asc';
        }
    }

    // ── Modales items ─────────────────────────────────────────────────────────
    public bool   $showAgregarModal = false;
    public string $modalPrecio      = '0';
    public string $modalPuntos      = '0';
    public string $modalStock       = '0';
    public bool   $showEditModal    = false;

    public function openAgregarModal(): void
    {
        $this->modalPrecio      = '0';
        $this->modalPuntos      = '0';
        $this->modalStock       = '0';
        $this->showAgregarModal = true;
        $this->resetValidation();
    }

    public function openAgregarParaProducto(int $productId): void
    {
        $this->selectedProductId = $productId;
        $this->openAgregarModal();
    }

    public function closeAgregarModal(): void
    {
        $this->showAgregarModal = false;
        $this->resetValidation();
    }

    public function saveAgregarModal(): void
    {
        $this->validate([
            'modalPrecio' => 'required|numeric|min:0',
            'modalPuntos' => 'required|integer|min:0',
            'modalStock'  => 'required|numeric|min:0',
        ], [], [
            'modalPrecio' => 'precio',
            'modalPuntos' => 'puntos',
            'modalStock'  => 'stock inicial',
        ]);

        $lista     = ListaMaestra::findOrFail($this->viewingId);
        $productId = $this->selectedProductId;
        $cicloId   = $lista->cycle_id;
        $newStock  = (float) $this->modalStock;

        try {
            DB::transaction(function () use ($productId, $cicloId, $newStock) {
                $disp = $this->disponibleParaAsignar($productId, $cicloId, null);

                if ($disp !== null && $newStock > $disp + 0.001) {
                    throw new \RuntimeException('Stock insuficiente. Disponible: ' . number_format(max(0, $disp), 2));
                }

                [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista((float) $this->modalPrecio);

                ListaMaestraItem::create([
                    'lista_maestra_id'  => $this->viewingId,
                    'product_id'        => $productId,
                    'precio_base'       => $this->modalPrecio,
                    'puntos'            => (int) $this->modalPuntos,
                    'stock_inicial'     => $newStock,
                    'stock_consumido'   => 0,
                    'stock_actual'      => $newStock,
                    'descuento'         => 0,
                    'active'            => true,
                    'tipo_incremento'   => $tipoInc,
                    'factor_incremento' => $factorInc,
                    'monto_incremento'  => $montoInc,
                ]);
            });
        } catch (\RuntimeException $e) {
            $this->addError('modalStock', $e->getMessage());
            return;
        }

        $this->showAgregarModal = false;
        session()->flash('success', 'Producto agregado a la lista.');
    }

    public ?int   $showAddItemForm  = null;  // legacy, unused
    public ?int   $editItemId              = null;
    public string $editItemCode            = '';
    public string $editItemPrecio          = '0';
    public string $editItemPuntos          = '0';
    public string $editItemStock           = '0';
    public bool   $editItemActive          = true;
    public string $editItemTipoIncremento  = '';
    public string $editItemFactorIncremento = '0';

    // ── Acceso mode ───────────────────────────────────────────────────────────
    // Clientes
    public string  $sqlCliente        = '';
    public ?array  $sqlClienteResult  = null;
    public string  $sqlClienteError   = '';
    public string  $searchCliente     = '';
    public ?array  $manualClienteResult = null;

    // Vendedores
    public string  $sqlVendedor       = '';
    public ?array  $sqlVendedorResult = null;
    public string  $sqlVendedorError  = '';
    public string  $searchVendedor    = '';
    public ?array  $manualVendedorResult = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedMostrarInactivas(): void { $this->resetPage(); }

    public function refrescarGrilla(): void {}

    // ── List: inline add ─────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm          = true;
        $this->newCode              = '';
        $this->newName              = '';
        $this->newCycleId           = null;
        $this->newActive            = true;
        $this->newTipoIncremento    = '';
        $this->newValorIncremento   = '0';
        $this->newCantidadCuotas    = '';
        $this->newDiasEntreCuotas   = '30';
        $this->newTipoCuotaInicial  = 'ninguna';
        $this->newValorCuotaInicial = '0';
        $this->editingId            = null;
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newCode'    => ['required', 'string', 'max:30'],
            'newName'    => 'required|string|min:2',
            'newCycleId' => ['required', 'integer', 'exists:commercial_cycles,id'],
        ], [], [
            'newCode'    => 'código',
            'newName'    => 'nombre',
            'newCycleId' => 'ciclo',
        ]);

        ListaMaestra::create([
            'code'                => strtoupper(trim($this->newCode)),
            'cycle_id'            => $this->newCycleId,
            'name'                => $this->newName,
            'active'              => $this->newActive,
            'estado'              => $this->newActive ? 'activa' : 'cerrada',
            'tipo_incremento'     => $this->newTipoIncremento ?: null,
            'valor_incremento'    => (float) $this->newValorIncremento,
            'cantidad_cuotas'     => $this->newCantidadCuotas !== '' ? (int) $this->newCantidadCuotas : null,
            'dias_entre_cuotas'   => $this->newDiasEntreCuotas !== '' ? (int) $this->newDiasEntreCuotas : null,
            'usa_cuota_inicial'   => $this->newTipoCuotaInicial !== 'ninguna',
            'tipo_cuota_inicial'  => $this->newTipoCuotaInicial !== 'ninguna' ? $this->newTipoCuotaInicial : null,
            'valor_cuota_inicial' => $this->newTipoCuotaInicial !== 'ninguna' ? (float) $this->newValorCuotaInicial : null,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Lista de precios creada.');
    }

    // ── List: inline edit ────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $m = ListaMaestra::findOrFail($id);
        $this->editingId             = $id;
        $this->editCode              = $m->code ?? '';
        $this->editName              = $m->name;
        $this->editCycleId           = $m->cycle_id;
        $this->editActive            = (bool) $m->active;
        $this->editTipoIncremento    = (string) ($m->tipo_incremento ?? '');
        $this->editValorIncremento   = (string) ($m->valor_incremento ?? '0');
        $this->editCantidadCuotas    = (string) ($m->cantidad_cuotas ?? '');
        $this->editDiasEntreCuotas   = (string) ($m->dias_entre_cuotas ?? '30');
        $this->editTipoCuotaInicial  = $m->tipo_cuota_inicial ?: 'ninguna';
        $this->editValorCuotaInicial = (string) ($m->valor_cuota_inicial ?? '0');
        $this->showAddForm           = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editCode'    => ['required', 'string', 'max:30'],
            'editName'    => 'required|string|min:2',
            'editCycleId' => ['required', 'integer', 'exists:commercial_cycles,id'],
        ], [], [
            'editCode'    => 'código',
            'editName'    => 'nombre',
            'editCycleId' => 'ciclo',
        ]);

        $tipoInc   = $this->editTipoIncremento ?: null;
        $valorInc  = (float) $this->editValorIncremento;

        ListaMaestra::findOrFail($this->editingId)->update([
            'code'                => strtoupper(trim($this->editCode)),
            'cycle_id'            => $this->editCycleId,
            'name'                => $this->editName,
            'active'              => $this->editActive,
            'estado'              => $this->editActive ? 'activa' : 'cerrada',
            'tipo_incremento'     => $tipoInc,
            'valor_incremento'    => $valorInc,
            'cantidad_cuotas'     => $this->editCantidadCuotas !== '' ? (int) $this->editCantidadCuotas : null,
            'dias_entre_cuotas'   => $this->editDiasEntreCuotas !== '' ? (int) $this->editDiasEntreCuotas : null,
            'usa_cuota_inicial'   => $this->editTipoCuotaInicial !== 'ninguna',
            'tipo_cuota_inicial'  => $this->editTipoCuotaInicial !== 'ninguna' ? $this->editTipoCuotaInicial : null,
            'valor_cuota_inicial' => $this->editTipoCuotaInicial !== 'ninguna' ? (float) $this->editValorCuotaInicial : null,
        ]);

        // Propagar incremento a todos los ítems de la lista
        ListaMaestraItem::where('lista_maestra_id', $this->editingId)->get()
            ->each(function ($item) use ($tipoInc, $valorInc) {
                $monto = ($tipoInc && $valorInc > 0)
                    ? ($tipoInc === 'porcentaje'
                        ? round((float) $item->precio_base * $valorInc / 100, 2)
                        : $valorInc)
                    : 0.0;
                $item->update([
                    'tipo_incremento'   => $tipoInc,
                    'factor_incremento' => $valorInc,
                    'monto_incremento'  => $monto,
                ]);
            });

        $this->editingId = null;
        session()->flash('success', 'Lista actualizada.');
    }

    public function toggleActive(int $id): void
    {
        $m = ListaMaestra::findOrFail($id);
        $newActive = !$m->active;
        $m->update([
            'active' => $newActive,
            'estado' => $newActive ? 'activa' : 'cerrada',
        ]);
    }

    // ── Ver detalle ───────────────────────────────────────────────────────────

    public function openView(int $id): void
    {
        $m = ListaMaestra::with('cycle')->findOrFail($id);

        $inc = '';
        if ($m->tipo_incremento && $m->valor_incremento) {
            $inc = $m->tipo_incremento === 'porcentaje'
                ? $m->valor_incremento . '%'
                : 'Bs ' . number_format($m->valor_incremento, 2);
        }

        $ci = '';
        if ($m->usa_cuota_inicial && $m->tipo_cuota_inicial !== 'ninguna' && $m->valor_cuota_inicial) {
            $ci = $m->tipo_cuota_inicial === 'porcentaje'
                ? $m->valor_cuota_inicial . '%'
                : 'Bs ' . number_format($m->valor_cuota_inicial, 2);
        }

        $this->viewMaestraData = [
            'name'         => $m->name,
            'code'         => $m->code ?? '—',
            'ciclo'        => $m->cycle?->code ?? '—',
            'active'       => $m->active,
            'cuotas'       => $m->cantidad_cuotas ? $m->cantidad_cuotas . 'c' : '—',
            'dias'         => $m->dias_entre_cuotas ?? '—',
            'incremento'   => $inc ?: '—',
            'cuota_ini'    => $ci ?: '—',
        ];
        $this->showViewModal = true;
    }

    public function closeView(): void
    {
        $this->showViewModal   = false;
        $this->viewMaestraData = [];
    }

    // ── Items mode ────────────────────────────────────────────────────────────

    public function viewItems(int $id): void
    {
        $this->viewingId            = $id;
        $this->mode                 = 'items';
        $this->editItemId           = null;
        $this->selectedProductId    = null;
        $this->showAgregarModal     = false;
        $this->showEditModal        = false;
        $this->filterCodigo         = '';
        $this->filterProducto       = '';
        $this->filterEnLista        = '';
        $this->itemSortBy           = 'name';
        $this->itemSortDir          = 'asc';
        $this->itemColFilterCodigo      = '';
        $this->itemColFilterNombre      = '';
        $this->itemColFilterTipoInc     = '';
        $this->itemColFilterEnLista     = '';
        $this->itemColFilterPrecioBase  = '';
        $this->itemColFilterFactorInc   = '';
        $this->itemColFilterPrecioFinal = '';
        $this->itemColFilterPuntos      = '';
        $this->itemColFilterStock       = '';
        $this->resetValidation();
    }

    public function showAddMaestro(): void
    {
        $this->showAddMaestroForm = true;
        $this->addMaestroId       = null;
        $this->addMaestroStock    = '';
        $this->addMaestroPrecio   = '0';
        $this->addMaestroPuntos   = '0';
        $this->maestroCategoria   = '';
        $this->maestroUnidad      = '';
        $this->showAddItemForm    = null;
        $this->resetValidation();
    }

    public function cancelAddMaestro(): void
    {
        $this->showAddMaestroForm = false;
        $this->addMaestroId       = null;
        $this->addMaestroStock    = '';
        $this->addMaestroPrecio   = '0';
        $this->addMaestroPuntos   = '0';
        $this->maestroCategoria   = '';
        $this->maestroUnidad      = '';
        $this->resetValidation();
    }

    public function selectMaestro(int|string|null $id): void
    {
        if ($id === null) return;
        $this->addMaestroId     = (int) $id;
        $m = MaestroArticulo::with(['categoria', 'unidad'])->find($id);
        $this->maestroCategoria = $m?->categoria?->descripcion ?? '—';
        $this->maestroUnidad    = $m?->unidad?->name ?? '—';
    }

    public function openCreateMaestroModal(string $query = ''): void
    {
        $this->showCreateMaestroModal = true;
        $this->newMaestroCodigo       = strtoupper(trim($query));
        $this->newMaestroNombreModal  = trim($query);
        $this->newMaestroCategoriaId  = null;
        $this->newMaestroUnidadId     = null;
        $this->resetValidation(['newMaestroCodigo', 'newMaestroNombreModal']);
    }

    public function saveCreateMaestroModal(): void
    {
        $this->validate([
            'newMaestroCodigo'      => ['required', 'string', 'max:50', Rule::unique('maestro_articulos', 'codigo')],
            'newMaestroNombreModal' => 'required|string|max:255',
        ], [], [
            'newMaestroCodigo'      => 'código',
            'newMaestroNombreModal' => 'nombre',
        ]);

        $nuevo = MaestroArticulo::create([
            'codigo'       => strtoupper(trim($this->newMaestroCodigo)),
            'nombre'       => trim($this->newMaestroNombreModal),
            'categoria_id' => $this->newMaestroCategoriaId,
            'unidad_id'    => $this->newMaestroUnidadId,
            'active'       => true,
        ]);

        $this->showCreateMaestroModal = false;
        $this->selectMaestro($nuevo->id);

        $this->dispatch('maestro-created',
            id:     $nuevo->id,
            codigo: $nuevo->codigo,
            nombre: $nuevo->nombre,
            label:  $nuevo->codigo . ' — ' . $nuevo->nombre,
        );
    }

    public function saveAddMaestro(): void
    {
        $this->validate([
            'addMaestroId'     => 'required|exists:maestro_articulos,id',
            'addMaestroStock'  => 'required|numeric|min:0',
            'addMaestroPrecio' => 'required|numeric|min:0',
        ], [], [
            'addMaestroId'     => 'artículo',
            'addMaestroStock'  => 'stock',
            'addMaestroPrecio' => 'precio base',
        ]);

        $existe = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
            ->where('maestro_articulo_id', $this->addMaestroId)
            ->exists();

        if ($existe) {
            $this->addError('addMaestroId', 'Este artículo ya está en esta lista.');
            return;
        }

        $stock = (float) $this->addMaestroStock;

        $lista      = ListaMaestra::find($this->viewingId);
        $precioBase = (float) $this->addMaestroPrecio;
        [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista($precioBase);
        $puntosCalc = $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $lista?->cycle_id);

        ListaMaestraItem::create([
            'lista_maestra_id'    => $this->viewingId,
            'maestro_articulo_id' => $this->addMaestroId,
            'stock_inicial'       => $stock,
            'stock_actual'        => $stock,
            'stock_consumido'     => 0,
            'stock_comprometido'  => 0,
            'precio_base'         => $precioBase,
            'puntos'              => $puntosCalc,
            'descuento'           => 0,
            'active'              => true,
            'tipo_incremento'     => $tipoInc,
            'factor_incremento'   => $factorInc,
            'monto_incremento'    => $montoInc,
        ]);

        $this->showAddMaestroForm = false;
        $this->addMaestroId       = null;
        $this->addMaestroStock    = '';
        $this->addMaestroPrecio   = '0';
        $this->addMaestroPuntos   = '0';
        $this->maestroCategoria   = '';
        $this->maestroUnidad      = '';
        session()->flash('success', 'Artículo agregado a la lista.');
    }

    public function showAddItem(): void
    {
        $this->showAddItemForm = true;
        $this->newItemCode     = '';
        $this->newItemNombre   = '';
        $this->newItemUnidadId = null;
        $this->newItemCatId    = null;
        $this->newItemPrecio   = '0';
        $this->newItemPuntos   = '0';
        $this->newItemStock    = '0';
        $this->newItemActive   = true;
        $this->editItemId      = null;
        $this->resetValidation();
    }

    public function cancelAddItem(): void
    {
        $this->showAddItemForm = false;
        $this->resetValidation();
    }

    public function saveNewItem(): void
    {
        $this->validate([
            'newItemCode'   => ['required', 'string', 'max:30',
                                Rule::unique('products', 'code')->whereNull('deleted_at')],
            'newItemNombre' => ['required', 'string', 'min:2',
                                Rule::unique('products', 'name')->whereNull('deleted_at')],
            'newItemUnidadId' => 'nullable|integer|exists:unidades,id',
            'newItemCatId'    => 'nullable|integer|exists:categorias,id',
            'newItemPrecio'   => 'required|numeric|min:0',
            'newItemStock'    => 'required|numeric|min:0',
        ], [], [
            'newItemCode'     => 'código',
            'newItemNombre'   => 'nombre',
            'newItemUnidadId' => 'unidad',
            'newItemCatId'    => 'categoría',
            'newItemPrecio'   => 'precio',
            'newItemStock'    => 'stock inicial',
        ]);

        $product = Product::create([
            'code'         => strtoupper(trim($this->newItemCode)),
            'name'         => $this->newItemNombre,
            'unidad_id'    => $this->newItemUnidadId,
            'categoria_id' => $this->newItemCatId,
            'active'       => $this->newItemActive,
        ]);

        $lista      = ListaMaestra::find($this->viewingId);
        $precioBase = (float) $this->newItemPrecio;
        [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista($precioBase);
        $puntosCalc = $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $lista?->cycle_id);

        ListaMaestraItem::create([
            'lista_maestra_id' => $this->viewingId,
            'product_id'       => $product->id,
            'precio_base'      => $this->newItemPrecio,
            'puntos'           => $puntosCalc,
            'stock_inicial'    => $this->newItemStock,
            'stock_consumido'  => 0,
            'stock_actual'     => $this->newItemStock,
            'descuento'        => 0,
            'active'           => $this->newItemActive,
            'tipo_incremento'  => $tipoInc,
            'factor_incremento'=> $factorInc,
            'monto_incremento' => $montoInc,
        ]);

        $this->showAddItemForm = false;
        session()->flash('success', "Producto \"{$product->name}\" creado y agregado.");
    }


    public function startEditItem(int $id): void
    {
        $item = ListaMaestraItem::with('product')->findOrFail($id);
        $this->editItemId               = $id;
        $this->editItemCode             = $item->product?->code ?? '';
        $this->editItemPrecio           = (string) $item->precio_base;
        $this->editItemPuntos           = (string) $item->puntos;
        $this->editItemStock            = (string) $item->stock_inicial;
        $this->editItemActive           = (bool) $item->active;
        $this->editItemTipoIncremento   = (string) ($item->tipo_incremento ?? '');
        $this->editItemFactorIncremento = (string) ($item->factor_incremento ?? '0');
        $this->showEditModal            = false;
        $this->resetValidation();
    }

    public function cancelEditItem(): void
    {
        $this->editItemId    = null;
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function saveEditItem(): void
    {
        $item = ListaMaestraItem::with('product')->findOrFail($this->editItemId);

        $rules = [
            'editItemPrecio' => 'required|numeric|min:0',
            'editItemStock'  => 'required|numeric|min:0',
        ];
        if ($item->product_id) {
            $rules['editItemCode'] = ['required', 'string', 'max:30',
                                      Rule::unique('products', 'code')
                                          ->ignore($item->product_id)
                                          ->whereNull('deleted_at')];
        }

        $this->validate($rules, [], [
            'editItemCode'   => 'código',
            'editItemPrecio' => 'precio',
            'editItemStock'  => 'stock inicial',
        ]);

        $lista     = ListaMaestra::findOrFail($this->viewingId);
        $cicloId   = $lista->cycle_id;
        $newStock  = (float) $this->editItemStock;
        $itemId    = $item->id;
        $productId = $item->product_id;

        try {
            DB::transaction(function () use ($item, $productId, $cicloId, $newStock, $itemId, $lista) {
                if ($productId !== null) {
                    $disp = $this->disponibleParaAsignar($productId, $cicloId, $itemId);
                    if ($disp !== null && $newStock > $disp + 0.001) {
                        throw new \RuntimeException('Stock insuficiente. Disponible: ' . number_format(max(0, $disp), 2));
                    }
                }

                if ($item->product_id && $item->product) {
                    $item->product->update(['code' => strtoupper(trim($this->editItemCode))]);
                }

                $precioBase = (float) $this->editItemPrecio;
                $nuevoActual = max(0, $newStock - (float) $item->stock_consumido);
                [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista($precioBase);
                $puntosCalc = $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $cicloId);

                $item->update([
                    'precio_base'       => $precioBase,
                    'puntos'            => $puntosCalc,
                    'stock_inicial'     => $newStock,
                    'stock_actual'      => $nuevoActual,
                    'active'            => (bool) $this->editItemActive,
                    'tipo_incremento'   => $tipoInc,
                    'factor_incremento' => $factorInc,
                    'monto_incremento'  => $montoInc,
                ]);
            });
        } catch (\RuntimeException $e) {
            $this->addError('editItemStock', $e->getMessage());
            return;
        }

        $this->editItemId    = null;
        $this->showEditModal = false;
        session()->flash('success', 'Ítem actualizado.');
    }

    // ── Stock helper (concurrencia) ───────────────────────────────────────────

    private function disponibleParaAsignar(int $productId, int $cicloId, ?int $excludeItemId): ?float
    {
        $cicloRow = DB::table('ciclo_productos')
            ->where('commercial_cycle_id', $cicloId)
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->first();

        if (!$cicloRow) {
            return null;
        }

        $listaIds = ListaMaestra::where('cycle_id', $cicloId)->pluck('id');

        $query = ListaMaestraItem::whereIn('lista_maestra_id', $listaIds)
            ->where('product_id', $productId)
            ->lockForUpdate();

        if ($excludeItemId !== null) {
            $query->where('id', '!=', $excludeItemId);
        }

        return max(0, (float) $cicloRow->stock_total - (float) $query->sum('stock_inicial'));
    }

    public function toggleItemActive(int $itemId): void
    {
        $item = ListaMaestraItem::findOrFail($itemId);
        $item->update(['active' => !$item->active]);
    }

    public bool $showRemoveItemModal   = false;
    public ?int $confirmRemoveItemId   = null;

    public function askRemoveItem(int $itemId): void
    {
        $this->confirmRemoveItemId = $itemId;
        $this->showRemoveItemModal = true;
    }

    public function cancelRemoveItem(): void
    {
        $this->showRemoveItemModal = false;
        $this->confirmRemoveItemId = null;
    }

    public function removeItem(int $itemId): void
    {
        $hasOrders = DB::table('pedido_items')
            ->where('lista_maestra_item_id', $itemId)
            ->exists();

        if ($hasOrders) {
            session()->flash('error', 'No se puede quitar: el ítem tiene pedidos asociados.');
            $this->cancelRemoveItem();
            return;
        }

        ListaMaestraItem::destroy($itemId);
        $this->selectedProductId     = null;
        $this->selectedMaestroItemId = null;
        $this->cancelRemoveItem();
        session()->flash('success', 'Ítem quitado de la lista.');
    }

    public function refreshFromCatalog(): void
    {
        // Agrega al items map todos los productos activos que aún no estén en la lista
        $enLista = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)->pluck('product_id');
        $nuevos  = Product::where('active', true)->whereNotIn('id', $enLista)->get();

        foreach ($nuevos as $p) {
            ListaMaestraItem::create([
                'lista_maestra_id' => $this->viewingId,
                'product_id'       => $p->id,
                'precio_base'      => 0,
                'puntos'           => 0,
                'stock_inicial'    => 0,
                'stock_consumido'  => 0,
                'stock_actual'     => 0,
                'descuento'        => 0,
                'active'           => false,
            ]);
        }

        session()->flash('success', $nuevos->count() > 0
            ? "{$nuevos->count()} producto(s) agregados desde el catálogo."
            : 'El catálogo ya está sincronizado.');
    }

    // ── Acceso mode ───────────────────────────────────────────────────────────

    public function viewAcceso(int $id): void
    {
        $this->viewingId             = $id;
        $this->mode                  = 'acceso';
        $this->sqlCliente            = '';
        $this->sqlClienteResult      = null;
        $this->sqlClienteError       = '';
        $this->searchCliente         = '';
        $this->manualClienteResult   = null;
        $this->sqlVendedor           = '';
        $this->sqlVendedorResult     = null;
        $this->sqlVendedorError      = '';
        $this->searchVendedor        = '';
        $this->manualVendedorResult  = null;
    }

    public function runSqlCliente(): void
    {
        $this->sqlClienteError  = '';
        $this->sqlClienteResult = null;
        try {
            $where   = trim($this->sqlCliente) ?: '1=1';
            $results = DB::select(
                "SELECT id, name, email FROM users WHERE tipo = 'cliente' AND ({$where}) LIMIT 100"
            );
            $this->sqlClienteResult = array_map(fn($r) => (array) $r, $results);
        } catch (\Throwable $e) {
            $this->sqlClienteError = 'Error en la consulta: ' . $e->getMessage();
        }
    }

    public function addAllFromSqlCliente(): void
    {
        if (empty($this->sqlClienteResult)) return;
        foreach ($this->sqlClienteResult as $row) {
            ListaAcceso::firstOrCreate(
                ['lista_maestra_id' => $this->viewingId, 'user_id' => $row['id'], 'tipo' => 'cliente'],
                ['origen' => 'sql']
            );
        }
        $this->sqlClienteResult = null;
        session()->flash('success', 'Clientes agregados al acceso.');
    }

    public function addClienteManual(int $userId): void
    {
        ListaAcceso::firstOrCreate(
            ['lista_maestra_id' => $this->viewingId, 'user_id' => $userId, 'tipo' => 'cliente'],
            ['origen' => 'manual']
        );
        $this->manualClienteResult = null;
        $this->searchCliente       = '';
        session()->flash('success', 'Cliente agregado.');
    }

    public function updatingSearchCliente(): void
    {
        $this->searchManualCliente();
    }

    public function searchManualCliente(): void
    {
        $q = trim($this->searchCliente);
        if (!$q) { $this->manualClienteResult = null; return; }

        $existingIds = ListaAcceso::where('lista_maestra_id', $this->viewingId)
            ->where('tipo', 'cliente')->pluck('user_id');

        $this->manualClienteResult = User::where('tipo', 'cliente')
            ->whereNotIn('id', $existingIds)
            ->where(fn($q2) => $q2->where('id', 'like', "%{$q}%")
                                  ->orWhere('name', 'like', "%{$q}%")
                                  )
            ->limit(10)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function runSqlVendedor(): void
    {
        $this->sqlVendedorError  = '';
        $this->sqlVendedorResult = null;
        try {
            $where   = trim($this->sqlVendedor) ?: '1=1';
            $results = DB::select(
                "SELECT id, name, email FROM users WHERE tipo = 'vendedor' AND ({$where}) LIMIT 100"
            );
            $this->sqlVendedorResult = array_map(fn($r) => (array) $r, $results);
        } catch (\Throwable $e) {
            $this->sqlVendedorError = 'Error en la consulta: ' . $e->getMessage();
        }
    }

    public function addAllFromSqlVendedor(): void
    {
        if (empty($this->sqlVendedorResult)) return;
        foreach ($this->sqlVendedorResult as $row) {
            ListaAcceso::firstOrCreate(
                ['lista_maestra_id' => $this->viewingId, 'user_id' => $row['id'], 'tipo' => 'vendedor'],
                ['origen' => 'sql']
            );
        }
        $this->sqlVendedorResult = null;
        session()->flash('success', 'Vendedores agregados al acceso.');
    }

    public function addVendedorManual(int $userId): void
    {
        ListaAcceso::firstOrCreate(
            ['lista_maestra_id' => $this->viewingId, 'user_id' => $userId, 'tipo' => 'vendedor'],
            ['origen' => 'manual']
        );
        $this->manualVendedorResult = null;
        $this->searchVendedor       = '';
        session()->flash('success', 'Vendedor agregado.');
    }

    public function updatingSearchVendedor(): void
    {
        $this->searchManualVendedor();
    }

    public function searchManualVendedor(): void
    {
        $q = trim($this->searchVendedor);
        if (!$q) { $this->manualVendedorResult = null; return; }

        $existingIds = ListaAcceso::where('lista_maestra_id', $this->viewingId)
            ->where('tipo', 'vendedor')->pluck('user_id');

        $this->manualVendedorResult = User::where('tipo', 'vendedor')
            ->whereNotIn('id', $existingIds)
            ->where(fn($q2) => $q2->where('id', 'like', "%{$q}%")
                                  ->orWhere('name', 'like', "%{$q}%")
                                  )
            ->limit(10)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function removeAcceso(int $accesoId): void
    {
        ListaAcceso::destroy($accesoId);
    }

    // ── Import CSV ────────────────────────────────────────────────────────────

    public function downloadImportTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Código', 'Precio Base', 'Stock Inicial', 'Estado'], ';');
            fputcsv($out, ['PROD-001', '1500.00', '10', '1'], ';');
            fputcsv($out, ['PROD-002', '2300.50', '5', '1'], ';');
            fclose($out);
        }, 'formato-importacion.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importCsv(): void
    {
        $this->validate(['importFile' => 'required|file|mimes:csv,txt|max:4096']);

        $handle = fopen($this->importFile->getRealPath(), 'r');

        // Detect delimiter (semicolon or comma)
        $firstLine = fgets($handle);
        rewind($handle);
        $delim = substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';

        // Skip header row
        fgetcsv($handle, 0, $delim);

        $actualizados   = 0;
        $creados        = 0;
        $noEncontrados  = [];

        while (($row = fgetcsv($handle, 0, $delim)) !== false) {
            if (count($row) < 2 || empty(trim($row[0] ?? ''))) continue;

            $codigo      = strtoupper(trim($row[0]));
            $precioBase  = (float) str_replace(',', '.', trim($row[1] ?? '0'));
            $stockInicial= (float) str_replace(',', '.', trim($row[2] ?? '0'));
            $estadoRaw   = strtolower(trim($row[3] ?? '1'));
            $active      = in_array($estadoRaw, ['1', 'si', 'sí', 'habilitado', 'true', 'yes', 'activo']);

            [$tipoInc, $factorInc, $montoInc] = $this->calcIncrementoFromLista($precioBase);

            // Buscar producto
            $product = Product::where('code', $codigo)->first();
            if ($product) {
                $item = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                    ->where('product_id', $product->id)->first();
                $data = [
                    'precio_base'       => $precioBase,
                    'stock_inicial'     => $stockInicial,
                    'stock_actual'      => $stockInicial,
                    'active'            => $active,
                    'tipo_incremento'   => $tipoInc,
                    'factor_incremento' => $factorInc,
                    'monto_incremento'  => $montoInc,
                ];
                if ($item) { $item->update($data); $actualizados++; }
                else {
                    ListaMaestraItem::create(array_merge($data, [
                        'lista_maestra_id' => $this->viewingId,
                        'product_id'       => $product->id,
                        'stock_consumido'  => 0,
                        'stock_comprometido' => 0,
                        'puntos'           => 0,
                        'descuento'        => 0,
                    ]));
                    $creados++;
                }
                continue;
            }

            // Buscar maestro artículo
            $maestro = MaestroArticulo::where('codigo', $codigo)->first();
            if ($maestro) {
                $item = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                    ->where('maestro_articulo_id', $maestro->id)->first();
                $data = [
                    'precio_base'       => $precioBase,
                    'stock_inicial'     => $stockInicial,
                    'stock_actual'      => $stockInicial,
                    'active'            => $active,
                    'tipo_incremento'   => $tipoInc,
                    'factor_incremento' => $factorInc,
                    'monto_incremento'  => $montoInc,
                ];
                if ($item) { $item->update($data); $actualizados++; }
                else {
                    ListaMaestraItem::create(array_merge($data, [
                        'lista_maestra_id'    => $this->viewingId,
                        'maestro_articulo_id' => $maestro->id,
                        'stock_consumido'     => 0,
                        'stock_comprometido'  => 0,
                        'puntos'              => 0,
                        'descuento'           => 0,
                    ]));
                    $creados++;
                }
                continue;
            }

            $noEncontrados[] = $codigo;
        }

        fclose($handle);

        $this->importFile            = null;
        $this->showImportModal       = false;
        $this->importResult          = [
            'actualizados'  => $actualizados,
            'creados'       => $creados,
            'noEncontrados' => $noEncontrados,
        ];
        $this->showImportResultModal = true;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function calcIncrementoFromLista(float $precioBase): array
    {
        $lista    = ListaMaestra::find($this->viewingId);
        $tipoInc  = $lista?->tipo_incremento ?: null;
        $factorInc = (float) ($lista?->valor_incremento ?? 0);
        $montoInc = 0.0;
        if ($tipoInc && $factorInc > 0) {
            $montoInc = $tipoInc === 'porcentaje'
                ? round($precioBase * $factorInc / 100, 2)
                : $factorInc;
        }
        return [$tipoInc, $factorInc, $montoInc];
    }

    private function calcPuntosFromPrecioFinal(float $precioFinal, ?int $cycleId): int
    {
        if (!$cycleId) {
            return 0;
        }
        $valorPunto = (float) (ConfiguracionPuntos::where('cycle_id', $cycleId)->value('valor_punto') ?? 0);
        if ($valorPunto <= 0) {
            return 0;
        }
        return (int) ceil($precioFinal / $valorPunto);
    }

    public function newItemPuntosPreview(): int
    {
        $lista = ListaMaestra::find($this->viewingId);
        if (!$lista) {
            return 0;
        }
        $precioBase = (float) $this->newItemPrecio;
        [, , $montoInc] = $this->calcIncrementoFromLista($precioBase);
        return $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $lista->cycle_id);
    }

    public function editItemPuntosPreview(): int
    {
        $lista = ListaMaestra::find($this->viewingId);
        if (!$lista) {
            return 0;
        }
        $precioBase = (float) $this->editItemPrecio;
        [, , $montoInc] = $this->calcIncrementoFromLista($precioBase);
        return $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $lista->cycle_id);
    }

    public function addMaestroPuntosPreview(): int
    {
        $lista = ListaMaestra::find($this->viewingId);
        if (!$lista) {
            return 0;
        }
        $precioBase = (float) $this->addMaestroPrecio;
        [, , $montoInc] = $this->calcIncrementoFromLista($precioBase);
        return $this->calcPuntosFromPrecioFinal(max(0, $precioBase + $montoInc), $lista->cycle_id);
    }

    // ── Navigation ────────────────────────────────────────────────────────────

    public function backToList(): void
    {
        $restoreId               = in_array($this->mode, ['items', 'acceso']) ? $this->viewingId : null;
        $this->mode              = 'list';
        $this->viewingId         = null;
        $this->editingId         = null;
        $this->selectedMaestraId = $restoreId;
        $this->selectedProductId = null;
        $this->editItemId        = null;
        $this->showAgregarModal  = false;
        $this->showEditModal     = false;
        $this->sqlClienteResult  = null;
        $this->sqlVendedorResult = null;
        $this->manualClienteResult  = null;
        $this->manualVendedorResult = null;
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $viewingMaestra = ListaMaestra::with('cycle')->find($this->viewingId);
        $cicloId        = $viewingMaestra?->cycle_id;
        $viewingId      = $this->viewingId;

        $cicloProductoIds = $cicloId
            ? DB::table('ciclo_productos')->where('commercial_cycle_id', $cicloId)->pluck('product_id')
            : collect();

        $listaIds    = $cicloId ? ListaMaestra::where('cycle_id', $cicloId)->pluck('id') : collect();
        $stockMap    = $cicloId
            ? DB::table('ciclo_productos')->where('commercial_cycle_id', $cicloId)
                ->pluck('stock_total', 'product_id')->map(fn($v) => (float) $v)
            : collect();
        $asignadoMap = $cicloId
            ? ListaMaestraItem::whereIn('lista_maestra_id', $listaIds)
                ->selectRaw('product_id, SUM(stock_inicial) as total')
                ->groupBy('product_id')->pluck('total', 'product_id')->map(fn($v) => (float) $v)
            : collect();

        // Productos en el ciclo
        $itemsMap       = ListaMaestraItem::where('lista_maestra_id', $viewingId)
            ->whereNotNull('product_id')->get()->keyBy('product_id');
        $habilitadosIds = $itemsMap->filter(fn($i) => $i->active)->keys();

        $products = Product::with(['categoria', 'unidad'])
            ->leftJoin('lista_maestra_items as lmi', function ($join) use ($viewingId) {
                $join->on('lmi.product_id', '=', 'products.id')
                     ->where('lmi.lista_maestra_id', $viewingId);
            })
            ->select('products.*')
            ->whereIn('products.id', $cicloProductoIds)
            ->when($this->itemColFilterCodigo,          fn($q) => $q->where('products.code', 'like', "%{$this->itemColFilterCodigo}%"))
            ->when($this->itemColFilterNombre,          fn($q) => $q->where('products.name', 'like', "%{$this->itemColFilterNombre}%"))
            ->when($this->itemColFilterTipoInc,         fn($q) => $q->where('lmi.tipo_incremento', $this->itemColFilterTipoInc))
            ->when($this->itemColFilterEnLista === '1', fn($q) => $q->whereIn('products.id', $habilitadosIds))
            ->when($this->itemColFilterEnLista === '0', fn($q) => $q->whereNotIn('products.id', $habilitadosIds))
            ->when($this->itemColFilterPrecioBase !== '',    fn($q) => $q->whereRaw("CAST(lmi.precio_base AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioBase}%"]))
            ->when($this->itemColFilterFactorInc !== '',     fn($q) => $q->whereRaw("CAST(lmi.factor_incremento AS CHAR) LIKE ?", ["%{$this->itemColFilterFactorInc}%"]))
            ->when($this->itemColFilterPrecioFinal !== '',   fn($q) => $q->whereRaw("CAST(COALESCE(lmi.precio_base,0) + COALESCE(lmi.monto_incremento,0) AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioFinal}%"]))
            ->when($this->itemColFilterPuntos !== '',        fn($q) => $q->whereRaw("CAST(lmi.puntos AS CHAR) LIKE ?", ["%{$this->itemColFilterPuntos}%"]))
            ->when($this->itemColFilterStock !== '',         fn($q) => $q->whereRaw("CAST(lmi.stock_inicial AS CHAR) LIKE ?", ["%{$this->itemColFilterStock}%"]))
            ->orderBy('products.name')
            ->get();

        // Artículos maestro en esta lista
        $maestroItems = ListaMaestraItem::with(['maestroArticulo'])
            ->where('lista_maestra_id', $viewingId)
            ->whereNotNull('maestro_articulo_id')
            ->when($this->itemColFilterCodigo,            fn($q) => $q->whereHas('maestroArticulo', fn($sq) => $sq->where('codigo', 'like', "%{$this->itemColFilterCodigo}%")))
            ->when($this->itemColFilterNombre,            fn($q) => $q->whereHas('maestroArticulo', fn($sq) => $sq->where('nombre', 'like', "%{$this->itemColFilterNombre}%")))
            ->when($this->itemColFilterPrecioBase !== '',    fn($q) => $q->whereRaw("CAST(precio_base AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioBase}%"]))
            ->when($this->itemColFilterFactorInc !== '',     fn($q) => $q->whereRaw("CAST(factor_incremento AS CHAR) LIKE ?", ["%{$this->itemColFilterFactorInc}%"]))
            ->when($this->itemColFilterPrecioFinal !== '',   fn($q) => $q->whereRaw("CAST(COALESCE(precio_base,0) + COALESCE(monto_incremento,0) AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioFinal}%"]))
            ->when($this->itemColFilterPuntos !== '',        fn($q) => $q->whereRaw("CAST(puntos AS CHAR) LIKE ?", ["%{$this->itemColFilterPuntos}%"]))
            ->when($this->itemColFilterStock !== '',         fn($q) => $q->whereRaw("CAST(stock_inicial AS CHAR) LIKE ?", ["%{$this->itemColFilterStock}%"]))
            ->orderBy('id')
            ->get();

        $filename = 'lista-' . str($viewingMaestra?->code ?? 'export')->slug() . '-' . now()->format('Ymd') . '.csv';

        $tieneCuota     = $viewingMaestra?->usa_cuota_inicial ?? false;
        $tipoCuota      = $viewingMaestra?->tipo_cuota_inicial;
        $valorCuota     = $viewingMaestra?->valor_cuota_inicial;
        $cantidadCuotas = $viewingMaestra?->cantidad_cuotas ?? 0;

        $calcCuota = function (float $precioFinal) use ($tieneCuota, $tipoCuota, $valorCuota, $cantidadCuotas) {
            $montoCuota = $tieneCuota
                ? ($tipoCuota === 'porcentaje' ? round($precioFinal * $valorCuota / 100, 2) : (float) $valorCuota)
                : 0.0;
            $montoPorCuota = $cantidadCuotas > 0 ? round(($precioFinal - $montoCuota) / $cantidadCuotas, 2) : 0.0;
            return [
                $tieneCuota && $tipoCuota === 'porcentaje' ? number_format($valorCuota, 2, '.', '') : '',
                $tieneCuota ? number_format($montoCuota, 2, '.', '') : '',
                $cantidadCuotas > 0 ? $cantidadCuotas : '',
                $cantidadCuotas > 0 ? number_format($montoPorCuota, 2, '.', '') : '',
            ];
        };

        return response()->streamDownload(function () use ($products, $maestroItems, $itemsMap, $stockMap, $asignadoMap, $calcCuota) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['Código', 'Artículo', 'Precio Base', 'Tipo Inc.', 'Incremento', 'Precio Final', 'Cuota Inicial %', 'Cuota Inicial (Bs)', 'Cant. Cuotas', 'Monto x Cuota', 'Puntos', 'Stock Inicial', 'Stock Cons.', 'Stock Disp.', 'Estado'], ';');

            foreach ($products as $p) {
                $item    = $itemsMap->get($p->id);
                $inLista = $item !== null;
                $cuota   = $inLista ? $calcCuota((float) $item->precio_final) : ['', '', '', ''];
                fputcsv($out, [
                    strtoupper($p->code),
                    strtoupper($p->name),
                    $inLista ? number_format($item->precio_base, 2, '.', '') : '',
                    $inLista ? ($item->tipo_incremento ?? '') : '',
                    $inLista && $item->factor_incremento > 0 ? number_format($item->factor_incremento, 2, '.', '') : '',
                    $inLista ? number_format($item->precio_final, 2, '.', '') : '',
                    $cuota[0],
                    $cuota[1],
                    $cuota[2],
                    $cuota[3],
                    $inLista ? $item->puntos : '',
                    $inLista ? number_format($item->stock_inicial, 2, '.', '') : '',
                    $inLista ? number_format($item->stock_consumido, 2, '.', '') : '',
                    $inLista ? number_format($item->stock_actual, 2, '.', '') : '',
                    $inLista ? ($item->active ? 'Habilitado' : 'Deshabilitado') : '',
                ], ';');
            }

            foreach ($maestroItems as $mi) {
                $ma    = $mi->maestroArticulo;
                $cuota = $calcCuota((float) $mi->precio_final);
                fputcsv($out, [
                    strtoupper($ma?->codigo ?? ''),
                    strtoupper($ma?->nombre ?? ''),
                    number_format($mi->precio_base, 2, '.', ''),
                    $mi->tipo_incremento ?? '',
                    $mi->factor_incremento > 0 ? number_format($mi->factor_incremento, 2, '.', '') : '',
                    number_format($mi->precio_final, 2, '.', ''),
                    $cuota[0],
                    $cuota[1],
                    $cuota[2],
                    $cuota[3],
                    $mi->puntos,
                    number_format($mi->stock_inicial, 2, '.', ''),
                    number_format($mi->stock_consumido, 2, '.', ''),
                    number_format($mi->stock_actual, 2, '.', ''),
                    $mi->active ? 'Habilitado' : 'Deshabilitado',
                ], ';');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function render()
    {
        $cycles = CommercialCycle::where('status', '!=', 'cerrado')->orderByDesc('start_date')->get();

        // List mode
        $maestras = ListaMaestra::with('cycle')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->filterCycleId, fn($q) => $q->where('cycle_id', $this->filterCycleId))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->when(!$this->mostrarInactivas, fn($q) => $q->where('active', true))
            ->when($this->colFilterId !== '',        fn($q) => $q->where('id', 'like', "%{$this->colFilterId}%"))
            ->when($this->colFilterCodigo,           fn($q) => $q->where('code', 'like', "%{$this->colFilterCodigo}%"))
            ->when($this->colFilterNombre,           fn($q) => $q->where('name', 'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterCiclo,            fn($q) => $q->whereHas('cycle', fn($sq) => $sq->where('code', 'like', "%{$this->colFilterCiclo}%")))
            ->when($this->colFilterCuotas !== '',    fn($q) => $q->where('cantidad_cuotas', $this->colFilterCuotas))
            ->when($this->colFilterEstado !== '',    fn($q) => $q->where('active', (bool) $this->colFilterEstado))
            ->when($this->colFilterCuotaInicial !== '', fn($q) => $q->where('usa_cuota_inicial', (bool) $this->colFilterCuotaInicial))
            ->when($this->colFilterIncremento,          fn($q) => $q->where('tipo_incremento', $this->colFilterIncremento))
            ->when($this->colFilterDias !== '',         fn($q) => $q->where('dias_entre_cuotas', $this->colFilterDias))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(15);

        // Items mode
        $viewingMaestra      = null;
        $products            = collect();
        $itemsMap            = collect();
        $maestroItems        = collect();
        $maestrosDisponibles = collect();
        $categorias          = collect();
        $unidades            = collect();
        $stockMap            = collect();
        $asignadoMap         = collect();

        if ($this->viewingId && in_array($this->mode, ['items', 'acceso'])) {
            $viewingMaestra = ListaMaestra::with('cycle')->find($this->viewingId);
        }

        if ($this->viewingId && $this->mode === 'items') {
            $itemsMap = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                ->get()->keyBy('product_id');

            $inListaIds        = $itemsMap->keys();
            $habilitadosIds    = $itemsMap->filter(fn($i) => $i->active)->keys();
            $deshabilitadosIds = $itemsMap->filter(fn($i) => !$i->active)->keys();

            $cicloId = $viewingMaestra?->cycle_id;

            $cicloProductoIds = $cicloId
                ? DB::table('ciclo_productos')
                    ->where('commercial_cycle_id', $cicloId)
                    ->pluck('product_id')
                : collect();

            $itemSortMap = [
                'code'               => 'products.code',
                'name'               => 'products.name',
                'precio_base'        => 'lmi.precio_base',
                'tipo_incremento'    => 'lmi.tipo_incremento',
                'factor_incremento'  => 'lmi.factor_incremento',
                'puntos'             => 'lmi.puntos',
                'stock_inicial'      => 'lmi.stock_inicial',
                'stock_comprometido' => 'lmi.stock_comprometido',
                'stock_consumido'    => 'lmi.stock_consumido',
                'stock_actual'       => 'lmi.stock_actual',
                'active'             => 'lmi.active',
            ];
            $itemSortCol = $itemSortMap[$this->itemSortBy] ?? 'products.name';
            $sortDir     = $this->itemSortDir === 'desc' ? 'desc' : 'asc';
            $viewingId   = $this->viewingId;

            // Compute $listaIds here so stock_max sort subquery can use it
            $listaIds = $cicloId ? ListaMaestra::where('cycle_id', $cicloId)->pluck('id') : collect();

            $products = Product::with(['categoria', 'unidad'])
                ->leftJoin('lista_maestra_items as lmi', function ($join) use ($viewingId) {
                    $join->on('lmi.product_id', '=', 'products.id')
                         ->where('lmi.lista_maestra_id', $viewingId);
                })
                ->when($this->itemSortBy === 'stock_max' && $cicloId, function ($q) use ($cicloId, $listaIds) {
                    $q->leftJoin('ciclo_productos as cp_sort', function ($j) use ($cicloId) {
                        $j->on('cp_sort.product_id', '=', 'products.id')
                          ->where('cp_sort.commercial_cycle_id', $cicloId);
                    });
                    $asigSub = ListaMaestraItem::whereIn('lista_maestra_id', $listaIds)
                        ->selectRaw('product_id, SUM(stock_inicial) as asignado')
                        ->groupBy('product_id');
                    $q->leftJoinSub($asigSub, 'asig_sort', 'asig_sort.product_id', '=', 'products.id');
                })
                ->select('products.*')
                ->whereIn('products.id', $cicloProductoIds)
                ->when($this->itemColFilterCodigo,              fn($q) => $q->where('products.code', 'like', "%{$this->itemColFilterCodigo}%"))
                ->when($this->itemColFilterNombre,              fn($q) => $q->where('products.name', 'like', "%{$this->itemColFilterNombre}%"))
                ->when($this->itemColFilterTipoInc,             fn($q) => $q->where('lmi.tipo_incremento', $this->itemColFilterTipoInc))
                ->when($this->itemColFilterEnLista === '1',     fn($q) => $q->whereIn('products.id', $habilitadosIds))
                ->when($this->itemColFilterEnLista === '0',     fn($q) => $q->whereNotIn('products.id', $habilitadosIds))
                ->when($this->itemColFilterPrecioBase !== '',    fn($q) => $q->whereRaw("CAST(lmi.precio_base AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioBase}%"]))
                ->when($this->itemColFilterFactorInc !== '',   fn($q) => $q->whereRaw("CAST(lmi.factor_incremento AS CHAR) LIKE ?", ["%{$this->itemColFilterFactorInc}%"]))
                ->when($this->itemColFilterPrecioFinal !== '', fn($q) => $q->whereRaw("CAST(COALESCE(lmi.precio_base,0) + COALESCE(lmi.monto_incremento,0) AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioFinal}%"]))
                ->when($this->itemColFilterPuntos !== '',      fn($q) => $q->whereRaw("CAST(lmi.puntos AS CHAR) LIKE ?", ["%{$this->itemColFilterPuntos}%"]))
                ->when($this->itemColFilterStock !== '',       fn($q) => $q->whereRaw("CAST(lmi.stock_inicial AS CHAR) LIKE ?", ["%{$this->itemColFilterStock}%"]))
                ->when($this->itemSortBy === 'precio_final',
                    fn($q) => $q->orderByRaw("(COALESCE(lmi.precio_base,0) + COALESCE(lmi.monto_incremento,0)) $sortDir"),
                    fn($q) => $this->itemSortBy === 'stock_max' && $cicloId
                        ? $q->orderByRaw("(COALESCE(cp_sort.stock_total,0) - COALESCE(asig_sort.asignado,0)) $sortDir")
                        : $q->orderBy($itemSortCol, $sortDir)
                )
                ->get();

            $categorias = Categoria::where('active', true)->orderBy('name')->get();
            $unidades   = Unidad::where('active', true)->orderBy('name')->get();

            $maestroItems = ListaMaestraItem::with(['maestroArticulo.categoria', 'maestroArticulo.unidad'])
                ->where('lista_maestra_id', $this->viewingId)
                ->whereNotNull('maestro_articulo_id')
                ->when($this->itemColFilterCodigo,            fn($q) => $q->whereHas('maestroArticulo', fn($sq) => $sq->where('codigo', 'like', "%{$this->itemColFilterCodigo}%")))
                ->when($this->itemColFilterNombre,            fn($q) => $q->whereHas('maestroArticulo', fn($sq) => $sq->where('nombre', 'like', "%{$this->itemColFilterNombre}%")))
                ->when($this->itemColFilterPrecioBase !== '',    fn($q) => $q->whereRaw("CAST(precio_base AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioBase}%"]))
                ->when($this->itemColFilterFactorInc !== '',   fn($q) => $q->whereRaw("CAST(factor_incremento AS CHAR) LIKE ?", ["%{$this->itemColFilterFactorInc}%"]))
                ->when($this->itemColFilterPrecioFinal !== '', fn($q) => $q->whereRaw("CAST(COALESCE(precio_base,0) + COALESCE(monto_incremento,0) AS CHAR) LIKE ?", ["%{$this->itemColFilterPrecioFinal}%"]))
                ->when($this->itemColFilterPuntos !== '',      fn($q) => $q->whereRaw("CAST(puntos AS CHAR) LIKE ?", ["%{$this->itemColFilterPuntos}%"]))
                ->when($this->itemColFilterStock !== '',       fn($q) => $q->whereRaw("CAST(stock_inicial AS CHAR) LIKE ?", ["%{$this->itemColFilterStock}%"]))
                ->get();

            $usadosEnEstaLista   = ListaMaestraItem::where('lista_maestra_id', $this->viewingId)
                ->whereNotNull('maestro_articulo_id')
                ->pluck('maestro_articulo_id');
            $maestrosDisponibles = MaestroArticulo::where('active', true)
                ->whereNotIn('id', $usadosEnEstaLista)
                ->orderBy('nombre')
                ->get();

            if ($cicloId) {
                $stockMap = DB::table('ciclo_productos')
                    ->where('commercial_cycle_id', $cicloId)
                    ->pluck('stock_total', 'product_id')
                    ->map(fn($v) => (float) $v);

                $asignadoMap = ListaMaestraItem::whereIn('lista_maestra_id', $listaIds)
                    ->selectRaw('product_id, SUM(stock_inicial) as total')
                    ->groupBy('product_id')
                    ->pluck('total', 'product_id')
                    ->map(fn($v) => (float) $v);
            }
        }

        // Acceso mode
        $accesosClientes  = collect();
        $accesosVendedores = collect();

        if ($this->viewingId && $this->mode === 'acceso') {
            $accesosClientes  = ListaAcceso::with('user')
                ->where('lista_maestra_id', $this->viewingId)
                ->where('tipo', 'cliente')
                ->orderBy('created_at')
                ->get();

            $accesosVendedores = ListaAcceso::with('user')
                ->where('lista_maestra_id', $this->viewingId)
                ->where('tipo', 'vendedor')
                ->orderBy('created_at')
                ->get();
        }

        $itemSortBy  = $this->itemSortBy;
        $itemSortDir = $this->itemSortDir;

        return view('livewire.admin.listas.lista-maestra-manager', compact(
            'maestras', 'cycles', 'viewingMaestra', 'products', 'itemsMap',
            'maestroItems', 'maestrosDisponibles', 'categorias', 'unidades',
            'accesosClientes', 'accesosVendedores',
            'stockMap', 'asignadoMap', 'itemSortBy', 'itemSortDir'
        ));
    }
}
