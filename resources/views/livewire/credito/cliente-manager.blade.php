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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- ── Cabecera + filtros ────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-100">
        <div class="flex-1 min-w-0">
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Buscar por CI, ID_LN, nombre o apellido…"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
        </div>
        <select wire:model.live="filterCiudad"
                class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
            <option value="">Todas las ciudades</option>
            @foreach ($ciudades as $ciu)
                <option value="{{ $ciu }}">{{ $ciu }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterActivo"
                class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
            <option value="">Todos los estados</option>
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
        </select>
        @if (!$showAddForm && !$editingId)
        <button wire:click="showAdd"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-mint-500 hover:bg-mint-600
                       text-white text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo cliente
        </button>
        @endif
    </div>

    {{-- ── Fila agregar ──────────────────────────────────────────────────────── --}}
    @if ($showAddForm)
    <div class="px-5 py-4 bg-mint-50 border-b border-mint-100">
        <p class="text-xs font-semibold text-mint-700 uppercase tracking-wide mb-3">Nuevo cliente</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            <div>
                <label class="text-xs text-gray-500 mb-1 block">CI * <span class="text-gray-400">(usuario de acceso)</span></label>
                <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Nombre *</label>
                <input wire:model="newNombre" type="text" maxlength="120"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Apellido *</label>
                <input wire:model="newApellido" type="text" maxlength="120"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Teléfono * <span class="text-gray-400">(contraseña inicial)</span></label>
                <input wire:model="newTelefono" type="text" maxlength="30"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">NIT</label>
                <input wire:model="newNit" type="text" maxlength="30"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Correo</label>
                <input wire:model="newCorreo" type="email" maxlength="191"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newCorreo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Ciudad *</label>
                <select wire:model.live="newCiudad" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
                    <option value="">-- Seleccionar --</option>
                    @foreach($ciudadesAll as $c)
                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('newCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Provincia *</label>
                <select wire:model.live="newProvincia" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" @disabled(!$newCiudad)>
                    <option value="">-- Seleccionar --</option>
                    @foreach($newProvincias as $p)
                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('newProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Municipio *</label>
                <select wire:model.live="newMunicipio" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" @disabled(!$newProvincia)>
                    <option value="">-- Seleccionar --</option>
                    @foreach($newMunicipios as $m)
                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
                @error('newMunicipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Dirección *</label>
                <input wire:model="newDireccion" type="text" maxlength="255"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                @error('newDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Vendedor</label>
                <select wire:model="newVendedorId"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
                    <option value="">Sin asignar</option>
                    @foreach ($vendedores as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500 mb-1 block">Activo</label>
                <div class="flex items-center h-9">
                    <input wire:model="newActive" type="checkbox" class="w-4 h-4 rounded text-mint-600" />
                </div>
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button wire:click="saveNew"
                    class="px-4 py-2 bg-mint-500 hover:bg-mint-600 text-white text-sm font-medium rounded-lg transition-colors">
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
    @if ($clientes->isEmpty() && !$showAddForm)
        <div class="py-16 text-center text-gray-400 text-sm">No hay clientes registrados.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">ID_LN</th>
                    <th class="px-4 py-3 text-left font-semibold">CI</th>
                    <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                    <th class="px-4 py-3 text-left font-semibold hidden lg:table-cell">Apellido</th>
                    <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Teléfono</th>
                    <th class="px-4 py-3 text-left font-semibold hidden xl:table-cell">Ciudad</th>
                    <th class="px-4 py-3 text-left font-semibold hidden xl:table-cell">Vendedor</th>
                    <th class="px-4 py-3 text-center font-semibold">Estado</th>
                    <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($clientes as $c)

                {{-- ── Fila edición inline ──────────────────────────────────── --}}
                @if ($editingId === $c->id)
                <tr wire:key="edit-{{ $c->id }}" class="bg-mint-50/60">
                    <td colspan="9" class="px-5 py-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">CI * <span class="text-gray-400">(actualiza usuario de acceso)</span></label>
                                <input wire:model="editCi" type="text" maxlength="20"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Nombre *</label>
                                <input wire:model="editNombre" type="text" maxlength="120"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Apellido *</label>
                                <input wire:model="editApellido" type="text" maxlength="120"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Teléfono *</label>
                                <input wire:model="editTelefono" type="text" maxlength="30"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">NIT</label>
                                <input wire:model="editNit" type="text" maxlength="30"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Correo</label>
                                <input wire:model="editCorreo" type="email" maxlength="191"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editCorreo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Ciudad *</label>
                                <select wire:model.live="editCiudad" class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($ciudadesAll as $c)
                                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('editCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Provincia *</label>
                                <select wire:model.live="editProvincia" class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" @disabled(!$editCiudad)>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($editProvincias as $p)
                                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('editProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Municipio *</label>
                                <select wire:model.live="editMunicipio" class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" @disabled(!$editProvincia)>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($editMunicipios as $m)
                                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('editMunicipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Dirección *</label>
                                <input wire:model="editDireccion" type="text" maxlength="255"
                                       class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300" />
                                @error('editDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Vendedor</label>
                                <select wire:model="editVendedorId"
                                        class="w-full border border-mint-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mint-300">
                                    <option value="">Sin asignar</option>
                                    @foreach ($vendedores as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Activo</label>
                                <div class="flex items-center h-9">
                                    <input wire:model="editActive" type="checkbox" class="w-4 h-4 rounded text-mint-600" />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="saveEdit"
                                    class="px-4 py-2 bg-mint-500 hover:bg-mint-600 text-white text-sm font-medium rounded-lg transition-colors">
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
                    <td class="px-4 py-3.5 font-mono text-xs text-gray-500">{{ $c->id_ln ?? '—' }}</td>
                    <td class="px-4 py-3.5 font-mono text-sm text-gray-700">{{ $c->ci }}</td>
                    <td class="px-4 py-3.5 font-medium text-gray-800">{{ $c->usuario->name ?? '—' }}</td>
                    <td class="px-4 py-3.5 text-gray-600 hidden lg:table-cell">{{ $c->apellido ?? '—' }}</td>
                    <td class="px-4 py-3.5 text-gray-600 hidden md:table-cell">{{ $c->telefono }}</td>
                    <td class="px-4 py-3.5 text-gray-500 hidden xl:table-cell">{{ $c->ciudad }}</td>
                    <td class="px-4 py-3.5 text-gray-500 hidden xl:table-cell">{{ $c->vendedorUsuario->name ?? '—' }}</td>
                    <td class="px-4 py-3.5 text-center">
                        @if ($c->active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-mint-100 text-mint-700">Activo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button wire:click="toggleActivo({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors {{ $c->active ? 'text-mint-600 hover:bg-mint-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $c->active ? 'M5.636 18.364a9 9 0 010-12.728M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M18.364 5.636a9 9 0 010 12.728M12 21v-1m0-16V3m-9 9h1m16 0h1M5.636 5.636l.707.707M18.364 18.364l.707.707m0-13.435l-.707.707M5.636 18.364l-.707.707M8 12a4 4 0 118 0 4 4 0 01-8 0z' }}"/>
                                </svg>
                            </button>
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-mint-600 hover:bg-mint-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
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
        {{ $clientes->links() }}
    </div>
    @endif
</div>

</div>
