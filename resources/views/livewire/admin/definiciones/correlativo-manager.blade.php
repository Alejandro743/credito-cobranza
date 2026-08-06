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
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column;">

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
    <div class="hidden sm:block" style="display:flex; flex-direction:column; max-height:calc(100vh - 180px);">
    <div style="overflow:auto; flex:1;">
        @if ($correlativos->isNotEmpty())
        @php
        $sortCols = ['Prefijo'=>'prefijo','Descripción'=>'descripcion','Sig. Número'=>'siguiente_numero','Longitud'=>'longitud','Estado'=>'activo'];
        @endphp
        <table style="table-layout:fixed; width:100%; min-width:680px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:44px;">
                <col style="width:180px;">
                <col style="width:240px;">
                <col style="width:120px;">
                <col style="width:100px;">
                <col style="width:100px;">
                <col style="width:110px;">
            </colgroup>
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                    @foreach($sortCols as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:{{ in_array($label,['Sig. Número','Longitud','Estado']) ? 'center' : 'left' }}; user-select:none; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    @endforeach
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($correlativos as $c)

                @if ($editingId === $c->id)
                @php $eS = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;'; @endphp
                <tr wire:key="edit-{{ $c->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; border-left:3px solid #7B6FE8;">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:13px; color:#111827; white-space:nowrap;">{{ $correlativos->firstItem() + $loop->index }}</td>
                    <td colspan="6" style="padding:10px 14px;">
                        <div style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                            <div style="width:100px;">
                                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Prefijo *</label>
                                <input wire:model="editPrefijo" type="text" maxlength="10"
                                       style="{{ $eS }} width:100%; font-family:monospace; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                                @error('editPrefijo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>
                            <div style="flex:1; min-width:160px;">
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
                <tr wire:key="row-{{ $c->id }}" style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:13px; color:#111827; white-space:nowrap;">{{ $correlativos->firstItem() + $loop->index }}</td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:13px; color:#111827; white-space:nowrap;">{{ $c->prefijo }}</span>
                            <span style="font-size:13px; color:#111827;">→</span>
                            <span style="font-family:monospace; font-size:12px; color:#7B6FE8; font-weight:600; background:#F0EEFF; padding:2px 7px; border-radius:6px; white-space:nowrap;">
                                {{ $c->prefijo }}{{ str_pad($c->siguiente_numero, $c->longitud, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->descripcion ? ucwords(strtolower($c->descripcion)) : '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:center; font-size:13px; color:#111827;">{{ $c->siguiente_numero }}</td>
                    <td style="padding:10px 14px; text-align:center; font-size:13px; color:#111827;">{{ $c->longitud }}</td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $c->activo ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $c->activo ? '#059669' : '#9CA3AF' }};">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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

    {{-- MOBILE --}}
    <div class="block sm:hidden">
        <div style="display:flex; flex-direction:column; gap:10px; padding:14px;">
            @foreach ($correlativos as $c)

            @if ($editingId === $c->id)
            {{-- Card edición mobile --}}
            @php $mS = 'width:100%; height:38px; border:1px solid #D8D3F8; border-radius:8px; padding:0 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;'; @endphp
            <div wire:key="medit-{{ $c->id }}" style="background:#F8F7FF; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; padding:14px; display:flex; flex-direction:column; gap:10px;">
                <p style="font-size:11px; font-weight:700; color:#7B6FE8; margin:0; text-transform:uppercase; letter-spacing:.5px;">Editando correlativo</p>

                {{-- Fila 1: Prefijo + Estado --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Prefijo *</label>
                        <input wire:model="editPrefijo" type="text" maxlength="10"
                               style="{{ $mS }} font-family:monospace; font-weight:700; text-transform:uppercase; letter-spacing:1px; text-align:center;">
                        @error('editPrefijo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                        <select wire:model="editActivo" style="{{ $mS }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                {{-- Fila 2: Descripción --}}
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Descripción</label>
                    <input wire:model="editDescripcion" type="text" maxlength="200" style="{{ $mS }}">
                    @error('editDescripcion') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                </div>

                {{-- Fila 3: Sig. Número + Longitud --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Sig. Número *</label>
                        <input wire:model="editSiguienteNumero" type="number" min="1"
                               style="{{ $mS }} text-align:center;">
                        @error('editSiguienteNumero') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Longitud *</label>
                        <input wire:model="editLongitud" type="number" min="1" max="10"
                               style="{{ $mS }} text-align:center;">
                        @error('editLongitud') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2" style="padding-top:4px; border-top:1px solid #EDE9FE;">
                    <button wire:click="saveEdit"
                            style="height:38px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">
                        Guardar
                    </button>
                    <button wire:click="cancelEdit"
                            style="height:38px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                        Cancelar
                    </button>
                </div>
            </div>

            @else
            {{-- Card normal mobile --}}
            <div wire:key="mrow-{{ $c->id }}" style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); padding:12px 14px;">

                {{-- Fila 1: avatar + prefijo + preview + estado --}}
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; line-height:1;">{{ mb_substr($c->prefijo, 0, 1) }}</span>
                    </div>
                    <span style="font-family:monospace; font-size:14px; font-weight:800; color:#111827;">{{ $c->prefijo }}</span>
                    <span style="font-family:monospace; font-size:11px; font-weight:700; color:#7B6FE8; background:#F0EEFF; padding:2px 7px; border-radius:6px; white-space:nowrap;">
                        {{ $c->prefijo }}{{ str_pad($c->siguiente_numero, $c->longitud, '0', STR_PAD_LEFT) }}
                    </span>
                    <span style="margin-left:auto; flex-shrink:0; padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600;
                                 background:{{ $c->activo ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $c->activo ? '#059669' : '#9CA3AF' }};">
                        {{ $c->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                {{-- Fila 2: descripción --}}
                @if ($c->descripcion)
                <p style="font-size:12px; color:#6B7280; margin:0 0 8px 38px;">{{ $c->descripcion }}</p>
                @endif

                {{-- Botón --}}
                <div style="border-top:1px solid #F3F4F6; padding-top:10px;">
                    <button wire:click="startEdit({{ $c->id }})"
                            style="width:100%; height:32px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                </div>
            </div>
            @endif

            @endforeach
        </div>
    </div>

    @if ($correlativos->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $correlativos->links() }}</div>
    @endif
    </div>{{-- end desktop block --}}
    @endif
</div>

</div>
