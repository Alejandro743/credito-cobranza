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
    <div wire:key="cm-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; background:#F4F4F4; color:#4A4A4A; border:1px solid #CBCBCB;">Cerrado</span>
        </div>
        <div style="padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Vendedor</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->vendedor?->nombre_completo ?? '—' }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Cerrado el</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->cierre?->created_at->format('d/m/Y') ?? $p->updated_at->format('d/m/Y') }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Total Bs.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ number_format($p->total_pagar, 2) }}</span>
            </div>
        </div>
        @if($p->cierre?->motivoCierre)
        <div style="padding:6px 14px 10px; border-top:1px solid #F3F4F6;">
            <span style="font-size:11px; color:#9CA3AF; display:block;">Motivo cierre</span>
            <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->cierre->motivoCierre->nombre }}</span>
            @if($p->cierre->motivoCierre->afecta_mora)
            <span style="display:inline-block; margin-left:6px; background:#FEE2E2; color:#DC2626; font-size:9px; font-weight:700; border-radius:4px; padding:1px 6px; border:1px solid #FCA5A5;">Afecta indicadores</span>
            @endif
        </div>
        @endif
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="ver({{ $p->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver
            </button>
        </div>
    </div>
    @empty
    <div wire:key="cm-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin créditos cerrados</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Créditos Cerrados</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cod. Pedido</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Motivo Cierre</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Vendedor</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cerrado el</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Total Bs.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="cd-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 14px; font-family:monospace; font-size:12px; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ $p->cliente->nombre_completo }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#374151; white-space:nowrap;">
                    @if($p->cierre?->motivoCierre)
                        {{ $p->cierre->motivoCierre->nombre }}
                        @if($p->cierre->motivoCierre->afecta_mora)
                        <span style="display:inline-block; margin-left:4px; background:#FEE2E2; color:#DC2626; font-size:9px; font-weight:700; border-radius:4px; padding:1px 5px; border:1px solid #FCA5A5; vertical-align:middle;">Afecta</span>
                        @endif
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap;">{{ $p->vendedor?->nombre_completo ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cierre?->created_at->format('d/m/Y') ?? $p->updated_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:700; color:#111827; white-space:nowrap;">{{ number_format($p->total_pagar, 2) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="ver({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'"
                            title="Ver">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="cd-empty">
                <td colspan="8" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin créditos cerrados</p>
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
@php
    $p      = $pedidoDetalle;
    $cierre = $p->cierre;
    $plan   = $p->planes->where('id', $cierre?->plan_pago_id)->first() ?? $p->planes->last();
@endphp

<style>
.cm-act-wrap { display:flex; flex-direction:column; gap:8px; margin-top:16px; }
@@media (min-width:640px) { .cm-act-wrap { flex-direction:row; } }
</style>

<div style="max-width:900px; margin:0 auto;">

    @include('livewire.credito.partials.pedido-detail', [
        'p'        => $p,
        'plan'     => $plan,
        'aprobado' => false,
        'editable' => false,
    ])

    {{-- Registro de Cierre --}}
    @if($cierre)
    <div style="background:#fff; border:1px solid #C4B5FD; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/></svg>
            <span style="font-size:12px; font-weight:700; color:#534AB7; letter-spacing:0.05em; white-space:nowrap;">Registro de Cierre</span>
            <div style="flex:1; height:1.5px; background:#EDE9FE;"></div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:12px;">
            <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Motivo</p>
                <span style="font-weight:600; color:#3C3489;">{{ $cierre->motivoCierre?->nombre ?? '—' }}</span>
                @if($cierre->motivoCierre?->afecta_mora)
                <span style="display:inline-block; margin-left:4px; background:#FEF2F2; color:#B91C1C; font-size:9px; font-weight:700; border-radius:4px; padding:1px 6px;">Afecta indicadores</span>
                @endif
            </div>
            <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Cerrado por</p>
                <span style="font-weight:600; color:#3C3489;">{{ $cierre->cerradoPor?->name ?? '—' }}</span>
                <span style="display:block; font-size:11px; color:#AFA9EC;">{{ $cierre->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($cierre->observacion)
            <div style="grid-column:span 2; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Observación</p>
                <span style="color:#3C3489;">{{ $cierre->observacion }}</span>
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
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:8px; margin-top:12px;">
        <div style="background:#F0FDF4; border:1px solid #86EFAC; border-radius:10px; padding:12px; text-align:center;">
            <p style="font-size:9px; font-weight:700; color:#15803D; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 4px;">Cuotas Pagadas</p>
            <span style="font-size:22px; font-weight:900; color:#15803D;">{{ $pagadas }}</span>
            <p style="font-size:10px; color:#15803D; margin:2px 0 0;">Bs. {{ number_format($montoPagado, 2) }}</p>
        </div>
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:12px; text-align:center;">
            <p style="font-size:9px; font-weight:700; color:#B91C1C; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 4px;">Cuotas Pendientes</p>
            <span style="font-size:22px; font-weight:900; color:#B91C1C;">{{ $pendientes }}</span>
            <p style="font-size:10px; color:#B91C1C; margin:2px 0 0;">Bs. {{ number_format($montoPendiente, 2) }}</p>
        </div>
    </div>
    @endif

    {{-- Botones --}}
    @if(!$confirmandoReversion)
    <div class="cm-act-wrap">
        <button wire:click="backToList"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Regresar
        </button>
        <button wire:click="$set('confirmandoReversion', true)"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF3C7; color:#854F0B; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FCD34D; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Revertir Cierre
        </button>
    </div>
    @else
    <div style="background:#FEF3C7; border:1.5px solid #FCD34D; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#854F0B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:13px; font-weight:700; color:#854F0B;">Motivo de la reversión</span>
        </div>
        <textarea wire:model="motivoReversion" rows="3" placeholder="Explicá por qué se revierte el cierre..."
                  style="width:100%; display:block; background:#fff; border:1px solid #FCD34D; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
        @error('motivoReversion')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button wire:click="$set('confirmandoReversion', false)"
                    style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
            <button wire:click="revertir"
                    style="flex:1; padding:10px; background:#854F0B; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">Confirmar Reversión</button>
        </div>
    </div>
    @endif

</div>

@endif
</div>
