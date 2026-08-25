<div>

@php
$iS     = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$iRow   = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$lStyle = 'display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;';
@endphp

{{-- Toast success --}}
@if(session('success'))
<div wire:key="toast-success-{{ uniqid() }}" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#7B6FE8;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(123,111,232,.35);display:flex;align-items:center;gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
@if(!$showAddForm)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;justify-content:flex-start;">
    <button wire:click="showAdd"
            style="height:36px;padding:0 18px;background:#F8F7FF;color:#7B6FE8;border:1px solid #EDE9FE;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap;transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Cliente
    </button>
</div>
@endif

{{-- Panel Alta --}}
@if($showAddForm)

{{-- Desktop --}}
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #EDE9FE;box-shadow:0 2px 12px rgba(123,111,232,.12);margin-bottom:20px;overflow:hidden;">
    <div style="background:#F8F7FF;border-bottom:1px solid #EDE9FE;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:800;color:#7B6FE8;display:flex;align-items:center;gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Nuevo Cliente
        </span>
        <button wire:click="cancelAdd"
                style="width:30px;height:30px;border:1px solid #EDE9FE;background:#fff;color:#9CA3AF;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">CI * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(usuario)</span></label>
            <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678" style="width:100%;{{ $iS }}">
            @error('newCi') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:150px;">
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="newNombre" type="text" maxlength="120" style="width:100%;{{ $iS }}">
            @error('newNombre') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:150px;">
            <label style="{{ $lStyle }}">Apellido *</label>
            <input wire:model="newApellido" type="text" maxlength="120" style="width:100%;{{ $iS }}">
            @error('newApellido') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">Teléfono * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(contraseña)</span></label>
            <input wire:model="newTelefono" type="text" maxlength="30" style="width:100%;{{ $iS }}">
            @error('newTelefono') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">NIT</label>
            <input wire:model="newNit" type="text" maxlength="30" style="width:100%;{{ $iS }}">
        </div>
        <div style="min-width:170px;">
            <label style="{{ $lStyle }}">Correo</label>
            <input wire:model="newCorreo" type="email" maxlength="191" style="width:100%;{{ $iS }}">
            @error('newCorreo') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:160px;">
            <label style="{{ $lStyle }}">Vendedor</label>
            <select wire:model="newVendedorId" style="width:100%;{{ $iS }} cursor:pointer;">
                <option value="">Sin asignar</option>
                @foreach ($vendedores as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Ubicación --}}
    <div style="padding:0 20px 16px;">
        <div style="border:1px solid #EDE9FE;border-radius:10px;padding:14px 16px;background:#FAFAFE;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Ubicación</span>
                <div style="flex:1; height:1px; background:#EDE9FE;"></div>
            </div>
            <div style="display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
                <div style="min-width:160px;">
                    <label style="{{ $lStyle }}">Ciudad *</label>
                    <select wire:model.live="newCiudad" style="width:100%;{{ $iS }} cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        @foreach($ciudadesAll as $c)
                        <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('newCiudad') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="min-width:160px;">
                    <label style="{{ $lStyle }}">Provincia *</label>
                    <select wire:model.live="newProvincia" style="width:100%;{{ $iS }} cursor:pointer;" @disabled(!$newCiudad)>
                        <option value="">— Seleccionar —</option>
                        @foreach($newProvincias as $p)
                        <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    @error('newProvincia') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="min-width:160px;">
                    <label style="{{ $lStyle }}">Municipio *</label>
                    <select wire:model.live="newMunicipio" style="width:100%;{{ $iS }} cursor:pointer;" @disabled(!$newProvincia)>
                        <option value="">— Seleccionar —</option>
                        @foreach($newMunicipios as $m)
                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                    @error('newMunicipio') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="flex:1; min-width:220px;">
                    <label style="{{ $lStyle }}">Dirección *</label>
                    <input wire:model="newDireccion" type="text" maxlength="255" style="width:100%;{{ $iS }}">
                    @error('newDireccion') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div style="padding:0 20px 20px;display:flex;gap:8px;">
        <button wire:click="saveNew" wire:loading.attr="disabled"
                style="height:38px;padding:0 24px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="height:38px;padding:0 18px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
            Cancelar
        </button>
    </div>
