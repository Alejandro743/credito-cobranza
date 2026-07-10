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
{{-- ═══════════════════════════════════════════════════════ GRUPOS MODE ══ --}}
@if ($mode === 'grupos' && $viewingDerivada)
<div>
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Grupos — {{ $viewingDerivada->name }}</h2>
            <p class="text-xs text-gray-500">Maestra: {{ $viewingDerivada->listaMaestra->name ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Asignar grupo</h3>
        <div class="flex gap-3">
            <select wire:model="addGroupId" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Seleccionar grupo —</option>
                @foreach ($availableGroups as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>
            <button wire:click="addGrupo" class="bg-mint-500 hover:bg-mint-600 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors whitespace-nowrap">
                + Asignar
            </button>
        </div>
        @error('addGroupId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">Grupos asignados ({{ $assignedGroups->count() }})</span>
        </div>
        @if ($assignedGroups->isEmpty())
            <div class="py-12 text-center text-gray-400 text-sm">Sin grupos asignados.</div>
        @else
        <ul class="divide-y divide-gray-50">
            @foreach ($assignedGroups as $g)
            <li class="flex items-center justify-between px-5 py-3">
                <span class="text-sm font-medium text-gray-800">{{ $g->name }}</span>
                <button wire:click="removeGrupo({{ $g->id }})"
                        wire:confirm="¿Quitar este grupo de la lista?"
                        class="text-red-400 hover:text-red-600 transition-colors p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ ITEMS MODE ══ --}}
@elseif ($mode === 'items' && $viewingDerivada)
<div>
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Productos — {{ $viewingDerivada->name }}</h2>
            <p class="text-xs text-gray-500">Maestra: {{ $viewingDerivada->listaMaestra->name ?? '—' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Agregar producto de la maestra</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Producto *</label>
                <select wire:model="addMaestraItemId" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="">— Seleccionar —</option>
                    @foreach ($maestraItems as $mi)
                        <option value="{{ $mi->id }}">{{ $mi->product->name ?? '—' }} · S/ {{ number_format($mi->precio_base, 2) }}</option>
                    @endforeach
                </select>
                @error('addMaestraItemId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Descuento (S/)</label>
                <input wire:model="addDescuento" type="number" step="0.01" min="0" placeholder="0.00"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('addDescuento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Stock Asignado</label>
                <input wire:model="addStockAsignado" type="number" step="0.01" min="0" placeholder="0"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                @error('addStockAsignado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="mt-3 flex justify-end">
            <button wire:click="addItem" class="bg-mint-500 hover:bg-mint-600 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
                + Agregar
            </button>
        </div>
    </div>

    <div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 280px);">
        <div style="padding:10px 18px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Productos</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $items->count() }}</span>
        </div>
        @if ($items->isEmpty())
            <div style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">Sin productos. Agrega el primero.</div>
        @else
        <div style="overflow:auto; flex:1;">
            <table style="table-layout:fixed; width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
                <colgroup>
                    <col style="width:44px;">
                    <col style="width:220px;">
                    <col style="width:110px;">
                    <col style="width:110px;">
                    <col style="width:110px;">
                    <col style="width:120px;">
                    <col style="width:90px;">
                    <col style="width:80px;">
                </colgroup>
                <thead style="position:sticky; top:0; z-index:10;">
                    <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                        <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                        <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Producto</th>
                        <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Precio Base</th>
                        <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Descuento</th>
                        <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Precio Final</th>
                        <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Asignado</th>
                        <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</th>
                        <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr wire:key="item-{{ $item->id }}" style="border-bottom:1px solid #F3F4F6; transition:background .1s; {{ !$item->active ? 'opacity:.55;' : '' }}"
                        @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                        <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $loop->iteration }}</td>
                        <td style="padding:10px 14px; overflow:hidden;">
                            <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ strtoupper($item->maestraItem->product->name ?? '—') }}</span>
                        </td>
                        <td style="padding:10px 14px; text-align:right; font-size:13px; color:#374151; white-space:nowrap;">S/ {{ number_format($item->maestraItem->precio_base ?? 0, 2) }}</td>
                        <td style="padding:10px 14px; text-align:right; font-size:13px; color:#374151; white-space:nowrap;">S/ {{ number_format($item->descuento, 2) }}</td>
                        <td style="padding:10px 14px; text-align:right; font-size:13px; color:#374151; white-space:nowrap;">S/ {{ number_format($item->precio_final, 2) }}</td>
                        <td style="padding:10px 14px; text-align:right; font-size:13px; color:#374151; white-space:nowrap;">{{ number_format($item->stock_asignado, 2) }}</td>
                        <td style="padding:10px 14px; text-align:center;">
                            <button wire:click="toggleItemActive({{ $item->id }})"
                                    style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; border:none; cursor:pointer; white-space:nowrap;
                                        background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                                        color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                                {{ $item->active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td style="padding:10px 14px; text-align:center;">
                            <button wire:click="removeItem({{ $item->id }})"
                                    wire:confirm="¿Eliminar este producto de la lista?"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #FEE2E2; background:#FFF1F1; color:#EF4444; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#FEE2E2'" @mouseleave="$el.style.background='#FFF1F1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ FORM MODE ══ --}}
@elseif ($mode === 'form')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Lista Derivada' : 'Nueva Lista Derivada' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lista Maestra *</label>
            <select wire:model="lista_maestra_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Seleccionar lista maestra —</option>
                @foreach ($maestras as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} ({{ ucfirst($m->estado) }})</option>
                @endforeach
            </select>
            @error('lista_maestra_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
            <input wire:model="name" type="text" placeholder="Lista VIP Ene-2026"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Estado</label>
            <select wire:model="estado" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="activa">Activa</option>
                <option value="cerrada">Cerrada</option>
            </select>
        </div>
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" wire:click="backToList" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Cancelar</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white transition-colors">
                {{ $editing ? 'Guardar cambios' : 'Crear lista' }}
            </button>
        </div>
    </form>
</div>

{{-- ═══════════════════════════════════════════════════════ LIST MODE ══ --}}
@else
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-lavanda-50 border border-lavanda-100 rounded-2xl p-4">
        <p class="text-xs text-lavanda-500 font-medium uppercase tracking-wide">Total</p>
        <p class="text-3xl font-bold text-lavanda-700 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-mint-50 border border-mint-100 rounded-2xl p-4">
        <p class="text-xs text-mint-500 font-medium uppercase tracking-wide">Activas</p>
        <p class="text-3xl font-bold text-mint-700 mt-1">{{ $stats['activa'] }}</p>
    </div>
    <div class="bg-celeste-50 border border-celeste-100 rounded-2xl p-4">
        <p class="text-xs text-celeste-500 font-medium uppercase tracking-wide">Cerradas</p>
        <p class="text-3xl font-bold text-celeste-700 mt-1">{{ $stats['cerrada'] }}</p>
    </div>
</div>

<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar lista derivada..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <button wire:click="create" class="bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        + Nueva Lista Derivada
    </button>
</div>

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 220px);">
    @if ($derivadas->isEmpty())
        <div style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay listas derivadas.</div>
    @else
    @php
    $fI = 'height:22px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px; width:100%; outline:none; box-sizing:border-box; background:#fff; margin-top:4px; color:#374151; font-weight:400;';
    @endphp
    <div style="overflow:auto; flex:1;">
        <table style="table-layout:fixed; width:100%; min-width:620px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:44px;">
                <col style="width:220px;">
                <col style="width:200px;">
                <col style="width:100px;">
                <col style="width:110px;">
                <col style="width:130px;">
            </colgroup>
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; vertical-align:top;">#</th>

                    {{-- Nombre --}}
                    <th wire:click="toggleSort('name')"
                        style="padding:8px 14px 6px; text-align:left; user-select:none; cursor:pointer; vertical-align:top; {{ $sortBy==='name' ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $sortBy==='name' ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                            Nombre
                            @if($sortBy==='name' && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($sortBy==='name') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <input wire:model.live.debounce.300ms="filterNombre" @click.stop type="text" placeholder="Filtrar..." style="{{ $fI }}">
                    </th>

                    {{-- Lista Maestra --}}
                    <th style="padding:8px 14px 6px; text-align:left; vertical-align:top;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:block;">Lista Maestra</span>
                        <input wire:model.live.debounce.300ms="filterListaMaestra" type="text" placeholder="Filtrar..." style="{{ $fI }}">
                    </th>

                    {{-- Estado --}}
                    <th wire:click="toggleSort('estado')"
                        style="padding:8px 14px 6px; text-align:center; user-select:none; cursor:pointer; vertical-align:top; {{ $sortBy==='estado' ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $sortBy==='estado' ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; justify-content:center; gap:5px;">
                            Estado
                            @if($sortBy==='estado' && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($sortBy==='estado') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <select wire:model.live="filterEstado" @click.stop style="{{ $fI }} padding:0 4px; cursor:pointer;">
                            <option value="">Todos</option>
                            <option value="activa">Activa</option>
                            <option value="cerrada">Cerrada</option>
                        </select>
                    </th>

                    {{-- Creada --}}
                    <th wire:click="toggleSort('created_at')"
                        style="padding:8px 14px 6px; text-align:center; user-select:none; cursor:pointer; vertical-align:top; {{ $sortBy==='created_at' ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $sortBy==='created_at' ? '#EDE9FE' : '' }}'">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; justify-content:center; gap:5px;">
                            Creada
                            @if($sortBy==='created_at' && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($sortBy==='created_at') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                        <input wire:model.live.debounce.300ms="filterCreada" @click.stop type="text" placeholder="dd/mm/aaaa" style="{{ $fI }}">
                    </th>

                    {{-- Acciones --}}
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; vertical-align:top;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($derivadas as $d)
                <tr wire:key="d-{{ $d->id }}" style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $derivadas->firstItem() + $loop->index }}</td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ strtoupper($d->name) }}</span>
                    </td>
                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $d->listaMaestra->name ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                            background:{{ $d->estado === 'activa' ? '#D1FAE5' : '#F3F4F6' }};
                            color:{{ $d->estado === 'activa' ? '#059669' : '#9CA3AF' }};">
                            {{ ucfirst($d->estado) }}
                        </span>
                    </td>
                    <td style="padding:10px 14px; text-align:center; font-size:13px; color:#374151;">{{ $d->created_at->format('d/m/Y') }}</td>
                    <td style="padding:10px 14px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="viewItems({{ $d->id }})" title="Productos"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #BFDBFE; background:#EFF6FF; color:#3B82F6; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#DBEAFE'" @mouseleave="$el.style.background='#EFF6FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </button>
                            <button wire:click="viewGrupos({{ $d->id }})" title="Grupos"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #A7F3D0; background:#ECFDF5; color:#059669; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#D1FAE5'" @mouseleave="$el.style.background='#ECFDF5'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                            <button wire:click="edit({{ $d->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($derivadas->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $derivadas->links() }}</div>
    @endif
    @endif
</div>
@endif

@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif
</div>
