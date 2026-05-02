<div>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
.cv-badge  { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.cv-card   { background:#fff; border-radius:12px; border:1px solid #d1fae5; box-shadow:0 1px 4px rgba(0,0,0,0.04); padding:14px 16px; }
.cv-th     { padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; border:0.5px solid #d1fae5; background:#f0fdf4; color:#15803D; }
.cv-td     { padding:9px 12px; font-size:12px; border:0.5px solid #e5e7eb; color:#374151; }
</style>

{{-- KPI Cards --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px;">
    @php
    $kpiCfg = [
        ['label'=>'Total Vendedores', 'val'=>$kpis['total'], 'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#d1fae5'],
        ['label'=>'Calif. A / B',     'val'=>$kpis['ab'],    'bg'=>'#F0FDF4','cl'=>'#15803D','bc'=>'#6ee7b7'],
        ['label'=>'Calif. C',         'val'=>$kpis['c'],     'bg'=>'#FFFBEB','cl'=>'#854F0B','bc'=>'#FCD34D'],
        ['label'=>'D / Bloqueado',    'val'=>$kpis['db'],    'bg'=>'#FEF2F2','cl'=>'#B91C1C','bc'=>'#FCA5A5'],
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
    </div>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; min-width:780px;">
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
        $isOpen = $detalleId === $v['id'];
        @endphp
        <tr wire:key="v-{{ $v['id'] }}"
            wire:click="toggleDetalle({{ $v['id'] }})"
            style="cursor:pointer; {{ $isOpen ? 'background:#f0fdf4;' : '' }}"
            class="hover:bg-green-50 transition-colors">
            <td class="cv-td" style="font-weight:600; color:#166534;">
                <div style="display:flex; align-items:center; gap:6px;">
                    <svg style="width:14px;height:14px;flex-shrink:0;color:#6ee7b7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isOpen ? 'M19 9l-7 7-7-7' : 'M9 5l7 7-7 7' }}"/>
                    </svg>
                    {{ $v['nombre'] }}
                    <span style="font-size:10px; color:#9ca3af; font-weight:400;">({{ $v['total_pedidos'] }} ped.)</span>
                </div>
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
        </tr>

        {{-- Panel de detalle inline --}}
        @if($isOpen)
        <tr wire:key="detalle-{{ $v['id'] }}">
            <td colspan="8" style="padding:0; border:0.5px solid #d1fae5; background:#f9fffe;">
                <div style="padding:16px 20px;">

                    {{-- Tarjetas de indicadores --}}
                    <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D; margin:0 0 10px;">Indicadores — {{ $v['nombre'] }}</p>
                    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:10px;">
                        @php
                        $indicadores = [
                            ['nombre'=>'Puntualidad',    'pct'=>$v['puntualidad'],  'peso'=>$pesos->peso_puntualidad,    'puntos'=>$v['puntualidad'],        'aporte'=>round($v['puntualidad']        * $pesos->peso_puntualidad    / 100, 1)],
                            ['nombre'=>'Mora generada',  'pct'=>$v['mora'],          'peso'=>$pesos->peso_mora,           'puntos'=>100-$v['mora'],           'aporte'=>round((100-$v['mora'])         * $pesos->peso_mora           / 100, 1)],
                            ['nombre'=>'C. en Riesgo',   'pct'=>$v['riesgo'],        'peso'=>$pesos->peso_riesgo,         'puntos'=>100-$v['riesgo'],         'aporte'=>round((100-$v['riesgo'])       * $pesos->peso_riesgo         / 100, 1)],
                            ['nombre'=>'Recuperación',   'pct'=>$v['recuperacion'],  'peso'=>$pesos->peso_recuperacion,   'puntos'=>$v['recuperacion'],       'aporte'=>round($v['recuperacion']       * $pesos->peso_recuperacion   / 100, 1)],
                            ['nombre'=>'Reprogramación', 'pct'=>$v['reprog'],        'peso'=>$pesos->peso_reprogramacion, 'puntos'=>100-$v['reprog'],         'aporte'=>round((100-$v['reprog'])       * $pesos->peso_reprogramacion / 100, 1)],
                        ];
                        @endphp
                        @foreach($indicadores as $ind)
                        <div style="background:#fff; border:1px solid #d1fae5; border-radius:10px; padding:12px; text-align:center;">
                            <p style="font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; margin:0 0 8px; letter-spacing:0.04em;">{{ $ind['nombre'] }}</p>
                            <p style="font-size:18px; font-weight:800; color:#166534; margin:0; font-family:monospace;">{{ $ind['pct'] }}%</p>
                            <div style="margin:8px 0; height:1px; background:#d1fae5;"></div>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0;">Peso: <strong style="color:#374151;">{{ $ind['peso'] }}%</strong></p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0;">Puntos: <strong style="color:#374151;">{{ $ind['puntos'] }}</strong></p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0;">Aporte: <strong style="color:#15803D;">{{ $ind['aporte'] }}</strong></p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Puntaje final --}}
                    <div style="margin-top:10px; text-align:right;">
                        <span style="font-size:13px; font-weight:700; color:#166534;">
                            Puntaje final: <span style="font-family:monospace; font-size:16px;">{{ $v['puntaje'] }}</span>
                            &nbsp;→&nbsp;
                            <span class="cv-badge" style="background:{{ $calBadge['bg'] }}; color:{{ $calBadge['cl'] }}; font-size:12px;">{{ $v['calificacion'] }}</span>
                        </span>
                    </div>

                    {{-- Tabla de pedidos individuales --}}
                    @if($detallePedidos->isNotEmpty())
                    <div style="margin-top:16px;">
                        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D; margin:0 0 8px;">
                            Desglose por Pedido
                            <span style="font-weight:400; color:#9ca3af;">({{ $detallePedidos->count() }} pedido{{ $detallePedidos->count() !== 1 ? 's' : '' }})</span>
                        </p>
                        <div style="overflow-x:auto; border-radius:8px; border:1px solid #d1fae5;">
                        <table style="width:100%; border-collapse:collapse; font-size:11px;">
                            <thead>
                                <tr style="background:#f0fdf4;">
                                    <th style="padding:7px 10px; text-align:left; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Pedido</th>
                                    <th style="padding:7px 10px; text-align:left; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5;">Cliente</th>
                                    <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:110px;">Cuotas (al día/venc.)</th>
                                    <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:90px;">Puntualidad</th>
                                    <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:70px;">Mora</th>
                                    <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:80px;">C. Riesgo</th>
                                    <th style="padding:7px 10px; text-align:center; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:75px;">Reprog.</th>
                                    <th style="padding:7px 10px; text-align:right; font-size:10px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid #d1fae5; width:110px;">Monto total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($detallePedidos as $ped)
                            @php
                            $ptColor = $ped['puntualidad'] >= 90 ? '#15803D' : ($ped['puntualidad'] >= 70 ? '#854F0B' : '#B91C1C');
                            $rsColor = $ped['riesgo'] > 30 ? '#B91C1C' : ($ped['riesgo'] > 10 ? '#854F0B' : '#15803D');
                            @endphp
                            <tr style="border-bottom:0.5px solid #e5e7eb;">
                                <td style="padding:7px 10px; font-weight:700; color:#374151; font-family:monospace;">{{ $ped['numero'] }}</td>
                                <td style="padding:7px 10px; color:#374151;">{{ $ped['cliente'] }}</td>
                                <td style="padding:7px 10px; text-align:center; color:#6b7280; font-family:monospace;">
                                    {{ $ped['al_dia'] }}/{{ $ped['cerradas'] }}
                                    <span style="color:#9ca3af; font-size:10px;">({{ $ped['total_cuotas'] }})</span>
                                </td>
                                <td style="padding:7px 10px; text-align:center; font-weight:700; font-family:monospace; color:{{ $ptColor }};">{{ $ped['puntualidad'] }}%</td>
                                <td style="padding:7px 10px; text-align:center;">
                                    @if($ped['en_mora'])
                                    <span style="font-size:10px; font-weight:700; padding:2px 7px; border-radius:10px; background:#FEF2F2; color:#B91C1C;">Sí</span>
                                    @else
                                    <span style="font-size:10px; font-weight:700; padding:2px 7px; border-radius:10px; background:#F0FDF4; color:#15803D;">No</span>
                                    @endif
                                </td>
                                <td style="padding:7px 10px; text-align:center; font-weight:700; font-family:monospace; color:{{ $rsColor }};">{{ $ped['riesgo'] }}%</td>
                                <td style="padding:7px 10px; text-align:center;">
                                    @if($ped['reprogramado'])
                                    <span style="font-size:10px; font-weight:700; padding:2px 7px; border-radius:10px; background:#FEF3C7; color:#854F0B;">Sí</span>
                                    @else
                                    <span style="font-size:10px; font-weight:700; padding:2px 7px; border-radius:10px; background:#F0FDF4; color:#15803D;">No</span>
                                    @endif
                                </td>
                                <td style="padding:7px 10px; text-align:right; font-weight:600; font-family:monospace; color:#374151;">
                                    Bs {{ number_format($ped['monto'], 2) }}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @elseif($detalleId === $v['id'])
                    <p style="margin-top:14px; font-size:11px; color:#9ca3af; text-align:center;">Sin pedidos con plan de pago activo.</p>
                    @endif

                </div>
            </td>
        </tr>
        @endif

        @empty
        <tr>
            <td colspan="8" style="padding:40px; text-align:center; color:#9ca3af; font-size:13px;">
                No hay vendedores con pedidos aprobados.
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
</div>
