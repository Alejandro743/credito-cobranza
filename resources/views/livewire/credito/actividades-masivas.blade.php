<div>

@php
    $thC  = 'padding:9px 12px; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9F8FF; border-bottom:2px solid #EDE9FE;';
    $tdC  = 'padding:8px 12px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB;';
    $lbl  = 'font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:4px;';
    $inp  = 'width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#F5F3FF; box-sizing:border-box;';
    $sel  = 'width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#F5F3FF; cursor:pointer; box-sizing:border-box;';
    $card = 'border:1px solid #EDE9FE; border-radius:10px; padding:14px; background:#fff; display:flex; flex-direction:column; gap:10px;';
    $mHead= 'padding:14px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px; flex-shrink:0; background:#fff;';
    $mBody= 'padding:14px 16px; display:flex; flex-direction:column; gap:10px; overflow-y:auto; flex:1; background:#fff;';
    $mFoot= 'padding:12px 20px 14px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px; flex-shrink:0; background:#fff;';
    $xBtn = 'width:28px; height:28px; border:none; background:#F3F4F6; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#6B7280;';
    $sTitle='font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#7B6FE8; margin:0 0 6px;';
@endphp

{{-- ══ MODO LIST ══ --}}
@if ($mode === 'list')

<div style="display:flex; align-items:center; gap:8px; margin-bottom:8px; flex-wrap:wrap;">
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
    <button wire:click="create"
            style="height:32px; padding:0 14px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:5px; white-space:nowrap;">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva campaña
    </button>
</div>

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; height:calc(100vh - 152px);">
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
        @endphp
        <tr wire:key="camp-{{ $c->id }}" style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''" x-data>
            <td style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700; color:#9CA3AF;">{{ $campanas->firstItem() + $loop->index }}</td>
            <td style="{{ $tdC }} font-weight:600; color:#111827;">{{ $c->nombre }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $c->tipoContacto?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $c->accion?->nombre ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $c->fecha_programada?->format('d/m/Y') ?? '—' }}</td>
            <td style="{{ $tdC }} font-size:12px;">{{ $c->responsable?->name ?? '—' }}</td>
            <td style="{{ $tdC }} text-align:center;">
                <span style="display:inline-flex; align-items:center; justify-content:center; min-width:28px; height:20px; border-radius:6px; font-size:12px; font-weight:600; background:#EDE9FE; color:#7B6FE8; padding:0 6px;">{{ $c->actividades_count }}</span>
            </td>
            <td style="{{ $tdC }} text-align:center;">
                <span style="padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $eBg }}; color:{{ $eCol }};">{{ $eLbl }}</span>
            </td>
            <td style="{{ $tdC }} text-align:center;">
                <div style="display:inline-flex; gap:4px;">
                    <button wire:click="verDetalle({{ $c->id }})"
                            style="height:24px; padding:0 8px; border:none; border-radius:5px; background:#EDE9FE; color:#7B6FE8; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">Ver</button>
                    @if ($c->estado === 'abierta')
                    <button wire:click="cambiarEstado({{ $c->id }}, 'en_proceso')"
                            style="height:24px; padding:0 8px; border:none; border-radius:5px; background:#EFF6FF; color:#1D4ED8; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;"
                            title="Iniciar">Iniciar</button>
                    @endif
                    @if ($c->estado === 'en_proceso')
                    <button wire:click="cambiarEstado({{ $c->id }}, 'cerrada')"
                            style="height:24px; padding:0 8px; border:none; border-radius:5px; background:#F0FDF4; color:#065F46; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;"
                            title="Cerrar">Cerrar</button>
                    @endif
                    @if (in_array($c->estado, ['abierta','en_proceso']))
                    <button wire:click="edit({{ $c->id }})"
                            style="height:24px; padding:0 8px; border:1px solid #E5E7EB; border-radius:5px; background:#F9FAFB; color:#374151; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">Editar</button>
                    <button wire:click="cambiarEstado({{ $c->id }}, 'cancelada')"
                            style="height:24px; padding:0 8px; border:none; border-radius:5px; background:#FEF2F2; color:#B91C1C; font-size:10px; font-weight:700; cursor:pointer; white-space:nowrap;">Cancelar</button>
                    @endif
                </div>
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

@endif

