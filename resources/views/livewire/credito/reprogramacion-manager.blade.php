<div>
<style>
.rp-badge  { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
.rp-card   { background:#fff; border-radius:14px; border:1px solid #d1fae5; box-shadow:0 1px 4px rgba(0,0,0,0.05); overflow:hidden; }
.rp-input  { width:100%; border:1px solid #a7f3d0; border-radius:6px; padding:6px 9px; font-size:12px; outline:none; background:#f0fdf4; }
.rp-input:focus { border-color:#6ee7b7; box-shadow:0 0 0 2px rgba(110,231,183,0.2); }
.rp-btn-green  { background:#15803D; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.rp-btn-green:hover { background:#166534; }
.rp-btn-outline{ background:#fff; color:#15803D; border:1.5px solid #6ee7b7; border-radius:8px; padding:8px 16px; font-size:13px; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.rp-pill-back  { background:#fff; border:1.5px solid #6ee7b7; border-radius:20px; padding:5px 14px 5px 10px; font-size:12px; font-weight:600; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:5px; }
.rp-th { padding:8px 12px; color:#9ca3af; font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:0.4px; border-bottom:1px solid #f0fdf4; background:#f9fffe; }
.rp-td { padding:9px 12px; font-size:12px; border-bottom:1px solid #f9fafb; color:#374151; }
[x-cloak] { display:none !important; }
</style>

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center gap-3" style="background:#DCFCE7;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg flex-shrink-0"
            style="background:rgba(21,128,61,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="#15803D" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#15803D;">Reprogramación de Planes</h1>
    <span class="text-sm font-medium" style="color:#15803D;">{{ now()->format('d/m/Y') }}</span>
</div>

@if (session('success'))
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

{{-- ══════════════════════ HOME ══════════════════════ --}}
@if($mode === 'home')
<div class="max-w-xl mx-auto" style="padding-top:16px;">

    <p style="text-align:center; font-size:12px; color:#6b7280; margin-bottom:24px;">
        Seleccioná una acción para comenzar
    </p>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        {{-- Nueva Reprogramación --}}
        <button wire:click="irNueva"
                style="background:#fff; border:1.5px solid #d1fae5; border-radius:16px; padding:28px 20px; text-align:center; cursor:pointer; transition:all 0.15s; box-shadow:0 1px 4px rgba(0,0,0,0.05);"
                onmouseover="this.style.borderColor='#6ee7b7'; this.style.boxShadow='0 4px 16px rgba(21,128,61,0.12)'"
                onmouseout="this.style.borderColor='#d1fae5'; this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'">
            <div style="width:52px; height:52px; border-radius:14px; background:#DCFCE7; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                <svg style="width:26px;height:26px;" fill="none" stroke="#15803D" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <p style="font-size:14px; font-weight:700; color:#166534; margin:0 0 4px;">Nueva Reprogramación</p>
            <p style="font-size:11px; color:#6b7280; margin:0; line-height:1.4;">Reprogramá el saldo pendiente de un plan activo</p>
        </button>

        {{-- Historial --}}
        <button wire:click="irHistorial"
                style="background:#fff; border:1.5px solid #dbeafe; border-radius:16px; padding:28px 20px; text-align:center; cursor:pointer; transition:all 0.15s; box-shadow:0 1px 4px rgba(0,0,0,0.05);"
                onmouseover="this.style.borderColor='#93c5fd'; this.style.boxShadow='0 4px 16px rgba(29,78,216,0.10)'"
                onmouseout="this.style.borderColor='#dbeafe'; this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'">
            <div style="width:52px; height:52px; border-radius:14px; background:#EFF6FF; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                <svg style="width:26px;height:26px;" fill="none" stroke="#1D4ED8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p style="font-size:14px; font-weight:700; color:#1e40af; margin:0 0 4px;">Historial</p>
            <p style="font-size:11px; color:#6b7280; margin:0; line-height:1.4;">Consultá el historial de planes reprogramados</p>
        </button>

    </div>
</div>

{{-- ══════════════════════ NUEVA: BUSCAR ══════════════════════ --}}
@elseif($mode === 'nueva_buscar')
<div class="max-w-2xl mx-auto">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
        <button wire:click="backHome" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Inicio
        </button>
        <h2 style="font-size:15px; font-weight:700; color:#166534; margin:0;">Nueva Reprogramación</h2>
    </div>

    <div class="rp-card mb-5">
        <div style="padding:14px 18px;">
            <p style="font-size:11px; color:#6b7280; margin:0 0 8px;">Buscá por CI, nombre o número de pedido</p>
            <div style="position:relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px;" fill="none" stroke="#6ee7b7" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="CI, nombre o Nº pedido..." class="rp-input" style="padding-left:32px;" />
            </div>
        </div>
    </div>

    @if(strlen(trim($search)) >= 2)
        @if($resultados->isEmpty())
        <div style="text-align:center; padding:48px 20px; color:#9ca3af;">
            <svg class="w-12 h-12 mx-auto mb-3" style="color:#d1fae5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-semibold" style="color:#6b7280;">Sin resultados</p>
            <p style="font-size:12px; margin-top:4px;">No hay pedidos aprobados con plan activo para esa búsqueda.</p>
        </div>
        @else
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($resultados as $p)
            @php
                $plan      = $p->planPago;
                $pagadas   = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
            @endphp
            <div class="rp-card" style="cursor:pointer;" wire:click="seleccionarPedido({{ $p->id }})"
                 onmouseover="this.style.boxShadow='0 4px 16px rgba(21,128,61,0.12)'"
                 onmouseout="this.style.boxShadow=''">
                <div style="padding:14px 18px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                            <span style="font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">{{ $p->numero }}</span>
                            @if($plan)<span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $plan->version }}</span>@endif
                        </div>
                        <p style="font-size:14px; font-weight:700; color:#166534; margin:0 0 2px;">{{ $p->cliente->nombre_completo }}</p>
                        <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <p style="font-size:10px; color:#9ca3af; margin:0 0 2px;">Saldo pendiente</p>
                        <p style="font-size:16px; font-weight:800; color:#C2410C; margin:0;">Bs. {{ number_format($pendiente, 2) }}</p>
                        <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">{{ $nPend }} cuota{{ $nPend !== 1 ? 's' : '' }} pend.</p>
                    </div>
                </div>
                <div style="padding:7px 18px; background:#f9fffe; border-top:1px solid #f0fdf4; display:flex; justify-content:space-between;">
                    <span style="font-size:11px; color:#6b7280;">Pagado: <strong style="color:#15803D;">Bs. {{ number_format($pagadas, 2) }}</strong></span>
                    <span style="font-size:11px; color:#15803D; font-weight:600; display:flex; align-items:center; gap:3px;">
                        Ver plan
                        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @elseif(strlen(trim($search)) > 0)
        <p style="font-size:11px; color:#9ca3af; margin-top:6px; text-align:center;">Ingresá al menos 2 caracteres.</p>
    @endif
</div>

{{-- ══════════════════════ NUEVA: PREVIEW ══════════════════════ --}}
@elseif($mode === 'nueva_preview' && $pedidoDetalle)
@php
    $p        = $pedidoDetalle;
    $plan     = $p->planPago;
    $cuotas   = $plan?->cuotas ?? collect();
    $pagadas  = $cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto');
    $pendiente= $cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto');
    $nPend    = $cuotas->where('estado','!=','pagado')->where('numero','>',0)->count();
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="irNueva" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Búsqueda
        </button>
        <span style="font-size:13px; color:#6b7280;">Plan activo del pedido</span>
    </div>

    {{-- Header pedido --}}
    <div class="rp-card mb-4">
        <div style="padding:14px 18px; background:#DCFCE7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:10px; color:#6ee7b7; margin:0; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pedido</p>
                <p style="font-size:16px; font-weight:800; color:#15803D; margin:0; font-family:monospace;">{{ $p->numero }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:14px; font-weight:700; color:#166534; margin:0;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}
                    @if($p->vendedor) · {{ $p->vendedor->user->name ?? '' }}@endif
                </p>
            </div>
        </div>
    </div>

    {{-- Resumen financiero --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px;">
        <div class="rp-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Total plan</p>
            <p style="font-size:15px; font-weight:800; color:#374151; margin:0; font-family:monospace;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</p>
        </div>
        <div class="rp-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pagado</p>
            <p style="font-size:15px; font-weight:800; color:#15803D; margin:0; font-family:monospace;">Bs. {{ number_format($pagadas, 2) }}</p>
        </div>
        <div class="rp-card" style="padding:12px 14px; text-align:center; background:#FFF9F0; border-color:#FED7AA;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pendiente</p>
            <p style="font-size:15px; font-weight:800; color:#C2410C; margin:0; font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</p>
        </div>
    </div>

    {{-- Cuotas del plan activo --}}
    <div class="rp-card mb-4">
        <div style="padding:11px 16px; border-bottom:1px solid #f0fdf4; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:13px; font-weight:700; color:#166534;">Plan Activo</span>
                <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $plan?->version ?? 1 }}</span>
            </div>
            <span style="font-size:11px; color:#9ca3af;">{{ $plan?->matriz_nombre }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="rp-th" style="width:44px; text-align:center;">#</th>
                    <th class="rp-th" style="text-align:right;">Monto</th>
                    <th class="rp-th" style="text-align:center;">Vencimiento</th>
                    <th class="rp-th" style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas->where('numero','>',0)->sortBy('numero') as $c)
                @php
                    $badge = match($c->estado) {
                        'pagado'  => ['bg'=>'#DCFCE7','cl'=>'#15803D','lb'=>'Pagado'],
                        'vencido' => ['bg'=>'#FEF2F2','cl'=>'#B91C1C','lb'=>'Vencido'],
                        default   => ['bg'=>'#FEF3C7','cl'=>'#854F0B','lb'=>'Pendiente'],
                    };
                @endphp
                <tr style="{{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                    <td class="rp-td" style="text-align:center; font-weight:700;">{{ $c->numero }}</td>
                    <td class="rp-td" style="text-align:right; font-family:monospace; font-weight:700;">Bs. {{ number_format($c->monto,2) }}</td>
                    <td class="rp-td" style="text-align:center;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td class="rp-td" style="text-align:center;">
                        <span class="rp-badge" style="background:{{ $badge['bg'] }}; color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:20px; text-align:center; color:#9ca3af;">Sin cuotas</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Acción --}}
    @if($nPend > 0)
    <div style="display:flex; justify-content:flex-end;">
        <button wire:click="irForm" class="rp-btn-green">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Nueva Reprogramación
        </button>
    </div>
    @else
    <div style="text-align:center; padding:16px; background:#F0FDF4; border-radius:10px; color:#15803D; font-size:13px; font-weight:600;">
        Todas las cuotas están pagadas — no hay saldo a reprogramar.
    </div>
    @endif
</div>

{{-- ══════════════════════ NUEVA: FORM ══════════════════════ --}}
@elseif($mode === 'nueva_form' && $pedidoDetalle)
@php
    $p          = $pedidoDetalle;
    $plan       = $p->planPago;
    $pendiente  = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
    $totalNuevo = collect($nuevasCuotas)->sum(fn($c) => (float)$c['monto']);
    $diff       = round($totalNuevo - (float)$pendiente, 2);
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:60px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="$set('mode','nueva_preview')" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Ver plan
        </button>
        <span style="font-size:13px; color:#6b7280;">Configurar nuevo plan</span>
    </div>

    {{-- Header --}}
    <div class="rp-card mb-4">
        <div style="padding:12px 18px; background:#DCFCE7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:11px; color:#6ee7b7; margin:0; font-weight:600;">{{ $p->numero }} — {{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#C2410C; margin:2px 0 0;">Saldo a reprog.: <strong>Bs. {{ number_format($pendiente, 2) }}</strong></p>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#6b7280;">Plan v{{ $plan?->version ?? 1 }}</span>
                <span style="font-size:11px; color:#6b7280; margin:0 6px;">→</span>
                <span class="rp-badge" style="background:#EFF6FF; color:#1D4ED8; font-size:11px;">v{{ ($plan?->version ?? 1) + 1 }}</span>
            </div>
        </div>
    </div>

    {{-- Editor cuotas --}}
    <div class="rp-card mb-4">
        <div style="padding:11px 16px; border-bottom:1px solid #f0fdf4; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; font-weight:700; color:#166534;">Cuotas del nuevo plan</span>
            <button wire:click="agregarCuota"
                    style="background:#DCFCE7; border:none; border-radius:6px; padding:5px 12px; font-size:11px; font-weight:600; color:#15803D; cursor:pointer; display:flex; align-items:center; gap:4px;">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar cuota
            </button>
        </div>
        <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th class="rp-th" style="text-align:center; width:40px;">#</th>
                    <th class="rp-th">Monto (Bs.)</th>
                    <th class="rp-th">Fecha vencimiento</th>
                    <th class="rp-th" style="width:36px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($nuevasCuotas as $i => $cuota)
                <tr wire:key="nc-{{ $i }}">
                    <td class="rp-td" style="text-align:center; font-weight:700; color:#6b7280;">{{ $cuota['numero'] }}</td>
                    <td class="rp-td">
                        <input wire:model="nuevasCuotas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                               style="width:100%; border:1px solid #d1fae5; border-radius:6px; padding:5px 8px; font-size:12px; font-family:monospace; outline:none; background:#f9fffe;"
                               onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#d1fae5'"/>
                        @error("nuevasCuotas.{$i}.monto")<p style="color:#dc2626; font-size:10px; margin:2px 0 0;">{{ $message }}</p>@enderror
                    </td>
                    <td class="rp-td">
                        <input wire:model="nuevasCuotas.{{ $i }}.fecha" type="date"
                               style="width:100%; border:1px solid #d1fae5; border-radius:6px; padding:5px 8px; font-size:12px; outline:none; background:#f9fffe;"
                               onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#d1fae5'"/>
                        @error("nuevasCuotas.{$i}.fecha")<p style="color:#dc2626; font-size:10px; margin:2px 0 0;">{{ $message }}</p>@enderror
                    </td>
                    <td class="rp-td" style="text-align:center;">
                        @if(count($nuevasCuotas) > 1)
                        <button wire:click="quitarCuota({{ $i }})"
                                style="background:#FEF2F2; border:none; border-radius:5px; padding:4px 6px; cursor:pointer; color:#dc2626; display:inline-flex; align-items:center;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f9fffe; border-top:2px solid #d1fae5;">
                    <td colspan="2" style="padding:10px 12px; font-size:12px; font-weight:700; color:#374151;">
                        Total nuevo plan:
                        <span style="font-size:14px; color:#15803D; font-family:monospace; margin-left:6px;">Bs. {{ number_format($totalNuevo, 2) }}</span>
                    </td>
                    <td colspan="2" style="padding:10px 12px; text-align:right;">
                        @if(abs($diff) < 0.01)
                            <span style="font-size:11px; color:#15803D; font-weight:600;">✓ Cuadra exacto</span>
                        @elseif($diff > 0)
                            <span style="font-size:11px; color:#854F0B; font-weight:600;">+Bs. {{ number_format($diff,2) }} sobre saldo</span>
                        @else
                            <span style="font-size:11px; color:#B91C1C; font-weight:600;">−Bs. {{ number_format(abs($diff),2) }} bajo saldo</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    {{-- Motivo --}}
    <div class="rp-card mb-4" style="padding:16px 18px;">
        <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px;">
            Motivo de la reprogramación <span style="color:#dc2626;">*</span>
        </label>
        <textarea wire:model="motivo" rows="3"
                  placeholder="Ej: Cliente solicitó extensión de plazo por dificultades económicas..."
                  class="rp-input" style="resize:vertical;"></textarea>
        @error('motivo')<p style="color:#dc2626; font-size:11px; margin-top:4px;">{{ $message }}</p>@enderror
    </div>

    <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button wire:click="$set('mode','nueva_preview')" class="rp-btn-outline">Cancelar</button>
        <button wire:click="confirmar" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="rp-btn-green">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="confirmar">Confirmar Reprogramación</span>
            <span wire:loading wire:target="confirmar">Procesando...</span>
        </button>
    </div>
</div>

{{-- ══════════════════════ HIST: LIST ══════════════════════ --}}
@elseif($mode === 'hist_list')
<div class="max-w-2xl mx-auto">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
        <button wire:click="backHome" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Inicio
        </button>
        <h2 style="font-size:15px; font-weight:700; color:#166534; margin:0;">Historial de Reprogramaciones</h2>
    </div>

    {{-- Search --}}
    <div class="rp-card mb-4" style="padding:12px 16px;">
        <div style="position:relative;">
            <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px;" fill="none" stroke="#6ee7b7" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.400ms="searchHist" type="text" placeholder="CI, nombre o Nº pedido..." class="rp-input" style="padding-left:32px;"/>
        </div>
    </div>

    @if($pedidosHist->isEmpty())
    <div style="text-align:center; padding:48px 20px; color:#9ca3af;">
        <svg class="w-12 h-12 mx-auto mb-3" style="color:#dbeafe;" fill="none" stroke="#93c5fd" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-semibold" style="color:#6b7280;">Sin reprogramaciones registradas</p>
        <p style="font-size:12px; margin-top:4px;">Aún no hay planes reprogramados en el sistema.</p>
    </div>
    @else
    <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach($pedidosHist as $p)
        @php $totalPlanes = $p->planes->count(); @endphp
        <div class="rp-card" style="cursor:pointer;" wire:click="verHistorialPedido({{ $p->id }})"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(29,78,216,0.10)'"
             onmouseout="this.style.boxShadow=''">
            <div style="padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px;">
                        <span style="font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">{{ $p->numero }}</span>
                        <span class="rp-badge" style="background:#EFF6FF; color:#1D4ED8;">{{ $totalPlanes - 1 }} reprog.</span>
                    </div>
                    <p style="font-size:14px; font-weight:700; color:#166534; margin:0 0 2px;">{{ $p->cliente->nombre_completo }}</p>
                    <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
                </div>
                <svg style="width:16px;height:16px; color:#93c5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </div>
        @endforeach
    </div>
    @if($pedidosHist->hasPages())
    <div class="mt-4">{{ $pedidosHist->links() }}</div>
    @endif
    @endif
</div>

{{-- ══════════════════════ HIST: PEDIDO ══════════════════════ --}}
@elseif($mode === 'hist_pedido' && $pedidoDetalle)
@php $p = $pedidoDetalle; @endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="irHistorial" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Historial
        </button>
        <span style="font-size:13px; color:#6b7280;">Reprogramaciones del pedido</span>
    </div>

    {{-- Header pedido --}}
    <div class="rp-card mb-4">
        <div style="padding:13px 18px; background:#EFF6FF; border-bottom:1px solid #dbeafe; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:10px; color:#93c5fd; margin:0; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pedido</p>
                <p style="font-size:16px; font-weight:800; color:#1D4ED8; margin:0; font-family:monospace;">{{ $p->numero }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:14px; font-weight:700; color:#1e40af; margin:0;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
            </div>
        </div>

        {{-- Plan activo actual --}}
        @php $planActivo = $p->planPago; @endphp
        @if($planActivo)
        <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; background:#f9fffe;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:12px; font-weight:600; color:#374151;">Plan activo:</span>
                <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $planActivo->version }}</span>
                <span style="font-size:11px; color:#6b7280;">{{ $planActivo->matriz_nombre }}</span>
            </div>
            <div>
                <span style="font-size:12px; font-weight:700; color:#15803D; font-family:monospace;">Bs. {{ number_format($planActivo->total_pagar, 2) }}</span>
                <span style="font-size:11px; color:#9ca3af; margin-left:6px;">{{ $planActivo->cantidad_cuotas }} cuota{{ $planActivo->cantidad_cuotas !== 1 ? 's' : '' }}</span>
            </div>
        </div>
        @endif
    </div>

    {{-- Lista de reprogramaciones --}}
    @if($reprogramaciones->isEmpty())
    <div style="text-align:center; padding:32px 20px; color:#9ca3af;">
        <p>No hay reprogramaciones registradas para este pedido.</p>
    </div>
    @else
    <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach($reprogramaciones as $rp)
        <div class="rp-card" style="cursor:pointer;" wire:click="verDetalle({{ $rp->id }})"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(29,78,216,0.10)'"
             onmouseout="this.style.boxShadow=''">
            <div style="padding:13px 18px; display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                        <span class="rp-badge" style="background:#f3f4f6; color:#374151;">v{{ $rp->version_anterior }}</span>
                        <svg style="width:14px;height:14px; color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $rp->version_nueva }}</span>
                        @if($rp->version_nueva === ($planActivo?->version ?? 0))
                        <span class="rp-badge" style="background:#EFF6FF; color:#1D4ED8;">Activo</span>
                        @endif
                    </div>
                    <p style="font-size:12px; color:#374151; margin:0 0 3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:340px;">
                        <span style="color:#9ca3af;">Motivo:</span> {{ $rp->motivo }}
                    </p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;">
                        {{ $rp->created_at->format('d/m/Y H:i') }} · {{ $rp->creadoPor->name ?? '—' }}
                    </p>
                </div>
                <div style="text-align:right; flex-shrink:0;">
                    <p style="font-size:10px; color:#9ca3af; margin:0 0 2px;">Saldo reprog.</p>
                    <p style="font-size:14px; font-weight:800; color:#C2410C; margin:0; font-family:monospace;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</p>
                    <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">{{ $rp->cuotas_pagadas }} cuota{{ $rp->cuotas_pagadas !== 1 ? 's' : '' }} pagada{{ $rp->cuotas_pagadas !== 1 ? 's' : '' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══════════════════════ HIST: DETALLE ══════════════════════ --}}
@elseif($mode === 'hist_detalle' && $reprogramacionDetalle)
@php
    $rp        = $reprogramacionDetalle;
    $p         = $rp->pedido;
    $planViejo = $rp->planViejo;
    $planNuevo = $rp->planNuevo;
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="verHistorialPedido({{ $rp->pedido_id }})" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Reprogramaciones
        </button>
        <span style="font-size:13px; color:#6b7280;">Detalle de reprogramación</span>
    </div>

    {{-- Meta card --}}
    <div class="rp-card mb-4" style="background:linear-gradient(135deg,#EFF6FF,#F0FDF4);">
        <div style="padding:16px 18px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <p style="font-size:10px; color:#9ca3af; margin:0 0 2px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pedido</p>
                <p style="font-size:14px; font-weight:800; color:#374151; margin:0; font-family:monospace;">{{ $p->numero }}</p>
                <p style="font-size:12px; font-weight:600; color:#166534; margin:3px 0 0;">{{ $p->cliente->nombre_completo }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:10px; color:#9ca3af; margin:0 0 2px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Fecha</p>
                <p style="font-size:13px; font-weight:700; color:#374151; margin:0;">{{ $rp->created_at->format('d/m/Y') }}</p>
                <p style="font-size:11px; color:#6b7280; margin:2px 0 0;">{{ $rp->created_at->format('H:i') }} · {{ $rp->creadoPor->name ?? '—' }}</p>
            </div>
        </div>
        <div style="padding:10px 18px; border-top:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <span style="font-size:11px; color:#6b7280;">Versión:</span>
            <span class="rp-badge" style="background:#f3f4f6; color:#374151;">v{{ $rp->version_anterior }}</span>
            <svg style="width:14px;height:14px; color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
            <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $rp->version_nueva }}</span>
            <span style="font-size:11px; color:#C2410C; font-weight:600; margin-left:auto;">Saldo reprog.: Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</span>
        </div>
        <div style="padding:10px 18px; border-top:1px solid rgba(0,0,0,0.05);">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 3px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Motivo</p>
            <p style="font-size:12px; color:#374151; margin:0; line-height:1.5;">{{ $rp->motivo }}</p>
        </div>
    </div>

    {{-- Dos columnas: plan viejo vs plan nuevo --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">

        {{-- Plan anterior --}}
        <div class="rp-card" style="opacity:0.85;">
            <div style="padding:10px 14px; background:#f9fafb; border-bottom:1px solid #f0fdf4; display:flex; align-items:center; gap:6px;">
                <span style="font-size:12px; font-weight:700; color:#6b7280;">Plan anterior</span>
                <span class="rp-badge" style="background:#f3f4f6; color:#6b7280;">v{{ $rp->version_anterior }}</span>
                <span class="rp-badge" style="background:#FEF2F2; color:#B91C1C; margin-left:auto;">Inactivo</span>
            </div>
            <div style="padding:8px 0;">
            @if($planViejo)
                @php $cuotasViejas = $planViejo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                @foreach($cuotasViejas as $c)
                @php
                    $badge = match($c->estado) {
                        'pagado'  => ['bg'=>'#DCFCE7','cl'=>'#15803D','lb'=>'Pagado'],
                        'vencido' => ['bg'=>'#FEF2F2','cl'=>'#B91C1C','lb'=>'Vencido'],
                        default   => ['bg'=>'#FEF3C7','cl'=>'#854F0B','lb'=>'Pendiente'],
                    };
                @endphp
                <div style="padding:5px 14px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f9fafb; {{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:11px; font-weight:700; color:#6b7280; width:16px;">{{ $c->numero }}</span>
                        <span class="rp-badge" style="background:{{ $badge['bg'] }}; color:{{ $badge['cl'] }}; font-size:9px;">{{ $badge['lb'] }}</span>
                    </div>
                    <span style="font-size:11px; font-family:monospace; font-weight:700; color:#374151;">Bs. {{ number_format($c->monto,2) }}</span>
                </div>
                @endforeach
                <div style="padding:8px 14px; display:flex; justify-content:space-between; border-top:1px solid #f0fdf4; margin-top:2px;">
                    <span style="font-size:11px; color:#6b7280;">{{ $cuotasViejas->where('estado','pagado')->count() }} pagadas · {{ $cuotasViejas->where('estado','!=','pagado')->count() }} pend.</span>
                </div>
            @else
                <p style="padding:20px; text-align:center; font-size:12px; color:#9ca3af;">Sin datos</p>
            @endif
            </div>
        </div>

        {{-- Plan nuevo --}}
        <div class="rp-card">
            <div style="padding:10px 14px; background:#F0FDF4; border-bottom:1px solid #d1fae5; display:flex; align-items:center; gap:6px;">
                <span style="font-size:12px; font-weight:700; color:#166534;">Plan nuevo</span>
                <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $rp->version_nueva }}</span>
                @php $esActivo = $planNuevo?->estado === 'activo'; @endphp
                <span class="rp-badge" style="background:{{ $esActivo ? '#DCFCE7' : '#f3f4f6' }}; color:{{ $esActivo ? '#15803D' : '#6b7280' }}; margin-left:auto;">
                    {{ $esActivo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div style="padding:8px 0;">
            @if($planNuevo)
                @php $cuotasNuevas = $planNuevo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                @foreach($cuotasNuevas as $c)
                @php
                    $badge = match($c->estado) {
                        'pagado'  => ['bg'=>'#DCFCE7','cl'=>'#15803D','lb'=>'Pagado'],
                        'vencido' => ['bg'=>'#FEF2F2','cl'=>'#B91C1C','lb'=>'Vencido'],
                        default   => ['bg'=>'#FEF3C7','cl'=>'#854F0B','lb'=>'Pendiente'],
                    };
                @endphp
                <div style="padding:5px 14px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f9fafb; {{ $c->estado==='pagado' ? 'opacity:0.6;' : '' }}">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:11px; font-weight:700; color:#6b7280; width:16px;">{{ $c->numero }}</span>
                        <span class="rp-badge" style="background:{{ $badge['bg'] }}; color:{{ $badge['cl'] }}; font-size:9px;">{{ $badge['lb'] }}</span>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:11px; font-family:monospace; font-weight:700; color:#15803D;">Bs. {{ number_format($c->monto,2) }}</span>
                        @if($c->fecha_vencimiento)
                        <p style="font-size:9px; color:#9ca3af; margin:0;">{{ \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
                <div style="padding:8px 14px; border-top:1px solid #d1fae5; margin-top:2px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:11px; color:#6b7280;">{{ $cuotasNuevas->where('estado','pagado')->count() }} pagadas · {{ $cuotasNuevas->where('estado','!=','pagado')->count() }} pend.</span>
                    <span style="font-size:11px; font-weight:700; color:#15803D; font-family:monospace;">Bs. {{ number_format($planNuevo->total_pagar,2) }}</span>
                </div>
            @else
                <p style="padding:20px; text-align:center; font-size:12px; color:#9ca3af;">Sin datos</p>
            @endif
            </div>
        </div>

    </div>
</div>

@endif
</div>
</div>
