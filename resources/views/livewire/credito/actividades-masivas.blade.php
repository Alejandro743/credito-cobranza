<div>

@php
    $thC  = 'padding:9px 12px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9F8FF; border-bottom:2px solid #EDE9FE;';
    $tdC  = 'padding:8px 12px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB;';
    $lbl  = 'font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:5px;';
    $inp  = 'width:100%; height:38px; padding:0 10px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; color:#374151; outline:none; background:#fff; box-sizing:border-box;';
    $sel  = 'width:100%; height:38px; padding:0 10px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; color:#374151; outline:none; background:#fff; cursor:pointer; box-sizing:border-box;';
    $mHead= 'padding:14px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px; flex-shrink:0; background:#fff;';
    $mBody= 'padding:14px 16px; display:flex; flex-direction:column; gap:10px; overflow-y:auto; flex:1; background:#fff;';
    $mFoot= 'padding:12px 20px 14px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px; flex-shrink:0; background:#fff;';
    $xBtn = 'width:28px; height:28px; border:none; background:#F3F4F6; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#6B7280;';
    $sTitle='font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#7B6FE8; margin:0 0 6px;';
@endphp

{{-- ══ MODO LIST ══ --}}
@if ($mode === 'list')

<div style="display:flex; flex-direction:column; gap:8px; height:calc(100vh - 152px);">

    {{-- Toolbar --}}
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; flex:none;">
        <div style="flex:1; min-width:180px; position:relative;">
            <svg style="position:absolute; left:9px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar campaña..."
                   style="width:100%; height:32px; padding:0 10px 0 28px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
        </div>
        <select wire:model.live="filtroEstado"
                style="height:32px; padding:0 8px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; cursor:pointer;">
            <option value="">Todos los estados</option>
            <option value="abierta">Sin Iniciar</option>
            <option value="en_proceso">En Proceso</option>
            <option value="cerrada">Cerrada</option>
            <option value="cancelada">Cancelada</option>
        </select>
        @if(!$showAddForm)
        <button wire:click="create"
                style="height:32px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:5px; white-space:nowrap;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Nueva campaña
        </button>
        @endif
    </div>

    {{-- Form inline (usuario-manager pattern) --}}
    @if ($showAddForm)
    <div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); overflow:hidden; flex:none;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 16px; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Nueva campaña
            </span>
            <button wire:click="cancelForm" style="width:28px; height:28px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:14px 16px; display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap;">
            <div style="flex:2; min-width:150px;">
                <label style="{{ $lbl }}">Nombre *</label>
                <input wire:model="nombre" type="text" placeholder="Ej: WhatsApp Junio 22" style="{{ $inp }}">
                @error('nombre') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:130px;">
                <label style="{{ $lbl }}">Tipo Contacto *</label>
                <select wire:model="tipoContactoId" style="{{ $sel }}">
                    <option value="0">— Seleccioná —</option>
                    @foreach($tiposContacto as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                </select>
                @error('tipoContactoId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:130px;">
                <label style="{{ $lbl }}">Acción *</label>
                <select wire:model="accionId" style="{{ $sel }}">
                    <option value="0">— Seleccioná —</option>
                    @foreach($acciones as $a)<option value="{{ $a->id }}">{{ $a->nombre }}</option>@endforeach
                </select>
                @error('accionId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:130px;">
                <label style="{{ $lbl }}">Responsable *</label>
                <select wire:model="responsableId" style="{{ $sel }}">
                    <option value="0">— Seleccioná —</option>
                    @foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                </select>
                @error('responsableId') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1; min-width:120px;">
                <label style="{{ $lbl }}">Fecha Prog. *</label>
                <input wire:model="fechaProgramada" type="date" style="{{ $inp }}">
                @error('fechaProgramada') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="flex:1.5; min-width:130px;">
                <label style="{{ $lbl }}">Observación</label>
                <input wire:model="observacion" type="text" placeholder="Opcional..." style="{{ $inp }}">
            </div>
            <div style="display:flex; gap:6px; flex-shrink:0;">
                <button wire:click="cancelForm" style="height:38px; padding:0 14px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
                <button wire:click="save" wire:loading.attr="disabled" style="height:38px; padding:0 18px; border:none; border-radius:8px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer;">
                    Crear campaña
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabla campañas --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; flex:1; min-height:0;">
        <div style="padding:10px 16px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:8px; flex:none;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Campañas</span>
            <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $campanas->total() }}</span>
        </div>
        <div style="flex:1; min-height:0; overflow:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:800px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr>
                    <th style="padding:9px 12px; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#EDE9FE; border-bottom:2px solid #EDE9FE; width:36px; text-align:center;">#</th>
                    <th style="{{ $thC }}">Nombre</th>
                    <th style="{{ $thC }}">Tipo Contacto</th>
                    <th style="{{ $thC }}">Acción</th>
                    <th style="{{ $thC }}">Fecha Prog.</th>
                    <th style="{{ $thC }}">Responsable</th>
                    <th style="{{ $thC }} text-align:center;">Actividades</th>
                    <th style="{{ $thC }} text-align:center;">Estado</th>
                    <th style="{{ $thC }} text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($campanas as $i => $c)
            @php
                $estMap = ['abierta'=>['#E0F2FE','#0369A1','Sin Iniciar'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#FEE2E2','#B91C1C','Cancelada']];
                [$eBg,$eCol,$eLbl] = $estMap[$c->estado] ?? ['#F3F4F6','#6B7280',$c->estado];
                $editing = $editingCampanaId === $c->id;
                $iE = 'width:100%; height:26px; padding:0 6px; border:1px solid #EDE9FE; border-radius:6px; font-size:12px; color:#374151; outline:none; background:#fff; box-sizing:border-box;';
                $sE = 'width:100%; height:26px; padding:0 4px; border:1px solid #EDE9FE; border-radius:6px; font-size:11px; color:#374151; outline:none; background:#fff; cursor:pointer; box-sizing:border-box;';
            @endphp
            <tr wire:key="camp-{{ $c->id }}" style="border-bottom:1px solid #F9FAFB; transition:background .1s; {{ $editing ? 'background:#F8F7FF;' : '' }}"
                @mouseenter="$el.style.background='{{ $editing ? '#F8F7FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $editing ? '#F8F7FF' : '' }}'"
                x-data="{ menuOpen: false, menuTop: 0, menuLeft: 0 }" @cerrar-menus.window="menuOpen=false">
                <td class="col-row-num" style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700;">{{ $campanas->firstItem() + $loop->index }}</td>

                {{-- Nombre --}}
                <td style="{{ $tdC }} font-weight:600; color:#111827; min-width:140px;">
                    @if($editing)
                        <input wire:model="editNombre" type="text" style="{{ $iE }}">
                        @error('editNombre') <p style="font-size:10px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                    @else
                        {{ $c->nombre }}
                    @endif
                </td>

                {{-- Tipo Contacto --}}
                <td style="{{ $tdC }} font-size:12px; min-width:110px;">
                    @if($editing)
                        <select wire:model="editTipoContactoId" style="{{ $sE }}">
                            <option value="0">—</option>
                            @foreach($tiposContacto as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                        </select>
                        @error('editTipoContactoId') <p style="font-size:10px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                    @else
                        {{ $c->tipoContacto?->nombre ?? '—' }}
                    @endif
                </td>

                {{-- Acción --}}
                <td style="{{ $tdC }} font-size:12px; min-width:100px;">
                    @if($editing)
                        <select wire:model="editAccionId" style="{{ $sE }}">
                            <option value="0">—</option>
                            @foreach($acciones as $a)<option value="{{ $a->id }}">{{ $a->nombre }}</option>@endforeach
                        </select>
                        @error('editAccionId') <p style="font-size:10px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                    @else
                        {{ $c->accion?->nombre ?? '—' }}
                    @endif
                </td>

                {{-- Fecha --}}
                <td style="{{ $tdC }} font-size:12px; min-width:110px;">
                    @if($editing)
                        <input wire:model="editFechaProgramada" type="date" style="{{ $iE }}">
                        @error('editFechaProgramada') <p style="font-size:10px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                    @else
                        {{ $c->fecha_programada?->format('d/m/Y') ?? '—' }}
                    @endif
                </td>

                {{-- Responsable --}}
                <td style="{{ $tdC }} font-size:12px; min-width:110px;">
                    @if($editing)
                        <select wire:model="editResponsableId" style="{{ $sE }}">
                            <option value="0">—</option>
                            @foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                        </select>
                        @error('editResponsableId') <p style="font-size:10px; color:#ef4444; margin:2px 0 0;">{{ $message }}</p> @enderror
                    @else
                        {{ $c->responsable?->name ?? '—' }}
                    @endif
                </td>

                <td style="{{ $tdC }} text-align:center;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; min-width:28px; height:20px; border-radius:6px; font-size:12px; font-weight:600; background:#EDE9FE; color:#7B6FE8; padding:0 6px;">{{ $c->actividades_count }}</span>
                </td>
                <td style="{{ $tdC }} text-align:center;">
                    <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $eBg }}; color:{{ $eCol }};">{{ $eLbl }}</span>
                </td>
                <td style="{{ $tdC }} text-align:center;">
                    @if($editing)
                    <div style="display:inline-flex; gap:4px; align-items:center;">
                        <button wire:click="saveEdit"
                                style="height:24px; padding:0 8px; border:none; border-radius:5px; background:#7B6FE8; color:#fff; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">Guardar</button>
                        <button wire:click="cancelEdit"
                                style="height:24px; padding:0 8px; border:1px solid #E5E7EB; border-radius:5px; background:#fff; color:#6B7280; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">Cancelar</button>
                    </div>
                    @else
                    {{-- Trigger ⋮ --}}
                    <button @click="$dispatch('cerrar-menus'); const r=$event.currentTarget.getBoundingClientRect(); menuTop=r.top+r.height/2; menuLeft=r.left-180; if(menuLeft<8)menuLeft=8; $nextTick(()=>menuOpen=true);"
                            style="width:28px; height:28px; border:none; border-radius:6px; background:#EDE9FE; color:#7B6FE8; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <circle cx="8" cy="2.5" r="1.5"/><circle cx="8" cy="8" r="1.5"/><circle cx="8" cy="13.5" r="1.5"/>
                        </svg>
                    </button>
                    <div x-show="menuOpen" @click.outside="menuOpen=false" x-transition.opacity.duration.100ms
                         :style="{ position:'fixed', top:menuTop+'px', left:menuLeft+'px', zIndex:9990, minWidth:'172px', transform:'translateY(-50%)' }">
                        <div style="position:absolute; right:-7px; top:50%; transform:translateY(-50%); width:0; height:0; border-top:7px solid transparent; border-bottom:7px solid transparent; border-left:7px solid #E5E7EB;"></div>
                        <div style="position:absolute; right:-6px; top:50%; transform:translateY(-50%); width:0; height:0; border-top:6px solid transparent; border-bottom:6px solid transparent; border-left:6px solid #fff;"></div>
                        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:4px 0; box-shadow:0 8px 24px rgba(0,0,0,.12); overflow:hidden;">
                            <button wire:click="verDetalle({{ $c->id }})" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver
                            </button>
                            @if (in_array($c->estado, ['abierta','en_proceso']))
                            <button wire:click="verCasos({{ $c->id }})" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#F0FDF4'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#065F46" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                                Agregar Casos
                            </button>
                            <div style="height:1px; background:#F3F4F6; margin:3px 0;"></div>
                            @if ($c->estado === 'abierta' && $c->actividades_count > 0)
                            <button wire:click="cambiarEstado({{ $c->id }}, 'en_proceso')" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#EFF6FF'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#1D4ED8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                                Iniciar
                            </button>
                            <button wire:click="edit({{ $c->id }})" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#374151" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Editar
                            </button>
                            @endif
                            @if ($c->estado === 'en_proceso')
                            <button wire:click="cambiarEstado({{ $c->id }}, 'cerrada')" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#F0FDF4'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#065F46" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cerrar
                            </button>
                            @endif
                            @if ($c->estado === 'abierta')
                            <div style="height:1px; background:#F3F4F6; margin:3px 0;"></div>
                            <button wire:click="cambiarEstado({{ $c->id }}, 'cancelada')" @click="menuOpen=false"
                                    style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#B91C1C; cursor:pointer; justify-content:center;"
                                    @mouseenter="$el.style.background='#FEF2F2'" @mouseleave="$el.style.background=''">
                                <svg width="12" height="12" fill="none" stroke="#B91C1C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Cancelar campaña
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding:48px 24px; text-align:center; color:#9CA3AF; font-size:13px;">
                    No hay campañas creadas aún.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if ($campanas->hasPages())
        <div style="padding:10px 16px; border-top:1px solid #F3F4F6;">{{ $campanas->links() }}</div>
        @endif
        </div>
    </div>

</div>
@endif

{{-- ══ MODO DETAIL ══ --}}
@if ($mode === 'detail' && $campanaDetalle)
@php
    $stMap = ['abierta'=>['#E0F2FE','#0369A1','Sin Iniciar'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#FEE2E2','#B91C1C','Cancelada']];
    [$dBg,$dCol,$dLbl] = $stMap[$campanaDetalle->estado] ?? ['#F3F4F6','#6B7280',$campanaDetalle->estado];
@endphp
<div>

    {{-- Card header — mismo formato que modo casos --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
        <div style="padding:13px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <button wire:click="backToList"
                        style="width:34px; height:34px; border-radius:9px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div style="min-width:0;">
                    <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin:0 0 2px;">Actividades de campaña</p>
                    <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $campanaDetalle->nombre }}</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="padding:2px 10px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $dBg }}; color:{{ $dCol }};">{{ $dLbl }}</span>
                <span style="font-size:12px; color:#6B7280;">{{ $campanaDetalle->tipoContacto?->nombre }} · {{ $campanaDetalle->accion?->nombre }} · {{ $campanaDetalle->fecha_programada?->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Panel tabla actividades --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 280px);">
        <div style="padding:10px 18px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Actividades &nbsp;<span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $campanaDetalle->actividades->count() }}</span></span>
        </div>
        <div style="overflow:auto; flex:1;">
        <table style="width:100%; border-collapse:collapse; min-width:700px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr>
                    <th style="padding:9px 12px; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#EDE9FE; border-bottom:2px solid #EDE9FE; width:36px; text-align:center;">#</th>
                    <th style="{{ $thC }} text-align:center;">Nº Act.</th>
                    <th style="{{ $thC }}">Ciclo</th>
                    <th style="{{ $thC }}">Nº Pedido</th>
                    <th style="{{ $thC }}">CI</th>
                    <th style="{{ $thC }}">Cliente</th>
                    <th style="{{ $thC }}">Teléfono</th>
                    <th style="{{ $thC }}">Tipo Contacto</th>
                    <th style="{{ $thC }}">Acción</th>
                    <th style="{{ $thC }}">Fecha Prog.</th>
                    <th style="{{ $thC }}">Responsable</th>
                    <th style="{{ $thC }} text-align:center;">Estado Act.</th>
                </tr>
            </thead>
            <tbody>
            @forelse($campanaDetalle->actividades as $i => $act)
            @php
                [$stBg,$stCol,$stLbl] = $stMap[$act->estado] ?? ['#F3F4F6','#6B7280',$act->estado];
                $actCiclo = $act->caso?->pedido?->items?->first()?->listaMaestraItem?->listaMaestra?->cycle?->code ?? '—';
            @endphp
            <tr wire:key="det-{{ $act->id }}" style="border-bottom:1px solid #F9FAFB;" @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''" x-data>
                <td class="col-row-num" style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700;">{{ $i + 1 }}</td>
                <td style="{{ $tdC }} text-align:center;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:20px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">#{{ $act->numero }}</span>
                </td>
                <td style="{{ $tdC }} font-size:12px; font-weight:600; color:#374151;">{{ $actCiclo }}</td>
                <td style="{{ $tdC }}"><span style="font-family:monospace; font-size:12px; color:#7B6FE8;">{{ $act->caso?->pedido?->numero ?? '—' }}</span></td>
                <td style="{{ $tdC }} font-size:12px;">{{ $act->caso?->pedido?->cliente?->ci ?? '—' }}</td>
                <td style="{{ $tdC }} color:#111827;">{{ $act->caso?->pedido?->cliente?->nombre_completo ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $act->caso?->pedido?->cliente?->telefono ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px;">{{ $act->tipoContacto?->nombre ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px;">{{ $act->accion?->nombre ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px;">{{ $act->fecha_programada?->format('d/m/Y') ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px;">{{ $act->responsable?->name ?? '—' }}</td>
                <td style="{{ $tdC }} text-align:center;">
                    <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $stBg }}; color:{{ $stCol }};">{{ $stLbl }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="12" style="padding:40px; text-align:center; color:#9CA3AF; font-size:13px;">Sin actividades.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endif


{{-- ══ MODO CASOS ══ --}}
@if ($mode === 'casos' && $casosCampana)
<div>

    {{-- Card header — igual a lista-maestra-manager --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
        <div style="padding:13px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <button wire:click="backToList"
                        style="width:34px; height:34px; border-radius:9px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div style="min-width:0;">
                    <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin:0 0 2px;">Casos vinculados</p>
                    <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $casosCampana->nombre }}</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 10px; border-radius:99px;">{{ count($selectedCasoIds) }} seleccionados</span>
                <button wire:click="backToList"
                        style="height:36px; padding:0 14px; border:1px solid #E5E7EB; background:#fff; color:#6B7280; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                        @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                    Cancelar
                </button>
<button wire:click="guardarCasos" wire:loading.attr="disabled"
                        style="height:36px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                    Guardar casos
                </button>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
        <input wire:model.live.debounce.300ms="filtroBusqueda" type="text"
               placeholder="Buscar por ciclo, Nº pedido o cliente..."
               style="flex:1; min-width:200px; height:36px; border:1px solid #E5E7EB; border-radius:9px; padding:0 10px; font-size:13px; color:#374151; outline:none; background:#fff;">
        <select wire:model.live="filtroCasoEstado"
                style="height:36px; border:1px solid #E5E7EB; border-radius:9px; padding:0 10px; font-size:13px; color:#374151; outline:none; background:#fff; cursor:pointer;">
            <option value="">Todos los estados</option>
            <option value="asignado">Asignado</option>
            <option value="en_gestion">En Gestión</option>
        </select>
    </div>

    {{-- Tabla --}}
    <div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 280px);">
        <div style="padding:10px 18px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Mis casos &nbsp;<span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $casosQuery->count() }}</span></span>
        </div>
        <div style="overflow:auto; flex:1;">
        <table style="width:100%; border-collapse:collapse; min-width:650px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr>
                    <th style="padding:9px 12px; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#EDE9FE; border-bottom:2px solid #EDE9FE; width:36px; text-align:center;">#</th>
                    <th style="width:40px; background:#F9F8FF; border-bottom:2px solid #EDE9FE; text-align:center; padding:9px 0;">
                        <input type="checkbox"
                               @change="const ids = @js($casosQuery->pluck('id')->toArray()); $wire.set('selectedCasoIds', $event.target.checked ? ids : []);"
                               {{ count($selectedCasoIds) > 0 && count($selectedCasoIds) === $casosQuery->count() ? 'checked' : '' }}
                               style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                    </th>
                    <th style="{{ $thC }}">Ciclo</th>
                    <th style="{{ $thC }}">Nº Pedido</th>
                    <th style="{{ $thC }}">CI</th>
                    <th style="{{ $thC }}">Cliente</th>
                    <th style="{{ $thC }}">Teléfono</th>
                    <th style="{{ $thC }} text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
            @forelse($casosQuery as $i => $caso)
            @php
                $estBadge = ['asignado'=>['#D1FAE5','#065F46'],'en_gestion'=>['#EFF6FF','#1D4ED8']];
                [$cBg,$cCol] = $estBadge[$caso->estado] ?? ['#F3F4F6','#6B7280'];
                $cicloCode = $caso->pedido?->items?->first()?->listaMaestraItem?->listaMaestra?->cycle?->code ?? '—';
            @endphp
            <tr wire:key="caso-v-{{ $caso->id }}" x-data
                style="border-bottom:1px solid #F3F4F6; cursor:pointer; transition:background .1s;"
                :style="$wire.selectedCasoIds.includes({{ $caso->id }}) ? 'background:#F5F3FF; border-bottom:1px solid #F3F4F6;' : 'background:#fff; border-bottom:1px solid #F3F4F6;'"
                @click="$wire.set('selectedCasoIds', $wire.selectedCasoIds.includes({{ $caso->id }})
                    ? $wire.selectedCasoIds.filter(id => id != {{ $caso->id }})
                    : [...$wire.selectedCasoIds, {{ $caso->id }}])"
                @mouseenter="if (!$wire.selectedCasoIds.includes({{ $caso->id }})) $el.style.background='#FAFAFE'"
                @mouseleave="$el.style.background = $wire.selectedCasoIds.includes({{ $caso->id }}) ? '#F5F3FF' : '#fff'">
                <td class="col-row-num" style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700;">{{ $i + 1 }}</td>
                <td style="{{ $tdC }} text-align:center;" @click.stop>
                    <input type="checkbox"
                           :checked="$wire.selectedCasoIds.includes({{ $caso->id }})"
                           @change="$wire.set('selectedCasoIds', $event.target.checked
                               ? [...$wire.selectedCasoIds, {{ $caso->id }}]
                               : $wire.selectedCasoIds.filter(id => id != {{ $caso->id }}))"
                           style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                </td>
                <td style="{{ $tdC }} font-size:12px; font-weight:600; color:#374151;">{{ $cicloCode }}</td>
                <td style="{{ $tdC }}"><span style="font-family:monospace; font-size:12px; color:#7B6FE8;">{{ $caso->pedido?->numero ?? '—' }}</span></td>
                <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $caso->pedido?->cliente?->ci ?? '—' }}</td>
                <td style="{{ $tdC }} color:#111827;">{{ $caso->pedido?->cliente?->nombre_completo ?? '—' }}</td>
                <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $caso->pedido?->cliente?->telefono ?? '—' }}</td>
                <td style="{{ $tdC }} text-align:center;">
                    <span style="padding:2px 8px; border-radius:99px; font-size:10px; font-weight:600; background:{{ $cBg }}; color:{{ $cCol }}; white-space:nowrap;">{{ ucfirst(str_replace('_',' ',$caso->estado)) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">No hay casos disponibles.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endif

{{-- ══ MODAL: CERRAR CAMPAÑA ══ --}}
<div x-data="{ open: @entangle('showModalCerrar') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#DCFCE7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#065F46" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cerrar campaña</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <p style="font-size:12px; color:#6B7280; margin:0;">Se cerrarán todas las actividades activas de esta campaña con los datos indicados.</p>
            <div style="border:1px solid #EDE9FE; border-radius:10px; padding:14px; background:#fff; display:flex; flex-direction:column; gap:10px;">
                <p style="{{ $sTitle }}">■ Resultado</p>
                <div>
                    <label style="{{ $lbl }}">Tipo de respuesta *</label>
                    <select wire:model="cierreTipoRespuestaId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($tiposRespuesta as $tr)
                        <option value="{{ $tr->id }}">{{ $tr->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cierreTipoRespuestaId') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Obs. de cierre <span style="font-weight:400; font-size:10px; text-transform:none;">(opcional)</span></label>
                    <textarea wire:model="cierreObs" rows="2" style="width:100%; padding:8px 10px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box; background:#fff;"></textarea>
                </div>
            </div>
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px; padding:0 14px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            <button wire:click="confirmarCierre" wire:loading.attr="disabled" style="height:36px; padding:0 18px; border:none; border-radius:8px; background:#065F46; color:#fff; font-size:13px; font-weight:700; cursor:pointer;">Confirmar cierre</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: CANCELAR CAMPAÑA ══ --}}
<div x-data="{ open: @entangle('showModalCancelar') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#FEE2E2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cancelar campaña</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <p style="font-size:12px; color:#6B7280; margin:0;">Se cancelarán todas las actividades activas de esta campaña.</p>
            <div style="border:1px solid #EDE9FE; border-radius:10px; padding:14px; background:#fff; display:flex; flex-direction:column; gap:10px;">
                <p style="{{ $sTitle }}">■ Motivo</p>
                <div>
                    <label style="{{ $lbl }}">Tipo de cancelación</label>
                    <select wire:model="cancelTipoId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($tiposCancelacion as $tc)
                        <option value="{{ $tc->id }}">{{ $tc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="{{ $lbl }}">Motivo</label>
                    <input wire:model="cancelMotivo" type="text" placeholder="Motivo de cancelación..." style="{{ $sel }}">
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; font-size:10px; text-transform:none;">(opcional)</span></label>
                    <textarea wire:model="cancelObs" rows="2" style="width:100%; padding:8px 10px; border:1px solid #EDE9FE; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box; background:#fff;"></textarea>
                </div>
            </div>
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px; padding:0 14px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">Volver</button>
            <button wire:click="confirmarCancelacion" wire:loading.attr="disabled" style="height:36px; padding:0 18px; border:none; border-radius:8px; background:#B91C1C; color:#fff; font-size:13px; font-weight:700; cursor:pointer;">Confirmar cancelación</button>
        </div>
    </div>
</div>
</template>
</div>

</div>
