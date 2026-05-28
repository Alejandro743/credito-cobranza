<div>

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    <div wire:key="ec-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#FEF3C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#D97706;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; background:#FEF3C7; color:#D97706;">
                En espera
            </span>
        </div>
        <div style="padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Vendedor</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->vendedor->user->name ?? '—' }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Total Bs.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="ver({{ $p->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Tomar para Revisión
            </button>
        </div>
    </div>
    @empty
    <div wire:key="ec-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos en espera</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">En Espera de Aprobación</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:800px; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cod. Pedido</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Vendedor</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Fecha</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Total Bs.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="ed-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 14px; font-family:monospace; font-size:12px; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ $p->cliente->nombre_completo }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap;">{{ $p->vendedor->user->name ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:700; color:#111827; white-space:nowrap;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="ver({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'"
                            title="Tomar para revisión">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="ed-empty">
                <td colspan="7" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos en espera</p>
                    <p style="font-size:12px; color:#9CA3AF;">Todos los pedidos han sido tomados para revisión</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if ($pedidos->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- ══ DETAIL ══ --}}
@elseif ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = false; $editable = false; @endphp

<div style="max-width:720px; margin:0 auto;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <span style="font-size:16px; font-weight:800; color:#4A4A4A; font-family:monospace; letter-spacing:1px;">{{ $p->numero }}</span>
        <span style="font-size:11px;color:#CBCBCB;white-space:nowrap;margin-left:auto;">{{ $p->created_at->format('d/m/Y H:i') }}</span>
    </div>

    @include('livewire.credito.partials.pedido-detail')

    <div style="display:flex;align-items:center;gap:10px;margin-top:12px;flex-wrap:wrap;">
        <div style="flex:1;"></div>
        <button wire:click="tomarRevision({{ $p->id }})"
                wire:confirm="¿Tomás este pedido para revisión? Quedará asignado a vos."
                class="ds-btn ds-btn-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Tomar para Revisión
        </button>
    </div>

</div>

@endif
</div>
