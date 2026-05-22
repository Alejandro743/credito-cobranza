<div>

{{-- Flash success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35); display:flex; align-items:center; gap:8px;">
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
<div style="display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap;">

    <div style="position:relative; flex:1; min-width:200px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#9CA3AF;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o descripción..."
               style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:9px; padding:0 12px 0 32px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;">
    </div>

    <select wire:model.live="filterStatus"
            style="height:36px; border:1px solid #E5E7EB; border-radius:9px; padding:0 12px; font-size:13px; color:#374151; background:#fff; outline:none; cursor:pointer;">
        <option value="">Todos los estados</option>
        <option value="abierto">Abierto</option>
        <option value="cerrado">Cerrado</option>
    </select>

    <button wire:click="showAdd"
            style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; white-space:nowrap;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
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
                        style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                    <span wire:loading.remove wire:target="saveNew">Guardar</span>
                    <span wire:loading wire:target="saveNew">Guardando...</span>
                </button>
                <button wire:click="cancelAdd"
                        style="height:38px; padding:0 18px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
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
                style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
            <span wire:loading.remove wire:target="saveNew">Guardar</span>
            <span wire:loading wire:target="saveNew">Guardando...</span>
        </button>
        <button wire:click="cancelAdd"
                style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
            Cancelar
        </button>
    </div>
</div>
@endif

{{-- ══════ TABLA ══════ --}}
@php
$iRow = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
@endphp

<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Ciclos Comerciales</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $cycles->total() }}</span>
    </div>

    <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; min-width:640px;">
        <thead>
            <tr style="background:#F8F7FF;">
                <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:120px;">Código</th>
                <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Descripción</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:110px;">Inicio</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:110px;">Fin</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:120px;">Estado</th>
                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; width:160px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($cycles as $cycle)

        @if($editingId === $cycle->id)
        {{-- ── FILA EDICIÓN INLINE ── --}}
        <tr wire:key="edit-{{ $cycle->id }}" style="background:#FAFAFE; border-bottom:1px solid #EDE9FE;">
            <td style="padding:10px 16px;">
                <input wire:model="editCode" type="text" maxlength="30"
                       style="width:100%; {{ $iRow }} text-transform:uppercase;">
                @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <input wire:model="editName" type="text" placeholder="Descripción"
                       style="width:100%; {{ $iRow }}">
                @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <input wire:model="editStartDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editStartDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <input wire:model="editEndDate" type="date" style="width:100%; {{ $iRow }}">
                @error('editEndDate')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <select wire:model="editStatus" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="abierto">Abierto</option>
                    <option value="cerrado">Cerrado</option>
                </select>
                @error('editStatus')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </td>
            <td style="padding:10px 16px;">
                <div style="display:flex; gap:6px; justify-content:center;">
                    <button wire:click="saveEdit" wire:loading.attr="disabled"
                            style="height:34px; padding:0 16px; border-radius:7px; border:none; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                            @mouseenter="$el.style.opacity='.85'" @mouseleave="$el.style.opacity='1'">
                        <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                        <span wire:loading wire:target="saveEdit">...</span>
                    </button>
                    <button wire:click="cancelEdit"
                            style="height:34px; padding:0 14px; border-radius:7px; border:1px solid #E5E7EB; background:#F3F4F6; color:#6B7280; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;"
                            @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                        Cerrar
                    </button>
                </div>
            </td>
        </tr>

        @else
        {{-- ── FILA NORMAL ── --}}
        <tr wire:key="c-{{ $cycle->id }}" style="border-bottom:1px solid #F9FAFB;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
            <td style="padding:11px 16px; font-size:13px; font-weight:700; color:#7B6FE8; font-family:monospace;">{{ $cycle->code }}</td>
            <td style="padding:11px 16px; font-size:13px; font-weight:600; color:#111827;">{{ $cycle->name }}</td>
            <td style="padding:11px 16px; text-align:center; font-size:13px; color:#6B7280;">{{ $cycle->start_date->format('d/m/Y') }}</td>
            <td style="padding:11px 16px; text-align:center; font-size:13px; color:#6B7280;">{{ $cycle->end_date->format('d/m/Y') }}</td>
            <td style="padding:11px 16px; text-align:center;">
                @if($cycle->status === 'abierto')
                <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; background:#D1FAE5; color:#059669;">Abierto</span>
                @else
                <span style="font-size:11px; font-weight:600; padding:3px 10px; border-radius:99px; background:#F3F4F6; color:#9CA3AF;">Cerrado</span>
                @endif
            </td>
            <td style="padding:11px 16px; text-align:center;">
                <div style="display:flex; gap:6px; justify-content:center;">
                    <button wire:click="startEdit({{ $cycle->id }})" title="Editar"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                </div>
            </td>
        </tr>
        @endif

        @empty
        <tr><td colspan="6" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">No hay ciclos registrados.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>

    @if($cycles->hasPages())
    <div style="padding:12px 18px; border-top:1px solid #F3F4F6;">
        {{ $cycles->links() }}
    </div>
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
                    style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                <span wire:loading.remove wire:target="saveEdit">Guardar</span>
                <span wire:loading wire:target="saveEdit">Guardando...</span>
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
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
                    style="flex:1; height:34px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
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

</div>
