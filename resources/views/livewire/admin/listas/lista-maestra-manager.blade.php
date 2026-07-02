<div>

@php
    $theadClass = match($moduleColor ?? '') {
        'lavanda'   => 'bg-lavanda-100 text-lavanda-700',
        'mint'      => 'bg-mint-100 text-mint-700',
        'melocoton' => 'bg-melocoton-100 text-melocoton-700',
        'celeste'   => 'bg-celeste-100 text-celeste-700',
        default     => 'bg-gray-50 text-gray-600',
    };
@endphp

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-20 sm:bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
     class="fixed bottom-20 sm:bottom-5 right-5 z-50 bg-red-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('error') }}
</div>
@endif

{{-- ═══════════════════════════════════════════════════════ ITEMS MODE ═══ --}}
@if ($mode === 'items' && $viewingMaestra)

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="padding:13px 18px;">
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <button wire:click="backToList"
                    style="width:34px; height:34px; border-radius:9px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div style="min-width:0;">
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin:0 0 2px;">Listado de artículos</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $viewingMaestra->name }}</p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <button wire:click="refreshFromCatalog"
                    style="height:36px; padding:0 14px; border:1px solid #E5E7EB; background:#fff; color:#6B7280; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualizar
            </button>
            <button wire:click="showAddItem"
                    style="height:36px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nuevo Producto
            </button>
        </div>
    </div>
</div>

{{-- Formulario nuevo producto --}}
@if ($showAddItemForm)
@php
    $iStyle = 'border:1px solid #EDE9FE; border-radius:8px; padding:7px 10px; font-size:13px; color:#374151; background:#fff; outline:none; width:100%;';
    $lStyle = 'display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:5px;';
@endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:16px; overflow:hidden;">
    {{-- Header --}}
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:11px 18px; display:flex; align-items:center; justify-content:space-between;">
        <p style="font-size:13px; font-weight:700; color:#5B21B6; margin:0; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Nuevo Producto
        </p>
        <button wire:click="cancelAddItem"
                style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:3px;"
                @mouseenter="$el.style.color='#6B7280'" @mouseleave="$el.style.color='#9CA3AF'">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    {{-- Body --}}
    <div style="padding:14px 18px;">
        <div style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:10px;">
            <div style="width:100px;">
                <label style="{{ $lStyle }}">Código *</label>
                <input wire:model="newItemCode" type="text" placeholder="PROD-001" style="{{ $iStyle }} font-family:monospace;">
                @error('newItemCode') <p style="font-size:10px; color:#ef4444; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:160px;">
                <label style="{{ $lStyle }}">Nombre *</label>
                <input wire:model="newItemNombre" type="text" placeholder="Nombre del producto" style="{{ $iStyle }}">
                @error('newItemNombre') <p style="font-size:10px; color:#ef4444; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:90px;">
                <label style="{{ $lStyle }}">Precio (Bs) *</label>
                <input wire:model="newItemPrecio" type="number" step="0.01" min="0" placeholder="0.00" style="{{ $iStyle }} text-align:center;">
                @error('newItemPrecio') <p style="font-size:10px; color:#ef4444; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:70px;">
                <label style="{{ $lStyle }}">Puntos</label>
                <input wire:model="newItemPuntos" type="number" min="0" placeholder="0" style="{{ $iStyle }} text-align:center;">
            </div>
            <div style="width:80px;">
                <label style="{{ $lStyle }}">Stock ini.</label>
                <input wire:model="newItemStock" type="number" step="0.01" min="0" placeholder="0" style="{{ $iStyle }} text-align:center;">
            </div>
            <div style="width:115px;">
                <label style="{{ $lStyle }}">Unidad</label>
                <select wire:model="newItemUnidadId" style="{{ $iStyle }} cursor:pointer;">
                    <option value="">— —</option>
                    @foreach ($unidades as $u)
                        <option value="{{ $u->id }}">{{ $u->abreviatura ?? $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width:130px;">
                <label style="{{ $lStyle }}">Categoría</label>
                <select wire:model="newItemCatId" style="{{ $iStyle }} cursor:pointer;">
                    <option value="">— —</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:8px; padding-bottom:2px;">
                <button wire:click="saveNewItem"
                        style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                    Guardar
                </button>
                <button wire:click="cancelAddItem"
                        style="height:36px; padding:0 14px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                        @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Tabla de productos (desktop) --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">
    {{-- Barra header --}}
    <div style="padding:10px 18px; display:flex; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Ítems del catálogo</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $products->count() }}</span>
        @php
            $btnH    = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;';
            $selProd = $selectedProductId ? $products->firstWhere('id', $selectedProductId) : null;
            $selItem = $selectedProductId ? $itemsMap->get($selectedProductId) : null;
            $selInLista = $selItem !== null;
        @endphp
        @if($selectedProductId)
        <div style="display:flex; align-items:center; gap:5px; margin-left:10px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($selInLista)
                <button wire:click="startEditItem({{ $selItem->id }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @else
                <button wire:click="openAgregarModal" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Agregar a lista
                </button>
            @endif
        </div>
        @endif
    </div>
    <div style="overflow:auto; flex:1;">
        <table style="width:100%; min-width:1600px; border-collapse:collapse;">
            <colgroup>
                <col style="width:44px;">
                <col style="width:90px;">
                <col style="width:300px;">
                <col style="width:100px;">
                <col style="width:70px;">
                <col style="width:85px;">
                <col style="width:85px;">
                <col style="width:85px;">
                <col style="width:85px;">
                <col style="width:95px;">
                <col style="width:95px;">
                <col style="width:105px;">
                <col style="width:135px;">
            </colgroup>
            <thead style="position:sticky; top:0; z-index:10;">
                @php
                    $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center;';
                    $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
                    $fW  = 'position:relative; margin-top:4px;';
                    $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
                    $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
                    $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; text-align:center; padding:8px 8px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top;';
                    $emptyF = '<div style="margin-top:4px; height:28px;"></div>';
                @endphp
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">

                    {{-- # --}}
                    <th style="width:44px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedProductId !== null"
                                   :disabled="$wire.selectedProductId === null"
                                   @click.prevent="$wire.selectedProductId !== null && $wire.set('selectedProductId', null)"
                                   :style="$wire.selectedProductId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Código --}}
                    @php $isA = $itemSortBy === 'code'; @endphp
                    <th wire:click="toggleItemSort('code')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Código
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="itemColFilterCodigo" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Producto --}}
                    @php $isA = $itemSortBy === 'name'; @endphp
                    <th wire:click="toggleItemSort('name')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Producto
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="itemColFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Precio Base --}}
                    @php $isA = $itemSortBy === 'precio_base'; @endphp
                    <th wire:click="toggleItemSort('precio_base')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Precio Base
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Tipo Inc --}}
                    @php $isA = $itemSortBy === 'tipo_incremento'; @endphp
                    <th wire:click="toggleItemSort('tipo_incremento')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Tipo Inc.
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="itemColFilterTipoInc" @click.stop style="{{ $fS }}">
                                <option value=""></option>
                                <option value="porcentaje">% Porcentaje</option>
                                <option value="monto_fijo">Bs Monto Fijo</option>
                            </select>
                        </div>
                    </th>

                    {{-- Incremento --}}
                    @php $isA = $itemSortBy === 'factor_incremento'; @endphp
                    <th wire:click="toggleItemSort('factor_incremento')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Incremento
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Precio Final --}}
                    @php $isA = $itemSortBy === 'precio_final'; @endphp
                    <th wire:click="toggleItemSort('precio_final')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Precio Final
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Puntos --}}
                    @php $isA = $itemSortBy === 'puntos'; @endphp
                    <th wire:click="toggleItemSort('puntos')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Puntos
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Stock Máx --}}
                    @php $isA = $itemSortBy === 'stock_max'; @endphp
                    <th wire:click="toggleItemSort('stock_max')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Stock Máx.
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Stock Inicial --}}
                    @php $isA = $itemSortBy === 'stock_inicial'; @endphp
                    <th wire:click="toggleItemSort('stock_inicial')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Stock Inicial
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Stock Comprometido --}}
                    @php $isA = $itemSortBy === 'stock_comprometido'; @endphp
                    <th wire:click="toggleItemSort('stock_comprometido')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Stock Comp.
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Stock Consumido --}}
                    @php $isA = $itemSortBy === 'stock_consumido'; @endphp
                    <th wire:click="toggleItemSort('stock_consumido')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Stock Cons.
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Stock Disponible --}}
                    @php $isA = $itemSortBy === 'stock_actual'; @endphp
                    <th wire:click="toggleItemSort('stock_actual')" style="{{ $thC }} cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Stock Disp.
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        {!! $emptyF !!}
                    </th>

                    {{-- Estado --}}
                    @php $isA = $itemSortBy === 'active'; @endphp
                    <th wire:click="toggleItemSort('active')" style="{{ $thC }} min-width:130px; cursor:pointer; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA ? 'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA ? 'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Estado
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $itemSortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="itemColFilterEnLista" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Habilitado</option>
                                <option value="0">Deshabilitado</option>
                            </select>
                        </div>
                    </th>


                </tr>
            </thead>
            <tbody>
                @forelse ($products as $p)
                @php $item = $itemsMap->get($p->id); $inLista = $item !== null; @endphp

                @php
                    $selProd  = $selectedProductId === $p->id;
                    $trBg     = $selProd ? '#F5F3FF' : ($inLista ? '#fff' : '#FFF1F2');
                    $trHover  = $selProd ? '#F5F3FF' : ($inLista ? '#FAFAFE' : '#FEE2E2');
                    $trStyle  = 'border-bottom:1px solid #F3F4F6;';
                    $tdBase   = 'padding:10px 12px; text-align:center; font-size:13px; color:#374151; font-weight:500; white-space:nowrap; text-transform:uppercase;';
                    $codeCol  = $inLista ? '#6B7280' : '#9CA3AF';
                    $nameCol  = $inLista ? '#111827' : '#9CA3AF';
                @endphp
                <tr wire:key="prod-{{ $p->id }}" style="{{ $trStyle }} background:{{ $trBg }};"
                    @mouseenter="$el.style.background='{{ $trHover }}'" @mouseleave="$el.style.background='{{ $trBg }}'">
                    <td class="col-row-num" style="padding:6px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap; background:{{ $trBg }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:5px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedProductId === {{ $p->id }}"
                                   @click="$wire.selectedProductId === {{ $p->id }} ? $wire.set('selectedProductId', null) : $wire.selectProduct({{ $p->id }})"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                            <span style="color:{{ $inLista ? '#374151' : '#9CA3AF' }};">{{ $loop->iteration }}</span>
                        </div>
                    </td>
                    <td style="{{ $tdBase }} font-family:monospace; font-size:12px; color:{{ $codeCol }};">{{ $p->code }}</td>
                    <td style="{{ $tdBase }} text-align:left; padding-left:14px; overflow:hidden; text-overflow:ellipsis; max-width:0; color:{{ $nameCol }}; {{ $inLista ? 'font-weight:600;' : '' }}">{{ ucwords(strtolower($p->name)) }}</td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista) Bs {{ number_format($item->precio_base, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista && $item->tipo_incremento)
                        <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">
                            {{ $item->tipo_incremento === 'porcentaje' ? '%' : 'Bs' }}
                        </span>
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista && $item->factor_incremento > 0)
                            {{ $item->tipo_incremento === 'porcentaje'
                                ? number_format($item->factor_incremento, 2).'%'
                                : 'Bs '.number_format($item->factor_incremento, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}{{ $inLista ? ' color:#7B6FE8; font-weight:700;' : '' }}">
                        @if ($inLista) Bs {{ number_format($item->precio_final, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista) {{ $item->puntos }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    @php
                        $rdStkMax = $stockMap->has($p->id)
                            ? max(0, $stockMap->get($p->id) - $asignadoMap->get($p->id, 0))
                            : null;
                    @endphp
                    <td style="{{ $tdBase }}">
                        @if($rdStkMax !== null)
                            <span style="font-weight:600; color:{{ $rdStkMax > 0 ? '#059669' : '#EF4444' }};">{{ number_format($rdStkMax, 0) }}</span>
                        @else
                            <span style="color:#D1D5DB;">—</span>
                        @endif
                    </td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista) {{ number_format($item->stock_inicial, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}{{ $inLista ? ' color:#F59E0B; font-weight:600;' : '' }}">
                        @if ($inLista) {{ number_format($item->stock_comprometido, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}">
                        @if ($inLista) {{ number_format($item->stock_consumido, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="{{ $tdBase }}{{ $inLista && $item->stock_actual <= 0 ? ' color:#EF4444; font-weight:700;' : '' }}">
                        @if ($inLista) {{ number_format($item->stock_actual, 2) }}
                        @else <span style="color:#D1D5DB;">—</span> @endif
                    </td>
                    <td style="padding:10px 12px; text-align:center;">
                        @if ($inLista && $item->active)
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#D1FAE5; color:#059669;">Habilitado</span>
                        @else
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#F3F4F6; color:#9CA3AF;">Deshabilitado</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="14" style="padding:48px 20px; text-align:center; font-size:13px; color:#9CA3AF;">No hay productos en el catálogo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══ MOBILE: Cards productos ══ --}}
<div class="block sm:hidden">
<div style="display:flex; flex-direction:column; gap:10px;">
    @forelse ($products as $p)
    @php $item = $itemsMap->get($p->id); $inLista = $item !== null; @endphp

    {{-- Card normal mobile --}}
    <div wire:key="mob-prod-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid {{ $inLista ? '#EDE9FE' : '#E5E7EB' }}; box-shadow:0 1px 3px rgba(0,0,0,.04); padding:12px 14px;
                {{ !$inLista ? 'opacity:.65;' : '' }}">

        {{-- Fila 1: código + precio final + estado --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:5px;">
            <span style="font-family:monospace; font-size:11px; font-weight:700; color:#7B6FE8; background:#F0EEFF; padding:2px 7px; border-radius:6px; white-space:nowrap; flex-shrink:0;">{{ $p->code }}</span>
            @if ($inLista)
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; background:#F0EEFF; border:1px solid #EDE9FE; padding:2px 8px; border-radius:6px; white-space:nowrap; flex-shrink:0;">
                Bs {{ number_format($item->precio_final, 2) }}
            </span>
            @if ($item->stock_comprometido > 0)
            <span style="font-size:11px; font-weight:600; color:#D97706; background:#FFFBEB; border:1px solid #FDE68A; padding:2px 7px; border-radius:6px; white-space:nowrap; flex-shrink:0;">
                Comp. {{ number_format($item->stock_comprometido, 0) }}
            </span>
            @endif
            <span style="margin-left:auto; flex-shrink:0; padding:2px 8px; border-radius:99px; font-size:11px; font-weight:600;
                         background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                {{ $item->active ? 'Activo' : 'Inactivo' }}
            </span>
            @else
            <span style="margin-left:auto; flex-shrink:0; padding:2px 8px; border-radius:99px; font-size:11px; font-weight:600; background:#F3F4F6; color:#9CA3AF;">Sin agregar</span>
            @endif
        </div>

        {{-- Fila 2: nombre --}}
        <div style="margin-bottom:10px;">
            <span style="font-size:14px; font-weight:700; color:#111827; line-height:1.3;">{{ $p->name }}</span>
        </div>

        {{-- Botones --}}
        <div style="display:flex; align-items:center; gap:6px; border-top:1px solid #F3F4F6; padding-top:10px;">
            @if ($inLista)
            <button wire:click="startEditItem({{ $item->id }})" title="Editar"
                    style="flex:1; height:32px; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px; font-size:11px; font-weight:600;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
            <button wire:click="removeItem({{ $item->id }})" title="Quitar de lista"
                    style="width:32px; height:32px; border-radius:8px; border:1px solid #FEE2E2; background:#FEF2F2; color:#EF4444; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.opacity='.7'" @mouseleave="$el.style.opacity='1'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            @else
            <button wire:click="openAgregarParaProducto({{ $p->id }})" title="Agregar a lista"
                    style="flex:1; height:32px; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Agregar a lista
            </button>
            @endif
        </div>
    </div>

    @empty
    <div style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; padding:40px; text-align:center; color:#9CA3AF; font-size:13px;">
        No hay productos en el catálogo.
    </div>
    @endforelse
</div>
</div>

{{-- ══ MODAL AGREGAR A LISTA ══ --}}
@if ($showAgregarModal && $selectedProductId)
@php
    $selProdModal  = $products->firstWhere('id', $selectedProductId);
    $tipoIncLista  = $viewingMaestra?->tipo_incremento;
    $valorIncLista = (float) ($viewingMaestra?->valor_incremento ?? 0);
    $incLabel      = $tipoIncLista === 'porcentaje' ? $valorIncLista.'%' : ($tipoIncLista === 'monto_fijo' ? 'Bs '.number_format($valorIncLista,2) : '—');
    $stkMaxModal   = $stockMap->has($selectedProductId)
        ? max(0, ($stockMap->get($selectedProductId) ?? 0) - ($asignadoMap->get($selectedProductId, 0)))
        : null;
@endphp
<div style="position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:50; display:flex; align-items:center; justify-content:center; padding:16px;"
     wire:click.self="closeAgregarModal">
    <div x-data="{
            precio: parseFloat('{{ (float)$modalPrecio }}') || 0,
            tipo:   '{{ $tipoIncLista }}',
            factor: {{ $valorIncLista }},
            get monto() {
                if (!this.tipo || !this.factor) return 0;
                return this.tipo === 'porcentaje'
                    ? Math.round(this.precio * this.factor / 100 * 100) / 100
                    : this.factor;
            },
            get final() { return (this.precio + this.monto).toFixed(2); },
            get montoFmt() { return this.monto.toFixed(2); }
         }"
         style="background:#fff; border-radius:8px; box-shadow:0 4px 24px rgba(0,0,0,.15); width:100%; max-width:420px; overflow:hidden;">
        {{-- Header --}}
        <div style="padding:14px 20px; border-bottom:1px solid #EDE9FE; background:#F5F3FF; display:flex; align-items:flex-start; justify-content:space-between;">
            <div>
                <p style="font-size:15px; font-weight:700; color:#111827; margin:0 0 2px;">Agregar a Lista</p>
                <p style="font-size:12px; color:#9CA3AF; margin:0;">{{ $selProdModal?->code }} — {{ $selProdModal?->name }}</p>
            </div>
            <button wire:click="closeAgregarModal" style="width:28px; height:28px; border:1px solid #EDE9FE; border-radius:6px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">✕</button>
        </div>
        {{-- Body --}}
        @php
            $row = 'display:flex; align-items:center; border-bottom:1px solid #E5E7EB;';
            $lbl = 'width:45%; padding:8px 14px; font-size:13px; color:#6B7280; text-align:right; flex-shrink:0;';
            $val = 'flex:1; padding:8px 12px; font-size:13px; color:#111827;';
        @endphp
        <div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Precio (Bs)</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="modalPrecio" x-on:input="precio = parseFloat($event.target.value) || 0"
                           type="number" step="0.01" min="0"
                           style="width:100%; height:30px; border:1px solid #D1D5DB; border-radius:5px; padding:0 8px; font-size:13px; outline:none; box-sizing:border-box;">
                    @error('modalPrecio') <p style="font-size:11px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Incremento</span>
                <span style="{{ $val }}">{{ $incLabel }}</span>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Monto Incremento</span>
                <span style="{{ $val }}">Bs <span x-text="montoFmt"></span></span>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Precio Final</span>
                <span style="{{ $val }} font-weight:700; color:#7B6FE8;">Bs <span x-text="final"></span></span>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Puntos</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="modalPuntos" type="number" min="0"
                           style="width:100%; height:30px; border:1px solid #D1D5DB; border-radius:5px; padding:0 8px; font-size:13px; outline:none; box-sizing:border-box;">
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Stock Inicial</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="modalStock" type="number" step="0.01" min="0"
                           onkeydown="if(event.key==='-'||event.key==='e'||event.key==='E')event.preventDefault()"
                           style="width:100%; height:30px; border:1px solid #D1D5DB; border-radius:5px; padding:0 8px; font-size:13px; outline:none; box-sizing:border-box;">
                    @error('modalStock') <p style="font-size:11px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div style="display:flex; align-items:center;">
                <span style="{{ $lbl }}">Stock Máximo</span>
                <span style="{{ $val }}">
                    @if($stkMaxModal !== null)
                        <span style="font-weight:600; color:{{ $stkMaxModal > 0 ? '#059669' : '#EF4444' }};">{{ number_format($stkMaxModal, 2) }}</span>
                    @else —
                    @endif
                </span>
            </div>
        </div>
        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #E5E7EB; display:flex; justify-content:flex-end; gap:8px;">
            <button wire:click="closeAgregarModal"
                    style="height:36px; padding:0 16px; border:1px solid #E5E7EB; border-radius:6px; background:#fff; color:#374151; font-size:13px; cursor:pointer;">
                Cancelar
            </button>
            <button wire:click="saveAgregarModal"
                    style="height:36px; padding:0 20px; border:none; border-radius:6px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                Agregar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ MODAL EDITAR ÍTEM ══ --}}
@if ($showEditModal && $editItemId)
@php
    $editProdModal = $selectedProductId ? $products->firstWhere('id', $selectedProductId) : null;
@endphp
<div style="position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:50; display:flex; align-items:center; justify-content:center; padding:16px;"
     wire:click.self="cancelEditItem">
    <div x-data="{
            precio: parseFloat('{{ (float)$editItemPrecio }}') || 0,
            tipo:   '{{ $editItemTipoIncremento }}',
            factor: parseFloat('{{ (float)$editItemFactorIncremento }}') || 0,
            get monto() {
                if (!this.tipo || !this.factor) return 0;
                return this.tipo === 'porcentaje'
                    ? Math.round(this.precio * this.factor / 100 * 100) / 100
                    : parseFloat(this.factor);
            },
            get final() { return (this.precio + this.monto).toFixed(2); },
            get montoFmt() { return this.monto.toFixed(2); }
         }"
         style="background:#fff; border-radius:8px; box-shadow:0 4px 24px rgba(0,0,0,.15); width:100%; max-width:420px; overflow:hidden;">
        {{-- Header --}}
        <div style="padding:14px 20px; border-bottom:1px solid #EDE9FE; background:#F5F3FF; display:flex; align-items:flex-start; justify-content:space-between;">
            <div>
                <p style="font-size:15px; font-weight:700; color:#111827; margin:0 0 2px;">Editar ítem</p>
                <p style="font-size:12px; color:#9CA3AF; margin:0;">{{ $editProdModal?->code }} — {{ $editProdModal?->name }}</p>
            </div>
            <button wire:click="cancelEditItem" style="width:28px; height:28px; border:1px solid #EDE9FE; border-radius:6px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">✕</button>
        </div>
        {{-- Body --}}
        @php
            $row = 'display:flex; align-items:center; border-bottom:1px solid #E5E7EB;';
            $lbl = 'width:45%; padding:8px 14px; font-size:13px; color:#6B7280; text-align:right; flex-shrink:0;';
            $val = 'flex:1; padding:8px 12px; font-size:13px; color:#111827;';
        @endphp
        @php $inp = 'width:100%; height:30px; border:1px solid #D1D5DB; border-radius:5px; padding:0 8px; font-size:13px; font-family:inherit; outline:none; box-sizing:border-box;'; @endphp
        <div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Precio (Bs)</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="editItemPrecio" x-on:input="precio = parseFloat($event.target.value) || 0"
                           type="number" step="0.01" min="0" style="{{ $inp }}">
                    @error('editItemPrecio') <p style="font-size:11px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Tipo Inc.</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <select wire:model="editItemTipoIncremento" x-on:change="tipo = $event.target.value"
                            style="{{ $inp }} background:#fff; cursor:pointer;">
                        <option value="">— Sin inc. —</option>
                        <option value="porcentaje">% Porcentaje</option>
                        <option value="monto_fijo">Bs Monto Fijo</option>
                    </select>
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Valor Inc.</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="editItemFactorIncremento" x-on:input="factor = parseFloat($event.target.value) || 0"
                           type="number" step="0.01" min="0" placeholder="0" style="{{ $inp }}">
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Monto Incremento</span>
                <span style="{{ $val }}">Bs <span x-text="montoFmt"></span></span>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Precio Final</span>
                <span style="{{ $val }} font-weight:700; color:#7B6FE8;">Bs <span x-text="final"></span></span>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Puntos</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="editItemPuntos" type="number" min="0" style="{{ $inp }}">
                </div>
            </div>
            <div style="{{ $row }}">
                <span style="{{ $lbl }}">Stock Inicial</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <input wire:model="editItemStock" type="number" step="0.01" min="0"
                           onkeydown="if(event.key==='-'||event.key==='e'||event.key==='E')event.preventDefault()"
                           style="{{ $inp }}">
                    @error('editItemStock') <p style="font-size:11px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div style="display:flex; align-items:center;">
                <span style="{{ $lbl }}">Estado</span>
                <div style="{{ $val }} padding-top:5px; padding-bottom:5px;">
                    <select wire:model="editItemActive"
                            style="{{ $inp }} background:#fff; cursor:pointer;">
                        <option value="1">Habilitado</option>
                        <option value="0">Deshabilitado</option>
                    </select>
                </div>
            </div>
        </div>
        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #E5E7EB; display:flex; justify-content:flex-end; gap:8px;">
            <button wire:click="cancelEditItem"
                    style="height:36px; padding:0 16px; border:1px solid #E5E7EB; border-radius:6px; background:#fff; color:#374151; font-size:13px; cursor:pointer;">
                Cancelar
            </button>
            <button wire:click="saveEditItem"
                    style="height:36px; padding:0 20px; border:none; border-radius:6px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                Guardar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════ ACCESO MODE ══ --}}
@elseif ($mode === 'acceso' && $viewingMaestra)

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="padding:13px 18px;">
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <button wire:click="backToList"
                    style="width:34px; height:34px; border-radius:9px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div style="min-width:0;">
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin:0 0 2px;">Gestionar acceso</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $viewingMaestra->name }}</p>
            </div>
        </div>
        <div>
            <span style="display:inline-flex; padding:2px 10px; border-radius:999px; font-size:11px; font-weight:600;
                {{ $viewingMaestra->active ? 'background:#D1FAE5; color:#065F46;' : 'background:#FEE2E2; color:#991B1B;' }}">
                {{ $viewingMaestra->active ? 'Activa' : 'Inactiva' }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- SECCIÓN A: CLIENTES --}}
    <div style="display:flex; flex-direction:column; gap:14px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="width:26px; height:26px; border-radius:50%; background:#D1FAE5; color:#065F46; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; flex-shrink:0;">A</span>
            <span style="font-size:14px; font-weight:700; color:#111827;">Clientes</span>
        </div>

        {{-- Consulta dinámica --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:12px; padding:14px;">
            <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 8px;">Consulta dinámica</p>
            <textarea wire:model="sqlCliente" rows="2" placeholder="email LIKE '%@empresa.com' OR id IN (1,2,3)"
                      style="width:100%; border:1px solid #EDE9FE; border-radius:8px; padding:7px 10px; font-size:12px; font-family:monospace; color:#374151; background:#fff; outline:none; resize:none; box-sizing:border-box;"></textarea>
            @if ($sqlClienteError)
                <p style="font-size:11px; color:#ef4444; margin-top:4px;">{{ $sqlClienteError }}</p>
            @endif
        </div>

        {{-- Agregar manualmente --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:12px; padding:14px;">
            <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 8px;">Agregar manualmente</p>
            <div style="position:relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="searchCliente" type="text" placeholder="Buscar por código, nombre o apellido..."
                       style="width:100%; height:36px; padding:0 12px 0 32px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; background:#fff; outline:none; box-sizing:border-box;">
            </div>
            @if ($manualClienteResult !== null)
            <div style="margin-top:8px; border:1px solid #EDE9FE; border-radius:8px; background:#fff; overflow:hidden;">
                @forelse ($manualClienteResult as $u)
                <button wire:click="addClienteManual({{ $u['id'] }})"
                        style="width:100%; display:flex; align-items:center; gap:10px; padding:8px 12px; border:none; border-bottom:1px solid #F3F4F6; background:#fff; cursor:pointer; text-align:left;"
                        @mouseenter="$el.style.background='#F0FDF4'" @mouseleave="$el.style.background='#fff'">
                    <span style="font-family:monospace; font-size:11px; color:#9CA3AF; width:28px; flex-shrink:0;">#{{ $u['id'] }}</span>
                    <span style="font-size:13px; font-weight:500; color:#111827;">{{ $u['name'] }}</span>
                </button>
                @empty
                <p style="padding:10px 12px; font-size:12px; color:#9CA3AF;">Sin resultados.</p>
                @endforelse
            </div>
            @endif
        </div>

        {{-- Resumen clientes --}}
        <div style="background:#fff; border-radius:12px; border:1px solid #E5E7EB; box-shadow:0 1px 3px rgba(0,0,0,.05); overflow:hidden;">
            <div style="padding:9px 14px; border-bottom:1px solid #E5E7EB; background:#F9FAFB;">
                <p style="font-size:11px; font-weight:600; color:#6B7280; margin:0;">Clientes con acceso ({{ $accesosClientes->count() }})</p>
            </div>
            @if ($accesosClientes->count())
            <table style="width:100%; font-size:12px; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F9FAFB;">
                        <th style="padding:7px 12px; text-align:left; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; width:40px;">ID</th>
                        <th style="padding:7px 12px; text-align:left; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Nombre</th>
                        <th style="padding:7px 12px; text-align:center; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Origen</th>
                        <th style="width:36px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accesosClientes as $acc)
                    <tr wire:key="acc-c-{{ $acc->id }}" style="border-top:1px solid #F3F4F6;">
                        <td style="padding:7px 12px; font-family:monospace; color:#9CA3AF;">#{{ $acc->user?->id }}</td>
                        <td style="padding:7px 12px; font-weight:500; color:#111827;">{{ $acc->user?->name }}</td>
                        <td style="padding:7px 12px; text-align:center;">
                            <span style="display:inline-flex; padding:1px 8px; border-radius:999px; font-size:10px; font-weight:600;
                                {{ $acc->origen === 'sql' ? 'background:#DBEAFE; color:#1D4ED8;' : 'background:#F3F4F6; color:#6B7280;' }}">
                                {{ ucfirst($acc->origen) }}
                            </span>
                        </td>
                        <td style="padding:7px 8px; text-align:right;">
                            <button wire:click="removeAcceso({{ $acc->id }})"
                                    style="background:transparent; border:none; cursor:pointer; color:#FCA5A5; padding:4px; display:inline-flex;"
                                    @mouseenter="$el.style.color='#EF4444'" @mouseleave="$el.style.color='#FCA5A5'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding:20px; text-align:center;">
                <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px; background:#D1FAE5; border:1px solid #A7F3D0; border-radius:999px; font-size:11px; font-weight:600; color:#065F46;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Acceso abierto a todos los clientes
                </span>
                <p style="font-size:11px; color:#9CA3AF; margin:6px 0 0;">Sin restricciones activas</p>
            </div>
            @endif
        </div>
    </div>

    {{-- SECCIÓN B: VENDEDORES --}}
    <div style="display:flex; flex-direction:column; gap:14px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="width:26px; height:26px; border-radius:50%; background:#FFE4D6; color:#9A3412; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; flex-shrink:0;">B</span>
            <span style="font-size:14px; font-weight:700; color:#111827;">Vendedores</span>
        </div>

        {{-- Consulta dinámica --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:12px; padding:14px;">
            <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 8px;">Consulta dinámica</p>
            <textarea wire:model="sqlVendedor" rows="2" placeholder="email LIKE '%@empresa.com' OR id IN (1,2,3)"
                      style="width:100%; border:1px solid #EDE9FE; border-radius:8px; padding:7px 10px; font-size:12px; font-family:monospace; color:#374151; background:#fff; outline:none; resize:none; box-sizing:border-box;"></textarea>
            @if ($sqlVendedorError)
                <p style="font-size:11px; color:#ef4444; margin-top:4px;">{{ $sqlVendedorError }}</p>
            @endif
        </div>

        {{-- Agregar manualmente --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:12px; padding:14px;">
            <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 8px;">Agregar manualmente</p>
            <div style="position:relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="searchVendedor" type="text" placeholder="Buscar por código, nombre o apellido..."
                       style="width:100%; height:36px; padding:0 12px 0 32px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; background:#fff; outline:none; box-sizing:border-box;">
            </div>
            @if ($manualVendedorResult !== null)
            <div style="margin-top:8px; border:1px solid #EDE9FE; border-radius:8px; background:#fff; overflow:hidden;">
                @forelse ($manualVendedorResult as $u)
                <button wire:click="addVendedorManual({{ $u['id'] }})"
                        style="width:100%; display:flex; align-items:center; gap:10px; padding:8px 12px; border:none; border-bottom:1px solid #F3F4F6; background:#fff; cursor:pointer; text-align:left;"
                        @mouseenter="$el.style.background='#FFF7ED'" @mouseleave="$el.style.background='#fff'">
                    <span style="font-family:monospace; font-size:11px; color:#9CA3AF; width:28px; flex-shrink:0;">#{{ $u['id'] }}</span>
                    <span style="font-size:13px; font-weight:500; color:#111827;">{{ $u['name'] }}</span>
                </button>
                @empty
                <p style="padding:10px 12px; font-size:12px; color:#9CA3AF;">Sin resultados.</p>
                @endforelse
            </div>
            @endif
        </div>

        {{-- Resumen vendedores --}}
        <div style="background:#fff; border-radius:12px; border:1px solid #E5E7EB; box-shadow:0 1px 3px rgba(0,0,0,.05); overflow:hidden;">
            <div style="padding:9px 14px; border-bottom:1px solid #E5E7EB; background:#F9FAFB;">
                <p style="font-size:11px; font-weight:600; color:#6B7280; margin:0;">Vendedores con acceso ({{ $accesosVendedores->count() }})</p>
            </div>
            @if ($accesosVendedores->count())
            <table style="width:100%; font-size:12px; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F9FAFB;">
                        <th style="padding:7px 12px; text-align:left; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; width:40px;">ID</th>
                        <th style="padding:7px 12px; text-align:left; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Nombre</th>
                        <th style="padding:7px 12px; text-align:center; font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Origen</th>
                        <th style="width:36px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accesosVendedores as $acc)
                    <tr wire:key="acc-v-{{ $acc->id }}" style="border-top:1px solid #F3F4F6;">
                        <td style="padding:7px 12px; font-family:monospace; color:#9CA3AF;">#{{ $acc->user?->id }}</td>
                        <td style="padding:7px 12px; font-weight:500; color:#111827;">{{ $acc->user?->name }}</td>
                        <td style="padding:7px 12px; text-align:center;">
                            <span style="display:inline-flex; padding:1px 8px; border-radius:999px; font-size:10px; font-weight:600;
                                {{ $acc->origen === 'sql' ? 'background:#DBEAFE; color:#1D4ED8;' : 'background:#F3F4F6; color:#6B7280;' }}">
                                {{ ucfirst($acc->origen) }}
                            </span>
                        </td>
                        <td style="padding:7px 8px; text-align:right;">
                            <button wire:click="removeAcceso({{ $acc->id }})"
                                    style="background:transparent; border:none; cursor:pointer; color:#FCA5A5; padding:4px; display:inline-flex;"
                                    @mouseenter="$el.style.color='#EF4444'" @mouseleave="$el.style.color='#FCA5A5'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding:20px; text-align:center;">
                <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px; background:#FFE4D6; border:1px solid #FDBA74; border-radius:999px; font-size:11px; font-weight:600; color:#9A3412;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Acceso abierto a todos los vendedores
                </span>
                <p style="font-size:11px; color:#9CA3AF; margin:6px 0 0;">Sin restricciones activas</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ LIST MODE ════ --}}
@else

@if(!$showAddForm)
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Lista
    </button>
</div>
@endif

@if ($showAddForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;"
     x-data="{
         tipoInc: @js($newTipoIncremento),
         valorInc: parseFloat(@js($newValorIncremento)) || 0,
         cantCuotas: parseInt(@js($newCantidadCuotas)) || 0,
         tipoCuotaIni: @js($newTipoCuotaInicial ?: 'ninguna'),
         valorCuotaIni: parseFloat(@js($newValorCuotaInicial)) || 0,
         get badgeInc() {
             if (!this.tipoInc || !this.valorInc) return null;
             let base = 100, v = parseFloat(this.valorInc) || 0;
             let inc = this.tipoInc === 'porcentaje' ? (base * v / 100) : v;
             let label = this.tipoInc === 'porcentaje' ? v + '%' : 'Bs ' + v.toFixed(2);
             return 'Bs 100 + ' + label + ' → Bs ' + (base + inc).toFixed(2);
         },
         get badgeResumen() {
             if (!this.cantCuotas) return null;
             let pedido = 1000, inicial = 0, textoIni = '';
             if (this.tipoCuotaIni !== 'ninguna' && this.valorCuotaIni > 0) {
                 let v = parseFloat(this.valorCuotaIni) || 0;
                 inicial = this.tipoCuotaIni === 'porcentaje' ? pedido * v / 100 : v;
                 textoIni = ' → Inicial ' + (this.tipoCuotaIni === 'porcentaje' ? v + '%' : 'Bs ' + v.toFixed(2)) + ' = Bs ' + inicial.toFixed(2);
             }
             let saldo = pedido - inicial;
             let cuota = this.cantCuotas > 0 ? saldo / this.cantCuotas : 0;
             return 'Ej: Pedido Bs 1,000' + textoIni + ' → Saldo Bs ' + saldo.toFixed(2) + ' ÷ ' + this.cantCuotas + ' cuotas = Bs ' + cuota.toFixed(2);
         }
     }">

    {{-- Header --}}
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Nueva Lista de Precios
        </span>
        <button wire:click="cancelAdd" type="button" style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; border:1px solid #EDE9FE; border-radius:8px; background:#fff; cursor:pointer; color:#9CA3AF;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Body --}}
    <div style="padding:16px 18px;">

        {{-- Fila 1: Info general --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Código *</label>
                <input wire:model="newCode" type="text" maxlength="30" placeholder="LP-202601"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box; font-family:monospace;">
                @error('newCode') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Nombre *</label>
                <input wire:model="newName" type="text" placeholder="Lista Enero 2026"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box;">
                @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Ciclo *</label>
                <select wire:model="newCycleId"
                        style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($cycles as $cycle)
                        <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
                    @endforeach
                </select>
                @error('newCycleId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Separador Incremento --}}
        <div style="display:flex; align-items:center; gap:10px; margin:16px 0 12px;">
            <div style="flex:1; height:1px; background:#F3F4F6;"></div>
            <span style="font-size:11px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Incremento de Precio</span>
            <div style="flex:1; height:1px; background:#F3F4F6;"></div>
        </div>

        {{-- Fila 2: Incremento --}}
        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            <div style="width:170px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Tipo Incremento</label>
                <select wire:model="newTipoIncremento" x-on:change="tipoInc = $event.target.value"
                        style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="">— Sin incremento —</option>
                    <option value="porcentaje">Porcentaje %</option>
                    <option value="monto_fijo">Monto Fijo Bs</option>
                </select>
            </div>
            <div style="width:120px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Valor</label>
                <input wire:model="newValorIncremento" x-on:input="valorInc = parseFloat($event.target.value) || 0"
                       type="number" step="0.01" min="0" placeholder="Ej: 10"
                       style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; box-sizing:border-box; text-align:center;">
            </div>
            <div x-show="badgeInc" x-cloak style="flex:1; min-width:200px; min-height:36px; background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:8px 12px; display:flex; align-items:center; box-sizing:border-box;">
                <span x-text="badgeInc" style="font-size:12px; font-weight:600; color:#065F46; line-height:1.4;"></span>
            </div>
        </div>

        {{-- Separador Financiamiento --}}
        <div style="display:flex; align-items:center; gap:10px; margin:16px 0 12px;">
            <div style="flex:1; height:1px; background:#F3F4F6;"></div>
            <span style="font-size:11px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Plan de Financiamiento</span>
            <div style="flex:1; height:1px; background:#F3F4F6;"></div>
        </div>

        {{-- Fila 3: Financiamiento --}}
        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
            <div style="width:150px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Cant. de Cuotas</label>
                <input wire:model="newCantidadCuotas" x-on:input="cantCuotas = parseInt($event.target.value) || 0"
                       type="number" min="1" max="999" placeholder="Ej: 6"
                       style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; box-sizing:border-box; text-align:center;">
            </div>
            <div style="width:150px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Días entre Cuotas</label>
                <input wire:model="newDiasEntreCuotas"
                       type="number" min="1" max="365" placeholder="Ej: 30"
                       style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; box-sizing:border-box; text-align:center;">
            </div>
            <div style="width:170px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Tipo Cuota Inicial</label>
                <select wire:model="newTipoCuotaInicial" x-on:change="tipoCuotaIni = $event.target.value"
                        style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="ninguna">Sin cuota inicial</option>
                    <option value="porcentaje">Porcentaje %</option>
                    <option value="monto_fijo">Monto Fijo Bs</option>
                </select>
            </div>
            <div x-show="tipoCuotaIni !== 'ninguna'" x-cloak style="width:130px;">
                <label style="display:block; font-size:12px; font-weight:700; color:#7B6FE8; margin-bottom:4px; text-transform:uppercase; letter-spacing:.4px;">Valor Inicial</label>
                <input wire:model="newValorCuotaInicial" x-on:input="valorCuotaIni = parseFloat($event.target.value) || 0"
                       type="number" step="0.01" min="0" placeholder="Ej: 20"
                       style="width:100%; height:36px; border:1px solid #E5E7EB; border-radius:8px; padding:0 10px; font-size:13px; outline:none; box-sizing:border-box; text-align:center;">
            </div>
            <div x-show="badgeResumen" x-cloak style="flex:1; min-width:220px; min-height:36px; background:#ECFDF5; border:1px solid #A7F3D0; border-radius:8px; padding:8px 12px; display:flex; align-items:center; box-sizing:border-box;">
                <span x-text="badgeResumen" style="font-size:12px; font-weight:600; color:#065F46; line-height:1.4;"></span>
            </div>
        </div>

        {{-- Botones --}}
        <div style="display:flex; gap:8px; padding-top:12px; border-top:1px solid #F3F4F6; margin-top:16px;">
            <button wire:click="saveNew"
                    style="height:36px; padding:0 20px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; box-sizing:border-box;">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:36px; padding:0 16px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; box-sizing:border-box;">
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
        <span style="font-size:13px; font-weight:700; color:#111827;">Listas registradas</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $maestras->total() }}</span>
        @if($selectedMaestraId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:10px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedMaestraId)
                <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Guardar</button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">Cancelar</button>
            @else
                <button wire:click="viewItems({{ $selectedMaestraId }})" style="{{ $btnH }} background:#CFFAFE; color:#0E7490; border:1px solid #A5F3FC;">Ver artículos</button>
                <button wire:click="viewAcceso({{ $selectedMaestraId }})" style="{{ $btnH }} background:#FFF7ED; color:#C2410C; border:1px solid #FED7AA;">Acceso</button>
                <button wire:click="openView({{ $selectedMaestraId }})" style="{{ $btnH }} background:#F3F4F6; color:#374151; border:1px solid #E5E7EB;">Ver detalle</button>
                <button wire:click="startEdit({{ $selectedMaestraId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Editar</button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        @php
        $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center;';
        $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
        $fW  = 'position:relative; margin-top:4px;';
        $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
        @endphp
        <table style="width:100%; min-width:1500px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'opacity:0.45; pointer-events:none;' : '' }}">
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF; white-space:nowrap; vertical-align:top;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedMaestraId !== null"
                                   :disabled="$wire.selectedMaestraId === null"
                                   @click.prevent="$wire.selectedMaestraId !== null && $wire.set('selectedMaestraId', null)"
                                   :style="$wire.selectedMaestraId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Ciclo --}}
                    @php $isActive = ($sortBy??'') === 'cycle_id'; @endphp
                    <th wire:click="toggleSort('cycle_id')" style="padding:8px 14px 6px; min-width:100px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Ciclo
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><input wire:model.live.debounce.300ms="colFilterCiclo" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Código --}}
                    @php $isActive = ($sortBy??'') === 'code'; @endphp
                    <th wire:click="toggleSort('code')" style="padding:8px 14px 6px; min-width:100px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Código
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><input wire:model.live.debounce.300ms="colFilterCodigo" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Nombre --}}
                    @php $isActive = ($sortBy??'') === 'name'; @endphp
                    <th wire:click="toggleSort('name')" style="padding:8px 14px 6px; min-width:220px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Nombre
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                    </th>

                    {{-- Cuotas --}}
                    @php $isActive = ($sortBy??'') === 'cantidad_cuotas'; @endphp
                    <th wire:click="toggleSort('cantidad_cuotas')" style="padding:8px 14px 6px; min-width:90px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Cuotas
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><input wire:model.live.debounce.300ms="colFilterCuotas" @click.stop type="number" min="1" style="{{ $fI }}"></div>
                    </th>

                    {{-- C. Inicial --}}
                    @php $isActive = ($sortBy??'') === 'valor_cuota_inicial'; @endphp
                    <th wire:click="toggleSort('valor_cuota_inicial')" style="padding:8px 14px 6px; min-width:160px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">C. Inicial
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><select wire:model.live="colFilterCuotaInicial" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select></div>
                    </th>

                    {{-- Incremento --}}
                    @php $isActive = ($sortBy??'') === 'valor_incremento'; @endphp
                    <th wire:click="toggleSort('valor_incremento')" style="padding:8px 14px 6px; min-width:160px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Incremento
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><select wire:model.live="colFilterIncremento" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="porcentaje">%</option>
                            <option value="monto_fijo">Bs</option>
                        </select></div>
                    </th>

                    {{-- Días --}}
                    @php $isActive = ($sortBy??'') === 'dias_entre_cuotas'; @endphp
                    <th wire:click="toggleSort('dias_entre_cuotas')" style="padding:8px 14px 6px; min-width:80px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Días
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><input wire:model.live.debounce.300ms="colFilterDias" @click.stop type="number" min="1" style="{{ $fI }}"></div>
                    </th>

                    {{-- Estado --}}
                    @php $isActive = ($sortBy??'') === 'active'; @endphp
                    <th wire:click="toggleSort('active')" style="padding:8px 14px 6px; min-width:160px; text-align:center; cursor:pointer; user-select:none; position:relative; vertical-align:top; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; justify-content:center; gap:5px;">Estado
                            @if($isActive && ($sortDir??'asc')==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div style="{{ $fW }}"><svg style="{{ $fIc }}" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg><select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select></div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($maestras as $m)

                @if ($editingId === $m->id)
                {{-- Fila edición — Fila 1: campos principales --}}
                @php $lE = 'font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:3px;';
                     $iE = 'width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;'; @endphp
                <tr wire:key="m-edit-{{ $m->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" rowspan="2" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:#F8F7FF; white-space:nowrap; vertical-align:middle;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $maestras->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">Ciclo</div>
                        <select wire:model="editCycleId" style="{{ $iE }} padding:0 6px;">
                            <option value="">— Ciclo —</option>
                            @foreach ($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
                            @endforeach
                        </select>
                        @error('editCycleId') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">Código</div>
                        <input wire:model="editCode" type="text" style="{{ $iE }} text-transform:uppercase; font-family:monospace;">
                        @error('editCode') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">Nombre</div>
                        <input wire:model="editName" type="text" style="{{ $iE }}">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        <div style="{{ $lE }}">Cuotas</div>
                        <input wire:model="editCantidadCuotas" type="number" min="1" max="999" placeholder="—"
                               style="width:60px; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; text-align:center; outline:none; background:#fff; box-sizing:border-box;">
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">C. Inicial</div>
                        <select wire:model.live="editTipoCuotaInicial" style="{{ $iE }} padding:0 6px;">
                            <option value="ninguna">Sin cuota</option>
                            <option value="porcentaje">%</option>
                            <option value="monto_fijo">Bs</option>
                        </select>
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">Incremento</div>
                        <select wire:model.live="editTipoIncremento" style="{{ $iE }} padding:0 6px;">
                            <option value="">Sin incr.</option>
                            <option value="porcentaje">%</option>
                            <option value="monto_fijo">Bs</option>
                        </select>
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        <div style="{{ $lE }}">Días</div>
                        <input wire:model="editDiasEntreCuotas" type="number" min="1" max="365" placeholder="30"
                               style="width:60px; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; text-align:center; outline:none; background:#fff; box-sizing:border-box;">
                    </td>
                    <td style="padding:7px 10px;">
                        <div style="{{ $lE }}">Estado</div>
                        <select wire:model="editActive" style="{{ $iE }} padding:0 6px;">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                    </td>
                </tr>
                {{-- Fila edición — Fila 2: valores de C. Inicial e Incremento --}}
                <tr style="background:#F8F7FF; border-bottom:2px solid #C4B5FD;">
                    <td colspan="4"></td>{{-- Ciclo + Código + Nombre + Cuotas --}}
                    <td style="padding:0 10px 7px;">
                        @if($editTipoCuotaInicial !== 'ninguna')
                        <input wire:model="editValorCuotaInicial" type="number" step="0.01" min="0" placeholder="Valor {{ $editTipoCuotaInicial === 'porcentaje' ? '%' : 'Bs' }}"
                               style="{{ $iE }} text-align:center;">
                        @endif
                    </td>
                    <td style="padding:0 10px 7px;">
                        @if($editTipoIncremento)
                        <input wire:model="editValorIncremento" type="number" step="0.01" min="0" placeholder="Valor {{ $editTipoIncremento === 'porcentaje' ? '%' : 'Bs' }}"
                               style="{{ $iE }} text-align:center;">
                        @endif
                    </td>
                    <td colspan="2"></td>{{-- Días + Estado --}}
                </tr>

                @else
                {{-- Fila normal --}}
                <tr wire:key="m-{{ $m->id }}"
                    style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    @php $selM = $selectedMaestraId === $m->id; @endphp
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selM ? '#F5F3FF' : '#fff' }}; white-space:nowrap;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedMaestraId === {{ $m->id }}"
                                   @click="$wire.selectedMaestraId === {{ $m->id }} ? $wire.set('selectedMaestraId', null) : $wire.selectMaestra({{ $m->id }})"
                                   :disabled="{{ $editingId && $editingId !== $m->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $m->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $maestras->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $m->cycle?->code ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $m->code ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($m->name)) }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="font-size:13px; color:#6B7280;">{{ $m->cantidad_cuotas ? $m->cantidad_cuotas.'c' : '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; text-align:center;">
                        @if ($m->usa_cuota_inicial)
                        <span style="font-size:13px; color:#374151;">
                            {{ $m->tipo_cuota_inicial === 'porcentaje' ? number_format($m->valor_cuota_inicial, 0).'%' : 'Bs '.number_format($m->valor_cuota_inicial, 2) }}
                        </span>
                        @else
                        <span style="font-size:13px; color:#9CA3AF;">—</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        @if($m->tipo_incremento === 'porcentaje')
                        <span style="font-size:13px; color:#374151;">{{ number_format($m->valor_incremento, 0) }}%</span>
                        @elseif($m->tipo_incremento === 'monto_fijo')
                        <span style="font-size:13px; color:#374151;">Bs {{ number_format($m->valor_incremento, 2) }}</span>
                        @else
                        <span style="font-size:13px; color:#9CA3AF;">—</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="font-size:13px; color:#374151;">{{ $m->dias_entre_cuotas ? $m->dias_entre_cuotas.'d' : '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $m->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $m->active ? '#059669' : '#9CA3AF' }};">
                            {{ $m->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                </tr>
                @endif

                @empty
                <tr><td colspan="9" style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">Sin resultados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($maestras->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $maestras->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE: Cards ══ --}}
<div class="block sm:hidden">
<div style="display:flex; flex-direction:column; gap:10px;">
    @forelse ($maestras as $m)

    @if ($editingId === $m->id)
    {{-- Card edición mobile --}}
    @php $ml = 'font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:3px;'; @endphp
    @php $mi = 'width:100%; height:34px; border:1px solid #D8D3F8; border-radius:8px; padding:0 8px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;'; @endphp
    <div wire:key="mob-edit-{{ $m->id }}"
         style="background:#F8F7FF; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; padding:14px; display:flex; flex-direction:column; gap:12px;">

        {{-- Título --}}
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#7B6FE8;">Editando lista</span>
        </div>

        {{-- Info general --}}
        <div style="display:flex; flex-direction:column; gap:8px;">
            <div>
                <label style="{{ $ml }}">Código</label>
                <input wire:model="editCode" type="text" style="{{ $mi }} font-family:monospace; text-transform:uppercase;">
                @error('editCode') <p style="color:#EF4444; font-size:11px; margin-top:2px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="{{ $ml }}">Nombre</label>
                <input wire:model="editName" type="text" style="{{ $mi }} font-size:13px; padding:0 10px;">
                @error('editName') <p style="color:#EF4444; font-size:11px; margin-top:2px;">{{ $message }}</p> @enderror
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                <div>
                    <label style="{{ $ml }}">Ciclo</label>
                    <select wire:model="editCycleId" style="{{ $mi }}">
                        <option value="">— Ciclo —</option>
                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="{{ $ml }}">Estado</label>
                    <select wire:model="editActive" style="{{ $mi }}">
                        <option value="1">Activa</option>
                        <option value="0">Inactiva</option>
                    </select>
                </div>
                <div>
                    <label style="{{ $ml }}">Cant. Cuotas</label>
                    <input wire:model="editCantidadCuotas" type="number" min="1" max="999" placeholder="—"
                           style="{{ $mi }} text-align:center;">
                </div>
                <div>
                    <label style="{{ $ml }}">Días entre Cuotas</label>
                    <input wire:model="editDiasEntreCuotas" type="number" min="1" max="365" placeholder="30"
                           style="{{ $mi }} text-align:center;">
                </div>
            </div>
        </div>

        {{-- Separador incremento --}}
        <div style="display:flex; align-items:center; gap:8px;">
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
            <span style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Incremento de Precio</span>
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
            <div>
                <label style="{{ $ml }}">Tipo</label>
                <select wire:model="editTipoIncremento" style="{{ $mi }}">
                    <option value="">— Sin incremento —</option>
                    <option value="porcentaje">Porcentaje %</option>
                    <option value="monto_fijo">Monto Fijo Bs</option>
                </select>
            </div>
            <div>
                <label style="{{ $ml }}">Valor {{ $editTipoIncremento === 'porcentaje' ? '(%)' : ($editTipoIncremento === 'monto_fijo' ? '(Bs)' : '') }}</label>
                <input wire:model="editValorIncremento" type="number" step="0.01" min="0" placeholder="0"
                       style="{{ $mi }} text-align:center;">
            </div>
        </div>

        {{-- Separador cuota inicial --}}
        <div style="display:flex; align-items:center; gap:8px;">
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
            <span style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px;">Cuota Inicial</span>
            <div style="flex:1; height:1px; background:#EDE9FE;"></div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
            <div>
                <label style="{{ $ml }}">Tipo</label>
                <select wire:model="editTipoCuotaInicial" style="{{ $mi }}">
                    <option value="ninguna">Sin cuota inicial</option>
                    <option value="porcentaje">Porcentaje %</option>
                    <option value="monto_fijo">Monto Fijo Bs</option>
                </select>
            </div>
            @if ($editTipoCuotaInicial !== 'ninguna')
            <div>
                <label style="{{ $ml }}">Valor {{ $editTipoCuotaInicial === 'porcentaje' ? '(%)' : '(Bs)' }}</label>
                <input wire:model="editValorCuotaInicial" type="number" step="0.01" min="0" placeholder="0"
                       style="{{ $mi }} text-align:center;">
            </div>
            @endif
        </div>

        {{-- Botones --}}
        <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #EDE9FE;">
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

    @else
    {{-- Card normal mobile --}}
    <div wire:key="mob-{{ $m->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 3px rgba(0,0,0,.05); padding:14px;">

        {{-- Fila 1: código + ciclo + estado --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <span style="font-family:monospace; font-size:11px; color:#9CA3AF; background:#F3F4F6; padding:2px 7px; border-radius:6px; white-space:nowrap; flex-shrink:0;">{{ $m->code ?? '—' }}</span>
            @if ($m->cycle?->code)
            <span style="font-size:11px; color:#6B7280; background:#F9FAFB; border:1px solid #E5E7EB; padding:2px 8px; border-radius:6px; white-space:nowrap;">
                <strong>{{ $m->cycle->code }}</strong>
            </span>
            @endif
            <span style="margin-left:auto; flex-shrink:0; padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;
                         background:{{ $m->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $m->active ? '#059669' : '#9CA3AF' }};">
                {{ $m->active ? 'Activa' : 'Inactiva' }}
            </span>
        </div>

        {{-- Fila 2: nombre --}}
        <div style="margin-bottom:12px;">
            <span style="font-size:14px; font-weight:700; color:#111827; line-height:1.3;">{{ $m->name }}</span>
        </div>

        {{-- Botones --}}
        <div style="display:flex; align-items:center; gap:6px; border-top:1px solid #F3F4F6; padding-top:10px;">
            {{-- Editar --}}
            <button wire:click="startEdit({{ $m->id }})" title="Editar"
                    style="flex:1; height:32px; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px; font-size:11px; font-weight:600;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
            {{-- Ver detalle --}}
            <button wire:click="openView({{ $m->id }})" title="Ver detalle"
                    style="width:32px; height:32px; border-radius:8px; border:1px solid #E5E7EB; background:#F9FAFB; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#F9FAFB'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
            {{-- Ver productos --}}
            <button wire:click="viewItems({{ $m->id }})" title="Ver productos"
                    style="width:32px; height:32px; border-radius:8px; border:1px solid #A5F3FC; background:#CFFAFE; color:#0E7490; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.opacity='.75'" @mouseleave="$el.style.opacity='1'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </button>
            {{-- Gestionar acceso --}}
            <button wire:click="viewAcceso({{ $m->id }})" title="Gestionar acceso"
                    style="width:32px; height:32px; border-radius:8px; border:1px solid #FED7AA; background:#FFF7ED; color:#C2410C; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.opacity='.75'" @mouseleave="$el.style.opacity='1'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </button>
        </div>
    </div>
    @endif

    @empty
    <div style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; padding:40px; text-align:center; color:#9CA3AF; font-size:13px;">
        No hay listas registradas.
    </div>
    @endforelse

    @if ($maestras->hasPages())
    <div style="padding:8px 0;">{{ $maestras->links() }}</div>
    @endif
</div>
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
@endif

{{-- ══ MODAL: Ver detalle lista ══ --}}
@if ($showViewModal)
<div style="position:fixed; inset:0; z-index:60; display:flex; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.45);"
     wire:click.self="closeView">
    <div style="background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(0,0,0,.18); width:100%; max-width:500px; height:80vh; display:flex; flex-direction:column;">

        {{-- Header --}}
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.7px; margin:0 0 2px;">Detalle de lista</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0;">{{ $viewMaestraData['name'] }}</p>
            </div>
            <button wire:click="closeView"
                    style="width:32px; height:32px; border-radius:9px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="height:calc(80vh - 130px); overflow-y:auto; padding:16px;">

            {{-- Info general --}}
            <div style="border-radius:12px; border:1px solid #EDE9FE; overflow:hidden; margin-bottom:10px;">
                <div style="background:#F5F3FF; padding:9px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span style="font-size:12px; font-weight:700; color:#111827; text-transform:uppercase; letter-spacing:.4px;">Información General</span>
                </div>
                <div style="padding:12px 14px; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F9FAFB; padding-bottom:8px;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Código</span>
                        <span style="font-size:13px; color:#374151; font-weight:600; font-family:monospace;">{{ $viewMaestraData['code'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F9FAFB; padding-bottom:8px;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Ciclo</span>
                        <span style="font-size:13px; color:#374151; font-weight:600; font-family:monospace;">{{ $viewMaestraData['ciclo'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Estado</span>
                        <span style="padding:3px 10px; border-radius:99px; font-size:12px; font-weight:600;
                                     background:{{ $viewMaestraData['active'] ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $viewMaestraData['active'] ? '#059669' : '#9CA3AF' }};">
                            {{ $viewMaestraData['active'] ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Incremento --}}
            <div style="border-radius:12px; border:1px solid #EDE9FE; overflow:hidden; margin-bottom:10px;">
                <div style="background:#F5F3FF; padding:9px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span style="font-size:12px; font-weight:700; color:#111827; text-transform:uppercase; letter-spacing:.4px;">Incremento de Precio</span>
                </div>
                <div style="padding:12px 14px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Valor aplicado</span>
                        <span style="font-size:13px; color:#374151; font-weight:600;">{{ $viewMaestraData['incremento'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Financiamiento --}}
            <div style="border-radius:12px; border:1px solid #EDE9FE; overflow:hidden;">
                <div style="background:#F5F3FF; padding:9px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span style="font-size:12px; font-weight:700; color:#111827; text-transform:uppercase; letter-spacing:.4px;">Plan de Financiamiento</span>
                </div>
                <div style="padding:12px 14px; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F9FAFB; padding-bottom:8px;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Cantidad de cuotas</span>
                        <span style="font-size:13px; color:#374151; font-weight:600;">{{ $viewMaestraData['cuotas'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F9FAFB; padding-bottom:8px;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Días entre cuotas</span>
                        <span style="font-size:13px; color:#374151; font-weight:600;">{{ $viewMaestraData['dias'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:12px; color:#6B7280; font-weight:500;">Cuota inicial</span>
                        <span style="font-size:13px; color:#374151; font-weight:600;">{{ $viewMaestraData['cuota_ini'] }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; flex-shrink:0; display:flex; justify-content:flex-end;">
            <button wire:click="closeView"
                    style="height:36px; padding:0 24px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif


</div>
