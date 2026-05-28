<div>

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#FEF3C7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>En Espera de Aprobación</h2>
        <p>Pedidos pendientes de revisión</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pedidos->total() }} pedido{{ $pedidos->total() !== 1 ? 's' : '' }}</span>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden">
    <div style="margin-bottom:12px;">
        <div style="position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
                   style="padding-left:32px;width:100%;box-sizing:border-box;">
        </div>
    </div>

    <div class="flex flex-col" style="gap:10px;">
        @forelse ($pedidos as $p)
        <div wire:key="ec-{{ $p->id }}"
             style="background:#fff;border-radius:14px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.05);overflow:hidden;">
            <div style="padding:12px 14px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #F3F4F6;">
                <div style="width:30px;height:30px;border-radius:8px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:12px;font-weight:700;color:#D97706;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:14px;font-weight:700;color:#111827;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                    <p style="font-size:12px;color:#7B6FE8;font-family:monospace;margin:2px 0 0;">{{ $p->numero }}</p>
                </div>
                <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;flex-shrink:0;background:#FEF3C7;color:#D97706;">
                    En espera
                </span>
            </div>
            <div style="padding:10px 14px;display:flex;align-items:center;gap:8px;">
                <div style="flex:1;">
                    <span style="font-size:11px;color:#9CA3AF;display:block;">Vendedor</span>
                    <span style="font-size:12px;font-weight:600;color:#374151;">{{ $p->vendedor->user->name ?? '—' }}</span>
                </div>
                <div style="flex:1;">
                    <span style="font-size:11px;color:#9CA3AF;display:block;">Fecha</span>
                    <span style="font-size:12px;font-weight:600;color:#374151;">{{ $p->created_at->format('d/m/Y') }}</span>
                </div>
                <div style="text-align:right;">
                    <span style="font-size:11px;color:#9CA3AF;display:block;">Total Bs.</span>
                    <span style="font-size:13px;font-weight:700;color:#D97706;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</span>
                </div>
            </div>
            <div style="padding:10px 14px;border-top:1px solid #F3F4F6;">
                <button wire:click="ver({{ $p->id }})" class="ds-btn ds-btn-warning" style="width:100%;justify-content:center;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Tomar para Revisión
                </button>
            </div>
        </div>
        @empty
        <div wire:key="ec-mobile-empty" style="text-align:center;padding:48px 24px;">
            <svg style="width:48px;height:48px;color:#E5E7EB;margin:0 auto 12px;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p style="font-weight:600;color:#6B7280;font-size:13px;">Sin pedidos en espera</p>
        </div>
        @endforelse
        @if ($pedidos->hasPages())
        <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
        @endif
    </div>
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:300px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
                   style="padding-left:32px;width:100%;">
        </div>
        <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;margin-left:auto;">{{ $pedidos->total() }} pedido{{ $pedidos->total() !== 1 ? 's' : '' }}</span>
    </div>

    <div style="overflow-x:auto;">
    <table style="min-width:700px;">
        <thead>
            <tr>
                <th>Cod. Pedido</th>
                <th>CI</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Fecha</th>
                <th style="text-align:right;">Total Bs.</th>
                <th style="text-align:center;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="ed-{{ $p->id }}">
                <td style="font-family:monospace;font-size:12px;font-weight:700;white-space:nowrap;">{{ $p->numero }}</td>
                <td style="white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="font-weight:500;white-space:nowrap;">{{ $p->cliente->nombre_completo }}</td>
                <td style="white-space:nowrap;">{{ $p->vendedor->user->name ?? '—' }}</td>
                <td style="white-space:nowrap;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="text-align:right;font-weight:700;">
                    {{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}
                </td>
                <td style="text-align:center;">
                    <button wire:click="ver({{ $p->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Tomar
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="ed-empty">
                <td colspan="7">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Sin pedidos en espera</p>
                        <p style="font-size:12px;margin-top:4px;">Todos los pedidos han sido tomados para revisión</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if ($pedidos->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- ══ DETAIL ══ --}}
@elseif ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = false; $editable = false; @endphp

<div style="max-width:720px;margin:0 auto;">

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <span style="font-size:16px;font-weight:800;color:#4A4A4A;font-family:monospace;letter-spacing:1px;">{{ $p->numero }}</span>
        <span style="background:#FEF3C7;color:#D97706;font-size:11px;font-weight:600;padding:2px 10px;border-radius:99px;margin-left:auto;">En espera</span>
    </div>

    @include('livewire.credito.partials.pedido-detail')

    <div style="margin-top:12px;display:flex;justify-content:flex-end;">
        <button wire:click="tomarRevision({{ $p->id }})"
                wire:confirm="¿Tomás este pedido para revisión? Quedará asignado a vos."
                class="ds-btn ds-btn-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Tomar para Revisión
        </button>
    </div>

</div>

@endif
</div>
