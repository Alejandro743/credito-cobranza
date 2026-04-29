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

{{-- Barra superior --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activas</option>
        <option value="0">Inactivas</option>
    </select>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar
    </button>
</div>

{{-- Formulario inline de agregar --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4 mb-4">
    <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Nueva Categoría</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <input wire:model="newName" type="text" placeholder="Nombre de la categoría *"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('newName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <input wire:model="newDescripcion" type="text" placeholder="Descripción (opcional)"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
        </div>
    </div>
    <div class="flex items-center gap-3 mt-3">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" wire:model="newActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
            Activa
        </label>
        <div class="flex-1"></div>
        <button wire:click="cancelAdd" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors font-medium">
            Cancelar
        </button>
        <button wire:click="saveNew" class="px-5 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">
            Guardar
        </button>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Código</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Descripción</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($categorias as $cat)
                @if ($editingId === $cat->id)
                {{-- FILA EN MODO EDICIÓN --}}
                <tr wire:key="cat-edit-{{ $cat->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                    <td class="px-4 py-2 font-mono text-xs text-gray-400">auto</td>
                    <td class="px-4 py-2">
                        <input wire:model="editName" type="text" placeholder="Nombre *"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 focus:ring-2 focus:ring-lavanda-100 bg-white">
                        @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2 hidden md:table-cell">
                        <input wire:model="editDescripcion" type="text" placeholder="Descripción"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                    </td>
                    <td class="px-4 py-2 text-center">
                        <input type="checkbox" wire:model="editActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300 cursor-pointer">
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="saveEdit" title="Guardar"
                                    class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            <button wire:click="cancelEdit" title="Cancelar"
                                    class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @else
                {{-- FILA EN MODO LECTURA --}}
                <tr wire:key="cat-{{ $cat->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500 font-medium">{{ $cat->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $cat->name }}</td>
                    <td class="px-4 py-3 text-gray-500 hidden md:table-cell">{{ $cat->descripcion ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $cat->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $cat->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="startEdit({{ $cat->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleActive({{ $cat->id }})" title="{{ $cat->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors
                                    {{ $cat->active ? 'text-gray-400 hover:text-melocoton-600 hover:bg-melocoton-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                @if ($cat->active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-14 text-center text-gray-400 text-sm">
                        No hay categorías registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categorias->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $categorias->links() }}
    </div>
    @endif
</div>

</div>
