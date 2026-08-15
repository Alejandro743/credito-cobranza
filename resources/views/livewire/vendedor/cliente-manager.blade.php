<div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

{{-- ══ TOOLBAR ══ --}}
@if(!$showAddForm)
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo cliente
    </button>
</div>
@endif

{{-- ══ FORM: Nuevo cliente ══ --}}
@if ($showAddForm)
@php $iF = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo cliente
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
        {{-- Fila 1 --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:12px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">CI * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(usuario)</span></label>
                <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678" style="{{ $iF }}">
                @error('newCi') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="newNombre" type="text" maxlength="120" style="{{ $iF }}">
                @error('newNombre') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Apellido *</label>
                <input wire:model="newApellido" type="text" maxlength="120" style="{{ $iF }}">
                @error('newApellido') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Teléfono * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(contraseña)</span></label>
                <input wire:model="newTelefono" type="text" maxlength="30" style="{{ $iF }}">
                @error('newTelefono') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">NIT</label>
                <input wire:model="newNit" type="text" maxlength="30" style="{{ $iF }}">
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Correo</label>
                <input wire:model="newCorreo" type="email" maxlength="191" style="{{ $iF }}">
                @error('newCorreo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        {{-- Fila 2: Ciudad · Provincia · Municipio · Dirección · botones --}}
        <div style="display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap;">
            <div style="width:130px; flex-shrink:0;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Ciudad *</label>
                <select wire:model.live="newCiudad" style="{{ $iF }} cursor:pointer;">
                    <option value="">— —</option>
                    @foreach($ciudadesAll as $c)
                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('newCiudad') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:130px; flex-shrink:0;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Provincia *</label>
                <select wire:model.live="newProvincia" style="{{ $iF }} cursor:pointer;" @disabled(!$newCiudad)>
                    <option value="">— —</option>
                    @foreach($newProvincias as $p)
                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('newProvincia') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:130px; flex-shrink:0;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Municipio *</label>
                <select wire:model.live="newMunicipio" style="{{ $iF }} cursor:pointer;" @disabled(!$newProvincia)>
                    <option value="">— —</option>
                    @foreach($newMunicipios as $m)
                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
                @error('newMunicipio') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:220px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Dirección *</label>
                <input wire:model="newDireccion" type="text" maxlength="255" style="{{ $iF }}">
                @error('newDireccion') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <button wire:click="saveNew"
                    style="height:38px; padding:0 22px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; flex-shrink:0;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:38px; padding:0 16px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; flex-shrink:0;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ MOBILE: Cards ══ --}}
@php
$iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;';
@endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($clientes as $c)

    @if($editingId === $c->id)
    {{-- Card edición --}}
    <div wire:key="card-edit-{{ $c->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px;">
            <p style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0;">Editando: {{ $c->usuario->name ?? $c->ci }}</p>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editNombre" type="text" style="{{ $iM }}">
                    @error('editNombre')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Apellido *</label>
                    <input wire:model="editApellido" type="text" style="{{ $iM }}">
                    @error('editApellido')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Teléfono *</label>
                    <input wire:model="editTelefono" type="text" style="{{ $iM }}">
                    @error('editTelefono')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">NIT</label>
                    <input wire:model="editNit" type="text" style="{{ $iM }}">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Correo</label>
                <input wire:model="editCorreo" type="email" style="{{ $iM }}">
                @error('editCorreo')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Ciudad *</label>
                    <select wire:model.live="editCiudad" style="{{ $iM }} cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        @foreach($ciudadesAll as $ciudad)
                        <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editCiudad')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Provincia *</label>
                    <select wire:model.live="editProvincia" style="{{ $iM }} cursor:pointer;" @disabled(!$editCiudad)>
                        <option value="">— Seleccionar —</option>
                        @foreach($editProvincias as $prov)
                        <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editProvincia')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Municipio *</label>
                    <select wire:model.live="editMunicipio" style="{{ $iM }} cursor:pointer;" @disabled(!$editProvincia)>
                        <option value="">— Seleccionar —</option>
                        @foreach($editMunicipios as $mun)
                        <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editMunicipio')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                    <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Dirección *</label>
                <input wire:model="editDireccion" type="text" maxlength="255" style="{{ $iM }}">
                @error('editDireccion')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
                <button wire:click="saveEdit" style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">Guardar</button>
                <button wire:click="cancelEdit" style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            </div>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $c->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Header --}}
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(mb_substr($c->usuario->name ?? $c->ci, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $c->usuario->name ?? '—' }} {{ $c->apellido }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;"><span style="color:#9CA3AF; font-family:inherit;">CI:</span> {{ $c->ci }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $c->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $c->active ? '#059669' : '#9CA3AF' }};">
                {{ $c->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Info --}}
        <div style="padding:10px 14px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <span style="font-size:12px; color:#6B7280;"><span style="font-weight:600; color:#9CA3AF;">Ciudad:</span> {{ $c->ciudad }}</span>
            @if($c->telefono)
            <span style="font-size:12px; color:#6B7280;"><span style="font-weight:600; color:#9CA3AF;">Celular:</span> {{ $c->telefono }}</span>
            @endif
        </div>

        {{-- Acciones --}}
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button wire:click="ver({{ $c->id }})"
                    style="flex:1; height:34px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
            <button wire:click="startEdit({{ $c->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No tenés clientes registrados.</p>
    @endforelse
    @if ($clientes->hasPages())
    <div style="padding-top:8px;">{{ $clientes->links() }}</div>
    @endif
</div>

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Mis clientes</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $clientes->total() }}</span>
            @if($selectedClienteId)
            @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
            <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
                @if($editingId === $selectedClienteId)
                    <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                        <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Guardar
                    </button>
                    <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Cancelar
                    </button>
                @else
                    <button wire:click="startEdit({{ $selectedClienteId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                        <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                    <button wire:click="verSeleccionado" style="{{ $btnH }} background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE;">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Ver
                    </button>
                @endif
            </div>
            @endif
        </div>
        <button type="button" wire:click="$refresh"
                style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Actualizar
        </button>
    </div>

    @php
    $fI   = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
    $fS   = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
    $fW   = 'position:relative; margin-top:4px;';
    $fIc  = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
    $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
    $colFiltersV = ['ID_LN'=>'colFilterIdLn','CI'=>'colFilterCi','Nombre'=>'colFilterNombre','Apellido'=>'colFilterApellido','Teléfono'=>'colFilterTelefono','NIT'=>'colFilterNit','Correo'=>'colFilterCorreo','Ciudad'=>'colFilterCiudad','Provincia'=>'colFilterProvincia','Municipio'=>'colFilterMunicipio','Dirección'=>'colFilterDireccion'];
    @endphp
    <div style="overflow-x:auto;">
        @if ($clientes->isEmpty() && !$showAddForm)
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No tenés clientes registrados.</p>
        @else
        <table style="table-layout:fixed; width:100%; min-width:1700px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:50px;">   {{-- # --}}
                <col style="width:90px;">   {{-- ID_LN --}}
                <col style="width:110px;">  {{-- CI --}}
                <col style="width:150px;">  {{-- Nombre --}}
                <col style="width:130px;">  {{-- Apellido --}}
                <col style="width:110px;">  {{-- Teléfono --}}
                <col style="width:110px;">  {{-- NIT --}}
                <col style="width:170px;">  {{-- Correo --}}
                <col style="width:110px;">  {{-- Ciudad --}}
                <col style="width:110px;">  {{-- Provincia --}}
                <col style="width:110px;">  {{-- Municipio --}}
                <col style="width:180px;">  {{-- Dirección --}}
                <col style="width:90px;">   {{-- Estado --}}
            </colgroup>
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; vertical-align:top; position:sticky; left:0; z-index:2; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedClienteId !== null"
                                   :disabled="$wire.selectedClienteId === null || $wire.editingId !== null"
                                   @click.prevent="$wire.selectedClienteId !== null && $wire.editingId === null && $wire.set('selectedClienteId', null)"
                                   :style="($wire.selectedClienteId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>
                    @foreach(['ID_LN','CI','Nombre','Apellido','Teléfono','NIT','Correo','Ciudad','Provincia','Municipio','Dirección','Estado'] as $col)
                    <th style="padding:8px 10px 6px; text-align:left; position:relative; user-select:none; overflow:hidden; vertical-align:top;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">{{ $col }}</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                        @if($col === 'Estado')
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        @else
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="{{ $colFiltersV[$col] }}" @click.stop type="text" style="{{ $fI }}"></div>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)

                {{-- Fila edición inline --}}
                @if ($editingId === $c->id)
                @php $iE = 'width:100%; height:32px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; color:#374151;'; @endphp
                <tr wire:key="edit-{{ $c->id }}" style="background:#FAFAFE; border-bottom:2px solid #EDE9FE;">
                    <td style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:#FAFAFE;">
                        <span style="font-size:13px; color:#111827;">{{ $clientes->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:10px 10px;"><span style="font-size:11px;font-family:monospace;font-weight:700;color:#7B6FE8;white-space:nowrap;">{{ $c->id_ln ?? '—' }}</span></td>
                    <td style="padding:10px 10px;"><span style="font-size:12px;font-family:monospace;color:#374151;white-space:nowrap;">{{ $c->ci }}</span></td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editNombre" type="text" style="{{ $iE }}">
                        @error('editNombre') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editApellido" type="text" style="{{ $iE }}">
                        @error('editApellido') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editTelefono" type="text" style="{{ $iE }}">
                        @error('editTelefono') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editNit" type="text" style="{{ $iE }}">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editCorreo" type="email" style="{{ $iE }}">
                        @error('editCorreo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model.live="editCiudad" style="{{ $iE }} cursor:pointer;">
                            <option value="">— Ciudad —</option>
                            @foreach($ciudadesAll as $ciudad)
                            <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editCiudad') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model.live="editProvincia" style="{{ $iE }} cursor:pointer;" @disabled(!$editCiudad)>
                            <option value="">— Provincia —</option>
                            @foreach($editProvincias as $prov)
                            <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editProvincia') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model.live="editMunicipio" style="{{ $iE }} cursor:pointer;" @disabled(!$editProvincia)>
                            <option value="">— Municipio —</option>
                            @foreach($editMunicipios as $mun)
                            <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editMunicipio') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editDireccion" type="text" maxlength="255" style="{{ $iE }}">
                        @error('editDireccion') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model="editActive" style="{{ $iE }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selC = $selectedClienteId === $c->id; @endphp
                <tr wire:key="c-{{ $c->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s; background:{{ $selC ? '#F5F3FF' : '' }}; {{ $selC ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selC ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selC ? '#F5F3FF' : '' }}'">

                    <td style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selC ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedClienteId === {{ $c->id }}"
                                   @click="$wire.selectedClienteId === {{ $c->id }} ? $wire.set('selectedClienteId', null) : $wire.selectCliente({{ $c->id }})"
                                   :disabled="{{ $editingId && $editingId !== $c->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $c->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                        </div>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:11px; font-family:monospace; font-weight:700; color:#7B6FE8; white-space:nowrap;">{{ $c->id_ln ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; color:#374151; white-space:nowrap;">{{ $c->ci }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->usuario->name ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->apellido ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->telefono }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->nit ?: '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->correo ?: '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->ciudad }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->provincia ?: '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->municipio ?: '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->direccion ?: '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;
                                     background:{{ $c->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $c->active ? '#059669' : '#9CA3AF' }};">
                            {{ $c->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($clientes->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $clientes->links() }}</div>
    @endif
</div>

<script>
window.colResize = function () {
    return {
        start(e) {
            e.preventDefault();
            const th = this.$el.closest('th');
            const table = th.closest('table');
            const idx = Array.from(th.closest('tr').querySelectorAll('th')).indexOf(th);
            const col = table.querySelectorAll('colgroup col')[idx];
            const startX = e.clientX;
            const startW = col ? (parseFloat(col.style.width) || th.getBoundingClientRect().width)
                               : th.getBoundingClientRect().width;
            const onMove = mv => {
                const w = Math.max(60, startW + mv.clientX - startX);
                if (col) col.style.width = w + 'px';
            };
            const onUp = () => {
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
            };
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        }
    };
};
</script>

{{-- ══ MODAL: Ver cliente ══ --}}
@if ($viewingCliente)
<div style="position:fixed; inset:0; z-index:60; display:flex; align-items:center; justify-content:center; padding:16px; background:rgba(0,0,0,.45);"
     wire:click.self="closeModal">
    <div style="background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(0,0,0,.18); width:100%; max-width:560px; display:flex; flex-direction:column; max-height:calc(100vh - 32px);">

        {{-- Header --}}
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:14px 20px; border-radius:20px 20px 0 0; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; min-width:0;">
            <div style="min-width:0; flex:1; padding-right:12px;">
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.7px; margin:0 0 2px;">Datos del cliente</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $viewingCliente->usuario->name ?? '—' }} {{ $viewingCliente->apellido }}</p>
            </div>
            <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; flex-shrink:0; {{ $viewingCliente->active ? 'background:#D1FAE5; color:#059669;' : 'background:#F3F4F6; color:#9CA3AF;' }}">
                {{ $viewingCliente->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Body --}}
        <div style="padding:16px 20px; overflow-y:auto; display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            @php
                $field = fn($label, $value) => '<div style="min-width:0;"><p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 3px;">'.$label.'</p><p style="font-size:13px; color:#374151; margin:0; font-weight:500; overflow-wrap:break-word; word-break:break-word;">'.e($value ?: '—').'</p></div>';
            @endphp
            {!! $field('CI', $viewingCliente->ci) !!}
            {!! $field('ID_LN', $viewingCliente->id_ln) !!}
            {!! $field('Teléfono', $viewingCliente->telefono) !!}
            {!! $field('NIT', $viewingCliente->nit) !!}
            {!! $field('Correo', $viewingCliente->correo) !!}
            {!! $field('Ciudad', $viewingCliente->ciudad) !!}
            {!! $field('Provincia', $viewingCliente->provincia) !!}
            {!! $field('Municipio', $viewingCliente->municipio) !!}
            <div style="grid-column:1/-1; min-width:0;">
                <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 3px;">Dirección</p>
                <p style="font-size:13px; color:#374151; margin:0; font-weight:500; overflow-wrap:break-word; word-break:break-word;">{{ $viewingCliente->direccion ?: '—' }}</p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end;">
            <button wire:click="closeModal"
                    style="height:36px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif

</div>
