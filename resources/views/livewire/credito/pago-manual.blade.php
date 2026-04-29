<div>
<style>
.pm-badge    { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
.pm-card     { background:#fff; border-radius:14px; border:1px solid #d1fae5; box-shadow:0 1px 4px rgba(0,0,0,0.05); overflow:hidden; }
.pm-pill-back{ background:#fff; border:1.5px solid #6ee7b7; border-radius:20px; padding:5px 14px 5px 10px; font-size:12px; font-weight:600; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:5px; }
.pm-th       { padding:8px 12px; color:#9ca3af; font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:0.4px; border-bottom:1px solid #f0fdf4; background:#f9fffe; }
.pm-td       { padding:9px 12px; font-size:12px; border-bottom:1px solid #f9fafb; color:#374151; }
</style>


@if(session('success'))
<div x-data="{ show:true }" x-show="show" x-init="setTimeout(()=>show=false,3500)"
     class="fixed bottom-5 right-5 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2"
     style="background:#15803D;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="p-4 sm:p-6">

{{-- ══ LIST ══ --}}
@if($mode === 'list')
@php $theadStyle = 'background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

{{-- Toolbar --}}
<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    <div style="position:relative; flex-shrink:0; width:240px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #a7f3d0; border-radius:8px;
                      background:#f0fdf4; font-size:12px; outline:none;" />
    </div>

    {{-- Filtros financieros --}}
    <div style="display:flex; gap:6px;">
        <button wire:click="$set('filtro','todos')"
                style="padding:5px 14px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid {{ $filtro==='todos' ? '#6ee7b7' : '#e5e7eb' }}; background:{{ $filtro==='todos' ? '#DCFCE7' : '#fff' }}; color:{{ $filtro==='todos' ? '#15803D' : '#6b7280' }};">
            Todos
        </button>
        <button wire:click="$set('filtro','vigente')"
                style="padding:5px 14px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid {{ $filtro==='vigente' ? '#FCD34D' : '#e5e7eb' }}; background:{{ $filtro==='vigente' ? '#FEF3C7' : '#fff' }}; color:{{ $filtro==='vigente' ? '#854F0B' : '#6b7280' }};">
            Vigente
        </button>
        <button wire:click="$set('filtro','en_mora')"
                style="padding:5px 14px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid {{ $filtro==='en_mora' ? '#fca5a5' : '#e5e7eb' }}; background:{{ $filtro==='en_mora' ? '#FEF2F2' : '#fff' }}; color:{{ $filtro==='en_mora' ? '#B91C1C' : '#6b7280' }};">
            En Mora
        </button>
        <button wire:click="$set('filtro','pagado')"
                style="padding:5px 14px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid {{ $filtro==='pagado' ? '#6ee7b7' : '#e5e7eb' }}; background:{{ $filtro==='pagado' ? '#DCFCE7' : '#fff' }}; color:{{ $filtro==='pagado' ? '#15803D' : '#6b7280' }};">
            Pagado
        </button>
    </div>

    {{-- Subir Pagos Masivos --}}
    <button wire:click="irUpload"
            style="padding:5px 14px; border-radius:6px; font-size:11px; font-weight:700; cursor:pointer; border:1.5px solid #15803D; background:#15803D; color:#fff; display:inline-flex; align-items:center; gap:5px; margin-left:auto;">
        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Subir Pagos
    </button>

    <span style="font-size:12px; color:#9ca3af; margin-left:4px;">{{ $pedidos->count() }} pedido{{ $pedidos->count() !== 1 ? 's' : '' }}</span>
</div>

{{-- Grilla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px; min-width:780px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Pedido</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Cliente</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:90px;">Estado</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Versión</th>
                <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Total plan</th>
                <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Pagado</th>
                <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:130px;">Saldo pend.</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Cuotas pend.</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:60px;">Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $ped)
            @php
                $plan      = $ped->planPago;
                $pagado    = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
                $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#f3f4f6','cl'=>'#6b7280','lb'=>'—'];
            @endphp
            <tr wire:key="ped-{{ $ped->id }}" class="hover:bg-green-50 transition-colors" style="cursor:pointer;"
                wire:click="seleccionarPedido({{ $ped->id }})">
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">
                    {{ $ped->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb;">
                    <p style="font-weight:600; font-size:13px; color:#166534; margin:0;">{{ $ped->cliente->nombre_completo }}</p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;">CI: {{ $ped->cliente->ci ?? '—' }}</p>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <span class="pm-badge" style="background:{{ $efBadge['bg'] }}; color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <span class="pm-badge" style="background:#DCFCE7; color:#15803D;">v{{ $plan?->version ?? 1 }}</span>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-size:12px; color:#374151; font-weight:600;">
                    Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-size:12px; color:#15803D; font-weight:600;">
                    Bs. {{ number_format($pagado, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#C2410C; font-size:12px;">
                    Bs. {{ number_format($pendiente, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; font-weight:700; color:#374151;">
                    {{ $nPend }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <button wire:click.stop="seleccionarPedido({{ $ped->id }})" title="Ver cuotas"
                            class="p-1.5 rounded-lg hover:bg-green-50 transition-colors" style="color:#15803D;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-14 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">
                        {{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos con cuotas pendientes.' }}
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pedidoDetalle)
@php
    $ped       = $pedidoDetalle;
    $plan      = $ped->planPago;
    $cuotas    = $plan?->cuotas->where('numero', '>', 0)->sortBy('numero') ?? collect();
    $pagado    = $cuotas->where('estado','pagado')->sum('monto');
    $pendiente = $cuotas->where('estado','!=','pagado')->sum('monto');
    $nPend     = $cuotas->where('estado','!=','pagado')->count();
    $montoSel  = $cuotas->whereIn('id', $cuotasSeleccionadas)->sum('monto');
    $planLabel = ($plan?->version ?? 1) > 1
        ? 'Reprogramación: V' . $plan->version
        : 'Plan Original';
    $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#f3f4f6','cl'=>'#6b7280','lb'=>'Sin plan'];
    $hdrCfg    = match($plan?->estadoFinanciero ?? 'vigente') {
        'pagado'  => ['color'=>'#15803D','bg'=>'#F0FDF4','border'=>'#86EFAC'],
        'en_mora' => ['color'=>'#B91C1C','bg'=>'#FEF2F2','border'=>'#FCA5A5'],
        default   => ['color'=>'#854F0B','bg'=>'#FFFBEB','border'=>'#FCD34D'],
    };
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:60px;">

    {{-- Cabecera --}}
    <div style="background:{{ $hdrCfg['bg'] }}; border:1px solid {{ $hdrCfg['border'] }}; border-radius:14px; padding:16px 18px; margin:0 0 20px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="volver"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid {{ $hdrCfg['border'] }}; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="{{ $hdrCfg['color'] }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:{{ $hdrCfg['color'] }};">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:22px; font-weight:800; color:#166534; letter-spacing:-0.3px; margin:0;">PAGO MANUAL</h1>
            <div style="width:52px; flex-shrink:0;"></div>
        </div>
        <p style="text-align:center; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:{{ $hdrCfg['color'] }}; margin-bottom:8px;">
            {{ $efBadge['lb'] }}
        </p>
        <div style="text-align:center;">
            <span style="font-size:11px; font-weight:500; color:#6b7280;">
                Nro. Pedido: <span style="font-family:monospace; font-weight:700; color:#15803D;">{{ $ped->numero }}</span>
            </span>
        </div>
    </div>

    {{-- DATOS DEL CLIENTE --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Datos del Cliente</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-4" style="border:0.5px solid #d1fae5; border-radius:10px; padding:10px 12px;">
        <span style="font-size:9px; font-weight:600; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Cliente</span>
        <span style="font-size:13px; font-weight:600; color:#166534; display:block;">
            {{ $ped->cliente->ci ? $ped->cliente->ci . ' — ' : '' }}{{ $ped->cliente->nombre_completo }}
        </span>
    </div>

    {{-- RESUMEN --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Resumen</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px;">
        <div class="pm-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Total plan</p>
            <p style="font-size:15px; font-weight:800; color:#374151; margin:0; font-family:monospace;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</p>
        </div>
        <div class="pm-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pagado</p>
            <p style="font-size:15px; font-weight:800; color:#15803D; margin:0; font-family:monospace;">Bs. {{ number_format($pagado, 2) }}</p>
        </div>
        <div class="pm-card" style="padding:12px 14px; text-align:center; background:#FFF9F0; border-color:#FED7AA;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pendiente</p>
            <p style="font-size:15px; font-weight:800; color:#C2410C; margin:0; font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</p>
        </div>
    </div>

    {{-- PLAN DE PAGOS --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Plan de Pagos</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#f0fdf4; color:#15803D; border:1px solid #6ee7b7;">{{ $planLabel }}</span>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#DCFCE7; color:#15803D;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        <div style="padding:8px 16px; border-bottom:1px solid #f0fdf4; background:#f9fffe; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:11px; color:#9ca3af;">{{ $nPend }} pendiente{{ $nPend !== 1 ? 's' : '' }}</span>
            <span style="font-size:11px; color:#9ca3af;">Seleccionadas: <strong style="color:#15803D;">{{ count($cuotasSeleccionadas) }}</strong></span>
        </div>
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px;">
            <thead style="background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:44px;">✓</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Cuota</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5;">Monto</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5;">Vencimiento</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5;">Fecha pago</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                @php
                    $esPagada   = $c->estado === 'pagado';
                    $esSelec    = in_array($c->id, $cuotasSeleccionadas);
                    $badgeCuota = $c->estadoFinancieroBadge;
                    $diffDias   = ($c->fecha_vencimiento && $c->fecha_pago)
                        ? (int) $c->fecha_vencimiento->diffInDays($c->fecha_pago, false)
                        : null;
                @endphp
                <tr wire:key="c-{{ $c->id }}"
                    style="{{ $esPagada ? 'opacity:0.5; background:#f9fafb;' : ($esSelec ? 'background:#F0FDF4;' : '') }} {{ !$esPagada ? 'cursor:pointer;' : '' }}"
                    {{ !$esPagada ? "wire:click=toggleCuota({$c->id})" : '' }}>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        @if(!$esPagada)
                        <div style="width:18px; height:18px; border-radius:5px; border:2px solid {{ $esSelec ? '#15803D' : '#d1d5db' }};
                                    background:{{ $esSelec ? '#15803D' : '#fff' }}; display:inline-flex; align-items:center; justify-content:center;">
                            @if($esSelec)
                            <svg style="width:11px;height:11px;" fill="none" stroke="#fff" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            @endif
                        </div>
                        @endif
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:left;">
                        <div style="display:inline-flex; align-items:center; gap:5px;">
                            <span style="width:20px; height:20px; border-radius:50%; background:#DCFCE7; color:#15803D; font-size:10px; font-weight:700; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $c->numero }}</span>
                            <span style="font-size:12px; font-weight:600; color:#374151;">Cuota {{ $c->numero }}</span>
                        </div>
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#374151;">
                        Bs. {{ number_format($c->monto, 2) }}
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#6b7280;">
                        {{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        @if($c->fecha_pago)
                            <span style="font-size:12px; font-weight:600; color:#15803D; display:block;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                            @if($diffDias !== null)
                            <span style="font-size:10px; font-weight:600; color:{{ $diffDias > 0 ? '#B91C1C' : ($diffDias < 0 ? '#15803D' : '#854F0B') }};">
                                {{ $diffDias > 0 ? '+' . $diffDias . 'd mora' : ($diffDias < 0 ? abs($diffDias) . 'd antes' : 'a tiempo') }}
                            </span>
                            @endif
                        @else
                            <span style="font-size:12px; color:#9ca3af;">—</span>
                        @endif
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <span class="pm-badge" style="background:{{ $badgeCuota['bg'] }}; color:{{ $badgeCuota['cl'] }};">{{ $badgeCuota['lb'] }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Sin cuotas</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f9fffe;">
                    <td colspan="2" style="padding:9px 12px; border-top:2px solid #d1fae5; font-size:11px; color:#9ca3af; text-align:center;">
                        {{ $cuotas->where('estado','pagado')->count() }} pag. · {{ $nPend }} pend.
                    </td>
                    <td colspan="4" style="padding:9px 12px; border-top:2px solid #d1fae5; text-align:right;">
                        @if(count($cuotasSeleccionadas) > 0)
                        <span style="font-size:12px; font-weight:700; color:#15803D;">
                            Seleccionado: Bs. {{ number_format($montoSel, 2) }}
                        </span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    {{-- Acción --}}
    @if(count($cuotasSeleccionadas) > 0)
    <div class="pm-card" style="padding:14px 18px; background:#F0FDF4; border-color:#6ee7b7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div>
            <p style="font-size:13px; font-weight:700; color:#166534; margin:0;">
                {{ count($cuotasSeleccionadas) }} cuota{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }} seleccionada{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }}
            </p>
            <p style="font-size:12px; color:#6b7280; margin:2px 0 0;">
                Total a registrar: <strong style="color:#15803D; font-family:monospace;">Bs. {{ number_format($montoSel, 2) }}</strong>
            </p>
        </div>
        <button wire:click="registrarPago" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                style="background:#15803D; color:#fff; border:none; border-radius:9px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:7px;">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="registrarPago">Registrar pago</span>
            <span wire:loading wire:target="registrarPago">Registrando...</span>
        </button>
    </div>
    @else
    <div style="text-align:center; padding:14px; background:#f9fafb; border-radius:10px; color:#9ca3af; font-size:13px;">
        Seleccioná una o más cuotas pendientes para registrar el pago.
    </div>
    @endif

</div>

{{-- ══ UPLOAD ══ --}}
@elseif($mode === 'upload')
<div class="max-w-2xl mx-auto" style="padding-bottom:60px;">

    {{-- Cabecera --}}
    <div style="background:#F0FDF4; border:1px solid #86EFAC; border-radius:14px; padding:16px 18px; margin:0 0 20px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="volver"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #86EFAC; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="#15803D" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:#15803D;">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:22px; font-weight:800; color:#166534; letter-spacing:-0.3px; margin:0;">PAGOS MASIVOS</h1>
            <div style="width:60px; flex-shrink:0;"></div>
        </div>
        <p style="text-align:center; font-size:11px; color:#6b7280; margin:6px 0 0;">Subí un archivo CSV o Excel con el detalle de pagos</p>
    </div>

    {{-- FORMATO ESPERADO --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Formato del Archivo</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-4" style="border:0.5px solid #d1fae5; border-radius:10px; overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:12px;">
            <thead>
                <tr style="background:#DCFCE7;">
                    <th style="padding:8px 12px; text-align:left; font-weight:700; color:#15803D; border-bottom:1px solid #d1fae5;">transaccion</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; color:#15803D; border-bottom:1px solid #d1fae5;">fecha_de_pago</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; color:#15803D; border-bottom:1px solid #d1fae5;">numero_de_pedido</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; color:#15803D; border-bottom:1px solid #d1fae5;">numero_de_cuota</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:7px 12px; color:#374151; border-bottom:1px solid #f0f0f0;">TXN-001</td>
                    <td style="padding:7px 12px; color:#374151; border-bottom:1px solid #f0f0f0;">29/04/2026</td>
                    <td style="padding:7px 12px; font-family:monospace; color:#15803D; border-bottom:1px solid #f0f0f0;">PED-000003</td>
                    <td style="padding:7px 12px; color:#374151; border-bottom:1px solid #f0f0f0;">3</td>
                </tr>
                <tr>
                    <td style="padding:7px 12px; color:#374151;">TXN-001</td>
                    <td style="padding:7px 12px; color:#374151;">29/04/2026</td>
                    <td style="padding:7px 12px; font-family:monospace; color:#15803D;">PED-000003</td>
                    <td style="padding:7px 12px; color:#374151;">4</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- SUBIR ARCHIVO --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Archivo</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
        <span style="font-size:10px; color:#9ca3af;">CSV o Excel (.xlsx)</span>
    </div>

    <div class="bg-white overflow-hidden mb-4" style="border:0.5px solid #d1fae5; border-radius:10px; padding:20px;">
        <label style="display:block; cursor:pointer;">
            <input type="file" wire:model="archivo" accept=".csv,.xlsx,.xls" style="display:none;" id="pm-file-input">
            <div style="border:2px dashed #6ee7b7; border-radius:10px; padding:28px; text-align:center; background:#f0fdf4; transition:background 0.2s;"
                 onclick="document.getElementById('pm-file-input').click()">
                <svg style="width:32px;height:32px;margin:0 auto 8px;display:block;" fill="none" stroke="#6ee7b7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                @if($archivo)
                    <p style="font-size:13px; font-weight:700; color:#15803D; margin:0;">{{ $archivo->getClientOriginalName() }}</p>
                    <p style="font-size:11px; color:#9ca3af; margin:4px 0 0;">{{ number_format($archivo->getSize() / 1024, 1) }} KB — clic para cambiar</p>
                @else
                    <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">Seleccioná el archivo</p>
                    <p style="font-size:11px; color:#9ca3af; margin:4px 0 0;">CSV o Excel · máx. 10 MB</p>
                @endif
            </div>
        </label>
        @error('archivo')
            <p style="font-size:12px; color:#B91C1C; margin:8px 0 0; font-weight:600;">{{ $message }}</p>
        @enderror
    </div>

    @if($archivo)
    <button wire:click="procesarArchivo" wire:loading.attr="disabled" wire:loading.class="opacity-60"
            style="width:100%; background:#15803D; color:#fff; border:none; border-radius:9px; padding:12px; font-size:14px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        <span wire:loading.remove wire:target="procesarArchivo">Procesar archivo</span>
        <span wire:loading wire:target="procesarArchivo">Procesando...</span>
    </button>
    @endif

</div>

{{-- ══ UPLOAD RESULTADO ══ --}}
@elseif($mode === 'upload-resultado')
<div class="max-w-3xl mx-auto" style="padding-bottom:60px;">

    {{-- Cabecera --}}
    @php
        $totalOk  = count($resultadosOk);
        $totalErr = count($resultadosError);
        $hdrBg    = $totalErr === 0 ? '#F0FDF4' : ($totalOk === 0 ? '#FEF2F2' : '#FFFBEB');
        $hdrBo    = $totalErr === 0 ? '#86EFAC'  : ($totalOk === 0 ? '#FCA5A5'  : '#FCD34D');
        $hdrCl    = $totalErr === 0 ? '#15803D'  : ($totalOk === 0 ? '#B91C1C'  : '#854F0B');
    @endphp
    <div style="background:{{ $hdrBg }}; border:1px solid {{ $hdrBo }}; border-radius:14px; padding:16px 18px; margin:0 0 20px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="volver"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid {{ $hdrBo }}; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="{{ $hdrCl }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:{{ $hdrCl }};">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:22px; font-weight:800; color:{{ $hdrCl }}; letter-spacing:-0.3px; margin:0;">RESULTADO</h1>
            <div style="width:60px; flex-shrink:0;"></div>
        </div>
        <div style="display:flex; justify-content:center; gap:20px; margin-top:8px;">
            <span style="font-size:13px; font-weight:700; color:#15803D;">✓ {{ $totalOk }} cuota{{ $totalOk !== 1 ? 's' : '' }} aplicada{{ $totalOk !== 1 ? 's' : '' }}</span>
            @if($totalErr > 0)
            <span style="font-size:13px; font-weight:700; color:#B91C1C;">✗ {{ $totalErr }} línea{{ $totalErr !== 1 ? 's' : '' }} con error</span>
            @endif
        </div>
    </div>

    {{-- CUOTAS APLICADAS --}}
    @if($totalOk > 0)
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Cuotas Aplicadas</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#DCFCE7; color:#15803D;">{{ $totalOk }}</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:12px;">
            <thead style="background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Transacción</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Pedido</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Cuota</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:100px;">Fecha pago</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultadosOk as $r)
                <tr>
                    <td style="padding:7px 12px; border:0.5px solid #e5e7eb; font-size:11px; color:#374151; font-family:monospace;">{{ $r['transaccion'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">{{ $r['pedido'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <div style="display:inline-flex; align-items:center; gap:4px;">
                            <span style="width:18px;height:18px;border-radius:50%;background:#DCFCE7;color:#15803D;font-size:9px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;">{{ $r['cuota'] }}</span>
                            <span style="font-size:11px; font-weight:600; color:#374151;">Cuota {{ $r['cuota'] }}</span>
                        </div>
                    </td>
                    <td style="padding:7px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:11px; color:#374151;">{{ \Carbon\Carbon::parse($r['fecha'])->format('d/m/Y') }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#15803D; font-size:11px;">Bs. {{ number_format($r['monto'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

    {{-- ERRORES --}}
    @if($totalErr > 0)
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#B91C1C;">Líneas No Aplicadas</span>
        <div style="flex:1; height:1px; background:#FCA5A5;"></div>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#FEF2F2; color:#B91C1C;">{{ $totalErr }}</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:12px;">
            <thead style="background:#FEF2F2; color:#B91C1C; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FCA5A5; width:50px;">Fila</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FCA5A5;">Transacción</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FCA5A5;">Pedido</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FCA5A5; width:70px;">Cuota</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FCA5A5;">Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultadosError as $e)
                <tr style="background:#fffafa;">
                    <td style="padding:7px 12px; border:0.5px solid #fee2e2; text-align:center; font-size:11px; color:#9ca3af;">{{ $e['fila'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #fee2e2; font-family:monospace; font-size:11px; color:#374151;">{{ $e['transaccion'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #fee2e2; font-family:monospace; font-size:11px; color:#374151;">{{ $e['pedidoNum'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #fee2e2; text-align:center; font-size:11px; color:#374151;">{{ $e['cuotaNum'] }}</td>
                    <td style="padding:7px 12px; border:0.5px solid #fee2e2;">
                        <span style="font-size:11px; font-weight:600; color:#B91C1C;">{{ $e['motivo'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

</div>
@endif

</div>
</div>
