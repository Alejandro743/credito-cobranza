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

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o código de ciclo..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select>
    @if ($ciclosDisponibles->isNotEmpty())
    <button wire:click="showAdd" class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Config.
    </button>
    @endif
</div>

{{-- Inline add form --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-lavanda-700 mb-4">Nueva Configuración de Puntos</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div class="lg:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Ciclo *</label>
            <select wire:model="newCycleId" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                <option value="">— Seleccionar ciclo —</option>
                @foreach ($ciclosDisponibles as $ciclo)
                    <option value="{{ $ciclo->id }}">{{ $ciclo->code }} — {{ $ciclo->name }}</option>
                @endforeach
            </select>
            @error('newCycleId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Valor del punto (Bs) *</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">Bs</span>
                <input wire:model="newValorPunto" type="number" step="0.01" min="0.01" placeholder="1.00"
                       class="w-full border border-gray-200 rounded-xl pl-8 pr-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            </div>
            @error('newValorPunto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
            <input wire:model="newDescription" type="text" placeholder="Observación opcional"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        </div>
        <div class="flex items-end pb-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="newActive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 focus:ring-lavanda-400">
                <span class="text-sm font-medium text-gray-700">Activo</span>
            </label>
        </div>
    </div>
    <div class="flex gap-3">
        <button wire:click="saveNew" class="px-5 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">Guardar</button>
        <button wire:click="cancelAdd" class="px-5 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">Cancelar</button>
    </div>
</div>
@endif

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Ciclo</th>
                    <th class="px-5 py-3 text-left hidden md:table-cell">Período</th>
                    <th class="px-5 py-3 text-center">Valor / Punto</th>
                    <th class="px-5 py-3 text-left hidden lg:table-cell">Descripción</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($puntos as $punto)
                @if ($editingId === $punto->id)
                {{-- Inline edit row --}}
                <tr wire:key="edit-{{ $punto->id }}" class="bg-lavanda-50">
                    <td class="px-3 py-2" colspan="2">
                        <div>
                            <p class="font-mono text-xs text-lavanda-700 font-semibold">{{ $punto->cycle->code }}</p>
                            <p class="text-xs text-gray-600">{{ $punto->cycle->name }}</p>
                        </div>
                    </td>
                    <td class="px-3 py-2">
                        <div class="relative">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Bs</span>
                            <input wire:model="editValorPunto" type="number" step="0.01" min="0.01"
                                   class="w-full border border-gray-200 rounded-lg pl-7 pr-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                        </div>
                        @error('editValorPunto') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2 hidden lg:table-cell">
                        <input wire:model="editDescription" type="text" placeholder="Descripción"
                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <input wire:model="editActive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    </td>
                    <td class="px-3 py-2 text-right">
                        <div class="flex gap-1 justify-end">
                            <button wire:click="saveEdit" class="px-3 py-1.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-xs font-semibold rounded-lg transition-colors">Guardar</button>
                            <button wire:click="cancelEdit" class="px-3 py-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-medium rounded-lg transition-colors">Cancelar</button>
                        </div>
                    </td>
                </tr>
                @else
                {{-- Normal row --}}
                <tr wire:key="p-{{ $punto->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="font-mono text-xs text-lavanda-700 font-semibold">{{ $punto->cycle->code }}</p>
                        <p class="text-gray-700 text-sm">{{ $punto->cycle->name }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs hidden md:table-cell">
                        {{ $punto->cycle->start_date->format('d/m/Y') }} — {{ $punto->cycle->end_date->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="font-bold text-lavanda-700">Bs {{ number_format((float) $punto->valor_punto, 2) }}</span>
                        <span class="text-gray-400 text-xs"> / pto</span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs hidden lg:table-cell">
                        {{ $punto->description ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <button wire:click="toggleActive({{ $punto->id }})"
                                class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                    {{ $punto->active ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $punto->active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button wire:click="startEdit({{ $punto->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">No hay configuraciones de puntos registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($puntos->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $puntos->links() }}</div>
    @endif
</div>
</div>
