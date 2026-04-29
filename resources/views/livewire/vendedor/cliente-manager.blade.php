<div>

<style>
.mc-wrap { overflow-x: auto; background: #fff; }
.mc-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 13px; }
.mc-table .sticky-combined {
    position: sticky; left: 0; z-index: 2; background: #fff; padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07);
}
.mc-table thead .sticky-combined { background: #EFF6FF; }
</style>

@php $theadStyle = 'background:#FAEEDA; color:#633806; font-size:10px; font-weight:500; letter-spacing:0.5px;'; @endphp

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#FAEEDA;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0"
            style="background:rgba(99,56,6,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#633806;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#633806;">Mis Clientes</h1>
    <span class="text-sm font-medium" style="color:#633806;">{{ now()->format('d/m/Y') }}</span>
</div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2"
     style="background:#633806;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="p-4 sm:p-6">

{{-- Toolbar --}}
<div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-bottom:16px;">
    <div style="position:relative; flex-shrink:0; width:200px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#AFA9EC" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar CI, nombre, apellido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #CECBF6; border-radius:8px;
                      background:#FAFAFE; font-size:12px; outline:none;" />
    </div>

    <select wire:model.live="filterCiudad"
            style="padding:7px 10px; border:0.5px solid #CECBF6; border-radius:8px; background:#FAFAFE;
                   font-size:12px; outline:none; color:#534AB7;">
        <option value="">Todas las ciudades</option>
        @foreach ($ciudades as $ciu)
            <option value="{{ $ciu }}">{{ $ciu }}</option>
        @endforeach
    </select>

    <select wire:model.live="filterActivo"
            style="padding:7px 10px; border:0.5px solid #CECBF6; border-radius:8px; background:#FAFAFE;
                   font-size:12px; outline:none; color:#534AB7;">
        <option value="">Todos</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>

    @if (!$showAddForm && !$editingId)
    <button wire:click="showAdd"
            style="display:inline-flex; align-items:center; gap:6px; margin-left:auto;
                   background:transparent; color:#633806; border:1.5px solid #633806;
                   border-radius:8px; padding:7px 14px; font-size:12px; font-weight:500;
                   cursor:pointer; white-space:nowrap;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#633806" stroke-width="2" stroke-linecap="round">
            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            <line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>
        </svg>
        Nuevo Cliente
    </button>
    @endif
</div>

{{-- Form Agregar --}}
@if ($showAddForm)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
    <p class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:#633806;">Nuevo Cliente</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
        <div>
            <label class="text-xs text-gray-500 mb-1 block">CI * <span class="text-gray-400">(usuario de acceso)</span></label>
            <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
            @error('newCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Nombre *</label>
            <input wire:model="newNombre" type="text" maxlength="120"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
            @error('newNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Apellido *</label>
            <input wire:model="newApellido" type="text" maxlength="120"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
            @error('newApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Teléfono * <span class="text-gray-400">(contraseña)</span></label>
            <input wire:model="newTelefono" type="text" maxlength="30"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
            @error('newTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">NIT</label>
            <input wire:model="newNit" type="text" maxlength="30"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Correo</label>
            <input wire:model="newCorreo" type="email" maxlength="191"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
            @error('newCorreo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Ciudad *</label>
            <select wire:model.live="newCiudad" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                <option value="">-- Seleccionar --</option>
                @foreach($ciudadesAll as $c)
                <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
            @error('newCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Provincia *</label>
            <select wire:model.live="newProvincia" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" @disabled(!$newCiudad)>
                <option value="">-- Seleccionar --</option>
                @foreach($newProvincias as $p)
                <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
            @error('newProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-gray-500 mb-1 block">Municipio *</label>
            <select wire:model.live="newMunicipio" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" @disabled(!$newProvincia)>
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
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
            @error('newDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
    <div class="flex gap-2 mt-4">
        <button wire:click="saveNew"
                class="px-4 py-2 text-white text-sm font-semibold rounded-lg transition-colors"
                style="background:#633806;">Guardar</button>
        <button wire:click="cancelAdd"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
            Cancelar</button>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if ($clientes->isEmpty() && !$showAddForm)
        <div class="py-16 text-center text-gray-400 text-sm">No tenés clientes registrados aún.</div>
    @else
    <div class="mc-wrap">
    <table class="mc-table" style="min-width:600px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th class="sticky-combined" style="border:0.5px solid #e5e7eb; font-weight:700; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; display:flex; align-items:center; justify-content:center;">CI</div>
                        <div style="flex:1; padding:8px 10px; text-align:center; display:flex; align-items:center; justify-content:center;">Nombre</div>
                    </div>
                </th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:120px;">Teléfono</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:120px;">Ciudad</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:90px;">Estado</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:90px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $c)

            {{-- Fila edición --}}
            @if ($editingId === $c->id)
            <tr wire:key="edit-{{ $c->id }}">
                <td colspan="5" style="padding:16px; border:0.5px solid #e5e7eb; background:#FAFAFE;">
                    <p class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:#633806;">Editando cliente</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">CI *</label>
                            <input wire:model="editCi" type="text" maxlength="20"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
                            @error('editCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Nombre *</label>
                            <input wire:model="editNombre" type="text" maxlength="120"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
                            @error('editNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Apellido *</label>
                            <input wire:model="editApellido" type="text" maxlength="120"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
                            @error('editApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Teléfono *</label>
                            <input wire:model="editTelefono" type="text" maxlength="30"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-200" />
                            @error('editTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">NIT</label>
                            <input wire:model="editNit" type="text" maxlength="30"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Correo</label>
                            <input wire:model="editCorreo" type="email" maxlength="191"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
                            @error('editCorreo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Ciudad *</label>
                            <select wire:model.live="editCiudad" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                                <option value="">-- Seleccionar --</option>
                                @foreach($ciudadesAll as $ciudad)
                                <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('editCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Provincia *</label>
                            <select wire:model.live="editProvincia" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" @disabled(!$editCiudad)>
                                <option value="">-- Seleccionar --</option>
                                @foreach($editProvincias as $prov)
                                <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                                @endforeach
                            </select>
                            @error('editProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Municipio *</label>
                            <select wire:model.live="editMunicipio" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" @disabled(!$editProvincia)>
                                <option value="">-- Seleccionar --</option>
                                @foreach($editMunicipios as $mun)
                                <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                                @endforeach
                            </select>
                            @error('editMunicipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Dirección *</label>
                            <input wire:model="editDireccion" type="text" maxlength="255"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none" />
                            @error('editDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Activo</label>
                            <div class="flex items-center h-9">
                                <input wire:model="editActive" type="checkbox" class="w-4 h-4 rounded" />
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button wire:click="saveEdit"
                                class="px-4 py-2 text-white text-sm font-semibold rounded-lg"
                                style="background:#633806;">Guardar</button>
                        <button wire:click="cancelEdit"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg">
                            Cancelar</button>
                    </div>
                </td>
            </tr>

            {{-- Fila normal --}}
            @else
            <tr wire:key="row-{{ $c->id }}">
                <td class="sticky-combined" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#534AB7; display:flex; align-items:center; justify-content:center;">{{ $c->ci }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#534AB7;">{{ $c->usuario->name ?? '—' }} {{ $c->apellido }}</p>
                        </div>
                    </div>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">{{ $c->telefono }}</td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">{{ $c->ciudad }}</td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <span class="inline-flex items-center text-xs font-semibold"
                          style="{{ $c->active ? 'background:#F0FDF4; color:#15803D;' : 'background:#f3f4f6; color:#6b7280;' }} padding:3px 10px; border-radius:6px;">
                        {{ $c->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <div class="flex items-center justify-center gap-1.5">
                        <button wire:click="ver({{ $c->id }})" title="Ver detalle"
                                class="p-1.5 rounded-lg transition-colors hover:bg-melocoton-50"
                                style="color:#633806;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                class="p-1.5 rounded-lg transition-colors hover:bg-melocoton-50"
                                style="color:#633806;">
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
    <div class="px-4 py-3 border-t border-gray-100">{{ $clientes->links() }}</div>
    @endif
</div>

</div>

{{-- Modal Ver Cliente --}}
@if ($viewingCliente)
<div x-data="{ open: true }"
     x-show="open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(30,20,10,0.45);"
     @click.self="open=false; $wire.closeModal()">

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4" style="background:#FAEEDA;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                     style="background:rgba(99,56,6,0.15);">
                    <svg class="w-5 h-5" fill="none" stroke="#633806" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm leading-tight" style="color:#633806;">
                        {{ $viewingCliente->usuario->name ?? '—' }} {{ $viewingCliente->apellido }}
                    </p>
                    <p class="text-xs" style="color:#9A6030;">CI: {{ $viewingCliente->ci }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                      style="{{ $viewingCliente->active ? 'background:#F0FDF4; color:#15803D;' : 'background:#f3f4f6; color:#6b7280;' }}">
                    {{ $viewingCliente->active ? 'Activo' : 'Inactivo' }}
                </span>
                <button @click="open=false; $wire.closeModal()"
                        class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                        style="color:#9A6030; background:rgba(99,56,6,0.08);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-5 grid grid-cols-2 gap-x-6 gap-y-4">

            @php
                $fields = [
                    ['Teléfono',  $viewingCliente->telefono  ?: '—'],
                    ['NIT',       $viewingCliente->nit       ?: '—'],
                    ['Correo',    $viewingCliente->correo    ?: '—'],
                    ['Ciudad',    $viewingCliente->ciudad    ?: '—'],
                    ['Provincia', $viewingCliente->provincia ?: '—'],
                    ['Municipio', $viewingCliente->municipio ?: '—'],
                ];
            @endphp

            @foreach ($fields as [$label, $value])
            <div>
                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color:#AFA9EC;">{{ $label }}</p>
                <p class="text-sm font-semibold" style="color:#534AB7;">{{ $value }}</p>
            </div>
            @endforeach

            <div class="col-span-2">
                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color:#AFA9EC;">Dirección</p>
                <p class="text-sm font-semibold" style="color:#534AB7;">{{ $viewingCliente->direccion ?: '—' }}</p>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-5 pb-4 flex justify-end gap-2 border-t border-gray-100 pt-3">
            <button wire:click="startEdit({{ $viewingCliente->id }})"
                    @click="open=false"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                    style="background:transparent; color:#633806; border:1.5px solid #633806;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
            <button @click="open=false; $wire.closeModal()"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                    style="background:#f3f4f6; color:#6b7280;">
                Cerrar
            </button>
        </div>

    </div>
</div>
@endif

</div>
