<div>

{{-- Flash success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35); display:flex; align-items:center; gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Flash error --}}
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#DC2626; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(220,38,38,.35); display:flex; align-items:center; gap:8px; max-width:340px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- ══════ PANEL NUEVO REGISTRO ══════ --}}
@if($showForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Nuevo Motivo de Cierre
        </span>
        <button wire:click="cancelar"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        @php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp

        <div style="flex:1; min-width:180px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Descripción *</label>
            <input wire:model="nombre" type="text" placeholder="Ej: Fallecimiento" style="width:100%; {{ $iS }}">
            @error('nombre')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="min-width:120px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Afecta</label>
            <select wire:model="afectaMora" style="{{ $iS }} width:100%; cursor:pointer;">
                <option value="0">No afecta</option>
                <option value="1">Sí afecta</option>
            </select>
        </div>

        <div style="min-width:110px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
            <select wire:model="activo" style="{{ $iS }} width:100%; cursor:pointer;">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; margin-bottom:5px;">·</label>
            <div style="display:flex; gap:8px;">
                <button wire:click="save" wire:loading.attr="disabled"
                        style="height:38px; padding:0 24px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <span wire:loading.remove wire:target="save">Guardar</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
                <button wire:click="cancelar"
                        style="height:38px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@php
$iRow = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;';
@endphp

{{-- ══════ TABLA ESCRITORIO ══════ --}}
<div class="hidden sm:block">
@if(!$showForm)
<div style="display:flex; justify-content:flex-start; margin-bottom:8px;">
    <button wire:click="create"
            style="height:32px; padding:0 14px; display:inline-flex; align-items:center; gap:5px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; font-size:12px; font-weight:700; color:#7B6FE8; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Motivo
    </button>
</div>
@endif
</div>
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Motivos de Cierre</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $registros->count() }}</span>
        @if($selectedMotivoId)
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editId === $selectedMotivoId)
                <button wire:click="save" wire:loading.attr="disabled"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span wire:loading.remove wire:target="save">Guardar</span>
                    <span wire:loading wire:target="save">...</span>
                </button>
                <button wire:click="cancelar"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="edit({{ $selectedMotivoId }})"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
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

    <div style="overflow:auto; flex:1;">
    @php
        $sortCols = ['Estado'=>'activo','Descripción'=>'nombre','Afecta indicadores'=>'afecta_mora'];
        $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
        $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; cursor:pointer;';
        $fW  = 'position:relative; margin-top:4px;' . ($editId ? ' opacity:0.45;' : '');
        $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
        $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
        $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
    @endphp
    <table style="table-layout:fixed; width:100%; border-collapse:collapse; min-width:480px;">
        <colgroup>
            <col style="width:50px;">
            <col style="width:130px;">
            <col>
            <col style="width:160px;">
        </colgroup>
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editId ? 'pointer-events:none;' : '' }}">

                {{-- # --}}
                <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; box-shadow:inset -1px 0 0 #E5E7EB;">
                    #
                    <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                        <input type="checkbox"
                               :checked="$wire.selectedMotivoId !== null"
                               :disabled="$wire.selectedMotivoId === null"
                               @click.prevent="$wire.selectedMotivoId !== null && $wire.set('selectedMotivoId', null)"
                               :style="$wire.selectedMotivoId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                    </div>
                </th>

                @foreach($sortCols as $label => $key)
                @php $isActive = $sortBy === $key; $isLastCol = $loop->last; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="{{ $thC }} text-align:center; cursor:pointer; {{ $isLastCol ? '' : 'box-shadow:inset -1px 0 0 #E5E7EB;' }} {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isActive?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isActive?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                        {{ $label }}
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isActive && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isActive && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    @if($key === 'nombre')
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                    @elseif($key === 'afecta_mora')
                    <div style="{{ $fW }}" @click.stop>
                        <select wire:model.live="colFilterAfecta" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="1">Sí afecta</option>
                            <option value="0">No afecta</option>
                        </select>
                    </div>
                    @else
                    <div style="{{ $fW }}" @click.stop>
                        <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    @endif
                </th>
                @endforeach

            </tr>
        </thead>
        <tbody>
        @forelse($registros as $r)

        @if($r->id === $editId)
        {{-- ── FILA EDICIÓN INLINE ── --}}
        <tr wire:key="mc-edit-{{ $r->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
            <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                <span style="font-size:13px; color:#111827;">{{ $loop->iteration }}</span>
            </td>
            <td style="padding:7px 10px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                <select wire:model="activo" style="{{ $iRow }} width:100%; cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </td>
            <td style="padding:7px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                <input wire:model="nombre" type="text"
                       style="width:100%; {{ $iRow }}">
                @error('nombre')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:7px 10px; text-align:center;">
                <select wire:model="afectaMora" style="{{ $iRow }} width:100%; cursor:pointer;">
                    <option value="0">No afecta</option>
                    <option value="1">Sí afecta</option>
                </select>
            </td>
        </tr>

        @else
        {{-- ── FILA NORMAL ── --}}
        @php $selM = $selectedMotivoId === $r->id; @endphp
        <tr wire:key="mc-{{ $r->id }}"
            style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $selM ? '#F5F3FF' : '' }}; {{ $selM ? 'border-left:3px solid #7B6FE8;' : '' }}"
            @mouseenter="$el.style.background='{{ $selM ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selM ? '#F5F3FF' : '' }}'">

            <td class="col-row-num" style="padding:6px 6px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                    <input type="checkbox"
                           :checked="$wire.selectedMotivoId === {{ $r->id }}"
                           @click="$wire.selectedMotivoId === {{ $r->id }} ? $wire.set('selectedMotivoId', null) : $wire.selectMotivo({{ $r->id }})"
                           :disabled="{{ $editId && $editId !== $r->id ? 'true' : 'false' }}"
                           style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editId && $editId !== $r->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                    <span style="font-size:13px; color:#111827;">{{ $loop->iteration }}</span>
                </div>
            </td>

            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                <span style="font-size:13px; font-weight:700; color:#374151; white-space:nowrap;">{{ $r->activo ? 'Activo' : 'Inactivo' }}</span>
            </td>

            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                <span style="font-size:13px; font-weight:400; color:#374151;">{{ ucwords(strtolower($r->nombre)) }}</span>
            </td>

            <td style="padding:10px 14px; text-align:center;">
                @if($r->afecta_mora)
                <span style="font-size:12px; font-weight:700; padding:3px 10px; border-radius:6px; background:#FEE2E2; color:#DC2626;">Sí afecta</span>
                @else
                <span style="font-size:12px; font-weight:700; padding:3px 10px; border-radius:6px; background:#F3F4F6; color:#9CA3AF;">No afecta</span>
                @endif
            </td>
        </tr>
        @endif

        @empty
        <tr><td colspan="4" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">{{ $colFilterNombre || $colFilterAfecta !== '' || $colFilterEstado !== '' ? 'Sin resultados para los filtros aplicados.' : 'Sin motivos configurados. Creá el primero.' }}</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══════ CARDS MOBILE ══════ --}}
