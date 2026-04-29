<div>

@php
    $theadClass = match($moduleColor ?? '') {
        'lavanda'   => 'bg-lavanda-100 text-lavanda-700',
        'mint'      => 'bg-mint-100 text-mint-700',
        'melocoton' => 'bg-melocoton-100 text-melocoton-700',
        'celeste'   => 'bg-celeste-100 text-celeste-700',
        default     => 'bg-gray-50 text-gray-600',
    };
@endphp

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

{{-- ══════════════════════════════════════════════════════════ ITEMS MODE ══ --}}
@if ($mode === 'items' && $viewingLista)
<div>
    {{-- Cabecera --}}
    <div class="flex items-start gap-3 mb-5">
        <button wire:click="backToList" class="mt-1 p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-lg font-bold text-gray-800 truncate">{{ $viewingLista->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $viewingLista->estado === 'activa' ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($viewingLista->estado) }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">
                Ciclo: <span class="font-medium text-gray-700">{{ $viewingLista->cycle->name ?? '—' }}</span>
                @if ($viewingLista->cycle?->code)
                    &nbsp;·&nbsp;<span class="font-mono text-gray-400">{{ $viewingLista->cycle->code }}</span>
                @endif
            </p>
        </div>
        {{-- Botón Actualizar --}}
        <button wire:click="$refresh" title="Actualizar tabla desde catálogo"
                class="shrink-0 flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-500 hover:text-lavanda-600 border border-gray-200 hover:border-lavanda-300 hover:bg-lavanda-50 rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Actualizar
        </button>
    </div>

    {{-- Barra de filtros + botón nuevo producto --}}
    <div class="flex flex-col sm:flex-row gap-2 mb-4">
        <input wire:model.live.debounce.300ms="filterCodigo" type="text" placeholder="Código..."
               class="border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 w-full sm:w-32">
        <input wire:model.live.debounce.300ms="filterProducto" type="text" placeholder="Buscar producto..."
               class="border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 flex-1">
        <select wire:model.live="filterEnLista"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos</option>
            <option value="1">En esta lista</option>
            <option value="0">Disponibles</option>
        </select>
        <button wire:click="showAddItem"
                class="flex items-center justify-center gap-1.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors whitespace-nowrap">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Producto
        </button>
    </div>

    {{-- Formulario nuevo producto (crea en catálogo + agrega a lista) --}}
    @if ($showAddItemForm)
    <div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4 mb-4">
        <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Nuevo Producto</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Código *</label>
                <input wire:model="newCode" type="text" placeholder="Ej: PROD-001"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                @error('newCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nombre *</label>
                <input wire:model="newNombre" type="text" placeholder="Nombre del producto"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                @error('newNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Unidad</label>
                <select wire:model="newUnidadId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="">— Sin unidad —</option>
                    @foreach ($unidades as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}{{ $u->abreviatura ? ' ('.$u->abreviatura.')' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Categoría</label>
                <select wire:model="newCategoriaId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="">— Sin categoría —</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Precio *</label>
                <input wire:model="newPrecio" type="number" step="0.01" min="0" placeholder="0.00"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('newPrecio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Puntos *</label>
                <input wire:model="newPuntos" type="number" min="0" placeholder="0"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('newPuntos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Stock Inicial *</label>
                <input wire:model.live="newStockInicial" type="number" step="0.01" min="0" placeholder="0"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('newStockInicial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Stock Actual</label>
                <div class="w-full border border-lavanda-100 bg-white rounded-xl px-3 py-2 text-sm text-lavanda-700 font-semibold">
                    {{ number_format(max(0, (float)$newStockInicial), 2) }}
                </div>
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" wire:model="newActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
                    Activo
                </label>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3 mt-4">
            <button wire:click="cancelAddItem" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors font-medium">Cancelar</button>
            <button wire:click="saveNewItem" class="px-5 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">Guardar</button>
        </div>
    </div>
    @endif

    {{-- Tabla: todos los productos del catálogo --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Producto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Unidad</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Precio</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Puntos</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Stock Ini.</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Consumido</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Stock Act.</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($products as $product)
                    @php $item = $itemsMap->get($product->id); $inLista = !is_null($item); @endphp

                    @if ($inLista && $editItemId === $item->id)
                    {{-- ── FILA EN EDICIÓN ── --}}
                    <tr wire:key="edit-{{ $product->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                        <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ $product->code }}</td>
                        <td class="px-4 py-2 font-medium text-gray-700 text-xs">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-400 hidden sm:table-cell">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <input wire:model="editPrecio" type="number" step="0.01" min="0"
                                   class="w-24 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                            @error('editPrecio') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 hidden md:table-cell">
                            <input wire:model="editPuntos" type="number" min="0"
                                   class="w-20 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                        </td>
                        <td class="px-4 py-2 hidden lg:table-cell">
                            <input wire:model.live="editStockInicial" type="number" step="0.01" min="0"
                                   class="w-24 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                        </td>
                        <td class="px-4 py-2 text-right text-xs text-melocoton-600 hidden lg:table-cell">
                            {{ number_format($item->stock_consumido, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            @php $preview = max(0, (float)$editStockInicial - (float)$item->stock_consumido); @endphp
                            <span class="font-semibold text-sm {{ $preview > 0 ? 'text-mint-700' : 'text-melocoton-600' }}">
                                {{ number_format($preview, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $item->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="saveEditItem" class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button wire:click="cancelEditItem" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @elseif (!$inLista && $quickAddProductId === $product->id)
                    {{-- ── FILA QUICK-ADD (producto disponible, agregando) ── --}}
                    <tr wire:key="qadd-{{ $product->id }}" class="bg-celeste-50 border-l-2 border-celeste-400">
                        <td class="px-4 py-2 font-mono text-xs text-gray-500">{{ $product->code }}</td>
                        <td class="px-4 py-2 font-medium text-gray-700 text-xs">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-400 hidden sm:table-cell">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <input wire:model="quickAddPrecio" type="number" step="0.01" min="0" placeholder="Precio"
                                   class="w-24 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                            @error('quickAddPrecio') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 hidden md:table-cell">
                            <input wire:model="quickAddPuntos" type="number" min="0" placeholder="Pts"
                                   class="w-20 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                        </td>
                        <td class="px-4 py-2 hidden lg:table-cell">
                            <input wire:model.live="quickAddStockInicial" type="number" step="0.01" min="0" placeholder="Stock"
                                   class="w-24 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                        </td>
                        <td class="px-4 py-2 text-right text-xs text-gray-300 hidden lg:table-cell">0</td>
                        <td class="px-4 py-2 text-right">
                            <span class="text-sm font-semibold text-celeste-700">
                                {{ number_format(max(0, (float)$quickAddStockInicial), 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-celeste-100 text-celeste-700">Nuevo</span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="saveQuickAdd" class="p-1.5 rounded-lg bg-celeste-100 text-celeste-700 hover:bg-celeste-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button wire:click="cancelQuickAdd" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @elseif ($inLista)
                    {{-- ── FILA EN LECTURA (ya en lista) ── --}}
                    <tr wire:key="inlist-{{ $product->id }}" class="hover:bg-gray-50 transition-colors {{ !$item->active ? 'opacity-60' : '' }}">
                        <td class="px-4 py-3 font-mono text-xs text-gray-500 font-medium">{{ $product->code }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 hidden sm:table-cell">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">S/ {{ number_format($item->precio_base, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 hidden md:table-cell">{{ $item->puntos }}</td>
                        <td class="px-4 py-3 text-right text-gray-500 hidden lg:table-cell">{{ number_format($item->stock_inicial, 2) }}</td>
                        <td class="px-4 py-3 text-right text-melocoton-600 hidden lg:table-cell">{{ number_format($item->stock_consumido, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-semibold {{ $item->stock_actual > 0 ? 'text-mint-700' : 'text-melocoton-600' }}">
                                {{ number_format($item->stock_actual, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleItemActive({{ $item->id }})"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors
                                    {{ $item->active ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $item->active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="startEditItem({{ $item->id }})" title="Editar"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="removeItem({{ $item->id }})"
                                        wire:confirm="¿Quitar este producto de la lista?"
                                        title="Quitar de la lista"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @else
                    {{-- ── FILA DISPONIBLE (no en lista aún) ── --}}
                    <tr wire:key="avail-{{ $product->id }}" class="hover:bg-gray-50 transition-colors opacity-70">
                        <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $product->code }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400 hidden sm:table-cell">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300 hidden md:table-cell">—</td>
                        <td class="px-4 py-3 text-right text-gray-300 hidden lg:table-cell">—</td>
                        <td class="px-4 py-3 text-right text-gray-300 hidden lg:table-cell">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">No en lista</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="startQuickAdd({{ $product->id }})" title="Agregar a esta lista"
                                    class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-celeste-600 bg-celeste-50 hover:bg-celeste-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Agregar
                            </button>
                        </td>
                    </tr>
                    @endif

                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-14 text-center text-gray-400 text-sm">
                            No hay productos en el catálogo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════ FORM MODE ══ --}}
@elseif ($mode === 'form')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Lista de Precios' : 'Nueva Lista de Precios' }}</h2>
    </div>
    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Ciclo Comercial *</label>
            <select wire:model="cycle_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Seleccionar ciclo —</option>
                @foreach ($cycles as $c)
                    <option value="{{ $c->id }}">{{ $c->code }}</option>
                @endforeach
            </select>
            @error('cycle_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
            <input wire:model="name" type="text" placeholder="Lista de Precios Ene-2026"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Estado</label>
            <select wire:model="estado" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="activa">Activa</option>
                <option value="cerrada">Cerrada</option>
            </select>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" wire:click="backToList" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Cancelar</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white transition-colors">
                {{ $editing ? 'Guardar cambios' : 'Crear lista' }}
            </button>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════════ LIST MODE ══ --}}
@else
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar lista de precios..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <button wire:click="create" class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Lista de Precios
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ciclo</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Creada</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($listas as $lista)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $lista->name }}</td>
                    <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $lista->cycle?->code ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $lista->estado === 'activa' ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($lista->estado) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center text-gray-500 hidden md:table-cell">{{ $lista->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="viewItems({{ $lista->id }})" title="Ver productos"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-celeste-600 hover:bg-celeste-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </button>
                            <button wire:click="edit({{ $lista->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-14 text-center text-gray-400 text-sm">No hay listas de precios registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($listas->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $listas->links() }}</div>
    @endif
</div>
@endif

</div>
