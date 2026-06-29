<div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

{{-- ══ TOOLBAR ══ --}}
@if(!$showAddForm)
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
    <select wire:model.live="filterListaId" class="w-full sm:w-auto"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box; max-width:220px;">
        <option value="">Todos los ciclos</option>
        @foreach($listas as $l)
        <option value="{{ $l->id }}">{{ $l->cycle?->code }} — {{ $l->name }}</option>
        @endforeach
    </select>
    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar artículo
    </button>
</div>
@endif

{{-- ══ FORM: Agregar artículo al stock ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8;">Agregar Artículo al Stock</span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:20px; display:flex; flex-wrap:wrap; gap:16px;">

        {{-- CICLO --}}
        <div style="min-width:200px; flex:1;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Ciclo *</label>
            <select wire:model.live="formListaMaestraId" style="{{ $iS }} cursor:pointer; padding:0 8px;">
                <option value="">— Seleccionar ciclo —</option>
                @foreach($listas as $l)
                <option value="{{ $l->id }}">{{ $l->cycle?->code }} — {{ $l->name }}</option>
                @endforeach
            </select>
            @error('formListaMaestraId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>

        {{-- ARTÍCULO: autocomplete Alpine --}}
        <div style="flex:2; min-width:240px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código — Descripción *</label>

            @if(!$formListaMaestraId)
            <div style="height:38px; border:1px solid #E5E7EB; border-radius:8px; padding:0 12px; font-size:13px; color:#9CA3AF; background:#F9FAFB; display:flex; align-items:center;">
                Primero seleccioná un ciclo
            </div>
            @else

            @php
                $itemsJson = $maestrosDisponibles->map(fn($m) => [
                    'id'     => $m->id,
                    'codigo' => $m->codigo,
                    'nombre' => $m->nombre,
                    'label'  => $m->codigo . ' — ' . $m->nombre,
                ])->values()->toJson();
            @endphp

            <div wire:key="autocomplete-{{ $formListaMaestraId }}"
                 x-data="{
                     open: false,
                     query: '',
                     selected: null,
                     items: {{ $itemsJson }},
                     get filtered() {
                         if (!this.query || this.selected) return [];
                         const q = this.query.toLowerCase();
                         return this.items.filter(i =>
                             i.codigo.toLowerCase().includes(q) ||
                             i.nombre.toLowerCase().includes(q)
                         );
                     },
                     pick(item) {
                         this.selected = item;
                         this.query = item.label;
                         this.open = false;
                         $wire.selectMaestro(item.id);
                     },
                     clear() {
                         this.selected = null;
                         this.query = '';
                         this.open = true;
                         $wire.set('selectedMaestroId', null);
                         $wire.set('maestroCategoria', '');
                         $wire.set('maestroUnidad', '');
                         $nextTick(() => this.$refs.input.focus());
                     }
                 }"
                 @click.outside="open = false"
                 style="position:relative;">

                <div style="position:relative;">
                    <input x-ref="input"
                           x-model="query"
                           @focus="if(!selected) open = true"
                           @input="if(!selected){ open = true; }"
                           @keydown.escape="open = false"
                           :readonly="!!selected"
                           type="text"
                           placeholder="Escribí código o nombre..."
                           :style="'{{ $iS }} padding-right:32px;' + (selected ? 'background:#F0FDF4; color:#065F46; font-weight:600; cursor:default;' : '')">

                    {{-- X para limpiar --}}
                    <button x-show="selected" @click.prevent="clear()"
                            style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#9CA3AF; padding:2px; display:flex; align-items:center;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Dropdown --}}
                <div x-show="open && filtered.length > 0"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="position:absolute; left:0; right:0; top:calc(100% + 4px); background:#fff; border:1px solid #E5E7EB; border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,.1); z-index:50; max-height:200px; overflow-y:auto;">
                    <template x-for="item in filtered" :key="item.id">
                        <button @mousedown.prevent="pick(item)"
                                style="width:100%; text-align:left; padding:8px 12px; border:none; border-bottom:1px solid #F9FAFB; background:#fff; font-size:13px; color:#374151; cursor:pointer; display:block;"
                                @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background='#fff'">
                            <span x-text="item.codigo" style="font-family:monospace; font-weight:700; color:#7B6FE8;"></span>
                            <span x-text="' — ' + item.nombre"></span>
                        </button>
                    </template>
                </div>

                <div x-show="open && filtered.length === 0 && query.length > 0"
                     style="position:absolute; left:0; right:0; top:calc(100% + 4px); background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:12px; font-size:12px; color:#9CA3AF; z-index:50;">
                    Sin resultados para "<span x-text="query"></span>"
                </div>

            </div>
            @endif
            @if($formListaMaestraId) @error('selectedMaestroId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror @endif
        </div>

        {{-- STOCK INICIAL --}}
        <div style="min-width:120px; max-width:160px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Stock Inicial *</label>
            <input wire:model="stockInicial" type="number" min="0" step="1" placeholder="0"
                   style="{{ $iS }} text-align:right;">
            @error('stockInicial') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>

        {{-- CATEGORÍA (auto) --}}
        <div style="min-width:130px; flex:1;">
            <label style="display:block; font-size:11px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Categoría</label>
            <div style="height:38px; border:1px solid #E5E7EB; border-radius:8px; padding:0 12px; font-size:13px; color:#6B7280; background:#F9FAFB; display:flex; align-items:center;">
                {{ $maestroCategoria ?: '—' }}
            </div>
        </div>

        {{-- UNIDAD (auto) --}}
        <div style="min-width:120px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Unidad</label>
            <div style="height:38px; border:1px solid #E5E7EB; border-radius:8px; padding:0 12px; font-size:13px; color:#6B7280; background:#F9FAFB; display:flex; align-items:center;">
                {{ $maestroUnidad ?: '—' }}
            </div>
        </div>

        <div style="padding-top:21px; display:flex; gap:8px;">
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
@endif

{{-- ══ TABLA ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Stock de artículos</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $items->total() }}</span>
        @if($selectedItemId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:10px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingItemId === $selectedItemId)
                <button wire:click="saveEdit"   style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedItemId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
                <button wire:click="openStockModal({{ $selectedItemId }})" style="{{ $btnH }} background:#EDE9FE; color:#7B6FE8;">
                    <svg width="10" height="10" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
                    Ver Listas
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        @if($items->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay artículos en el stock.</p>
        @else
        <table style="width:100%; min-width:1000px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF; white-space:nowrap;">#</th>
                    {{-- Ciclo (sortable) --}}
                    @php $isActive = $sortBy === 'lista_maestra_id'; @endphp
                    <th wire:click="toggleSort('lista_maestra_id')"
                        style="padding:10px 14px; text-align:left; user-select:none; cursor:pointer; white-space:nowrap; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            Ciclo
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    <th style="padding:10px 14px; text-align:left; min-width:140px;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Lista</span></th>
                    {{-- Código, Nombre, Categoría, Unidad — columnas de tabla relacionada, sin sort --}}
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Código</span></th>
                    <th style="padding:10px 14px; text-align:left; min-width:160px;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Categoría</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Unidad</span></th>
                    {{-- Stock Ini., Stock Act., Stock Asig. — sortable --}}
                    @foreach(['stock_inicial'=>'Stock Ini.','stock_actual'=>'Stock Act.','stock_comprometido'=>'Stock Asig.'] as $col=>$label)
                    @php $isActive = $sortBy === $col; @endphp
                    <th wire:click="toggleSort('{{ $col }}')"
                        style="padding:10px 14px; text-align:right; user-select:none; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; justify-content:flex-end; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    @endforeach
                    {{-- Stock Disponible — calculado, sin sort --}}
                    <th style="padding:10px 14px; text-align:right;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Disp.</span></th>
                    {{-- Estado — sortable --}}
                    @php $isActive = $sortBy === 'active'; @endphp
                    <th wire:click="toggleSort('active')"
                        style="padding:10px 14px; text-align:center; user-select:none; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            Estado
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                @php $isSelected = $selectedItemId === $item->id; @endphp

                {{-- Fila edición inline --}}
                @if($editingItemId === $item->id)
                @php $eI = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;'; @endphp
                <tr wire:key="edit-{{ $item->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:#F8F7FF; white-space:nowrap;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $items->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:10px 14px; white-space:nowrap;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#7B6FE8;">{{ $item->listaMaestra?->cycle?->code }}</span>
                    </td>
                    <td style="padding:10px 14px; min-width:140px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->listaMaestra?->name }}</span>
                    </td>
                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827;">{{ $item->maestroArticulo?->codigo }}</span>
                    </td>
                    <td style="padding:10px 14px; min-width:160px;">
                        <span style="font-size:13px; color:#111827;">{{ $item->maestroArticulo?->nombre }}</span>
                    </td>
                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->maestroArticulo?->categoria?->descripcion ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->maestroArticulo?->unidad?->name ?? '—' }}</span>
                    </td>
                    {{-- Stock Inicial editable --}}
                    <td style="padding:6px 10px;">
                        <input wire:model="editStockInicial" type="number" min="0" step="1"
                               style="{{ $eI }} width:90px; text-align:right;">
                        @error('editStockInicial') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:600; color:#111827; font-family:monospace;">{{ number_format((float)$item->stock_actual, 0) }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:600; color:#6366F1; font-family:monospace;">{{ number_format((float)$item->stock_comprometido, 0) }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:700; font-family:monospace; color:#9CA3AF;">{{ number_format($item->stockDisponible(), 0) }}</span>
                    </td>
                    {{-- Estado editable --}}
                    <td style="padding:6px 10px; text-align:center;">
                        <select wire:model="editActive" style="{{ $eI }} width:90px; padding:0 6px; cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                <tr wire:key="item-{{ $item->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s; {{ $isSelected ? 'background:#F5F3FF;' : '' }}"
                    @mouseenter="$el.style.background='{{ $isSelected ? '#EDEAFF' : '#FAFAFE' }}'"
                    @mouseleave="$el.style.background='{{ $isSelected ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $isSelected ? '#F5F3FF' : '#fff' }}; white-space:nowrap;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedItemId === {{ $item->id }}"
                                   @click="$wire.selectedItemId === {{ $item->id }} ? $wire.set('selectedItemId', null) : $wire.selectItem({{ $item->id }})"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $items->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; white-space:nowrap;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#7B6FE8;">{{ $item->listaMaestra?->cycle?->code }}</span>
                    </td>

                    <td style="padding:10px 14px; min-width:140px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->listaMaestra?->name }}</span>
                    </td>

                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827;">{{ $item->maestroArticulo?->codigo }}</span>
                    </td>

                    <td style="padding:10px 14px; min-width:160px;">
                        <span style="font-size:13px; color:#111827;">{{ $item->maestroArticulo?->nombre }}</span>
                    </td>

                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->maestroArticulo?->categoria?->descripcion ?? '—' }}</span>
                    </td>

                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; color:#374151;">{{ $item->maestroArticulo?->unidad?->name ?? '—' }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:600; color:#111827; font-family:monospace;">{{ number_format((float)$item->stock_inicial, 0) }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:600; color:#111827; font-family:monospace;">{{ number_format((float)$item->stock_actual, 0) }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:right;">
                        <span style="font-size:13px; font-weight:600; color:#6366F1; font-family:monospace;">{{ number_format((float)$item->stock_comprometido, 0) }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:right;">
                        @php $disp = $item->stockDisponible(); @endphp
                        <span style="font-size:13px; font-weight:700; font-family:monospace; color:{{ $disp > 0 ? '#059669' : '#9CA3AF' }};">
                            {{ number_format($disp, 0) }}
                        </span>
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700;
                                     background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                            {{ $item->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($items->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $items->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE ══ --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($items as $item)
    <div wire:key="card-{{ $item->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_strtoupper(mb_substr($item->maestroArticulo?->nombre ?? '?', 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->maestroArticulo?->nombre }}</p>
                <p style="font-size:11px; font-family:monospace; color:#7B6FE8; font-weight:600; margin:2px 0 0;">{{ $item->maestroArticulo?->codigo }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                {{ $item->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div style="padding:8px 14px; display:flex; gap:16px; flex-wrap:wrap;">
            <span style="font-size:12px; color:#7B6FE8; font-weight:600;">{{ $item->listaMaestra?->cycle?->code }}</span>
            @if($item->maestroArticulo?->categoria)
            <span style="font-size:12px; color:#6B7280;">{{ $item->maestroArticulo->categoria->descripcion }}</span>
            @endif
            @if($item->maestroArticulo?->unidad)
            <span style="font-size:12px; color:#6B7280;">{{ $item->maestroArticulo->unidad->name }}</span>
            @endif
            <span style="font-size:12px; font-weight:600; color:#111827;">Stock: {{ number_format((float)$item->stock_actual, 0) }}</span>
        </div>
    </div>
    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay artículos en el stock.</p>
    @endforelse
    @if($items->hasPages())
    <div style="padding-top:8px;">{{ $items->links() }}</div>
    @endif
</div>

{{-- ══ MODAL: Listas de Precios ══ --}}
@if($stockModalItemId)
<div style="position:fixed; inset:0; z-index:200; display:flex; align-items:center; justify-content:center; padding:16px; background:rgba(0,0,0,.45);"
     @keydown.escape.window="$wire.closeStockModal()">
    <div style="background:#fff; border-radius:20px; width:100%; max-width:640px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22);">

        {{-- Header --}}
        <div style="padding:16px 20px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; gap:10px; flex-shrink:0;">
            <div style="width:36px; height:36px; border-radius:10px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:13px; font-weight:800; color:#7B6FE8; margin:0;">Stock del artículo por ciclo</p>
                <p style="font-size:12px; color:#9CA3AF; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $stockModalItem?->maestroArticulo?->codigo }} — {{ $stockModalItem?->maestroArticulo?->nombre }}
                </p>
            </div>
            <button wire:click="closeStockModal"
                    style="width:32px; height:32px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:9px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="overflow:auto; flex:1; padding:16px 20px;">
            @if($stockModalRows->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <svg width="40" height="40" fill="none" stroke="#D1D5DB" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p style="font-size:13px; color:#9CA3AF;">Este artículo no tiene stock en ningún ciclo.</p>
            </div>
            @else
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                        <th style="padding:9px 12px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Ciclo</th>
                        <th style="padding:9px 12px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Lista</th>
                        <th style="padding:9px 12px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Stock Ini.</th>
                        <th style="padding:9px 12px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Stock Act.</th>
                        <th style="padding:9px 12px; text-align:right; font-size:11px; font-weight:700; color:#6366F1; text-transform:uppercase; letter-spacing:.4px;">Comprom.</th>
                        <th style="padding:9px 12px; text-align:right; font-size:11px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:.4px;">Disponible</th>
                        <th style="padding:9px 12px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockModalRows as $row)
                    @php $dispRow = $row->stockDisponible(); $esCurrent = $row->id === $stockModalItemId; @endphp
                    <tr style="border-bottom:1px solid #F3F4F6; {{ $esCurrent ? 'background:#F5F3FF;' : '' }}">
                        <td style="padding:10px 12px; white-space:nowrap;">
                            <span style="font-size:12px; font-family:monospace; font-weight:700; color:#7B6FE8;">{{ $row->listaMaestra?->cycle?->code ?? '—' }}</span>
                        </td>
                        <td style="padding:10px 12px;">
                            <span style="font-size:12px; color:#374151;">{{ $row->listaMaestra?->name ?? '—' }}</span>
                        </td>
                        <td style="padding:10px 12px; text-align:right;">
                            <span style="font-size:13px; font-weight:600; color:#111827; font-family:monospace;">{{ number_format((float)$row->stock_inicial, 0) }}</span>
                        </td>
                        <td style="padding:10px 12px; text-align:right;">
                            <span style="font-size:13px; font-weight:600; color:#111827; font-family:monospace;">{{ number_format((float)$row->stock_actual, 0) }}</span>
                        </td>
                        <td style="padding:10px 12px; text-align:right;">
                            <span style="font-size:13px; font-weight:700; color:#6366F1; font-family:monospace;">{{ number_format((float)$row->stock_comprometido, 0) }}</span>
                        </td>
                        <td style="padding:10px 12px; text-align:right;">
                            <span style="font-size:13px; font-weight:700; font-family:monospace; color:{{ $dispRow > 0 ? '#059669' : '#9CA3AF' }};">{{ number_format($dispRow, 0) }}</span>
                        </td>
                        <td style="padding:10px 12px; text-align:center;">
                            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700;
                                         background:{{ $row->active ? '#D1FAE5' : '#F3F4F6' }};
                                         color:{{ $row->active ? '#059669' : '#9CA3AF' }};">
                                {{ $row->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- Footer --}}
        <div style="padding:14px 20px; border-top:1px solid #EDE9FE; display:flex; justify-content:flex-end; flex-shrink:0;">
            <button wire:click="closeStockModal"
                    style="height:36px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif

</div>
