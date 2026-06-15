<div>

@if (session('success'))
<div style="background:#F0FDF4; color:#15803D; border:1px solid #BBF7D0; border-radius:10px; padding:10px 16px; margin-bottom:14px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

<div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-5">
    <div class="relative flex-1" style="min-width:0;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
    </div>
    <select wire:model.live="filtroActivo"
            style="height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; color:#374151; cursor:pointer;">
        <option value="">Todos</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
    <button wire:click="create"
            style="height:36px; padding:0 16px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; white-space:nowrap;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo
    </button>
</div>

{{-- DESKTOP --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 200px);">
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">{{ $titulo }}</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $items->total() }}</span>
    </div>
    <div style="overflow:auto; flex:1;">
    @php
        $th = 'padding:9px 14px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9F8FF; border-bottom:2px solid #EDE9FE;';
        $td = 'padding:9px 14px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB;';
    @endphp
    <table style="width:100%; border-collapse:collapse; min-width:600px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr>
                <th style="{{ $th }} color:#C4B5FD; width:40px; text-align:center;">#</th>
                <th style="{{ $th }}">Código</th>
                <th style="{{ $th }}">Nombre</th>
                <th style="{{ $th }}">Descripción</th>
                <th style="{{ $th }} text-align:center;">Orden</th>
                <th style="{{ $th }} text-align:center;">Estado</th>
                <th style="{{ $th }} text-align:center;">Modificado por</th>
                <th style="{{ $th }} text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($items as $item)
        <tr wire:key="def-{{ $item->id }}"
            style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''"
            x-data>
            <td style="{{ $td }} text-align:center; font-size:11px; font-weight:700; color:#C4B5FD;">{{ $items->firstItem() + $loop->index }}</td>
            <td style="{{ $td }}">
                <span style="font-family:monospace; font-size:12px; font-weight:700; color:#111827; background:#F3F4F6; padding:2px 8px; border-radius:6px;">{{ $item->codigo }}</span>
            </td>
            <td style="{{ $td }} font-weight:600; color:#111827;">{{ $item->nombre }}</td>
            <td style="{{ $td }} color:#6B7280; max-width:260px; overflow:hidden; text-overflow:ellipsis;">{{ $item->descripcion ?? '—' }}</td>
            <td style="{{ $td }} text-align:center; color:#6B7280;">{{ $item->sort_order }}</td>
            <td style="{{ $td }} text-align:center;">
                @if ($item->activo)
                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#D1FAE5; color:#065F46;">Activo</span>
                @else
                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#F3F4F6; color:#6B7280;">Inactivo</span>
                @endif
            </td>
            <td style="{{ $td }} text-align:center; font-size:12px; color:#6B7280;">{{ $item->modificadoPor?->name ?? '—' }}</td>
            <td style="{{ $td }} text-align:center;">
                <div style="display:inline-flex; gap:6px;">
                    <button wire:click="edit({{ $item->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'"
                            title="Editar">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="toggleActivo({{ $item->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid {{ $item->activo ? '#FEE2E2' : '#D1FAE5' }}; background:{{ $item->activo ? '#FEF2F2' : '#ECFDF5' }}; color:{{ $item->activo ? '#B91C1C' : '#059669' }}; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                            title="{{ $item->activo ? 'Desactivar' : 'Activar' }}">
                        @if ($item->activo)
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        @else
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="padding:56px 24px; text-align:center;">
                <svg style="width:40px; height:40px; color:#E5E7EB; margin:0 auto 10px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin registros</p>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
    @if ($items->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $items->links() }}</div>
    @endif
</div>

