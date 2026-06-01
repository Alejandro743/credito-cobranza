<div>

<style>
.pm-btns { display:flex; flex-direction:column; gap:8px; margin-top:20px; }
@media (min-width:640px) { .pm-btns { flex-direction:row; } }
</style>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
    <div style="display:flex; gap:6px; flex-wrap:wrap;">
        <button wire:click="$set('filtro','todos')"
                style="padding:5px 12px; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap; border-radius:7px; -webkit-appearance:none; appearance:none;
                       border:1.5px solid {{ $filtro==='todos' ? '#C4B5FD' : '#E5E7EB' }};
                       background:{{ $filtro==='todos' ? '#EDE9FE' : 'transparent' }};
                       color:{{ $filtro==='todos' ? '#7B6FE8' : '#9CA3AF' }};">Todos</button>
        <button wire:click="$set('filtro','vigente')"
                style="padding:5px 12px; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap; border-radius:7px; -webkit-appearance:none; appearance:none;
                       border:1.5px solid {{ $filtro==='vigente' ? '#FCD34D' : '#E5E7EB' }};
                       background:{{ $filtro==='vigente' ? '#FEF9C3' : 'transparent' }};
                       color:{{ $filtro==='vigente' ? '#78350F' : '#9CA3AF' }};">Vigente</button>
        <button wire:click="$set('filtro','en_mora')"
                style="padding:5px 12px; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap; border-radius:7px; -webkit-appearance:none; appearance:none;
                       border:1.5px solid {{ $filtro==='en_mora' ? '#FCA5A5' : '#E5E7EB' }};
                       background:{{ $filtro==='en_mora' ? '#FEE2E2' : 'transparent' }};
                       color:{{ $filtro==='en_mora' ? '#991B1B' : '#9CA3AF' }};">En Mora</button>
        <button wire:click="$set('filtro','pagado')"
                style="padding:5px 12px; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap; border-radius:7px; -webkit-appearance:none; appearance:none;
                       border:1.5px solid {{ $filtro==='pagado' ? '#6ee7b7' : '#E5E7EB' }};
                       background:{{ $filtro==='pagado' ? '#D1FAE5' : 'transparent' }};
                       color:{{ $filtro==='pagado' ? '#065F46' : '#9CA3AF' }};">Pagado</button>
    </div>
    <button wire:click="irUpload"
            style="height:36px; padding:0 18px; display:flex; align-items:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; -webkit-appearance:none; appearance:none;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Subir Pagos
    </button>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($pedidos as $ped)
    @php
        $plan      = $ped->planPago;
        $pagado    = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
        $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
        $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
        $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#F7F7F0','cl'=>'#6D8196','lb'=>'—'];
    @endphp
    <div wire:key="ped-mob-{{ $ped->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($ped->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($ped->cliente->nombre_completo)) }}</p>
                <p style="font-size:12px; color:#9CA3AF; margin:2px 0 0;">CI: {{ $ped->cliente->ci ?: '—' }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; flex-shrink:0; background:{{ $efBadge['bg'] }}; color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
        </div>
        <div style="padding:10px 14px; display:flex; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Número</span>
                <span style="font-size:12px; font-weight:700; color:#374151; font-family:monospace;">{{ $ped->numero }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Saldo Pend.</span>
                <span style="font-size:13px; font-weight:700; color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Cuotas pend.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ $nPend }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="seleccionarPedido({{ $ped->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">{{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos con cuotas pendientes.' }}</p>
    </div>
    @endforelse
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Gestión de Pagos</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->count() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:780px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Código</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cliente</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Estado</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Versión</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Total Plan</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Pagado</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Saldo Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cuotas Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $ped)
            @php
                $plan      = $ped->planPago;
                $pagado    = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
                $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#F7F7F0','cl'=>'#6D8196','lb'=>'—'];
            @endphp
            <tr wire:key="ped-{{ $ped->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap;">{{ $loop->iteration }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $ped->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $ped->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($ped->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:{{ $efBadge['bg'] }}; color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">v{{ $plan?->version ?? 1 }}</span>
                </td>
                <td style="padding:10px 14px; text-align:right; font-family:monospace; font-size:12px; font-weight:600; white-space:nowrap;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</td>
                <td style="padding:10px 14px; text-align:right; font-family:monospace; font-size:12px; font-weight:600; color:#059669; white-space:nowrap;">Bs. {{ number_format($pagado, 2) }}</td>
                <td style="padding:10px 14px; text-align:right; font-family:monospace; font-weight:700; color:#DC2626; font-size:12px; white-space:nowrap;">Bs. {{ number_format($pendiente, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-weight:700;">{{ $nPend }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="seleccionarPedido({{ $ped->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Ver">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="ped-empty">
                <td colspan="11" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">{{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos con cuotas pendientes.' }}</p>
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
    $planLabel = ($plan?->version ?? 1) > 1 ? 'Reprogramación: V' . $plan->version : 'Plan Original';
    $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#F7F7F0','cl'=>'#6D8196','lb'=>'Sin plan'];
@endphp
<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            PAGO MANUAL
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $ped->numero }}
        </p>
        <p style="font-size:13px; color:#534AB7; margin:0 0 8px;">
            {{ $ped->cliente->ci ? $ped->cliente->ci . ' — ' : '' }}{{ $ped->cliente->nombre_completo }}
        </p>
        <span style="padding:3px 12px; border-radius:6px; font-size:13px; font-weight:700; background:{{ $efBadge['bg'] }}; color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px; margin-bottom:4px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $ped->cliente->ci ?: '—' }} — {{ ucwords(strtolower($ped->cliente->nombre_completo)) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($ped->vendedor->user->name ?? '—')) }}
            </p>
        </div>

        {{-- Separador Plan de Pagos --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Plan de Pagos</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Tabla cuotas --}}
        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:14px;">
            <div style="padding:10px 14px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; justify-content:space-between; background:#F8F7FF; flex-wrap:wrap; gap:6px;">
                <span style="font-size:12px; font-weight:700; color:#534AB7;">{{ $planLabel }}</span>
                <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                    <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
                    <span style="font-size:11px; color:#9CA3AF;">{{ $nPend }} pend. · Sel: <strong style="color:#534AB7;">{{ count($cuotasSeleccionadas) }}</strong></span>
                </div>
            </div>
            <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center; width:44px;">✓</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuota</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Vencimiento</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha pago</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $c)
                    @php
                        $esPagada  = $c->estado === 'pagado';
                        $esSelec   = in_array($c->id, $cuotasSeleccionadas);
                        $badgeCuota = $c->estadoFinancieroBadge;
                        $diffDias  = ($c->fecha_vencimiento && $c->fecha_pago)
                            ? (int) $c->fecha_vencimiento->diffInDays($c->fecha_pago, false) : null;
                        $lowerUnpaid = $cuotas->where('numero', '<', $c->numero)->where('estado', '!=', 'pagado');
                        $bloqueada   = !$esPagada && !$esSelec && !$lowerUnpaid->every(fn($x) => in_array($x->id, $cuotasSeleccionadas));
                        $clickable   = !$esPagada && !$bloqueada;
                    @endphp
                    <tr wire:key="c-{{ $c->id }}"
                        style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $esPagada ? 'opacity:0.5;background:#f9fafb;' : ($esSelec ? 'background:#F5F3FF;' : ($bloqueada ? 'opacity:0.5;' : '')) }}cursor:{{ $clickable ? 'pointer' : 'default' }};"
                        {{ $clickable ? "wire:click=toggleCuota({$c->id})" : '' }}>
                        <td style="padding:8px 12px; text-align:center;">
                            @if($esPagada)
                                {{-- pagada: sin checkbox --}}
                            @elseif($bloqueada)
                            <svg style="width:14px;height:14px;color:#CBCBCB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            @else
                            <div style="width:18px;height:18px;border-radius:3px;border:2px solid {{ $esSelec ? '#7B6FE8' : '#C4B5FD' }};background:{{ $esSelec ? '#7B6FE8' : '#fff' }};display:inline-flex;align-items:center;justify-content:center;">
                                @if($esSelec)<svg style="width:11px;height:11px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                            </div>
                            @endif
                        </td>
                        <td style="padding:8px 12px; font-size:11px; font-weight:600; color:#374151; text-align:center;">Cuota {{ $c->numero }}</td>
                        <td style="padding:8px 12px; text-align:right; font-family:monospace; font-weight:700; color:#7c3aed; font-size:13px;">Bs. {{ number_format($c->monto, 2) }}</td>
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#6b7280;">{{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                        <td style="padding:8px 12px; text-align:center;">
                            @if($c->fecha_pago)
                            <span style="font-size:11px; font-weight:600;color:#059669;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                            @if($diffDias !== null)
                            <br><span style="font-size:10px;font-weight:600;color:{{ $diffDias > 0 ? '#DC2626' : ($diffDias < 0 ? '#059669' : '#B45309') }};">
                                {{ $diffDias > 0 ? '+' . $diffDias . 'd mora' : ($diffDias < 0 ? abs($diffDias) . 'd antes' : 'a tiempo') }}
                            </span>
                            @endif
                            @else
                            <span style="font-size:11px; color:#D1D5DB;">—</span>
                            @endif
                        </td>
                        <td style="padding:8px 12px; text-align:center;">
                            <span class="ds-badge" style="background:{{ $badgeCuota['bg'] }};color:{{ $badgeCuota['cl'] }};">{{ $badgeCuota['lb'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:32px 12px; text-align:center; font-size:13px; color:#9CA3AF;">Sin cuotas</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background:#F8F7FF; border-top:1.5px solid #EDE9FE;">
                        <td colspan="2" style="padding:10px 12px; font-size:11px; color:#9CA3AF; text-align:center;">{{ $cuotas->where('estado','pagado')->count() }} pag. · {{ $nPend }} pend.</td>
                        <td colspan="4" style="padding:10px 12px; text-align:right;">
                            @if(count($cuotasSeleccionadas) > 0)
                            <span style="font-weight:700; color:#534AB7; font-family:monospace;">Seleccionado: Bs. {{ number_format($montoSel, 2) }}</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        @if(count($cuotasSeleccionadas) > 0)
        <div style="background:#F5F3FF; border:1px solid #C4B5FD; border-radius:10px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:4px;">
            <div>
                <p style="font-size:13px; font-weight:700; color:#534AB7; margin:0;">{{ count($cuotasSeleccionadas) }} cuota{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }} seleccionada{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }}</p>
                <p style="font-size:12px; color:#7B6FE8; margin:2px 0 0;">Total a registrar: <strong style="font-family:monospace;">Bs. {{ number_format($montoSel, 2) }}</strong></p>
            </div>
        </div>
        @else
        <div style="text-align:center; padding:14px; background:#F9F8FF; border-radius:8px; color:#9CA3AF; font-size:13px; border:1px solid #EDE9FE; margin-bottom:4px;">
            Seleccioná una o más cuotas pendientes para registrar el pago.
        </div>
        @endif

        @if($confirmandoAnulacion)
        <div style="background:#FFF0F0; border:1px solid #FCA5A5; border-radius:10px; padding:14px 18px; margin-top:14px;">
            <p style="font-size:13px; font-weight:700; color:#DC2626; margin:0 0 6px;">Confirmar anulación</p>
            <p style="font-size:12px; color:#6b7280; margin:0 0 12px;">Esta acción revertirá las cuotas a estado pendiente.</p>
            <div style="display:flex; gap:8px;">
                <button wire:click="$set('confirmandoAnulacion', false)"
                        style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:13px; font-weight:700; border-radius:8px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
                <button wire:click="confirmarAnulacion" wire:loading.attr="disabled"
                        style="flex:1; padding:10px; background:#DC2626; color:#fff; font-size:13px; font-weight:700; border-radius:8px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <span wire:loading.remove wire:target="confirmarAnulacion">Sí, anular</span>
                    <span wire:loading wire:target="confirmarAnulacion">Anulando...</span>
                </button>
            </div>
        </div>
        @endif

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="pm-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        @if(count($cuotasSeleccionadas) > 0)
        <button wire:click="registrarPago" wire:loading.attr="disabled"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="registrarPago">Registrar pago</span>
            <span wire:loading wire:target="registrarPago">Registrando...</span>
        </button>
        @endif
    </div>

</div>

{{-- ══ UPLOAD ══ --}}
@elseif($mode === 'upload')
<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 8px;">
            PAGOS MASIVOS
        </h1>
        <p style="font-size:13px; color:#7B6FE8; margin:0;">Subí un archivo CSV o Excel con el detalle de pagos</p>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Formato --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Formato del Archivo</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:20px;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">transaccion</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">fecha_de_pago</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">numero_de_pedido</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">numero_de_cuota</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:0.5px solid #e5e7eb;">
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">TXN-001</td>
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">29/04/2026</td>
                        <td style="padding:8px 12px; font-size:11px; font-family:monospace; color:#374151; text-align:center;">PED-000003</td>
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">3</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">TXN-001</td>
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">29/04/2026</td>
                        <td style="padding:8px 12px; font-size:11px; font-family:monospace; color:#374151; text-align:center;">PED-000003</td>
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">4</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Separador Archivo --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Seleccioná el archivo <span style="font-weight:400;">(CSV o Excel, máx. 10 MB)</span></span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; padding:20px; margin-bottom:14px;">
            <label style="display:block;cursor:pointer;">
                <input type="file" wire:model="archivo" accept=".csv,.xlsx,.xls" style="display:none;" id="pm-file-input">
                <div style="border:2px dashed #C4B5FD; border-radius:8px; padding:28px; text-align:center; background:#F9F8FF;"
                     onclick="document.getElementById('pm-file-input').click()">
                    <svg style="width:32px;height:32px;margin:0 auto 8px;display:block;" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    @if($archivo)
                    <p style="font-size:13px;font-weight:700;color:#534AB7;margin:0;">{{ $archivo->getClientOriginalName() }}</p>
                    <p style="font-size:11px;color:#9CA3AF;margin:4px 0 0;">{{ number_format($archivo->getSize() / 1024, 1) }} KB — clic para cambiar</p>
                    @else
                    <p style="font-size:13px;font-weight:600;color:#534AB7;margin:0;">Seleccioná el archivo</p>
                    <p style="font-size:11px;color:#9CA3AF;margin:4px 0 0;">CSV o Excel · máx. 10 MB</p>
                    @endif
                </div>
            </label>
            @error('archivo')<p class="ds-form-error">{{ $message }}</p>@enderror
        </div>

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="pm-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        @if($archivo)
        <button wire:click="procesarArchivo" wire:loading.attr="disabled"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span wire:loading.remove wire:target="procesarArchivo">Procesar archivo</span>
            <span wire:loading wire:target="procesarArchivo">Procesando...</span>
        </button>
        @endif
    </div>

</div>

{{-- ══ UPLOAD RESULTADO ══ --}}
@elseif($mode === 'upload-resultado')
@php
    $totalOk  = count($resultadosOk);
    $totalErr = count($resultadosError);
@endphp
<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 8px;">
            RESULTADO DE LA CARGA
        </h1>
        <div style="display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
            <span style="font-size:13px; font-weight:700; color:#059669;">✓ {{ $totalOk }} cuota{{ $totalOk !== 1 ? 's' : '' }} aplicada{{ $totalOk !== 1 ? 's' : '' }}</span>
            @if($totalErr > 0)
            <span style="font-size:13px; font-weight:700; color:#DC2626;">✗ {{ $totalErr }} línea{{ $totalErr !== 1 ? 's' : '' }} con error</span>
            @endif
        </div>
    </div>

    <div style="padding:12px 0 16px;">

        @if($totalOk > 0)
        {{-- Separador Cuotas Aplicadas --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Cuotas Aplicadas</span>
            <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#D1FAE5; color:#059669;">{{ $totalOk }}</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:20px;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:left;">Transacción</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:left;">Pedido</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuota</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha pago</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultadosOk as $r)
                    <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
                        <td style="padding:8px 12px; font-family:monospace; font-size:11px; color:#374151;">{{ $r['transaccion'] }}</td>
                        <td style="padding:8px 12px; font-family:monospace; font-size:11px; font-weight:700; color:#7B6FE8;">{{ $r['pedido'] }}</td>
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#374151;">Cuota {{ $r['cuota'] }}</td>
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#6b7280;">{{ \Carbon\Carbon::parse($r['fecha'])->format('d/m/Y') }}</td>
                        <td style="padding:8px 12px; text-align:right; font-family:monospace; font-weight:700; color:#059669;">Bs. {{ number_format($r['monto'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($totalErr > 0)
        {{-- Separador Errores --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#DC2626; letter-spacing:0.05em;">Líneas No Aplicadas</span>
            <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#FEE2E2; color:#DC2626;">{{ $totalErr }}</span>
            <div style="flex:1; height:1.5px; background:#FCA5A5;"></div>
        </div>

        <div style="background:#fff; border:1px solid #FCA5A5; border-radius:10px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#FFF0F0;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#DC2626; text-align:center; width:50px;">Fila</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#DC2626; text-align:left;">Transacción</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#DC2626; text-align:left;">Pedido</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#DC2626; text-align:center;">Cuota</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#DC2626; text-align:left;">Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultadosError as $e)
                    <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #FCA5A5;' : '' }}">
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#9CA3AF;">{{ $e['fila'] }}</td>
                        <td style="padding:8px 12px; font-family:monospace; font-size:11px; color:#374151;">{{ $e['transaccion'] }}</td>
                        <td style="padding:8px 12px; font-family:monospace; font-size:11px; color:#374151;">{{ $e['pedidoNum'] }}</td>
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#374151;">{{ $e['cuotaNum'] }}</td>
                        <td style="padding:8px 12px; font-weight:600; color:#DC2626; font-size:11px;">{{ $e['motivo'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="pm-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
    </div>

</div>
@endif

</div>
