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
                <tr wire:key="row-{{ $c->id }}" x-data="{ modal: false }" class="hover:bg-gray-50/60 transition-colors">
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
                            {{-- Ver cliente --}}
                            <button @click="modal = true" title="Ver cliente"
                                    class="p-1.5 rounded-lg text-mint-600 hover:bg-mint-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            {{-- Toggle activo --}}
                            <button wire:click="toggleActivo({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors {{ $c->active ? 'text-mint-600 hover:bg-mint-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="{{ $c->active ? 'M5.636 18.364a9 9 0 010-12.728M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M18.364 5.636a9 9 0 010 12.728M12 21v-1m0-16V3m-9 9h1m16 0h1M5.636 5.636l.707.707M18.364 18.364l.707.707m0-13.435l-.707.707M5.636 18.364l-.707.707M8 12a4 4 0 118 0 4 4 0 01-8 0z' }}"/>
                                </svg>
                            </button>
                            {{-- Editar --}}
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-mint-600 hover:bg-mint-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </td>

                    {{-- Modal Ver Cliente --}}
                    <template x-teleport="body">
                    <div x-show="modal"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                         style="background:rgba(20,10,40,0.4);"
                         @click.self="modal = false">
                        <div x-show="modal"
                             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             style="background:#F0FDF4; border-radius:18px; width:100%; max-width:440px; overflow:hidden; position:relative; max-height:90vh; overflow-y:auto;">
                            <button @click="modal = false"
                                    style="position:absolute; top:14px; right:14px; width:28px; height:28px; border-radius:8px; background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 4px rgba(0,0,0,0.1); z-index:1;">
                                <svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div style="padding:20px 18px 18px;">
                                @php
                                $fSty = 'background:#fff; border-radius:10px; padding:8px 10px; display:flex; align-items:center; gap:8px;';
                                $iBox = 'width:32px; height:32px; border-radius:8px; background:#DCFCE7; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                                $vClr = 'font-size:11px; font-weight:700; color:#166534; margin:0;';
                                $lClr = 'font-size:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6ee7b7; margin-bottom:1px;';
                                @endphp
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px; padding-right:40px;">
                                    <span style="font-size:13px; font-weight:700; color:#15803D; white-space:nowrap;">Datos del Cliente</span>
                                    <div style="flex:1; height:1px; background:#6ee7b7;"></div>
                                    <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:{{ $c->active ? '#DCFCE7' : '#f3f4f6' }}; color:{{ $c->active ? '#15803D' : '#6b7280' }};">
                                        {{ $c->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                        <div style="min-width:0;"><p style="{{ $lClr }}">Nombre</p><p style="{{ $vClr }} word-break:break-word;">{{ $c->usuario->name ?? '—' }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div>
                                        <div><p style="{{ $lClr }}">CI</p><p style="{{ $vClr }} font-family:monospace;">{{ $c->ci }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Apellido</p><p style="{{ $vClr }}">{{ $c->apellido ?: '—' }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Teléfono</p><p style="{{ $vClr }}">{{ $c->telefono ?: '—' }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Correo</p><p style="{{ $vClr }} word-break:break-all;">{{ $c->correo ?: '—' }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                        <div><p style="{{ $lClr }}">NIT</p><p style="{{ $vClr }}">{{ $c->nit ?: '—' }}</p></div>
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px; margin:8px 0;">
                                    <span style="font-size:11px; font-weight:700; color:#15803D; white-space:nowrap;">Dirección</span>
                                    <div style="flex:1; height:1px; background:#6ee7b7;"></div>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Ciudad</p><p style="{{ $vClr }}">{{ strtoupper($c->ciudad ?: '—') }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Provincia</p><p style="{{ $vClr }}">{{ strtoupper($c->provincia ?: '—') }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Municipio</p><p style="{{ $vClr }}">{{ strtoupper($c->municipio ?: '—') }}</p></div>
                                    </div>
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                                        <div><p style="{{ $lClr }}">Dirección</p><p style="{{ $vClr }} word-break:break-word;">{{ $c->direccion ?: '—' }}</p></div>
                                    </div>
                                </div>
                                @if($c->vendedorUsuario)
                                <div style="margin-top:8px;">
                                    <div style="{{ $fSty }}">
                                        <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                        <div><p style="{{ $lClr }}">Vendedor</p><p style="{{ $vClr }}">{{ $c->vendedorUsuario->name }}</p></div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    </template>
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
