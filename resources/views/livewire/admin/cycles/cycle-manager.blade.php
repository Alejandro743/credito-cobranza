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

{{-- ══════ TOOLBAR ══════ --}}
@if(!$showAddForm)
<div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap; justify-content:flex-start;">

    <button wire:click="showAdd"
            style="height:36px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Ciclo
    </button>
</div>
@endif

{{-- ══════ PANEL NUEVO REGISTRO ══════ --}}
@if($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp

{{-- PANEL NUEVO — ESCRITORIO --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Nuevo Ciclo Comercial
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div style="min-width:120px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
            <input wire:model="newCode" type="text" maxlength="30" placeholder="CIC-202601"
                   style="width:100%; {{ $iS }} text-transform:uppercase;">
            @error('newCode')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="flex:1; min-width:180px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Descripción *</label>
            <input wire:model="newName" type="text" placeholder="Ciclo Enero 2026"
                   style="width:100%; {{ $iS }}">
            @error('newName')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="width:148px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Fecha inicio *</label>
            <input wire:model="newStartDate" type="date" style="width:100%; {{ $iS }}">
            @error('newStartDate')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="width:148px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Fecha fin *</label>
            <input wire:model="newEndDate" type="date" style="width:100%; {{ $iS }}">
            @error('newEndDate')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="min-width:120px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado *</label>
            <select wire:model="newStatus" style="width:100%; {{ $iS }} cursor:pointer;">
                <option value="abierto">Abierto</option>
                <option value="cerrado">Cerrado</option>
            </select>
            @error('newStatus')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; margin-bottom:5px;">·</label>
            <div style="display:flex; gap:8px;">
                <button wire:click="saveNew" wire:loading.attr="disabled"
                        style="height:38px; padding:0 24px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <span wire:loading.remove wire:target="saveNew">Guardar</span>
                    <span wire:loading wire:target="saveNew">Guardando...</span>
                </button>
                <button wire:click="cancelAdd"
                        style="height:38px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- PANEL NUEVO — MOBILE --}}
<div class="sm:hidden" style="background:#FAFAFE; border-radius:14px; border:1px solid #EDE9FE; margin-bottom:16px; padding:14px 16px; box-shadow:0 1px 4px rgba(123,111,232,.1);">

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
            <input wire:model="newCode" type="text" maxlength="30" placeholder="CIC-202601"
                   style="width:100%; {{ $iS }} text-transform:uppercase;">
            @error('newCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado *</label>
            <select wire:model="newStatus" style="width:100%; {{ $iS }} cursor:pointer;">
                <option value="abierto">Abierto</option>
                <option value="cerrado">Cerrado</option>
            </select>
        </div>
    </div>

    <div style="margin-bottom:10px;">
        <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Descripción *</label>
        <input wire:model="newName" type="text" placeholder="Ciclo Enero 2026"
               style="width:100%; {{ $iS }}">
        @error('newName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Inicio *</label>
            <input wire:model="newStartDate" type="date" style="width:100%; {{ $iS }}">
            @error('newStartDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Fin *</label>
            <input wire:model="newEndDate" type="date" style="width:100%; {{ $iS }}">
            @error('newEndDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
        </div>
    </div>

    <div style="display:flex; gap:8px;">
        <button wire:click="saveNew" wire:loading.attr="disabled"
                style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
            Cancelar
        </button>
    </div>
</div>
@endif

{{-- ══════ TABLA ══════ --}}
@php
$iRow = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
@endphp

@php
$fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
$fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
$fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
$fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
$fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
$thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
@endphp
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Ciclos Comerciales</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $cycles->total() }}</span>
        @if($selectedCycleId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedCycleId)
                <button wire:click="saveEdit" wire:loading.attr="disabled" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedCycleId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
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
    <table style="width:100%; border-collapse:collapse; min-width:640px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'pointer-events:none;' : '' }}">
                <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF; box-shadow:inset -1px 0 0 #E5E7EB;">
                    #
                    <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                        <input type="checkbox"
                               :checked="$wire.selectedCycleId !== null"
                               :disabled="$wire.selectedCycleId === null || $wire.editingId !== null"
                               @click.prevent="$wire.selectedCycleId !== null && $wire.editingId === null && $wire.set('selectedCycleId', null)"
                               :style="($wire.selectedCycleId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                    </div>
                </th>

                {{-- Estado --}}
                @php $isA = $sortBy === 'status'; @endphp
                <th wire:click="toggleSort('status')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:120px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Estado
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>
                        {!! $fSvg !!}
                        <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }} text-indent:0; padding-left:16px;">
                            <option value="">Todos</option>
                            <option value="abierto">Abierto</option>
                            <option value="cerrado">Cerrado</option>
                        </select>
                    </div>
                </th>

                {{-- Código --}}
                @php $isA = $sortBy === 'code'; @endphp
                <th wire:click="toggleSort('code')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:120px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Código
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCodigo" @click.stop type="text" style="{{ $fI }} text-align:center;"></div>
                    <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                </th>

                {{-- Descripción --}}
                @php $isA = $sortBy === 'name'; @endphp
                <th wire:click="toggleSort('name')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:180px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Descripción
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterDescripcion" @click.stop type="text" style="{{ $fI }} text-align:center;"></div>
                    <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                </th>

                {{-- Inicio --}}
                @php $isA = $sortBy === 'start_date'; @endphp
                <th wire:click="toggleSort('start_date')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Inicio
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>
                        <input wire:model.live.debounce.300ms="colFilterInicio" @click.stop type="date" title="Desde esta fecha"
                               style="height:28px; font-size:10px; border:1px solid #DDD8FA; border-radius:5px; padding:0 3px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; cursor:pointer;">
                    </div>
                </th>

                {{-- Fin --}}
                @php $isA = $sortBy === 'end_date'; @endphp
                <th wire:click="toggleSort('end_date')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Fin
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>
                        <input wire:model.live.debounce.300ms="colFilterFin" @click.stop type="date" title="Hasta esta fecha"
                               style="height:28px; font-size:10px; border:1px solid #DDD8FA; border-radius:5px; padding:0 3px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; cursor:pointer;">
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        @forelse($cycles as $cycle)

        @if($editingId === $cycle->id)
        {{-- ── FILA EDICIÓN INLINE ── --}}
        <tr wire:key="edit-{{ $cycle->id }}" style="background:#FAFAFE; border-bottom:1px solid #EDE9FE;">
            <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#FAFAFE; box-shadow:inset -1px 0 0 #E5E7EB;">
                <span style="font-size:13px; color:#111827;">{{ $cycles->firstItem() + $loop->index }}</span>
            </td>
            <td style="padding:10px 16px; box-shadow:inset -1px 0 0 #E5E7EB;">
                <select wire:model="editStatus" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="abierto">Abierto</option>
                    <option value="cerrado">Cerrado</option>
                </select>
                @error('editStatus')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px; box-shadow:inset -1px 0 0 #E5E7EB;">
                <input wire:model="editCode" type="text" maxlength="30"
                       style="width:100%; {{ $iRow }} text-transform:uppercase;">
                @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px; box-shadow:inset -1px 0 0 #E5E7EB;">
                <input wire:model="editName" type="text" placeholder="Descripción"
                       style="width:100%; {{ $iRow }}">
                @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px; box-shadow:inset -1px 0 0 #E5E7EB;">
                <input wire:model="editStartDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editStartDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <input wire:model="editEndDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editEndDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
        </tr>

        @else
        {{-- ── FILA NORMAL ── --}}
        @php $selCy = $selectedCycleId === $cycle->id; @endphp
        <tr wire:key="c-{{ $cycle->id }}"
            style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $selCy ? '#F5F3FF' : '' }}; {{ $selCy ? 'border-left:3px solid #7B6FE8;' : '' }}"
            @mouseenter="$el.style.background='{{ $selCy ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selCy ? '#F5F3FF' : '' }}'">
            <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selCy ? '#F5F3FF' : '#fff' }}; box-shadow:inset -1px 0 0 #E5E7EB;">
                <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                    <input type="checkbox"
                           :checked="$wire.selectedCycleId === {{ $cycle->id }}"
                           @click="$wire.selectedCycleId === {{ $cycle->id }} ? $wire.set('selectedCycleId', null) : $wire.selectCycle({{ $cycle->id }})"
                           :disabled="{{ $editingId && $editingId !== $cycle->id ? 'true' : 'false' }}"
                           style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $cycle->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                    <span style="font-size:13px; color:#111827;">{{ $cycles->firstItem() + $loop->index }}</span>
                </div>
            </td>
            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                <span style="font-size:13px; font-weight:700; color:#374151; white-space:nowrap;">{{ ucfirst($cycle->status) }}</span>
            </td>
            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $cycle->code }}</span></td>
            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px; font-weight:400; color:#374151;">{{ ucwords(strtolower($cycle->name)) }}</span></td>
            <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;"><span style="font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $cycle->start_date->format('d/m/Y') }}</span></td>
            <td style="padding:10px 14px; text-align:center;"><span style="font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $cycle->end_date->format('d/m/Y') }}</span></td>
        </tr>
        @endif

        @empty
        <tr><td colspan="6" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">No hay ciclos registrados.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>

    @if($cycles->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $cycles->links() }}</div>
    @endif