{{-- MOBILE --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($items as $item)
    <div wire:key="def-m-{{ $item->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px;">
            <span style="font-family:monospace; font-size:12px; font-weight:700; color:#111827; background:#F3F4F6; padding:3px 8px; border-radius:6px; flex-shrink:0;">{{ $item->codigo }}</span>
            <span style="font-size:14px; font-weight:700; color:#111827; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item->nombre }}</span>
            @if ($item->activo)
            <span style="padding:2px 8px; border-radius:99px; font-size:11px; font-weight:600; background:#D1FAE5; color:#065F46; flex-shrink:0;">Activo</span>
            @else
            <span style="padding:2px 8px; border-radius:99px; font-size:11px; font-weight:600; background:#F3F4F6; color:#6B7280; flex-shrink:0;">Inactivo</span>
            @endif
        </div>
        @if ($item->descripcion)
        <div style="padding:0 14px 10px; font-size:12px; color:#6B7280;">{{ $item->descripcion }}</div>
        @endif
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:6px;">
            <button wire:click="edit({{ $item->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer;">
                Editar
            </button>
            <button wire:click="toggleActivo({{ $item->id }})"
                    style="flex:1; height:32px; border:1px solid {{ $item->activo ? '#FEE2E2' : '#D1FAE5' }}; border-radius:8px; background:{{ $item->activo ? '#FEF2F2' : '#ECFDF5' }}; color:{{ $item->activo ? '#B91C1C' : '#059669' }}; font-size:12px; font-weight:600; cursor:pointer;">
                {{ $item->activo ? 'Desactivar' : 'Activar' }}
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:56px 24px;">
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin registros</p>
    </div>
    @endforelse
    @if ($items->hasPages())
    <div style="padding-top:8px;">{{ $items->links() }}</div>
    @endif
</div>

@endif

{{-- ══ FORM ══ --}}
@if ($mode === 'form')

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; max-width:560px;">
    <div style="padding:14px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px;">
        <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0;">{{ $editId ? 'Editar' : 'Nuevo' }} — {{ $titulo }}</p>
        </div>
    </div>

    <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">
        @php $lbl = 'font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6B7280; display:block; margin-bottom:5px;'; @endphp
        @php $inp = 'width:100%; height:38px; padding:0 12px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#fff; box-sizing:border-box;'; @endphp

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <div>
                <label style="{{ $lbl }}">Código <span style="color:#EF4444;">*</span></label>
                <input wire:model="codigo" type="text" placeholder="Ej: LLAMADA" style="{{ $inp }} text-transform:uppercase;">
                @error('codigo') <p style="font-size:12px; color:#EF4444; margin-top:4px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $lbl }}">Orden visualización</label>
                <input wire:model="sortOrder" type="number" min="0" style="{{ $inp }}">
            </div>
        </div>

        <div>
            <label style="{{ $lbl }}">Nombre <span style="color:#EF4444;">*</span></label>
            <input wire:model="nombre" type="text" placeholder="Nombre descriptivo" style="{{ $inp }}">
            @error('nombre') <p style="font-size:12px; color:#EF4444; margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label style="{{ $lbl }}">Descripción <span style="font-weight:400; color:#9CA3AF;">(opcional)</span></label>
            <textarea wire:model="descripcion" rows="2" placeholder="Descripción adicional..."
                      style="width:100%; padding:9px 12px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box;"></textarea>
        </div>

        <div style="display:flex; align-items:center; gap:10px;">
            <input wire:model="activo" type="checkbox" id="activo-chk" style="accent-color:#7B6FE8; width:16px; height:16px; cursor:pointer;">
            <label for="activo-chk" style="font-size:13px; font-weight:600; color:#374151; cursor:pointer;">Activo</label>
        </div>
    </div>

    <div style="padding:14px 20px 16px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px;">
        <button wire:click="backToList"
                style="height:36px; padding:0 16px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">
            Cancelar
        </button>
        <button wire:click="save" wire:loading.attr="disabled"
                style="height:36px; padding:0 20px; border:none; border-radius:8px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;">
            <span wire:loading.remove wire:target="save">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </span>
            <span wire:loading wire:target="save">
                <svg class="animate-spin" width="14" height="14" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            </span>
            Guardar
        </button>
    </div>
</div>

@endif

</div>
