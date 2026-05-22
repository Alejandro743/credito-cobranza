<div>

{{-- Flash success --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35);">
    {{ session('success') }}
</div>
@endif

{{-- Flash error --}}
@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#EF4444; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(239,68,68,.35);">
    {{ session('error') }}
</div>
@endif

{{-- Toolbar --}}
<div style="display:flex; align-items:center; justify-content:flex-end; margin-bottom:16px;">
    @if (!$showAddForm && !$editingId)
    <button wire:click="showAdd"
            style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Correlativo
    </button>
    @endif
</div>

{{-- Formulario Nuevo Correlativo --}}
@if ($showAddForm)
@php $iS = 'width:100%; height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    {{-- Header --}}
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
            Nuevo Correlativo
        </span>
        <button wire:click="cancelAdd" type="button" style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; border:1px solid #EDE9FE; border-radius:8px; background:#fff; cursor:pointer; color:#9CA3AF;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    {{-- Body --}}
    <div style="padding:16px 18px; display:flex; flex-direction:column; gap:12px;">
        <div style="display:flex; flex-wrap:wrap; gap:12px;">
            <div style="width:110px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;">Prefijo *</label>
                <input wire:model="newPrefijo" type="text" maxlength="10" placeholder="LN"
                       style="{{ $iS }} font-family:monospace; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                @error('newPrefijo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:130px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;">Sig. Número *</label>
                <input wire:model="newSiguienteNumero" type="number" min="1"
                       style="{{ $iS }} text-align:center;">
                @error('newSiguienteNumero') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:120px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;">Longitud dígitos *</label>
                <input wire:model="newLongitud" type="number" min="1" max="10"
                       style="{{ $iS }} text-align:center;">
                @error('newLongitud') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:90px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;">Estado</label>
                <select wire:model="newActivo" style="{{ $iS }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div style="flex:1; min-width:200px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;">Descripción</label>
                <input wire:model="newDescripcion" type="text" maxlength="200" placeholder="Ej: Correlativo para clientes nuevos"
                       style="{{ $iS }}">
                @error('newDescripcion') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
            <button wire:click="saveNew"
                    style="height:36px; padding:0 20px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:36px; padding:0 16px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- Tabla --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra tabla --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Correlativos registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $correlativos->total() }}</span>
        </div>
    </div>

    @if ($correlativos->isEmpty() && !$showAddForm)
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay correlativos registrados.</p>
    @else

    {{-- DESKTOP --}}
    <div class="hidden sm:block" style="overflow-x:auto;">
        <table class="corr-table" style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:left;">Prefijo</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:left;">Descripción</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:center;">Sig. Número</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:center;">Longitud</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:center;">Estado</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($correlativos as $c)

                @if ($editingId === $c->id)
                @php $eS = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;'; @endphp
                <tr wire:key="edit-{{ $c->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; border-left:3px solid #7B6FE8;">
                    <td colspan="6" style="padding:14px 18px;">
                        <div style="display:flex; gap:10px; align-items:flex-end; overflow-x:auto;">
                            <div style="width:100px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Prefijo *</label>
                                <input wire:model="editPrefijo" type="text" maxlength="10"
                                       style="{{ $eS }} width:100%; font-family:monospace; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                                @error('editPrefijo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>
                            <div style="flex:1; min-width:180px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Descripción</label>
                                <input wire:model="editDescripcion" type="text" maxlength="200"
                                       style="{{ $eS }} width:100%;">
                                @error('editDescripcion') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>
                            <div style="width:110px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Sig. Número *</label>
                                <input wire:model="editSiguienteNumero" type="number" min="1"
                                       style="{{ $eS }} width:100%; text-align:center;">
                                @error('editSiguienteNumero') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>
                            <div style="width:90px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Longitud *</label>
                                <input wire:model="editLongitud" type="number" min="1" max="10"
                                       style="{{ $eS }} width:100%; text-align:center;">
                                @error('editLongitud') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>
                            <div style="width:80px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                                <select wire:model="editActivo" style="{{ $eS }} width:100%; cursor:pointer;">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div style="display:flex; gap:6px;">
                                <button wire:click="saveEdit"
                                        style="height:30px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap;"
                                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                                    Guardar
                                </button>
                                <button wire:click="cancelEdit"
                                        style="height:30px; padding:0 10px; background:#F3F4F6; color:#6B7280; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer;"
                                        @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @else
                <tr wire:key="row-{{ $c->id }}" style="border-bottom:1px solid #F9FAFB;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td style="padding:11px 16px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-family:monospace; font-size:13px; font-weight:700; color:#111827; letter-spacing:.5px;">{{ $c->prefijo }}</span>
                            <span style="font-size:11px; color:#9CA3AF;">→</span>
                            <span style="font-family:monospace; font-size:12px; color:#7B6FE8; font-weight:600; background:#F0EEFF; padding:2px 7px; border-radius:6px;">
                                {{ $c->prefijo }}{{ str_pad($c->siguiente_numero, $c->longitud, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </td>
                    <td style="padding:11px 16px; font-size:13px; color:#6B7280;">{{ $c->descripcion ?? '—' }}</td>
                    <td style="padding:11px 16px; text-align:center; font-family:monospace; font-size:13px; font-weight:600; color:#374151;">{{ $c->siguiente_numero }}</td>
                    <td style="padding:11px 16px; text-align:center; font-size:13px; color:#6B7280;">{{ $c->longitud }}</td>
                    <td style="padding:11px 16px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600;
                                     background:{{ $c->activo ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $c->activo ? '#059669' : '#9CA3AF' }};">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="padding:11px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $c->id }})" wire:confirm="¿Eliminar este correlativo?" title="Eliminar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #FEE2E2; background:#FEF2F2; color:#EF4444; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.opacity='.7'" @mouseleave="$el.style.opacity='1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MOBILE --}}
    <div class="block sm:hidden">
        <div style="display:flex; flex-direction:column; gap:10px; padding:14px;">
            @foreach ($correlativos as $c)

            @if ($editingId === $c->id)
            {{-- Card edición mobile --}}
            @php $mS = 'width:100%; height:38px; border:1px solid #D8D3F8; border-radius:8px; padding:0 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;'; @endphp
            <div wire:key="medit-{{ $c->id }}" style="background:#F8F7FF; border-radius:12px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; padding:14px; display:flex; flex-direction:column; gap:12px;">
                <p style="font-size:11px; font-weight:700; color:#7B6FE8; margin:0; text-transform:uppercase; letter-spacing:.5px;">Editando correlativo</p>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Prefijo *</label>
                        <input wire:model="editPrefijo" type="text" maxlength="10"
                               style="{{ $mS }} font-family:monospace; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                        @error('editPrefijo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Descripción</label>
                        <input wire:model="editDescripcion" type="text" maxlength="200"
                               style="{{ $mS }}">
                        @error('editDescripcion') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;">
                            <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Sig. Número *</label>
                            <input wire:model="editSiguienteNumero" type="number" min="1"
                                   style="{{ $mS }} text-align:center;">
                            @error('editSiguienteNumero') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Longitud *</label>
                            <input wire:model="editLongitud" type="number" min="1" max="10"
                                   style="{{ $mS }} text-align:center;">
                            @error('editLongitud') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                        <select wire:model="editActivo" style="{{ $mS }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #EDE9FE;">
                    <button wire:click="saveEdit"
                            style="flex:1; height:38px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">
                        Guardar
                    </button>
                    <button wire:click="cancelEdit"
                            style="flex:1; height:38px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                        Cancelar
                    </button>
                </div>
            </div>

            @else
            {{-- Card normal mobile --}}
            <div wire:key="mrow-{{ $c->id }}" style="background:#fff; border-radius:12px; border:1px solid #E5E7EB; padding:14px; display:flex; flex-direction:column; gap:10px;">
                {{-- Header card --}}
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <span style="font-family:monospace; font-size:15px; font-weight:800; color:#111827; letter-spacing:.5px;">{{ $c->prefijo }}</span>
                        <span style="font-size:11px; color:#9CA3AF;">→</span>
                        <span style="font-family:monospace; font-size:13px; color:#7B6FE8; font-weight:700; background:#F0EEFF; padding:3px 8px; border-radius:6px;">
                            {{ $c->prefijo }}{{ str_pad($c->siguiente_numero, $c->longitud, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600;
                                 background:{{ $c->activo ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $c->activo ? '#059669' : '#9CA3AF' }};">
                        {{ $c->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                {{-- Descripción --}}
                @if ($c->descripcion)
                <p style="font-size:12px; color:#6B7280; margin:0;">{{ $c->descripcion }}</p>
                @endif
                {{-- Datos --}}
                <div style="display:flex; gap:16px; border-top:1px solid #F3F4F6; padding-top:10px;">
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 2px;">Sig. Número</p>
                        <p style="font-family:monospace; font-size:13px; font-weight:700; color:#374151; margin:0;">{{ $c->siguiente_numero }}</p>
                    </div>
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 2px;">Longitud</p>
                        <p style="font-size:13px; color:#6B7280; margin:0;">{{ $c->longitud }} dígitos</p>
                    </div>
                </div>
                {{-- Acciones --}}
                <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
                    <button wire:click="startEdit({{ $c->id }})"
                            style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                    <button wire:click="delete({{ $c->id }})" wire:confirm="¿Eliminar este correlativo?"
                            style="width:36px; height:36px; border:1px solid #FEE2E2; background:#FEF2F2; color:#EF4444; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endif

            @endforeach
        </div>
    </div>

    @if ($correlativos->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $correlativos->links() }}</div>
    @endif
    @endif
</div>

</div>
