<div>

@php
$iS     = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$iRow   = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$lStyle = 'display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;';
@endphp

{{-- Toast success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(123,111,232,.35);display:flex;align-items:center;gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
@if(!$showAddForm)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;justify-content:flex-start;">
    <button wire:click="showAdd"
            style="height:36px;padding:0 18px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Vendedor
    </button>
</div>
@endif

{{-- Panel Alta --}}
@if($showAddForm)

{{-- Desktop --}}
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #EDE9FE;box-shadow:0 2px 12px rgba(123,111,232,.12);margin-bottom:20px;overflow:hidden;">
    <div style="background:#F8F7FF;border-bottom:1px solid #EDE9FE;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:800;color:#7B6FE8;display:flex;align-items:center;gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Nuevo Vendedor
        </span>
        <button wire:click="cancelAdd"
                style="width:30px;height:30px;border:1px solid #EDE9FE;background:#fff;color:#9CA3AF;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
        <div style="min-width:170px;">
            <label style="{{ $lStyle }}">Código de Usuario *</label>
            <input wire:model="newCodigoUsuario" type="text" placeholder="Ej. jperez" style="width:100%;{{ $iS }} font-family:monospace;">
            @error('newCodigoUsuario') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">CI *</label>
            <input wire:model="newCi" type="text" style="width:100%;{{ $iS }}">
            @error('newCi') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:150px;">
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="newNombre" type="text" style="width:100%;{{ $iS }}">
            @error('newNombre') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:150px;">
            <label style="{{ $lStyle }}">Apellido *</label>
            <input wire:model="newApellido" type="text" style="width:100%;{{ $iS }}">
            @error('newApellido') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">Teléfono</label>
            <input wire:model="newTelefono" type="text" style="width:100%;{{ $iS }}">
        </div>
        <div style="min-width:170px;">
            <label style="{{ $lStyle }}">Email</label>
            <input wire:model="newEmail" type="email" style="width:100%;{{ $iS }}">
            @error('newEmail') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:160px;">
            <label style="{{ $lStyle }}">Ciudad</label>
            <select wire:model="newCiudadId" style="width:100%;{{ $iS }}">
                <option value="">— Sin ciudad —</option>
                @foreach($ciudades as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Acceso al sistema --}}
    <div style="padding:0 20px 16px;">
        <div style="border:1px solid #EDE9FE;border-radius:10px;padding:14px 16px;background:#FAFAFE;">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;">
                <input wire:model.live="newTieneAcceso" type="checkbox" style="width:16px;height:16px;accent-color:#7B6FE8;cursor:pointer;">
                <span style="font-size:13px;font-weight:700;color:{{ $newTieneAcceso ? '#7B6FE8' : '#6B7280' }};">¿Tiene acceso al sistema?</span>
                <span style="font-size:11px;color:#9CA3AF;">Inicia sesión con su Código de Usuario</span>
            </label>

            @if($newTieneAcceso)
            <div style="display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-top:14px;">
                <div style="min-width:180px;">
                    <label style="{{ $lStyle }}">Contraseña *</label>
                    <input wire:model="newUserPassword" type="password" placeholder="Mínimo 6 caracteres" style="width:100%;{{ $iS }}">
                    @error('newUserPassword') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="min-width:150px;">
                    <label style="{{ $lStyle }}">Rol *</label>
                    <select wire:model="newUserRol" style="width:100%;{{ $iS }}">
                        <option value="">— Seleccionar —</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                    @error('newUserRol') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
            </div>
            @endif
        </div>
    </div>

    <div style="padding:0 20px 20px;display:flex;gap:8px;">
        <button wire:click="saveNew" wire:loading.attr="disabled"
                style="height:38px;padding:0 24px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap; transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="height:38px;padding:0 18px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap; transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
            Cancelar
        </button>
    </div>
</div>

{{-- Móvil --}}
<div class="sm:hidden" style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;margin-bottom:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">Código de Usuario *</label>
        <input wire:model="newCodigoUsuario" type="text" placeholder="Ej. jperez" style="width:100%;{{ $iS }} font-family:monospace;">
        @error('newCodigoUsuario') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
    </div>
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">CI *</label>
        <input wire:model="newCi" type="text" style="width:100%;{{ $iS }}">
        @error('newCi') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
        <div>
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="newNombre" type="text" style="width:100%;{{ $iS }}">
            @error('newNombre') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="{{ $lStyle }}">Apellido *</label>
            <input wire:model="newApellido" type="text" style="width:100%;{{ $iS }}">
            @error('newApellido') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
        <div>
            <label style="{{ $lStyle }}">Teléfono</label>
            <input wire:model="newTelefono" type="text" style="width:100%;{{ $iS }}">
        </div>
        <div>
            <label style="{{ $lStyle }}">Email</label>
            <input wire:model="newEmail" type="email" style="width:100%;{{ $iS }}">
            @error('newEmail') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
    </div>
    <div style="margin-bottom:12px;">
        <label style="{{ $lStyle }}">Ciudad</label>
        <select wire:model="newCiudadId" style="width:100%;{{ $iS }}">
            <option value="">— Sin ciudad —</option>
            @foreach($ciudades as $c)
            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div style="border:1px solid #EDE9FE;border-radius:10px;padding:12px;background:#fff;margin-bottom:12px;">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;">
            <input wire:model.live="newTieneAcceso" type="checkbox" style="width:16px;height:16px;accent-color:#7B6FE8;cursor:pointer;">
            <span style="font-size:12px;font-weight:700;color:{{ $newTieneAcceso ? '#7B6FE8' : '#6B7280' }};">¿Tiene acceso al sistema?</span>
        </label>
        @if($newTieneAcceso)
        <div style="margin-top:10px;display:flex;flex-direction:column;gap:10px;">
            <div>
                <label style="{{ $lStyle }}">Contraseña *</label>
                <input wire:model="newUserPassword" type="password" placeholder="Mínimo 6 caracteres" style="width:100%;{{ $iS }}">
                @error('newUserPassword') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Rol *</label>
                <select wire:model="newUserRol" style="width:100%;{{ $iS }}">
                    <option value="">— Seleccionar —</option>
                    @foreach($roles as $r)
                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
                @error('newUserRol') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        @endif
    </div>

    <div style="display:flex;gap:8px;">
        <button wire:click="saveNew" wire:loading.attr="disabled"
                style="flex:1;height:36px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="flex:1;height:36px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;">
            Cancelar
        </button>
    </div>
</div>
@endif

{{-- Tabla escritorio --}}
@php
$fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
$fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
$fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
$fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
$fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
$thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
@endphp
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;display:flex;flex-direction:column;max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px;display:flex;align-items:center;gap:8px;border-bottom:1px solid #F3F4F6;flex-shrink:0;">
        <span style="font-size:13px;font-weight:700;color:#111827;">Vendedores</span>
        <span style="background:#EDE9FE;color:#7B6FE8;font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;">{{ $vendedores->total() }}</span>
        @if($selectedVendedorId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedVendedorId)
                <button wire:click="saveEdit" wire:loading.attr="disabled" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedVendedorId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @endif
        </div>
        @endif

        @php $btnH2 = 'height:28px; padding:0 10px; border:1px solid #EDE9FE; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; background:#fff; color:#7B6FE8; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="margin-left:auto; display:flex; align-items:center; gap:6px;">
            <button wire:click="$set('showImportModal', true)" style="{{ $btnH2 }}">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                Importar
            </button>
            <button wire:click="exportCsv" style="{{ $btnH2 }}">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 8l5-5 5 5M12 3v12"/></svg>
                Exportar
            </button>
        </div>
    </div>

    <div style="overflow:auto;flex:1;">
        <table style="width:100%;border-collapse:collapse;min-width:1600px;">
            <thead style="position:sticky;top:0;z-index:10;">
                <tr style="background:#F9F8FF;border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedVendedorId !== null"
                                   :disabled="$wire.selectedVendedorId === null || $wire.editingId !== null"
                                   @click.prevent="$wire.selectedVendedorId !== null && $wire.editingId === null && $wire.set('selectedVendedorId', null)"
                                   :style="($wire.selectedVendedorId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'activo'; @endphp
                    <th wire:click="toggleSort('activo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Estado
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </th>

                    {{-- Código Usuario --}}
                    @php $isA = $sortBy === 'codigo_usuario'; @endphp
                    <th wire:click="toggleSort('codigo_usuario')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:150px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Código Usuario
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCodigoUsuario" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- CI --}}
                    @php $isA = $sortBy === 'ci'; @endphp
                    <th wire:click="toggleSort('ci')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:130px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">CI
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCi" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'nombre'; @endphp
                    <th wire:click="toggleSort('nombre')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:140px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Apellido --}}
                    @php $isA = $sortBy === 'apellido'; @endphp
                    <th wire:click="toggleSort('apellido')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:170px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Apellido
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterApellido" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Teléfono --}}
                    <th style="{{ $thC }} text-align:left; min-width:130px;">
                        Teléfono
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterTelefono" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Email --}}
                    @php $isA = $sortBy === 'email'; @endphp
                    <th wire:click="toggleSort('email')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:170px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Email
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterEmail" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Ciudad --}}
                    <th style="{{ $thC }} text-align:left; min-width:180px;">
                        Ciudad
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiudad" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Acceso --}}
                    <th style="{{ $thC }} text-align:center; min-width:120px;">
                        Acceso
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterAcceso" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </th>

                    {{-- Contraseña --}}
                    <th style="{{ $thC }} text-align:center; min-width:150px;">
                        Contraseña
                    </th>

                    {{-- Rol --}}
                    <th style="{{ $thC }} text-align:center; min-width:130px;">
                        Rol
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterRol" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendedores as $v)
                @if($editingId === $v->id)
                {{-- Fila edición inline --}}
                <tr wire:key="edit-{{ $v->id }}" style="background:#FAFAFE;border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#FAFAFE;">
                        <span style="font-size:13px; color:#111827;">{{ $vendedores->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model="editActivo" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editCodigoUsuario" type="text" style="width:100%;{{ $iRow }} font-family:monospace;">
                        @error('editCodigoUsuario') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editCi" type="text" style="width:100%;{{ $iRow }}">
                        @error('editCi') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
                        @error('editNombre') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editApellido" type="text" style="width:100%;{{ $iRow }}">
                        @error('editApellido') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editTelefono" type="text" style="width:100%;{{ $iRow }}">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editEmail" type="email" style="width:100%;{{ $iRow }}">
                        @error('editEmail') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model="editCiudadId" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="">— Sin ciudad —</option>
                            @foreach($ciudades as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:10px 10px;text-align:center;">
                        <input wire:model.live="editTieneAcceso" type="checkbox" style="width:16px;height:16px;accent-color:#7B6FE8;cursor:pointer;">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editUserPassword" type="password" placeholder="{{ $editUserIdActual ? 'Dejar vacío' : 'Mínimo 6' }}"
                               style="width:100%;{{ $iRow }}" {{ $editTieneAcceso ? '' : 'disabled' }}>
                        @error('editUserPassword') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model="editUserRol" style="width:100%;{{ $iRow }} cursor:pointer;" {{ $editTieneAcceso ? '' : 'disabled' }}>
                            <option value="">— Rol —</option>
                            @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                            @endforeach
                        </select>
                        @error('editUserRol') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                </tr>
                @else
                {{-- Fila normal --}}
                @php $selV = $selectedVendedorId === $v->id; @endphp
                <tr wire:key="v-{{ $v->id }}"
                    style="border-bottom:1px solid #F3F4F6;transition:background .1s; background:{{ $selV ? '#F5F3FF' : '' }}; {{ $selV ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selV ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selV ? '#F5F3FF' : '' }}'">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selV ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedVendedorId === {{ $v->id }}"
                                   @click="$wire.selectedVendedorId === {{ $v->id }} ? $wire.set('selectedVendedorId', null) : $wire.selectVendedor({{ $v->id }})"
                                   :disabled="{{ $editingId && $editingId !== $v->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $v->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:13px; color:#111827;">{{ $vendedores->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;
                                     background:{{ $v->activo ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $v->activo ? '#059669' : '#9CA3AF' }};">
                            {{ $v->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;font-family:monospace;">{{ $v->codigo_usuario ?? '—' }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;font-family:monospace;">{{ $v->ci ?? '—' }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ ucwords(strtolower($v->nombre)) }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ ucwords(strtolower($v->apellido)) }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ $v->telefono ?? '—' }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ $v->email ?? '—' }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ $v->ciudad->nombre ?? '—' }}</span></td>
                    <td style="padding:10px 14px;text-align:center;">
                        @if($v->user)
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;background:#DBEAFE;color:#2563EB;">Sí</span>
                        @else
                        <span style="font-size:13px;color:#D1D5DB;">No</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#D1D5DB;">{{ $v->user ? '••••••••' : '—' }}</span></td>
                    <td style="padding:10px 14px;text-align:center;">
                        @if($v->user && $v->user->roles->first())
                        <span style="font-size:13px;color:#111827;">{{ ucfirst($v->user->roles->first()->name) }}</span>
                        @else
                        <span style="font-size:13px;color:#D1D5DB;">—</span>
                        @endif
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="12" style="padding:48px;text-align:center;color:#9CA3AF;font-size:13px;">No hay vendedores registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vendedores->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #F3F4F6;flex-shrink:0;">{{ $vendedores->links() }}</div>
    @endif
</div>

{{-- Tarjetas móvil --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($vendedores as $v)
    @if($editingId === $v->id)
    <div wire:key="card-edit-{{ $v->id }}"
         style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
        <div style="margin-bottom:10px;">
            <label style="{{ $lStyle }}">Código de Usuario *</label>
            <input wire:model="editCodigoUsuario" type="text" style="width:100%;{{ $iRow }} font-family:monospace;">
            @error('editCodigoUsuario') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="margin-bottom:10px;">
            <label style="{{ $lStyle }}">CI *</label>
            <input wire:model="editCi" type="text" style="width:100%;{{ $iRow }}">
            @error('editCi') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">Nombre *</label>
                <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
                @error('editNombre') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Apellido *</label>
                <input wire:model="editApellido" type="text" style="width:100%;{{ $iRow }}">
                @error('editApellido') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">Teléfono</label>
                <input wire:model="editTelefono" type="text" style="width:100%;{{ $iRow }}">
            </div>
            <div>
                <label style="{{ $lStyle }}">Email</label>
                <input wire:model="editEmail" type="email" style="width:100%;{{ $iRow }}">
                @error('editEmail') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
            <div>
                <label style="{{ $lStyle }}">Ciudad</label>
                <select wire:model="editCiudadId" style="width:100%;{{ $iRow }} cursor:pointer;">
                    <option value="">— Sin ciudad —</option>
                    @foreach($ciudades as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="{{ $lStyle }}">Estado</label>
                <select wire:model="editActivo" style="width:100%;{{ $iRow }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div style="border:1px solid #EDE9FE;border-radius:10px;padding:12px;background:#fff;margin-bottom:12px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;">
                <input wire:model.live="editTieneAcceso" type="checkbox" style="width:16px;height:16px;accent-color:#7B6FE8;cursor:pointer;">
                <span style="font-size:12px;font-weight:700;color:{{ $editTieneAcceso ? '#7B6FE8' : '#6B7280' }};">¿Tiene acceso al sistema?</span>
            </label>
            @if($editTieneAcceso)
            <div style="margin-top:10px;display:flex;flex-direction:column;gap:10px;">
                <div>
                    <label style="{{ $lStyle }}">Contraseña {{ $editUserIdActual ? '' : '*' }}</label>
                    <input wire:model="editUserPassword" type="password" placeholder="{{ $editUserIdActual ? 'Dejar vacío' : 'Mínimo 6 caracteres' }}" style="width:100%;{{ $iRow }}">
                    @error('editUserPassword') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lStyle }}">Rol *</label>
                    <select wire:model="editUserRol" style="width:100%;{{ $iRow }} cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                    @error('editUserRol') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                </div>
            </div>
            @endif
        </div>

        <div style="display:flex;gap:8px;">
            <button wire:click="saveEdit" wire:loading.attr="disabled"
                    style="flex:1;height:36px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;">
                <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                <span wire:loading wire:target="saveEdit">Guardando...</span>
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1;height:36px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>
    @else
    <div wire:key="card-{{ $v->id }}"
         style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;">

        <div style="background:#F8F7FF;padding:10px 14px;display:flex;align-items:center;gap:8px;border-bottom:1px solid #EDE9FE;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($v->nombre, 0, 1) . substr($v->apellido, 0, 1)) }}</span>
            </div>
            <div style="flex:1;min-width:0;">
                <span style="font-size:13px;font-weight:700;color:#111827;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ ucwords(strtolower($v->nombre.' '.$v->apellido)) }}</span>
                @if($v->ci) <span style="font-size:11px;color:#7B6FE8;font-family:monospace;">CI {{ $v->ci }}</span> @endif
            </div>
            @if($v->activo)
            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;background:#D1FAE5;color:#059669;flex-shrink:0;">Activo</span>
            @else
            <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;background:#F3F4F6;color:#9CA3AF;flex-shrink:0;">Inactivo</span>
            @endif
        </div>

        <div style="padding:12px 14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Teléfono</span>
                    <span style="font-size:13px;color:#111827;">{{ $v->telefono ?? '—' }}</span>
                </div>
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Ciudad</span>
                    <span style="font-size:13px;color:#111827;">{{ $v->ciudad->nombre ?? '—' }}</span>
                </div>
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Rol</span>
                    <span style="font-size:13px;color:#111827;">{{ $v->user && $v->user->roles->first() ? ucfirst($v->user->roles->first()->name) : '—' }}</span>
                </div>
            </div>
            <div style="margin-top:10px;">
                <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Código de Usuario</span>
                <span style="font-size:13px;color:#111827;font-family:monospace;word-break:break-all;">{{ $v->codigo_usuario ?? '—' }}</span>
            </div>
            @if($v->email)
            <p style="font-size:12px;color:#9CA3AF;margin:8px 0 0;">{{ $v->email }}</p>
            @endif
        </div>

        <div style="padding:10px 14px;border-top:1px solid #F3F4F6;">
            <button wire:click="startEdit({{ $v->id }})"
                    style="width:100%;height:34px;background:#F8F7FF;color:#7B6FE8;border:1px solid #EDE9FE;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Editar
            </button>
        </div>
    </div>
    @endif
    @empty
    <p style="text-align:center;font-size:13px;color:#9CA3AF;padding:48px 0;">No hay vendedores registrados.</p>
    @endforelse
    @if($vendedores->hasPages())
    <div style="margin-top:4px;">{{ $vendedores->links() }}</div>
    @endif
</div>

{{-- ══ MODAL: Importar CSV ══ --}}
@php
    $mHead2 = 'padding:14px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px; flex-shrink:0; background:#fff;';
    $mBody2 = 'padding:20px; display:flex; flex-direction:column; gap:14px; background:#fff;';
    $mFoot2 = 'padding:12px 20px 14px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px; flex-shrink:0; background:#fff;';
    $xBtn2  = 'width:28px; height:28px; border:none; background:#F3F4F6; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#6B7280;';
@endphp
<div x-data="{ open: @entangle('showImportModal') }">
<template x-teleport="body">
<div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);"
     @click.self="$wire.set('showImportModal', false)" @keydown.escape.window="$wire.set('showImportModal', false)">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead2 }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Importar CSV</p>
            <button wire:click="$set('showImportModal', false)" style="{{ $xBtn2 }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="{{ $mBody2 }}">
            <p style="font-size:13px; color:#374151; margin:0;">
                El archivo debe tener las columnas en orden:<br>
                <span style="font-family:monospace; font-size:12px; color:#7B6FE8; font-weight:700;">Código Usuario ; CI ; Nombre ; Apellido ; Teléfono ; Email ; Ciudad ; Estado ; Acceso ; Rol</span><br>
                <span style="font-size:11px; color:#9CA3AF;">Se identifica por CI: si ya existe un vendedor con ese CI se actualiza, si no existe se crea. Acceso = 1 crea/actualiza su login con Usuario = Código de Usuario y Contraseña = su CI. Los códigos válidos de Estado, Acceso y Rol están en las hojas de referencia del formato de ejemplo. Primera fila = encabezado (se omite).</span>
            </p>
            <button wire:click="downloadImportTemplate"
                    style="display:inline-flex; align-items:center; gap:6px; height:32px; padding:0 12px; border:1px dashed #C4B5FD; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:700; cursor:pointer; width:fit-content;">
                <svg width="12" height="12" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 8l5-5 5 5M12 3v12"/></svg>
                Descargar formato de ejemplo (.xlsx)
            </button>
            <div>
                <input wire:model="importFile" type="file" accept=".csv,.txt"
                       style="width:100%; font-size:13px; color:#374151; border:1px dashed #C4B5FD; border-radius:8px; padding:10px; background:#F8F7FF; cursor:pointer; box-sizing:border-box;">
                @error('importFile') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="{{ $mFoot2 }}">
            <button wire:click="$set('showImportModal', false)" style="height:36px; padding:0 14px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            <button wire:click="importCsv" wire:loading.attr="disabled" wire:target="importCsv"
                    style="height:36px; padding:0 18px; border:none; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                <span wire:loading.remove wire:target="importCsv">Procesar</span>
                <span wire:loading wire:target="importCsv">Procesando...</span>
            </button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: Resultado Import ══ --}}
