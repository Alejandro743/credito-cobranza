<div>

<style>
.cr-wrap { overflow-x: auto; }
.cr-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 13px; }
.cr-table .sticky-col { position: sticky; left: 0; z-index: 2; background: #fff; padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07); }
.cr-table thead .sticky-col { background: #DCFCE7; }
</style>

@php $theadStyle = 'background:#DCFCE7; color:#15803D; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#DCFCE7;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0"
            style="background:rgba(21,128,61,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#15803D;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#15803D;">En Espera de Aprobación</h1>
    <span class="text-sm font-medium" style="color:#15803D;">{{ now()->format('d/m/Y') }}</span>
</div>

@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
     class="fixed bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="p-4 sm:p-6">

{{-- ══ DETAIL ══ --}}
@if ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = false; $editable = false; @endphp
<div class="max-w-2xl mx-auto" style="padding:0 0 40px;">

    @include('livewire.credito.partials.pedido-detail')

    {{-- Acción --}}
    <div class="flex justify-end" style="margin-top:8px;">
        <button wire:click="tomarRevision({{ $p->id }})"
                wire:confirm="¿Tomás este pedido para revisión? Quedará asignado a vos."
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white text-sm transition-colors"
                style="background:#15803D;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Tomar para Revisión
        </button>
    </div>
</div>

{{-- ══ LIST ══ --}}
@else

{{-- Toolbar --}}
<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
    <div style="position:relative; flex-shrink:0; width:200px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #a7f3d0; border-radius:8px;
                      background:#f0fdf4; font-size:12px; outline:none;" />
    </div>
    <span class="text-xs font-medium" style="color:#15803D; margin-left:auto;">
        {{ $pedidos->total() }} pedido{{ $pedidos->total() !== 1 ? 's' : '' }} en espera
    </span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="cr-wrap">
    <table class="cr-table" style="min-width:580px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th class="sticky-col" style="border:0.5px solid #d1fae5; font-weight:700; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #a7f3d0; flex-shrink:0; display:flex; align-items:center; justify-content:center;">Pedido</div>
                        <div style="flex:1; padding:8px 10px; text-align:center; display:flex; align-items:center; justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:120px;">Vendedor</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:110px;">Fecha</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:100px;">Total Bs.</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #d1fae5; width:80px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr wire:key="e-{{ $p->id }}">
                <td class="sticky-col" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#15803D; display:flex; align-items:center; justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#166534;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px; color:#6ee7b7;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#374151;">
                    {{ $p->vendedor->user->name ?? '—' }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#374151;">
                    {{ $p->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-weight:700; color:#15803D;">
                    {{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <button wire:click="ver({{ $p->id }})" title="Ver detalle"
                            class="p-1.5 rounded-lg hover:bg-mint-50 text-mint-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-14 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Sin pedidos en espera</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pedidos->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $pedidos->links() }}</div>
    @endif
</div>
@endif

</div>
</div>
