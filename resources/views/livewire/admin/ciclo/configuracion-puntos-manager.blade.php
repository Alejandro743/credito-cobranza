<div>

@php
$iS   = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$iRow = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
@endphp

{{-- Toast success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#7B6FE8;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(123,111,232,.35);display:flex;align-items:center;gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#DC2626;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(220,38,38,.35);display:flex;align-items:center;gap:8px;max-width:340px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Toolbar --}}
@if(!$showAddForm)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;justify-content:flex-start;">
    @if($ciclosDisponibles->isNotEmpty())
    <button wire:click="showAdd"
            style="height:36px;padding:0 18px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap;transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva Config.
    </button>
    @endif
</div>
@endif

{{-- Panel nuevo --}}
@if($showAddForm)

{{-- Desktop --}}
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #EDE9FE;box-shadow:0 2px 12px rgba(123,111,232,.12);margin-bottom:20px;overflow:hidden;">
    <div style="background:#F8F7FF;border-bottom:1px solid #EDE9FE;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:800;color:#7B6FE8;display:flex;align-items:center;gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Nueva Configuración de Puntos
        </span>
        <button wire:click="cancelAdd"
                style="width:30px;height:30px;border:1px solid #EDE9FE;background:#fff;color:#9CA3AF;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Ciclo *</label>
            <select wire:model="newCycleId" style="width:100%;{{ $iS }}">
                <option value="">— Seleccionar ciclo —</option>
                @foreach($ciclosDisponibles as $ciclo)
                <option value="{{ $ciclo->id }}">{{ $ciclo->code }} — {{ $ciclo->name }}</option>
                @endforeach
            </select>
            @error('newCycleId')<p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="min-width:120px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Valor / Punto *</label>
            <div style="position:relative;">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;font-weight:500;">Bs</span>
                <input wire:model="newValorPunto" type="number" step="0.01" min="0.01" placeholder="1.00"
                       style="width:100%;{{ $iS }} padding-left:28px;">
            </div>
            @error('newValorPunto')<p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="flex:1;min-width:150px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Descripción</label>
            <input wire:model="newDescription" type="text" placeholder="Observación opcional"
                   style="width:100%;{{ $iS }}">
        </div>
        <div style="min-width:120px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px;">Estado</label>
            <select wire:model="newActive" style="width:100%;{{ $iS }}">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:700;color:transparent;margin-bottom:5px;">·</label>
            <div style="display:flex;gap:8px;">
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
    </div>
</div>

{{-- Móvil --}}
<div class="sm:hidden" style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;margin-bottom:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
    <div style="margin-bottom:10px;">
        <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Ciclo *</label>
        <select wire:model="newCycleId" style="width:100%;{{ $iS }}">
            <option value="">— Seleccionar —</option>
            @foreach($ciclosDisponibles as $ciclo)
            <option value="{{ $ciclo->id }}">{{ $ciclo->code }} — {{ $ciclo->name }}</option>
            @endforeach
        </select>
        @error('newCycleId')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
        <div>
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Valor / Punto *</label>
            <div style="position:relative;">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;">Bs</span>
                <input wire:model="newValorPunto" type="number" step="0.01" min="0.01" placeholder="1.00"
                       style="width:100%;{{ $iS }} padding-left:28px;">
            </div>
            @error('newValorPunto')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Estado</label>
            <select wire:model="newActive" style="width:100%;{{ $iS }}">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Descripción</label>
        <input wire:model="newDescription" type="text" placeholder="Observación opcional" style="width:100%;{{ $iS }}">
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
        <span style="font-size:13px;font-weight:700;color:#111827;">Configuración de Puntos</span>
        <span style="background:#EDE9FE;color:#7B6FE8;font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;">{{ $puntos->total() }}</span>
        @if($selectedPuntoId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedPuntoId)
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
                <button wire:click="startEdit({{ $selectedPuntoId }})"
                        style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto;flex:1;">
        <table style="width:100%;border-collapse:collapse;min-width:640px;">
            <thead style="position:sticky;top:0;z-index:10;">
                <tr style="background:#F9F8FF;border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedPuntoId !== null"
                                   :disabled="$wire.selectedPuntoId === null || $wire.editingId !== null"
                                   @click.prevent="$wire.selectedPuntoId !== null && $wire.editingId === null && $wire.set('selectedPuntoId', null)"
                                   :style="($wire.selectedPuntoId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Ciclo --}}
                    @php $isA = $sortBy === 'code'; @endphp
                    <th wire:click="toggleSort('code')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:150px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Ciclo
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiclo" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Valor / Pto --}}
                    @php $isA = $sortBy === 'valor_punto'; @endphp
                    <th wire:click="toggleSort('valor_punto')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Valor / Pto
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterValor" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Descripción --}}
                    <th style="{{ $thC }} text-align:left; min-width:160px;">
                        Descripción
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterDescripcion" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'active'; @endphp
                    <th wire:click="toggleSort('active')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
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
                </tr>
            </thead>
            <tbody>
                @forelse($puntos as $punto)
                @if($editingId === $punto->id)
                {{-- Fila edición inline --}}
                <tr wire:key="edit-{{ $punto->id }}" style="background:#FAFAFE;border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#FAFAFE;">
                        <span style="font-size:13px; color:#111827;">{{ $puntos->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:10px 16px;">
                        <select wire:model="editCycleId" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="{{ $punto->cycle->id }}">{{ $punto->cycle->code }}</option>
                            @foreach($ciclosDisponibles as $ciclo)
                            <option value="{{ $ciclo->id }}">{{ $ciclo->code }}</option>
                            @endforeach
                        </select>
                        @error('editCycleId')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:10px 16px;">
                        <div style="position:relative;">
                            <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;font-weight:500;">Bs</span>
                            <input wire:model="editValorPunto" type="number" step="0.01" min="0.01"
                                   style="width:100%;{{ $iRow }} padding-left:24px;">
                        </div>
                        @error('editValorPunto')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:10px 16px;">
                        <input wire:model="editDescription" type="text" placeholder="Descripción"
                               style="width:100%;{{ $iRow }}">
                    </td>
                    <td style="padding:10px 16px;">
                        <select wire:model="editActive" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>
                @else
                {{-- Fila normal --}}
                @php $selPu = $selectedPuntoId === $punto->id; @endphp
                <tr wire:key="p-{{ $punto->id }}"
                    style="border-bottom:1px solid #F3F4F6;transition:background .1s; background:{{ $selPu ? '#F5F3FF' : '' }}; {{ $selPu ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selPu ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selPu ? '#F5F3FF' : '' }}'">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selPu ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedPuntoId === {{ $punto->id }}"
                                   @click="$wire.selectedPuntoId === {{ $punto->id }} ? $wire.set('selectedPuntoId', null) : $wire.selectPunto({{ $punto->id }})"
                                   :disabled="{{ $editingId && $editingId !== $punto->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $punto->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:13px; color:#111827;">{{ $puntos->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px;">
                        <span style="font-size:13px;color:#111827;">{{ $punto->cycle->code }}</span>
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="font-size:13px;color:#111827;">Bs {{ number_format((float)$punto->valor_punto, 2) }}</span>
                        <span style="font-size:13px;color:#111827;"> / pto</span>
                    </td>
                    <td style="padding:10px 14px;font-size:13px;color:#111827;">{{ $punto->description ?? '—' }}</td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;
                                     background:{{ $punto->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $punto->active ? '#059669' : '#9CA3AF' }};">
                            {{ $punto->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="5" style="padding:48px;text-align:center;color:#9CA3AF;font-size:13px;">No hay configuraciones de puntos registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($puntos->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #F3F4F6;flex-shrink:0;">{{ $puntos->links() }}</div>
    @endif
</div>

{{-- Tarjetas móvil --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($puntos as $punto)
    @if($editingId === $punto->id)
    <div wire:key="card-edit-{{ $punto->id }}"
         style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">

        <div style="margin-bottom:10px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Ciclo *</label>
            <select wire:model="editCycleId" style="width:100%;{{ $iRow }} cursor:pointer;">
                <option value="{{ $punto->cycle->id }}">{{ $punto->cycle->code }}</option>
                @foreach($ciclosDisponibles as $ciclo)
                <option value="{{ $ciclo->id }}">{{ $ciclo->code }}</option>
                @endforeach
            </select>
            @error('editCycleId')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Valor / Punto *</label>
                <div style="position:relative;">
                    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;">Bs</span>
                    <input wire:model="editValorPunto" type="number" step="0.01" min="0.01"
                           style="width:100%;{{ $iRow }} padding-left:28px;">
                </div>
                @error('editValorPunto')<p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" style="width:100%;{{ $iRow }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:12px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Descripción</label>
            <input wire:model="editDescription" type="text" placeholder="Descripción" style="width:100%;{{ $iRow }}">
        </div>

        <div style="display:flex;gap:8px;">
            <button wire:click="saveEdit" wire:loading.attr="disabled"
                    style="flex:1;height:36px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                <span wire:loading wire:target="saveEdit">Guardando...</span>
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1;height:36px;border:1px solid #EDE9FE;background:#F8F7FF;color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Cerrar
            </button>
        </div>
    </div>
    @else
    <div wire:key="card-{{ $punto->id }}"
         style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;">

        {{-- Header: código + badge --}}
        <div style="background:#F8F7FF;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #EDE9FE;">
            <span style="font-family:monospace;font-size:13px;font-weight:800;color:#7B6FE8;letter-spacing:.5px;">{{ $punto->cycle->code }}</span>
            @if($punto->active)
            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;background:#D1FAE5;color:#059669;">Activo</span>
            @else
            <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;background:#F3F4F6;color:#9CA3AF;">Inactivo</span>
            @endif
        </div>

        {{-- Cuerpo --}}
        <div style="padding:12px 14px;">
            <p style="font-size:13px;font-weight:600;color:#111827;margin:0 0 10px;">{{ $punto->cycle->name }}</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Valor / Punto</span>
                    <span style="font-size:15px;font-weight:800;color:#7B6FE8;">Bs {{ number_format((float)$punto->valor_punto, 2) }}</span>
                    <span style="font-size:11px;color:#9CA3AF;"> / pto</span>
                </div>
                <div>
                    <span style="display:block;font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Período</span>
                    <span style="font-size:12px;font-weight:500;color:#374151;">{{ $punto->cycle->start_date->format('d/m/Y') }}</span>
                    <span style="font-size:11px;color:#9CA3AF;"> — </span>
                    <span style="font-size:12px;font-weight:500;color:#374151;">{{ $punto->cycle->end_date->format('d/m/Y') }}</span>
                </div>
            </div>
            @if($punto->description)
            <p style="font-size:12px;color:#9CA3AF;margin:8px 0 0;font-style:italic;">{{ $punto->description }}</p>
            @endif
        </div>

        {{-- Footer --}}
        <div style="padding:10px 14px;border-top:1px solid #F3F4F6;">
            <button wire:click="startEdit({{ $punto->id }})"
                    style="width:100%;height:34px;background:#F8F7FF;color:#7B6FE8;border:1px solid #EDE9FE;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Editar
            </button>
        </div>
    </div>
    @endif
    @empty
    <p style="text-align:center;font-size:13px;color:#9CA3AF;padding:48px 0;">No hay configuraciones registradas.</p>
    @endforelse
    @if($puntos->hasPages())
    <div style="margin-top:4px;">{{ $puntos->links() }}</div>
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
