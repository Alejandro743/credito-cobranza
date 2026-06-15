<div>

@if (session('success'))
<div style="background:#F0FDF4; color:#15803D; border:1px solid #BBF7D0; border-radius:10px; padding:10px 16px; margin-bottom:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

@php
    $thC = 'padding:9px 12px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9F8FF; border-bottom:2px solid #EDE9FE;';
    $tdC = 'padding:8px 12px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB;';
@endphp

{{-- ══ PANEL SUPERIOR: CASOS ══ --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; margin-bottom:10px;">

    {{-- Header con búsqueda --}}
    <div style="padding:10px 16px; border-bottom:1px solid #F3F4F6; display:flex; flex-wrap:wrap; align-items:center; gap:8px;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Mis Casos</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $casos->count() }}</span>
        <div style="flex:1; min-width:180px; position:relative;">
            <svg style="position:absolute; left:9px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input wire:model.live.debounce.300ms="searchCasos" type="text" placeholder="Buscar pedido, CI o cliente..."
                   style="width:100%; height:32px; padding:0 10px 0 28px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
        </div>
        <select wire:model.live="filtroEstadoCaso"
                style="height:32px; padding:0 8px; border:1px solid #E5E7EB; border-radius:8px; font-size:12px; outline:none; background:#fff; cursor:pointer;">
            <option value="">Todos los estados</option>
            <option value="asignado">Asignado</option>
            <option value="en_gestion">En Gestión</option>
        </select>
    </div>

    {{-- Tabla casos --}}
    <div style="overflow:auto; max-height:320px;">
    <table style="width:100%; border-collapse:collapse; min-width:900px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr>
                <th style="{{ $thC }} color:#C4B5FD; width:36px; text-align:center;">#</th>
                <th style="{{ $thC }}">Ciclo</th>
                <th style="{{ $thC }}">Nº Pedido</th>
                <th style="{{ $thC }}">CI</th>
                <th style="{{ $thC }}">Cliente</th>
                <th style="{{ $thC }} text-align:right;">Días Venc.</th>
                <th style="{{ $thC }} text-align:center;">Cuotas</th>
                <th style="{{ $thC }} text-align:center;">Estado</th>
                <th style="{{ $thC }}">Fecha Asig.</th>
                <th style="{{ $thC }}">Asignó</th>
                <th style="{{ $thC }} text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($casos as $i => $caso)
        @php
            $sel  = $selectedCasoId === $caso->id;
            $dias = (int)($caso->dias_vencimiento ?? 0);
        @endphp
        <tr wire:key="caso-{{ $caso->id }}"
            wire:click="selectCaso({{ $caso->id }})"
            style="background:{{ $sel ? '#F5F3FF' : '#fff' }}; cursor:pointer; transition:background .1s; {{ $sel ? 'border-left:3px solid #7B6FE8;' : '' }}"
            @mouseenter="$el.style.background='{{ $sel ? '#EDE9FE' : '#FAFAFE' }}'"
            @mouseleave="$el.style.background='{{ $sel ? '#F5F3FF' : '#fff' }}'"
            x-data>
            <td style="{{ $tdC }} text-align:center; font-size:11px; font-weight:700; color:#C4B5FD;">{{ $i + 1 }}</td>
            <td style="{{ $tdC }}">
                <span style="font-family:monospace; font-size:11px; font-weight:700; color:#7B6FE8;">{{ $caso->ciclo_code ?? '—' }}</span>
            </td>
            <td style="{{ $tdC }}">
                <span style="font-family:monospace; font-size:12px; font-weight:700; color:#111827;">{{ $caso->pedido->numero ?? '—' }}</span>
            </td>
            <td style="{{ $tdC }} font-size:12px;">{{ $caso->pedido?->cliente?->ci ?? '—' }}</td>
            <td style="{{ $tdC }} font-weight:600; color:#111827;">{{ $caso->pedido?->cliente?->nombre_completo ?? '—' }}</td>
            <td style="{{ $tdC }} text-align:right;">
                <span style="font-weight:700; color:{{ $dias > 30 ? '#B91C1C' : ($dias > 15 ? '#D97706' : '#374151') }};">{{ $dias }}d</span>
            </td>
            <td style="{{ $tdC }} text-align:center;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:20px; border-radius:6px; font-size:12px; font-weight:700; background:#FEF2F2; color:#B91C1C;">{{ $caso->cuotas_vencidas_count ?? 0 }}</span>
            </td>
            <td style="{{ $tdC }} text-align:center;">
                @if ($caso->estado === 'en_gestion')
                <span style="padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; background:#EFF6FF; color:#1D4ED8;">En Gestión</span>
                @else
                <span style="padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; background:#D1FAE5; color:#065F46;">Asignado</span>
                @endif
            </td>
            <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $caso->fecha_asignacion?->format('d/m/Y') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $caso->asignadoPor?->name ?? '—' }}</td>
            <td style="{{ $tdC }} text-align:center;" wire:click.stop>
                <div style="display:inline-flex; gap:5px;">
                    <button wire:click="abrirCerrarCaso" @click="$wire.selectedCasoId = {{ $caso->id }}"
                            style="height:26px; padding:0 8px; border:1px solid #E5E7EB; border-radius:6px; background:#fff; color:#374151; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap;">
                        Cerrar
                    </button>
                    <button wire:click="abrirCancelarCaso" @click="$wire.selectedCasoId = {{ $caso->id }}"
                            style="height:26px; padding:0 8px; border:1px solid #FEE2E2; border-radius:6px; background:#FEF2F2; color:#B91C1C; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap;">
                        Cancelar
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="11" style="padding:40px 24px; text-align:center; color:#9CA3AF; font-size:13px;">
                No tienes casos activos asignados.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══ PANEL INFERIOR: ACTIVIDADES ══ --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Header actividades --}}
    <div style="padding:10px 16px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        @if ($casoSeleccionado)
        <span style="font-size:13px; font-weight:700; color:#111827;">Actividades</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $actividades->count() }}</span>
        <span style="font-size:12px; color:#6B7280; margin-left:4px;">
            — {{ $casoSeleccionado->pedido?->numero }} · {{ $casoSeleccionado->pedido?->cliente?->nombre_completo }}
        </span>
        <div style="margin-left:auto;">
            <button wire:click="abrirNuevaActividad(0)"
                    style="height:32px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva actividad
            </button>
        </div>
        @else
        <span style="font-size:13px; font-weight:700; color:#111827;">Actividades</span>
        <span style="font-size:12px; color:#9CA3AF; margin-left:4px;">— selecciona un caso arriba</span>
        @endif
    </div>

    {{-- Tabla actividades --}}
    <div style="overflow:auto; max-height:340px;">
    @if ($casoSeleccionado)
    <table style="width:100%; border-collapse:collapse; min-width:1000px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr>
                <th style="{{ $thC }} color:#C4B5FD; width:36px; text-align:center;">#</th>
                <th style="{{ $thC }}">Tipo Contacto</th>
                <th style="{{ $thC }}">Acción</th>
                <th style="{{ $thC }}">Fecha Prog.</th>
                <th style="{{ $thC }}">Fecha Inicio</th>
                <th style="{{ $thC }}">Fecha Cierre</th>
                <th style="{{ $thC }}">Tipo Respuesta</th>
                <th style="{{ $thC }}">Observación</th>
                <th style="{{ $thC }}">Responsable</th>
                <th style="{{ $thC }} text-align:center;">Estado</th>
                <th style="{{ $thC }}">Origen</th>
                <th style="{{ $thC }} text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($actividades as $i => $act)
        <tr wire:key="act-{{ $act->id }}"
            style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''" x-data>
            <td style="{{ $tdC }} text-align:center; font-size:11px; font-weight:700; color:#C4B5FD;">{{ $i + 1 }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->tipoContacto?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px; font-weight:600;">{{ $act->accion?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->fecha_programada?->format('d/m/Y') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $act->fecha_inicio?->format('d/m/Y H:i') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $act->fecha_cierre?->format('d/m/Y H:i') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->tipoRespuesta?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px; max-width:180px; overflow:hidden; text-overflow:ellipsis;">{{ $act->observacion ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $act->responsable?->name ?? '—' }}</td>
            <td style="{{ $tdC }} text-align:center;">
                @php
                    $stMap = ['abierta'=>['#FEF3C7','#92400E','Abierta'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#F3F4F6','#6B7280','Cancelada']];
                    [$stBg,$stCol,$stLbl] = $stMap[$act->estado] ?? ['#F3F4F6','#6B7280',$act->estado];
                @endphp
                <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $stBg }}; color:{{ $stCol }};">{{ $stLbl }}</span>
            </td>
            <td style="{{ $tdC }} font-size:12px; color:#6B7280;">{{ $act->actividadOrigen ? '#'.$act->actividadOrigen->id : '—' }}</td>
            <td style="{{ $tdC }} text-align:center;">
                @if ($act->estado === 'abierta')
                <div style="display:inline-flex; gap:4px;">
                    <button wire:click="abrirEditarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #E5E7EB; border-radius:5px; background:#F9FAFB; color:#374151; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Editar
                    </button>
                    <button wire:click="iniciarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #BFDBFE; border-radius:5px; background:#EFF6FF; color:#1D4ED8; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Iniciar
                    </button>
                    <button wire:click="abrirCancelarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #FEE2E2; border-radius:5px; background:#FEF2F2; color:#B91C1C; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Cancelar
                    </button>
                </div>
                @elseif ($act->estado === 'en_proceso')
                <div style="display:inline-flex; gap:4px;">
                    <button wire:click="abrirEditarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #E5E7EB; border-radius:5px; background:#F9FAFB; color:#374151; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Editar
                    </button>
                    <button wire:click="abrirCerrarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #EDE9FE; border-radius:5px; background:#F5F3FF; color:#7B6FE8; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Cerrar
                    </button>
                    <button wire:click="abrirNuevaActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #D1FAE5; border-radius:5px; background:#ECFDF5; color:#065F46; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        + Nueva
                    </button>
                    <button wire:click="abrirCancelarActividad({{ $act->id }})"
                            style="height:24px; padding:0 7px; border:1px solid #FEE2E2; border-radius:5px; background:#FEF2F2; color:#B91C1C; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                        Cancelar
                    </button>
                </div>
                @elseif ($act->estado === 'cerrada')
                <button wire:click="abrirEditarActividad({{ $act->id }})"
                        style="height:24px; padding:0 7px; border:1px solid #E5E7EB; border-radius:5px; background:#F9FAFB; color:#374151; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">
                    Editar
                </button>
                @else
                <span style="font-size:11px; color:#D1D5DB;">—</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="12" style="padding:40px 24px; text-align:center; color:#9CA3AF; font-size:13px;">
                Este caso no tiene actividades aún.
                <button wire:click="abrirNuevaActividad(0)"
                        style="display:inline-flex; margin-left:8px; height:28px; padding:0 12px; background:#7B6FE8; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; align-items:center; gap:4px; vertical-align:middle;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Crear actividad
                </button>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    @else
    <div style="padding:48px 24px; text-align:center;">
        <svg style="width:40px; height:40px; color:#E5E7EB; margin:0 auto 10px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <p style="font-size:13px; font-weight:600; color:#6B7280;">Selecciona un caso para ver sus actividades</p>
    </div>
    @endif
    </div>
</div>

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

{{-- ══ MODAL: NUEVA ACTIVIDAD ══ --}}
<div x-data="{ open: @entangle('showModalNuevaAct') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:480px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#111827; margin:0; flex:1;">{{ $actOrigenId ? 'Nueva actividad desde #'.$actOrigenId : 'Nueva actividad' }}</p>
            <button @click="open=false" style="{{ $xBtn }}"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div style="{{ $mBody }}">
            {{-- Card: Tipo de contacto --}}
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
            {{-- Card: Programación --}}
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
        </div>
        <div style="{{ $mFoot }}">
            <button @click="open=false" style="height:36px;padding:0 14px;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">Cancelar</button>
            <button wire:click="guardarActividad" wire:loading.attr="disabled" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#7B6FE8;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Guardar</button>
        </div>
    </div>
</div>
</template>
</div>

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
            {{-- Card: Resultado --}}
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
            {{-- Card: Siguiente paso --}}
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Siguiente paso</p>
                @foreach([['solo','Cerrar solo la actividad'],['actividad_y_nueva','Cerrar y crear nueva actividad'],['actividad_y_caso','Cerrar actividad y cerrar el caso']] as [$val,$lbl2])
                <label style="display:flex; align-items:center; gap:10px; padding:10px 12px; cursor:pointer; border-radius:8px; background:{{ $cerrarOpcion === $val ? '#EDE9FE' : '#F9F8FF' }}; border:1.5px solid {{ $cerrarOpcion === $val ? '#C4B5FD' : '#F3F4F6' }}; transition:all .1s;">
                    <input type="radio" wire:model.live="cerrarOpcion" value="{{ $val }}" style="accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer; flex-shrink:0;">
                    <span style="font-size:13px; font-weight:{{ $cerrarOpcion === $val ? '700' : '500' }}; color:{{ $cerrarOpcion === $val ? '#5B21B6' : '#374151' }};">{{ $lbl2 }}</span>
                </label>
                @endforeach
            </div>
            {{-- Card: Nueva actividad (opción 2) --}}
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
                <p style="{{ $sTitle }}">■ Motivo</p>
                <div>
                    <label style="{{ $lbl }}">Motivo</label>
                    <input wire:model="actMotivoCancelac" type="text" placeholder="Describe el motivo..." style="{{ $inp }}">
                </div>
                <div>
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

{{-- ══ MODAL: CERRAR CASO ══ --}}
<div x-data="{ open: @entangle('showModalCerrarCaso') }">
<template x-teleport="body">
<div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index:9999; background:rgba(0,0,0,.45);" @click.self="open=false" @keydown.escape.window="open=false">
    <div style="background:#F8F7FF; border-radius:12px; width:100%; max-width:440px; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22); overflow:hidden;">
        <div style="{{ $mHead }}">
            <div style="width:32px; height:32px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
            <button wire:click="confirmarCerrarCaso" style="height:36px;padding:0 18px;border:none;border-radius:8px;background:#7B6FE8;color:#fff;font-size:13px;font-weight:700;cursor:pointer;">Cerrar caso</button>
        </div>
    </div>
</div>
</template>
</div>

{{-- ══ MODAL: CANCELAR CASO ══ --}}
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
                <p style="{{ $sTitle }}">■ Motivo</p>
                <div>
                    <label style="{{ $lbl }}">Motivo</label>
                    <input wire:model="motivoCancelCaso" type="text" placeholder="Describe el motivo..." style="{{ $inp }}">
                </div>
                <div>
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
