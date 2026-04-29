<div>
<style>
.rp-badge    { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
.rp-pill-back{ background:#fff; border:1.5px solid #6ee7b7; border-radius:20px; padding:5px 14px 5px 10px; font-size:12px; font-weight:600; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:5px; }
.rp-card     { background:#fff; border-radius:14px; border:1px solid #d1fae5; box-shadow:0 1px 4px rgba(0,0,0,0.05); overflow:hidden; }
.rp-filter   { padding:5px 14px; border-radius:20px; font-size:11px; font-weight:600; cursor:pointer; border:1.5px solid transparent; transition:all 0.15s; }
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
    <h1 class="font-bold text-base flex-1" style="color:#15803D;">Historial de Reprogramaciones</h1>
    <span class="text-sm font-medium" style="color:#15803D;">{{ now()->format('d/m/Y') }}</span>
</div>

<div class="p-4 sm:p-6">

{{-- ══ LIST ══ --}}
@if($mode === 'list')
@php $theadStyle = 'background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

{{-- Toolbar --}}
<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">

    {{-- Search --}}
    <div style="position:relative; flex-shrink:0; width:220px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, cliente o pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #a7f3d0; border-radius:8px;
                      background:#f0fdf4; font-size:12px; outline:none;" />
    </div>

    {{-- Filtros --}}
    <div style="display:flex; gap:6px;">
        <button wire:click="$set('filtro','todos')"
                class="rp-filter"
                style="{{ $filtro === 'todos' ? 'background:#DCFCE7; border-color:#6ee7b7; color:#15803D;' : 'background:#fff; border-color:#e5e7eb; color:#6b7280;' }}">
            Todos
        </button>
        <button wire:click="$set('filtro','activo')"
                class="rp-filter"
                style="{{ $filtro === 'activo' ? 'background:#DCFCE7; border-color:#6ee7b7; color:#15803D;' : 'background:#fff; border-color:#e5e7eb; color:#6b7280;' }}">
            Plan activo
        </button>
        <button wire:click="$set('filtro','inactivo')"
                class="rp-filter"
                style="{{ $filtro === 'inactivo' ? 'background:#FEF2F2; border-color:#fca5a5; color:#B91C1C;' : 'background:#fff; border-color:#e5e7eb; color:#6b7280;' }}">
            Plan inactivo
        </button>
    </div>

    {{-- Nueva Reprogramación --}}
    <div style="margin-left:auto;">
        <a href="{{ route('credito.reprogramacion.nueva') }}"
           style="background:#15803D; color:#fff; border:none; border-radius:8px; padding:8px 16px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Reprogramación
        </a>
    </div>
</div>

{{-- Grilla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px; min-width:700px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:140px;">Código</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Pedido</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Cliente</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Versión</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:100px;">Fecha</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Saldo reprog.</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:90px;">Plan</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:60px;">Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reprogramaciones as $rp)
            @php $esActivo = $rp->planNuevo?->estado === 'activo'; @endphp
            <tr wire:key="rp-{{ $rp->id }}" class="hover:bg-green-50 transition-colors">
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#15803D; font-weight:700;">
                    {{ $rp->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#374151; font-weight:600;">
                    {{ $rp->pedido->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb;">
                    <p style="font-weight:600; font-size:13px; color:#166534; margin:0;">{{ $rp->pedido->cliente->nombre_completo }}</p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;">CI: {{ $rp->pedido->cliente->ci ?? '—' }}</p>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                        <span class="rp-badge" style="background:#f3f4f6; color:#374151;">v{{ $rp->version_anterior }}</span>
                        <svg style="width:11px;height:11px; color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $rp->version_nueva }}</span>
                    </div>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#374151;">
                    {{ $rp->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-family:monospace; font-weight:700; color:#C2410C; font-size:12px;">
                    Bs. {{ number_format($rp->saldo_reprogramado, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <span class="rp-badge" style="background:{{ $esActivo ? '#DCFCE7' : '#FEF2F2' }}; color:{{ $esActivo ? '#15803D' : '#B91C1C' }};">
                        {{ $esActivo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <button wire:click="verDetalle({{ $rp->id }})" title="Ver plan de pago"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Sin reprogramaciones registradas</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($reprogramaciones->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $reprogramaciones->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $reprogramacionDetalle)
@php
    $rp        = $reprogramacionDetalle;
    $p         = $rp->pedido;
    $planNuevo = $rp->planNuevo;
    $cuotas    = $planNuevo?->cuotas->where('numero', '>', 0)->sortBy('numero') ?? collect();
    $pagado    = $cuotas->where('estado','pagado')->sum('monto');
    $pendiente = $cuotas->where('estado','!=','pagado')->sum('monto');
    $esActivo  = $planNuevo?->estado === 'activo';
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
        <button wire:click="volver" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Historial
        </button>
        <div style="display:flex; gap:8px; align-items:center;">
            @if($esActivo)
            <button wire:click="editarPlan"
                    style="background:#EFF6FF; color:#1D4ED8; border:1.5px solid #BFDBFE; border-radius:8px; padding:7px 14px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px;">
                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar plan
            </button>
            @endif
            <a href="{{ route('credito.reprogramacion.nueva') }}"
               style="background:#15803D; color:#fff; border:none; border-radius:8px; padding:7px 14px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px; text-decoration:none;">
                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Reprogramación
            </a>
        </div>
    </div>

    {{-- Header --}}
    <div class="rp-card mb-4">
        <div style="padding:13px 18px; background:#DCFCE7; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-family:monospace; font-size:13px; font-weight:800; color:#15803D; margin:0;">{{ $rp->numero }}</p>
                <p style="font-size:14px; font-weight:700; color:#166534; margin:2px 0 0;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">CI: {{ $p->cliente->ci ?? '—' }} · Pedido: <span style="font-family:monospace; font-weight:600;">{{ $p->numero }}</span></p>
            </div>
            <div style="text-align:right;">
                <div style="display:flex; align-items:center; gap:5px; justify-content:flex-end; margin-bottom:4px;">
                    <span class="rp-badge" style="background:#f3f4f6; color:#374151;">v{{ $rp->version_anterior }}</span>
                    <svg style="width:11px;height:11px; color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    <span class="rp-badge" style="background:#DCFCE7; color:#15803D;">v{{ $rp->version_nueva }}</span>
                    <span class="rp-badge" style="background:{{ $esActivo ? '#EFF6FF' : '#FEF2F2' }}; color:{{ $esActivo ? '#1D4ED8' : '#B91C1C' }};">
                        {{ $esActivo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <p style="font-size:11px; color:#6b7280; margin:0;">{{ $rp->created_at->format('d/m/Y H:i') }} · {{ $rp->creadoPor->name ?? '—' }}</p>
            </div>
        </div>
        <div style="padding:10px 18px; background:#f9fffe; border-top:1px solid #f0fdf4; display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
            <div style="flex:1;">
                <p style="font-size:10px; color:#9ca3af; margin:0 0 2px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Motivo</p>
                <p style="font-size:12px; color:#374151; margin:0; line-height:1.5;">{{ $rp->motivo }}</p>
            </div>
            <div style="text-align:right; flex-shrink:0;">
                <p style="font-size:10px; color:#9ca3af; margin:0 0 2px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Saldo reprog.</p>
                <p style="font-size:14px; font-weight:800; color:#C2410C; font-family:monospace; margin:0;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Resumen --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px;">
        <div class="rp-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Total plan</p>
            <p style="font-size:15px; font-weight:800; color:#374151; margin:0; font-family:monospace;">Bs. {{ number_format($planNuevo?->total_pagar ?? 0, 2) }}</p>
        </div>
        <div class="rp-card" style="padding:12px 14px; text-align:center;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pagado</p>
            <p style="font-size:15px; font-weight:800; color:#15803D; margin:0; font-family:monospace;">Bs. {{ number_format($pagado, 2) }}</p>
        </div>
        <div class="rp-card" style="padding:12px 14px; text-align:center; background:#FFF9F0; border-color:#FED7AA;">
            <p style="font-size:10px; color:#9ca3af; margin:0 0 4px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">Pendiente</p>
            <p style="font-size:15px; font-weight:800; color:#C2410C; margin:0; font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</p>
        </div>
    </div>

    {{-- Plan de pago --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div style="padding:11px 16px; border-bottom:1px solid #f0fdf4; background:#f9fffe;">
            <span style="font-size:13px; font-weight:700; color:#166534;">Plan de pago · v{{ $rp->version_nueva }}</span>
            <span style="font-size:11px; color:#9ca3af; margin-left:8px;">{{ $planNuevo?->matriz_nombre }}</span>
            @if($planNuevo)
            @php $efBadge = $planNuevo->estadoFinancieroBadge; @endphp
            <span class="rp-badge" style="background:{{ $efBadge['bg'] }}; color:{{ $efBadge['cl'] }}; margin-left:6px;">{{ $efBadge['lb'] }}</span>
            @endif
        </div>
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px;">
            <thead style="background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:50px;">#</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5;">Monto</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5;">Vencimiento</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                @php
                    $badge = $c->estadoFinancieroBadge;
                @endphp
                <tr style="{{ $c->estado==='pagado' ? 'opacity:0.55;' : '' }}">
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-weight:700; color:#374151;">{{ $c->numero }}</td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#374151;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#6b7280;">
                        {{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <span class="rp-badge" style="background:{{ $badge['bg'] }}; color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Sin cuotas</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f9fffe;">
                    <td colspan="2" style="padding:9px 12px; border-top:2px solid #d1fae5; font-size:12px; font-weight:700; color:#374151; text-align:right;">
                        Total: <span style="font-family:monospace; color:#15803D; margin-left:4px;">Bs. {{ number_format($planNuevo?->total_pagar ?? 0, 2) }}</span>
                    </td>
                    <td colspan="2" style="padding:9px 12px; border-top:2px solid #d1fae5; font-size:11px; color:#9ca3af; text-align:center;">
                        {{ $cuotas->where('estado','pagado')->count() }} pagadas · {{ $cuotas->where('estado','!=','pagado')->count() }} pendientes
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

</div>

{{-- ══ EDITAR ══ --}}
@elseif($mode === 'editar' && $reprogramacionDetalle)
@php
    $rp        = $reprogramacionDetalle;
    $p         = $rp->pedido;
    $planNuevo = $rp->planNuevo;
    $pagado    = $planNuevo?->cuotas->where('numero','>',0)->where('estado','pagado')->sum('monto') ?? 0;
    $pendActual= $planNuevo?->cuotas->where('numero','>',0)->where('estado','!=','pagado')->sum('monto') ?? 0;
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:60px;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
        <button wire:click="volver" class="rp-pill-back">
            <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Ver detalle
        </button>
        <span style="font-size:13px; color:#6b7280;">Editar cuotas del plan</span>
    </div>

    {{-- Header --}}
    <div class="rp-card mb-4">
        <div style="padding:12px 18px; background:#EFF6FF; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <div>
                <p style="font-size:11px; color:#93C5FD; margin:0; font-weight:600;">{{ $rp->numero }} — {{ $p->numero }}</p>
                <p style="font-size:14px; font-weight:700; color:#1D4ED8; margin:0;">{{ $p->cliente->nombre_completo }}</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:10px; color:#93C5FD; margin:0 0 2px; font-weight:600; text-transform:uppercase;">Saldo pendiente actual</p>
                <p style="font-size:15px; font-weight:800; color:#C2410C; margin:0; font-family:monospace;">Bs. {{ number_format($pendActual, 2) }}</p>
                <p style="font-size:10px; color:#6b7280; margin:2px 0 0;">Pagado: Bs. {{ number_format($pagado, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Tabla editable con cuadre Alpine --}}
    <div class="rp-card mb-4"
         x-data="{
            saldo: {{ $pendActual }},
            recalc() {
                let inputs = this.$el.querySelectorAll('.monto-edit');
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
            <span style="font-size:13px; font-weight:700; color:#166534;">Cuotas · v{{ $rp->version_nueva }}</span>
            <button wire:click="agregarCuotaEdicion" @click="$nextTick(()=>recalc())"
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
                    <th class="rp-th" style="text-align:center; width:90px;">Estado</th>
                    <th class="rp-th" style="width:36px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuotasEditadas as $i => $ce)
                <tr wire:key="ce-{{ $i }}" style="{{ $ce['pagado'] ? 'opacity:0.5; background:#f9fafb;' : '' }}">
                    <td class="rp-td" style="text-align:center; font-weight:700; color:#6b7280;">{{ $ce['numero'] }}</td>
                    <td class="rp-td">
                        @if($ce['pagado'])
                            <span style="font-family:monospace; font-weight:700; color:#374151;">Bs. {{ number_format((float)$ce['monto'], 2) }}</span>
                        @else
                            <input wire:model="cuotasEditadas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                                   class="monto-edit"
                                   @input="recalc()"
                                   style="width:100%; border:1px solid #d1fae5; border-radius:6px; padding:5px 8px; font-size:12px; font-family:monospace; outline:none; background:#f9fffe;"
                                   onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#d1fae5'"/>
                            @error("cuotasEditadas.{$i}.monto")<p style="color:#dc2626; font-size:10px; margin:2px 0 0;">{{ $message }}</p>@enderror
                        @endif
                    </td>
                    <td class="rp-td">
                        @if($ce['pagado'])
                            <span style="font-size:12px; color:#6b7280;">{{ $ce['fecha'] ? \Carbon\Carbon::parse($ce['fecha'])->format('d/m/Y') : '—' }}</span>
                        @else
                            <input wire:model="cuotasEditadas.{{ $i }}.fecha" type="date"
                                   style="width:100%; border:1px solid #d1fae5; border-radius:6px; padding:5px 8px; font-size:12px; outline:none; background:#f9fffe;"
                                   onfocus="this.style.borderColor='#6ee7b7'" onblur="this.style.borderColor='#d1fae5'"/>
                            @error("cuotasEditadas.{$i}.fecha")<p style="color:#dc2626; font-size:10px; margin:2px 0 0;">{{ $message }}</p>@enderror
                        @endif
                    </td>
                    <td class="rp-td" style="text-align:center;">
                        <span class="rp-badge" style="background:{{ $ce['pagado'] ? '#DCFCE7' : '#FEF3C7' }}; color:{{ $ce['pagado'] ? '#15803D' : '#854F0B' }};">
                            {{ $ce['pagado'] ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </td>
                    <td class="rp-td" style="text-align:center;">
                        @if(!$ce['pagado'])
                        <button wire:click="quitarCuotaEdicion({{ $i }})" @click="$nextTick(()=>recalc())"
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
                        Total pendiente:
                        <span x-ref="totalDisplay" style="font-size:14px; color:#15803D; font-family:monospace; margin-left:6px;"></span>
                    </td>
                    <td colspan="3" style="padding:10px 12px; text-align:right;">
                        <span x-ref="diffDisplay" style="font-size:11px; font-weight:700;"></span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button wire:click="volver"
                style="background:#fff; color:#374151; border:1.5px solid #e5e7eb; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:500; cursor:pointer;">
            Cancelar
        </button>
        <button wire:click="guardarEdicion" wire:loading.attr="disabled" wire:loading.class="opacity-60"
                style="background:#1D4ED8; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="guardarEdicion">Guardar cambios</span>
            <span wire:loading wire:target="guardarEdicion">Guardando...</span>
        </button>
    </div>

</div>
@endif

</div>
</div>
