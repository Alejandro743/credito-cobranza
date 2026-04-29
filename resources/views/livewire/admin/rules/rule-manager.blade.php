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
@if ($mode === 'form')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Regla' : 'Nueva Regla' }}</h2>
    </div>
    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
                <input wire:model="name" type="text" placeholder="Regla para segmento norte"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tipo *</label>
                <select wire:model="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="segmento">Segmento</option>
                    <option value="geografica">Geográfica</option>
                    <option value="comercial">Comercial</option>
                    <option value="personalizado">Personalizado</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Prioridad</label>
                <input wire:model="priority" type="number" min="0" placeholder="0"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Condición</label>
            <textarea wire:model="condicion" rows="3" placeholder="ej: region = 'norte' AND tipo_cliente = 'A'"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 resize-none"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Descripción</label>
            <textarea wire:model="description" rows="2" placeholder="Descripción interna opcional..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 resize-none"></textarea>
        </div>

        {{-- Grupos --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2">Grupos aplicables</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($groups as $g)
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" wire:model="selectedGroups" value="{{ $g->id }}"
                           class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
                    {{ $g->name }}
                </label>
                @endforeach
            </div>
        </div>

        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" wire:model="active" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
            <span class="text-sm text-gray-700">Regla activa</span>
        </label>
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" wire:click="backToList" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Cancelar</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white transition-colors">
                {{ $editing ? 'Guardar cambios' : 'Crear regla' }}
            </button>
        </div>
    </form>
</div>

@else
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-lavanda-50 border border-lavanda-100 rounded-2xl p-4 sm:col-span-2">
        <p class="text-xs text-lavanda-500 font-medium uppercase tracking-wide">Total reglas</p>
        <p class="text-3xl font-bold text-lavanda-700 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-mint-50 border border-mint-100 rounded-2xl p-4 sm:col-span-2">
        <p class="text-xs text-mint-500 font-medium uppercase tracking-wide">Activas</p>
        <p class="text-3xl font-bold text-mint-700 mt-1">{{ $stats['activas'] }}</p>
    </div>
</div>

<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar regla..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterType" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los tipos</option>
        <option value="segmento">Segmento</option>
        <option value="geografica">Geográfica</option>
        <option value="comercial">Comercial</option>
        <option value="personalizado">Personalizado</option>
    </select>
    <button wire:click="create" class="bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        + Nueva Regla
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if ($rules->isEmpty())
        <div class="py-16 text-center text-gray-400 text-sm">No hay reglas registradas.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Tipo</th>
                    <th class="px-5 py-3 text-center">Prioridad</th>
                    <th class="px-5 py-3 text-center">Grupos</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($rules as $r)
                <tr wire:key="r-{{ $r->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $r->name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            @switch($r->type)
                                @case('segmento') bg-lavanda-100 text-lavanda-700 @break
                                @case('geografica') bg-celeste-100 text-celeste-700 @break
                                @case('comercial') bg-mint-100 text-mint-700 @break
                                @default bg-melocoton-100 text-melocoton-700
                            @endswitch">
                            {{ ucfirst($r->type) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $r->priority }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $r->groups_count }}</td>
                    <td class="px-5 py-3 text-center">
                        <button wire:click="toggleActive({{ $r->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                    {{ $r->active ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $r->active ? 'Activa' : 'Inactiva' }}
                        </button>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <button wire:click="edit({{ $r->id }})" class="text-lavanda-500 hover:text-lavanda-700 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $rules->links() }}</div>
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
