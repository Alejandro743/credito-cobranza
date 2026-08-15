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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden sm:block">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Código</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Producto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Unidad</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Precio Base</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Precio Final</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Puntos</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Stock Máximo</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Stock Inicial</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Stock Comprometido</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Stock Consumido</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Stock Disponible</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($products as $product)
                    @php $item = $itemsMap->get($product->id); $inLista = !is_null($item); @endphp

                    @if ($inLista && $editItemId === $item->id)
                    {{-- ── FILA EN EDICIÓN ── --}}
                    @php
                        $editStTotal  = $stockMap->get($product->id);
                        $editStAsig   = $asignadoMap->get($product->id, 0);
                        $editStDisp   = $editStTotal !== null
                            ? max(0, $editStTotal - $editStAsig + (float)$item->stock_inicial)
                            : null;
                    @endphp
                    <tr wire:key="edit-{{ $product->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                        <td class="px-4 py-2">
                            <input wire:model="editCode" type="text"
                                   class="w-24 border border-lavanda-300 rounded-lg px-2 py-1.5 text-xs font-mono uppercase text-center focus:outline-none focus:border-lavanda-500 bg-white">
                            @error('editCode') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 font-medium text-gray-700 text-xs whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-400 whitespace-nowrap">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <input wire:model="editPrecio" type="number" step="0.01" min="0"
                                   class="w-24 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                            @error('editPrecio') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 text-right text-sm font-semibold text-gray-500">
                            {{ number_format($item->precio_final, 2) }}
                        </td>
                        <td class="px-4 py-2">
                            <input wire:model="editPuntos" type="number" min="0"
                                   class="w-20 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                        </td>
                        <td class="px-4 py-2">
                            <div class="w-24 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right bg-gray-50 font-semibold {{ $editStDisp !== null && $editStDisp > 0 ? 'text-mint-700' : ($editStDisp === 0 ? 'text-red-500' : 'text-gray-300') }}">
                                {{ $editStDisp !== null ? number_format($editStDisp, 0) : '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <input wire:model.live="editStockInicial" type="number" step="0.01" min="0"
                                   @if($editStDisp !== null) max="{{ $editStDisp }}" @endif
                                   class="w-24 border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-lavanda-500 bg-white">
                            @error('editStockInicial') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 text-right text-sm text-orange-500">
                            {{ number_format($item->stock_comprometido, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right text-sm text-melocoton-600">
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
                    @php
                        $qaStTotal = $stockMap->get($product->id);
                        $qaStAsig  = $asignadoMap->get($product->id, 0);
                        $qaStDisp  = $qaStTotal !== null ? max(0, $qaStTotal - $qaStAsig) : null;
                    @endphp
                    <tr wire:key="qadd-{{ $product->id }}" class="bg-celeste-50 border-l-2 border-celeste-400">
                        <td class="px-4 py-2 font-mono text-xs text-gray-500 whitespace-nowrap">{{ $product->code }}</td>
                        <td class="px-4 py-2 font-medium text-gray-700 text-xs whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-xs text-gray-400 whitespace-nowrap">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <input wire:model="quickAddPrecio" type="number" step="0.01" min="0" placeholder="Precio"
                                   class="w-24 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                            @error('quickAddPrecio') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 text-right text-sm text-gray-300">—</td>
                        <td class="px-4 py-2">
                            <input wire:model="quickAddPuntos" type="number" min="0" placeholder="Pts"
                                   class="w-20 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                        </td>
                        <td class="px-4 py-2">
                            <div class="w-24 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right bg-gray-50 font-semibold {{ $qaStDisp !== null && $qaStDisp > 0 ? 'text-mint-700' : ($qaStDisp === 0 ? 'text-red-500' : 'text-gray-300') }}">
                                {{ $qaStDisp !== null ? number_format($qaStDisp, 0) : '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <input wire:model.live="quickAddStockInicial" type="number" step="0.01" min="0" placeholder="Stock"
                                   @if($qaStDisp !== null) max="{{ $qaStDisp }}" @endif
                                   class="w-24 border border-celeste-300 rounded-lg px-2 py-1.5 text-sm text-right focus:outline-none focus:border-celeste-500 bg-white">
                            @error('quickAddStockInicial') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        </td>
                        <td class="px-4 py-2 text-right text-sm text-gray-300">0</td>
                        <td class="px-4 py-2 text-right text-sm text-gray-300">0</td>
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
                    @php
                        $rdStTotal = $stockMap->get($product->id);
                        $rdStAsig  = $asignadoMap->get($product->id, 0);
                        $rdStDisp  = $rdStTotal !== null
                            ? max(0, $rdStTotal - $rdStAsig + (float)$item->stock_inicial)
                            : null;
                    @endphp
                    <tr wire:key="inlist-{{ $product->id }}" class="hover:bg-gray-50 transition-colors {{ !$item->active ? 'opacity-60' : '' }}">
                        <td class="px-4 py-3 font-mono text-xs text-gray-500 font-medium whitespace-nowrap">{{ $product->code }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 whitespace-nowrap">{{ number_format($item->precio_base, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-700 whitespace-nowrap">{{ number_format($item->precio_final, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $item->puntos }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($rdStDisp !== null)
                                <span class="font-semibold {{ $rdStDisp > 0 ? 'text-mint-700' : 'text-red-500' }}">
                                    {{ number_format($rdStDisp, 0) }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-gray-500">{{ number_format($item->stock_inicial, 2) }}</td>
                        <td class="px-4 py-3 text-right text-orange-500 font-semibold">{{ number_format($item->stock_comprometido, 2) }}</td>
                        <td class="px-4 py-3 text-right text-melocoton-600">{{ number_format($item->stock_consumido, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-semibold {{ $item->stock_actual > 0 ? 'text-mint-700' : 'text-melocoton-600' }}">
                                {{ number_format($item->stock_actual, 2) }}
                            </span>
                        </td>
                        <td data-label="Estado" class="px-4 py-3 text-center">
                            <button wire:click="toggleItemActive({{ $item->id }})"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors
                                    {{ $item->active ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $item->active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td data-label="" class="px-4 py-3">
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
                    @php
                        $avStTotal = $stockMap->get($product->id);
                        $avStAsig  = $asignadoMap->get($product->id, 0);
                        $avStDisp  = $avStTotal !== null ? max(0, $avStTotal - $avStAsig) : null;
                    @endphp
                    <tr wire:key="avail-{{ $product->id }}" class="hover:bg-gray-50 transition-colors opacity-70">
                        <td class="px-4 py-3 font-mono text-xs text-gray-400 whitespace-nowrap">{{ $product->code }}</td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">{{ $product->unidad?->abreviatura ?? $product->unidad?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right">
                            @if($avStDisp !== null)
                                <span class="font-semibold {{ $avStDisp > 0 ? 'text-mint-700' : 'text-red-500' }}">
                                    {{ number_format($avStDisp, 0) }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td class="px-4 py-3 text-right text-gray-300">—</td>
                        <td data-label="Estado" class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-400">No en lista</span>
                        </td>
                        <td data-label="" class="px-4 py-3 text-right">
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
                        <td colspan="13" class="px-5 py-14 text-center text-gray-400 text-sm">
                            No hay productos en el catálogo.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MOBILE: Cards ítems ── --}}
    @php $iMi = 'height:36px; border:1px solid #C4B5FD; border-radius:8px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
    <div class="sm:hidden flex flex-col gap-3">
        @forelse ($products as $product)
        @php $item = $itemsMap->get($product->id); $inLista = !is_null($item); @endphp

        @if ($inLista && $editItemId === $item->id)
        {{-- CARD EDICIÓN --}}
        <div wire:key="card-edit-{{ $product->id }}"
             style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
            <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Editando</span>
                    <p style="font-size:13px; font-weight:600; color:#374151; margin:2px 0 0;">{{ $product->name }}</p>
                </div>
                <button wire:click="cancelEditItem" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
                        <input wire:model="editCode" type="text" style="{{ $iMi }} font-family:monospace; text-transform:uppercase;">
                        @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Precio *</label>
                        <input wire:model="editPrecio" type="number" step="0.01" min="0" style="{{ $iMi }} text-align:right;">
                        @error('editPrecio')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Puntos</label>
                        <input wire:model="editPuntos" type="number" min="0" style="{{ $iMi }} text-align:right;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Stock Inicial</label>
                        <input wire:model.live="editStockInicial" type="number" step="0.01" min="0" style="{{ $iMi }} text-align:right;">
                        @error('editStockInicial')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div style="background:#F8F7FF; border-radius:8px; padding:8px 12px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:12px; color:#6B7280;">Stock actual (preview)</span>
                    @php $preview = max(0, (float)$editStockInicial - (float)$item->stock_consumido); @endphp
                    <span style="font-size:14px; font-weight:700; color:{{ $preview > 0 ? '#059669' : '#E11D48' }};">{{ number_format($preview, 2) }}</span>
                </div>
                <div style="display:flex; gap:8px; padding-top:2px;">
                    <button wire:click="saveEditItem"
                            style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                        Guardar
                    </button>
                    <button wire:click="cancelEditItem"
                            style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        @elseif (!$inLista && $quickAddProductId === $product->id)
        {{-- CARD QUICK-ADD --}}
        <div wire:key="card-qadd-{{ $product->id }}"
             style="background:#fff; border-radius:14px; border:1px solid #BAE6FD; border-left:3px solid #0EA5E9; box-shadow:0 2px 8px rgba(14,165,233,.1); overflow:hidden;">
            <div style="background:#F0F9FF; border-bottom:1px solid #BAE6FD; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <span style="font-size:12px; font-weight:700; color:#0369A1; text-transform:uppercase; letter-spacing:.5px;">Agregando a lista</span>
                    <p style="font-size:13px; font-weight:600; color:#374151; margin:2px 0 0;">{{ $product->name }}</p>
                </div>
                <button wire:click="cancelQuickAdd" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#0369A1; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Precio *</label>
                        <input wire:model="quickAddPrecio" type="number" step="0.01" min="0"
                               style="height:36px; border:1px solid #BAE6FD; border-radius:8px; padding:0 8px; font-size:13px; background:#fff; outline:none; box-sizing:border-box; width:100%; text-align:right;">
                        @error('quickAddPrecio')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#0369A1; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Puntos</label>
                        <input wire:model="quickAddPuntos" type="number" min="0"
                               style="height:36px; border:1px solid #BAE6FD; border-radius:8px; padding:0 8px; font-size:13px; background:#fff; outline:none; box-sizing:border-box; width:100%; text-align:right;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#0369A1; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Stock</label>
                        <input wire:model="quickAddStockInicial" type="number" step="0.01" min="0"
                               style="height:36px; border:1px solid #BAE6FD; border-radius:8px; padding:0 8px; font-size:13px; background:#fff; outline:none; box-sizing:border-box; width:100%; text-align:right;">
                    </div>
                </div>
                <div style="display:flex; gap:8px;">
                    <button wire:click="saveQuickAdd"
                            style="flex:1; height:36px; background:#0EA5E9; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                        Agregar
                    </button>
                    <button wire:click="cancelQuickAdd"
                            style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        @elseif ($inLista)
        {{-- CARD NORMAL (en lista) --}}
        <div wire:key="card-{{ $product->id }}"
             style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden; {{ !$item->active ? 'opacity:.7;' : '' }}">
            <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
                <div style="flex:1; min-width:0;">
                    <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $product->name }}</p>
                    <p style="font-size:11px; font-family:monospace; color:#7B6FE8; font-weight:600; margin:2px 0 0;">{{ $product->code }}</p>
                </div>
                <button wire:click="toggleItemActive({{ $item->id }})"
                        style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; border:none; cursor:pointer; flex-shrink:0;
                               background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                               color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                    {{ $item->active ? 'Activo' : 'Inactivo' }}
                </button>
            </div>
            <div style="padding:8px 14px; display:flex; gap:14px; border-bottom:1px solid #F3F4F6; flex-wrap:wrap;">
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Precio Base</span>
                    <span style="font-size:13px; font-weight:700; color:#374151;">{{ number_format($item->precio_base, 2) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#7B6FE8; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Precio Final</span>
                    <span style="font-size:13px; font-weight:700; color:#7B6FE8;">{{ number_format($item->precio_final, 2) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Puntos</span>
                    <span style="font-size:13px; font-weight:700; color:#374151;">{{ $item->puntos }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Stock Inicial</span>
                    <span style="font-size:13px; font-weight:700; color:#374151;">{{ number_format($item->stock_inicial, 2) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#EA580C; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Comprometido</span>
                    <span style="font-size:13px; font-weight:700; color:#EA580C;">{{ number_format($item->stock_comprometido, 2) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#E11D48; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Stock Consumido</span>
                    <span style="font-size:13px; font-weight:700; color:#E11D48;">{{ number_format($item->stock_consumido, 2) }}</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center;">
                    <span style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">Stock Disponible</span>
                    <span style="font-size:13px; font-weight:700; color:{{ $item->stock_actual > 0 ? '#059669' : '#E11D48' }};">{{ number_format($item->stock_actual, 2) }}</span>
                </div>
            </div>
            <div style="padding:10px 14px; display:flex; gap:7px;">
                <button wire:click="startEditItem({{ $item->id }})"
                        style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </button>
                <button wire:click="removeItem({{ $item->id }})" wire:confirm="¿Quitar este producto de la lista?"
                        style="height:32px; padding:0 12px; border:1px solid #FEE2E2; border-radius:8px; background:#FEF2F2; color:#EF4444; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>

        @else
        {{-- CARD DISPONIBLE (no en lista) --}}
        <div wire:key="card-avail-{{ $product->id }}"
             style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden; opacity:.65;">
            <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
                <div style="flex:1; min-width:0;">
                    <p style="font-size:14px; font-weight:700; color:#6B7280; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $product->name }}</p>
                    <p style="font-size:11px; font-family:monospace; color:#9CA3AF; font-weight:600; margin:2px 0 0;">{{ $product->code }}</p>
                </div>
                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#F3F4F6; color:#9CA3AF; flex-shrink:0;">No en lista</span>
            </div>
            <div style="padding:10px 14px;">
                <button wire:click="startQuickAdd({{ $product->id }})"
                        style="width:100%; height:32px; border:1px solid #BAE6FD; border-radius:8px; background:#F0F9FF; color:#0369A1; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Agregar a lista
                </button>
            </div>
        </div>
        @endif

        @empty
        <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay productos en el catálogo.</p>
        @endforelse
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════ LIST MODE ══ --}}
@else
@php
$iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$lbl = 'font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;';
@endphp

{{-- Toolbar (se oculta cuando hay formulario abierto) --}}
@if($mode !== 'form')
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
@endif

{{-- Formulario inline --}}
@if($mode === 'form')
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ $editing ? 'Editar Lista de Precios' : 'Nueva Lista de Precios' }}
        </span>
        <button wire:click="cancelForm" style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; border:1px solid #EDE9FE; border-radius:8px; background:#fff; cursor:pointer; color:#9CA3AF;" type="button">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 18px;">
        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">

            {{-- Ciclo Comercial --}}
            <div style="display:flex; flex-direction:column;">
                <label style="{{ $lbl }}">Ciclo Comercial *</label>
                <select wire:model="cycle_id" style="{{ $iS }} width:190px;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($cycles as $c)
                        <option value="{{ $c->id }}">{{ $c->code }}</option>
                    @endforeach
                </select>
                @error('cycle_id') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>

            {{-- Nombre --}}
            <div style="display:flex; flex-direction:column;">
                <label style="{{ $lbl }}">Nombre *</label>
                <input wire:model="name" type="text" placeholder="Lista de Precios Ene-2026"
                       style="{{ $iS }} width:260px;">
                @error('name') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>

            {{-- Estado --}}
            <div style="display:flex; flex-direction:column;">
                <label style="{{ $lbl }}">Estado</label>
                <select wire:model="estado" style="{{ $iS }} width:140px;">
                    <option value="activa">Activa</option>
                    <option value="cerrada">Cerrada</option>
                </select>
            </div>

            {{-- Botones --}}
            <div style="display:flex; flex-direction:column;">
                <label style="color:transparent; font-size:11px; margin-bottom:5px;">.</label>
                <div style="display:flex; gap:8px;">
                    <button wire:click="save" type="button"
                            style="height:38px; padding:0 18px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; border:none; border-radius:8px; cursor:pointer;">
                        Guardar
                    </button>
                    <button wire:click="cancelForm" type="button"
                            style="height:38px; padding:0 14px; background:#F3F4F6; color:#374151; font-size:13px; font-weight:600; border:1px solid #E5E7EB; border-radius:8px; cursor:pointer;">
                        Cancelar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

{{-- Tabla escritorio --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 220px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Listas registradas</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $listas->total() }}</span>
        @if($selectedListaId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:10px; padding-left:10px; border-left:1px solid #E5E7EB;">
            <button wire:click="viewItems({{ $selectedListaId }})" style="{{ $btnH }} background:#0EA5E9; color:#fff;">Ver productos</button>
            <button wire:click="edit({{ $selectedListaId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Editar</button>
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF; white-space:nowrap;">#</th>
                    <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre</th>
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</th>
                    <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Ciclo</th>
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Creada</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listas as $lista)
                @php $selL = $selectedListaId === $lista->id; @endphp
                <tr wire:key="lista-{{ $lista->id }}" style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selL ? '#F5F3FF' : '#fff' }}; white-space:nowrap;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedListaId === {{ $lista->id }}"
                                   @click="$wire.selectedListaId === {{ $lista->id }} ? $wire.set('selectedListaId', null) : $wire.selectLista({{ $lista->id }})"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $listas->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827;">{{ strtoupper($lista->name) }}</td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="display:inline-flex; padding:2px 10px; border-radius:99px; font-size:12px; font-weight:700;
                            {{ $lista->estado === 'activa' ? 'background:#D1FAE5; color:#059669;' : 'background:#F3F4F6; color:#6B7280;' }}">
                            {{ strtoupper($lista->estado) }}
                        </span>
                    </td>
                    <td style="padding:10px 14px; font-family:monospace; font-size:12px; color:#6B7280;">{{ strtoupper($lista->cycle?->code ?? '—') }}</td>
                    <td style="padding:10px 14px; text-align:center; font-size:12px; color:#6B7280;">{{ $lista->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:64px; text-align:center; color:#9CA3AF; font-size:13px;">No hay listas de precios registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($listas->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $listas->links() }}</div>
    @endif
</div>

{{-- Cards móvil --}}
<div class="sm:hidden flex flex-col gap-3">
    @forelse ($listas as $lista)
    <div style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; overflow:hidden;">
        {{-- Cabecera card --}}
        <div style="background:#F8F7FF; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #EDE9FE;">
            <span style="font-size:14px; font-weight:700; color:#1F2937;">{{ strtoupper($lista->name) }}</span>
            <span style="display:inline-flex; padding:2px 10px; border-radius:99px; font-size:11px; font-weight:700;
                {{ $lista->estado === 'activa' ? 'background:#D1FAE5; color:#065F46;' : 'background:#F3F4F6; color:#6B7280;' }}">
                {{ strtoupper($lista->estado) }}
            </span>
        </div>
        {{-- Cuerpo card --}}
        <div style="padding:10px 14px; display:flex; flex-direction:column; gap:4px;">
            <div style="font-size:12px; color:#6B7280;">
                Ciclo: <span style="font-family:monospace; font-weight:600; color:#374151;">{{ $lista->cycle?->code ?? '—' }}</span>
            </div>
            <div style="font-size:12px; color:#9CA3AF;">Creada: {{ $lista->created_at->format('d/m/Y') }}</div>
        </div>
        {{-- Footer card --}}
        <div style="padding:8px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px; justify-content:flex-end;">
            <button wire:click="viewItems({{ $lista->id }})"
                    style="height:32px; padding:0 12px; background:#EFF6FF; color:#0369A1; font-size:12px; font-weight:600; border:none; border-radius:8px; cursor:pointer;">
                Ver productos
            </button>
            <button wire:click="edit({{ $lista->id }})"
                    style="height:32px; padding:0 12px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; border:1px solid #EDE9FE; border-radius:8px; cursor:pointer;">
                Editar
            </button>
        </div>
    </div>
    @empty
    <div style="padding:40px 20px; text-align:center; color:#9CA3AF; font-size:14px;">No hay listas de precios registradas.</div>
    @endforelse
    @if ($listas->hasPages())
    <div class="px-2 py-3">{{ $listas->links() }}</div>
    @endif
</div>
@endif

</div>
