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
@elseif ($mode === 'detail' && $pedidoDetalle)
@php
    $p       = $pedidoDetalle;
    $plan    = $p->planPago;
    $estadoConfig = match($p->estado) {
        'en_espera' => ['color'=>'#B45309','bg'=>'#FFF9E3','border'=>'#FCD34D'],
        'revision'  => ['color'=>'#6D8196','bg'=>'#E8F0F7','border'=>'#CBCBCB'],
        'aprobado'  => ['color'=>'#15803D','bg'=>'#F0FDF4','border'=>'#86EFAC'],
        'rechazado' => ['color'=>'#B91C1C','bg'=>'#FEF2F2','border'=>'#FCA5A5'],
        'cerrado'   => ['color'=>'#6D8196','bg'=>'#F4F4F4','border'=>'#CBCBCB'],
        default     => ['color'=>'#6D8196','bg'=>'#F7F7F0','border'=>'#CBCBCB'],
    };
    $docs = [
        'Anverso CI'   => ['path'=>$p->doc_anverso_ci,  'campo'=>'doc_anverso_ci',  'prop'=>'docAnversoCi'],
        'Reverso CI'   => ['path'=>$p->doc_reverso_ci,  'campo'=>'doc_reverso_ci',  'prop'=>'docReversoCi'],
        'Anverso Doc.' => ['path'=>$p->doc_anverso_doc, 'campo'=>'doc_anverso_doc', 'prop'=>'docAnversoDoc'],
        'Reverso Doc.' => ['path'=>$p->doc_reverso_doc, 'campo'=>'doc_reverso_doc', 'prop'=>'docReversoDoc'],
        'Aviso de Luz' => ['path'=>$p->doc_aviso_luz,   'campo'=>'doc_aviso_luz',   'prop'=>'docAvisoLuz'],
    ];
    $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad);
    $fieldStyle  = 'background:#fff;border:1px solid #CBCBCB;border-radius:6px;padding:8px 10px;display:flex;align-items:center;gap:8px;';
    $iconBox     = 'width:28px;height:28px;border-radius:5px;background:#E8F0F7;display:flex;align-items:center;justify-content:center;flex-shrink:0;';
    $lblColor    = 'font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:#CBCBCB;margin-bottom:1px;';
    $valColor    = 'font-size:12px;font-weight:600;color:#4A4A4A;';
@endphp

<style>
@media(min-width:900px){
    .rev-grid    { grid-template-columns: 1fr 360px !important; }
    .rev-sidebar { position: sticky; top: 16px; }
}
</style>

