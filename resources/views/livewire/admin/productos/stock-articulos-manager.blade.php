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
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
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

        {{-- BUSCAR ARTÍCULO --}}
        <div style="min-width:260px; flex:2;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código - Descripción *</label>
            @if(!$formListaMaestraId)
            <div style="height:38px; border:1px solid #E5E7EB; border-radius:8px; padding:0 12px; font-size:13px; color:#9CA3AF; background:#F9FAFB; display:flex; align-items:center;">
                Primero seleccione un ciclo
            </div>
            @else
            <div style="position:relative;">
                <input wire:model.live.debounce.300ms="searchMaestro" type="text"
                       placeholder="Buscar por código o nombre..."
                       style="{{ $iS }} padding-right:30px;">
                @if($searchMaestro)
                <button wire:click="$set('searchMaestro', '')"
                        style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#9CA3AF; padding:2px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
            </div>
            @if($selectedMaestroId)
            @php $mSel = $maestrosDisponibles->firstWhere('id', $selectedMaestroId) ?? \App\Models\MaestroArticulo::find($selectedMaestroId) @endphp
            <div style="margin-top:6px; padding:8px 12px; background:#F0FDF4; border:1px solid #6EE7B7; border-radius:8px; font-size:12px; font-weight:600; color:#065F46; display:flex; align-items:center; justify-content:space-between;">
                <span>{{ $mSel?->codigo }} — {{ $mSel?->nombre }}</span>
                <button wire:click="$set('selectedMaestroId', null)" style="background:transparent; border:none; cursor:pointer; color:#6B7280; padding:0 0 0 8px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @elseif($maestrosDisponibles->isNotEmpty())
            <div style="margin-top:4px; border:1px solid #E5E7EB; border-radius:8px; overflow:hidden; max-height:180px; overflow-y:auto;">
                @foreach($maestrosDisponibles as $m)
                <button wire:click="selectMaestro({{ $m->id }})"
                        style="width:100%; text-align:left; padding:8px 12px; border:none; border-bottom:1px solid #F3F4F6; background:#fff; font-size:13px; color:#374151; cursor:pointer; display:block;"
                        @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background='#fff'">
                    <span style="font-family:monospace; font-weight:700; color:#7B6FE8;">{{ $m->codigo }}</span>
                    <span style="color:#6B7280;"> — {{ $m->nombre }}</span>
                </button>
                @endforeach
            </div>
            @elseif($formListaMaestraId && !$selectedMaestroId)
            <p style="font-size:12px; color:#9CA3AF; margin-top:4px;">
                {{ $searchMaestro ? 'Sin resultados.' : 'No hay artículos disponibles para este ciclo.' }}
            </p>
            @endif
            @error('selectedMaestroId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            @endif
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

        {{-- ACCIONES --}}
        <div style="display:flex; align-items:flex-end; gap:8px;">
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

    <div style="padding:10px 18px; display:flex; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Stock de artículos</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:8px;">{{ $items->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
        @if($items->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay artículos en el stock.</p>
        @else
        <table style="width:100%; min-width:800px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF;">#</th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Ciclo</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Código</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Categoría</span></th>
                    <th style="padding:10px 14px; text-align:left;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Unidad</span></th>
                    <th style="padding:10px 14px; text-align:right;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Ini.</span></th>
                    <th style="padding:10px 14px; text-align:right;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Stock Act.</span></th>
                    <th style="padding:10px 14px; text-align:center;"><span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr wire:key="item-{{ $item->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    <td class="col-row-num" style="padding:10px 8px; text-align:center; position:sticky; left:0; z-index:2; background:#fff;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $items->firstItem() + $loop->index }}</span>
                    </td>

                    <td style="padding:10px 14px; white-space:nowrap;">
                        <span style="font-size:12px; font-family:monospace; font-weight:600; color:#7B6FE8;">{{ $item->listaMaestra?->cycle?->code }}</span>
                        <span style="font-size:11px; color:#9CA3AF; display:block;">{{ $item->listaMaestra?->name }}</span>
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

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700;
                                     background:{{ $item->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $item->active ? '#059669' : '#9CA3AF' }};">
                            {{ $item->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
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

</div>
