<div>
<style>
.ph-badge    { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
.ph-card     { background:#fff; border-radius:14px; border:1px solid #d1fae5; box-shadow:0 1px 4px rgba(0,0,0,0.05); overflow:hidden; }
.ph-pill-back{ background:#fff; border:1.5px solid #6ee7b7; border-radius:20px; padding:5px 14px 5px 10px; font-size:12px; font-weight:600; color:#15803D; cursor:pointer; display:inline-flex; align-items:center; gap:5px; }
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
    <h1 class="font-bold text-base flex-1" style="color:#15803D;">Historial de Pagos</h1>
    <span class="text-sm font-medium" style="color:#15803D;">{{ now()->format('d/m/Y') }}</span>
</div>

<div class="p-4 sm:p-6">

{{-- ══ LIST ══ --}}
@if($mode === 'list')
@php $theadStyle = 'background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    <div style="position:relative; flex-shrink:0; width:260px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, nombre o pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #a7f3d0; border-radius:8px;
                      background:#f0fdf4; font-size:12px; outline:none;" />
    </div>
    <span style="font-size:12px; color:#9ca3af;">{{ $pagos->total() }} pago{{ $pagos->total() !== 1 ? 's' : '' }} registrado{{ $pagos->total() !== 1 ? 's' : '' }}</span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px; min-width:700px;">
        <thead style="{{ $theadStyle }}">
            <tr>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:150px;">Código</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Pedido</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Cliente</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Cuotas</th>
                <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5; width:130px;">Monto total</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:100px;">Fecha</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:90px;">Estado</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5;">Registrado por</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:60px;">Ver</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Anular</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pg)
            @php $esAnulado = $pg->estado === 'anulado'; @endphp
            <tr wire:key="pg-{{ $pg->id }}" class="hover:bg-green-50 transition-colors" style="cursor:pointer; {{ $esAnulado ? 'opacity:0.55;' : '' }}"
                wire:click="verPago({{ $pg->id }})">
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#15803D; font-weight:700; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}">
                    {{ $pg->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#374151; font-weight:600;">
                    {{ $pg->pedido->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb;">
                    <p style="font-weight:600; font-size:13px; color:#166534; margin:0;">{{ $pg->pedido->cliente->nombre_completo }}</p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;">CI: {{ $pg->pedido->cliente->ci ?? '—' }}</p>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-weight:700; color:#374151;">
                    {{ $pg->cantidad_cuotas }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:{{ $esAnulado ? '#9ca3af' : '#15803D' }}; font-size:12px;">
                    Bs. {{ number_format($pg->monto_total, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#374151;">
                    {{ $pg->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    @if($esAnulado)
                    <span class="ph-badge" style="background:#FEF2F2; color:#B91C1C;">Anulado</span>
                    @else
                    <span class="ph-badge" style="background:#DCFCE7; color:#15803D;">Activo</span>
                    @endif
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-size:12px; color:#6b7280;">
                    {{ $pg->creadoPor->name ?? '—' }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    <button wire:click.stop="verPago({{ $pg->id }})" title="Ver detalle"
                            class="p-1.5 rounded-lg hover:bg-green-50 transition-colors" style="color:#15803D;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
                <td x-data="{ confirmar: false }" style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    @if(!$esAnulado && $pg->planPago?->estado === 'activo')
                        <template x-if="!confirmar">
                            <button @click.stop="confirmar = true"
                                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #FCA5A5; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                                <svg width="13" height="13" fill="none" stroke="#B91C1C" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span style="font-size:10px; font-weight:700; color:#B91C1C;">Anular</span>
                            </button>
                        </template>
                        <template x-if="confirmar">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:5px;">
                                <span style="font-size:10px; font-weight:700; color:#991B1B; white-space:nowrap;">¿Confirmar?</span>
                                <div style="display:flex; gap:5px;">
                                    <button @click.stop="$wire.anularPago({{ $pg->id }})"
                                            style="font-size:10px; font-weight:700; color:#fff; background:#B91C1C; border:none; border-radius:6px; padding:4px 10px; cursor:pointer;">
                                        Confirmar
                                    </button>
                                    <button @click.stop="confirmar = false"
                                            style="font-size:10px; font-weight:600; color:#6b7280; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:6px; padding:4px 8px; cursor:pointer;">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </template>
                    @else
                        <span style="color:#d1d5db; font-size:11px;">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-14 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Sin pagos registrados.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pagos->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $pagos->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pagoDetalle)
@php
    $pg          = $pagoDetalle;
    $cuotas      = $pg->cuotas->where('numero', '>', 0)->sortBy('numero');
    $pgVersion   = $pg->planPago?->version ?? 1;
    $pgPlanLabel = $pgVersion > 1 ? 'Reprogramación: V' . $pgVersion : 'Plan Original';
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    {{-- Cabecera --}}
    @php
        $esAnulado  = $pg->estado === 'anulado';
        $hBg        = $esAnulado ? '#FEF2F2' : '#F0FDF4';
        $hBorder    = $esAnulado ? '#FCA5A5' : '#86EFAC';
        $hColor     = $esAnulado ? '#B91C1C' : '#15803D';
        $hColorDark = $esAnulado ? '#991B1B' : '#166534';
    @endphp
    <div style="background:{{ $hBg }}; border:1px solid {{ $hBorder }}; border-radius:14px; padding:16px 18px; margin:0 0 20px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="volver"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid {{ $hBorder }}; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="{{ $hColor }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:{{ $hColor }};">Historial</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:22px; font-weight:800; color:{{ $hColorDark }}; letter-spacing:-0.3px; margin:0;">HISTORIAL DE PAGO</h1>
            @if($pg->esAnulable && !$confirmandoAnulacion)
            <button wire:click="$set('confirmandoAnulacion', true)"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #FCA5A5; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="#B91C1C" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:#B91C1C;">Anular</span>
            </button>
            @else
            <div style="width:70px; flex-shrink:0;"></div>
            @endif
        </div>
        <p style="text-align:center; font-size:22px; font-weight:800; color:{{ $hColor }}; font-family:monospace; margin:4px 0 6px; {{ $esAnulado ? 'text-decoration:line-through; opacity:0.6;' : '' }}">
            Bs. {{ number_format($pg->monto_total, 2) }}
        </p>
        <div style="text-align:center;">
            <span style="font-size:11px; font-weight:500; color:#6b7280;">
                Código: <span style="font-family:monospace; font-weight:700; color:{{ $hColor }};">{{ $pg->numero }}</span>
            </span>
            @if($esAnulado)
            <span class="ph-badge" style="background:#FEF2F2; color:#B91C1C; margin-left:8px;">Anulado</span>
            @endif
        </div>
    </div>

    {{-- Confirmación anulación --}}
    @if($confirmandoAnulacion)
    <div style="background:#FEF2F2; border:1.5px solid #FCA5A5; border-radius:12px; padding:14px 18px; margin-bottom:20px;">
        <p style="font-size:13px; font-weight:700; color:#991B1B; margin:0 0 4px;">Confirmar anulación</p>
        <p style="font-size:12px; color:#6b7280; margin:0 0 12px;">
            Las {{ $pg->cuotas->where('numero','>',0)->count() }} cuota(s) de este pago volverán a estado <strong>pendiente</strong>. Esta acción no se puede deshacer.
        </p>
        <div style="display:flex; gap:10px;">
            <button wire:click="$set('confirmandoAnulacion', false)"
                    style="flex:1; padding:8px; font-size:12px; font-weight:600; color:#6b7280; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">
                Cancelar
            </button>
            <button wire:click="anularPago" wire:loading.attr="disabled"
                    style="flex:1; padding:8px; font-size:12px; font-weight:700; color:#fff; background:#B91C1C; border:none; border-radius:8px; cursor:pointer;">
                <span wire:loading.remove wire:target="anularPago">Sí, anular pago</span>
                <span wire:loading wire:target="anularPago">Anulando...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Info anulación --}}
    @if($esAnulado)
    <div style="background:#FEF2F2; border:0.5px solid #FCA5A5; border-radius:10px; padding:10px 14px; margin-bottom:16px; display:flex; align-items:center; gap:10px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#B91C1C" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p style="font-size:11px; font-weight:700; color:#B91C1C; margin:0;">Pago anulado</p>
            <p style="font-size:11px; color:#6b7280; margin:0;">
                Por {{ $pg->anuladoPor->name ?? '—' }} el {{ $pg->anulado_at?->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    @endif

    {{-- DATOS DEL CLIENTE --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Datos del Cliente</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-4" style="border:0.5px solid #d1fae5; border-radius:10px; padding:10px 12px;">
        <span style="font-size:9px; font-weight:600; color:#15803D; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:4px;">Cliente</span>
        <span style="font-size:13px; font-weight:600; color:#166534; display:block;">
            {{ $pg->pedido->cliente->ci ? $pg->pedido->cliente->ci . ' — ' : '' }}{{ $pg->pedido->cliente->nombre_completo }}
        </span>
        <span style="font-size:11px; color:#9ca3af; display:block; margin-top:2px;">
            Pedido: <span style="font-family:monospace; font-weight:600; color:#374151;">{{ $pg->pedido->numero }}</span>
        </span>
    </div>

    {{-- DETALLES DEL PAGO --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Detalles del Pago</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-4" style="border:0.5px solid #d1fae5; border-radius:10px; padding:12px 16px; display:flex; gap:24px; flex-wrap:wrap;">
        <div>
            <p style="font-size:9px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 3px;">Fecha</p>
            <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">{{ $pg->created_at->format('d/m/Y') }}</p>
            <p style="font-size:11px; color:#9ca3af; margin:0;">{{ $pg->created_at->format('H:i') }}</p>
        </div>
        <div>
            <p style="font-size:9px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 3px;">Registrado por</p>
            <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">{{ $pg->creadoPor->name ?? '—' }}</p>
        </div>
    </div>

    {{-- CUOTAS PAGADAS --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#15803D;">Cuotas Pagadas</span>
        <div style="flex:1; height:1px; background:#6ee7b7;"></div>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#f0fdf4; color:#15803D; border:1px solid #6ee7b7;">{{ $pgPlanLabel }}</span>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#DCFCE7; color:#15803D;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px;">
            <thead style="background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #d1fae5; width:90px;">Cuota</th>
                    <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #d1fae5;">Monto</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5;">Vencimiento</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #d1fae5;">Fecha pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                @php
                    $hDiff = ($c->fecha_vencimiento && $c->fecha_pago)
                        ? (int) $c->fecha_vencimiento->diffInDays($c->fecha_pago, false)
                        : null;
                @endphp
                <tr wire:key="dc-{{ $c->id }}">
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:left;">
                        <div style="display:inline-flex; align-items:center; gap:5px;">
                            <span style="width:20px; height:20px; border-radius:50%; background:#DCFCE7; color:#15803D; font-size:10px; font-weight:700; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $c->numero }}</span>
                            <span style="font-size:12px; font-weight:600; color:#374151;">Cuota {{ $c->numero }}</span>
                        </div>
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:#15803D;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#6b7280;">
                        {{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        @if($c->fecha_pago)
                            <span style="font-size:12px; font-weight:600; color:#15803D; display:block;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                            @if($hDiff !== null)
                            <span style="font-size:10px; font-weight:600; color:{{ $hDiff > 0 ? '#B91C1C' : ($hDiff < 0 ? '#15803D' : '#854F0B') }};">
                                {{ $hDiff > 0 ? '+' . $hDiff . 'd mora' : ($hDiff < 0 ? abs($hDiff) . 'd antes' : 'a tiempo') }}
                            </span>
                            @endif
                        @else
                            <span style="font-size:12px; color:#9ca3af;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Sin cuotas</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:#f9fffe;">
                    <td colspan="2" style="padding:9px 12px; border-top:2px solid #d1fae5; font-size:12px; font-weight:700; color:#374151; text-align:right;">
                        Total: <span style="font-family:monospace; color:#15803D; margin-left:4px;">Bs. {{ number_format($pg->monto_total, 2) }}</span>
                    </td>
                    <td colspan="2" style="padding:9px 12px; border-top:2px solid #d1fae5;"></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

</div>
@endif

</div>
</div>
