<div>

<style>
.nr-btns, .rp-det-btns { display:flex; flex-direction:column; gap:8px; margin-top:20px; }
@media (min-width:640px) { .nr-btns, .rp-det-btns { flex-direction:row; } }
</style>

{{-- ══ BUSCAR ══ --}}
@if($mode === 'buscar')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($resultados as $p)
    @php
        $plan      = $p->planPago;
        $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
        $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
    @endphp
    <div wire:key="res-mob-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">v{{ $plan?->version ?? 1 }}</span>
        </div>
        <div style="padding:10px 14px; display:flex; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">CI</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->cliente->ci ?: '—' }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Cuotas pend.</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $nPend }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Saldo pend.</span>
                <span style="font-size:13px; font-weight:700; color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button wire:click="seleccionarPedido({{ $p->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Seleccionar
            </button>
            <button wire:click="abrirEditarPlan({{ $p->id }})" title="Editar Plan"
                    style="width:34px; height:34px; flex-shrink:0; border:1px solid #D1FAE5; border-radius:8px; background:#F0FDF4; color:#15803D; cursor:pointer; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">No hay pedidos aprobados con saldo pendiente.</p>
    </div>
    @endforelse
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Pedidos disponibles</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $resultados->count() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:780px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Código</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Versión</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Total Plan</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Pagado</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Saldo Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cuotas Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
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
            <tr wire:key="res-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s; cursor:pointer;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap; background:#F9F8FF; color:#C4B5FD; font-weight:700;">{{ $loop->iteration }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">v{{ $plan?->version ?? 1 }}</span>
                </td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; color:#111827;">{{ number_format($plan?->total_pagar ?? 0, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; color:#059669;">{{ number_format($pagadas, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; font-weight:700; color:#DC2626;">{{ number_format($pendiente, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#111827;">{{ $nPend }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <div style="display:flex; align-items:center; justify-content:center; gap:5px;">
                        <button wire:click="seleccionarPedido({{ $p->id }})"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;"
                                @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Seleccionar">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <button wire:click="abrirEditarPlan({{ $p->id }})"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #D1FAE5; background:#F0FDF4; color:#15803D; cursor:pointer; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;"
                                @mouseenter="$el.style.background='#D1FAE5'" @mouseleave="$el.style.background='#F0FDF4'" title="Editar Plan">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">No hay pedidos aprobados con saldo pendiente.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══ EDITAR PLAN ══ --}}
@elseif($mode === 'editar_plan' && $planEditando)
@php
    $p           = $pedEdit;
    $pendActualEd = $pendActual;
    $totalEditadoEd = round(collect($cuotasEditadas)->filter(fn($c) => !($c['pagado'] ?? false))->sum(fn($c) => (float)$c['monto']), 2);
    $difEditadoEd = round($totalEditadoEd - $pendActualEd, 2);
@endphp

<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            EDITAR PLAN DE PAGOS
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $p?->numero }}
        </p>
        <span style="padding:2px 10px; border-radius:6px; font-size:13px; font-weight:800; background:#fff; color:#7B6FE8;">v{{ $planEditando->version }}</span>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $p?->cliente->ci ?: '—' }} - {{ ucwords(strtolower($p?->cliente->nombre_completo ?? '')) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($p?->vendedor->user->name ?? '—')) }}
            </p>
        </div>

        {{-- Separador Editar Cuotas --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Editar Cuotas</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Tabla editable con cuadre Alpine --}}
        <div x-data="{
                saldo: {{ $pendActualEd }},
                total: {{ $totalEditadoEd }},
                diff:  {{ $difEditadoEd }},
                get diffLabel() {
                    if (Math.abs(this.diff) < 0.01) return '✓ Cuadra exacto';
                    return (this.diff > 0 ? '+' : '−') + 'Bs. ' + Math.abs(this.diff).toFixed(2);
                },
                get diffColor() {
                    if (Math.abs(this.diff) < 0.01) return '#059669';
                    return this.diff > 0 ? '#B45309' : '#DC2626';
                },
                recalc() {
                    let inputs = this.$el.querySelectorAll('.monto-edit');
                    let raw = Array.from(inputs).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
                    this.total = Math.round(raw * 100) / 100;
                    this.diff  = Math.round((this.total - this.saldo) * 100) / 100;
                }
             }"
             x-init="
                $el.addEventListener('input', (e) => { if (e.target.classList.contains('monto-edit')) recalc(); });
                $wire.$watch('cuotasEditadas', () => $nextTick(() => recalc()));
             ">

            <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:14px;">
                <div style="padding:10px 14px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; justify-content:space-between; background:#F8F7FF;">
                    <span style="font-size:12px; font-weight:700; color:#534AB7;">Cuotas · v{{ $planEditando->version }}</span>
                    <button wire:click="agregarCuotaEdicion"
                            style="display:flex; align-items:center; gap:5px; padding:5px 12px; background:#EDE9FE; color:#534AB7; font-size:12px; font-weight:700; border:1px solid #C4B5FD; border-radius:8px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Agregar cuota
                    </button>
                </div>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#F8F7FF;">
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">#</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto (Bs.)</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha vencimiento</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotasEditadas as $i => $ce)
                        <tr wire:key="ce-{{ $i }}" style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $ce['pagado'] ? 'opacity:0.5;background:#f9fafb;' : '' }}">
                            <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">{{ $ce['numero'] }}</td>
                            <td style="padding:8px 12px; font-size:11px; color:#6b7280; font-weight:600; text-align:center;">Cuota {{ $ce['numero'] }}</td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="font-family:monospace; font-weight:700; color:#374151;">{{ number_format((float)$ce['monto'], 2) }}</span>
                                @else
                                <input wire:model="cuotasEditadas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                                       class="monto-edit" style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; text-align:center; outline:none; background:#fff;">
                                @error("cuotasEditadas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="font-size:11px; color:#6b7280;">{{ $ce['fecha'] ? \Carbon\Carbon::parse($ce['fecha'])->format('d/m/Y') : '—' }}</span>
                                @else
                                <input wire:model="cuotasEditadas.{{ $i }}.fecha" type="date"
                                       style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; outline:none; background:#fff;">
                                @error("cuotasEditadas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#D1FAE5; color:#059669;">Pagado</span>
                                @else
                                <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#FEE2E2; color:#DC2626;">Pendiente</span>
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if(!$ce['pagado'])
                                <button wire:click="quitarCuotaEdicion({{ $i }})"
                                        style="width:28px; height:28px; border-radius:7px; border:1px solid #FECACA; background:#FEF2F2; color:#DC2626; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Resumen real-time --}}
            <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18);">
                <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#DC2626 100%);"></div>
                <div style="padding:14px; display:grid; grid-template-columns:repeat(3,1fr); text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Saldo a cubrir</span>
                        <span style="font-size:13px; font-weight:900; color:#DC2626; font-family:monospace;">Bs. {{ number_format($pendActualEd, 2) }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total cuotas</span>
                        <p x-text="'Bs. ' + total.toFixed(2)" style="font-size:13px; font-weight:900; color:#111827; font-family:monospace; margin:0;"></p>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Diferencia</span>
                        <p x-text="diffLabel" :style="'font-size:13px; font-weight:900; font-family:monospace; margin:0; color:' + diffColor"></p>
                    </div>
                </div>
            </div>

        </div>{{-- /x-data --}}

        @error('cuotasEditadas')
        <div style="margin-top:10px; padding:10px 14px; background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; font-size:13px; font-weight:600; color:#DC2626;">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Botones grandes --}}
    <div class="nr-btns">
        <button wire:click="cerrarEditarPlan"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        <button wire:click="guardarEdicionPlan" wire:loading.attr="disabled" wire:target="guardarEdicionPlan"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="guardarEdicionPlan">Guardar cambios</span>
            <span wire:loading wire:target="guardarEdicionPlan">Guardando...</span>
        </button>
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

