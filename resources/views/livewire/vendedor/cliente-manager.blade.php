<div>

<style>
.mc-wrap { overflow-x: auto; background: #fff; }
.mc-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 13px; }
.mc-table .sticky-combined {
    position: sticky; left: 0; z-index: 2; background: #fff; padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07);
}
.mc-table thead .sticky-combined { background: #FFFFE3; }
</style>

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
        <thead class="tracking-wide">
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
                <td data-label="CI / Nombre" class="sticky-combined" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#534AB7; display:flex; align-items:center; justify-content:center;">{{ $c->ci }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#534AB7;">{{ $c->usuario->name ?? '—' }} {{ $c->apellido }}</p>
                        </div>
                    </div>
                </td>
                <td data-label="Teléfono" style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">{{ $c->telefono }}</td>
                <td data-label="Ciudad" style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">{{ $c->ciudad }}</td>
                <td data-label="Estado" style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <span class="inline-flex items-center text-xs font-semibold"
                          style="{{ $c->active ? 'background:#F0FDF4; color:#15803D;' : 'background:#f3f4f6; color:#6b7280;' }} padding:3px 10px; border-radius:6px;">
                        {{ $c->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td data-label="" style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
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
<div style="position:fixed; inset:0; z-index:60; display:flex; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.45);"
     wire:click.self="closeModal">
    <div style="background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(0,0,0,.18); width:100%; max-width:520px; display:flex; flex-direction:column;">

        {{-- Header --}}
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:14px 20px; border-radius:20px 20px 0 0; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.7px; margin:0 0 2px;">Datos del cliente</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0;">{{ $viewingCliente->nombre }} {{ $viewingCliente->apellido }}</p>
                <p style="font-size:12px; color:#9CA3AF; margin:2px 0 0; font-family:monospace;">CI: {{ $viewingCliente->ci }}</p>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px;
                    {{ $viewingCliente->active ? 'background:#D1FAE5; color:#059669;' : 'background:#F3F4F6; color:#9CA3AF;' }}">
                    {{ $viewingCliente->active ? 'Activo' : 'Inactivo' }}
                </span>
                <button wire:click="closeModal"
                        style="width:32px; height:32px; border-radius:9px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                        @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div style="padding:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px 24px;">
            @foreach ([
                ['Teléfono',  $viewingCliente->telefono  ?: '—'],
                ['NIT',       $viewingCliente->nit       ?: '—'],
                ['Correo',    $viewingCliente->correo    ?: '—'],
                ['Ciudad',    $viewingCliente->ciudad    ?: '—'],
                ['Provincia', $viewingCliente->provincia ?: '—'],
                ['Municipio', $viewingCliente->municipio ?: '—'],
            ] as [$label, $value])
            <div>
                <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 3px;">{{ $label }}</p>
                <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">{{ $value }}</p>
            </div>
            @endforeach
            <div style="grid-column:span 2;">
                <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 3px;">Dirección</p>
                <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">{{ $viewingCliente->direccion ?: '—' }}</p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; border-radius:0 0 20px 20px; display:flex; justify-content:flex-end; gap:8px;">
            <button wire:click="startEdit({{ $viewingCliente->id }}); $wire.closeModal()"
                    style="height:36px; padding:0 20px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                Editar
            </button>
            <button wire:click="closeModal"
                    style="height:36px; padding:0 24px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cerrar
            </button>
        </div>

    </div>
</div>
@endif

</div>