</div>

{{-- ══════ CARDS MOBILE ══════ --}}
<div class="sm:hidden">

    @if($cycles->isEmpty())
    <p style="text-align:center; color:#9CA3AF; font-size:13px; padding:48px 0;">No hay ciclos registrados.</p>
    @endif

    @foreach($cycles as $cycle)

    @if($editingId === $cycle->id)
    {{-- CARD EDICIÓN --}}
    <div wire:key="edit-mobile-{{ $cycle->id }}"
         style="background:#FAFAFE; border-radius:14px; border:1px solid #EDE9FE; margin-bottom:10px; padding:14px 16px; box-shadow:0 1px 4px rgba(123,111,232,.1);">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
                <input wire:model="editCode" type="text" maxlength="30"
                       style="width:100%; {{ $iRow }} text-transform:uppercase;">
                @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="editStatus" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="abierto">Abierto</option>
                    <option value="cerrado">Cerrado</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:10px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Descripción *</label>
            <input wire:model="editName" type="text"
                   style="width:100%; {{ $iRow }}">
            @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Inicio *</label>
                <input wire:model="editStartDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editStartDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Fin *</label>
                <input wire:model="editEndDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editEndDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <button wire:click="saveEdit" wire:loading.attr="disabled"
                    style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                <span wire:loading wire:target="saveEdit">Guardando...</span>
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>

    @else
    {{-- CARD NORMAL --}}
    <div wire:key="mobile-{{ $cycle->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; margin-bottom:10px; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Header: avatar + código + estado --}}
        <div style="background:#F8F7FF; padding:10px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #EDE9FE;">
            <div style="width:30px;height:30px;border-radius:8px;background:#EDE9FE;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:12px;font-weight:700;color:#7B6FE8;">{{ strtoupper(substr($cycle->name, 0, 1)) }}</span>
            </div>
            <span style="flex:1;font-size:13px; font-weight:800; color:#7B6FE8; font-family:monospace; letter-spacing:.5px;">{{ $cycle->code }}</span>
            @if($cycle->status === 'abierto')
            <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; background:#D1FAE5; color:#059669;">Abierto</span>
            @else
            <span style="font-size:11px; font-weight:600; padding:3px 10px; border-radius:99px; background:#F3F4F6; color:#9CA3AF;">Cerrado</span>
            @endif
        </div>

        {{-- Descripción + fechas --}}
        <div style="padding:12px 14px 10px;">
            <p style="font-size:14px; font-weight:700; color:#111827; margin:0 0 12px;">{{ $cycle->name }}</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <span style="display:block; font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px;">Inicio</span>
                    <span style="font-size:13px; font-weight:600; color:#374151;">{{ $cycle->start_date->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span style="display:block; font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px;">Fin</span>
                    <span style="font-size:13px; font-weight:600; color:#374151;">{{ $cycle->end_date->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button wire:click="startEdit({{ $cycle->id }})"
                    style="flex:1; height:34px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Editar
            </button>
        </div>
    </div>
    @endif

    @endforeach

    @if($cycles->hasPages())
    <div style="margin-top:4px;">{{ $cycles->links() }}</div>
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

</div>
