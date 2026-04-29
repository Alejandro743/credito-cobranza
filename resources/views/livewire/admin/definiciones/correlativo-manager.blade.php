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
     class="fixed bottom-5 right-5 z-50 bg-lavanda-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- ── Cabecera ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100">
        <h2 class="text-base font-semibold text-gray-800">Correlativos</h2>
        @if (!$showAddForm && !$editingId)
        <button wire:click="showAdd"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-lavanda-500 hover:bg-lavanda-600
                       text-white text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo correlativo
        </button>
        @endif
    </div>

    {{-- ── Fila agregar ──────────────────────────────────────────────────────── --}}
    @if ($showAddForm)
    <div class="px-5 py-4 bg-lavanda-50 border-b border-lavanda-100">
        <p class="text-xs font-semibold text-lavanda-700 uppercase tracking-wide mb-3">Nuevo correlativo</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Prefijo *</label>
                <input wire:model="newPrefijo" type="text" maxlength="10" placeholder="LN"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300 uppercase" />
                @error('newPrefijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Siguiente número *</label>
                <input wire:model="newSiguienteNumero" type="number" min="1"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                @error('newSiguienteNumero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Longitud dígitos *</label>
                <input wire:model="newLongitud" type="number" min="1" max="10"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                @error('newLongitud') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Activo</label>
                <div class="flex items-center h-9">
                    <input wire:model="newActivo" type="checkbox" class="w-4 h-4 rounded text-lavanda-600" />
                </div>
            </div>
            <div class="col-span-2 sm:col-span-4">
                <label class="text-xs text-gray-500 mb-1 block">Descripción</label>
                <input wire:model="newDescripcion" type="text" maxlength="200" placeholder="Ej: Correlativo para clientes nuevos"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                @error('newDescripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button wire:click="saveNew"
                    class="px-4 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-medium rounded-lg transition-colors">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- ── Tabla ─────────────────────────────────────────────────────────────── --}}
    @if ($correlativos->isEmpty() && !$showAddForm)
        <div class="py-16 text-center text-gray-400 text-sm">No hay correlativos registrados.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Prefijo</th>
                    <th class="px-5 py-3 text-left hidden sm:table-cell">Descripción</th>
                    <th class="px-5 py-3 text-center">Sig. número</th>
                    <th class="px-5 py-3 text-center hidden sm:table-cell">Longitud</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($correlativos as $c)

                {{-- ── Fila edición inline ──────────────────────────────────── --}}
                @if ($editingId === $c->id)
                <tr wire:key="edit-{{ $c->id }}" class="bg-lavanda-50/60">
                    <td colspan="6" class="px-5 py-4">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Prefijo *</label>
                                <input wire:model="editPrefijo" type="text" maxlength="10"
                                       class="w-full border border-lavanda-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300 uppercase" />
                                @error('editPrefijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Siguiente número *</label>
                                <input wire:model="editSiguienteNumero" type="number" min="1"
                                       class="w-full border border-lavanda-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                                @error('editSiguienteNumero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Longitud *</label>
                                <input wire:model="editLongitud" type="number" min="1" max="10"
                                       class="w-full border border-lavanda-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                                @error('editLongitud') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Activo</label>
                                <div class="flex items-center h-9">
                                    <input wire:model="editActivo" type="checkbox" class="w-4 h-4 rounded text-lavanda-600" />
                                </div>
                            </div>
                            <div class="col-span-2 sm:col-span-4">
                                <label class="text-xs text-gray-500 mb-1 block">Descripción</label>
                                <input wire:model="editDescripcion" type="text" maxlength="200"
                                       class="w-full border border-lavanda-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lavanda-300" />
                                @error('editDescripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="saveEdit"
                                    class="px-4 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-medium rounded-lg transition-colors">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- ── Fila normal ──────────────────────────────────────────── --}}
                @else
                <tr wire:key="row-{{ $c->id }}" class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-5 py-3.5">
                        <span class="font-mono font-bold text-gray-800">{{ $c->prefijo }}</span>
                        <span class="ml-1 text-xs text-gray-400">
                            + {{ $c->longitud }} dígitos →
                            <span class="font-mono text-gray-600">
                                {{ $c->prefijo }}{{ str_pad($c->siguiente_numero, $c->longitud, '0', STR_PAD_LEFT) }}
                            </span>
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 text-sm hidden sm:table-cell">
                        {{ $c->descripcion ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-center font-mono text-gray-700">{{ $c->siguiente_numero }}</td>
                    <td class="px-5 py-3.5 text-center text-gray-500 hidden sm:table-cell">{{ $c->longitud }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @if ($c->activo)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-mint-100 text-mint-700">Activo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            {{-- Toggle activo --}}
                            <button wire:click="toggleActivo({{ $c->id }})" title="{{ $c->activo ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors {{ $c->activo ? 'text-mint-600 hover:bg-mint-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $c->activo ? 'M5.636 18.364a9 9 0 010-12.728M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M18.364 5.636a9 9 0 010 12.728M12 21v-1m0-16V3m-9 9h1m16 0h1M5.636 5.636l.707.707M18.364 18.364l.707.707m0-13.435l-.707.707M5.636 18.364l-.707.707M8 12a4 4 0 118 0 4 4 0 01-8 0z' }}"/>
                                </svg>
                            </button>
                            {{-- Editar --}}
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-lavanda-500 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            {{-- Eliminar --}}
                            <button wire:click="delete({{ $c->id }})"
                                    wire:confirm="¿Eliminar este correlativo?"
                                    title="Eliminar"
                                    class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $correlativos->links() }}
    </div>
    @endif
</div>

</div>
