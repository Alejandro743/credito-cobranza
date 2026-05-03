<div>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
.cv-badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.cv-th    { padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; border:0.5px solid #d1fae5; background:#f0fdf4; color:#15803D; }
.cv-td    { padding:9px 12px; font-size:12px; border:0.5px solid #e5e7eb; color:#374151; }
</style>

@if($mode === 'list')
<div x-data="{ infoOpen: false }">

{{-- KPI Cards --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px;">
    @php
    $kpiCfg = [
        ['label'=>'Total Vendedores','val'=>$kpis['total'],'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#d1fae5'],
        ['label'=>'Calif. A / B',    'val'=>$kpis['ab'],   'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#6ee7b7'],
        ['label'=>'Calif. C',        'val'=>$kpis['c'],    'bg'=>'#FFFBEB','cl'=>'#854F0B','bc'=>'#FCD34D'],
        ['label'=>'D / Bloqueado',   'val'=>$kpis['db'],   'bg'=>'#FEF2F2','cl'=>'#B91C1C','bc'=>'#FCA5A5'],
    ];
    @endphp
    @foreach($kpiCfg as $k)
    <div style="background:{{ $k['bg'] }}; border:1px solid {{ $k['bc'] }}; border-radius:12px; padding:14px 16px; text-align:center;">
        <p style="font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; color:{{ $k['cl'] }}; margin:0 0 6px;">{{ $k['label'] }}</p>
        <p style="font-size:26px; font-weight:800; color:{{ $k['cl'] }}; margin:0; font-family:monospace;">{{ $k['val'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filtros y ordenamiento --}}
<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    <div style="display:flex; gap:6px; flex-wrap:wrap;">
        @foreach(['todos'=>'Todos','A'=>'A','B'=>'B','C'=>'C','D'=>'D','BLOQUEADO'=>'Bloqueado'] as $val=>$lbl)
        @php
        $activo = $filtroCalificacion === $val;
        $cfg = match($val) {
            'A'         => ['bo'=>'#6ee7b7','bg'=>'#DCFCE7','cl'=>'#15803D'],
            'B'         => ['bo'=>'#67e8f9','bg'=>'#ECFEFF','cl'=>'#0e7490'],
            'C'         => ['bo'=>'#FCD34D','bg'=>'#FEF3C7','cl'=>'#854F0B'],
            'D'         => ['bo'=>'#fdba74','bg'=>'#FFF7ED','cl'=>'#C2410C'],
            'BLOQUEADO' => ['bo'=>'#fca5a5','bg'=>'#FEF2F2','cl'=>'#B91C1C'],
            default     => ['bo'=>'#d1fae5','bg'=>'#F0FDF4','cl'=>'#15803D'],
        };
        @endphp
        <button wire:click="$set('filtroCalificacion','{{ $val }}')"
                style="padding:5px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer;
                       border:1.5px solid {{ $activo ? $cfg['bo'] : '#e5e7eb' }};
                       background:{{ $activo ? $cfg['bg'] : '#fff' }};
                       color:{{ $activo ? $cfg['cl'] : '#6b7280' }};">
            {{ $lbl }}
        </button>
        @endforeach
    </div>
    <div style="margin-left:auto; display:flex; align-items:center; gap:8px;">
        <button wire:click="$refresh" wire:loading.class="opacity-50"
                style="padding:5px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid #d1fae5; background:#f0fdf4; color:#15803D; display:inline-flex; align-items:center; gap:5px;">
            <svg wire:loading.remove wire:target="$refresh" style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <svg wire:loading wire:target="$refresh" style="width:12px;height:12px;animation:spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Actualizar
        </button>
        <div style="position:relative;">
            <svg style="position:absolute; left:8px; top:50%; transform:translateY(-50%); width:12px; height:12px;"
                 fill="none" stroke="#6ee7b7" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="buscarVendedor" type="text" placeholder="Buscar vendedor..."
                   style="padding:5px 10px 5px 26px; border-radius:6px; font-size:11px; border:1.5px solid #d1fae5; background:#f9fffe; color:#374151; outline:none; width:170px;" />
        </div>
        <select wire:model.live="ordenar"
                style="padding:5px 10px; border-radius:6px; font-size:11px; border:1px solid #d1fae5; background:#f9fffe; color:#374151; cursor:pointer;">
            <option value="puntaje_desc">Mayor puntaje</option>
            <option value="puntaje_asc">Menor puntaje</option>
            <option value="nombre">Nombre A-Z</option>
        </select>

        <button @click="infoOpen = !infoOpen"
                style="padding:5px 11px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid #d1fae5; display:inline-flex; align-items:center; gap:5px;"
                :style="infoOpen ? 'background:#f0fdf4; color:#15803D;' : 'background:#fff; color:#6b7280;'">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Cómo se calcula
        </button>
    </div>
</div>

{{-- Panel explicativo de la fórmula --}}
<div x-show="infoOpen" x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
     style="background:#fff; border:1px solid #d1fae5; border-radius:14px; padding:18px 20px; margin-bottom:16px;">

    <p style="font-size:12px; font-weight:800; color:#15803D; margin:0 0 14px; text-transform:uppercase; letter-spacing:0.05em;">
        Fórmula de calificación de cartera
    </p>

    {{-- 5 indicadores --}}
    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:8px; margin-bottom:16px;">
        @php
        $infoIndicadores = [
            ['nombre'=>'Puntualidad',    'peso'=>$pesos->peso_puntualidad,    'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#d1fae5',
             'formula'=>'Cuotas pagadas antes o en fecha / total cuotas vencidas × 100',
             'contribuye'=>'Positivo — a mayor puntualidad, mejor puntaje'],
            ['nombre'=>'Mora Generada',  'peso'=>$pesos->peso_mora,           'bg'=>'#FEF2F2','cl'=>'#B91C1C','bc'=>'#fecaca',
             'formula'=>'Pedidos con al menos 1 cuota vencida sin pagar / total pedidos × 100',
             'contribuye'=>'Negativo — se invierte: (100 − mora%) × peso'],
            ['nombre'=>'Cartera en Riesgo','peso'=>$pesos->peso_riesgo,       'bg'=>'#FFF7ED','cl'=>'#C2410C','bc'=>'#fdba74',
             'formula'=>'Saldo vencido sin pagar / (saldo vencido + saldo futuro) × 100',
             'contribuye'=>'Negativo — se invierte: (100 − riesgo%) × peso'],
            ['nombre'=>'Recuperación',   'peso'=>$pesos->peso_recuperacion,   'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#d1fae5',
             'formula'=>'Monto recuperado con pago tardío / monto total vencido sin pagar × 100',
             'contribuye'=>'Positivo — a mayor recuperación, mejor puntaje'],
            ['nombre'=>'Reprogramación', 'peso'=>$pesos->peso_reprogramacion, 'bg'=>'#FFFBEB','cl'=>'#854F0B','bc'=>'#FCD34D',
             'formula'=>'Pedidos con más de 1 versión de plan / total pedidos × 100',
             'contribuye'=>'Negativo — se invierte: (100 − reprog%) × peso'],
        ];
        @endphp
        @foreach($infoIndicadores as $ii)
        <div style="background:{{ $ii['bg'] }}; border:1px solid {{ $ii['bc'] }}; border-radius:10px; padding:10px 11px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <span style="font-size:10px; font-weight:700; color:{{ $ii['cl'] }}; text-transform:uppercase; letter-spacing:0.03em;">{{ $ii['nombre'] }}</span>
                <span style="font-size:11px; font-weight:800; color:{{ $ii['cl'] }}; background:#fff; border:1px solid {{ $ii['bc'] }}; border-radius:8px; padding:1px 7px;">{{ $ii['peso'] }}%</span>
            </div>
            <p style="font-size:10px; color:#6b7280; margin:0 0 4px; line-height:1.4;">{{ $ii['formula'] }}</p>
            <p style="font-size:10px; color:{{ $ii['cl'] }}; font-style:italic; margin:0;">{{ $ii['contribuye'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Fórmula del puntaje --}}
    <div style="background:#f0fdf4; border:1px solid #d1fae5; border-radius:10px; padding:10px 14px; margin-bottom:14px; font-size:11px; color:#166534; font-family:monospace; line-height:1.8;">
        <span style="font-weight:700; font-family:sans-serif; font-size:10px; text-transform:uppercase; letter-spacing:0.05em; color:#15803D;">Puntaje =&nbsp;</span>
        (Puntualidad × {{ $pesos->peso_puntualidad }}%) +
        ((100 − Mora%) × {{ $pesos->peso_mora }}%) +
        ((100 − Riesgo%) × {{ $pesos->peso_riesgo }}%) +
        (Recuperación × {{ $pesos->peso_recuperacion }}%) +
        ((100 − Reprog%) × {{ $pesos->peso_reprogramacion }}%)
    </div>

    {{-- Umbrales de calificación vigentes --}}
    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
        <span style="font-size:10px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.04em; margin-right:4px;">Calificación vigente:</span>
        <span style="font-size:11px; font-weight:700; padding:3px 12px; border-radius:10px; background:#DCFCE7; color:#15803D;">A ≥ {{ $rangos->min_a }}</span>
        <span style="font-size:11px; font-weight:700; padding:3px 12px; border-radius:10px; background:#ECFEFF; color:#0e7490;">B ≥ {{ $rangos->min_b }}</span>
        <span style="font-size:11px; font-weight:700; padding:3px 12px; border-radius:10px; background:#FEF3C7; color:#854F0B;">C ≥ {{ $rangos->min_c }}</span>
        <span style="font-size:11px; font-weight:700; padding:3px 12px; border-radius:10px; background:#FFF7ED; color:#C2410C;">D ≥ {{ $rangos->min_d }}</span>
        <span style="font-size:11px; font-weight:700; padding:3px 12px; border-radius:10px; background:#FEF2F2; color:#B91C1C;">BLOQUEADO &lt; {{ $rangos->min_d }}</span>
        <span style="font-size:10px; color:#9ca3af; margin-left:4px;">— configuración: <em>{{ $rangos->nombre ?? 'Por Defecto' }}</em></span>
    </div>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; min-width:820px;">
        <thead>
            <tr>
                <th class="cv-th" style="text-align:left;">Vendedor</th>
                <th class="cv-th" style="text-align:center; width:100px;">Puntualidad%</th>
                <th class="cv-th" style="text-align:center; width:80px;">Mora%</th>
                <th class="cv-th" style="text-align:center; width:90px;">C.Riesgo%</th>
                <th class="cv-th" style="text-align:center; width:100px;">Recuperación%</th>
                <th class="cv-th" style="text-align:center; width:80px;">Reprog.%</th>
                <th class="cv-th" style="text-align:center; width:80px;">Puntaje</th>
                <th class="cv-th" style="text-align:center; width:100px;">Calificación</th>
                <th class="cv-th" style="text-align:center; width:80px;">Detalle</th>
            </tr>
        </thead>
        <tbody>
        @forelse($vendedores as $v)
        @php
        $calBadge = match($v['calificacion']) {
            'A'         => ['bg'=>'#DCFCE7','cl'=>'#15803D'],
            'B'         => ['bg'=>'#ECFEFF','cl'=>'#0e7490'],
            'C'         => ['bg'=>'#FEF3C7','cl'=>'#854F0B'],
            'D'         => ['bg'=>'#FFF7ED','cl'=>'#C2410C'],
            'BLOQUEADO' => ['bg'=>'#FEF2F2','cl'=>'#B91C1C'],
            default     => ['bg'=>'#f3f4f6','cl'=>'#6b7280'],
        };
        @endphp
        <tr wire:key="v-{{ $v['id'] }}">
            <td class="cv-td" style="font-weight:600; color:#166534;">
                {{ $v['nombre'] }}
                <span style="font-size:10px; color:#9ca3af; font-weight:400;">({{ $v['total_pedidos'] }} ped.)</span>
            </td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-weight:600;">{{ $v['puntualidad'] }}%</td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-weight:600; color:{{ $v['mora'] > 20 ? '#B91C1C' : '#374151' }};">{{ $v['mora'] }}%</td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-weight:600; color:{{ $v['riesgo'] > 30 ? '#C2410C' : '#374151' }};">{{ $v['riesgo'] }}%</td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-weight:600;">{{ $v['recuperacion'] }}%</td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-weight:600; color:{{ $v['reprog'] > 20 ? '#C2410C' : '#374151' }};">{{ $v['reprog'] }}%</td>
            <td class="cv-td" style="text-align:center; font-family:monospace; font-size:14px; font-weight:800; color:#166534;">{{ $v['puntaje'] }}</td>
            <td class="cv-td" style="text-align:center;">
                <span class="cv-badge" style="background:{{ $calBadge['bg'] }}; color:{{ $calBadge['cl'] }};">
                    {{ $v['calificacion'] }}
                </span>
            </td>
            <td class="cv-td" style="text-align:center;">
                <button wire:click="verDetalle({{ $v['id'] }})" title="Ver detalle"
                        style="padding:4px 10px; border-radius:6px; border:1px solid #d1fae5; background:#f0fdf4; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:600;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="padding:40px; text-align:center; color:#9ca3af; font-size:13px;">
                No hay vendedores con pedidos aprobados.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

</div>{{-- /x-data infoOpen --}}

@elseif($mode === 'detalle' && $vendedorDetalle)
@php
$v = $vendedorDetalle;
$calBadge = match($v['calificacion']) {
    'A'         => ['bg'=>'#DCFCE7','cl'=>'#15803D'],
    'B'         => ['bg'=>'#ECFEFF','cl'=>'#0e7490'],
    'C'         => ['bg'=>'#FEF3C7','cl'=>'#854F0B'],
    'D'         => ['bg'=>'#FFF7ED','cl'=>'#C2410C'],
    'BLOQUEADO' => ['bg'=>'#FEF2F2','cl'=>'#B91C1C'],
    default     => ['bg'=>'#f3f4f6','cl'=>'#6b7280'],
};
@endphp

{{-- Header con Volver --}}
<div style="background:#f0fdf4; border:1px solid #d1fae5; border-radius:14px; padding:14px 18px; margin-bottom:20px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <button wire:click="backToList"
                style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #d1fae5; border-radius:20px; padding:5px 14px 5px 10px; cursor:pointer;">
            <svg width="14" height="14" fill="none" stroke="#15803D" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/></svg>
            <span style="font-size:11px; font-weight:700; color:#15803D;">Volver</span>
        </button>
        <div style="flex:1; text-align:center;">
            <p style="font-size:11px; color:#9ca3af; margin:0;">Calificación de Cartera</p>
            <h1 style="font-size:17px; font-weight:800; color:#166534; margin:0;">{{ $v['nombre'] }}</h1>
        </div>
        <div style="width:90px; display:flex; justify-content:flex-end;">
            <span class="cv-badge" style="background:{{ $calBadge['bg'] }}; color:{{ $calBadge['cl'] }}; font-size:14px; padding:4px 14px;">
                {{ $v['calificacion'] }}
            </span>
        </div>
    </div>
</div>

{{-- KPIs con info expandible --}}
@php
$kpis_detalle = [
    [
        'label' => 'Puntaje Final',
        'val'   => $v['puntaje'].' pts',
        'bg'    => $calBadge['bg'], 'cl' => $calBadge['cl'], 'bc' => $calBadge['cl'],
        'icono' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        'titulo_info' => 'Suma ponderada',
        'info'  => 'Es la suma de los 5 indicadores, cada uno multiplicado por su peso configurado en Definiciones → Pesos de Indicadores.',
        'formula' => '(Puntualidad × p%) + ((100−Mora) × p%) + ((100−Riesgo) × p%) + (Recuperación × p%) + ((100−Reprog) × p%)',
        'nota'  => null,
    ],
    [
        'label' => 'Puntualidad',
        'val'   => $v['puntualidad'].' %',
        'bg'    => '#F0FDF4', 'cl' => '#15803D', 'bc' => '#d1fae5',
        'icono' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'titulo_info' => 'Indicador positivo',
        'info'  => 'De todas las cuotas cuya fecha de vencimiento ya pasó, calcula qué porcentaje fue pagado en fecha o antes.',
        'formula' => 'Cuotas pagadas a tiempo ÷ total cuotas vencidas × 100',
        'nota'  => '↑ Cuanto más alto, mejor puntaje.',
    ],
    [
        'label' => 'Mora Generada',
        'val'   => $v['mora'].' %',
        'bg'    => '#FEF2F2', 'cl' => '#B91C1C', 'bc' => '#fecaca',
        'icono' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'titulo_info' => 'Indicador negativo',
        'info'  => 'Mide qué porcentaje de los pedidos tiene al menos una cuota vencida que todavía no fue pagada.',
        'formula' => 'Pedidos con cuota vencida sin pagar ÷ total pedidos × 100',
        'nota'  => '↓ Se invierte: se resta de 100 antes de ponderar. Cuanto más bajo, mejor.',
    ],
    [
        'label' => 'C. en Riesgo',
        'val'   => $v['riesgo'].' %',
        'bg'    => '#FFF7ED', 'cl' => '#C2410C', 'bc' => '#fdba74',
        'icono' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
        'titulo_info' => 'Indicador negativo',
        'info'  => 'Del total de saldo pendiente de cobro (vencido + futuro), calcula qué proporción ya está vencida y sin pagar.',
        'formula' => 'Saldo vencido sin pagar ÷ (saldo vencido + saldo futuro pendiente) × 100',
        'nota'  => '↓ Se invierte al ponderar. Un valor alto indica cartera deteriorada.',
    ],
    [
        'label' => 'Recuperación',
        'val'   => $v['recuperacion'].' %',
        'bg'    => '#F0FDF4', 'cl' => '#15803D', 'bc' => '#d1fae5',
        'icono' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'titulo_info' => 'Indicador positivo',
        'info'  => 'De todas las cuotas que estaban vencidas sin pagar, mide cuánto monto se logró cobrar aunque fuera con retraso.',
        'formula' => 'Monto cobrado tardíamente ÷ total monto vencido sin pagar × 100',
        'nota'  => '↑ Refleja capacidad de gestión de cobranza. Cuanto más alto, mejor.',
    ],
    [
        'label' => 'Reprogramación',
        'val'   => $v['reprog'].' %',
        'bg'    => '#FFFBEB', 'cl' => '#854F0B', 'bc' => '#FCD34D',
        'icono' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
        'titulo_info' => 'Indicador negativo',
        'info'  => 'Porcentaje de pedidos que tuvieron que ser reprogramados, es decir, que tienen más de una versión de plan de pago.',
        'formula' => 'Pedidos con más de 1 versión de plan ÷ total pedidos × 100',
        'nota'  => '↓ Se invierte al ponderar. Muchas reprogramaciones indican riesgo de cartera.',
    ],
];
@endphp
<div style="display:grid; grid-template-columns:repeat(6,1fr); gap:10px; margin-bottom:20px;">
    @foreach($kpis_detalle as $kd)
    <div x-data="{ open: false }" @click.away="open = false"
         style="background:{{ $kd['bg'] }}; border:1px solid {{ $kd['bc'] }}; border-radius:10px; padding:10px 12px; position:relative;">

        {{-- Label + botón ⓘ --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <span style="font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:{{ $kd['cl'] }};">{{ $kd['label'] }}</span>
            <button @click.stop="open = !open"
                    style="width:15px; height:15px; border-radius:50%; border:1px solid {{ $kd['bc'] }}; background:#fff; color:{{ $kd['cl'] }}; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; padding:0;">
                <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>
        </div>

        {{-- Valor --}}
        <p style="font-size:20px; font-weight:800; color:{{ $kd['cl'] }}; margin:0; font-family:monospace; text-align:center; line-height:1.1;">{{ $kd['val'] }}</p>

        {{-- Tooltip flotante --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             style="position:absolute; top:calc(100% + 6px); left:50%; transform:translateX(-50%); z-index:99;
                    width:230px; background:#fff; border:1px solid {{ $kd['bc'] }}; border-radius:10px;
                    box-shadow:0 8px 24px rgba(0,0,0,0.12); padding:12px 13px;">
            {{-- Flecha --}}
            <div style="position:absolute; top:-6px; left:50%; transform:translateX(-50%); width:10px; height:10px; background:#fff; border-left:1px solid {{ $kd['bc'] }}; border-top:1px solid {{ $kd['bc'] }}; transform:translateX(-50%) rotate(45deg);"></div>
            <p style="font-size:10px; font-weight:700; color:{{ $kd['cl'] }}; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 5px;">{{ $kd['titulo_info'] }}</p>
            <p style="font-size:11px; color:#374151; margin:0 0 7px; line-height:1.5;">{{ $kd['info'] }}</p>
            <div style="background:{{ $kd['bg'] }}; border:1px solid {{ $kd['bc'] }}; border-radius:6px; padding:5px 8px; font-size:10px; font-family:monospace; color:{{ $kd['cl'] }}; line-height:1.6; margin-bottom:{{ $kd['nota'] ? '6px' : '0' }};">
                {{ $kd['formula'] }}
            </div>
            @if($kd['nota'])
            <p style="font-size:10px; font-weight:600; color:{{ $kd['cl'] }}; margin:0; font-style:italic;">{{ $kd['nota'] }}</p>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Tabla de pedidos --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="padding:14px 16px; border-bottom:1px solid #f0fdf4; display:flex; align-items:center; justify-content:space-between;">
        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D; margin:0;">
            Pedidos individuales
            <span style="font-weight:400; color:#9ca3af;">({{ $detallePedidos->count() }})</span>
        </p>
        <span style="font-size:11px; color:#6b7280;">Total pedidos activos con plan: {{ $v['total_pedidos'] }}</span>
    </div>
    @if($detallePedidos->isNotEmpty())
    <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; font-size:12px;">
        <thead>
            <tr style="background:#f0fdf4;">
                <th style="padding:8px 12px; text-align:left; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Pedido</th>
                <th style="padding:8px 12px; text-align:left; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Cliente</th>
                <th style="padding:8px 12px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:130px;">Cuotas (al día / venc.)</th>
                <th style="padding:8px 12px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:95px;">Puntualidad</th>
                <th style="padding:8px 12px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:70px;">Mora</th>
                <th style="padding:8px 12px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:85px;">C. Riesgo</th>
                <th style="padding:8px 12px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:80px;">Reprog.</th>
                <th style="padding:8px 12px; text-align:right; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:120px;">Monto total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($detallePedidos as $ped)
        @php
        $ptColor = $ped['puntualidad'] >= 90 ? '#15803D' : ($ped['puntualidad'] >= 70 ? '#854F0B' : '#B91C1C');
        $rsColor = $ped['riesgo'] > 30 ? '#B91C1C' : ($ped['riesgo'] > 10 ? '#854F0B' : '#15803D');
        @endphp
        <tr style="border-bottom:0.5px solid #e5e7eb;">
            <td style="padding:10px 12px; font-weight:700; color:#374151; font-family:monospace;">{{ $ped['numero'] }}</td>
            <td style="padding:10px 12px; color:#374151;">{{ $ped['cliente'] }}</td>
            <td style="padding:10px 12px; text-align:center; color:#6b7280; font-family:monospace;">
                {{ $ped['al_dia'] }}/{{ $ped['cerradas'] }}
                <span style="color:#9ca3af; font-size:10px;">({{ $ped['total_cuotas'] }} total)</span>
            </td>
            <td style="padding:10px 12px; text-align:center; font-weight:700; font-family:monospace; color:{{ $ptColor }};">{{ $ped['puntualidad'] }}%</td>
            <td style="padding:10px 12px; text-align:center;">
                @if($ped['en_mora'])
                <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FEF2F2; color:#B91C1C;">Sí</span>
                @else
                <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#F0FDF4; color:#15803D;">No</span>
                @endif
            </td>
            <td style="padding:10px 12px; text-align:center; font-weight:700; font-family:monospace; color:{{ $rsColor }};">{{ $ped['riesgo'] }}%</td>
            <td style="padding:10px 12px; text-align:center;">
                @if($ped['reprogramado'])
                <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#FEF3C7; color:#854F0B;">Sí</span>
                @else
                <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; background:#F0FDF4; color:#15803D;">No</span>
                @endif
            </td>
            <td style="padding:10px 12px; text-align:right; font-weight:600; font-family:monospace; color:#374151;">
                Bs {{ number_format($ped['monto'], 2) }}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    @else
    <p style="padding:30px; text-align:center; color:#9ca3af; font-size:13px;">Sin pedidos con plan de pago activo.</p>
    @endif
</div>

@endif
</div>