<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            VISTA PREVIA DEL PLAN
        </h1>
        <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:4px;">
            <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0;">{{ $p->numero }}</p>
            <span style="padding:2px 10px; border-radius:6px; font-size:13px; font-weight:800; background:#fff; color:#7B6FE8;">v{{ $plan?->version ?? 1 }}</span>
        </div>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $p->cliente->ci ?: '—' }} - {{ ucwords(strtolower($p->cliente->nombre_completo)) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}
            </p>
        </div>

        {{-- Separador Plan de Pagos --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Plan de Pagos Activo</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#C4B5FD; text-align:center;">#</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Vencimiento</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas->where('numero','>',0)->sortBy('numero') as $c)
                    @php $badge = $c->estadoFinancieroBadge; @endphp
                    <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                        <td style="padding:8px 12px; font-size:13px; text-align:center; color:#374151;">{{ $c->numero }}</td>
                        <td style="padding:8px 12px; font-size:11px; text-align:center; color:#6b7280; font-weight:600;">Cuota {{ $c->numero }}</td>
                        <td style="padding:8px 12px; font-size:13px; text-align:center; font-family:monospace; font-weight:700; color:#7c3aed;">{{ number_format($c->monto, 2) }}</td>
                        <td style="padding:8px 12px; font-size:11px; text-align:center; color:#6b7280;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                        <td style="padding:8px 12px; text-align:center;"><span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:32px; text-align:center; color:#9CA3AF; font-size:13px;">Sin cuotas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Separador Resumen --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Resumen</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18);">
            <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#DC2626 100%);"></div>
            <div style="padding:14px;">
                <div style="display:grid; grid-template-columns:repeat(3,1fr); text-align:center; margin-bottom:10px;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Plan</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; font-family:monospace;">{{ number_format($cuotas->where('numero','>',0)->sum('monto'), 2) }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Pagado</span>
                        <span style="font-size:13px; font-weight:900; color:#059669; font-family:monospace;">{{ number_format($pagadas, 2) }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Pendiente</span>
                        <span style="font-size:13px; font-weight:900; color:#DC2626; font-family:monospace;">{{ number_format($pendiente, 2) }}</span>
                    </div>
                </div>
                <div style="height:1px; background:#EDE9FE; margin-bottom:10px;"></div>
                <div style="display:grid; grid-template-columns:repeat(3,1fr); text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Cuotas</span>
                        <span style="font-size:13px; font-weight:900; color:#111827;">{{ $cuotas->where('numero','>',0)->count() }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Cuotas Pagadas</span>
                        <span style="font-size:13px; font-weight:900; color:#111827;">{{ $cuotas->where('numero','>',0)->where('estado','pagado')->count() }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Cuotas Pendientes</span>
                        <span style="font-size:13px; font-weight:900; color:#111827;">{{ $nPend }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Botones --}}
    <div class="rp-det-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        @if($nPend > 0)
        <button wire:click="irForm"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Nueva Reprogramación
        </button>
        @else
        <div style="flex:1; padding:14px; background:#FFF7ED; border:1.5px solid #FCD34D; border-radius:12px; text-align:center; font-size:13px; font-weight:600; color:#B45309;">
            Todas las cuotas están pagadas — sin saldo a reprogramar.
        </div>
        @endif
    </div>

</div>

{{-- ══ FORM ══ --}}
@elseif($mode === 'form' && $pedidoDetalle)
@php
    $p         = $pedidoDetalle;
    $plan      = $p->planPago;
    $pendiente = round((float)($plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0), 2);
@endphp

<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            NUEVA REPROGRAMACIÓN
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $p->numero }}
        </p>
        <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
            <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#F3F4F6; color:#6B7280;">v{{ $plan?->version ?? 1 }}</span>
            <svg width="11" height="11" fill="none" stroke="#C4B5FD" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#D1FAE5; color:#059669;">v{{ ($plan?->version ?? 1) + 1 }}</span>
        </div>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $p->cliente->ci ?: '—' }} - {{ ucwords(strtolower($p->cliente->nombre_completo)) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Saldo a reprogramar:</span>
                <span style="color:#DC2626; font-weight:800; font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</span>
            </p>
        </div>

        {{-- Separador Configurar Plan --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Configurar Plan</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:16px 18px;">
            <div style="display:flex; align-items:flex-end; gap:14px; flex-wrap:wrap;">
                <div style="flex:0 0 160px;">
                    <label style="font-size:10px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:4px;">Cantidad de cuotas</label>
                    <input wire:model="cantidadCuotas" type="number" min="1" max="120" placeholder="Ej: 6"
                           style="width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; outline:none; background:#fff;">
                </div>
                <div style="flex:0 0 190px;">
                    <label style="font-size:10px; font-weight:700; color:#6B7280; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:4px;">Fecha 1ª cuota</label>
                    <input wire:model="fechaPrimera" type="date"
                           style="width:100%; height:36px; padding:0 10px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; outline:none; background:#fff;">
                </div>
                <button wire:click="generarPlan"
                        style="display:flex; align-items:center; gap:6px; height:36px; padding:0 16px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; border:none; border-radius:8px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Generar plan
                </button>
            </div>
        </div>

        {{-- Separador Cuotas --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Cuotas del Nuevo Plan</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Tabla cuotas + card resumen (mismo scope Alpine) --}}
        <div x-data="{
                saldo: {{ $pendiente }},
                recalc(root, refs) {
                    let inputs = root.querySelectorAll('.monto-cuota');
                    let total = Array.from(inputs).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
                    refs.totalDisplay.textContent = 'Bs. ' + total.toFixed(2);
                    let diff = Math.round((total - this.saldo) * 100) / 100;
                    let el = refs.diffDisplay;
                    if (Math.abs(diff) < 0.01) {
                        el.textContent = '✓ Cuadra exacto';
                        el.style.color = '#059669';
                    } else if (diff > 0) {
                        el.textContent = '+Bs. ' + diff.toFixed(2) + ' sobre saldo';
                        el.style.color = '#B45309';
                    } else {
                        el.textContent = '−Bs. ' + Math.abs(diff).toFixed(2) + ' bajo saldo';
                        el.style.color = '#DC2626';
                    }
                }
             }"
             x-init="
                $el.addEventListener('input', (e) => { if (e.target.classList.contains('monto-cuota')) recalc($el, $refs); });
                $wire.$watch('nuevasCuotas', () => $nextTick(() => recalc($el, $refs)));
                $nextTick(() => recalc($el, $refs));
             ">
        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:14px;">
            <div style="padding:10px 14px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; justify-content:space-between; background:#F8F7FF;">
                <span style="font-size:12px; font-weight:700; color:#534AB7;">Cuotas del nuevo plan</span>
                <button wire:click="agregarCuota" @click="$nextTick(()=>recalc($el,$refs))"
                        style="display:flex; align-items:center; gap:5px; padding:5px 12px; background:#EDE9FE; color:#534AB7; font-size:12px; font-weight:700; border:1px solid #C4B5FD; border-radius:8px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Agregar cuota
                </button>
            </div>
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#C4B5FD; text-align:center;">#</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto (Bs.)</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha vencimiento</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nuevasCuotas as $i => $cuota)
                    <tr wire:key="nc-{{ $i }}" style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
                        <td style="padding:8px 12px; font-size:13px; text-align:center; color:#374151;">{{ $cuota['numero'] }}</td>
                        <td style="padding:8px 12px; font-size:11px; text-align:center; color:#6b7280; font-weight:600;">Cuota {{ $cuota['numero'] }}</td>
                        <td style="padding:8px 12px; text-align:center;">
                            <input wire:model="nuevasCuotas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                                   class="monto-cuota"
                                   style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; text-align:center; outline:none; background:#fff;">
                            @error("nuevasCuotas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                        </td>
                        <td style="padding:8px 12px; text-align:center;">
                            <input wire:model="nuevasCuotas.{{ $i }}.fecha" type="date"
                                   style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; outline:none; background:#fff;">
                            @error("nuevasCuotas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                        </td>
                        <td style="padding:8px 12px; text-align:center;">
                            @if(count($nuevasCuotas) > 1)
                            <button wire:click="quitarCuota({{ $i }})" @click="$nextTick(()=>recalc($el,$refs))"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #FECACA; background:#FEF2F2; color:#DC2626; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>{{-- /tabla --}}

        {{-- Card resumen real-time --}}
        <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18); margin-bottom:14px;">
            <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#DC2626 100%);"></div>
            <div style="padding:14px; display:grid; grid-template-columns:repeat(3,1fr); text-align:center;">
                <div style="padding:0 6px;">
                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Saldo a cubrir</span>
                    <span style="font-size:13px; font-weight:900; color:#DC2626; font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</span>
                </div>
                <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total cuotas</span>
                    <p x-ref="totalDisplay" style="font-size:13px; font-weight:900; color:#111827; font-family:monospace; margin:0;"></p>
                </div>
                <div style="padding:0 6px;">
                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Diferencia</span>
                    <p x-ref="diffDisplay" style="font-size:13px; font-weight:900; font-family:monospace; margin:0;"></p>
                </div>
            </div>
        </div>

        </div>{{-- /x-data wrapper --}}

        {{-- Separador Motivo --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Motivo</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; padding:14px 16px;">
            <textarea wire:model="motivo" rows="3" placeholder="Ej: Cliente solicitó extensión de plazo por dificultades económicas..."
                      style="width:100%; display:block; resize:vertical; border:1px solid #E5E7EB; border-radius:8px; padding:8px 12px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;"></textarea>
            @error('motivo')<p class="ds-form-error">{{ $message }}</p>@enderror
        </div>

    </div>

    {{-- Botones grandes --}}
    <div class="nr-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        <button wire:click="confirmar" wire:loading.attr="disabled"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="confirmar">Confirmar Reprogramación</span>
            <span wire:loading wire:target="confirmar">Procesando...</span>
        </button>
    </div>

</div>
@endif

</div>