</div>

{{-- Móvil --}}
<div class="sm:hidden" style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;margin-bottom:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">CI * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(usuario)</span></label>
        <input wire:model="newCi" type="text" maxlength="20" style="width:100%;{{ $iS }}">
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
            <label style="{{ $lStyle }}">Teléfono *</label>
            <input wire:model="newTelefono" type="text" style="width:100%;{{ $iS }}">
            @error('newTelefono') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="{{ $lStyle }}">NIT</label>
            <input wire:model="newNit" type="text" style="width:100%;{{ $iS }}">
        </div>
    </div>
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">Correo</label>
        <input wire:model="newCorreo" type="email" style="width:100%;{{ $iS }}">
        @error('newCorreo') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
    </div>
    <div style="margin-bottom:12px;">
        <label style="{{ $lStyle }}">Vendedor</label>
        <select wire:model="newVendedorId" style="width:100%;{{ $iS }} cursor:pointer;">
            <option value="">Sin asignar</option>
            @foreach ($vendedores as $v)
                <option value="{{ $v->id }}">{{ $v->name }}</option>
            @endforeach
        </select>
    </div>

    <div style="border:1px solid #EDE9FE;border-radius:10px;padding:12px;background:#fff;margin-bottom:12px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
            <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Ubicación</span>
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <div>
                <label style="{{ $lStyle }}">Ciudad *</label>
                <select wire:model.live="newCiudad" style="width:100%;{{ $iS }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach($ciudadesAll as $c)
                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('newCiudad') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Provincia *</label>
                <select wire:model.live="newProvincia" style="width:100%;{{ $iS }} cursor:pointer;" @disabled(!$newCiudad)>
                    <option value="">— Seleccionar —</option>
                    @foreach($newProvincias as $p)
                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('newProvincia') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Municipio *</label>
                <select wire:model.live="newMunicipio" style="width:100%;{{ $iS }} cursor:pointer;" @disabled(!$newProvincia)>
                    <option value="">— Seleccionar —</option>
                    @foreach($newMunicipios as $m)
                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
                @error('newMunicipio') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Dirección *</label>
                <input wire:model="newDireccion" type="text" maxlength="255" style="width:100%;{{ $iS }}">
                @error('newDireccion') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div style="display:flex;gap:8px;">
        <button wire:click="saveNew" wire:loading.attr="disabled"
                style="flex:1;height:36px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="flex:1;height:36px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
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
        <span style="font-size:13px;font-weight:700;color:#111827;">Clientes registrados</span>
        <span style="background:#EDE9FE;color:#7B6FE8;font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;">{{ $clientes->total() }}</span>
        @if($selectedClienteId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedClienteId)
                <button wire:click="saveEdit" wire:loading.attr="disabled"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedClienteId }})"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
                <button wire:click="openViewModal({{ $selectedClienteId }})"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Ver
                </button>
            @endif
        </div>
        @endif

        <button type="button" wire:click="refrescarGrilla"
                style="margin-left:auto; height:28px; padding:0 10px; border:1px solid #EDE9FE; border-radius:7px; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:inline-flex; align-items:center; gap:5px; flex-shrink:0; font-size:11px; font-weight:700; white-space:nowrap; transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';"
                onclick="const ic=this.querySelector('svg'); const deg=(parseInt(ic.dataset.deg||'0')+360); ic.dataset.deg=deg; ic.style.transition='transform .5s ease'; ic.style.transform='rotate('+deg+'deg)';">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
            Actualizar
        </button>
    </div>

    <div style="overflow:auto;flex:1;">
        @if ($clientes->isEmpty())
        <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay clientes registrados.</p>
        @else
        <table style="table-layout:fixed; width:100%;border-collapse:collapse;min-width:2100px;">
            <colgroup>
                <col style="width:50px;">   {{-- # --}}
                <col style="width:160px;">  {{-- Estado --}}
                <col style="width:170px;">  {{-- Vendedor --}}
                <col style="width:110px;">  {{-- ID_LN --}}
                <col style="width:120px;">  {{-- CI --}}
                <col style="width:150px;">  {{-- Nombre --}}
                <col style="width:150px;">  {{-- Apellido --}}
                <col style="width:130px;">  {{-- Teléfono --}}
                <col style="width:110px;">  {{-- NIT --}}
                <col style="width:180px;">  {{-- Correo --}}
                <col style="width:140px;">  {{-- Ciudad --}}
                <col style="width:140px;">  {{-- Provincia --}}
                <col style="width:140px;">  {{-- Municipio --}}
                <col style="width:220px;">  {{-- Dirección --}}
                <col style="width:150px;">  {{-- Fecha Registro --}}
            </colgroup>
            <thead style="position:sticky;top:0;z-index:10;">
                <tr style="background:#F9F8FF;border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF; box-shadow:inset -1px 0 0 #E5E7EB;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedClienteId !== null"
                                   :disabled="$wire.selectedClienteId === null || $wire.editingId !== null"
                                   @click.prevent="$wire.selectedClienteId !== null && $wire.editingId === null && $wire.set('selectedClienteId', null)"
                                   :style="($wire.selectedClienteId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'active'; @endphp
                    <th wire:click="toggleSort('active')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:150px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
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

                    {{-- Vendedor --}}
                    @php $isA = $sortBy === 'vendedor'; @endphp
                    <th wire:click="toggleSort('vendedor')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:170px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Vendedor
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterVendedor" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- ID_LN --}}
                    @php $isA = $sortBy === 'id_ln'; @endphp
                    <th wire:click="toggleSort('id_ln')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">ID_LN
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterIdLn" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- CI --}}
                    @php $isA = $sortBy === 'ci'; @endphp
                    <th wire:click="toggleSort('ci')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:120px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">CI
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCi" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'nombre'; @endphp
                    <th wire:click="toggleSort('nombre')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:150px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Apellido --}}
                    @php $isA = $sortBy === 'apellido'; @endphp
                    <th wire:click="toggleSort('apellido')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:150px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Apellido
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterApellido" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Teléfono --}}
                    @php $isA = $sortBy === 'telefono'; @endphp
                    <th wire:click="toggleSort('telefono')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Teléfono
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterTelefono" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- NIT --}}
                    @php $isA = $sortBy === 'nit'; @endphp
                    <th wire:click="toggleSort('nit')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">NIT
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNit" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Correo --}}
                    @php $isA = $sortBy === 'correo'; @endphp
                    <th wire:click="toggleSort('correo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:180px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Correo
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCorreo" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Ciudad --}}
                    @php $isA = $sortBy === 'ciudad'; @endphp
                    <th wire:click="toggleSort('ciudad')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:140px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Ciudad
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiudad" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Provincia --}}
                    @php $isA = $sortBy === 'provincia'; @endphp
                    <th wire:click="toggleSort('provincia')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:140px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Provincia
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterProvincia" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Municipio --}}
                    @php $isA = $sortBy === 'municipio'; @endphp
                    <th wire:click="toggleSort('municipio')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:140px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Municipio
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterMunicipio" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Dirección --}}
                    @php $isA = $sortBy === 'direccion'; @endphp
                    <th wire:click="toggleSort('direccion')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:200px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Dirección
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterDireccion" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Fecha Registro --}}
                    @php $isA = $sortBy === 'created_at'; @endphp
                    <th wire:click="toggleSort('created_at')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:150px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Fecha Registro
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)

                {{-- Fila edición inline --}}
                @if ($editingId === $c->id)
                <tr wire:key="edit-{{ $c->id }}" style="background:#FAFAFE;border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#FAFAFE; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:13px; color:#111827;">{{ $clientes->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model="editActive" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model="editVendedorId" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="">Sin asignar</option>
                            @foreach ($vendedores as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:12px;font-family:monospace;font-weight:700;color:#7B6FE8;white-space:nowrap;">{{ $c->id_ln ?? '—' }}</span></td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editCi" type="text" maxlength="20" style="width:100%;{{ $iRow }}">
                        @error('editCi') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
                        @error('editNombre') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editApellido" type="text" style="width:100%;{{ $iRow }}">
                        @error('editApellido') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editTelefono" type="text" style="width:100%;{{ $iRow }}">
                        @error('editTelefono') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editNit" type="text" style="width:100%;{{ $iRow }}">
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editCorreo" type="email" style="width:100%;{{ $iRow }}">
                        @error('editCorreo') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model.live="editCiudad" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="">— Ciudad —</option>
                            @foreach($ciudadesAll as $ciudad)
                            <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editCiudad') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model.live="editProvincia" style="width:100%;{{ $iRow }} cursor:pointer;" @disabled(!$editCiudad)>
                            <option value="">— Provincia —</option>
                            @foreach($editProvincias as $prov)
                            <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editProvincia') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model.live="editMunicipio" style="width:100%;{{ $iRow }} cursor:pointer;" @disabled(!$editProvincia)>
                            <option value="">— Municipio —</option>
                            @foreach($editMunicipios as $mun)
                            <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editMunicipio') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editDireccion" type="text" maxlength="255" style="width:100%;{{ $iRow }}">
                        @error('editDireccion') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->created_at?->format('d/m/Y H:i') ?? '—' }}</span></td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selC = $selectedClienteId === $c->id; @endphp
                <tr wire:key="c-{{ $c->id }}"
                    style="border-bottom:1px solid #F3F4F6;transition:background .1s; background:{{ $selC ? '#F5F3FF' : '' }}; {{ $selC ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selC ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selC ? '#F5F3FF' : '' }}'">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selC ? '#F5F3FF' : '#fff' }}; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedClienteId === {{ $c->id }}"
                                   @click="$wire.selectedClienteId === {{ $c->id }} ? $wire.set('selectedClienteId', null) : $wire.selectCliente({{ $c->id }})"
                                   :disabled="{{ $editingId && $editingId !== $c->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $c->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:13px; color:#111827;">{{ $clientes->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:13px; font-weight:700; color:#374151; white-space:nowrap;">{{ $c->active ? 'Activo' : 'Inactivo' }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->vendedorUsuario->name ?? '—')) }}</span></td>
                    <td style="padding:10px 14px; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->id_ln ?? '—' }}</span></td>
                    <td style="padding:10px 14px; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->ci }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->usuario->name ?? '—')) }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->apellido ?? '—')) }}</span></td>
                    <td style="padding:10px 14px; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->telefono }}</span></td>
                    <td style="padding:10px 14px; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->nit ?: '—' }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ $c->correo ?: '—' }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->ciudad)) }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->provincia)) }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ ucwords(strtolower($c->municipio)) }}</span></td>
                    <td style="padding:10px 14px; overflow:hidden; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">{{ $c->direccion ?: '—' }}</span></td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;font-weight:400;color:#374151;white-space:nowrap;">{{ $c->created_at?->format('d/m/Y H:i') ?? '—' }}</span></td>
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

{{-- Tarjetas móvil --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($clientes as $c)

    @if($editingId === $c->id)
    {{-- Card edición --}}
    <div wire:key="card-edit-{{ $c->id }}"
         style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;border-left:3px solid #7B6FE8;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">CI *</label>
                <input wire:model="editCi" type="text" maxlength="20" style="width:100%;{{ $iRow }}">
                @error('editCi')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Nombre *</label>
                <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
                @error('editNombre')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">Apellido *</label>
                <input wire:model="editApellido" type="text" style="width:100%;{{ $iRow }}">
                @error('editApellido')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Teléfono *</label>
                <input wire:model="editTelefono" type="text" style="width:100%;{{ $iRow }}">
                @error('editTelefono')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">NIT</label>
                <input wire:model="editNit" type="text" style="width:100%;{{ $iRow }}">
            </div>
            <div>
                <label style="{{ $lStyle }}">Correo</label>
                <input wire:model="editCorreo" type="email" style="width:100%;{{ $iRow }}">
                @error('editCorreo')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">Ciudad *</label>
                <select wire:model.live="editCiudad" style="width:100%;{{ $iRow }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach($ciudadesAll as $ciudad)
                    <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                    @endforeach
                </select>
                @error('editCiudad')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Provincia *</label>
                <select wire:model.live="editProvincia" style="width:100%;{{ $iRow }} cursor:pointer;" @disabled(!$editCiudad)>
                    <option value="">— Seleccionar —</option>
                    @foreach($editProvincias as $prov)
                    <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                    @endforeach
                </select>
                @error('editProvincia')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="{{ $lStyle }}">Municipio *</label>
                <select wire:model.live="editMunicipio" style="width:100%;{{ $iRow }} cursor:pointer;" @disabled(!$editProvincia)>
                    <option value="">— Seleccionar —</option>
                    @foreach($editMunicipios as $mun)
                    <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                    @endforeach
                </select>
                @error('editMunicipio')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="{{ $lStyle }}">Estado</label>
                <select wire:model="editActive" style="width:100%;{{ $iRow }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>
        <div style="margin-bottom:10px;">
            <label style="{{ $lStyle }}">Dirección *</label>
            <input wire:model="editDireccion" type="text" maxlength="255" style="width:100%;{{ $iRow }}">
            @error('editDireccion')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
        </div>
        <div style="margin-bottom:12px;">
            <label style="{{ $lStyle }}">Vendedor</label>
            <select wire:model="editVendedorId" style="width:100%;{{ $iRow }} cursor:pointer;">
                <option value="">Sin asignar</option>
                @foreach ($vendedores as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; gap:8px;">
            <button wire:click="saveEdit" wire:loading.attr="disabled" style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                <span wire:loading wire:target="saveEdit">Guardando...</span>
            </button>
            <button wire:click="cancelEdit" style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cerrar</button>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $c->id }}"
         style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;">

        <div style="background:#F8F7FF;padding:10px 14px;display:flex;align-items:center;gap:8px;border-bottom:1px solid #EDE9FE;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(mb_substr($c->usuario->name ?? $c->ci, 0, 1)) }}</span>
            </div>
            <div style="flex:1;min-width:0;">
                <span style="font-size:13px;font-weight:700;color:#111827;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ ucwords(strtolower(($c->usuario->name ?? '—').' '.$c->apellido)) }}</span>
                <span style="font-size:11px;color:#7B6FE8;font-family:monospace;">CI {{ $c->ci }}</span>
            </div>
            @if($c->active)
            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;background:#D1FAE5;color:#059669;flex-shrink:0;">Activo</span>
            @else
            <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;background:#F3F4F6;color:#9CA3AF;flex-shrink:0;">Inactivo</span>
            @endif
        </div>

        <div style="padding:12px 14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Teléfono</span>
                    <span style="font-size:13px;color:#111827;">{{ $c->telefono ?? '—' }}</span>
                </div>
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Ciudad</span>
                    <span style="font-size:13px;color:#111827;">{{ ucwords(strtolower($c->ciudad)) }}</span>
                </div>
            </div>
            @if($c->vendedorUsuario)
            <div style="margin-top:10px;">
                <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Vendedor</span>
                <span style="font-size:13px;color:#111827;">{{ $c->vendedorUsuario->name }}</span>
            </div>
            @endif
            <div style="margin-top:10px;">
                <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">ID_LN</span>
                <span style="font-size:13px;color:#111827;font-family:monospace;">{{ $c->id_ln ?? '—' }}</span>
            </div>
        </div>

        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button wire:click="openViewModal({{ $c->id }})"
                    style="flex:1; height:34px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
            <button wire:click="startEdit({{ $c->id }})"
                    style="flex:1; height:34px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay clientes registrados.</p>
    @endforelse
    @if ($clientes->hasPages())
    <div style="margin-top:4px;">{{ $clientes->links() }}</div>
    @endif
</div>

{{-- ══ MODAL: Ver cliente (mismo estándar del popup "Datos del Cliente" de Mis Clientes / Revisión del Crédito) ══ --}}
@if ($viewingClienteId)
@php $vc = \App\Models\Cliente::with(['usuario','vendedorUsuario'])->find($viewingClienteId); @endphp
@if ($vc)
@php
$vField = 'background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:9px 12px; font-size:13px; font-weight:600; color:#111827; min-height:38px; display:flex; align-items:center;';
$vLabel = 'font-size:10px; font-weight:700; letter-spacing:0.06em; color:#7B6FE8; margin:0 0 4px 0;';
$vSec   = 'display:flex; align-items:center; gap:7px; margin-bottom:12px;';
@endphp
<div class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);"
     wire:click.self="closeViewModal">
    <div style="background:#fff; border-radius:8px; width:100%; max-width:460px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22);">

        {{-- Header --}}
        <div style="padding:16px 20px; border-bottom:1px solid #F0EEFF; display:flex; align-items:center; gap:9px; flex-shrink:0;">
            <div style="width:30px; height:30px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <p style="font-size:17px; font-weight:700; color:#6B7280; margin:0; letter-spacing:-0.2px; flex:1;">Datos del Cliente</p>
            <button wire:click="closeViewModal"
                    style="width:32px; height:32px; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#7B6FE8; transition:background .15s, color .15s;"
                    @mouseenter="$el.style.background='#7B6FE8'; $el.style.color='#fff';"
                    @mouseleave="$el.style.background='#F8F7FF'; $el.style.color='#7B6FE8';">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="overflow:auto; flex:1; padding:16px 20px; display:flex; flex-direction:column; gap:12px;">
            <div>
                <div style="{{ $vSec }}">
                    <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Datos Personales</span>
                    <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                </div>
                <div style="margin-bottom:10px;"><p style="{{ $vLabel }}">Nombre</p><div style="{{ $vField }}">{{ $vc->usuario->name ?? '—' }} {{ $vc->apellido }}</div></div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div><p style="{{ $vLabel }}">ID_LN</p><div style="{{ $vField }} font-family:monospace;">{{ $vc->id_ln ?: '—' }}</div></div>
                    <div><p style="{{ $vLabel }}">CI</p><div style="{{ $vField }} font-family:monospace;">{{ $vc->ci ?: '—' }}</div></div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div><p style="{{ $vLabel }}">Teléfono</p><div style="{{ $vField }}">{{ $vc->telefono ?: '—' }}</div></div>
                    <div><p style="{{ $vLabel }}">NIT</p><div style="{{ $vField }}">{{ $vc->nit ?: '—' }}</div></div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div><p style="{{ $vLabel }}">Vendedor</p><div style="{{ $vField }}">{{ $vc->vendedorUsuario?->name ?: '—' }}</div></div>
                    <div><p style="{{ $vLabel }}">Estado</p><div style="{{ $vField }}">{{ $vc->active ? 'Activo' : 'Inactivo' }}</div></div>
                </div>
                <div><p style="{{ $vLabel }}">Correo</p><div style="{{ $vField }} word-break:break-all; align-items:flex-start; padding-top:9px;">{{ $vc->correo ?: '—' }}</div></div>
            </div>
            <div>
                <div style="{{ $vSec }}">
                    <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección</span>
                    <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div><p style="{{ $vLabel }}">Ciudad</p><div style="{{ $vField }}">{{ $vc->ciudad ?: '—' }}</div></div>
                    <div><p style="{{ $vLabel }}">Provincia</p><div style="{{ $vField }}">{{ $vc->provincia ?: '—' }}</div></div>
                    <div><p style="{{ $vLabel }}">Municipio</p><div style="{{ $vField }}">{{ $vc->municipio ?: '—' }}</div></div>
                </div>
                <div><p style="{{ $vLabel }}">Dirección</p><div style="{{ $vField }}">{{ $vc->direccion ?: '—' }}</div></div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; flex-shrink:0;">
            <button wire:click="closeViewModal"
                    style="width:100%; padding:10px; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:10px; font-size:13px; font-weight:700; color:#7B6FE8; cursor:pointer; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Cerrar
            </button>
        </div>

    </div>
</div>
@endif
@endif

</div>
