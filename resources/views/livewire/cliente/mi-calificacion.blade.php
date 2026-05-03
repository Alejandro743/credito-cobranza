<div>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
.mc-th { padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; border:0.5px solid #d1fae5; background:#f0fdf4; color:#15803D; }
.mc-td { padding:9px 12px; font-size:12px; border:0.5px solid #e5e7eb; color:#374151; }
</style>

@if(isset($sinCliente) && $sinCliente)
<div style="text-align:center; padding:60px 20px; color:#9ca3af;">
    <svg style="width:48px;height:48px;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    <p style="font-size:14px; font-weight:600;">No tenés un perfil de cliente asociado.</p>
</div>

@elseif(!$indicadores)
<div style="background:#FEF3C7; border:1px solid #FCD34D; border-radius:14px; padding:20px 24px; text-align:center; color:#854F0B;">
    <p style="font-size:14px; font-weight:700; margin:0 0 4px;">Sin historial crediticio</p>
    <p style="font-size:12px; margin:0;">Aún no tenés pedidos aprobados con plan de pago activo.</p>
</div>

@else
@php $nombre = ucwords(mb_strtolower(auth()->user()->name . ' ' . (auth()->user()->cliente->apellido ?? ''))); @endphp

{{-- Header personal --}}
<div style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border:1px solid #d1fae5; border-radius:16px; padding:20px 24px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
    <div>
        <p style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Mi calificación crediticia</p>
        <p style="font-size:20px; font-weight:800; color:#166534; margin:0; letter-spacing:-0.01em;">{{ $nombre }}</p>
    </div>
    <div style="display:flex; align-items:center; gap:14px;">
        @if($calificacion)
        <div style="text-align:center;">
            <div style="width:60px; height:60px; border-radius:50%; background:{{ $calBadge['bg'] }}; border:3px solid {{ $calBadge['cl'] }}; display:flex; align-items:center; justify-content:center; margin:0 auto 4px;">
                <span style="font-size:24px; font-weight:900; color:{{ $calBadge['cl'] }}; line-height:1;">{{ $calificacion }}</span>
            </div>
            <p style="font-size:10px; font-weight:600; color:{{ $calBadge['cl'] }}; margin:0; text-transform:uppercase; letter-spacing:0.04em;">Calificación</p>
        </div>
        <div style="text-align:center;">
            <p style="font-size:30px; font-weight:900; color:#166534; margin:0; font-family:monospace; line-height:1;">{{ $indicadores['puntaje'] }}</p>
            <p style="font-size:10px; font-weight:600; color:#6b7280; margin:2px 0 0; text-transform:uppercase; letter-spacing:0.04em;">Puntaje</p>
        </div>
        @endif
    </div>
</div>

{{-- KPIs con tooltips --}}
@php
$kpis_detalle = [
    [
        'label' => 'Puntualidad',
        'val'   => $indicadores['puntualidad'].' %',
        'bg'    => '#F0FDF4', 'cl' => '#15803D', 'bc' => '#d1fae5',
        'titulo_info' => 'Indicador positivo ↑',
        'info'  => 'Cuotas ya vencidas que pagaste antes o en la fecha de vencimiento.',
        'formula' => 'Cuotas pagadas a tiempo ÷ total vencidas × 100',
        'ejemplo' => [
            ['txt' => '20 cuotas vencidas',  'cal' => ''],
            ['txt' => '18 pagadas a tiempo', 'cal' => ''],
            ['txt' => 'Resultado',           'cal' => '18 ÷ 20 × 100 = 90%', 'bold' => true],
        ],
        'nota' => '↑ A mayor puntualidad, mejor puntaje.',
    ],
    [
        'label' => 'Mora Generada',
        'val'   => $indicadores['mora'].' %',
        'bg'    => '#FEF2F2', 'cl' => '#B91C1C', 'bc' => '#fecaca',
        'titulo_info' => 'Indicador negativo ↓',
        'info'  => 'Pedidos con al menos una cuota vencida sin pagar.',
        'formula' => 'Pedidos en mora ÷ total pedidos × 100',
        'ejemplo' => [
            ['txt' => '3 pedidos activos',           'cal' => ''],
            ['txt' => '1 tiene cuota vencida',       'cal' => ''],
            ['txt' => 'Mora',                        'cal' => '1 ÷ 3 × 100 = 33%', 'bold' => true],
        ],
        'nota' => '↓ Cuanto más bajo, mejor puntaje.',
    ],
    [
        'label' => 'C. en Riesgo',
        'val'   => $indicadores['riesgo'].' %',
        'bg'    => '#FFF7ED', 'cl' => '#C2410C', 'bc' => '#fdba74',
        'titulo_info' => 'Indicador negativo ↓',
        'info'  => 'Del total pendiente, cuánto ya venció sin ser pagado.',
        'formula' => 'Saldo vencido impago ÷ (vencido + futuro) × 100',
        'ejemplo' => [
            ['txt' => 'Vencido sin pagar', 'cal' => 'Bs 500'],
            ['txt' => 'Cuotas futuras',    'cal' => 'Bs 1.500'],
            ['txt' => 'Riesgo',            'cal' => '500 ÷ 2.000 × 100 = 25%', 'bold' => true],
        ],
        'nota' => '↓ Un valor alto indica deuda deteriorada.',
    ],
    [
        'label' => 'Recuperación',
        'val'   => $indicadores['recuperacion'].' %',
        'bg'    => '#F0FDF4', 'cl' => '#15803D', 'bc' => '#d1fae5',
        'titulo_info' => 'Indicador positivo ↑',
        'info'  => 'Del monto vencido sin pagar, cuánto lograste pagar aunque sea tarde.',
        'formula' => 'Cobrado tardíamente ÷ total vencido impago × 100',
        'ejemplo' => [
            ['txt' => 'Vencido sin pagar',   'cal' => 'Bs 1.000'],
            ['txt' => 'Pagado con retraso',  'cal' => 'Bs 600'],
            ['txt' => 'Recuperación',        'cal' => '600 ÷ 1.000 × 100 = 60%', 'bold' => true],
        ],
        'nota' => '↑ Pagar aunque sea tarde suma puntos.',
    ],
    [
        'label' => 'Reprogramación',
        'val'   => $indicadores['reprog'].' %',
        'bg'    => '#FFFBEB', 'cl' => '#854F0B', 'bc' => '#FCD34D',
        'titulo_info' => 'Indicador negativo ↓',
        'info'  => 'Pedidos que requirieron un nuevo plan de pago.',
        'formula' => 'Pedidos reprogramados ÷ total pedidos × 100',
        'ejemplo' => [
            ['txt' => '3 pedidos activos',  'cal' => ''],
            ['txt' => '1 fue reprogramado', 'cal' => ''],
            ['txt' => 'Reprogramación',     'cal' => '1 ÷ 3 × 100 = 33%', 'bold' => true],
        ],
        'nota' => '↓ Menos reprogramaciones = mejor puntaje.',
    ],
];
@endphp

<div x-data="{ activeKpi: null }" @click.away="activeKpi = null"
     style="display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:24px;">
    @foreach($kpis_detalle as $kd)
    @php
        $idx = $loop->index;
        if ($idx <= 1) {
            $tipPos = 'left:0; transform:none;'; $arrowPos = 'left:16px; transform:rotate(45deg);';
        } elseif ($idx >= 3) {
            $tipPos = 'right:0; left:auto; transform:none;'; $arrowPos = 'right:16px; left:auto; transform:rotate(45deg);';
        } else {
            $tipPos = 'left:50%; transform:translateX(-50%);'; $arrowPos = 'left:50%; transform:translateX(-50%) rotate(45deg);';
        }
    @endphp
    <div style="background:{{ $kd['bg'] }}; border:1px solid {{ $kd['bc'] }}; border-radius:10px; padding:12px; position:relative;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
            <span style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:{{ $kd['cl'] }};">{{ $kd['label'] }}</span>
            <button @click.stop="activeKpi = activeKpi === {{ $idx }} ? null : {{ $idx }}"
                    style="width:15px;height:15px;border-radius:50%;border:1px solid {{ $kd['bc'] }};background:#fff;color:{{ $kd['cl'] }};cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;padding:0;">
                <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>
        <p style="font-size:22px; font-weight:800; color:{{ $kd['cl'] }}; margin:0; font-family:monospace; text-align:center; line-height:1.1;">{{ $kd['val'] }}</p>

        <div x-show="activeKpi === {{ $idx }}" x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             style="position:absolute; top:calc(100% + 6px); {{ $tipPos }} z-index:9999; width:260px; background:#fff; border:1px solid {{ $kd['bc'] }}; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.13); padding:12px 13px;">
            <div style="position:absolute; top:-6px; {{ $arrowPos }} width:10px; height:10px; background:#fff; border-left:1px solid {{ $kd['bc'] }}; border-top:1px solid {{ $kd['bc'] }};"></div>
            <p style="font-size:10px; font-weight:700; color:{{ $kd['cl'] }}; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 4px;">{{ $kd['titulo_info'] }}</p>
            <p style="font-size:11px; color:#374151; margin:0 0 7px; line-height:1.45;">{{ $kd['info'] }}</p>
            <div style="background:{{ $kd['bg'] }}; border:1px solid {{ $kd['bc'] }}; border-radius:6px; padding:5px 8px; font-size:10px; font-family:monospace; color:{{ $kd['cl'] }}; line-height:1.6; margin-bottom:8px;">{{ $kd['formula'] }}</div>
            <div style="border-top:1px solid {{ $kd['bc'] }}; padding-top:7px;">
                <p style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#9ca3af; margin:0 0 5px;">Ejemplo</p>
                @foreach($kd['ejemplo'] as $ej)
                <div style="display:flex; justify-content:space-between; align-items:baseline; gap:6px; {{ !empty($ej['bold']) ? 'border-top:1px dashed '.$kd['bc'].'; padding-top:3px; margin-top:1px;' : '' }}">
                    <span style="font-size:10px; color:#6b7280; flex-shrink:0;">{{ $ej['txt'] }}</span>
                    @if($ej['cal'])
                    <span style="font-size:10px; font-family:monospace; font-weight:{{ !empty($ej['bold']) ? '700' : '500' }}; color:{{ !empty($ej['bold']) ? $kd['cl'] : '#374151' }}; text-align:right;">{{ $ej['cal'] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
            @if($kd['nota'])
            <p style="font-size:10px; font-weight:600; color:{{ $kd['cl'] }}; margin:7px 0 0; font-style:italic; border-top:1px solid {{ $kd['bc'] }}; padding-top:6px;">{{ $kd['nota'] }}</p>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Pedidos con cuotas expandibles --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="padding:14px 18px; border-bottom:1px solid #f0fdf4;">
        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D; margin:0;">
            Mis Pedidos
            <span style="font-weight:400; color:#9ca3af;">({{ $pedidos->count() }})</span>
        </p>
    </div>

    @forelse($pedidos as $ped)
    @php $expandido = $pedidoExpandido === $ped['id']; @endphp

    {{-- Fila resumen del pedido --}}
    <div style="border-bottom:1px solid #f3f4f6;">
        <div style="display:grid; grid-template-columns:1fr auto; align-items:center; padding:14px 18px; gap:12px;">

            {{-- Info izquierda --}}
            <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                {{-- Número + estado --}}
                <div>
                    <p style="font-size:13px; font-weight:800; color:#166534; margin:0; font-family:monospace;">{{ $ped['numero'] }}</p>
                    <p style="font-size:10px; color:#9ca3af; margin:1px 0 0;">{{ $ped['vendedor'] }}</p>
                </div>

                {{-- Badge estado financiero --}}
                <span style="font-size:10px; font-weight:700; padding:3px 10px; border-radius:10px; background:{{ $ped['estado_badge']['bg'] }}; color:{{ $ped['estado_badge']['cl'] }};">
                    {{ $ped['estado_badge']['lb'] }}
                </span>

                {{-- Barra de progreso cuotas --}}
                <div style="min-width:120px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                        <span style="font-size:10px; color:#6b7280;">Cuotas pagadas</span>
                        <span style="font-size:10px; font-weight:700; color:#15803D;">{{ $ped['pagadas'] }}/{{ $ped['total_cuotas'] }}</span>
                    </div>
                    <div style="height:5px; background:#e5e7eb; border-radius:99px; overflow:hidden;">
                        @php $pct = $ped['total_cuotas'] > 0 ? round(($ped['pagadas'] / $ped['total_cuotas']) * 100) : 0; @endphp
                        <div style="height:100%; width:{{ $pct }}%; background:#22c55e; border-radius:99px; transition:width 0.4s;"></div>
                    </div>
                </div>

                {{-- Indicadores rápidos --}}
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <div style="text-align:center;">
                        <p style="font-size:11px; font-weight:700; font-family:monospace; color:{{ $ped['puntualidad'] >= 90 ? '#15803D' : ($ped['puntualidad'] >= 70 ? '#854F0B' : '#B91C1C') }}; margin:0;">{{ $ped['puntualidad'] }}%</p>
                        <p style="font-size:9px; color:#9ca3af; margin:0; text-transform:uppercase;">Puntualidad</p>
                    </div>
                    <div style="text-align:center;">
                        <p style="font-size:11px; font-weight:700; font-family:monospace; color:{{ $ped['riesgo'] > 30 ? '#B91C1C' : ($ped['riesgo'] > 10 ? '#854F0B' : '#15803D') }}; margin:0;">{{ $ped['riesgo'] }}%</p>
                        <p style="font-size:9px; color:#9ca3af; margin:0; text-transform:uppercase;">C. Riesgo</p>
                    </div>
                    @if($ped['reprogramado'])
                    <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FEF3C7; color:#854F0B; align-self:center;">Reprogramado</span>
                    @endif
                </div>

                {{-- Monto --}}
                <div style="text-align:right; margin-left:auto;">
                    <p style="font-size:13px; font-weight:700; font-family:monospace; color:#374151; margin:0;">Bs {{ number_format($ped['monto'], 2) }}</p>
                    <p style="font-size:9px; color:#9ca3af; margin:1px 0 0; text-transform:uppercase;">Monto total</p>
                </div>
            </div>

            {{-- Botón expandir --}}
            <button wire:click="togglePedido({{ $ped['id'] }})"
                    style="padding:6px 12px; border-radius:8px; border:1.5px solid {{ $expandido ? '#6ee7b7' : '#e5e7eb' }}; background:{{ $expandido ? '#f0fdf4' : '#fff' }}; color:{{ $expandido ? '#15803D' : '#6b7280' }}; cursor:pointer; display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600; white-space:nowrap; transition:all 0.15s;">
                <svg style="width:13px;height:13px;transition:transform 0.2s;{{ $expandido ? 'transform:rotate(180deg);' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
                {{ $expandido ? 'Ocultar' : 'Ver cuotas' }}
            </button>
        </div>

        {{-- Tabla de cuotas expandible --}}
        @if($expandido)
        <div style="background:#fafafa; border-top:1px solid #f0fdf4; padding:0 18px 14px;">
            <table style="width:100%; border-collapse:collapse; font-size:12px; margin-top:12px;">
                <thead>
                    <tr style="background:#f0fdf4;">
                        <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:80px;">#</th>
                        <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Vencimiento</th>
                        <th style="padding:7px 10px; text-align:right; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Monto</th>
                        <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:100px;">Estado</th>
                        <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Fecha de Pago</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($ped['cuotas'] as $cuota)
                @php $esInicial = $cuota['numero'] === 0; @endphp
                <tr style="border-bottom:0.5px solid #e5e7eb; background:{{ $cuota['estado'] === 'pagado' ? '#fafff8' : ($cuota['estado'] === 'en_mora' ? '#fff8f8' : '#fff') }};">
                    <td style="padding:8px 10px; text-align:center; font-family:monospace; font-weight:700; color:#374151;">
                        {{ $esInicial ? 'Inicial' : $cuota['numero'] }}
                    </td>
                    <td style="padding:8px 10px; text-align:center; color:#6b7280;">
                        {{ $cuota['fecha_vencimiento'] ? $cuota['fecha_vencimiento']->format('d/m/Y') : '—' }}
                    </td>
                    <td style="padding:8px 10px; text-align:right; font-family:monospace; font-weight:600; color:#374151;">
                        Bs {{ number_format($cuota['monto'], 2) }}
                    </td>
                    <td style="padding:8px 10px; text-align:center;">
                        <span style="font-size:10px; font-weight:700; padding:2px 10px; border-radius:10px; background:{{ $cuota['badge']['bg'] }}; color:{{ $cuota['badge']['cl'] }};">
                            {{ $cuota['badge']['lb'] }}
                        </span>
                    </td>
                    <td style="padding:8px 10px; text-align:center; color:{{ $cuota['estado'] === 'pagado' ? '#15803D' : '#9ca3af' }}; font-family:monospace; font-size:11px;">
                        {{ $cuota['fecha_pago'] ? $cuota['fecha_pago']->format('d/m/Y') : '—' }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @empty
    <div style="padding:40px; text-align:center; color:#9ca3af; font-size:13px;">
        No tenés pedidos aprobados con plan de pago activo.
    </div>
    @endforelse
</div>

@endif
</div>
