<div>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Historial de Pagos</h2>
        <p>Todos los pagos registrados en el sistema</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pagos->total() }} pago{{ $pagos->total() !== 1 ? 's' : '' }}</span>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:300px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, nombre o pedido..."
                   style="padding-left:32px;width:100%;">
        </div>
    </div>

    <div style="overflow-x:auto;">
    <table style="min-width:700px;">
        <thead>
            <tr>
                <th class="ds-sticky-col" style="padding:0;height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:130px;padding:10px 12px;border-right:1px solid #CBCBCB;display:flex;align-items:center;justify-content:center;">Código</div>
                        <div style="flex:1;padding:10px 12px;display:flex;align-items:center;justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th>Pedido</th>
                <th>Cuotas</th>
                <th>Monto total</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Registrado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pg)
            @php $esAnulado = $pg->estado === 'anulado'; @endphp
            <tr wire:key="pg-{{ $pg->id }}" style="{{ $esAnulado ? 'opacity:0.6;' : '' }}" wire:click="verPago({{ $pg->id }})" style="cursor:pointer;">
                <td data-label="Código / Cliente" class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:130px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;{{ $esAnulado ? 'text-decoration:line-through;' : '' }}">
                            {{ $pg->numero }}
                        </div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $pg->pedido->cliente->nombre_completo }}</p>
                            @if($pg->pedido->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $pg->pedido->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td data-label="Pedido" style="text-align:center;font-family:monospace;font-size:11px;font-weight:600;">{{ $pg->pedido->numero }}</td>
                <td data-label="Cuotas" style="text-align:center;font-weight:700;">{{ $pg->cantidad_cuotas }}</td>
                <td data-label="Monto" style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($pg->monto_total, 2) }}</td>
                <td data-label="Fecha" style="text-align:center;font-size:11px;color:#CBCBCB;">{{ $pg->created_at->format('d/m/Y') }}</td>
                <td data-label="Estado" style="text-align:center;">
                    @if($esAnulado)
                    <span class="ds-badge ds-badge-anulado">Anulado</span>
                    @else
                    <span class="ds-badge" style="background:#DCFCE7;color:#059669;">Activo</span>
                    @endif
                </td>
                <td data-label="Registrado por" style="font-size:12px;color:#CBCBCB;">{{ $pg->creadoPor->name ?? '—' }}</td>
                <td data-label="" style="text-align:center;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                        <button wire:click.stop="verPago({{ $pg->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver
                        </button>
                        @if(!$esAnulado && $pg->planPago?->estado === 'activo')
                        <button wire:click.stop="anularPago({{ $pg->id }})"
                                wire:confirm="¿Anular este pago? Las cuotas volverán a estado pendiente."
                                class="ds-btn ds-btn-danger ds-btn-sm">
                            Anular
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p>Sin pagos registrados</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($pagos->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $pagos->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pagoDetalle)
@php
    $pg          = $pagoDetalle;
    $cuotas      = $cuotasDetalle;
    $esAnulado   = $pg->estado === 'anulado';
    $pgVersion   = $pg->planPago?->version ?? 1;
    $pgPlanLabel = $pgVersion > 1 ? 'Reprogramación: V' . $pgVersion : 'Plan Original';
@endphp

<div style="max-width:680px;margin:0 auto;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Historial
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $pg->pedido->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $pg->numero }} · Pedido: {{ $pg->pedido->numero }}</p>
        </div>
        @if($esAnulado)
        <span class="ds-badge ds-badge-anulado">ANULADO</span>
        @else
        <span class="ds-badge" style="background:#DCFCE7;color:#059669;">ACTIVO</span>
        @endif
        @if($pg->esAnulable && !$confirmandoAnulacion)
        <button wire:click="$set('confirmandoAnulacion', true)" class="ds-btn ds-btn-danger ds-btn-sm">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Anular
        </button>
        @endif
    </div>

    @if($confirmandoAnulacion)
    <div class="ds-confirm-panel danger">
        <p class="ds-confirm-panel-title">Confirmar anulación</p>
        <p style="font-size:12px;color:#6b7280;margin:0 0 10px;">
            Las {{ $pg->cuotas->where('numero','>',0)->count() }} cuota(s) volverán a estado <strong>pendiente</strong>. Esta acción no se puede deshacer.
        </p>
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoAnulacion', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="anularPago" wire:loading.attr="disabled" class="ds-btn ds-btn-danger ds-btn-sm">
                <span wire:loading.remove wire:target="anularPago">Sí, anular pago</span>
                <span wire:loading wire:target="anularPago">Anulando...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Datos del cliente --}}
    <div x-data="{ modal: false }" style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 16px;margin-bottom:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
            <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0;">Cliente</p>
            <button @click="modal = true" class="ds-btn ds-btn-ghost ds-btn-sm">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Ver datos
            </button>
        </div>
        <p style="font-size:14px;font-weight:600;color:#4A4A4A;margin:0;">
            {{ $pg->pedido->cliente->ci ? $pg->pedido->cliente->ci . ' — ' : '' }}{{ $pg->pedido->cliente->nombre_completo }}
        </p>

        {{-- Modal datos cliente --}}
        <div x-show="modal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(20,10,40,0.4);"
             @click.self="modal = false">
            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;width:100%;max-width:420px;overflow:hidden;position:relative;max-height:90vh;overflow-y:auto;">
                <div style="padding:16px 18px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;justify-content:space-between;">
                    <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Datos del Cliente</p>
                    <button @click="modal = false" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @php $cli = $pg->pedido->cliente; @endphp
                <div style="padding:16px 18px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div><span class="ds-form-label">Nombre</span><p style="font-size:12px;font-weight:600;color:#4A4A4A;margin:0;">{{ $cli->nombre_completo }}</p></div>
                    <div><span class="ds-form-label">CI</span><p style="font-size:12px;font-weight:600;color:#4A4A4A;margin:0;font-family:monospace;">{{ $cli->ci ?: '—' }}</p></div>
                    <div><span class="ds-form-label">Teléfono</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ $cli->telefono ?: '—' }}</p></div>
                    <div><span class="ds-form-label">Correo</span><p style="font-size:12px;color:#4A4A4A;margin:0;word-break:break-all;">{{ $cli->correo ?: '—' }}</p></div>
                    @if($cli->ciudad)<div><span class="ds-form-label">Ciudad</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ strtoupper($cli->ciudad) }}</p></div>@endif
                    @if($cli->direccion)<div><span class="ds-form-label">Dirección</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ $cli->direccion }}</p></div>@endif
                </div>
            </div>
        </div>
    </div>

    {{-- Cuotas --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">{{ $esAnulado ? 'Cuotas Anuladas' : 'Cuotas Pagadas' }}</p>
            <span class="ds-badge ds-badge-cerrado">{{ $pgPlanLabel }}</span>
            <span class="ds-badge" style="background:#FFFFE3;color:#6D8196;border:1px solid #6D8196;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;">Cuota</th>
                    <th style="text-align:right;">Monto</th>
                    <th style="text-align:center;">Vencimiento</th>
                    <th style="text-align:center;">Fecha pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                <tr wire:key="dc-{{ $c->id }}">
                    <td style="text-align:center;font-weight:600;">Cuota {{ $c->numero }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="text-align:center;color:#CBCBCB;">{{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                    <td style="text-align:center;">
                        @if($c->fecha_pago)
                        <span style="font-weight:600;color:#059669;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                        @else
                        <span style="color:#CBCBCB;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="ds-empty"><p>Sin cuotas</p></div></td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:center;">
                        Total:
                        <span style="font-family:monospace;font-size:15px;font-weight:800;color:{{ $esAnulado ? '#DC2626' : '#059669' }};{{ $esAnulado ? 'text-decoration:line-through;' : '' }}margin-left:6px;">
                            Bs. {{ number_format($pg->monto_total, 2) }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    @if($esAnulado)
    <div style="background:#FFF0F0;border:1px solid #FCA5A5;border-radius:8px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p style="font-size:11px;font-weight:700;color:#DC2626;margin:0;">Pago anulado</p>
            <p style="font-size:11px;color:#6b7280;margin:0;">Por {{ $pg->anuladoPor->name ?? '—' }} el {{ $pg->anulado_at?->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    @endif

</div>

@endif
</div>
