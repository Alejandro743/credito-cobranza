<div>

@php
$iS     = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$iRow   = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$lStyle = 'display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;';
@endphp

{{-- Toast success --}}
@if(session('success'))
<div wire:key="toast-success-{{ uniqid() }}" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(123,111,232,.35);display:flex;align-items:center;gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Toast error --}}
@if(session('error'))
<div wire:key="toast-error-{{ uniqid() }}" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#EF4444;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 16px rgba(239,68,68,.35);">
    {{ session('error') }}
</div>
@endif

{{-- Toolbar --}}
@if(!$showAddForm)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;justify-content:flex-start;">
    <button wire:click="showAdd"
            style="height:36px;padding:0 18px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva Configuración
    </button>
</div>
@endif

{{-- Panel Alta --}}
@if($showAddForm)
@php $totalNew = $newPesoPuntualidad + $newPesoMora + $newPesoRiesgo + $newPesoRecuperacion + $newPesoReprogramacion; @endphp

{{-- Desktop --}}
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #EDE9FE;box-shadow:0 2px 12px rgba(123,111,232,.12);margin-bottom:20px;overflow:hidden;">
    <div style="background:#F8F7FF;border-bottom:1px solid #EDE9FE;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:800;color:#7B6FE8;display:flex;align-items:center;gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nueva Configuración de Pesos
        </span>
        <button wire:click="cancelAdd"
                style="width:30px;height:30px;border:1px solid #EDE9FE;background:#fff;color:#9CA3AF;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
        <div style="min-width:200px;">
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="newNombre" type="text" placeholder="Ej: Pesos 2026" style="width:100%;{{ $iS }}">
            @error('newNombre') <p style="font-size:11px;color:#EF4444;margin-top:4px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="{{ $lStyle }}">Estado</label>
            <select wire:model="newActivo" style="width:100%;{{ $iS }} cursor:pointer;">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div style="min-width:220px; padding-top:20px;">
            <p style="font-size:11px; color:#9CA3AF; margin:0; display:flex; align-items:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Empieza a regir ahora mismo y cierra automáticamente la configuración anterior.
            </p>
        </div>
    </div>

    {{-- Pesos --}}
    <div style="padding:0 20px 16px;">
        <div style="border:1px solid #EDE9FE;border-radius:10px;padding:14px 16px;background:#FAFAFE;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Pesos de Indicadores</span>
                <div style="flex:1; height:1px; background:#EDE9FE;"></div>
                <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; white-space:nowrap;
                             background:{{ round($totalNew,2) == 100 ? '#D1FAE5' : '#FEF2F2' }};
                             color:{{ round($totalNew,2) == 100 ? '#059669' : '#EF4444' }};">
                    Suma: {{ $totalNew }}%
                </span>
            </div>
            @error('newPesoPuntualidad')<p style="font-size:11px; color:#EF4444; margin:-6px 0 10px;">{{ $message }}</p>@enderror
            <div style="display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
                @foreach([
                    ['Puntualidad',  'newPesoPuntualidad',    25],
                    ['Mora',         'newPesoMora',           25],
                    ['C. Riesgo',    'newPesoRiesgo',         20],
                    ['Recuperación', 'newPesoRecuperacion',   20],
                    ['Reprogramac.', 'newPesoReprogramacion', 10],
                ] as [$lbl, $model, $def])
                <div style="min-width:120px;">
                    <label style="{{ $lStyle }} margin-bottom:3px;">{{ $lbl }}</label>
                    <p style="font-size:10px; color:#9CA3AF; margin:0 0 4px;">def. {{ $def }}%</p>
                    <div style="position:relative;">
                        <input wire:model.live="{{ $model }}" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iS }} padding-right:28px;">
                        <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:12px; color:#9CA3AF; font-weight:700;">%</span>
                    </div>
                </div>
                @endforeach
            </div>
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
                style="height:38px;padding:0 18px;border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">
            Cancelar
        </button>
    </div>
</div>

