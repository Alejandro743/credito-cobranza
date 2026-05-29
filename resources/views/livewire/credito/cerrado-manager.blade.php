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
