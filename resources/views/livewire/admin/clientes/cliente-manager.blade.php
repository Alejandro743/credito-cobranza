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
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por ID, CI, nombre o apellido..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterCiudad" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todas las ciudades</option>
        @foreach ($ciudades as $ciudad)
        <option value="{{ $ciudad }}">{{ $ciudad }}</option>
        @endforeach
    </select>
    <select wire:model.live="filterActivo" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
    <button wire:click="openCorrelativo"
            class="flex items-center gap-2 border border-gray-200 text-gray-500 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Correlativo
    </button>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Cliente
    </button>
</div>

{{-- Config correlativo --}}
@if ($showCorrelativo)
<div class="bg-celeste-50 border border-celeste-200 rounded-2xl p-5 mb-5">
    <p class="text-sm text-celeste-700 font-semibold mb-4">Configuración de Correlativo ID_LN</p>
    <div class="flex flex-wrap items-end gap-3">
        <div class="w-24">
            <p class="text-xs text-celeste-600 font-medium mb-1">Prefijo</p>
            <input wire:model="cfgPrefijo" type="text" maxlength="10" placeholder="LN"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-celeste-400 uppercase">
            @error('cfgPrefijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="w-36">
            <p class="text-xs text-celeste-600 font-medium mb-1">Siguiente número</p>
            <input wire:model="cfgSiguienteNumero" type="number" min="1"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-celeste-400">
            @error('cfgSiguienteNumero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="w-28">
            <p class="text-xs text-celeste-600 font-medium mb-1">Dígitos (longitud)</p>
            <input wire:model="cfgLongitud" type="number" min="1" max="10"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-celeste-400">
            @error('cfgLongitud') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="text-xs text-celeste-600 pb-2">
            Ejemplo: <span class="font-mono font-bold">{{ strtoupper($cfgPrefijo ?: 'LN') }}{{ str_pad($cfgSiguienteNumero ?: 1, (int)($cfgLongitud ?: 6), '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex gap-2 pb-0.5">
            <button wire:click="cancelCorrelativo" class="px-4 py-2 text-sm text-gray-600 hover:bg-celeste-100 rounded-xl transition-colors">Cancelar</button>
            <button wire:click="saveCorrelativo" class="px-5 py-2 text-sm font-semibold bg-celeste-500 hover:bg-celeste-600 text-white rounded-xl transition-colors">Guardar</button>
        </div>
    </div>
</div>
@endif

{{-- Formulario nuevo cliente --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <p class="text-sm text-lavanda-600 font-semibold mb-4">Nuevo Cliente</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-3">
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">CI *</p>
            <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
            @error('newCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Nombre *</p>
            <input wire:model="newNombre" type="text" placeholder="Juan"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Apellido *</p>
            <input wire:model="newApellido" type="text" placeholder="Pérez"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Teléfono * <span class="text-gray-400">(será contraseña)</span></p>
            <input wire:model="newTelefono" type="text" maxlength="20" placeholder="70012345"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
            @error('newTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Correo electrónico *</p>
            <input wire:model="newEmail" type="email" placeholder="cliente@email.com"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">NIT</p>
            <input wire:model="newNit" type="text" maxlength="30" placeholder="Opcional"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Ciudad *</p>
            <select wire:model.live="newCiudad" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">-- Seleccionar --</option>
                @foreach($ciudadesAll as $c)
                <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
            @error('newCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Provincia *</p>
            <select wire:model.live="newProvincia" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400" @disabled(!$newCiudad)>
                <option value="">-- Seleccionar --</option>
                @foreach($newProvincias as $p)
                <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
            @error('newProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Municipio *</p>
            <select wire:model.live="newMunicipio" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400" @disabled(!$newProvincia)>
                <option value="">-- Seleccionar --</option>
                @foreach($newMunicipios as $m)
                <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
            @error('newMunicipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2 lg:col-span-2">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Dirección *</p>
            <input wire:model="newDireccion" type="text" placeholder="Av. Principal #123"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        @if (!$esVendedor)
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Vendedor</p>
            <select wire:model="newVendedorId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Sin vendedor —</option>
                @foreach ($vendedores as $v)
                <option value="{{ $v->id }}">{{ $v->name }} {{ $v->apellido }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
    <div class="flex justify-end gap-2">
        <button wire:click="cancelAdd" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors">Cancelar</button>
        <button wire:click="saveNew" class="px-6 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">Guardar</button>
    </div>
</div>
@endif

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Clientes registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $clientes->total() }}</span>
        </div>
        <button type="button" wire:click="$refresh"
                style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Actualizar
        </button>
    </div>

    <div style="overflow:auto; flex:1;">
        @if ($clientes->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay clientes registrados.</p>
        @else
        @php
        $sortColsClientes = ['ID_LN'=>'id_ln','CI'=>'ci','Nombre'=>null,'Apellido'=>null,'Teléfono'=>null,'Ciudad'=>'ciudad','Estado'=>'active'];
        @endphp
        <table style="table-layout:fixed; width:100%; min-width:780px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:44px;">
                <col style="width:110px;">
                <col style="width:100px;">
                <col style="width:140px;">
                <col style="width:140px;">
                <col style="width:110px;">
                <col style="width:120px;">
                <col style="width:90px;">
                <col style="width:100px;">
            </colgroup>
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                    @foreach($sortColsClientes as $label => $key)
                    @if($key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:{{ $label==='Estado' ? 'center' : 'left' }}; user-select:none; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    @else
                    <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">{{ $label }}</th>
                    @endif
                    @endforeach
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)
                @if ($editingId === $c->id)
                {{-- EDICIÓN INLINE --}}
                @php $eS = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box; width:100%;'; @endphp
                <tr wire:key="edit-{{ $c->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; border-left:3px solid #7B6FE8;">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $clientes->firstItem() + $loop->index }}</td>
                    <td style="padding:7px 10px; font-size:12px; font-family:monospace; font-weight:700; color:#111827;">{{ $c->id_ln ?? '—' }}</td>
                    <td style="padding:7px 10px; font-size:12px; font-family:monospace; color:#6B7280;">{{ $c->ci }}</td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editNombre" type="text" placeholder="Nombre"
                               style="{{ $eS }}">
                        @error('editNombre') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editApellido" type="text" placeholder="Apellido"
                               style="{{ $eS }}">
                        @error('editApellido') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editTelefono" type="text" placeholder="Teléfono"
                               style="{{ $eS }} font-family:monospace;">
                        @error('editTelefono') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <select wire:model.live="editCiudad" style="{{ $eS }} cursor:pointer;">
                            <option value="">--</option>
                            @foreach($ciudadesAll as $ciudad)
                            <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        <select wire:model="editActive" style="{{ $eS }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="saveEdit"
                                    style="height:30px; padding:0 10px; background:#7B6FE8; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    style="height:30px; padding:0 8px; background:#F3F4F6; color:#6B7280; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>

                @else
                {{-- FILA NORMAL --}}
                <tr wire:key="c-{{ $c->id }}" style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $clientes->firstItem() + $loop->index }}</td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->id_ln ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->ci }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($c->usuario->name ?? '—')) }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($c->usuario->apellido ?? '—')) }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->telefono }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($c->ciudad)) }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $c->active ? '#D1FAE5' : '#FEE2E2' }};
                                     color:{{ $c->active ? '#059669' : '#DC2626' }};">
                            {{ $c->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid {{ $c->active ? '#FEE2E2' : '#D1FAE5' }}; background:{{ $c->active ? '#FFF1F1' : '#ECFDF5' }}; color:{{ $c->active ? '#EF4444' : '#059669' }}; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.opacity='.8'" @mouseleave="$el.style.opacity='1'">
                                @if ($c->active)
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                @else
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($clientes->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $clientes->links() }}</div>
    @endif
</div>

</div>
