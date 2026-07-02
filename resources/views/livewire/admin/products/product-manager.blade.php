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

    <select wire:model.live="filterCategoriaId" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todas las categorías</option>
        @foreach ($categorias as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <select wire:model.live="filterStatus" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo producto
    </button>
</div>
@endif

{{-- ══ FORM: Nuevo producto ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Nuevo Producto
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;">
        <div style="display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="min-width:130px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Ciclo</label>
                <select wire:model="newCicloId" style="width:100%; {{ $iS }} cursor:pointer;">
                    <option value="">— Sin ciclo —</option>
                    @foreach ($ciclos as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:100px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
                <input wire:model="newCode" type="text" placeholder="Ej. PRD001"
                       style="width:100%; {{ $iS }} font-family:monospace; text-transform:uppercase;">
                @error('newCode') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:140px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="newName" type="text" placeholder="Nombre del producto"
                       style="width:100%; {{ $iS }}">
                @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="width:90px; flex-shrink:0;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Stock Inicial</label>
                <input wire:model="newStockInicial" type="number" min="0" step="1" placeholder="0"
                       style="width:100%; {{ $iS }} text-align:right;">
            </div>
            <div style="min-width:130px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Categoría</label>
                <select wire:model="newCategoriaId" style="width:100%; {{ $iS }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:130px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Unidad</label>
                <select wire:model="newUnidadId" style="width:100%; {{ $iS }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($unidades as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}{{ $u->abreviatura ? ' ('.$u->abreviatura.')' : '' }}</option>
                    @endforeach
                </select>
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
        {{-- Nota Cloudinary --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:10px; padding:10px 14px; display:flex; align-items:flex-start; gap:8px;">
            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-size:12px; color:#5B21B6; margin:0; line-height:1.5;">
                La foto se gestiona en <strong>Cloudinary</strong>. Subí el archivo con el nombre del código del producto (ej: <span style="font-family:monospace;">38090810</span>) y aparecerá automáticamente.
            </p>
        </div>
    </div>
</div>
@endif

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-wrap:wrap;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Productos registrados</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $products->total() }}</span>
        @if($selectedProductId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedProductId)
                <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedProductId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
                <button wire:click="openStockModal({{ $selectedProductId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
                    Stock
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1; overflow-x:auto;">
        @if ($products->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay productos registrados.</p>
        @else
        @php
        $sortBefore = ['Código'=>'code','Nombre'=>'name'];
        $sortAfter  = ['Categoría'=>'categoria_id','Unidad'=>'unidad_id','Estado'=>'active'];
        @endphp
        <table style="width:100%; min-width:1100px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; width:50px; position:sticky; left:0; z-index:11; background:#F9F8FF;">#</th>
                    <th style="padding:10px 12px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Img</span>
                    </th>
                    <th style="padding:10px 10px; text-align:left;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Ciclo</span>
                    </th>
                    @foreach($sortBefore as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:left; position:relative; user-select:none; overflow:hidden; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    @endforeach
                    <th style="padding:10px 8px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Inicial</span>
                    </th>
                    <th style="padding:10px 8px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Asignado</span>
                    </th>
                    <th style="padding:10px 8px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Disponible</span>
                    </th>
                    @foreach($sortAfter as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:{{ $label === 'Estado' ? 'center' : 'left' }}; position:relative; user-select:none; overflow:hidden; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        @if($label !== 'Estado')
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)

                {{-- Fila edición inline --}}
                @if ($editingId === $p->id)
                <tr wire:key="edit-{{ $p->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#F8F7FF;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $products->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 12px; text-align:center;">
                        @if($p->foto_url)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->name }}"
                             style="width:36px; height:36px; border-radius:8px; object-fit:cover; border:1px solid #E5E7EB;"
                             onerror="this.style.display='none';">
                        @else
                        <div style="width:36px; height:36px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                            <svg width="16" height="16" fill="none" stroke="#A78BFA" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td style="padding:7px 6px;">
                        <select wire:model="editCicloId"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="">— —</option>
                            @foreach ($ciclos as $c)
                                <option value="{{ $c->id }}">{{ $c->code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editCode" type="text"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; font-family:monospace; text-transform:uppercase;">
                        @error('editCode') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editName" type="text" placeholder="Nombre del producto"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 4px;">
                        <input wire:model="editStockInicial" type="number" min="0" step="1" placeholder="0"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; text-align:right;">
                    </td>
                    <td></td><td></td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editCategoriaId"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="">— Categoría —</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editUnidadId"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="">— Unidad —</option>
                            @foreach ($unidades as $u)
                                <option value="{{ $u->id }}">{{ $u->abreviatura ?? $u->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editActive"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selP = $selectedProductId === $p->id; @endphp
                <tr wire:key="p-{{ $p->id }}"
                    style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $selP ? '#F5F3FF' : '' }}; {{ $selP ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selP ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selP ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selP ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedProductId === {{ $p->id }}"
                                   @click="$wire.selectedProductId === {{ $p->id }} ? $wire.set('selectedProductId', null) : $wire.selectProduct({{ $p->id }})"
                                   :disabled="{{ $editingId && $editingId !== $p->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $p->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $products->firstItem() + $loop->index }}</span>
                        </div>
                    </td>
                    <td style="padding:8px 12px; text-align:center;">
                        @if($p->foto_url)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->name }}"
                             style="width:38px; height:38px; border-radius:9px; object-fit:cover; border:1px solid #E5E7EB;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <div style="width:38px; height:38px; border-radius:9px; background:#EDE9FE; display:{{ $p->foto_url ? 'none' : 'flex' }}; align-items:center; justify-content:center; margin:0 auto;">
                            <svg width="16" height="16" fill="none" stroke="#A78BFA" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </td>

                    @php
                        $cicloDelProducto = $p->ciclos->first();
                        $stkTotal    = (float)($cicloDelProducto?->pivot->stock_total ?? 0);
                        $stkAsignado = $asignadoMap->get($p->id, 0);
                        $stkDisp     = max(0, $stkTotal - $stkAsignado);
                    @endphp
                    <td style="padding:10px 10px; overflow:hidden;">
                        @if($cicloDelProducto)
                        <span style="font-size:12px; color:#7c3aed; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $cicloDelProducto->code }}</span>
                        @else
                        <span style="font-size:12px; color:#D1D5DB;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->code }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($p->name)) }}</span>
                    </td>

                    <td style="padding:10px 8px; text-align:center; overflow:hidden;">
                        @if($cicloDelProducto)
                        <span style="font-size:13px; color:#374151;">{{ number_format($stkTotal, 0) }}</span>
                        @else
                        <span style="font-size:12px; color:#D1D5DB;">—</span>
                        @endif
                    </td>
                    <td style="padding:10px 8px; text-align:center; overflow:hidden;">
                        @if($cicloDelProducto)
                        <span style="font-size:13px; color:#6B7280;">{{ number_format($stkAsignado, 0) }}</span>
                        @else
                        <span style="font-size:12px; color:#D1D5DB;">—</span>
                        @endif
                    </td>
                    <td style="padding:10px 8px; text-align:center; overflow:hidden;">
                        @if($cicloDelProducto)
                        <span style="font-size:13px; color:{{ $stkDisp > 0 ? '#059669' : '#EF4444' }};">{{ number_format($stkDisp, 0) }}</span>
                        @else
                        <span style="font-size:12px; color:#D1D5DB;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($p->categoria?->name ?? '—')) }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->unidad?->abreviatura ?? $p->unidad?->name ?? '—' }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $p->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $p->active ? '#059669' : '#9CA3AF' }};">
                            {{ $p->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($products->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $products->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE: Cards ══ --}}
@php $iMp = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($products as $p)

    @if ($editingId === $p->id)
    {{-- CARD EDICIÓN --}}
    <div wire:key="card-edit-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">

        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Editando producto</span>
            <button wire:click="cancelEdit" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Código *</label>
                    <input wire:model="editCode" type="text" style="{{ $iMp }} font-family:monospace; text-transform:uppercase;">
                    @error('editCode')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editName" type="text" style="{{ $iMp }}">
                    @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Unidad</label>
                    <select wire:model="editUnidadId" style="{{ $iMp }} cursor:pointer;">
                        <option value="">— Unidad —</option>
                        @foreach ($unidades as $u)
                            <option value="{{ $u->id }}">{{ $u->abreviatura ?? $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Categoría</label>
                    <select wire:model="editCategoriaId" style="{{ $iMp }} cursor:pointer;">
                        <option value="">— Categoría —</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" style="{{ $iMp }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
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
    <div wire:key="card-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); padding:12px 14px;">

        {{-- Fila 1: avatar + nombre + estado --}}
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
            @if($p->foto_url)
            <img src="{{ $p->foto_url }}" alt="{{ $p->name }}"
                 style="width:30px; height:30px; border-radius:8px; object-fit:cover; border:1px solid #E5E7EB; flex-shrink:0;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:none; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; line-height:1;">{{ mb_substr($p->name, 0, 1) }}</span>
            </div>
            @else
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; line-height:1;">{{ mb_substr($p->name, 0, 1) }}</span>
            </div>
            @endif
            <span style="font-size:14px; font-weight:700; color:#111827; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $p->name }}</span>
            <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $p->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $p->active ? '#059669' : '#9CA3AF' }};">
                {{ $p->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Fila 2: código + categoría · unidad --}}
        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; margin-bottom:10px;">
            <span style="font-family:monospace; font-size:11px; font-weight:700; color:#7B6FE8; background:#F0EEFF; padding:2px 7px; border-radius:6px;">{{ $p->code }}</span>
            @if($p->categoria)
            <span style="color:#D1D5DB; font-size:11px;">·</span>
            <span style="font-size:12px; color:#6B7280;">{{ $p->categoria->name }}</span>
            @endif
            @if($p->unidad)
            <span style="color:#D1D5DB; font-size:11px;">·</span>
            <span style="font-family:monospace; font-size:11px; font-weight:600; color:#374151; background:#F3F4F6; padding:2px 7px; border-radius:6px;">{{ $p->unidad->abreviatura ?? $p->unidad->name }}</span>
            @endif
        </div>

        {{-- Botones --}}
        <div style="border-top:1px solid #F3F4F6; padding-top:10px; display:flex; gap:8px;">
            <button wire:click="startEdit({{ $p->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
            <button wire:click="openStockModal({{ $p->id }})"
                    style="flex:1; height:32px; border:1px solid #BAE6FD; border-radius:8px; background:#F0F9FF; color:#0284C7; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
                Stock
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay productos registrados.</p>
    @endforelse
    @if ($products->hasPages())
    <div style="padding-top:8px;">{{ $products->links() }}</div>
    @endif
</div>

{{-- ══ MODAL: Stock en listas ══ --}}
@if($stockModalProductId)
@php
    $alpineRows  = $stockModalRows->values()->toArray();
    $alpineEdits = $stockModalRows->mapWithKeys(fn($r) => [$r['item_id'] => (float)$r['stock_ini']])->toArray();
@endphp
<div x-data="{
        rows:  @js($alpineRows),
        edits: @js($alpineEdits),
        disp(row) {
            const same = this.rows.filter(r => r.ciclo_code === row.ciclo_code);
            const used = same.reduce((s, r) => s + (parseFloat(this.edits[r.item_id]) || 0), 0);
            return Math.max(0, row.stock_max - used);
        },
        maxFor(row) {
            const others = this.rows.filter(r => r.ciclo_code === row.ciclo_code && r.item_id !== row.item_id);
            const othersUsed = others.reduce((s, r) => s + (parseFloat(this.edits[r.item_id]) || 0), 0);
            return Math.max(0, row.stock_max - othersUsed);
        }
     }"
     style="position:fixed; inset:0; z-index:200; display:flex; align-items:center; justify-content:center; padding:16px; background:rgba(0,0,0,.45);"
     @keydown.escape.window="$wire.closeStockModal()">

    <div style="background:#fff; border-radius:20px; width:100%; max-width:680px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22);">

        {{-- Header --}}
        <div style="padding:16px 20px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; gap:10px; flex-shrink:0;">
            <div style="width:36px; height:36px; border-radius:10px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:13px; font-weight:800; color:#7B6FE8; margin:0;">Stock en listas</p>
                <p style="font-size:12px; color:#9CA3AF; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $stockModalProduct?->name }}</p>
            </div>
            <button wire:click="closeStockModal"
                    style="width:32px; height:32px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:9px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Error general --}}
        @error('stockModal')
        <div style="margin:12px 20px 0; background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <svg width="14" height="14" fill="none" stroke="#EF4444" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span style="font-size:12px; color:#B91C1C; font-weight:600;">{{ $message }}</span>
        </div>
        @enderror

        {{-- Body --}}
        <div style="overflow:auto; flex:1; padding:16px 20px;">
            @if($stockModalRows->isEmpty())
            <div style="text-align:center; padding:40px 20px;">
                <svg width="40" height="40" fill="none" stroke="#D1D5DB" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p style="font-size:13px; color:#9CA3AF;">Este producto no está en ninguna lista maestra.</p>
            </div>
            @else
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <colgroup>
                    <col style="width:80px;">
                    <col style="width:auto;">
                    <col style="width:90px;">
                    <col style="width:100px;">
                    <col style="width:110px;">
                </colgroup>
                <thead>
                    <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                        <th style="padding:9px 10px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Ciclo</th>
                        <th style="padding:9px 10px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Lista</th>
                        <th style="padding:9px 10px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Total ciclo</th>
                        <th style="padding:9px 10px; text-align:center; font-size:11px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:.4px;">Disponible</th>
                        <th style="padding:9px 10px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px;">Asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockModalRows as $idx => $row)
                    <tr style="border-bottom:1px solid #F3F4F6;">
                        <td style="padding:10px 10px;">
                            <span style="font-size:12px; font-weight:700; color:#7C3AED; background:#F0EEFF; padding:2px 8px; border-radius:6px; font-family:monospace;">{{ $row['ciclo_code'] }}</span>
                        </td>
                        <td style="padding:10px 10px;">
                            <span style="font-size:13px; color:#111827;">{{ $row['lista_name'] }}</span>
                        </td>
                        <td style="padding:10px 10px; text-align:center;">
                            <span style="font-size:13px; color:#374151; font-weight:600;">{{ number_format($row['stock_max'], 0) }}</span>
                        </td>
                        {{-- Disponible reactivo: se recalcula al escribir en cualquier input del mismo ciclo --}}
                        <td style="padding:10px 10px; text-align:center;">
                            <span x-text="disp(rows[{{ $idx }}]).toFixed(0)"
                                  :style="'font-size:14px; font-weight:700; color:' + (disp(rows[{{ $idx }}]) > 0 ? '#059669' : '#EF4444') + ';'">
                            </span>
                        </td>
                        <td style="padding:8px 10px; text-align:center;">
                            <input x-model.number="edits[{{ $row['item_id'] }}]"
                                   :max="maxFor(rows[{{ $idx }}])"
                                   type="number" step="0.01" placeholder="0"
                                   onkeydown="if(event.key==='-'||event.key==='e'||event.key==='E')event.preventDefault()"
                                   oninput="if(parseFloat(this.value)<0)this.value=0"
                                   style="width:88px; height:32px; border:1px solid #EDE9FE; border-radius:8px; padding:0 8px; font-size:13px; font-weight:600; text-align:center; outline:none; background:#F8F7FF; color:#374151; box-sizing:border-box;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- Footer --}}
        <div style="padding:14px 20px; border-top:1px solid #EDE9FE; display:flex; align-items:center; justify-content:flex-end; gap:8px; flex-shrink:0;">
            <button wire:click="closeStockModal"
                    style="height:36px; padding:0 20px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
            @if($stockModalRows->isNotEmpty())
            <button @click="$wire.saveStockModal(edits)"
                    style="height:36px; padding:0 22px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Guardar cambios
            </button>
            @endif
        </div>
    </div>
</div>
@endif

<script>
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
</script>

</div>
