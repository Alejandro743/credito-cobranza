<div>

{{-- Flash success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35); display:flex; align-items:center; gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ══════ FORM PANEL INLINE (desktop) ══════ --}}
@if($showForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">

    {{-- Header panel --}}
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8;">
            {{ $editId ? 'Editar motivo de cierre' : 'Nuevo motivo de cierre' }}
        </span>
        <button wire:click="cancelar"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Form body — fila única en desktop --}}
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:20px; flex-wrap:wrap;">

        {{-- Nombre --}}
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
            <input wire:model="nombre" type="text" placeholder="Ej: Fallecimiento"
                   style="width:100%; height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;">
            @error('nombre')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Afecta mora --}}
        <div style="display:flex; flex-direction:column; justify-content:flex-end; padding-bottom:1px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Afecta indicadores</label>
            <label style="display:flex; align-items:center; gap:8px; height:38px; cursor:pointer; padding:0 14px; border:1px solid #FEE2E2; background:#FEF2F2; border-radius:8px; white-space:nowrap;">
                <input wire:model="afectaMora" type="checkbox" style="width:15px; height:15px; accent-color:#DC2626; cursor:pointer; flex-shrink:0;">
                <span style="font-size:13px; font-weight:600; color:#DC2626;">Sí afecta</span>
            </label>
        </div>

        {{-- Activo --}}
        <div style="display:flex; flex-direction:column; justify-content:flex-end; padding-bottom:1px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
            <label style="display:flex; align-items:center; gap:8px; height:38px; cursor:pointer; padding:0 14px; border:1px solid #EDE9FE; background:#F8F7FF; border-radius:8px; white-space:nowrap;">
                <input wire:model="activo" type="checkbox" style="width:15px; height:15px; accent-color:#7B6FE8; cursor:pointer; flex-shrink:0;">
                <span style="font-size:13px; font-weight:600; color:#7B6FE8;">Activo</span>
            </label>
        </div>

        {{-- Guardar --}}
        <div style="display:flex; flex-direction:column; justify-content:flex-end; padding-bottom:1px;">
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">·</label>
            <button wire:click="save" wire:loading.attr="disabled"
                    style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>

    </div>
</div>
@endif

{{-- ══════ TOOLBAR ══════ --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <span style="font-size:12px; color:#9CA3AF; font-weight:500;">{{ $registros->count() }} motivo(s)</span>
    @if(!$showForm)
    <button wire:click="create"
            style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Motivo
    </button>
    @endif
</div>

{{-- ══════ TABLA ══════ --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Motivos de Cierre</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $registros->count() }}</span>
    </div>

    <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; min-width:480px;">
        <thead>
            <tr style="background:#F8F7FF;">
                <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:160px;">Afecta indicadores</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:100px;">Estado</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:80px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($registros as $r)
        <tr wire:key="mc-{{ $r->id }}" style="border-bottom:1px solid #F9FAFB;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
            <td style="padding:11px 16px; font-size:13px; font-weight:600; color:#111827;">{{ $r->nombre }}</td>
            <td style="padding:11px 16px; text-align:center;">
                @if($r->afecta_mora)
                <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; background:#FEE2E2; color:#DC2626;">Sí afecta</span>
                @else
                <span style="font-size:11px; font-weight:600; padding:3px 10px; border-radius:99px; background:#F3F4F6; color:#9CA3AF;">No afecta</span>
                @endif
            </td>
            <td style="padding:11px 16px; text-align:center;">
                <button wire:click="toggleActivo({{ $r->id }})"
                        style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; border:none; cursor:pointer;
                               background:{{ $r->activo ? '#D1FAE5' : '#F3F4F6' }};
                               color:{{ $r->activo ? '#059669' : '#9CA3AF' }};"
                        @mouseenter="$el.style.opacity='.75'" @mouseleave="$el.style.opacity='1'">
                    {{ $r->activo ? 'Activo' : 'Inactivo' }}
                </button>
            </td>
            <td style="padding:11px 16px; text-align:center;">
                <div style="display:flex; gap:6px; justify-content:center;">
                    <button wire:click="edit({{ $r->id }})" title="Editar"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="delete({{ $r->id }})" wire:confirm="¿Eliminar este motivo?" title="Eliminar"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #FECACA; background:#FEF2F2; color:#DC2626; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                            @mouseenter="$el.style.background='#FECACA'" @mouseleave="$el.style.background='#FEF2F2'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">Sin motivos configurados. Creá el primero.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

</div>
