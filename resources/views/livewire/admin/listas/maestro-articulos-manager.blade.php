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
    <select wire:model.live="filterStatus" class="w-full sm:w-auto"
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
        Nuevo artículo
    </button>
</div>
@endif

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
        <div style="display:flex; align-items:flex-end; gap:8px; padding-bottom:0;">
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
                <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Guardar</button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">Cancelar</button>
            @else
                <button wire:click="startEdit({{ $selectedId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Editar</button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        @if ($articulos->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay artículos maestros registrados.</p>
        @else
        @php
            $sortCols = ['Código'=>'codigo','Nombre'=>'nombre','Categoría'=>'categoria_id','Unidad'=>'unidad_id','Estado'=>'active'];
        @endphp
        <table style="width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF; white-space:nowrap;">#</th>
                    <th style="padding:10px 12px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Img</span>
                    </th>
                    @foreach($sortCols as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:{{ $label==='Estado' ? 'center' : 'left' }}; position:relative; user-select:none; overflow:hidden; min-width:80px; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            {{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($articulos as $a)

                {{-- Fila edición --}}
                @if ($editingId === $a->id)
                @php $eI = 'height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; width:100%;'; @endphp
                <tr wire:key="edit-{{ $a->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:#F8F7FF; white-space:nowrap;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $articulos->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 12px; text-align:center;">
                        @if($a->foto_url)
                        <img src="{{ $a->foto_url }}" alt="{{ $a->nombre }}"
                             style="width:36px; height:36px; border-radius:8px; object-fit:cover; border:1px solid #E5E7EB;"
                             onerror="this.style.display='none';">
                        @else
                        <div style="width:36px; height:36px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                            <svg width="16" height="16" fill="none" stroke="#A78BFA" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td style="padding:7px 10px; min-width:110px;">
                        <input wire:model="editCodigo" type="text" maxlength="50" style="{{ $eI }} font-family:monospace; text-transform:uppercase;">
                        @error('editCodigo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; min-width:180px;">
                        <input wire:model="editNombre" type="text" maxlength="255" style="{{ $eI }}">
                        @error('editNombre') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; min-width:130px;">
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
                    <td style="padding:7px 10px;">
                        <select wire:model="editActive" style="{{ $eI }} padding:0 6px; cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                <tr wire:key="a-{{ $a->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    @php $sel = $selectedId === $a->id; @endphp
                    <td class="col-row-num" style="padding:6px 8px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $sel ? '#F5F3FF' : '#fff' }}; white-space:nowrap;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedId === {{ $a->id }}"
                                   @click="$wire.selectedId === {{ $a->id }} ? $wire.set('selectedId', null) : $wire.selectArticulo({{ $a->id }})"
                                   :disabled="{{ $editingId && $editingId !== $a->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $a->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $articulos->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:8px 12px; text-align:center;">
                        @if($a->foto_url)
                        <img src="{{ $a->foto_url }}" alt="{{ $a->nombre }}"
                             style="width:38px; height:38px; border-radius:9px; object-fit:cover; border:1px solid #E5E7EB;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        <div style="width:38px; height:38px; border-radius:9px; background:#EDE9FE; display:{{ $a->foto_url ? 'none' : 'flex' }}; align-items:center; justify-content:center; margin:0 auto;">
                            <svg width="16" height="16" fill="none" stroke="#A78BFA" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </td>

                    <td style="padding:10px 14px;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $a->codigo }}</span>
                    </td>

                    <td style="padding:10px 14px; min-width:180px;">
                        <span style="font-size:13px; font-weight:500; color:#111827;">{{ $a->nombre }}</span>
                        @if($a->descripcion)
                        <p style="font-size:11px; color:#9CA3AF; margin:2px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">{{ $a->descripcion }}</p>
                        @endif
                    </td>

                    <td style="padding:10px 14px;">
                        @if($a->categoria)
                        <span style="font-size:12px; color:#374151;">{{ $a->categoria->descripcion }}</span>
                        @else
                        <span style="color:#D1D5DB; font-size:13px;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px;">
                        @if($a->unidad)
                        <span style="font-size:12px; color:#374151;">{{ $a->unidad->name }}</span>
                        @else
                        <span style="color:#D1D5DB; font-size:13px;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $a->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $a->active ? '#059669' : '#9CA3AF' }};">
                            {{ $a->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
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
                <select wire:model="editActive" style="{{ $iM }} cursor:pointer; padding:0 8px;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div style="display:flex; gap:8px; padding-top:2px;">
                <button wire:click="saveEdit"
                        style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">Guardar</button>
                <button wire:click="cancelEdit"
                        style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
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
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay artículos maestros registrados.</p>
    @endforelse
    @if ($articulos->hasPages())
    <div style="padding-top:8px;">{{ $articulos->links() }}</div>
    @endif
</div>

</div>
