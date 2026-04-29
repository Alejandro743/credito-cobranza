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
    <h1 class="font-bold text-base flex-1" style="color:#15803D;">Nueva Reprogramación</h1>
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

{{-- ══ BUSCAR ══ --}}
@if($mode === 'buscar')
<div>
    {{-- Toolbar --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
        <div style="position:relative; flex-shrink:0; width:260px;">
            <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
                 viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
                   style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #a7f3d0; border-radius:8px;
                          background:#f0fdf4; font-size:12px; outline:none;" />
        </div>
        <span style="font-size:12px; color:#9ca3af; margin-left:4px;">{{ $resultados->count() }} pedido{{ $resultados->count() !== 1 ? 's' : '' }}</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px; min-width:780px;">
            <thead style="background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Pedido</th>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Cliente</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Versión</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Total plan</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Pagado</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:130px;">Saldo pend.</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Cuotas pend.</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:60px;">Ver</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados as $p)
                @php
                    $plan      = $p->planPago;
                    $pagadas   = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                    $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                    $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
                @endphp
                <tr wire:key="res-{{ $p->id }}" class="hover:bg-green-50 transition-colors" style="cursor:pointer;"
                    wire:click="seleccionarPedido({{ $p->id }})">
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">
                        {{ $p->numero }}
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb;">
                        <p style="font-weight:600; font-size:13px; color:#166534; margin:0;">{{ $p->cliente->nombre_completo }}</p>
                        <p style="font-size:11px; color:#9ca3af; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $plan?->version ?? 1 }}</span>
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-size:12px; color:#374151; font-weight:600;">
                        Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-size:12px; color:#15803D; font-weight:600;">
                        Bs. {{ number_format($pagadas, 2) }}
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#C2410C; font-size:12px;">
                        Bs. {{ number_format($pendiente, 2) }}
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#374151; font-weight:700;">
                        {{ $nPend }}
                    </td>
                    <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <button wire:click.stop="seleccionarPedido({{ $p->id }})" title="Ver y reprogramar"
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
                    <td colspan="8" class="px-4 py-14 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-semibold text-gray-500">
                            {{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos aprobados con saldo pendiente.' }}
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

{{-- ══ PREVIEW ══ --}}
@elseif($mode === 'preview' && $pedidoDetalle)
@php
    $p        = $pedidoDetalle;
    $plan     = $p->planPago;
    $cuotas   = $plan?->cuotas ?? collect();
    $pagadas  = $cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto');
    $pendiente= $cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto');
    $nPend    = $cuotas->where('estado','!=','pagado')->where('numero','>',0)->count();
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="volver" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Búsqueda
        </button>
        <span style="font-size:13px; color:#6b7280;">Plan activo del pedido</span>
    </div>

    <div class="rp-card mb-4">
        <div style="padding:14px 18px; background:#DCFCE7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:10px; color:#6ee7b7; margin:0; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pedido</p>
                <p style="font-size:16px; font-weight:800; color:#15803D; margin:0; font-family:monospace;">{{ $p->numero }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:14px; font-weight:700; color:#166534; margin:0;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
            </div>
        </div>
    </div>

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
                    $badge = $c->estadoFinancieroBadge;
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

{{-- ══ FORM ══ --}}
@elseif($mode === 'form' && $pedidoDetalle)
@php
    $p         = $pedidoDetalle;
    $plan      = $p->planPago;
    $pendiente = round((float)($plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0), 2);
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:60px;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="volver" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Ver plan
        </button>
        <span style="font-size:13px; color:#6b7280;">Configurar nuevo plan</span>
    </div>

    {{-- Header pedido --}}
    <div class="rp-card mb-4">
        <div style="padding:12px 18px; background:#DCFCE7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:11px; color:#6ee7b7; margin:0; font-weight:600;">{{ $p->numero }} — {{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#C2410C; margin:2px 0 0;">Saldo a reprog.: <strong>Bs. {{ number_format($pendiente, 2) }}</strong></p>
            </div>
            <div>
                <span style="font-size:11px; color:#6b7280;">v{{ $plan?->version ?? 1 }}</span>
                <span style="font-size:11px; color:#6b7280; margin:0 6px;">→</span>
                <span class="rp-badge" style="background:#EFF6FF; color:#1D4ED8; font-size:11px;">v{{ ($plan?->version ?? 1) + 1 }}</span>
            </div>
        </div>
    </div>

    {{-- Configurador --}}
    <div class="rp-card mb-4">
        <div style="padding:11px 18px; border-bottom:1px solid #f0fdf4; background:#f9fffe;">
            <span style="font-size:12px; font-weight:700; color:#166534;">Configurar plan propuesto</span>
        </div>
        <div style="padding:14px 18px; display:flex; align-items:flex-end; gap:14px; flex-wrap:wrap;">
            <div style="flex:0 0 160px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:5px;">Cantidad de cuotas</label>
                <input wire:model="cantidadCuotas" type="number" min="1" max="120" placeholder="Ej: 6"
                       style="width:100%; border:1px solid #a7f3d0; border-radius:6px; padding:7px 10px; font-size:13px; font-family:monospace; outline:none; background:#f0fdf4;"
                       onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#a7f3d0'"/>
            </div>
            <div style="flex:0 0 190px;">
                <label style="display:block; font-size:11px; font-weight:600; color:#374151; margin-bottom:5px;">Fecha 1ª cuota</label>
                <input wire:model="fechaPrimera" type="date"
                       style="width:100%; border:1px solid #a7f3d0; border-radius:6px; padding:7px 10px; font-size:13px; outline:none; background:#f0fdf4;"
                       onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#a7f3d0'"/>
            </div>
            <div>
                <button wire:click="generarPlan"
                        style="background:#15803D; color:#fff; border:none; border-radius:7px; padding:8px 16px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Generar plan
                </button>
            </div>
        </div>
    </div>

    {{-- Cuotas editables con cuadre Alpine en tiempo real --}}
    <div class="rp-card mb-4"
         x-data="{
            saldo: {{ $pendiente }},
            recalc() {
                let inputs = this.$el.querySelectorAll('.monto-cuota');
                let total = Array.from(inputs).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
                this.$refs.totalDisplay.textContent = 'Bs. ' + total.toFixed(2);
                let diff = Math.round((total - this.saldo) * 100) / 100;
                let el = this.$refs.diffDisplay;
                if (Math.abs(diff) < 0.01) {
                    el.textContent = '✓ Cuadra exacto';
                    el.style.color = '#15803D';
                } else if (diff > 0) {
                    el.textContent = '+Bs. ' + diff.toFixed(2) + ' sobre saldo';
                    el.style.color = '#854F0B';
                } else {
                    el.textContent = '−Bs. ' + Math.abs(diff).toFixed(2) + ' bajo saldo';
                    el.style.color = '#B91C1C';
                }
            }
         }"
         x-init="recalc()">
        <div style="padding:11px 16px; border-bottom:1px solid #f0fdf4; display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:13px; font-weight:700; color:#166534;">Cuotas del nuevo plan</span>
            <button wire:click="agregarCuota" @click="$nextTick(()=>recalc())"
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
                               class="monto-cuota"
                               @input="recalc()"
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
                        <button wire:click="quitarCuota({{ $i }})" @click="$nextTick(()=>recalc())"
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
                        Total:
                        <span x-ref="totalDisplay" style="font-size:14px; color:#15803D; font-family:monospace; margin-left:6px;"></span>
                    </td>
                    <td colspan="2" style="padding:10px 12px; text-align:right;">
                        <span x-ref="diffDisplay" style="font-size:11px; font-weight:700;"></span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

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
        <button wire:click="volver" class="rp-btn-outline">Cancelar</button>
        <button wire:click="confirmar" wire:loading.attr="disabled" wire:loading.class="opacity-60" class="rp-btn-green">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="confirmar">Confirmar Reprogramación</span>
            <span wire:loading wire:target="confirmar">Procesando...</span>
        </button>
    </div>
</div>
@endif

</div>
</div>
