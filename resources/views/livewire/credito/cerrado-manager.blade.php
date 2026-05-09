<div>

<style>
.cr-wrap { overflow-x: auto; }
.cr-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 13px; }
.cr-table .sticky-col { position: sticky; left: 0; z-index: 2; background: #fff; padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07); }
.cr-table thead .sticky-col { background: #F3F4F6; }
</style>

@php $theadStyle = 'background:#F3F4F6; color:#374151; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#F3F4F6;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0"
            style="background:rgba(55,65,81,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#374151;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#374151;">Créditos Cerrados</h1>
    <span class="text-sm font-medium" style="color:#374151;">{{ now()->format('d/m/Y') }}</span>
</div>

@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
     class="fixed bottom-5 right-5 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2"
     style="background:#374151;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="p-4 sm:p-6">

{{-- ══ DETAIL ══ --}}
@if ($mode === 'detail' && $pedidoDetalle)
@php
    $p      = $pedidoDetalle;
    $cierre = $p->cierre;
    $plan   = $p->planes->where('id', $cierre?->plan_pago_id)->first() ?? $p->planes->last();
@endphp
<div class="max-w-2xl mx-auto" style="padding:0 0 40px;">

    {{-- Encabezado --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px; padding-right:40px;">
        <button wire:click="backToList"
                style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid #e5e7eb; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0;">
            <svg width="14" height="14" fill="none" stroke="#374151" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/></svg>
            <span style="font-size:11px; font-weight:700; color:#374151;">Volver</span>
        </button>
        <div style="flex:1; text-align:center;">
            <p style="font-size:17px; font-weight:800; color:#111827; margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px; color:#9ca3af; margin:0;">{{ $p->numero }}</p>
        </div>
        <span style="font-size:11px; font-weight:700; padding:4px 10px; border-radius:10px; background:#F3F4F6; color:#374151;">CERRADO</span>
    </div>

    {{-- Card de cierre --}}
    @if($cierre)
    <div style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:14px; padding:14px 16px; margin-bottom:14px;">
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#9ca3af; margin:0 0 10px;">Registro de Cierre</p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:12px;">
            <div>
                <span style="color:#9ca3af; display:block; font-size:10px; text-transform:uppercase; letter-spacing:0.3px;">Motivo</span>
                <span style="font-weight:600; color:#374151;">{{ $cierre->motivoCierre?->nombre ?? '—' }}</span>
                @if($cierre->motivoCierre?->afecta_mora)
                <span style="margin-left:6px; font-size:9px; font-weight:700; padding:1px 5px; border-radius:8px; background:#FEF2F2; color:#B91C1C;">Afecta mora</span>
                @endif
            </div>
            <div>
                <span style="color:#9ca3af; display:block; font-size:10px; text-transform:uppercase; letter-spacing:0.3px;">Cerrado por</span>
                <span style="font-weight:600; color:#374151;">{{ $cierre->cerradoPor?->name ?? '—' }}</span>
            </div>
            <div>
                <span style="color:#9ca3af; display:block; font-size:10px; text-transform:uppercase; letter-spacing:0.3px;">Fecha cierre</span>
                <span style="font-weight:600; color:#374151;">{{ $cierre->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($cierre->observacion)
            <div style="grid-column:span 2;">
                <span style="color:#9ca3af; display:block; font-size:10px; text-transform:uppercase; letter-spacing:0.3px;">Observación</span>
                <span style="font-weight:500; color:#374151;">{{ $cierre->observacion }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Totales del plan cerrado --}}
    @if($plan)
    @php
        $cuotasReg     = $plan->cuotas->where('numero', '>', 0);
        $totalCuotas   = $cuotasReg->count();
        $pagadas        = $cuotasReg->where('estado', 'pagado')->count();
        $pendientes    = $totalCuotas - $pagadas;
        $montoPagado   = $cuotasReg->where('estado', 'pagado')->sum('monto');
        $montoPendiente= $cuotasReg->whereIn('estado', ['pendiente', 'vencido'])->sum('monto');
    @endphp
    <div style="background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:14px 16px; margin-bottom:14px;">
        <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#9ca3af; margin:0 0 10px;">Resumen del Plan de Pagos (V{{ $plan->version }})</p>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:8px; text-align:center;">
            <div style="padding:10px 8px; background:#F0FDF4; border-radius:10px;">
                <p style="font-size:18px; font-weight:800; color:#15803D; margin:0;">{{ $pagadas }}</p>
                <p style="font-size:10px; color:#9ca3af; margin:0;">Pagadas</p>
            </div>
            <div style="padding:10px 8px; background:#FEF2F2; border-radius:10px;">
                <p style="font-size:18px; font-weight:800; color:#B91C1C; margin:0;">{{ $pendientes }}</p>
                <p style="font-size:10px; color:#9ca3af; margin:0;">Pendientes</p>
            </div>
            <div style="padding:10px 8px; background:#F0FDF4; border-radius:10px;">
                <p style="font-size:13px; font-weight:700; color:#15803D; margin:0;">{{ number_format($montoPagado, 2) }}</p>
                <p style="font-size:10px; color:#9ca3af; margin:0;">Bs. Cobrado</p>
            </div>
            <div style="padding:10px 8px; background:#FEF2F2; border-radius:10px;">
                <p style="font-size:13px; font-weight:700; color:#B91C1C; margin:0;">{{ number_format($montoPendiente, 2) }}</p>
                <p style="font-size:10px; color:#9ca3af; margin:0;">Bs. Pendiente</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Acción: Revertir --}}
    @if(!$confirmandoReversion)
    <div style="margin-top:8px;">
        <button wire:click="$set('confirmandoReversion', true)"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm"
                style="border:1.5px solid #7DD3FC; color:#0369A1; background:transparent;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Revertir Cierre
        </button>
    </div>
    @else
    <div style="background:#F0F9FF; border:1px solid #7DD3FC; border-radius:14px; padding:16px; margin-top:8px;">
        <p class="font-semibold text-sm mb-3" style="color:#0369A1;">Motivo de la reversión</p>
        <textarea wire:model="motivoReversion" rows="3"
                  placeholder="Explicá por qué se revierte el cierre..."
                  class="w-full text-sm border rounded-xl px-3 py-2 focus:outline-none bg-white"
                  style="border-color:#7DD3FC;"></textarea>
        @error('motivoReversion')<p class="text-xs mt-1" style="color:#B91C1C;">{{ $message }}</p>@enderror
        <div class="flex gap-3 mt-3 justify-end">
            <button wire:click="$set('confirmandoReversion', false)"
                    class="px-4 py-2 text-sm rounded-xl"
                    style="background:#f3f4f6; color:#6b7280;">Cancelar</button>
            <button wire:click="revertir"
                    class="px-5 py-2 text-white text-sm font-semibold rounded-xl"
                    style="background:#0369A1;">Confirmar Reversión</button>
        </div>
    </div>
    @endif

</div>

{{-- ══ LIST ══ --}}
@else

<div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-bottom:16px;">
    <div style="position:relative; flex-shrink:0; width:200px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #e5e7eb; border-radius:8px;
                      background:#f9fafb; font-size:12px; outline:none;" />
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="cr-wrap">
    <table class="cr-table" style="min-width:600px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th class="sticky-col" style="border:0.5px solid #e5e7eb; font-weight:700; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; display:flex; align-items:center; justify-content:center;">Pedido</div>
                        <div style="flex:1; padding:8px 10px; text-align:center; display:flex; align-items:center; justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:130px;">Motivo Cierre</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:120px;">Vendedor</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:110px;">Cerrado el</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:100px;">Total Bs.</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:70px;">Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr wire:key="cr-{{ $p->id }}">
                <td class="sticky-col" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#374151; display:flex; align-items:center; justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#111827;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px; color:#9ca3af;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="padding:8px 10px; border:0.5px solid #e5e7eb; text-align:center;">
                    @if($p->cierre?->motivoCierre)
                    <span style="font-size:11px; font-weight:600; color:#374151;">{{ $p->cierre->motivoCierre->nombre }}</span>
                    @if($p->cierre->motivoCierre->afecta_mora)
                    <span style="display:block; font-size:9px; font-weight:700; padding:1px 5px; border-radius:8px; background:#FEF2F2; color:#B91C1C; margin-top:2px;">Afecta mora</span>
                    @endif
                    @else
                    <span style="color:#9ca3af; font-size:11px;">—</span>
                    @endif
                </td>
                <td style="padding:8px 10px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#374151;">
                    {{ $p->vendedor?->nombre_completo ?? '—' }}
                </td>
                <td style="padding:8px 10px; border:0.5px solid #e5e7eb; text-align:center; font-size:11px; color:#6b7280;">
                    {{ $p->cierre?->created_at->format('d/m/Y') ?? $p->updated_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 10px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; font-weight:600; color:#374151;">
                    {{ number_format($p->total_pagar, 2) }}
                </td>
                <td style="padding:8px 10px; border:0.5px solid #e5e7eb; text-align:center;">
                    <button wire:click="ver({{ $p->id }})"
                            style="padding:5px 10px; border-radius:8px; border:1px solid #D1D5DB; background:#F9FAFB; color:#374151; font-size:11px; font-weight:600; cursor:pointer;">
                        Ver
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:40px; text-align:center; color:#9ca3af;">Sin créditos cerrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pedidos->hasPages())
    <div style="padding:12px 16px; border-top:1px solid #f3f4f6;">{{ $pedidos->links() }}</div>
    @endif
</div>

@endif
</div>
</div>