<div x-data="{ open: @entangle('showImportResultModal') }">
<template x-teleport="body">
<div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);"
     @keydown.escape.window="$wire.set('showImportResultModal', false)">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead2 }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#D1FAE5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Importación completada</p>
            <button wire:click="$set('showImportResultModal', false)" style="{{ $xBtn2 }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="{{ $mBody2 }}">
            <div style="display:flex; gap:10px;">
                <div style="flex:1; background:#F0FDF4; border:1px solid #BBF7D0; border-radius:10px; padding:14px; text-align:center;">
                    <div style="font-size:26px; font-weight:800; color:#059669;">{{ $importResult['actualizados'] ?? 0 }}</div>
                    <div style="font-size:11px; font-weight:600; color:#065F46; margin-top:2px;">Actualizados</div>
                </div>
                <div style="flex:1; background:#EFF6FF; border:1px solid #BFDBFE; border-radius:10px; padding:14px; text-align:center;">
                    <div style="font-size:26px; font-weight:800; color:#1D4ED8;">{{ $importResult['creados'] ?? 0 }}</div>
                    <div style="font-size:11px; font-weight:600; color:#1E3A8A; margin-top:2px;">Creados</div>
                </div>
                <div style="flex:1; background:#FFF7ED; border:1px solid #FED7AA; border-radius:10px; padding:14px; text-align:center;">
                    <div style="font-size:26px; font-weight:800; color:#C2410C;">{{ count($importResult['errores'] ?? []) }}</div>
                    <div style="font-size:11px; font-weight:600; color:#9A3412; margin-top:2px;">Con errores</div>
                </div>
            </div>
            @if(!empty($importResult['errores']))
            <div style="border:1px solid #FED7AA; border-radius:8px; background:#FFF7ED; padding:10px 12px; max-height:140px; overflow-y:auto;">
                <p style="font-size:11px; font-weight:700; color:#9A3412; margin:0 0 6px; text-transform:uppercase; letter-spacing:.5px;">Filas con errores</p>
                @foreach($importResult['errores'] as $err)
                <span style="display:inline-block; font-family:monospace; font-size:11px; background:#FEE2E2; color:#B91C1C; border-radius:4px; padding:2px 6px; margin:2px;">{{ $err }}</span>
                @endforeach
            </div>
            @endif
        </div>
        <div style="{{ $mFoot2 }}">
            <button wire:click="$set('showImportResultModal', false)"
                    style="height:36px; padding:0 20px; border:none; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; font-size:13px; font-weight:700; cursor:pointer;">Listo</button>
        </div>
    </div>
</div>
</template>
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

</div>
