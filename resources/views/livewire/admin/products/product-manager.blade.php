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
            <div style="min-width:100px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
                <input wire:model="newCode" type="text" placeholder="Ej. PRD001"
                       style="width:100%; {{ $iS }} font-family:monospace; text-transform:uppercase;">
                @error('newCode') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:2; min-width:180px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="newName" type="text" placeholder="Nombre del producto"
                       style="width:100%; {{ $iS }}">
                @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
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
            <div style="min-width:130px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Categoría</label>
                <select wire:model="newCategoriaId" style="width:100%; {{ $iS }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Productos registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $products->total() }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <button type="button" wire:click="$refresh"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualizar
            </button>
            <button type="button"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Importar
            </button>
            <button type="button"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        @if ($products->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay productos registrados.</p>
        @else
        <table style="table-layout:fixed; width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:60px;">
                <col style="width:110px;">
                <col style="width:200px;">
                <col style="width:110px;">
                <col style="width:140px;">
                <col style="width:90px;">
                <col style="width:150px;">
            </colgroup>
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 12px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Img</span>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:80px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Código</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:100px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Unidad</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:90px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Categoría</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:center; position:relative; user-select:none; overflow:hidden; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)

                {{-- Fila edición inline --}}
                @if ($editingId === $p->id)
                <tr wire:key="edit-{{ $p->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
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
                    <td style="padding:7px 10px;">
                        <span style="font-family:monospace; font-size:12px; color:#6B7280;">{{ $p->code }}</span>
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editName" type="text" placeholder="Nombre del producto"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
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
                        <select wire:model="editCategoriaId"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 6px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="">— Categoría —</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                    <td style="padding:7px 10px; text-align:center;">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="saveEdit"
                                    style="height:30px; padding:0 10px; background:#7B6FE8; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    style="height:30px; padding:0 8px; background:#F3F4F6; color:#6B7280; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                <tr wire:key="p-{{ $p->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

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

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->code }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->name }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->unidad?->abreviatura ?? $p->unidad?->name ?? '—' }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $p->categoria?->name ?? '—' }}</span>
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;
                                     background:{{ $p->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $p->active ? '#059669' : '#9CA3AF' }};">
                            {{ $p->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="startEdit({{ $p->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
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
<div class="sm:hidden space-y-3">
    @forelse ($products as $p)

    @if ($editingId === $p->id)
    {{-- Card edición mobile --}}
    <div wire:key="card-edit-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:11px 14px; display:flex; align-items:center; justify-content:space-between;">
            <p style="font-size:13px; font-weight:700; color:#5B21B6; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->name }}</p>
            <button wire:click="cancelEdit" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:3px; flex-shrink:0;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:4px;">Nombre</label>
                <input wire:model="editName" type="text"
                       style="width:100%; border:1px solid #D8D3F8; border-radius:8px; padding:8px 10px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
                @error('editName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:4px;">Unidad</label>
                    <select wire:model="editUnidadId"
                            style="width:100%; border:1px solid #D8D3F8; border-radius:8px; padding:8px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                        <option value="">— Unidad —</option>
                        @foreach ($unidades as $u)
                            <option value="{{ $u->id }}">{{ $u->abreviatura ?? $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:4px;">Categoría</label>
                    <select wire:model="editCategoriaId"
                            style="width:100%; border:1px solid #D8D3F8; border-radius:8px; padding:8px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                        <option value="">— Categoría —</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:4px;">Estado</label>
                <select wire:model="editActive"
                        style="width:100%; border:1px solid #D8D3F8; border-radius:8px; padding:8px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            <div style="display:flex; gap:8px; padding-top:4px;">
                <button wire:click="saveEdit"
                        style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                    Guardar
                </button>
                <button wire:click="cancelEdit"
                        style="flex:1; height:36px; background:#FEF2F2; color:#EF4444; border:1px solid #FEE2E2; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #F3F4F6; box-shadow:0 1px 4px rgba(0,0,0,.06); padding:14px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
            @if($p->foto_url)
            <img src="{{ $p->foto_url }}" alt="{{ $p->name }}"
                 style="width:44px; height:44px; border-radius:10px; object-fit:cover; border:1px solid #E5E7EB; flex-shrink:0;"
                 onerror="this.style.display='none';">
            @else
            <div style="width:44px; height:44px; border-radius:10px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#A78BFA" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->name }}</p>
                <p style="font-size:11px; font-family:monospace; color:#9CA3AF; margin:2px 0 0;">{{ $p->code }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $p->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $p->active ? '#059669' : '#9CA3AF' }};">
                {{ $p->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <p style="font-size:12px; color:#6B7280; margin:0 0 12px;">
            {{ $p->categoria?->name ?? '—' }} · {{ $p->unidad?->abreviatura ?? $p->unidad?->name ?? '—' }}
        </p>
        <div style="display:flex; gap:7px;">
            <button wire:click="startEdit({{ $p->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
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
