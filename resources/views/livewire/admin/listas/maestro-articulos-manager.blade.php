<div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

<div class="hidden sm:block">
@if(!$showAddForm)
<div style="display:flex; justify-content:flex-start; margin-bottom:8px;">
    <button wire:click="showAdd"
            style="height:32px; padding:0 14px; display:inline-flex; align-items:center; gap:5px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; font-size:12px; font-weight:700; color:#7B6FE8; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo artículo
    </button>
</div>
@endif
</div>

{{-- ══ FORM: Nuevo artículo ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8;">Nuevo Artículo Maestro</span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; flex-wrap:wrap; gap:12px;">
        <div style="min-width:100px; max-width:140px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
            <input wire:model="newCodigo" type="text" maxlength="50" placeholder="Ej. ART001"
                   style="{{ $iS }} font-family:monospace; text-transform:uppercase;">
            @error('newCodigo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:2; min-width:180px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
            <input wire:model="newNombre" type="text" maxlength="255" placeholder="Nombre del artículo"
                   style="{{ $iS }}">
            @error('newNombre') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:140px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Categoría</label>
            <select wire:model="newCategoriaId" style="{{ $iS }} cursor:pointer; padding:0 8px;">
                <option value="">— Sin categoría —</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:130px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Unidad</label>
            <select wire:model="newUnidadId" style="{{ $iS }} cursor:pointer; padding:0 8px;">
                <option value="">— Sin unidad —</option>
                @foreach($unidades as $uni)
                <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:100px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
            <select wire:model="newActive" style="{{ $iS }} cursor:pointer; padding:0 8px;">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div style="padding-top:21px; display:flex; gap:8px;">
            <button wire:click="saveNew"
                    style="height:38px; padding:0 24px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:38px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Artículos maestros</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $articulos->total() }}</span>
        @if($selectedId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:10px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedId)
                <button wire:click="saveEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>Guardar</button>
                <button wire:click="cancelEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>Cancelar</button>
            @else
                <button wire:click="startEdit({{ $selectedId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>Editar</button>
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
        @php
            $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
            $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
            $fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
            $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
            $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
            $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
        @endphp
        <table style="width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'pointer-events:none;' : '' }}">

                    {{-- # --}}
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF; box-shadow:inset -1px 0 0 #E5E7EB;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedId !== null"
                                   :disabled="$wire.selectedId === null"
                                   @click.prevent="$wire.selectedId !== null && $wire.set('selectedId', null)"
                                   :style="$wire.selectedId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'active'; @endphp
                    <th wire:click="toggleSort('active')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
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
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </th>

                    {{-- Código --}}
                    @php $isA = $sortBy === 'codigo'; @endphp
                    <th wire:click="toggleSort('codigo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:100px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
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

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'nombre'; @endphp
                    <th wire:click="toggleSort('nombre')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:180px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }} text-align:center;"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Categoría --}}
                    @php $isA = $sortBy === 'categoria_id'; @endphp
                    <th wire:click="toggleSort('categoria_id')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Categoría
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterCategoriaId" @click.stop style="{{ $fS }} text-indent:0; padding-left:16px;">
                                <option value="">Todas</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Unidad --}}
                    @php $isA = $sortBy === 'unidad_id'; @endphp
                    <th wire:click="toggleSort('unidad_id')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Unidad
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterUnidadId" @click.stop style="{{ $fS }} text-indent:0; padding-left:16px;">
                                <option value="">Todas</option>
                                @foreach($unidades as $uni)
                                    <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($articulos as $a)

                {{-- Fila edición --}}
                @if ($editingId === $a->id)
                @php $eI = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; width:100%;'; @endphp
                <tr wire:key="edit-{{ $a->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:#F8F7FF; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $articulos->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 10px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model="editActive" wire:key="edit-active-{{ $a->id }}" x-init="$el.value = @js($editActive ? '1' : '0')" style="{{ $eI }} padding:0 6px; cursor:pointer;">
                            <option value="1" @selected($editActive)>Activo</option>
                            <option value="0" @selected(!$editActive)>Inactivo</option>
                        </select>
                    </td>
                    <td style="padding:7px 10px; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editCodigo" type="text" maxlength="50" style="{{ $eI }} font-family:monospace; text-transform:uppercase; text-align:center;">
                        @error('editCodigo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; min-width:180px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <input wire:model="editNombre" type="text" maxlength="255" style="{{ $eI }} text-align:center;">
                        @error('editNombre') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <select wire:model="editCategoriaId" style="{{ $eI }} padding:0 6px; cursor:pointer;">
                            <option value="">— —</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px; min-width:120px;">
                        <select wire:model="editUnidadId" style="{{ $eI }} padding:0 6px; cursor:pointer;">
                            <option value="">— —</option>
                            @foreach($unidades as $uni)
                            <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $sel = $selectedId === $a->id; @endphp
                <tr wire:key="a-{{ $a->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s; background:{{ $sel ? '#F5F3FF' : '' }}; {{ $sel ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $sel ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $sel ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $sel ? '#F5F3FF' : '#fff' }}; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedId === {{ $a->id }}"
                                   @click="$wire.selectedId === {{ $a->id }} ? $wire.set('selectedId', null) : $wire.selectArticulo({{ $a->id }})"
                                   :disabled="{{ $editingId && $editingId !== $a->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $a->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $articulos->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:13px; font-weight:700; color:#374151; white-space:nowrap;">{{ $a->active ? 'Activo' : 'Inactivo' }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $a->codigo }}</span>
                    </td>

                    <td style="padding:10px 14px; min-width:180px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                        <span style="font-size:13px; font-weight:400; color:#374151;">{{ $a->nombre }}</span>
                        @if($a->descripcion)
                        <p style="font-size:11px; color:#9CA3AF; margin:2px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">{{ $a->descripcion }}</p>
                        @endif
                    </td>

                    <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                        @if($a->categoria)
                        <span style="font-size:13px; font-weight:400; color:#374151;">{{ $a->categoria->descripcion }}</span>
                        @else
                        <span style="color:#D1D5DB; font-size:13px;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        @if($a->unidad)
                        <span style="font-size:13px; font-weight:400; color:#374151;">{{ $a->unidad->name }}</span>
                        @else
                        <span style="color:#D1D5DB; font-size:13px;">—</span>
                        @endif
                    </td>

                </tr>
                @endif

                @empty
                <tr>
                    <td colspan="7" style="padding:48px;">
                        <p style="text-align:center; color:#9CA3AF; font-size:13px; margin:0;">
                            {{ $colFilterCodigo || $colFilterNombre || $colFilterCategoriaId || $colFilterEstado !== '' ? 'Sin resultados para los filtros aplicados.' : 'No hay artículos maestros registrados.' }}
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($articulos->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $articulos->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE: Cards ══ --}}
@php $iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($articulos as $a)

    @if ($editingId === $a->id)
    <div wire:key="card-edit-{{ $a->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Editando artículo</span>
            <button wire:click="cancelEdit" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
                    <input wire:model="editCodigo" type="text" maxlength="50" style="{{ $iM }} font-family:monospace;">
                    @error('editCodigo')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editNombre" type="text" style="{{ $iM }}">
                    @error('editNombre')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Categoría</label>
                    <select wire:model="editCategoriaId" style="{{ $iM }} cursor:pointer; padding:0 8px;">
                        <option value="">— —</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->descripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Unidad</label>
                    <select wire:model="editUnidadId" style="{{ $iM }} cursor:pointer; padding:0 8px;">
                        <option value="">— —</option>
                        @foreach($unidades as $uni)
                        <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" wire:key="card-edit-active-{{ $a->id }}" x-init="$el.value = @js($editActive ? '1' : '0')" style="{{ $iM }} cursor:pointer; padding:0 8px;">
                    <option value="1" @selected($editActive)>Activo</option>
                    <option value="0" @selected(!$editActive)>Inactivo</option>
                </select>
            </div>
            <div style="display:flex; gap:8px; padding-top:2px;">
                <button wire:click="saveEdit"
                        style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>Guardar</button>
                <button wire:click="cancelEdit"
                        style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>Cancelar</button>
            </div>
        </div>
    </div>

    @else
    <div wire:key="card-{{ $a->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                @if($a->foto_url)
                <img src="{{ $a->foto_url }}" alt="{{ $a->nombre }}"
                     style="width:30px; height:30px; object-fit:cover;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8; display:none;">{{ mb_strtoupper(mb_substr($a->nombre, 0, 1)) }}</span>
                @else
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_strtoupper(mb_substr($a->nombre, 0, 1)) }}</span>
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $a->nombre }}</p>
                <p style="font-size:11px; font-family:monospace; color:#7B6FE8; font-weight:600; margin:2px 0 0;">{{ $a->codigo }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $a->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $a->active ? '#059669' : '#9CA3AF' }};">
                {{ $a->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div style="padding:8px 14px; display:flex; gap:16px; flex-wrap:wrap; border-bottom:1px solid #F3F4F6;">
            @if($a->categoria)
            <span style="font-size:12px; color:#6B7280;">{{ $a->categoria->descripcion }}</span>
            @endif
            @if($a->unidad)
            <span style="font-size:12px; color:#6B7280;">{{ $a->unidad->name }}</span>
            @endif
        </div>
        <div style="padding:10px 14px;">
            <button wire:click="startEdit({{ $a->id }})"
                    style="width:100%; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">{{ $colFilterCodigo || $colFilterNombre || $colFilterCategoriaId || $colFilterEstado !== '' ? 'Sin resultados para los filtros aplicados.' : 'No hay artículos maestros registrados.' }}</p>
    @endforelse
    @if ($articulos->hasPages())
    <div style="padding-top:8px;">{{ $articulos->links() }}</div>
    @endif
</div>

</div>