{{-- Móvil --}}
<div class="sm:hidden" style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;margin-bottom:16px;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">Nombre *</label>
        <input wire:model="newNombre" type="text" placeholder="Ej: Pesos 2026" style="width:100%;{{ $iS }}">
        @error('newNombre') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
    </div>
    <div style="margin-bottom:10px;">
        <label style="{{ $lStyle }}">Estado</label>
        <select wire:model="newActivo" style="width:100%;{{ $iS }} cursor:pointer;">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
    </div>
    <p style="font-size:10px; color:#9CA3AF; margin:0 0 12px; display:flex; align-items:center; gap:5px;">
        <svg width="11" height="11" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Empieza a regir ahora y cierra la configuración anterior automáticamente.
    </p>

    <div style="border:1px solid #EDE9FE;border-radius:10px;padding:12px;background:#fff;margin-bottom:12px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
            <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Pesos</span>
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
            <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; white-space:nowrap;
                         background:{{ round($totalNew,2) == 100 ? '#D1FAE5' : '#FEF2F2' }};
                         color:{{ round($totalNew,2) == 100 ? '#059669' : '#EF4444' }};">
                {{ $totalNew }}%
            </span>
        </div>
        @error('newPesoPuntualidad')<p style="font-size:10px; color:#EF4444; margin:-6px 0 10px;">{{ $message }}</p>@enderror
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach([
                ['Puntualidad',  'newPesoPuntualidad',    25],
                ['Mora',         'newPesoMora',           25],
                ['C. Riesgo',    'newPesoRiesgo',         20],
                ['Recuperación', 'newPesoRecuperacion',   20],
                ['Reprogramac.', 'newPesoReprogramacion', 10],
            ] as [$lbl, $model, $def])
            <div>
                <label style="{{ $lStyle }} margin-bottom:3px;">{{ $lbl }} <span style="font-weight:400; color:#9CA3AF; text-transform:none;">(def. {{ $def }}%)</span></label>
                <div style="position:relative;">
                    <input wire:model.live="{{ $model }}" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iS }} padding-right:28px;">
                    <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:12px; color:#9CA3AF; font-weight:700;">%</span>
                </div>
            </div>
            @endforeach
        </div>
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
        <span style="font-size:13px;font-weight:700;color:#111827;">Configuraciones de Pesos</span>
        <span style="background:#EDE9FE;color:#7B6FE8;font-size:11px;font-weight:600;padding:2px 8px;border-radius:99px;">{{ $registros->count() }}</span>
        @if($selectedPesoId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedPesoId)
                <button wire:click="saveEdit" wire:loading.attr="disabled" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedPesoId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @endif
        </div>
        @endif
    </div>

    @if($registros->isEmpty())
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">Sin configuraciones. Creá la primera.</p>
    @else
    <div style="overflow:auto;flex:1;">
        <table style="width:100%;border-collapse:collapse;min-width:1200px;">
            <thead style="position:sticky;top:0;z-index:10;">
                <tr style="background:#F9F8FF;border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedPesoId !== null"
                                   :disabled="$wire.selectedPesoId === null || $wire.editingId !== null"
                                   @click.prevent="$wire.selectedPesoId !== null && $wire.editingId === null && $wire.set('selectedPesoId', null)"
                                   :style="($wire.selectedPesoId !== null && $wire.editingId === null) ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'nombre'; @endphp
                    <th wire:click="toggleSort('nombre')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:170px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Vigencia desde --}}
                    @php $isA = $sortBy === 'fecha_inicio'; @endphp
                    <th wire:click="toggleSort('fecha_inicio')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Desde
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Vigencia hasta --}}
                    @php $isA = $sortBy === 'fecha_fin'; @endphp
                    <th wire:click="toggleSort('fecha_fin')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Hasta
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Vigente --}}
                    <th style="{{ $thC }} text-align:center; min-width:100px;">
                        Vigente
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterVigente" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </th>

                    {{-- Punt% --}}
                    @php $isA = $sortBy === 'peso_puntualidad'; @endphp
                    <th wire:click="toggleSort('peso_puntualidad')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:90px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Punt%
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Mora% --}}
                    @php $isA = $sortBy === 'peso_mora'; @endphp
                    <th wire:click="toggleSort('peso_mora')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:90px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Mora%
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Riesgo% --}}
                    @php $isA = $sortBy === 'peso_riesgo'; @endphp
                    <th wire:click="toggleSort('peso_riesgo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:90px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Riesgo%
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Recup% --}}
                    @php $isA = $sortBy === 'peso_recuperacion'; @endphp
                    <th wire:click="toggleSort('peso_recuperacion')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:90px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Recup%
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                    </th>

                    {{-- Reprog% --}}
                    @php $isA = $sortBy === 'peso_reprogramacion'; @endphp
                    <th wire:click="toggleSort('peso_reprogramacion')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:90px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Reprog%
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
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
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $r)
                @if($editingId === $r->id)
                {{-- Fila edición inline --}}
                <tr wire:key="edit-{{ $r->id }}" style="background:#FAFAFE;border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#FAFAFE;">
                        <span style="font-size:13px; color:#111827;">{{ $loop->iteration }}</span>
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
                        @error('editNombre') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 10px;text-align:center;">
                        <span style="font-size:12px;color:#6B7280;white-space:nowrap;">{{ $r->fecha_inicio->format('d/m/Y H:i') }}</span>
                    </td>
                    <td style="padding:10px 10px;text-align:center;">
                        <span style="font-size:12px;color:#9CA3AF;">—</span>
                    </td>
                    <td style="padding:10px 10px;text-align:center;">
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;background:#EDE9FE;color:#7B6FE8;">Sí</span>
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editPesoPuntualidad" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} text-align:center;">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editPesoMora" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} text-align:center;">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editPesoRiesgo" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} text-align:center;">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editPesoRecuperacion" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} text-align:center;">
                    </td>
                    <td style="padding:10px 10px;">
                        <input wire:model="editPesoReprogramacion" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} text-align:center;">
                    </td>
                    <td style="padding:10px 10px;">
                        <select wire:model="editActivo" style="width:100%;{{ $iRow }} cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>
                @error('editPesoPuntualidad')
                <tr wire:key="edit-err-{{ $r->id }}" style="background:#FAFAFE;">
                    <td colspan="11" style="padding:0 10px 10px; position:sticky; left:0;">
                        <p style="font-size:11px;color:#EF4444;margin:0;">{{ $message }}</p>
                    </td>
                </tr>
                @enderror
                @else
                {{-- Fila normal --}}
                @php $selR = $selectedPesoId === $r->id; $esVigente = $vigenteId === $r->id; $esAbierta = $r->fecha_fin === null; @endphp
                <tr wire:key="pi-{{ $r->id }}"
                    style="border-bottom:1px solid #F3F4F6;transition:background .1s; background:{{ $selR ? '#F5F3FF' : '' }}; {{ $selR ? 'border-left:3px solid #7B6FE8;' : '' }} {{ !$esAbierta ? 'opacity:0.75;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selR ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selR ? '#F5F3FF' : '' }}'">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selR ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            @if($esAbierta)
                            <input type="checkbox"
                                   :checked="$wire.selectedPesoId === {{ $r->id }}"
                                   @click="$wire.selectedPesoId === {{ $r->id }} ? $wire.set('selectedPesoId', null) : $wire.selectPeso({{ $r->id }})"
                                   :disabled="{{ $editingId && $editingId !== $r->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $r->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            @else
                            <span title="Histórico: no editable" style="width:13px; height:13px; display:inline-flex; align-items:center; justify-content:center;">
                                <svg width="11" height="11" fill="none" stroke="#D1D5DB" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            @endif
                            <span style="font-size:13px; color:#111827;">{{ $loop->iteration }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px;"><span style="font-size:13px;color:#111827;">{{ ucwords(strtolower($r->nombre)) }}</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;white-space:nowrap;">{{ $r->fecha_inicio->format('d/m/Y H:i') }}</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;white-space:nowrap;">{{ $r->fecha_fin?->format('d/m/Y H:i') ?? '—' }}</span></td>
                    <td style="padding:10px 14px;text-align:center;">
                        @if($esVigente)
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;background:#EDE9FE;color:#7B6FE8;">Sí</span>
                        @else
                        <span style="font-size:13px;color:#D1D5DB;">—</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;">{{ $r->peso_puntualidad }}%</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;">{{ $r->peso_mora }}%</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;">{{ $r->peso_riesgo }}%</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;">{{ $r->peso_recuperacion }}%</span></td>
                    <td style="padding:10px 14px;text-align:center;"><span style="font-size:13px;color:#111827;">{{ $r->peso_reprogramacion }}%</span></td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;
                                     background:{{ $r->activo ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $r->activo ? '#059669' : '#9CA3AF' }};">
                            {{ $r->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Tarjetas móvil --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($registros as $r)
    @if($editingId === $r->id)
    <div wire:key="card-edit-{{ $r->id }}"
         style="background:#FAFAFE;border-radius:14px;border:1px solid #EDE9FE;padding:14px 16px;box-shadow:0 1px 4px rgba(123,111,232,.1);">
        <div style="margin-bottom:10px;">
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="editNombre" type="text" style="width:100%;{{ $iRow }}">
            @error('editNombre') <p style="font-size:10px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <p style="font-size:10px; color:#9CA3AF; margin:0 0 10px;">Vigente desde {{ $r->fecha_inicio->format('d/m/Y H:i') }}</p>
        <div style="margin-bottom:12px;">
            <label style="{{ $lStyle }}">Estado</label>
            <select wire:model="editActivo" style="width:100%;{{ $iRow }} cursor:pointer;">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <div style="border:1px solid #EDE9FE;border-radius:10px;padding:12px;background:#fff;margin-bottom:12px;">
            @php $totalEdit = $editPesoPuntualidad + $editPesoMora + $editPesoRiesgo + $editPesoRecuperacion + $editPesoReprogramacion; @endphp
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Pesos</span>
                <div style="flex:1; height:1px; background:#EDE9FE;"></div>
                <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; white-space:nowrap;
                             background:{{ round($totalEdit,2) == 100 ? '#D1FAE5' : '#FEF2F2' }};
                             color:{{ round($totalEdit,2) == 100 ? '#059669' : '#EF4444' }};">
                    {{ $totalEdit }}%
                </span>
            </div>
            @error('editPesoPuntualidad')<p style="font-size:10px; color:#EF4444; margin:-6px 0 10px;">{{ $message }}</p>@enderror
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach([
                    ['Puntualidad',  'editPesoPuntualidad',    25],
                    ['Mora',         'editPesoMora',           25],
                    ['C. Riesgo',    'editPesoRiesgo',         20],
                    ['Recuperación', 'editPesoRecuperacion',   20],
                    ['Reprogramac.', 'editPesoReprogramacion', 10],
                ] as [$lbl, $model, $def])
                <div>
                    <label style="{{ $lStyle }} margin-bottom:3px;">{{ $lbl }} <span style="font-weight:400; color:#9CA3AF; text-transform:none;">(def. {{ $def }}%)</span></label>
                    <div style="position:relative;">
                        <input wire:model.live="{{ $model }}" type="number" min="0" max="100" step="0.5" style="width:100%;{{ $iRow }} padding-right:28px;">
                        <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:12px; color:#9CA3AF; font-weight:700;">%</span>
                    </div>
                </div>
                @endforeach
            </div>
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
    @php $esVigente = $vigenteId === $r->id; $esAbierta = $r->fecha_fin === null; @endphp
    <div wire:key="card-{{ $r->id }}"
         style="background:#fff;border-radius:14px;border:1px solid {{ $esVigente ? '#C4B5FD' : '#E5E7EB' }}; {{ $esVigente ? 'box-shadow:0 0 0 3px #EDE9FE;' : 'box-shadow:0 1px 3px rgba(0,0,0,.06);' }} overflow:hidden; {{ !$esAbierta ? 'opacity:0.8;' : '' }}">

        <div style="background:#F8F7FF;padding:10px 14px;display:flex;align-items:center;gap:8px;border-bottom:1px solid #EDE9FE;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_substr($r->nombre, 0, 1) }}</span>
            </div>
            <div style="flex:1;min-width:0;">
                <span style="font-size:13px;font-weight:700;color:#111827;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $r->nombre }}</span>
                <span style="font-size:11px;color:#9CA3AF;">{{ $r->fecha_inicio->format('d/m/Y H:i') }} → {{ $r->fecha_fin?->format('d/m/Y H:i') ?? 'sin límite' }}</span>
            </div>
            @if($esVigente)
            <span style="font-size:9px; font-weight:800; padding:2px 7px; border-radius:99px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; white-space:nowrap; flex-shrink:0; letter-spacing:.3px;">VIGENTE</span>
            @endif
            @if($r->activo)
            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;background:#D1FAE5;color:#059669;flex-shrink:0;">Activo</span>
            @else
            <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:99px;background:#F3F4F6;color:#9CA3AF;flex-shrink:0;">Inactivo</span>
            @endif
        </div>

        <div style="padding:12px 14px;display:flex;flex-direction:column;gap:8px;">
            @foreach([
                ['Puntualidad',    $r->peso_puntualidad],
                ['Mora generada',  $r->peso_mora],
                ['Cuota en riesgo',$r->peso_riesgo],
                ['Recuperación',   $r->peso_recuperacion],
                ['Reprogramación', $r->peso_reprogramacion],
            ] as [$lbl, $val])
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:12px; color:#6B7280; width:100px; flex-shrink:0;">{{ $lbl }}</span>
                <div style="flex:1; height:6px; background:#F3F4F6; border-radius:99px; overflow:hidden;">
                    <div style="height:100%; width:{{ $val }}%; background:#7B6FE8; border-radius:99px;"></div>
                </div>
                <span style="font-size:13px; font-weight:800; color:#7B6FE8; width:36px; text-align:right; flex-shrink:0;">{{ $val }}%</span>
            </div>
            @endforeach
        </div>

        @if($esAbierta)
        <div style="padding:10px 14px;border-top:1px solid #F3F4F6;">
            <button wire:click="startEdit({{ $r->id }})"
                    style="width:100%;height:34px;background:#F8F7FF;color:#7B6FE8;border:1px solid #EDE9FE;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Editar
            </button>
        </div>
        @else
        <div style="padding:8px 14px;border-top:1px solid #F3F4F6;">
            <span style="font-size:11px;color:#9CA3AF;display:flex;align-items:center;justify-content:center;gap:5px;">
                <svg width="11" height="11" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Histórico
            </span>
        </div>
        @endif
    </div>
    @endif
    @empty
    <p style="text-align:center;font-size:13px;color:#9CA3AF;padding:48px 0;">Sin configuraciones. Creá la primera.</p>
    @endforelse
</div>

</div>
