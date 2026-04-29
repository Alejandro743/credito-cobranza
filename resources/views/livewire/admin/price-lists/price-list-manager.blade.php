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
    {{-- Flash message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         class="mb-4 flex items-center gap-2 px-4 py-3 bg-mint-50 border border-mint-200 rounded-xl text-mint-700 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar lista de precios..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 bg-white">
        </div>
        <button wire:click="openCreate"
                class="flex items-center gap-2 px-4 py-2.5 bg-celeste-500 hover:bg-celeste-600 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Lista
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Nombre</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden md:table-cell">Vigencia</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Productos</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden lg:table-cell">Grupos</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Estado</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($lists as $list)
                <tr wire:key="pricelist-{{ $list->id }}" class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3.5 font-medium text-gray-800">{{ $list->name }}
                        @if($list->description)
                        <p class="text-xs text-gray-400 font-normal mt-0.5">{{ Str::limit($list->description, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-gray-500 text-xs hidden md:table-cell">
                        @if($list->valid_from || $list->valid_to)
                        <span>{{ $list->valid_from?->format('d/m/Y') ?? '—' }}</span>
                        <span class="text-gray-300 mx-1">→</span>
                        <span>{{ $list->valid_to?->format('d/m/Y') ?? '—' }}</span>
                        @else
                        <span class="text-gray-300">Sin vencimiento</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-celeste-100 text-celeste-700">
                            {{ $list->items_count }} producto{{ $list->items_count !== 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        @if($list->groups->count())
                        <div class="flex flex-wrap gap-1">
                            @foreach($list->groups->take(3) as $g)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-melocoton-100 text-melocoton-700">{{ $g->name }}</span>
                            @endforeach
                            @if($list->groups->count() > 3)
                            <span class="text-xs text-gray-400">+{{ $list->groups->count() - 3 }}</span>
                            @endif
                        </div>
                        @else
                        <span class="text-gray-300 text-xs">Sin grupos</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $list->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $list->active ? 'bg-mint-500' : 'bg-gray-400' }}"></span>
                            {{ $list->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="openItems({{ $list->id }})"
                                    class="p-1.5 rounded-lg hover:bg-lavanda-50 text-lavanda-500 transition-colors" title="Ver productos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </button>
                            <button wire:click="openEdit({{ $list->id }})"
                                    class="p-1.5 rounded-lg hover:bg-celeste-50 text-celeste-500 transition-colors" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $list->id }})"
                                    class="p-1.5 rounded-lg hover:bg-melocoton-50 text-melocoton-500 transition-colors" title="Activar/Desactivar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        No se encontraron listas de precios
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $lists->links() }}</div>

    {{-- Modal crear/editar lista --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ $editing ? 'Editar Lista de Precios' : 'Nueva Lista de Precios' }}</h3>
                <button wire:click="$set('showModal', false)" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                    <input wire:model="listName" type="text" placeholder="Ej: Lista Temporada 2026"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('listName') border-red-300 @enderror">
                    @error('listName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                    <textarea wire:model="description" rows="2" placeholder="Descripción opcional..."
                              class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Válida desde</label>
                        <input wire:model="valid_from" type="date"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('valid_from') border-red-300 @enderror">
                        @error('valid_from') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Válida hasta</label>
                        <input wire:model="valid_to" type="date"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('valid_to') border-red-300 @enderror">
                        @error('valid_to') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" wire:model="active" class="w-4 h-4 rounded text-celeste-500 border-gray-300 focus:ring-celeste-300">
                    <span class="text-sm text-gray-700">Lista activa</span>
                </label>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition-colors">Cancelar</button>
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        class="px-5 py-2 bg-celeste-500 hover:bg-celeste-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">{{ $editing ? 'Actualizar' : 'Crear Lista' }}</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal items --}}
    @if($showItemsModal && $viewingList)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showItemsModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Productos en: {{ $viewingList->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $viewingList->items->count() }} productos registrados</p>
                </div>
                <button wire:click="$set('showItemsModal', false)" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Add item form --}}
            <div class="px-6 py-4 bg-celeste-50 border-b border-celeste-100">
                <p class="text-xs font-semibold text-celeste-700 mb-3 uppercase tracking-wide">Agregar Producto</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-3 sm:col-span-1">
                        <select wire:model="itemProductId"
                                class="w-full px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 bg-white @error('itemProductId') border-red-300 @enderror">
                            <option value="">Seleccionar producto...</option>
                            @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                        @error('itemProductId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input wire:model="itemPrice" type="number" step="0.01" min="0" placeholder="Precio"
                                   class="w-full pl-7 pr-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('itemPrice') border-red-300 @enderror">
                        </div>
                        @error('itemPrice') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <div class="relative">
                            <input wire:model="itemDiscount" type="number" step="0.01" min="0" max="100" placeholder="Descuento %"
                                   class="w-full px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300">
                        </div>
                    </div>
                </div>
                <button wire:click="addItem" wire:loading.attr="disabled" wire:target="addItem"
                        class="mt-3 px-4 py-2 bg-celeste-500 hover:bg-celeste-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm disabled:opacity-60">
                    Agregar
                </button>
            </div>

            {{-- Items list --}}
            <div class="divide-y divide-gray-50">
                @forelse($viewingList->items as $item)
                <div wire:key="item-{{ $item->id }}" class="flex items-center justify-between px-6 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $item->product->code ?? '' }}</p>
                    </div>
                    <div class="flex items-center gap-4 ml-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">${{ number_format($item->price, 2) }}</p>
                            @if($item->discount_pct > 0)
                            <p class="text-xs text-melocoton-500">-{{ $item->discount_pct }}%</p>
                            @endif
                        </div>
                        <button wire:click="removeItem({{ $item->id }})"
                                class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 transition-colors" title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Sin productos en esta lista</div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
