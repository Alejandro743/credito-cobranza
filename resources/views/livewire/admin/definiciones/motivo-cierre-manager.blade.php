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
    <span style="font-size:12px; color:#9ca3af;">{{ $registros->count() }} motivo(s)</span>
    <button wire:click="create"
            style="padding:6px 16px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; border:none; background:#7c3aed; color:#fff; display:inline-flex; align-items:center; gap:6px;">
        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Nuevo Motivo
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
<div style="overflow-x:auto;">
<table style="border-collapse:separate; border-spacing:0; width:100%; min-width:500px; font-size:12px;">
    <thead style="{{ $thead }}">
        <tr>
            <th style="padding:8px 12px; text-align:left; border:0.5px solid #ddd6fe;">Nombre</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:140px;">Afecta Indicadores</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:90px;">Estado</th>
            <th style="padding:8px 12px; text-align:center; border:0.5px solid #ddd6fe; width:80px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse($registros as $r)
    <tr wire:key="mc-{{ $r->id }}">
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-weight:600; color:#374151;">{{ $r->nombre }}</td>
        <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
            @if($r->afecta_mora)
            <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FEF2F2; color:#B91C1C;">Sí afecta</span>
            @else
            <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#F0FDF4; color:#15803D;">No afecta</span>
            @endif
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
                <button wire:click="delete({{ $r->id }})" wire:confirm="¿Eliminar este motivo?"
                        style="padding:4px; border-radius:6px; border:1px solid #fecaca; background:#fef2f2; color:#B91C1C; cursor:pointer;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="4" style="padding:40px; text-align:center; color:#9ca3af;">Sin motivos configurados.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
</div>

@elseif($mode === 'form')
<div class="max-w-md mx-auto">
    <div style="background:#faf5ff; border:1px solid #ddd6fe; border-radius:14px; padding:16px 18px; margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <button wire:click="backToList"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #ddd6fe; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer;">
                <svg width="14" height="14" fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/></svg>
                <span style="font-size:11px; font-weight:700; color:#7c3aed;">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:18px; font-weight:800; color:#6d28d9; margin:0;">
                {{ $editId ? 'Editar' : 'Nuevo' }} Motivo de Cierre
            </h1>
            <div style="width:60px;"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">

        <div>
            <label style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Nombre *</label>
            <input wire:model="nombre" type="text" placeholder="Ej: Fallecimiento"
                   style="width:100%; padding:8px 10px; border:1px solid #ddd6fe; border-radius:8px; font-size:13px; outline:none;">
            @error('nombre')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; padding:12px; border-radius:10px; border:1px solid #FEE2E2; background:#FEF2F2;">
                <input wire:model="afectaMora" type="checkbox" style="width:16px; height:16px; accent-color:#B91C1C; cursor:pointer; flex-shrink:0;">
                <div>
                    <span style="font-size:13px; font-weight:600; color:#B91C1C; display:block;">Afecta indicadores de calificación</span>
                    <span style="font-size:11px; color:#9ca3af;">El cierre con este motivo se contabiliza en la calificación del cliente</span>
                </div>
            </label>

            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input wire:model="activo" type="checkbox" style="width:16px; height:16px; accent-color:#7c3aed; cursor:pointer;">
                <span style="font-size:13px; font-weight:600; color:#374151;">Activo</span>
            </label>
        </div>

        <button wire:click="save" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                style="width:100%; background:#7c3aed; color:#fff; border:none; border-radius:9px; padding:11px; font-size:13px; font-weight:700; cursor:pointer; margin-top:8px;">
            <span wire:loading.remove wire:target="save">Guardar motivo</span>
            <span wire:loading wire:target="save">Guardando...</span>
        </button>
    </div>
</div>
@endif
</div>
