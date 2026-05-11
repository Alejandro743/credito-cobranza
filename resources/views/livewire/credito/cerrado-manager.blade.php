<div>

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#EDE9FE;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Créditos Cerrados</h2>
        <p>Historial de créditos finalizados</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pedidos->total() }} registro{{ $pedidos->total() !== 1 ? 's' : '' }}</span>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:300px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
                   style="padding-left:32px;width:100%;">
        </div>
    </div>

    <div style="overflow-x:auto;">
    <table style="min-width:600px;">
        <thead>
            <tr>
                <th class="ds-sticky-col" style="padding:0;height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #CBCBCB;display:flex;align-items:center;justify-content:center;">Pedido</div>
                        <div style="flex:1;padding:10px 12px;display:flex;align-items:center;justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th>Motivo Cierre</th>
                <th>Vendedor</th>
                <th>Cerrado el</th>
                <th>Total Bs.</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr wire:key="cr-{{ $p->id }}">
                <td data-label="Pedido / Cliente" class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td data-label="Motivo" style="text-align:center;">
                    @if($p->cierre?->motivoCierre)
                    <span style="font-size:12px;font-weight:600;color:#4A4A4A;">{{ $p->cierre->motivoCierre->nombre }}</span>
                    @if($p->cierre->motivoCierre->afecta_mora)
                    <span class="ds-badge ds-badge-danger" style="display:block;margin-top:3px;">Afecta indicadores</span>
                    @endif
                    @else
                    <span style="color:#CBCBCB;font-size:11px;">—</span>
                    @endif
                </td>
                <td data-label="Vendedor" style="text-align:center;">{{ $p->vendedor?->nombre_completo ?? '—' }}</td>
                <td data-label="Cerrado el" style="text-align:center;font-size:11px;color:#CBCBCB;">{{ $p->cierre?->created_at->format('d/m/Y') ?? $p->updated_at->format('d/m/Y') }}</td>
                <td data-label="Total Bs." style="text-align:center;font-weight:600;color:#4A4A4A;">{{ number_format($p->total_pagar, 2) }}</td>
                <td data-label="" style="text-align:center;">
                    <button wire:click="ver({{ $p->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/>
                        </svg>
                        <p>Sin créditos cerrados</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($pedidos->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- ══ DETAIL ══ --}}
@elseif ($mode === 'detail' && $pedidoDetalle)
@php
    $p      = $pedidoDetalle;
    $cierre = $p->cierre;
    $plan   = $p->planes->where('id', $cierre?->plan_pago_id)->first() ?? $p->planes->last();
@endphp

<div style="max-width:680px;margin:0 auto;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }}</p>
        </div>
        <span class="ds-badge ds-badge-cerrado">CERRADO</span>
    </div>

    {{-- Card de cierre --}}
    @if($cierre)
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px;margin-bottom:14px;">
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 12px;">Registro de Cierre</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:12px;">
            <div>
                <span class="ds-form-label">Motivo</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $cierre->motivoCierre?->nombre ?? '—' }}</span>
                @if($cierre->motivoCierre?->afecta_mora)
                <span class="ds-badge ds-badge-danger" style="margin-left:4px;">Afecta indicadores</span>
                @endif
            </div>
            <div>
                <span class="ds-form-label">Cerrado por</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $cierre->cerradoPor?->name ?? '—' }}</span>
            </div>
            <div>
                <span class="ds-form-label">Fecha de cierre</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $cierre->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($cierre->observacion)
            <div style="grid-column:span 2;">
                <span class="ds-form-label">Observación</span>
                <span style="color:#4A4A4A;">{{ $cierre->observacion }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Resumen del plan --}}
    @if($plan)
    @php
        $cuotasReg      = $plan->cuotas->where('numero', '>', 0);
        $totalCuotas    = $cuotasReg->count();
        $pagadas        = $cuotasReg->where('estado', 'pagado')->count();
        $pendientes     = $totalCuotas - $pagadas;
        $montoPagado    = $cuotasReg->where('estado', 'pagado')->sum('monto');
        $montoPendiente = $cuotasReg->whereIn('estado', ['pendiente', 'vencido'])->sum('monto');
    @endphp
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px;margin-bottom:14px;">
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 12px;">Plan de Pagos — V{{ $plan->version }}</p>
        <div class="ds-stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:0;">
            <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
                <div class="ds-stat-value" style="color:#10B981;">{{ $pagadas }}</div>
                <div class="ds-stat-label">Pagadas</div>
            </div>
            <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
                <div class="ds-stat-value" style="color:#DC2626;">{{ $pendientes }}</div>
                <div class="ds-stat-label">Pendientes</div>
            </div>
            <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
                <div class="ds-stat-value" style="font-size:16px;color:#10B981;">{{ number_format($montoPagado, 2) }}</div>
                <div class="ds-stat-label">Bs. Cobrado</div>
            </div>
            <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
                <div class="ds-stat-value" style="font-size:16px;color:#DC2626;">{{ number_format($montoPendiente, 2) }}</div>
                <div class="ds-stat-label">Bs. Pendiente</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Acción: revertir --}}
    @if(!$confirmandoReversion)
    <div style="margin-top:8px;">
        <button wire:click="$set('confirmandoReversion', true)" class="ds-btn ds-btn-warning ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Revertir Cierre
        </button>
    </div>
    @else
    <div class="ds-confirm-panel warning">
        <p class="ds-confirm-panel-title">Motivo de la reversión</p>
        <textarea wire:model="motivoReversion" rows="3" placeholder="Explicá por qué se revierte el cierre..."
                  style="width:100%;display:block;"></textarea>
        @error('motivoReversion')<p class="ds-form-error">{{ $message }}</p>@enderror
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoReversion', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="revertir" class="ds-btn ds-btn-warning ds-btn-sm">Confirmar Reversión</button>
        </div>
    </div>
    @endif

</div>

@endif
</div>
