<div>
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3500)"
     class="fixed bottom-5 right-5 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2"
     style="background:#7c3aed;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

@if($mode === 'list')
@php $thead = 'background:#EDE9FE; color:#6d28d9; font-size:10px; font-weight:700; letter-spacing:0.5px;'; @endphp

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:8px;">
    <span style="font-size:12px; color:#9ca3af;">{{ $registros->count() }} configuración(es)</span>
    <button wire:click="create"
            style="padding:6px 16px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; border:none; background:#7c3aed; color:#fff; display:inline-flex; align-items:center; gap:6px;">
        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Nueva Configuración
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
<div style="overflow-x:auto;">
<table style="border-collapse:separate; border-spacing:0; width:100%; min-width:700px; font-size:12px;">
    <thead style="{{ $thead }}">
        <tr>
            <th style="padding:8px 12px; text-align:left; border:0.5px solid #ddd6fe;">Nombre</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:110px;">Vigencia desde</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:110px;">Vigencia hasta</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">A (desde)</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">B (desde)</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">C (desde)</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">D (desde)</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">Estado</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse($registros as $r)
    @php
        $vigente   = \App\Models\RangoCalificacion::vigente();
        $esVigente = $vigente?->id === $r->id;
    @endphp
    <tr wire:key="rc-{{ $r->id }}" style="{{ $esVigente ? 'background:#faf5ff;' : '' }}">
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-weight:600; color:#374151;">
            {{ $r->nombre }}
            @if($esVigente)
            <span style="margin-left:6px; font-size:9px; font-weight:700; padding:2px 6px; border-radius:10px; background:#EDE9FE; color:#6d28d9;">VIGENTE</span>
            @endif
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:11px; color:#6b7280;">{{ $r->fecha_inicio->format('d/m/Y') }}</td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:11px; color:#6b7280;">{{ $r->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; background:#DCFCE7; color:#15803D;">≥ {{ $r->min_a }}</span>
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; background:#ECFEFF; color:#0e7490;">≥ {{ $r->min_b }}</span>
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FEF3C7; color:#854F0B;">≥ {{ $r->min_c }}</span>
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FFF7ED; color:#C2410C;">≥ {{ $r->min_d }}</span>
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <button wire:click="toggleActivo({{ $r->id }})"
                    style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; border:none; cursor:pointer;
                           background:{{ $r->activo ? '#DCFCE7' : '#f3f4f6' }}; color:{{ $r->activo ? '#15803D' : '#6b7280' }};">
                {{ $r->activo ? 'Activo' : 'Inactivo' }}
            </button>
        </td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            <div style="display:flex; gap:4px; justify-content:center;">
                <button wire:click="edit({{ $r->id }})" title="Editar"
                        style="padding:4px; border-radius:6px; border:1px solid #ddd6fe; background:#faf5ff; color:#7c3aed; cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button wire:click="delete({{ $r->id }})" wire:confirm="¿Eliminar esta configuración?"
                        style="padding:4px; border-radius:6px; border:1px solid #fecaca; background:#fef2f2; color:#B91C1C; cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="9" style="padding:40px; text-align:center; color:#9ca3af;">Sin configuraciones.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
</div>

