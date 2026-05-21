<div>

{{-- Flash success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35);">
    {{ session('success') }}
</div>
@endif

{{-- Flash error --}}
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#EF4444; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(239,68,68,.35);">
    {{ session('error') }}
</div>
@endif

{{-- ══════════════════════════════════════════════════
     LIST
══════════════════════════════════════════════════ --}}
@if($mode === 'list')

{{-- Toolbar --}}
<div style="display:flex; align-items:center; justify-content:flex-end; margin-bottom:16px;">
    <button wire:click="create"
            style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva Configuración
    </button>
</div>

{{-- Card tabla --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Configuraciones de Rangos</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $registros->count() }}</span>
    </div>

    @if($registros->isEmpty())
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">Sin configuraciones. Creá la primera.</p>
    @else

    {{-- DESKTOP --}}
    <div class="hidden sm:block" style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:720px;">
            <thead>
                <tr>
                    <th style="padding:10px 16px; text-align:left;">Nombre</th>
                    <th style="padding:10px 16px; text-align:center;">Vigencia desde</th>
                    <th style="padding:10px 16px; text-align:center;">Vigencia hasta</th>
                    <th style="padding:10px 16px; text-align:center;">Vigente</th>
                    <th style="padding:10px 16px; text-align:center;">A (desde)</th>
                    <th style="padding:10px 16px; text-align:center;">B (desde)</th>
                    <th style="padding:10px 16px; text-align:center;">C (desde)</th>
                    <th style="padding:10px 16px; text-align:center;">D (desde)</th>
                    <th style="padding:10px 16px; text-align:center;">Estado</th>
                    <th style="padding:10px 16px; text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($registros as $r)
            @php $esVigente = \App\Models\RangoCalificacion::vigente()?->id === $r->id; @endphp
            <tr wire:key="rc-{{ $r->id }}" style="border-bottom:1px solid #F9FAFB; {{ $esVigente ? 'background:#FAFAFE;' : '' }}"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background='{{ $esVigente ? '#FAFAFE' : '' }}'">
                <td style="padding:11px 16px; font-size:13px; font-weight:600; color:#111827;">{{ $r->nombre }}</td>
                <td style="padding:11px 16px; text-align:center; font-size:13px; color:#6B7280;">{{ $r->fecha_inicio->format('d/m/Y') }}</td>
                <td style="padding:11px 16px; text-align:center; font-size:13px; color:#6B7280;">{{ $r->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                <td style="padding:11px 16px; text-align:center;">
                    @if($esVigente)
                    <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; background:#EDE9FE; color:#7B6FE8;">Sí</span>
                    @else
                    <span style="font-size:13px; color:#9CA3AF;">—</span>
                    @endif
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <span style="font-size:13px; font-weight:700; padding:3px 10px; border-radius:99px; background:#D1FAE5; color:#059669;">≥ {{ $r->min_a }}</span>
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <span style="font-size:13px; font-weight:700; padding:3px 10px; border-radius:99px; background:#CFFAFE; color:#0E7490;">≥ {{ $r->min_b }}</span>
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <span style="font-size:13px; font-weight:700; padding:3px 10px; border-radius:99px; background:#FEF3C7; color:#B45309;">≥ {{ $r->min_c }}</span>
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <span style="font-size:13px; font-weight:700; padding:3px 10px; border-radius:99px; background:#FFEDD5; color:#C2410C;">≥ {{ $r->min_d }}</span>
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <span style="padding:3px 10px; border-radius:99px; font-size:13px; font-weight:600;
                                 background:{{ $r->activo ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $r->activo ? '#059669' : '#9CA3AF' }};">
                        {{ $r->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td style="padding:11px 16px; text-align:center;">
                    <div style="display:flex; justify-content:center;">
                        <button wire:click="edit({{ $r->id }})" title="Editar"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- MOBILE --}}
    <div class="block sm:hidden">
        <div style="display:flex; flex-direction:column; gap:10px; padding:14px;">
            @foreach($registros as $r)
            @php $esVigente = \App\Models\RangoCalificacion::vigente()?->id === $r->id; @endphp
            <div wire:key="mrc-{{ $r->id }}" style="background:#fff; border-radius:12px; border:1px solid {{ $esVigente ? '#EDE9FE' : '#E5E7EB' }}; {{ $esVigente ? 'border-left:3px solid #7B6FE8;' : '' }} padding:14px; display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                    <div style="display:flex; align-items:center; gap:6px; flex:1; min-width:0;">
                        <span style="font-size:14px; font-weight:700; color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $r->nombre }}</span>
                        @if($esVigente)
                        <span style="font-size:9px; font-weight:700; padding:2px 7px; border-radius:99px; background:#EDE9FE; color:#7B6FE8; white-space:nowrap; flex-shrink:0;">VIGENTE</span>
                        @endif
                    </div>
                    <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; background:{{ $r->activo ? '#D1FAE5' : '#F3F4F6' }}; color:{{ $r->activo ? '#059669' : '#9CA3AF' }};">
                        {{ $r->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div style="display:flex; gap:16px;">
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 2px;">Desde</p>
                        <p style="font-size:12px; color:#374151; margin:0;">{{ $r->fecha_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0 0 2px;">Hasta</p>
                        <p style="font-size:12px; color:#374151; margin:0;">{{ $r->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                <div style="display:flex; gap:6px; flex-wrap:wrap; border-top:1px solid #F3F4F6; padding-top:10px;">
                    <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; background:#D1FAE5; color:#059669;">A ≥ {{ $r->min_a }}</span>
                    <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; background:#CFFAFE; color:#0E7490;">B ≥ {{ $r->min_b }}</span>
                    <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; background:#FEF3C7; color:#B45309;">C ≥ {{ $r->min_c }}</span>
                    <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; background:#FFEDD5; color:#C2410C;">D ≥ {{ $r->min_d }}</span>
                    <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:99px; background:#FEE2E2; color:#DC2626;">Bloq &lt; {{ $r->min_d }}</span>
                </div>
                <div style="border-top:1px solid #F3F4F6; padding-top:10px;">
                    <button wire:click="edit({{ $r->id }})"
                            style="width:100%; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @endif
</div>

{{-- ══════════════════════════════════════════════════
     FORM
══════════════════════════════════════════════════ --}}
@elseif($mode === 'form')
@php $iS = 'width:100%; height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp

{{-- Header --}}
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:11px 18px; display:flex; align-items:center; gap:12px;">
        <button wire:click="backToList"
                style="width:34px; height:34px; border:1px solid #EDE9FE; background:#fff; color:#7B6FE8; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#fff'">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div>
            <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin:0;">Definiciones</p>
            <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0;">{{ $editId ? 'Editar' : 'Nueva' }} Configuración de Rangos</p>
        </div>
    </div>
</div>

{{-- Form card --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">
    <div style="padding:20px 22px; display:flex; flex-direction:column; gap:16px;">

        {{-- Nombre + Estado --}}
        <div style="display:grid; grid-template-columns:1fr auto; gap:12px; align-items:start;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="nombre" type="text" placeholder="Ej: Rangos 2026" style="{{ $iS }}">
                @error('nombre')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
                <select wire:model.live="activo" style="height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                @error('activo')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Vigencia --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Vigencia desde *</label>
                <input wire:model.blur="fechaInicio" type="date" style="{{ $iS }}">
                @error('fechaInicio')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">
                    Vigencia hasta <span style="font-weight:400; color:#9CA3AF; text-transform:none;">(vacío = abierto)</span>
                </label>
                <input wire:model.blur="fechaFin" type="date" style="{{ $iS }}">
                @error('fechaFin')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Separador umbrales --}}
        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Umbrales mínimos</span>
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
        </div>
        @error('minA')<p style="font-size:11px; color:#EF4444; margin-top:-8px;">{{ $message }}</p>@enderror

        {{-- Grid umbrales --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            @foreach([
                ['A — Excelente', 'minA', '#D1FAE5', '#059669'],
                ['B — Buena',     'minB', '#CFFAFE', '#0E7490'],
                ['C — Observada', 'minC', '#FEF3C7', '#B45309'],
                ['D — Riesgosa',  'minD', '#FFEDD5', '#C2410C'],
            ] as [$lbl, $model, $bg, $cl])
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">
                    Puntaje mín. {{ $lbl }}
                </label>
                <div style="position:relative;">
                    <input wire:model.live="{{ $model }}" type="number" min="0" max="100" step="0.5"
                           style="width:100%; height:38px; border:1.5px solid {{ $bg }}; border-radius:8px; padding:0 28px 0 10px; font-size:14px; font-weight:700; outline:none; background:{{ $bg }}; color:{{ $cl }}; box-sizing:border-box;">
                    <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:12px; color:{{ $cl }}; font-weight:700; opacity:.6;">pts</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Preview rangos --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:10px; padding:12px 14px;">
            <p style="font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0 0 8px;">Vista previa</p>
            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; background:#D1FAE5; color:#059669;">A ≥ {{ $minA }}</span>
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; background:#CFFAFE; color:#0E7490;">B ≥ {{ $minB }}</span>
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; background:#FEF3C7; color:#B45309;">C ≥ {{ $minC }}</span>
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; background:#FFEDD5; color:#C2410C;">D ≥ {{ $minD }}</span>
                <span style="font-size:11px; font-weight:700; padding:4px 12px; border-radius:99px; background:#FEE2E2; color:#DC2626;">Bloq &lt; {{ $minD }}</span>
            </div>
        </div>

        {{-- Botones --}}
        <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
            <button wire:click="save" wire:loading.attr="disabled"
                    style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                <span wire:loading.remove wire:target="save">Guardar configuración</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
            <button wire:click="backToList"
                    style="height:38px; padding:0 18px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
        </div>

    </div>
</div>
@endif

</div>
