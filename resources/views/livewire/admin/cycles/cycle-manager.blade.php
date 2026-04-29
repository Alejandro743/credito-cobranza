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
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o descripción..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="abierto">Abierto</option>
        <option value="cerrado">Cerrado</option>
    </select>
    <button wire:click="showAdd" class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Ciclo
    </button>
</div>

{{-- Inline add form --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-lavanda-700 mb-4">Nuevo Ciclo Comercial</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Código *</label>
            <input wire:model="newCode" type="text" maxlength="30" placeholder="CIC-202601"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white uppercase">
            @error('newCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción *</label>
            <input wire:model="newName" type="text" placeholder="Ciclo Enero 2026"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado *</label>
            <select wire:model="newStatus" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                <option value="abierto">Abierto</option>
                <option value="cerrado">Cerrado</option>
            </select>
            @error('newStatus') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha inicio *</label>
            <input wire:model="newStartDate" type="date"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newStartDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha fin *</label>
            <input wire:model="newEndDate" type="date"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newEndDate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Notas</label>
            <input wire:model="newNotes" type="text" placeholder="Observaciones opcionales"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
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
                    <th class="px-5 py-3 text-left">Código</th>
                    <th class="px-5 py-3 text-left">Descripción</th>
                    <th class="px-5 py-3 text-left hidden md:table-cell">Inicio</th>
                    <th class="px-5 py-3 text-left hidden md:table-cell">Fin</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($cycles as $cycle)
                @if ($editingId === $cycle->id)
                {{-- Inline edit row --}}
                <tr wire:key="edit-{{ $cycle->id }}" class="bg-lavanda-50">
                    <td class="px-3 py-2">
                        <span class="font-mono text-xs text-lavanda-700 font-semibold">{{ $cycle->code }}</span>
                    </td>
                    <td class="px-3 py-2">
                        <input wire:model="editName" type="text" placeholder="Descripción"
                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                        @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2 hidden md:table-cell">
                        <input wire:model="editStartDate" type="date"
                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                        @error('editStartDate') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2 hidden md:table-cell">
                        <input wire:model="editEndDate" type="date"
                               class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                        @error('editEndDate') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2">
                        <select wire:model="editStatus" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                            <option value="abierto">Abierto</option>
                            <option value="cerrado">Cerrado</option>
                        </select>
                        @error('editStatus') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
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
                <tr wire:key="c-{{ $cycle->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5 font-mono text-xs text-lavanda-700 font-semibold">{{ $cycle->code }}</td>
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $cycle->name }}</td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs hidden md:table-cell">{{ $cycle->start_date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs hidden md:table-cell">{{ $cycle->end_date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @php
                            $bc = match($cycle->status) {
                                'abierto' => 'bg-mint-100 text-mint-700',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                            $label = match($cycle->status) {
                                'abierto' => 'Abierto',
                                default   => 'Cerrado',
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $bc }}">{{ $label }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center gap-1 justify-end">
                            @if ($cycle->status === 'abierto')
                                <button wire:click="changeStatus({{ $cycle->id }},'cerrado')"
                                        class="px-2.5 py-1 text-xs bg-melocoton-100 text-melocoton-700 rounded-lg hover:bg-melocoton-200 font-medium transition-colors">Cerrar</button>
                            @else
                                <button wire:click="changeStatus({{ $cycle->id }},'abierto')"
                                        class="px-2.5 py-1 text-xs bg-mint-100 text-mint-700 rounded-lg hover:bg-mint-200 font-medium transition-colors">Abrir</button>
                            @endif
                            <button wire:click="startEdit({{ $cycle->id }})" class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">No hay ciclos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($cycles->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $cycles->links() }}</div>
    @endif
</div>
</div>