@elseif($mode === 'form')
<div class="max-w-xl mx-auto">
    <div style="background:#faf5ff; border:1px solid #ddd6fe; border-radius:14px; padding:16px 18px; margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <button wire:click="backToList"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #ddd6fe; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer;">
                <svg width="14" height="14" fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/></svg>
                <span style="font-size:11px; font-weight:700; color:#7c3aed;">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:18px; font-weight:800; color:#6d28d9; margin:0;">
                {{ $editId ? 'Editar' : 'Nueva' }} Configuración de Rangos
            </h1>
            <div style="width:60px;"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">

        <div>
            <label style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Nombre</label>
            <input wire:model="nombre" type="text" placeholder="Ej: Rangos 2026"
                   style="width:100%; padding:8px 10px; border:1px solid #ddd6fe; border-radius:8px; font-size:13px; outline:none;">
            @error('nombre')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <label style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Vigencia desde</label>
                <input wire:model="fechaInicio" type="date"
                       style="width:100%; padding:8px 10px; border:1px solid #ddd6fe; border-radius:8px; font-size:13px; outline:none;">
                @error('fechaInicio')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Vigencia hasta <span style="font-weight:400; color:#9ca3af;">(vacío = abierto)</span></label>
                <input wire:model="fechaFin" type="date"
                       style="width:100%; padding:8px 10px; border:1px solid #ddd6fe; border-radius:8px; font-size:13px; outline:none;">
                @error('fechaFin')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
            <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#6d28d9;">Umbrales mínimos por calificación</span>
            <div style="flex:1; height:1px; background:#ddd6fe;"></div>
        </div>
        @error('minA')<p style="font-size:11px; color:#B91C1C; margin:-6px 0 8px;">{{ $message }}</p>@enderror

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            @foreach([
                ['label'=>'A — Excelente',   'model'=>'minA', 'bg'=>'#DCFCE7','cl'=>'#15803D'],
                ['label'=>'B — Buena',        'model'=>'minB', 'bg'=>'#ECFEFF','cl'=>'#0e7490'],
                ['label'=>'C — Observada',    'model'=>'minC', 'bg'=>'#FEF3C7','cl'=>'#854F0B'],
                ['label'=>'D — Riesgosa',     'model'=>'minD', 'bg'=>'#FFF7ED','cl'=>'#C2410C'],
            ] as $campo)
            <div>
                <label style="font-size:11px; font-weight:700; color:{{ $campo['cl'] }}; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">
                    Puntaje mínimo {{ $campo['label'] }}
                </label>
                <div style="position:relative;">
                    <input wire:model.live="{{ $campo['model'] }}" type="number" min="0" max="100" step="0.5"
                           style="width:100%; padding:8px 10px; border:1.5px solid {{ $campo['bg'] }}; border-radius:8px; font-size:14px; font-weight:700; outline:none; background:{{ $campo['bg'] }}; color:{{ $campo['cl'] }};">
                </div>
            </div>
            @endforeach

            <div style="display:flex; align-items:center; gap:8px; padding-top:22px;">
                <input wire:model="activo" type="checkbox" id="activo-rc" style="width:16px; height:16px; accent-color:#7c3aed; cursor:pointer;">
                <label for="activo-rc" style="font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Activo</label>
            </div>
        </div>

        {{-- Preview rangos --}}
        <div style="background:#faf5ff; border:1px solid #ddd6fe; border-radius:10px; padding:12px; font-size:11px; color:#6d28d9;">
            <p style="font-weight:700; margin:0 0 6px;">Vista previa de rangos:</p>
            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                <span style="padding:3px 10px; border-radius:10px; background:#DCFCE7; color:#15803D; font-weight:700;">A ≥ {{ $minA }}</span>
                <span style="padding:3px 10px; border-radius:10px; background:#ECFEFF; color:#0e7490; font-weight:700;">B ≥ {{ $minB }}</span>
                <span style="padding:3px 10px; border-radius:10px; background:#FEF3C7; color:#854F0B; font-weight:700;">C ≥ {{ $minC }}</span>
                <span style="padding:3px 10px; border-radius:10px; background:#FFF7ED; color:#C2410C; font-weight:700;">D ≥ {{ $minD }}</span>
                <span style="padding:3px 10px; border-radius:10px; background:#FEF2F2; color:#B91C1C; font-weight:700;">BLOQUEADO &lt; {{ $minD }}</span>
            </div>
        </div>

        <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                style="width:100%; background:#7c3aed; color:#fff; border:none; border-radius:9px; padding:11px; font-size:13px; font-weight:700; cursor:pointer; margin-top:8px;">
            <span wire:loading.remove wire:target="save">Guardar configuración</span>
            <span wire:loading wire:target="save">Guardando...</span>
        </button>
    </div>
</div>
@endif
</div>