{{-- ══ MODO FORM ══ --}}
{{-- ══ MODO DETAIL ══ --}}
@if ($mode === 'detail' && $campanaDetalle)
@php
    $estMap = ['abierta'=>['#E0F2FE','#0369A1','Sin Iniciar'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#FEE2E2','#B91C1C','Cancelada']];
    [$eBg,$eCol,$eLbl] = $estMap[$campanaDetalle->estado] ?? ['#F3F4F6','#6B7280',$campanaDetalle->estado];
    $stMap = ['abierta'=>['#E0F2FE','#0369A1','Sin Iniciar'],'en_proceso'=>['#EFF6FF','#1D4ED8','En Proceso'],'cerrada'=>['#F0FDF4','#065F46','Cerrada'],'cancelada'=>['#FEE2E2','#B91C1C','Cancelada']];
@endphp

<div style="display:flex; flex-direction:column; gap:12px; height:calc(100vh - 152px); overflow:auto; padding-bottom:16px;">

    <div style="display:flex; align-items:center; gap:10px; flex:none; flex-wrap:wrap;">
        <button wire:click="backToList" style="height:32px; padding:0 12px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:5px;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Volver
        </button>
        <span style="font-size:14px; font-weight:700; color:#111827;">{{ $campanaDetalle->nombre }}</span>
        <span style="padding:2px 10px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $eBg }}; color:{{ $eCol }};">{{ $eLbl }}</span>
        <span style="font-size:12px; color:#6B7280; margin-left:4px;">{{ $campanaDetalle->tipoContacto?->nombre }} · {{ $campanaDetalle->accion?->nombre }} · {{ $campanaDetalle->fecha_programada?->format('d/m/Y') }}</span>
    </div>

    <div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; flex:1; min-height:0; display:flex; flex-direction:column;">
        <div style="padding:10px 16px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:8px; flex:none;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Actividades</span>
            <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $campanaDetalle->actividades->count() }}</span>
        </div>
        <div style="flex:1; min-height:0; overflow:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:700px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr>
                    <th style="padding:9px 12px; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#EDE9FE; border-bottom:2px solid #EDE9FE; width:36px; text-align:center;">#</th>
                    <th style="{{ $thC }} text-align:center;">Nº Act.</th>
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
            @php [$stBg,$stCol,$stLbl] = $stMap[$act->estado] ?? ['#F3F4F6','#6B7280',$act->estado]; @endphp
            <tr wire:key="det-{{ $act->id }}" style="border-bottom:1px solid #F9FAFB;" @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''" x-data>
                <td style="{{ $tdC }} text-align:center; font-size:12px; font-weight:700;">{{ $i + 1 }}</td>
                <td style="{{ $tdC }} text-align:center;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:20px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">#{{ $act->numero }}</span>
                </td>
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
            <tr><td colspan="10" style="padding:40px; text-align:center; color:#9CA3AF; font-size:13px;">Sin actividades.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endif

{{-- ══ MODO FORM ══ --}}
@if ($mode === 'form')

