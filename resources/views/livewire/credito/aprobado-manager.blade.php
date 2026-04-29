<div>

<style>
.ap-wrap { overflow-x: auto; }
.ap-table { border-collapse: separate; border-spacing: 0; width: 100%; font-size: 13px; }
.ap-table .sticky-col { position: sticky; left: 0; z-index: 2; background: #fff; padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07); }
.ap-table thead .sticky-col { background: #F3F4F6; }
</style>

@php
$theadStyle = 'background:#F3F4F6; color:#374151; font-size:10px; font-weight:600; letter-spacing:0.5px;';
$filtros = ['' => 'Todos', 'aprobado' => 'Aprobados', 'rechazado' => 'Rechazados'];
$estilosActivos = [
    ''          => 'background:#EEEDFE; border-color:#7c3aed; color:#534AB7;',
    'aprobado'  => 'background:#F0FDF4; border-color:#16A34A; color:#15803D;',
    'rechazado' => 'background:#FEF2F2; border-color:#DC2626; color:#B91C1C;',
];
@endphp

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#F3F4F6;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0"
            style="background:rgba(55,65,81,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#374151;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#374151;">Aprobado / Rechazado</h1>
    <span class="text-sm font-medium" style="color:#374151;">{{ now()->format('d/m/Y') }}</span>
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
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = $p->estado === 'aprobado'; @endphp
<div class="max-w-2xl mx-auto" style="padding:0 0 40px;">

    @include('livewire.credito.partials.pedido-detail')

    {{-- Acciones según estado --}}
    @if (!$confirmandoRechazo)
    <div class="flex items-center gap-3" style="margin-top:8px;">

        {{-- Izquierda: siempre disponible → devolver a revisión --}}
        <button wire:click="devolverRevision"
                wire:confirm="¿Devolvés este pedido a Revisión? La nota de rechazo se eliminará."
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-colors"
                style="border:1.5px solid #7DD3FC; color:#0369A1; background:transparent;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            A Revisión
        </button>

        <div style="flex:1;"></div>

        {{-- Derecha: toggle según estado actual --}}
        @if ($p->estado === 'aprobado')
            <button wire:click="$set('confirmandoRechazo', true)"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors"
                    style="border:1.5px solid #FCA5A5; color:#B91C1C; background:transparent;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Rechazar
            </button>
        @elseif ($p->estado === 'rechazado')
            <button wire:click="aprobar"
                    wire:confirm="¿Confirmás la aprobación? La nota de rechazo se eliminará."
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-semibold text-white text-sm transition-colors"
                    style="background:#15803D;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Aprobar
            </button>
        @endif
    </div>

    @else
    {{-- Panel de rechazo (solo desde aprobado) --}}
    <div style="background:#FEF2F2; border:1px solid #FCA5A5; border-radius:14px; padding:16px; margin-top:8px;">
        <p class="font-semibold text-sm mb-3" style="color:#B91C1C;">Motivo del rechazo</p>
        <textarea wire:model="notaRechazo" rows="3"
                  placeholder="Explicá el motivo del rechazo..."
                  class="w-full text-sm border rounded-xl px-3 py-2 focus:outline-none bg-white"
                  style="border-color:#FCA5A5;"></textarea>
        @error('notaRechazo')<p class="text-xs mt-1" style="color:#B91C1C;">{{ $message }}</p>@enderror
        <div class="flex gap-3 mt-3 justify-end">
            <button wire:click="$set('confirmandoRechazo', false)"
                    class="px-4 py-2 text-sm rounded-xl"
                    style="background:#f3f4f6; color:#6b7280;">Cancelar</button>
            <button wire:click="rechazar"
                    class="px-5 py-2 text-white text-sm font-semibold rounded-xl"
                    style="background:#B91C1C;">Confirmar Rechazo</button>
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

    @foreach($filtros as $valor => $label)
    <button wire:click="$set('filtroEstado', '{{ $valor }}')"
            style="{{ $filtroEstado === $valor ? $estilosActivos[$valor] : 'background:transparent; border-color:#e5e7eb; color:#9ca3af;' }}
                   border:0.5px solid; border-radius:6px; padding:6px 12px; font-size:11px; font-weight:500;
                   cursor:pointer; white-space:nowrap;">
        {{ $label }}
    </button>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="ap-wrap">
    <table class="ap-table" style="min-width:580px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th class="sticky-col" style="border:0.5px solid #e5e7eb; font-weight:700; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; display:flex; align-items:center; justify-content:center;">Pedido</div>
                        <div style="flex:1; padding:8px 10px; text-align:center; display:flex; align-items:center; justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:90px;">Estado</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:120px;">Vendedor</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:110px;">Fecha</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:100px;">Total Bs.</th>
                <th style="padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb; width:70px;">Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            <tr wire:key="a-{{ $p->id }}">
                <td class="sticky-col" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#374151; display:flex; align-items:center; justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#111827;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px; color:#9ca3af;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <span class="inline-flex items-center text-xs font-semibold" style="{{ $p->estado_badge['style'] }} padding:3px 8px; border-radius:6px;">
                        {{ $p->estado_badge['label'] }}
                    </span>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#374151;">
                    {{ $p->vendedor->user->name ?? '—' }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#374151;">
                    {{ $p->updated_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-weight:700; color:#374151;">
                    {{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <button wire:click="ver({{ $p->id }})" title="Ver detalle"
                            class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-14 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Sin resultados</p>
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
