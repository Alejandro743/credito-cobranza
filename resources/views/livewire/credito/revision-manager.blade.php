<div>

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#FEF3C7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>En Revisión</h2>
        <p>Pedidos asignados a tu revisión</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pedidos->total() }} pedido{{ $pedidos->total() !== 1 ? 's' : '' }}</span>
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
    <table style="min-width:580px;">
        <thead>
            <tr>
                <th class="ds-sticky-col" style="padding:0;height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #CBCBCB;display:flex;align-items:center;justify-content:center;">Pedido</div>
                        <div style="flex:1;padding:10px 12px;display:flex;align-items:center;justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Total Bs.</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr wire:key="r-{{ $p->id }}">
                <td data-label="Pedido / Cliente" class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td data-label="Vendedor" style="text-align:center;">{{ $p->vendedor->user->name ?? '—' }}</td>
                <td data-label="Fecha" style="text-align:center;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td data-label="Total Bs." style="text-align:center;font-weight:700;color:#6D8196;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</td>
                <td data-label="" style="text-align:center;">
                    <button wire:click="ver({{ $p->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Revisar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p>No tenés pedidos en revisión</p>
                        <p style="font-size:12px;margin-top:4px;">Tomá pedidos desde "En Espera"</p>
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
{{-- ══ DETAIL ══ --}}
@elseif ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = false; $editable = true; @endphp

<div style="max-width:720px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <span style="font-size:16px;font-weight:800;color:#4A4A4A;font-family:monospace;letter-spacing:1px;">{{ $p->numero }}</span>
        <span style="font-size:11px;color:#CBCBCB;margin-left:auto;">{{ $p->created_at->format('d/m/Y H:i') }}</span>
    </div>

    @include('livewire.credito.partials.pedido-detail')

    {{-- ACCIONES --}}
    @if (!$confirmandoRechazo)
    <div style="display:flex;align-items:center;gap:10px;margin-top:12px;flex-wrap:wrap;">
        <button wire:click="devolverEspera" wire:confirm="¿Devolvés este pedido a En Espera?" class="ds-btn ds-btn-warning ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            En Espera
        </button>
        <div style="flex:1;"></div>
        <button wire:click="$set('confirmandoRechazo', true)" class="ds-btn ds-btn-danger ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Rechazar
        </button>
        <button wire:click="aprobar" wire:confirm="¿Confirmás la aprobación de este pedido?" class="ds-btn ds-btn-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Aprobar
        </button>
    </div>
    @else
    <div class="ds-confirm-panel danger" style="margin-top:12px;">
        <p class="ds-confirm-panel-title">Motivo del rechazo</p>
        <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..." style="width:100%;display:block;"></textarea>
        @error('notaRechazo')<p class="ds-form-error">{{ $message }}</p>@enderror
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoRechazo', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="rechazar" class="ds-btn ds-btn-danger ds-btn-sm">Confirmar Rechazo</button>
        </div>
    </div>
    @endif
</div>

@endif
</div>