<div style="display:flex; flex-direction:column; gap:12px; height:calc(100vh - 152px); overflow:auto; padding-bottom:16px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:10px; flex:none;">
        <button wire:click="backToList" style="height:32px; padding:0 12px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:5px;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Volver
        </button>
        <span style="font-size:14px; font-weight:700; color:#111827;">{{ $campanaId ? 'Editar campaña' : 'Nueva campaña' }}</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; align-items:start;">

        {{-- Datos campaña --}}
        <div style="{{ $card }}">
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#7B6FE8; margin:0 0 4px;">■ Datos de la campaña</p>
            <div>
                <label style="{{ $lbl }}">Nombre <span style="color:#EF4444;">*</span></label>
                <input wire:model="nombre" type="text" placeholder="Ej: WhatsApp Junio 21" style="{{ $inp }}">
                @error('nombre') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="{{ $lbl }}">Tipo Contacto <span style="color:#EF4444;">*</span></label>
                    <select wire:model="tipoContactoId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($tiposContacto as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach
                    </select>
                    @error('tipoContactoId') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Acción <span style="color:#EF4444;">*</span></label>
                    <select wire:model="accionId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($acciones as $a)<option value="{{ $a->id }}">{{ $a->nombre }}</option>@endforeach
                    </select>
                    @error('accionId') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="{{ $lbl }}">Fecha Programada <span style="color:#EF4444;">*</span></label>
                    <input wire:model="fechaProgramada" type="date" style="{{ $inp }}">
                    @error('fechaProgramada') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Responsable <span style="color:#EF4444;">*</span></label>
                    <select wire:model="responsableId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($usuarios as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                    </select>
                    @error('responsableId') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label style="{{ $lbl }}">Observación <span style="font-weight:400; font-size:10px;">(opcional)</span></label>
                <textarea wire:model="observacion" rows="2" style="width:100%; padding:8px 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box; background:#F5F3FF;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:4px;">
                <button wire:click="backToList" style="height:36px; padding:0 14px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
                <button wire:click="save" wire:loading.attr="disabled" style="height:36px; padding:0 18px; border:none; border-radius:8px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer;">
                    {{ $campanaId ? 'Guardar cambios' : 'Crear campaña' }}
                </button>
            </div>
        </div>

        {{-- Selección de casos --}}
        <div style="{{ $card }}">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#7B6FE8; margin:0;">■ Casos a incluir</p>
                <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:auto;">{{ count($selectedCasoIds) }} seleccionados</span>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <select wire:model.live="filtroCasoEstado"
                        style="height:30px; padding:0 8px; border:1px solid #E5E7EB; border-radius:6px; font-size:12px; outline:none; background:#fff; cursor:pointer;">
                    <option value="">Todos los estados</option>
                    <option value="asignado">Asignado</option>
                    <option value="en_gestion">En Gestión</option>
                </select>
                <input wire:model.live.debounce.300ms="filtroCiclo" type="text" placeholder="Filtrar ciclo..."
                       style="height:30px; padding:0 8px; border:1px solid #E5E7EB; border-radius:6px; font-size:12px; outline:none; background:#fff; width:110px;">
                <label style="display:flex; align-items:center; gap:6px; font-size:12px; color:#374151; cursor:pointer; margin-left:auto;">
                    <input type="checkbox"
                           @change="const ids = @js($casosQuery->pluck('id')->toArray()); $wire.selectedCasoIds = $event.target.checked ? ids : [];"
                           style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                    Todos
                </label>
            </div>
            <div style="max-height:340px; overflow-y:auto; border:1px solid #F3F4F6; border-radius:8px;">
            @forelse($casosQuery as $caso)
            @php
                $estBadge = ['asignado'=>['#D1FAE5','#065F46'],'en_gestion'=>['#EFF6FF','#1D4ED8']];
                [$cBg,$cCol] = $estBadge[$caso->estado] ?? ['#F3F4F6','#6B7280'];
            @endphp
            <label wire:key="caso-sel-{{ $caso->id }}"
                   style="display:flex; align-items:center; gap:10px; padding:8px 12px; cursor:pointer; border-bottom:1px solid #F9FAFB; background:{{ in_array($caso->id, $selectedCasoIds) ? '#F5F3FF' : '#fff' }}; transition:background .1s;">
                <input type="checkbox"
                       wire:model.live="selectedCasoIds"
                       value="{{ $caso->id }}"
                       style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer; flex-shrink:0;">
                <span style="font-family:monospace; font-size:11px; color:#7B6FE8;">{{ $caso->pedido?->numero ?? '—' }}</span>
                <span style="font-size:12px; color:#111827; flex:1; overflow:hidden; text-overflow:ellipsis;">{{ $caso->pedido?->cliente?->nombre_completo ?? '—' }}</span>
                <span style="font-size:11px; color:#6B7280;">{{ $caso->pedido?->cliente?->telefono ?? '—' }}</span>
                <span style="padding:1px 7px; border-radius:99px; font-size:10px; font-weight:600; background:{{ $cBg }}; color:{{ $cCol }}; white-space:nowrap;">{{ ucfirst(str_replace('_',' ',$caso->estado)) }}</span>
            </label>
            @empty
            <div style="padding:24px; text-align:center; color:#9CA3AF; font-size:12px;">No hay casos disponibles.</div>
            @endforelse
            </div>
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
            <div style="{{ $card }}">
                <p style="{{ $sTitle }}">■ Resultado</p>
                <div>
                    <label style="{{ $lbl }}">Tipo de respuesta <span style="color:#EF4444;">*</span></label>
                    <select wire:model="cierreTipoRespuestaId" style="{{ $sel }}">
                        <option value="0">— Seleccioná —</option>
                        @foreach($tiposRespuesta as $tr)
                        <option value="{{ $tr->id }}">{{ $tr->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cierreTipoRespuestaId') <p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="{{ $lbl }}">Obs. de cierre <span style="font-weight:400; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="cierreObs" rows="2" style="width:100%; padding:8px 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box; background:#F5F3FF;"></textarea>
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
            <div style="{{ $card }}">
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
                    <input wire:model="cancelMotivo" type="text" placeholder="Motivo de cancelación..." style="{{ $inp }}">
                </div>
                <div>
                    <label style="{{ $lbl }}">Observación <span style="font-weight:400; font-size:10px;">(opcional)</span></label>
                    <textarea wire:model="cancelObs" rows="2" style="width:100%; padding:8px 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box; background:#F5F3FF;"></textarea>
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
