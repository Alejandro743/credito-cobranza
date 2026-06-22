<div>


@php
    $thC = 'padding:9px 12px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9F8FF; border-bottom:2px solid #EDE9FE;';
    $tdC = 'padding:8px 12px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB;';
@endphp

{{-- ══ FILTROS ══ --}}
<div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-bottom:8px;">
    <div style="flex:1; min-width:180px; position:relative;">
        <svg style="position:absolute; left:9px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar CI o cliente..."
               style="width:100%; height:32px; padding:0 10px 0 28px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
    </div>
    <input wire:model.live.debounce.300ms="filtroCiclo" type="text" placeholder="Filtrar ciclo..."
           style="height:32px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; width:110px;">
    <div style="background:#FFF7ED; border:1px solid #E5E7EB; border-radius:8px; display:flex; align-items:center; padding-right:4px;">
        <select wire:model.live="filtroCasoEstado"
                style="height:32px; padding:0 8px; border:none; font-size:12px; outline:none; background:transparent; cursor:pointer; color:#9A3412; width:100%;">
            <option value="">Estado del caso</option>
            <option value="asignado">Asignado</option>
            <option value="en_gestion">En Gestión</option>
            <option value="cerrado">Cerrado</option>
            <option value="cancelado">Cancelado</option>
        </select>
    </div>
    <div style="background:#F5F3FF; border:1px solid #E5E7EB; border-radius:8px; display:flex; align-items:center; padding-right:4px;">
        <select wire:model.live="filtroEstado"
                style="height:32px; padding:0 8px; border:none; font-size:12px; outline:none; background:transparent; cursor:pointer; color:#5B21B6; width:100%;">
            <option value="">Estado actividad</option>
            <option value="abierta">Abierta</option>
            <option value="en_proceso">En Proceso</option>
            <option value="cerrada">Cerrada</option>
            <option value="cancelada">Cancelada</option>
        </select>
    </div>
    <div style="background:#F0F9FF; border:1px solid #E5E7EB; border-radius:8px; display:flex; align-items:center; padding-right:4px; max-width:160px;">
        <select wire:model.live="filtroPedido"
                style="height:32px; padding:0 8px; border:none; font-size:12px; outline:none; background:transparent; cursor:pointer; color:#0C4A6E; width:100%;">
            <option value="">Todos los pedidos</option>
            @foreach ($pedidosDisponibles as $ped)
            <option value="{{ $ped->id }}">{{ $ped->numero }}</option>
            @endforeach
        </select>
    </div>
    <div style="background:#F0FDF4; border:1px solid #E5E7EB; border-radius:8px; display:flex; align-items:center; padding-right:4px;">
        <select wire:model.live="filtroAccion"
                style="height:32px; padding:0 8px; border:none; font-size:12px; outline:none; background:transparent; cursor:pointer; color:#065F46; width:100%;">
            <option value="">Todas las acciones</option>
            <option value="iniciar">Iniciar</option>
            <option value="editar">Editar</option>
            <option value="cerrar">Cerrar</option>
            <option value="cancelar">Cancelar</option>
            <option value="cerrar_caso">Cerrar caso</option>
        </select>
    </div>
</div>

