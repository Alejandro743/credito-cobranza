<div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif


{{-- ══ FORM: Nueva categoría ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Nueva Categoría
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div style="min-width:110px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
            <input wire:model="newCode" type="text" placeholder="Ej. ELEC"
                   style="width:100%; {{ $iS }} font-family:monospace; text-transform:uppercase;">
            @error('newCode') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:160px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
            <input wire:model="newName" type="text" placeholder="Ej. Electrónica"
                   style="width:100%; {{ $iS }}">
            @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:160px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Descripción</label>
            <input wire:model="newDescripcion" type="text" placeholder="Opcional"
                   style="width:100%; {{ $iS }}">
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; margin-bottom:5px;">·</label>
            <div style="display:flex; gap:8px;">
                <button wire:click="saveNew"
                        style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                    Guardar
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
@endif

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block">
@if(!$showAddForm)
<div style="display:flex; justify-content:flex-start; margin-bottom:8px;">
    <button wire:click="showAdd"
            style="height:32px; padding:0 14px; display:inline-flex; align-items:center; gap:5px; border:none; border-radius:8px; background:#7B6FE8; font-size:12px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva categoría
    </button>
</div>
@endif
</div>
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-wrap:wrap;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Categorías registradas</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $categorias->total() }}</span>
        @if($selectedCategoriaId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedCategoriaId)
                <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedCategoriaId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        @php
            $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
            $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
            $fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
            $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
            $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
            $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
        @endphp
        <table style="width:100%; min-width:600px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'pointer-events:none;' : '' }}">

                    {{-- # --}}
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedCategoriaId !== null"
                                   :disabled="$wire.selectedCategoriaId === null"
                                   @click.prevent="$wire.selectedCategoriaId !== null && $wire.set('selectedCategoriaId', null)"
                                   :style="$wire.selectedCategoriaId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Código --}}
                    @php $isA = $sortBy === 'code'; @endphp
                    <th wire:click="toggleSort('code')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:100px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Código
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCodigo" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'name'; @endphp
                    <th wire:click="toggleSort('name')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:160px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Descripción --}}
                    @php $isA = $sortBy === 'descripcion'; @endphp
                    <th wire:click="toggleSort('descripcion')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:160px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Descripción
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterDescripcion" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
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
                                <option value="1">Activa</option>
                                <option value="0">Inactiva</option>
                            </select>
                        </div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $cat)

                {{-- Fila edición inline --}}
                @if ($editingId === $cat->id)
                <tr wire:key="edit-{{ $cat->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#F8F7FF;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $categorias->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editCode" type="text"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; font-family:monospace; text-transform:uppercase;">
                        @error('editCode') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editName" type="text" placeholder="Nombre *"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editDescripcion" type="text" placeholder="Descripción"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                    </td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editActive"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selC = $selectedCategoriaId === $cat->id; @endphp
                <tr wire:key="cat-{{ $cat->id }}"
                    style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $selC ? '#F5F3FF' : '' }}; {{ $selC ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selC ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selC ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selC ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedCategoriaId === {{ $cat->id }}"
                                   @click="$wire.selectedCategoriaId === {{ $cat->id }} ? $wire.set('selectedCategoriaId', null) : $wire.selectCategoria({{ $cat->id }})"
                                   :disabled="{{ $editingId && $editingId !== $cat->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $cat->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $categorias->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $cat->code }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($cat->name)) }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $cat->descripcion ? ucwords(strtolower($cat->descripcion)) : '—' }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $cat->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $cat->active ? '#059669' : '#9CA3AF' }};">
                            {{ $cat->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>

                </tr>
                @endif

                @empty
                <tr><td colspan="5" style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">{{ $colFilterCodigo || $colFilterNombre || $colFilterDescripcion || $colFilterEstado ? 'Sin resultados para los filtros aplicados.' : 'No hay categorías registradas.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categorias->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $categorias->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE: Cards ══ --}}
@php $iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($categorias as $cat)

    @if ($editingId === $cat->id)
    {{-- CARD EDICIÓN --}}
    <div wire:key="card-edit-{{ $cat->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">

        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Editando categoría</span>
            <button wire:click="cancelEdit" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
                    <input wire:model="editCode" type="text" style="{{ $iM }} font-family:monospace; text-transform:uppercase;">
                    @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editName" type="text" style="{{ $iM }}">
                    @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Descripción</label>
                    <input wire:model="editDescripcion" type="text" placeholder="Opcional" style="{{ $iM }}">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                    <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:8px; padding-top:2px;">
                <button wire:click="saveEdit"
                        style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                    Guardar
                </button>
                <button wire:click="cancelEdit"
                        style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    @else
    {{-- CARD NORMAL --}}
    <div wire:key="card-{{ $cat->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_strtoupper(mb_substr($cat->name, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $cat->name }}</p>
                <p style="font-size:11px; font-family:monospace; color:#7B6FE8; font-weight:600; margin:2px 0 0;">{{ $cat->code }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $cat->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $cat->active ? '#059669' : '#9CA3AF' }};">
                {{ $cat->active ? 'Activa' : 'Inactiva' }}
            </span>
        </div>

        @if($cat->descripcion)
        <div style="padding:8px 14px; border-bottom:1px solid #F3F4F6;">
            <p style="font-size:12px; color:#6B7280; margin:0;">{{ $cat->descripcion }}</p>
        </div>
        @endif

        <div style="padding:10px 14px; display:flex; gap:7px;">
            <button wire:click="startEdit({{ $cat->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay categorías registradas.</p>
    @endforelse
    @if ($categorias->hasPages())
    <div style="padding-top:8px;">{{ $categorias->links() }}</div>
    @endif
</div>

<script>
if (!window.colResize) {
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
}
</script>

</div>