<div style="max-width:1100px; margin:0 auto;">

    {{-- Barra superior --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <p style="font-size:18px; font-weight:800; color:#4A4A4A; margin:0; font-family:monospace; letter-spacing:1px;">{{ $p->numero }}</p>
        <span class="ds-badge" style="background:{{ $estadoConfig['bg'] }};color:{{ $estadoConfig['color'] }};border:1px solid {{ $estadoConfig['border'] }};">{{ ucfirst(str_replace('_',' ',$p->estado)) }}</span>
        <span style="font-size:11px;color:#CBCBCB;margin-left:auto;">{{ $p->created_at->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Grid principal --}}
    <div class="rev-grid" style="display:grid; grid-template-columns:1fr; gap:16px; align-items:start;">

        {{-- ────────── COLUMNA IZQUIERDA ────────── --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Pedido header --}}
            <div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; padding:16px 18px;">
                <p style="font-size:10px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.4px; margin:0 0 10px;">Solicitud de Crédito</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.3px; margin:0 0 3px;">Cliente</p>
                        <p style="font-size:14px; font-weight:700; color:#4A4A4A; margin:0;">{{ $p->cliente->nombre_completo }}</p>
                        @if($p->cliente->ci)<p style="font-size:11px; color:#6D8196; margin:2px 0 0;">CI: {{ $p->cliente->ci }}</p>@endif
                        @if($p->cliente->telefono)<p style="font-size:11px; color:#CBCBCB; margin:1px 0 0;">{{ $p->cliente->telefono }}</p>@endif
                    </div>
                    <div>
                        <p style="font-size:10px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.3px; margin:0 0 3px;">Vendedor</p>
                        <p style="font-size:13px; font-weight:600; color:#4A4A4A; margin:0;">{{ $p->vendedor->user->name ?? '—' }}</p>
                        <p style="font-size:11px; color:#CBCBCB; margin:2px 0 0;">{{ $p->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Notas --}}
            @if ($p->notas && $p->estado === 'rechazado')
            <div class="ds-confirm-panel danger" style="margin:0;"><p class="ds-confirm-panel-title">Motivo de Rechazo</p><p style="font-size:13px;color:#DC2626;">{{ $p->notas }}</p></div>
            @elseif ($p->notas)
            <div class="ds-confirm-panel neutral" style="margin:0;"><p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.3px;margin:0 0 4px;">Notas</p><p style="font-size:13px;color:#4A4A4A;">{{ $p->notas }}</p></div>
            @endif

            {{-- Productos --}}
            <div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                    <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0;">Productos del pedido</p>
                    <span style="font-size:10px; color:#CBCBCB;">{{ $p->items->count() }} {{ $p->items->count() === 1 ? 'producto' : 'productos' }}</span>
                </div>
                <div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; overflow:hidden;">
                    @foreach ($p->items as $item)
                    <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; {{ !$loop->last ? 'border-bottom:1px solid #F0F0E8;' : '' }}">
                        <div style="width:44px;height:44px;border-radius:7px;border:1px solid #CBCBCB;background:#F7F7F0;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            @if ($item->product?->foto_url)
                            <img src="{{ $item->product->foto_url }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:contain;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            @endif
                            <div style="{{ $item->product?->foto_url ? 'display:none;' : '' }}width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <svg width="18" height="18" fill="none" stroke="#CBCBCB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div style="flex:1; min-width:0;">
                            @if ($item->product?->code)
                            <span style="display:inline-block;padding:1px 6px;font-size:9px;font-weight:700;background:#E8F0F7;color:#6D8196;border-radius:3px;text-transform:uppercase;margin-bottom:2px;">{{ $item->product->code }}</span>
                            @endif
                            <p style="font-size:12px;font-weight:600;color:#4A4A4A;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->product?->name }}</p>
                            <p style="font-size:11px;color:#CBCBCB;margin:1px 0 0;">{{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}</p>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:13px;font-weight:700;color:#6D8196;margin:0;">Bs {{ number_format($item->subtotal, 2) }}</p>
                            @if ($item->puntos > 0)
                            <span style="font-size:9px;font-weight:600;background:#D1FAE5;color:#15803D;border-radius:3px;padding:1px 5px;">+{{ $item->puntos * $item->cantidad }} pts</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    <div style="display:flex;justify-content:flex-end;align-items:center;gap:10px;padding:10px 14px;border-top:2px solid #CBCBCB;background:#F7F7F0;">
                        @if ($totalPuntos > 0)
                        <span style="font-size:11px;font-weight:600;background:#D1FAE5;color:#15803D;border-radius:3px;padding:2px 8px;">+{{ number_format($totalPuntos) }} pts</span>
                        @endif
                        <p style="font-size:15px;font-weight:800;color:#4A4A4A;margin:0;">Total: Bs {{ number_format($p->total, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Plan de pagos --}}
            @if ($plan)
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0;">Plan de Pagos</p>
                    @if ($plan->version > 1)<span class="ds-badge ds-badge-pending">Reprogramado v{{ $plan->version }}</span>@endif
                    <span style="font-size:10px;color:#CBCBCB;margin-left:auto;">{{ $plan->cantidad_cuotas }} {{ $plan->cantidad_cuotas === 1 ? 'cuota' : 'cuotas' }}</span>
                </div>
                <div class="ds-table-card">
                    <table>
                        <thead><tr><th>Cuota</th><th>Vencimiento</th><th style="text-align:right;">Monto</th></tr></thead>
                        <tbody>
                        @foreach ($plan->cuotas as $cuota)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    @if ($cuota->numero === 0)
                                    <span style="width:22px;height:22px;border-radius:50%;background:#D1FAE5;color:#15803D;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">0</span>
                                    <span style="font-size:11px;color:#15803D;font-weight:600;">Inicial</span>
                                    @else
                                    <span style="width:22px;height:22px;border-radius:50%;background:#E8F0F7;color:#6D8196;font-size:10px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $cuota->numero }}</span>
                                    <span style="font-size:11px;color:#4A4A4A;">Cuota {{ $cuota->numero }}</span>
                                    @endif
                                </div>
                            </td>
                            <td style="color:#6D8196;">{{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                            <td style="text-align:right;font-weight:700;color:#6D8196;">Bs {{ number_format($cuota->monto, 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="font-weight:700;color:#4A4A4A;">Total a pagar</td>
                                <td style="text-align:right;font-weight:800;color:#4A4A4A;">Bs {{ number_format($plan->cuotas->sum('monto'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

        </div>{{-- /columna izquierda --}}

        {{-- ────────── COLUMNA DERECHA (sidebar) ────────── --}}
        <div class="rev-sidebar" style="display:flex; flex-direction:column; gap:16px;">

            {{-- Ficha del cliente --}}
            <div x-data="{ modal: false }">
                <div style="background:#fff;border:1px solid #CBCBCB;border-radius:10px;padding:12px 14px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0;">Datos del Cliente</p>
                        <button @click="modal = true" class="ds-btn ds-btn-ghost ds-btn-sm">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Ver ficha
                        </button>
                    </div>
                    <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:8px 0 2px;">{{ $p->cliente->nombre_completo }}</p>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        @if($p->cliente->ci)<span style="font-size:11px;color:#6D8196;">CI: {{ $p->cliente->ci }}</span>@endif
                        @if($p->cliente->telefono)<span style="font-size:11px;color:#CBCBCB;">{{ $p->cliente->telefono }}</span>@endif
                    </div>
                </div>
                {{-- Modal ficha --}}
                <div x-show="modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(74,74,74,.5);" @click.self="modal = false">
                    <div x-show="modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         style="background:#FFFFE3;border:1px solid #CBCBCB;border-radius:10px;width:100%;max-width:440px;overflow:hidden;position:relative;max-height:90vh;overflow-y:auto;">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #CBCBCB;background:#fff;">
                            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Ficha del cliente</p>
                            <button @click="modal = false" style="width:26px;height:26px;border-radius:6px;background:#F4F4F4;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <svg width="12" height="12" fill="none" stroke="#4A4A4A" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div style="padding:16px;">
                            <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0 0 8px;">Datos personales</p>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;">
                                <div style="{{ $fieldStyle }}"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div style="min-width:0;"><p style="{{ $lblColor }}">Nombre</p><p style="{{ $valColor }}">{{ $p->cliente->nombre_completo }}</p></div></div>
                                <div style="{{ $fieldStyle }}"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div><div><p style="{{ $lblColor }}">CI</p><p style="{{ $valColor }};font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</p></div></div>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:6px;">
                                <div style="{{ $fieldStyle }}"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><div><p style="{{ $lblColor }}">Teléfono</p><p style="{{ $valColor }}">{{ $p->cliente->telefono ?: '—' }}</p></div></div>
                                <div style="{{ $fieldStyle }}"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div><p style="{{ $lblColor }}">NIT</p><p style="{{ $valColor }}">{{ $p->cliente->nit ?: '—' }}</p></div></div>
                            </div>
                            <div style="{{ $fieldStyle }};margin-bottom:12px;"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><div style="min-width:0;"><p style="{{ $lblColor }}">Correo</p><p style="{{ $valColor }};word-break:break-all;">{{ $p->cliente->correo ?: '—' }}</p></div></div>
                            <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0 0 8px;">Dirección</p>
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;margin-bottom:6px;">
                                <div style="{{ $fieldStyle }}"><div style="min-width:0;flex:1;"><p style="{{ $lblColor }}">Ciudad</p><p style="{{ $valColor }}">{{ strtoupper($p->cliente->ciudad ?: '—') }}</p></div></div>
                                <div style="{{ $fieldStyle }}"><div style="min-width:0;flex:1;"><p style="{{ $lblColor }}">Provincia</p><p style="{{ $valColor }}">{{ strtoupper($p->cliente->provincia ?: '—') }}</p></div></div>
                                <div style="{{ $fieldStyle }}"><div style="min-width:0;flex:1;"><p style="{{ $lblColor }}">Municipio</p><p style="{{ $valColor }}">{{ strtoupper($p->cliente->municipio ?: '—') }}</p></div></div>
                            </div>
                            <div style="{{ $fieldStyle }}"><div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div><div style="min-width:0;"><p style="{{ $lblColor }}">Dirección</p><p style="{{ $valColor }}">{{ $p->cliente->direccion ?: '—' }}</p></div></div>
                        </div>
                    </div>
                </div>
            </div>{{-- /ficha cliente --}}

            {{-- Documentos --}}
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0;">Documentos</p>
                    <span style="font-size:10px;font-weight:600;color:#6D8196;background:#E8F0F7;border:1px solid #CBCBCB;border-radius:3px;padding:2px 8px;">Editable</span>
                </div>
                <div style="background:#fff;border:1px solid #CBCBCB;border-radius:10px;padding:12px;">
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                        @foreach ($docs as $label => $d)
                        @php $path = $d['path']; $campo = $d['campo']; $prop = $d['prop']; @endphp
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            @if ($path)
                            @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
                            <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
                                <div style="border:1.5px solid #10B981;background:#F0FDF4;border-radius:6px;padding:6px 4px;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;height:76px;">
                                    <div style="width:26px;height:26px;border-radius:5px;background:#D1FAE5;display:flex;align-items:center;justify-content:center;"><svg width="13" height="13" fill="none" stroke="#10B981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                    <span style="font-size:9px;font-weight:600;color:#15803D;line-height:1.2;">{{ $label }}</span>
                                    <span style="font-size:8px;color:#15803D;">↓ Ver</span>
                                </div>
                            </a>
                            @else
                            <div style="border:1.5px dashed #CBCBCB;background:#F7F7F0;border-radius:6px;padding:6px 4px;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;height:76px;">
                                <div style="width:26px;height:26px;border-radius:5px;background:#E8F0F7;display:flex;align-items:center;justify-content:center;"><svg width="13" height="13" fill="none" stroke="#CBCBCB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                <span style="font-size:9px;font-weight:500;color:#6D8196;line-height:1.2;">{{ $label }}</span>
                                <span style="font-size:8px;color:#CBCBCB;">Sin archivo</span>
                            </div>
                            @endif
                            <div x-data="{ subido: false }">
                                <input type="file" wire:model="{{ $prop }}" x-ref="fi_{{ $campo }}" accept="image/*,.pdf" style="display:none" x-on:livewire-upload-finish="$wire.subirDocumento('{{ $campo }}'); subido = true;">
                                <button @click="$refs['fi_{{ $campo }}'].click()" :style="{ background: subido ? '#D1FAE5' : '#FFFFE3' }"
                                        style="width:100%;color:#6D8196;border:1.5px solid #6D8196;font-size:10px;font-weight:600;border-radius:3px;padding:4px 6px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:4px;font-family:'Inter',sans-serif;">
                                    <svg x-show="!subido" style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <svg x-show="subido" x-cloak style="width:11px;height:11px;" fill="none" stroke="#15803D" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span x-text="subido ? 'Listo' : '{{ $path ? 'Reemplazar' : 'Subir' }}'"></span>
                                </button>
                                @error($prop)<p style="font-size:9px;color:#DC2626;margin-top:2px;">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Dirección de Entrega --}}
            @php $tieneEntrega = $p->entrega_direccion || $p->entrega_ciudad; @endphp
            <div x-data="{ modalDir: false }" @direccion-guardada.window="modalDir = false">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0;">Dirección de Entrega</p>
                    <button @click="modalDir = true" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                </div>
                <div style="background:#fff;border:1px solid #CBCBCB;border-radius:10px;padding:12px 14px;">
                    @if ($tieneEntrega)
                    @php $partesDir = array_filter([$p->entrega_direccion, $p->entrega_ciudad, $p->entrega_provincia, $p->entrega_municipio]); @endphp
                    <p style="font-size:13px;font-weight:600;color:#4A4A4A;margin:0;">{{ implode(', ', $partesDir) }}</p>
                    @if ($p->entrega_referencia)<p style="font-size:11px;color:#CBCBCB;margin:3px 0 0;">Ref: {{ $p->entrega_referencia }}</p>@endif
                    @else
                    <p style="font-size:12px;color:#CBCBCB;font-style:italic;margin:0;">Sin dirección registrada</p>
                    @endif
                </div>
                {{-- Modal edición dirección --}}
                <div x-show="modalDir" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(74,74,74,.5);" @click.self="modalDir = false">
                    <div x-show="modalDir" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         style="background:#FFFFE3;border:1px solid #CBCBCB;border-radius:10px;width:100%;max-width:440px;overflow:hidden;max-height:90vh;overflow-y:auto;">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #CBCBCB;background:#fff;">
                            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Dirección de Entrega</p>
                            <button @click="modalDir = false" style="width:26px;height:26px;border-radius:6px;background:#F4F4F4;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;"><svg width="12" height="12" fill="none" stroke="#4A4A4A" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        <div style="padding:16px;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                                <div class="ds-form-group" style="margin-bottom:0;"><label class="ds-form-label">Ciudad *</label>
                                    <select wire:model.live="editCiudad" style="width:100%;"><option value="">-- Seleccionar --</option>@foreach($ciudadesAll as $c)<option value="{{ $c->nombre }}">{{ $c->nombre }}</option>@endforeach</select>
                                    @error('editCiudad')<p class="ds-form-error">{{ $message }}</p>@enderror</div>
                                <div class="ds-form-group" style="margin-bottom:0;"><label class="ds-form-label">Provincia</label>
                                    <select wire:model.live="editProvincia" style="width:100%;" @disabled(!$editCiudad)><option value="">-- Seleccionar --</option>@foreach($editProvincias as $prov)<option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>@endforeach</select></div>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                                <div class="ds-form-group" style="margin-bottom:0;"><label class="ds-form-label">Municipio</label>
                                    <select wire:model.live="editMunicipio" style="width:100%;" @disabled(!$editProvincia)><option value="">-- Seleccionar --</option>@foreach($editMunicipios as $mun)<option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>@endforeach</select></div>
                                <div class="ds-form-group" style="margin-bottom:0;"><label class="ds-form-label">Dirección *</label>
                                    <input wire:model="editDireccion" type="text" placeholder="Calle y número" style="width:100%;">
                                    @error('editDireccion')<p class="ds-form-error">{{ $message }}</p>@enderror</div>
                            </div>
                            <div class="ds-form-group"><label class="ds-form-label">Referencia <span style="font-weight:400;text-transform:none;">(opcional)</span></label>
                                <input wire:model="editReferencia" type="text" placeholder="Portón azul, frente al parque..." style="width:100%;"></div>
                            <div style="display:flex;justify-content:flex-end;gap:8px;">
                                <button @click="modalDir = false" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
                                <button wire:click="guardarDireccion" wire:loading.attr="disabled" class="ds-btn ds-btn-primary ds-btn-sm">
                                    <span wire:loading.remove wire:target="guardarDireccion">Guardar</span>
                                    <span wire:loading wire:target="guardarDireccion">Guardando...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- /dirección --}}

            {{-- ── ACCIONES ── --}}
            @if (!$confirmandoRechazo)
            <div style="background:#fff;border:1px solid #CBCBCB;border-radius:10px;padding:14px 16px;">
                <p style="font-size:10px;font-weight:700;color:#6D8196;text-transform:uppercase;letter-spacing:.4px;margin:0 0 12px;">Acciones</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <button wire:click="devolverEspera" wire:confirm="¿Devolvés este pedido a En Espera?" class="ds-btn ds-btn-warning ds-btn-sm" style="width:100%;justify-content:center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Devolver a En Espera
                    </button>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                        <button wire:click="$set('confirmandoRechazo', true)" class="ds-btn ds-btn-danger" style="justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Rechazar
                        </button>
                        <button wire:click="aprobar" wire:confirm="¿Confirmás la aprobación de este pedido?" class="ds-btn ds-btn-success" style="justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Aprobar
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="ds-confirm-panel danger">
                <p class="ds-confirm-panel-title">Motivo del rechazo</p>
                <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..." style="width:100%;display:block;"></textarea>
                @error('notaRechazo')<p class="ds-form-error">{{ $message }}</p>@enderror
                <div class="ds-confirm-panel-actions">
                    <button wire:click="$set('confirmandoRechazo', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
                    <button wire:click="rechazar" class="ds-btn ds-btn-danger ds-btn-sm">Confirmar Rechazo</button>
                </div>
            </div>
            @endif

        </div>{{-- /sidebar --}}

    </div>{{-- /rev-grid --}}
</div>

@endif
</div>
