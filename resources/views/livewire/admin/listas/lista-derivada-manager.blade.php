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
{{-- ═══════════════════════════════════════════════════════ GRUPOS MODE ══ --}}
@if ($mode === 'grupos' && $viewingDerivada)
<div>
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Grupos — {{ $viewingDerivada->name }}</h2>
            <p class="text-xs text-gray-500">Maestra: {{ $viewingDerivada->listaMaestra->name ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Asignar grupo</h3>
        <div class="flex gap-3">
            <select wire:model="addGroupId" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Seleccionar grupo —</option>
                @foreach ($availableGroups as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>
            <button wire:click="addGrupo" class="bg-mint-500 hover:bg-mint-600 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors whitespace-nowrap">
                + Asignar
            </button>
        </div>
        @error('addGroupId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">Grupos asignados ({{ $assignedGroups->count() }})</span>
        </div>
        @if ($assignedGroups->isEmpty())
            <div class="py-12 text-center text-gray-400 text-sm">Sin grupos asignados.</div>
        @else
        <ul class="divide-y divide-gray-50">
            @foreach ($assignedGroups as $g)
            <li class="flex items-center justify-between px-5 py-3">
                <span class="text-sm font-medium text-gray-800">{{ $g->name }}</span>
                <button wire:click="removeGrupo({{ $g->id }})"
                        wire:confirm="¿Quitar este grupo de la lista?"
                        class="text-red-400 hover:text-red-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ ITEMS MODE ══ --}}
@elseif ($mode === 'items' && $viewingDerivada)
<div>
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Productos — {{ $viewingDerivada->name }}</h2>
            <p class="text-xs text-gray-500">Maestra: {{ $viewingDerivada->listaMaestra->name ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Agregar producto de la maestra</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Producto *</label>
                <select wire:model="addMaestraItemId" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="">— Seleccionar —</option>
                    @foreach ($maestraItems as $mi)
                        <option value="{{ $mi->id }}">{{ $mi->product->name ?? '—' }} · S/ {{ number_format($mi->precio_base, 2) }}</option>
                    @endforeach
                </select>
                @error('addMaestraItemId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Descuento (S/)</label>
                <input wire:model="addDescuento" type="number" step="0.01" min="0" placeholder="0.00"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('addDescuento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Stock Asignado</label>
                <input wire:model="addStockAsignado" type="number" step="0.01" min="0" placeholder="0"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('addStockAsignado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="mt-3 flex justify-end">
            <button wire:click="addItem" class="bg-mint-500 hover:bg-mint-600 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
                + Agregar
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">Productos ({{ $items->count() }})</span>
        </div>
        @if ($items->isEmpty())
            <div class="py-12 text-center text-gray-400 text-sm">Sin productos. Agrega el primero.</div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-right">Precio Base</th>
                        <th class="px-4 py-3 text-right">Descuento</th>
                        <th class="px-4 py-3 text-right">Precio Final</th>
                        <th class="px-4 py-3 text-right">Stock Asignado</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$item->active ? 'opacity-50' : '' }}">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $item->maestraItem->product->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">S/ {{ number_format($item->maestraItem->precio_base ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-right text-melocoton-600">S/ {{ number_format($item->descuento, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-mint-700">S/ {{ number_format($item->precio_final, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ number_format($item->stock_asignado, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleItemActive({{ $item->id }})"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium transition-colors
                                        {{ $item->active ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $item->active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="removeItem({{ $item->id }})"
                                    wire:confirm="¿Eliminar este producto de la lista?"
                                    class="text-red-400 hover:text-red-600 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ FORM MODE ══ --}}
@elseif ($mode === 'form')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Lista Derivada' : 'Nueva Lista Derivada' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lista Maestra *</label>
            <select wire:model="lista_maestra_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Seleccionar lista maestra —</option>
                @foreach ($maestras as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} ({{ ucfirst($m->estado) }})</option>
                @endforeach
            </select>
            @error('lista_maestra_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
            <input wire:model="name" type="text" placeholder="Lista VIP Ene-2026"
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

{{-- ═══════════════════════════════════════════════════════ LIST MODE ══ --}}
@else
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-lavanda-50 border border-lavanda-100 rounded-2xl p-4">
        <p class="text-xs text-lavanda-500 font-medium uppercase tracking-wide">Total</p>
        <p class="text-3xl font-bold text-lavanda-700 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-mint-50 border border-mint-100 rounded-2xl p-4">
        <p class="text-xs text-mint-500 font-medium uppercase tracking-wide">Activas</p>
        <p class="text-3xl font-bold text-mint-700 mt-1">{{ $stats['activa'] }}</p>
    </div>
    <div class="bg-celeste-50 border border-celeste-100 rounded-2xl p-4">
        <p class="text-xs text-celeste-500 font-medium uppercase tracking-wide">Cerradas</p>
        <p class="text-3xl font-bold text-celeste-700 mt-1">{{ $stats['cerrada'] }}</p>
    </div>
</div>

<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar lista derivada..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <button wire:click="create" class="bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        + Nueva Lista Derivada
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if ($derivadas->isEmpty())
        <div class="py-16 text-center text-gray-400 text-sm">No hay listas derivadas.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Lista Maestra</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-center">Creada</th>
                    <th class="px-5 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($derivadas as $d)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $d->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $d->listaMaestra->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $d->estado === 'activa' ? 'bg-mint-100 text-mint-700' : ($d->estado === 'cerrada' ? 'bg-gray-100 text-gray-600' : 'bg-melocoton-100 text-melocoton-700') }}">
                            {{ ucfirst($d->estado) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-500">{{ $d->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <button wire:click="viewItems({{ $d->id }})" title="Productos"
                                    class="text-celeste-500 hover:text-celeste-700 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </button>
                            <button wire:click="viewGrupos({{ $d->id }})" title="Grupos"
                                    class="text-mint-600 hover:text-mint-800 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                            <button wire:click="edit({{ $d->id }})" title="Editar"
                                    class="text-lavanda-500 hover:text-lavanda-700 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $derivadas->links() }}
    </div>
    @endif
</div>
@endif

@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif
</div>