{{-- ══ PANEL ACTIVIDADES ══ --}}
<div style="display:flex; flex-direction:column; height:calc(100vh - 152px); background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Header --}}
    <div style="padding:10px 16px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:8px; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Mis Actividades</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $actividades->total() }}</span>
    </div>

    {{-- Tabla --}}
    <div style="flex:1; overflow:auto; min-height:0;">
    <table style="width:100%; border-collapse:collapse; min-width:1100px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr>
                <th style="padding:9px 12px; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#EDE9FE; border-bottom:2px solid #EDE9FE; width:36px; text-align:center;">#</th>
                <th style="{{ $thC }}">Ciclo</th>
                <th style="{{ $thC }}">Nº Pedido</th>
                <th style="{{ $thC }} text-align:center;">Estado Caso</th>
                <th style="{{ $thC }}">CI</th>
                <th style="{{ $thC }}">Cliente</th>
                <th style="{{ $thC }}">Tipo Contacto</th>
                <th style="{{ $thC }}">Acción</th>
                <th style="{{ $thC }} text-align:center;">Estado</th>
                <th style="{{ $thC }}">Fecha Prog.</th>
                <th style="{{ $thC }}">Origen</th>
                <th style="{{ $thC }} text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($actividades as $act)
        <tr wire:key="act-{{ $act->id }}"
            style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''"
            x-data="{ menuOpen: false, menuTop: 0, menuLeft: 0 }" @cerrar-menus.window="menuOpen=false">
            <td class="col-row-num" style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700;">{{ ($actividades->currentPage() - 1) * $actividades->perPage() + $loop->iteration }}</td>
            <td style="{{ $tdC }}">
                <span style="font-family:monospace; font-size:11px; color:#111827;">{{ $act->ciclo_code ?? '—' }}</span>
            </td>
            <td style="{{ $tdC }}">
                <span style="font-family:monospace; font-size:12px; color:#111827;">{{ $act->pedido_numero ?? '—' }}</span>
            </td>
            <td style="{{ $tdC }} text-align:center;">
                @php
                    $csMap = ['asignado'=>['#D1FAE5','#065F46','Asignado'],'en_gestion'=>['#EFF6FF','#1D4ED8','En Gestión'],'cerrado'=>['#F0FDF4','#166534','Cerrado'],'cancelado'=>['#FEE2E2','#B91C1C','Cancelado']];
                    [$csBg,$csCol,$csLbl] = $csMap[$act->caso_estado ?? ''] ?? ['#F3F4F6','#9CA3AF','—'];
                @endphp
                <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $csBg }}; color:{{ $csCol }};">{{ $csLbl }}</span>
            </td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->cliente_ci ?? '—' }}</td>
            <td style="{{ $tdC }} color:#111827; max-width:180px; overflow:hidden; text-overflow:ellipsis;">{{ Str::title($act->cliente_nombre ?? '—') }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->tipoContacto?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->accion?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} text-align:center;">
                @php
                    $stMap = ['abierta'=>['#E0F2FE','#0369A1','Abierta'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#FEE2E2','#B91C1C','Cancelada']];
                    [$stBg,$stCol,$stLbl] = $stMap[$act->estado] ?? ['#F3F4F6','#6B7280',$act->estado];
                @endphp
                <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $stBg }}; color:{{ $stCol }};">{{ $stLbl }}</span>
            </td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->fecha_programada?->format('d/m/Y') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->actividadOrigen ? '#'.$act->actividadOrigen->numero : '—' }}</td>
            <td style="{{ $tdC }} text-align:center;">
                @php $puedeC = $act->pendientes_caso == 0 && !in_array($act->caso_estado, ['cerrado','cancelado']); @endphp
                @if(in_array($act->estado, ['cancelada']) && !$puedeC)
                <span style="font-size:11px; color:#D1D5DB;">—</span>
                @else
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
                        @if ($act->estado === 'abierta')
                        <button wire:click="iniciarActividad({{ $act->id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/></svg>
                            Iniciar
                        </button>
                        @endif
                        @if ($act->estado === 'en_proceso')
                        <button wire:click="abrirCerrarActividad({{ $act->id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Cerrar
                        </button>
                        @endif
                        @if (!in_array($act->estado, ['cerrada','cancelada']))
                        <button wire:click="abrirEditarActividad({{ $act->id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#374151; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#374151" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            Editar
                        </button>
                        @endif
                        @if (in_array($act->estado, ['abierta','en_proceso']))
                        <div style="height:1px; background:#F3F4F6; margin:3px 0;"></div>
                        <button wire:click="abrirCancelarActividad({{ $act->id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#B91C1C; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#FEF2F2'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#B91C1C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancelar
                        </button>
                        @endif
                        @if ($puedeC)
                        <div style="height:1px; background:#F3F4F6; margin:3px 0;"></div>
                        <button wire:click="abrirCerrarCaso({{ $act->caso_id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#EA580C; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#FFF7ED'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#EA580C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Cerrar Caso
                        </button>
                        <button wire:click="abrirCancelarCaso({{ $act->caso_id }})" @click="menuOpen=false"
                                style="display:flex; align-items:center; gap:9px; width:100%; padding:9px 14px; border:none; background:none; font-size:12px; font-weight:600; color:#B91C1C; cursor:pointer; text-align:center; justify-content:center;"
                                @mouseenter="$el.style.background='#FEF2F2'" @mouseleave="$el.style.background=''">
                            <svg width="12" height="12" fill="none" stroke="#B91C1C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancelar Caso
                        </button>
                        @endif
                    </div>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" style="padding:48px 24px; text-align:center; color:#9CA3AF; font-size:13px;">
                No tienes actividades asignadas.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    @if($actividades->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6;">{{ $actividades->links() }}</div>
    @endif
    </div>

</div>{{-- fin panel --}}

@php
$mHead = 'padding:14px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px; flex-shrink:0; background:#fff;';
$mBody = 'padding:14px 16px; display:flex; flex-direction:column; gap:10px; overflow-y:auto; flex:1; background:#fff;';
$mFoot = 'padding:12px 20px 14px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px; flex-shrink:0; background:#fff;';
$card  = 'border:1px solid #EDE9FE; border-radius:10px; padding:14px; background:#fff; display:flex; flex-direction:column; gap:10px;';
$sTitle= 'font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#7B6FE8; margin:0 0 6px;';
$lbl   = 'font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:4px;';
$inp   = 'width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#F5F3FF; box-sizing:border-box;';
$sel   = 'width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#F5F3FF; cursor:pointer; box-sizing:border-box;';
$xBtn  = 'width:28px; height:28px; border-radius:6px; border:none; background:#EDE9FE; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;';
@endphp

{{-- ══ MODAL: EDITAR ACTIVIDAD ══ --}}
<div x-data="{ open: @entangle('showModalEditarAct') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:480px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Editar actividad</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Tipo de contacto</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="{{ $lbl }}">Tipo <span style="color:#EF4444;">*</span></label>
                        <select wire:model="tipoContactoId" style="{{ $sel }}">
                            <option value="0">— Selecciona —</option>
                            @foreach($tiposContacto as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                        </select>
                        @error('tipoContactoId') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="{{ $lbl }}">Acción <span style="color:#EF4444;">*</span></label>
                        <select wire:model="accionId" style="{{ $sel }}">
                            <option value="0">— Selecciona —</option>
                            @foreach($acciones as $a)<option value="{{ $a->id }}">{{ $a->nombre }}</option>@endforeach
                        </select>
                        @error('accionId') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Programación</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="{{ $lbl }}">Responsable</label>
                        <select wire:model="actResponsable" style="{{ $sel }}">
                            @foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="{{ $lbl }}">Fecha programada <span style="color:#EF4444;">*</span></label>
                        <input wire:model="actFechaProg" type="date" style="{{ $inp }}">
                        @error('actFechaProg') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="actObservacion" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
            @if ($actEstado === 'cerrada')
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Cierre</p>
                <div>
                    <label style="{{ $lbl }}">Tipo de respuesta <span style="color:#EF4444;">*</span></label>
                    <select wire:model="tipoRespuestaId" style="{{ $sel }}">
                        <option value="0">— Selecciona —</option>
                        @foreach($tiposRespuesta as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                    </select>
                    @error('tipoRespuestaId') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación de cierre <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="actObsCierre" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
            @endif
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Cancelar</button>
            <button wire:click="guardarEditarActividad" wire:loading.attr="disabled" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#7B6FE8;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Guardar</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: CERRAR ACTIVIDAD ══ --}}
<div x-data="{ open: @entangle('showModalCerrarAct') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:500px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cerrar actividad</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Resultado</p>
                <div>
                    <label style="{{ $lbl }}">Tipo de respuesta <span style="color:#EF4444;">*</span></label>
                    <select wire:model="tipoRespuestaId" style="{{ $sel }}">
                        <option value="0">— Selecciona —</option>
                        @foreach($tiposRespuesta as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                    </select>
                    @error('tipoRespuestaId') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="actObsCierre" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Siguiente paso</p>
                @foreach([['solo','Cerrar solo la actividad'],['actividad_y_nueva','Cerrar y crear nueva actividad'],['actividad_y_caso','Cerrar actividad y cerrar el caso']] as [$val,$lbl2])
                <label style="display:flex; align-items:center; gap:10px; padding:10px 12px; cursor:pointer; border-radius:8px; background:{{ $cerrarOpcion === $val ? '#EDE9FE' : '#F9F8FF' }}; border:1.5px solid {{ $cerrarOpcion === $val ? '#C4B5FD' : '#F3F4F6' }}; transition:all .1s;">
                    <input type="radio" wire:model.live="cerrarOpcion" value="{{ $val }}" style="accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer; flex-shrink:0;">
                    <span style="font-size:13px; font-weight:{{ $cerrarOpcion === $val ? '700' : '500' }}; color:{{ $cerrarOpcion === $val ? '#5B21B6' : '#374151' }};">{{ $lbl2 }}</span>
                </label>
                @endforeach
            </div>
            @if ($cerrarOpcion === 'actividad_y_nueva')
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Nueva actividad</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="{{ $lbl }}">Tipo contacto</label>
                        <select wire:model="nuevaTipoContacto" style="{{ $sel }}">
                            <option value="0">— Selecciona —</option>
                            @foreach($tiposContacto as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="{{ $lbl }}">Acción</label>
                        <select wire:model="nuevaAccion" style="{{ $sel }}">
                            <option value="0">— Selecciona —</option>
                            @foreach($acciones as $a)<option value="{{ $a->id }}">{{ $a->nombre }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div>
                        <label style="{{ $lbl }}">Responsable</label>
                        <select wire:model="nuevaResponsable" style="{{ $sel }}">
                            @foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label style="{{ $lbl }}">Fecha programada</label>
                        <input wire:model="nuevaFechaProg" type="date" style="{{ $inp }}">
                    </div>
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación</label>
                    <textarea wire:model="nuevaObs" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:none;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
            @endif
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Cancelar</button>
            <button wire:click="confirmarCerrarActividad" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#7B6FE8;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Confirmar</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: CERRAR CASO ══ --}}
<div x-data="{ open: @entangle('showModalCerrarCaso') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#FEF3E8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cerrar caso</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Cierre del caso</p>
                <div>
                    <label style="{{ $lbl }}">Motivo de cierre <span style="color:#EF4444;">*</span></label>
                    <select wire:model="motivoCierreId" style="{{ $sel }}">
                        <option value="0">— Selecciona —</option>
                        @foreach($motivosCierre as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach
                    </select>
                    @error('motivoCierreId') <p style="font-size:11px;color:#EF4444;margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="obsCierreCaso" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Cancelar</button>
            <button wire:click="confirmarCerrarCaso" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#F97316;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Cerrar caso</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: CANCELAR ACTIVIDAD ══ --}}
<div x-data="{ open: @entangle('showModalCancelarAct') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:420px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#FEF2F2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cancelar actividad</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Tipo de cancelación</p>
                <div>
                    <label style="{{ $lbl }}">Tipo <span style="color:#B91C1C;">*</span></label>
                    <select wire:model="tipoCancelacionId" style="{{ $inp }} cursor:pointer;">
                        <option value="0">— Selecciona —</option>
                        @foreach ($tiposCancelacion as $tc)
                        <option value="{{ $tc->id }}">{{ $tc->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipoCancelacionId') <p style="color:#B91C1C; font-size:11px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div style="margin-top:10px;">
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="actObsCierre" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Volver</button>
            <button wire:click="confirmarCancelarActividad" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#B91C1C;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Cancelar actividad</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL CANCELAR CASO ══ --}}
<div x-data="{ open: @entangle('showModalCancelarCaso') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:420px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#FEF2F2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">Cancelar caso</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Motivo de cancelación</p>
                <div>
                    <label style="{{ $lbl }}">Motivo <span style="color:#B91C1C;">*</span></label>
                    <select wire:model="motivoCancelacionId" style="{{ $inp }} cursor:pointer;">
                        <option value="0">— Selecciona —</option>
                        @foreach ($motivosCancelacion as $mc)
                        <option value="{{ $mc->id }}">{{ $mc->nombre }}</option>
                        @endforeach
                    </select>
                    @error('motivoCancelacionId') <p style="color:#B91C1C; font-size:11px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div style="margin-top:10px;">
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; text-transform:none; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="obsCancelCaso" rows="2" style="width:100%;padding:8px 10px;border:1px solid #E5E7EB;border-radius:8px;font-size:13px;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;background:#F5F3FF;"></textarea>
                </div>
            </div>
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Volver</button>
            <button wire:click="confirmarCancelarCaso" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#B91C1C;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Cancelar caso</button>
        </div>
    </div>
</div>
</template>
</div>

</div>
