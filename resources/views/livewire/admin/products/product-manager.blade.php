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

{{-- Barra superior: filtros + botón agregar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterCategoriaId" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todas las categorías</option>
        @foreach ($categorias as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
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
    <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Nuevo Producto</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <input wire:model="newCode" type="text" placeholder="Código *"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('newCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="lg:col-span-2">
            <input wire:model="newName" type="text" placeholder="Nombre del producto *"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('newName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <select wire:model="newUnidadId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Unidad —</option>
                @foreach ($unidades as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}{{ $u->abreviatura ? ' ('.$u->abreviatura.')' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model="newCategoriaId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Categoría —</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Campo imagen --}}
    <div class="mt-3 pt-3 border-t border-lavanda-200"
         x-data="{ preview: null }">
        <p class="text-xs font-semibold text-lavanda-600 mb-2">
            Imagen
            <span class="font-normal text-lavanda-400 normal-case">(opcional · JPG/PNG · máx. 2 MB · 800×800 px recomendado)</span>
        </p>
        <div class="flex items-center gap-4">
            {{-- Preview --}}
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-lavanda-100 flex-shrink-0 flex items-center justify-center">
                <img x-show="preview" :src="preview" x-cloak class="w-full h-full object-cover">
                <svg x-show="!preview" class="w-8 h-8 text-lavanda-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            {{-- Selector --}}
            <div>
                <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-xs font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Seleccionar imagen
                    <input type="file"
                           wire:model="newImage"
                           x-on:change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)"
                           accept="image/jpeg,image/png"
                           class="hidden">
                </label>
                <div wire:loading wire:target="newImage" class="text-xs text-lavanda-500 mt-1">Subiendo...</div>
                @error('newImage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 mt-3">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" wire:model="newActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
            Activo
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
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-14">Img</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Código</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Unidad</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Categoría</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($products as $p)
                @if ($editingId === $p->id)
                {{-- FILA EN MODO EDICIÓN --}}
                <tr wire:key="p-edit-{{ $p->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">

                    {{-- Imagen edit: thumbnail + input cambiar --}}
                    <td class="px-3 py-2" x-data="{ preview: null }">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-lavanda-100 flex items-center justify-center">
                                <img x-show="preview" :src="preview" x-cloak class="w-full h-full object-cover">
                                @if ($editCurrentImage)
                                <img x-show="!preview" src="{{ asset('storage/' . $editCurrentImage) }}"
                                     class="w-full h-full object-cover">
                                @else
                                <svg x-show="!preview" class="w-5 h-5 text-lavanda-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                @endif
                            </div>
                            <label class="cursor-pointer">
                                <span class="text-[9px] font-semibold text-lavanda-600 hover:text-lavanda-800 leading-none">Cambiar</span>
                                <input type="file"
                                       wire:model="editImage"
                                       x-on:change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)"
                                       accept="image/jpeg,image/png"
                                       class="hidden">
                            </label>
                            @error('editImage') <p class="text-red-500 text-[9px] text-center">{{ $message }}</p> @enderror
                        </div>
                    </td>

                    <td class="px-4 py-2">
                        <span class="font-mono text-xs text-gray-400">auto</span>
                    </td>
                    <td class="px-4 py-2">
                        <input wire:model="editName" type="text"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 focus:ring-2 focus:ring-lavanda-100 bg-white">
                        @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2 hidden md:table-cell">
                        <select wire:model="editUnidadId"
                                class="w-full border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                            <option value="">— Unidad —</option>
                            @foreach ($unidades as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}{{ $u->abreviatura ? ' ('.$u->abreviatura.')' : '' }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-2 hidden sm:table-cell">
                        <select wire:model="editCategoriaId"
                                class="w-full border border-lavanda-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                            <option value="">— Categoría —</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
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
                <tr wire:key="p-{{ $p->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-3 py-2">
                        @if ($p->image)
                        <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}"
                             class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                        @else
                        <div class="w-10 h-10 rounded-lg bg-lavanda-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-lavanda-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500 font-medium">{{ $p->code }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $p->unidad?->abreviatura ?? $p->unidad?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $p->categoria?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $p->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $p->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="startEdit({{ $p->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleActive({{ $p->id }})" title="{{ $p->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors
                                    {{ $p->active ? 'text-gray-400 hover:text-melocoton-600 hover:bg-melocoton-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                @if ($p->active)
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
                    <td colspan="7" class="px-5 py-14 text-center text-gray-400 text-sm">
                        No hay productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>

</div>
