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
        <option value="1">Activas</option>
        <option value="0">Inactivas</option>
    </select>

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva unidad
    </button>
</div>
@endif

{{-- ══ FORM: Nueva unidad ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            Nueva Unidad
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div style="flex:1; min-width:180px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
            <input wire:model="newName" type="text" placeholder="Ej. Kilogramo"
                   style="width:100%; {{ $iS }}">
            @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:140px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Abreviatura</label>
            <input wire:model="newAbreviatura" type="text" maxlength="20" placeholder="Ej. kg"
                   style="width:100%; {{ $iS }} font-family:monospace;">
            @error('newAbreviatura') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
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
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Unidades registradas</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $unidades->total() }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:5px;">
            @if($selectedUnidadId)
            @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
            <div style="display:flex; align-items:center; gap:5px; padding-right:8px; border-right:1px solid #E5E7EB;">
                @if($editingId === $selectedUnidadId)
                    <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Guardar</button>
                    <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">Cancelar</button>
                @else
                    <button wire:click="startEdit({{ $selectedUnidadId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">Editar</button>
                @endif
            </div>
            @endif
            <button type="button" wire:click="$refresh"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualizar
            </button>
        </div>
    </div>

    <div style="overflow:auto; flex:1;">
        @if ($unidades->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay unidades registradas.</p>
        @else
        @php $sortCols = ['Código'=>'code','Nombre'=>'name','Abreviatura'=>'abreviatura','Estado'=>'active']; @endphp
        <table style="width:100%; min-width:560px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="width:50px; padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; position:sticky; left:0; z-index:11; background:#F9F8FF; white-space:nowrap;">#</th>
                    @foreach($sortCols as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px; text-align:{{ $label==='Estado' ? 'center' : 'left' }}; position:relative; user-select:none; overflow:hidden; min-width:70px; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
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
                @foreach ($unidades as $u)

                {{-- Fila edición inline --}}
                @if ($editingId === $u->id)
                <tr wire:key="edit-{{ $u->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:#F8F7FF; white-space:nowrap;">
                        <span style="font-size:12px; font-weight:700; color:#374151;">{{ $unidades->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 10px;">
                        <span style="font-family:monospace; font-size:12px; color:#6B7280;">{{ $u->code }}</span>
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editName" type="text" placeholder="Nombre *"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editAbreviatura" type="text" maxlength="20" placeholder="kg"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; font-family:monospace;">
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
                <tr wire:key="u-{{ $u->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    @php $selU = $selectedUnidadId === $u->id; @endphp
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selU ? '#F5F3FF' : '#fff' }}; white-space:nowrap;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedUnidadId === {{ $u->id }}"
                                   @click="$wire.selectedUnidadId === {{ $u->id }} ? $wire.set('selectedUnidadId', null) : $wire.selectUnidad({{ $u->id }})"
                                   :disabled="{{ $editingId && $editingId !== $u->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $u->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:12px; font-weight:700; color:#374151;">{{ $unidades->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $u->code }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        <span style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($u->name)) }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden;">
                        @if($u->abreviatura)
                        <span style="display:inline-block; padding:2px 8px; border-radius:6px; background:#F3F4F6; font-family:monospace; font-size:12px; font-weight:600; color:#374151;">{{ $u->abreviatura }}</span>
                        @else
                        <span style="font-size:13px; color:#D1D5DB;">—</span>
                        @endif
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $u->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $u->active ? '#059669' : '#9CA3AF' }};">
                            {{ $u->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>

                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($unidades->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $unidades->links() }}</div>
    @endif
</div>

{{-- ══ MOBILE: Cards ══ --}}
@php $iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($unidades as $u)

    @if ($editingId === $u->id)
    {{-- CARD EDICIÓN --}}
    <div wire:key="card-edit-{{ $u->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">

        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Editando unidad</span>
            <button wire:click="cancelEdit" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:2px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editName" type="text" style="{{ $iM }}">
                    @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Abreviatura</label>
                    <input wire:model="editAbreviatura" type="text" maxlength="20" placeholder="Ej. kg" style="{{ $iM }} font-family:monospace;">
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                    <option value="1">Activa</option>
                    <option value="0">Inactiva</option>
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
    <div wire:key="card-{{ $u->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $u->name }}</p>
                <p style="font-size:11px; font-family:monospace; color:#7B6FE8; font-weight:600; margin:2px 0 0;">{{ $u->code }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $u->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $u->active ? '#059669' : '#9CA3AF' }};">
                {{ $u->active ? 'Activa' : 'Inactiva' }}
            </span>
        </div>

        @if($u->abreviatura)
        <div style="padding:8px 14px; border-bottom:1px solid #F3F4F6;">
            <span style="display:inline-block; padding:2px 8px; border-radius:6px; background:#F3F4F6; font-family:monospace; font-size:12px; font-weight:600; color:#374151;">{{ $u->abreviatura }}</span>
        </div>
        @endif

        <div style="padding:10px 14px; display:flex; gap:7px;">
            <button wire:click="startEdit({{ $u->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay unidades registradas.</p>
    @endforelse
    @if ($unidades->hasPages())
    <div style="padding-top:8px;">{{ $unidades->links() }}</div>
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