<div class="sm:hidden">

    @if($registros->isEmpty())
    <p style="text-align:center; color:#9CA3AF; font-size:13px; padding:48px 0;">Sin motivos configurados. Creá el primero.</p>
    @endif

    @foreach($registros as $r)

    @if($r->id === $editId)
    {{-- CARD EDICIÓN --}}
    <div wire:key="mc-edit-mobile-{{ $r->id }}"
         style="background:#FAFAFE; border-radius:14px; border:1px solid #EDE9FE; margin-bottom:10px; padding:14px 16px; box-shadow:0 1px 4px rgba(123,111,232,.1);">

        <div style="margin-bottom:12px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Descripción *</label>
            <input wire:model="nombre" type="text"
                   style="width:100%; {{ $iRow }}">
            @error('nombre')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Afecta</label>
                <select wire:model="afectaMora" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="0">No afecta</option>
                    <option value="1">Sí afecta</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="activo" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <button wire:click="save" wire:loading.attr="disabled"
                    style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
            <button wire:click="cancelar"
                    style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>

    @else
    {{-- CARD NORMAL --}}
    <div wire:key="mc-mobile-{{ $r->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; margin-bottom:10px; box-shadow:0 1px 4px rgba(0,0,0,.05); padding:12px 14px;">

        {{-- Fila 1: avatar + nombre + estado --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; line-height:1;">{{ mb_substr($r->nombre, 0, 1) }}</span>
            </div>
            <span style="font-size:14px; font-weight:700; color:#111827; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->nombre }}</span>
            <span style="flex-shrink:0; padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600;
                         background:{{ $r->activo ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $r->activo ? '#059669' : '#9CA3AF' }};">
                {{ $r->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Fila 2: afecta mora + botón --}}
        <div style="display:flex; align-items:center; gap:8px; border-top:1px solid #F3F4F6; padding-top:10px;">
            @if($r->afecta_mora)
            <span style="font-size:11px; font-weight:700; padding:2px 10px; border-radius:99px; background:#FEE2E2; color:#DC2626;">Sí afecta mora</span>
            @else
            <span style="font-size:11px; font-weight:600; padding:2px 10px; border-radius:99px; background:#F3F4F6; color:#9CA3AF;">No afecta</span>
            @endif
            <button wire:click="edit({{ $r->id }})"
                    style="margin-left:auto; height:32px; padding:0 14px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:4px; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @endforeach
</div>

</div>
