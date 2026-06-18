<div>

@if (session('success'))
<div style="background:#D1FAE5; color:#065F46; border:1px solid #6EE7B7; border-radius:10px; padding:10px 16px; margin-bottom:14px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ══ TOOLBAR ══ --}}
<div class="flex flex-col gap-2 mb-4">

    {{-- Búsqueda + filtros fila 1 --}}
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1" style="min-width:0;">
            <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por Nº pedido, CI o nombre..."
                   style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
        </div>
        <input wire:model.live.debounce.300ms="filtroCiclo" type="text" placeholder="Filtrar ciclo..."
               style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; width:130px;">
        <select wire:model.live="filtroEstado"
                style="height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; color:#374151; cursor:pointer;">
            <option value="">Todos los estados</option>
            <option value="sin_asignar">Sin asignar</option>
            <option value="asignado">Asignado</option>
            <option value="en_gestion">En gestión</option>
            <option value="cerrado">Cerrado</option>
            <option value="cancelado">Cancelado</option>
        </select>
        <select wire:model.live="filtroResponsable"
                style="height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; color:#374151; cursor:pointer; max-width:180px;">
            <option value="">Todos los responsables</option>
            @foreach ($usuarios as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filtroDias"
                style="height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; background:#fff; color:#374151; cursor:pointer;">
            <option value="">Todos los días</option>
            <option value="1-15">1 – 15 días</option>
            <option value="16-30">16 – 30 días</option>
            <option value="31-90">31 – 90 días</option>
            <option value=">90">Mayor a 90 días</option>
        </select>
    </div>

    {{-- Acciones masivas fila 2 --}}
    <div class="flex flex-wrap items-center gap-2">
        <button wire:click="selectAllFiltered"
                style="height:36px; padding:0 16px; border:none; border-radius:9px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Seleccionar todos los filtrados
        </button>
        @if (!empty($selectedIds))
        <span style="font-size:12px; font-weight:600; color:#7B6FE8; background:#EDE9FE; padding:2px 10px; border-radius:99px;">
            {{ count($selectedIds) }} seleccionados
        </span>
        <button wire:click="asignarMasivo"
                style="height:36px; padding:0 16px; border:none; border-radius:9px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Asignar seleccionados
        </button>
        <button wire:click="clearSelection"
                style="height:32px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer;">
            Limpiar selección
        </button>
        @endif
    </div>
</div>

{{-- ══ MOBILE: Cards ══ --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    @php
        $caso   = $p->cobranzaCaso;
        $estado = $caso?->estado ?? 'sin_asignar';
        $inicial = strtoupper(substr($p->cliente->nombre_completo ?? '?', 0, 1));
        $badgeMap = [
            'sin_asignar' => ['#F3F4F6','#6B7280','Sin asignar'],
            'asignado'    => ['#D1FAE5','#065F46','Asignado'],
            'en_gestion'  => ['#EFF6FF','#1D4ED8','En gestión'],
            'cerrado'     => ['#EDE9FE','#5B21B6','Cerrado'],
            'cancelado'   => ['#FEE2E2','#B91C1C','Cancelado'],
        ];
        [$badgeBg, $badgeCol, $badgeLbl] = $badgeMap[$estado] ?? ['#F3F4F6','#6B7280',$estado];
    @endphp
    <div wire:key="ba-m-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ $inicial }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}@if($p->ciclo_code) <span style="color:#9CA3AF;">· {{ $p->ciclo_code }}</span>@endif</p>
            </div>
            <span style="padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $badgeBg }}; color:{{ $badgeCol }}; flex-shrink:0;">{{ $badgeLbl }}</span>
        </div>
        <div style="padding:10px 14px; display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px;">
            <div>
                <span style="font-size:10px; color:#9CA3AF; display:block; text-transform:uppercase; letter-spacing:.04em;">Días venc.</span>
                <span style="font-size:13px; font-weight:700; color:{{ (int)$p->dias_vencimiento > 30 ? '#B91C1C' : '#D97706' }};">{{ $p->dias_vencimiento ?? 0 }}d</span>
            </div>
            <div>
                <span style="font-size:10px; color:#9CA3AF; display:block; text-transform:uppercase; letter-spacing:.04em;">Cuotas venc.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ $p->cuotas_vencidas_count ?? 0 }}</span>
            </div>
            <div>
                <span style="font-size:10px; color:#9CA3AF; display:block; text-transform:uppercase; letter-spacing:.04em;">CI</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->cliente->ci ?? '—' }}</span>
            </div>
        </div>
        @if ($caso)
        <div style="padding:4px 14px 10px; border-top:1px solid #F3F4F6; display:flex; align-items:center; gap:6px;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#065F46; flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; color:#374151;">{{ $caso->responsable?->name ?? '—' }}</span>
            @if($caso->fecha_asignacion)
            <span style="font-size:11px; color:#9CA3AF; margin-left:auto;">{{ $caso->fecha_asignacion->format('d/m/Y') }}</span>
            @endif
        </div>
        @endif
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:6px;">
            @if ($estado === 'sin_asignar')
            <button wire:click="abrirAsignar({{ $p->id }})"
                    style="flex:1; height:34px; border:none; border-radius:8px; background:#7B6FE8; color:#fff; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Asignar
            </button>
            @elseif (in_array($estado, ['asignado','en_gestion']))
            <button wire:click="abrirAsignar({{ $p->id }})"
                    style="flex:1; height:34px; border:none; border-radius:8px; background:#0284C7; color:#fff; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Re-Asignar
            </button>
            @else
            <button wire:click="marcarSinAsignar({{ $p->id }})"
                    wire:confirm="¿Re-Abrir este caso y marcarlo como sin asignar?"
                    style="flex:1; height:34px; border:none; border-radius:8px; background:#EA580C; color:#fff; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Re-Abrir
            </button>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:56px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos con cuotas vencidas</p>
        <p style="font-size:12px; color:#9CA3AF; margin-top:4px;">Los pedidos aprobados con cuotas vencidas aparecerán aquí</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:clip; display:flex; flex-direction:column; max-height:calc(100vh - 210px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Bandeja de Asignación</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
        @if (!empty($selectedIds))
        <span style="background:#FEF3C7; color:#92400E; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px; margin-left:4px;">{{ count($selectedIds) }} sel.</span>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
    @php
        $th = 'padding:9px 12px; font-size:11px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap; background:#F9FAFB; border-bottom:1px solid #F3F4F6; border-right:1px solid #F3F4F6;';
        $td = 'padding:8px 12px; font-size:13px; color:#374151; vertical-align:middle; white-space:nowrap; border-bottom:1px solid #F9FAFB; border-right:1px solid #F9FAFB;';
    @endphp
    <table style="width:100%; border-collapse:separate; border-spacing:0; min-width:1100px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr>
                <th style="{{ $th }} text-align:center; position:sticky; left:0; z-index:11; background:#F9FAFB; border-right:2px solid #E5E7EB; padding:9px 16px;">
                    <div style="display:flex; align-items:center; gap:10px; justify-content:center;">
                        <span style="color:#C4B5FD; font-size:11px; font-weight:700;">#</span>
                        <input type="checkbox" disabled style="cursor:default; accent-color:#7B6FE8;">
                    </div>
                </th>
                <th style="{{ $th }}">Ciclo</th>
                <th style="{{ $th }}">Nº Pedido</th>
                <th style="{{ $th }}">Est. Pedido</th>
                <th style="{{ $th }}">CI</th>
                <th style="{{ $th }}">Cliente</th>
                <th style="{{ $th }}">Cód. Vend.</th>
                <th style="{{ $th }}">Vendedor</th>
                <th style="{{ $th }} text-align:right;">Días Venc.</th>
                <th style="{{ $th }} text-align:center;">Cuotas Venc.</th>
                <th style="{{ $th }}">Responsable</th>
                <th style="{{ $th }}">Fecha Asig.</th>
                <th style="{{ $th }}">Fecha Cierre/Cancelación</th>
                <th style="{{ $th }}">Asignó</th>
                <th style="{{ $th }}">Estado</th>
                <th style="{{ $th }}">Motivo</th>
                <th style="{{ $th }}">Observación</th>
                <th style="{{ $th }} text-align:center;">Acción</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($pedidos as $p)
        @php
            $caso   = $p->cobranzaCaso;
            $estado = $caso?->estado ?? 'sin_asignar';
            $isSelected = in_array((string)$p->id, $this->selectedIds);
            $dias   = (int)($p->dias_vencimiento ?? 0);
            $badgeMap = ['sin_asignar'=>['#F3F4F6','#6B7280','Sin asignar'],'asignado'=>['#D1FAE5','#065F46','Asignado'],'en_gestion'=>['#EFF6FF','#1D4ED8','En gestión'],'cerrado'=>['#EDE9FE','#5B21B6','Cerrado'],'cancelado'=>['#FEE2E2','#B91C1C','Cancelado']];
            [$badgeBg,$badgeCol,$badgeLbl] = $badgeMap[$estado] ?? ['#F3F4F6','#6B7280',$estado];
        @endphp
        <tr wire:key="ba-d-{{ $p->id }}"
            style="background:{{ $isSelected ? '#F5F3FF' : '#fff' }}; transition:background .1s;"
            x-data>
            <td style="padding:8px 16px; text-align:center; white-space:nowrap; border-bottom:1px solid #F9FAFB; border-right:2px solid #E5E7EB; vertical-align:middle; position:sticky; left:0; z-index:2; background:{{ $isSelected ? '#F5F3FF' : '#fff' }};">
                <div style="display:flex; align-items:center; gap:10px; justify-content:center;">
                    <span style="font-size:11px; font-weight:700; color:#9CA3AF;">{{ $pedidos->firstItem() + $loop->index }}</span>
                    <input type="checkbox"
                           wire:model.live="selectedIds"
                           value="{{ $p->id }}"
                           style="cursor:pointer; accent-color:#7B6FE8; width:14px; height:14px;">
                </div>
            </td>
            <td style="{{ $td }}">
                @if($p->ciclo_code)
                <span style="font-family:monospace; font-size:12px; font-weight:700; color:#374151;">{{ $p->ciclo_code }}</span>
                @else
                <span style="color:#D1D5DB;">—</span>
                @endif
            </td>
            <td style="{{ $td }}">
                <span style="font-family:monospace; font-size:12px; font-weight:700; color:#111827;">{{ $p->numero }}</span>
            </td>
            <td style="{{ $td }}">
                @php
                    $pEstMap = ['aprobado'=>['#D1FAE5','#065F46','Aprobado'],'en_espera'=>['#FEF3C7','#92400E','En espera'],'revision'=>['#EFF6FF','#1D4ED8','Revisión'],'cerrado'=>['#F3F4F6','#6B7280','Cerrado']];
                    [$pBg,$pCol,$pLbl] = $pEstMap[$p->estado] ?? ['#F3F4F6','#6B7280',ucfirst($p->estado)];
                @endphp
                <span style="padding:2px 8px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $pBg }}; color:{{ $pCol }}; white-space:nowrap;">{{ $pLbl }}</span>
            </td>
            <td style="{{ $td }}">
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->cliente->ci ?? '—' }}</span>
            </td>
            <td style="{{ $td }}">
                <span style="font-weight:600; color:#111827;">{{ $p->cliente->nombre_completo ?? '—' }}</span>
            </td>
            <td style="{{ $td }}">
                <span style="font-family:monospace; font-size:12px; color:#6B7280;">V-{{ str_pad($p->vendedor?->id ?? 0, 4, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="{{ $td }}">{{ $p->vendedor->nombre ?? '' }} {{ $p->vendedor->apellido ?? '' }}</td>
            <td style="{{ $td }} text-align:right;">
                <span style="font-size:13px; font-weight:700; color:{{ $dias > 30 ? '#B91C1C' : ($dias > 15 ? '#D97706' : '#374151') }};">
                    {{ $dias }}d
                </span>
            </td>
            <td style="{{ $td }} text-align:center;">
                <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:20px; border-radius:6px; font-size:12px; font-weight:700; background:#FEF2F2; color:#B91C1C;">
                    {{ $p->cuotas_vencidas_count ?? 0 }}
                </span>
            </td>
            <td style="{{ $td }}">{{ $caso?->responsable?->name ?? '—' }}</td>
            <td style="{{ $td }}">{{ $caso?->fecha_asignacion?->format('d/m/Y H:i') ?? '—' }}</td>
            <td style="{{ $td }} font-size:12px; color:#6B7280;">
                @if($caso && in_array($caso->estado, ['cerrado','cancelado'])){{ $caso->fecha_cierre?->format('d/m/Y H:i') ?? '—' }}@endif
            </td>
            <td style="{{ $td }}">{{ $caso?->asignadoPor?->name ?? '—' }}</td>
            <td style="{{ $td }}">
                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:{{ $badgeBg }}; color:{{ $badgeCol }};">{{ $badgeLbl }}</span>
            </td>
            <td style="{{ $td }} font-size:12px; color:#374151; white-space:nowrap;">
                @if($caso && in_array($caso->estado, ['cerrado','cancelado'])){{ $caso->motivo_cierre ?? '' }}@endif
            </td>
            <td style="{{ $td }} font-size:12px; color:#6B7280; white-space:nowrap;">
                @if($caso && in_array($caso->estado, ['cerrado','cancelado']) && $caso->observacion_cierre)
                <div x-data="{ open: false }" style="position:relative; display:inline-block;">
                    <span @click="open=!open" style="cursor:pointer; text-decoration:underline dotted;">
                        {{ Str::limit($caso->observacion_cierre, 30) }}
                    </span>
                    <div x-show="open" @click.outside="open=false" x-transition
                         style="position:absolute; bottom:calc(100% + 6px); left:0; z-index:50; background:#1F2937; color:#F9FAFB; font-size:12px; padding:8px 12px; border-radius:8px; max-width:280px; white-space:normal; box-shadow:0 4px 16px rgba(0,0,0,.25); line-height:1.5;">
                        {{ $caso->observacion_cierre }}
                        <div style="position:absolute; top:100%; left:16px; border:6px solid transparent; border-top-color:#1F2937;"></div>
                    </div>
                </div>
                @endif
            </td>
            <td style="{{ $td }} text-align:center;">
                @if ($estado === 'sin_asignar')
                <button wire:click="abrirAsignar({{ $p->id }})"
                        style="height:28px; padding:0 12px; border:none; border-radius:7px; background:#7B6FE8; color:#fff; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px; white-space:nowrap;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Asignar
                </button>
                @elseif (in_array($estado, ['asignado','en_gestion']))
                <button wire:click="abrirAsignar({{ $p->id }})"
                        style="height:28px; padding:0 12px; border:none; border-radius:7px; background:#0284C7; color:#fff; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px; white-space:nowrap;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Re-Asignar
                </button>
                @else
                <button wire:click="marcarSinAsignar({{ $p->id }})"
                        wire:confirm="¿Re-Abrir este caso y marcarlo como sin asignar?"
                        style="height:28px; padding:0 12px; border:none; border-radius:7px; background:#EA580C; color:#fff; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px; white-space:nowrap;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Re-Abrir
                </button>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="17" style="padding:56px 24px; text-align:center;">
                <svg style="width:40px; height:40px; color:#E5E7EB; margin:0 auto 10px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos con cuotas vencidas</p>
                <p style="font-size:12px; color:#9CA3AF; margin-top:4px;">Los pedidos aprobados con cuotas vencidas aparecerán aquí</p>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>

    @if ($pedidos->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">
        {{ $pedidos->links() }}
    </div>
    @endif
</div>

{{-- ══ MODAL ASIGNACIÓN ══ --}}
<div x-data="{ open: @entangle('showModal') }">
    <template x-teleport="body">
        <div x-show="open"
             class="fixed inset-0 flex items-center justify-center p-4"
             style="z-index:9999; background:rgba(0,0,0,.45);"
             @click.self="open = false"
             @keydown.escape.window="open = false">
            <div style="background:#fff; border-radius:8px; width:100%; max-width:440px; box-shadow:0 24px 64px rgba(0,0,0,.22); display:flex; flex-direction:column;">
                {{-- Header --}}
                <div style="padding:16px 20px 14px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:10px; flex-shrink:0;">
                    <div style="width:36px; height:36px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:15px; font-weight:700; color:#111827; margin:0;">Asignar caso</p>
                        <p style="font-size:12px; color:#6B7280; margin:2px 0 0;">
                            @if(count($asignarIds) > 1)
                                {{ count($asignarIds) }} pedidos seleccionados
                            @else
                                Asignación individual
                            @endif
                        </p>
                    </div>
                    <button @click="open = false"
                            style="width:28px; height:28px; border-radius:6px; border:none; background:#EDE9FE; color:#7B6FE8; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6B7280; display:block; margin-bottom:6px;">
                            Responsable <span style="color:#EF4444;">*</span>
                        </label>
                        <select wire:model="asignarResponsable"
                                style="width:100%; height:38px; padding:0 12px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; background:#fff; cursor:pointer;">
                            <option value="0">— Selecciona un responsable —</option>
                            @foreach ($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('asignarResponsable')
                        <p style="font-size:12px; color:#EF4444; margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6B7280; display:block; margin-bottom:6px;">
                            Observación <span style="font-weight:400; color:#9CA3AF;">(opcional)</span>
                        </label>
                        <textarea wire:model="asignarObservacion" rows="3"
                                  placeholder="Notas de asignación..."
                                  style="width:100%; padding:9px 12px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; color:#111827; outline:none; resize:vertical; font-family:inherit; box-sizing:border-box;"></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="padding:14px 20px 16px; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:8px; flex-shrink:0;">
                    <button @click="open = false"
                            style="height:36px; padding:0 16px; border:1px solid #E5E7EB; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">
                        Cancelar
                    </button>
                    <button wire:click="confirmarAsignacion" wire:loading.attr="disabled"
                            style="height:36px; padding:0 20px; border:none; border-radius:8px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                        <span wire:loading.remove wire:target="confirmarAsignacion">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span wire:loading wire:target="confirmarAsignacion">
                            <svg class="animate-spin" width="14" height="14" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </span>
                        Confirmar asignación
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

</div>
