<div class="min-h-screen bg-gray-50"
     x-data="{ toastShow: false, toastMsg: '' }"
     x-on:producto-agregado.window="toastMsg = $event.detail.nombre; toastShow = true; setTimeout(() => toastShow = false, 2200)">
<style>
.celda-cliente {
    background-color: #EEEDFE !important;
    border: 0.5px solid #CECBF6 !important;
    border-radius: 8px !important;
    padding: 8px 10px !important;
    display: block !important;
}
.celda-cliente-label {
    font-size: 9px !important;
    font-weight: 500 !important;
    color: #534AB7 !important;
    display: block !important;
    margin-bottom: 3px !important;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.celda-cliente-valor {
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #3C3489 !important;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

{{-- Toast --}}
<div x-show="toastShow" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-3 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0 translate-y-3 scale-95"
     class="fixed bottom-5 left-1/2 -translate-x-1/2 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 pointer-events-none whitespace-nowrap"
     style="background:#7c3aed;">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span x-text="toastMsg + ' agregado'"></span>
</div>

{{-- Flash pedido confirmado --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
     class="fixed top-4 left-1/2 -translate-x-1/2 z-50 text-white text-sm font-semibold px-6 py-3 rounded-2xl shadow-xl flex items-center gap-2"
     style="background:#0F6E56;">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- ══════════════════════════════════ STEPS: CLIENTE + OFERTA ══════════════ --}}
@if ($step === 'cliente' || $step === 'oferta')

{{-- ── TOPBAR ────────────────────────────────────────────────────────────── --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#FAEEDA;">
    {{-- Botón hamburguesa solo en móvil (sidebar oculto cuando no hay layout header) --}}
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0 transition-colors hover:opacity-75"
            style="background:rgba(99,56,6,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#633806;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#633806;">Registrar Nuevo Plan</h1>
    <span class="text-sm font-medium" style="color:#633806;">{{ now()->format('d/m/Y') }}</span>
</div>

{{-- ── TÍTULO ────────────────────────────────────────────────────────────── --}}
<div style="padding:14px 16px 10px;">
    <div style="background:#EEEDFE; border:1px solid #CECBF6; border-radius:14px; padding:14px 18px; text-align:center;">
        <h2 style="font-size:20px; font-weight:800; color:#3C3489; letter-spacing:-0.3px; margin:0;">REGISTRAR NUEVA SOLICITUD</h2>
    </div>
</div>

{{-- ── BUSCADOR DE CLIENTE ───────────────────────────────────────────────── --}}
<div class="bg-white px-3 pt-2.5 pb-2 border-b border-gray-100">
    @if ($sinListasActivas)
    <div class="flex items-center gap-2 py-1" style="color:#e24b4a;">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-xs font-medium">Sin listas activas asignadas</span>
    </div>
    @else
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live.debounce.300ms="searchCliente" type="text"
               placeholder="Buscar por CI, nombre o apellido..."
               class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border-0 focus:outline-none"
               style="background:#f3f4f6;">
        {{-- Dropdown --}}
        @if (count($resultadosCliente))
        <div class="absolute left-0 right-0 mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50">
            @foreach ($resultadosCliente as $c)
            <button wire:click="seleccionarCliente({{ $c['id'] }}, {{ $c['user_id'] }}, '{{ addslashes($c['nombre']) }}', '{{ addslashes($c['ci']) }}')"
                    class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors text-left border-b border-gray-50 last:border-0">
                <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 text-white"
                     style="background:#7c3aed;">
                    {{ strtoupper(substr($c['nombre'], 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate" style="color:#3C3489;">
                        {{ $c['ci'] }} — {{ $c['nombre'] }}
                    </p>
                </div>
            </button>
            @endforeach
        </div>
        @elseif (strlen(trim($searchCliente)) >= 2)
        <div class="absolute left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-gray-100 px-4 py-3 text-center text-gray-400 text-xs z-50">
            Sin resultados para "{{ $searchCliente }}"
        </div>
        @endif
    </div>
    @endif
</div>

{{-- ── STATS BAR — DESKTOP (6 celdas) ─────────────────────────────────── --}}
<div class="hidden md:block bg-white border-b border-gray-100 px-2 py-2">
    <div class="flex gap-1.5 items-stretch">

        {{-- CLIENTE --}}
        <div style="flex:2; min-width:0; background-color:#EEEDFE; border:0.5px solid rgba(206,203,246,0.6); border-radius:8px; padding:8px 10px; display:flex; flex-direction:column; justify-content:center; box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
            <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">CLIENTE</span>
            @if ($clienteId)
            <span style="font-size:13px; font-weight:500; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' - ' : '' }}{{ $clienteNombre }}</span>
            @else
            <span style="font-size:13px; font-weight:500; color:#d1d5db; display:block;">—</span>
            @endif
        </div>

        {{-- SALDO --}}
        <div class="flex flex-col items-center justify-center px-1 py-1.5"
             style="flex:1; background:#F8F7FF; border-radius:8px; border:0.5px solid rgba(206,203,246,0.6); box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
            <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Saldo Bs</p>
            <p class="font-bold text-xs leading-none tabular-nums" style="color:#3C3489;">
                @if ($clienteId) {{ number_format($total, 2) }} @else <span style="color:#d1d5db;">0.00</span> @endif
            </p>
        </div>

        {{-- CARGADO --}}
        <div class="flex flex-col items-center justify-center px-1 py-1.5"
             style="flex:1; background:#F8F7FF; border-radius:8px; border:0.5px solid rgba(206,203,246,0.6); box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
            <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Cargado</p>
            <p class="font-bold text-xs leading-none tabular-nums" style="color:#BA7517;">
                @if ($clienteId) {{ $cantidad }} @else <span style="color:#d1d5db;">0</span> @endif
            </p>
        </div>

        {{-- DISP. --}}
        <div class="flex flex-col items-center justify-center px-1 py-1.5"
             style="flex:1; background:#F8F7FF; border-radius:8px; border:0.5px solid rgba(206,203,246,0.6); box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
            <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Disp.</p>
            <p class="font-bold text-xs leading-none tabular-nums" style="color:#0F6E56;">
                @if ($clienteId) {{ count($oferta) }} @else <span style="color:#d1d5db;">—</span> @endif
            </p>
        </div>

        {{-- PUNTOS --}}
        <div class="flex flex-col items-center justify-center px-1 py-1.5"
             style="flex:1; background:#F8F7FF; border-radius:8px; border:0.5px solid rgba(206,203,246,0.6); box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
            <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Puntos</p>
            <p class="font-bold text-xs leading-none tabular-nums" style="color:#0F6E56;">
                @if ($clienteId) +{{ $puntos }} @else <span style="color:#d1d5db;">0</span> @endif
            </p>
        </div>

        {{-- CARRITO --}}
        <div class="relative flex items-center justify-center flex-shrink-0"
             style="width:44px; border-radius:8px; background:#f97316; box-shadow:0 2px 8px rgba(249,115,22,0.25);">
            <button wire:click="irResumen"
                    @disabled(empty($carrito))
                    class="w-full h-full flex items-center justify-center transition-all active:scale-95"
                    style="min-height:46px; {{ empty($carrito) ? 'opacity:0.45; cursor:default;' : 'cursor:pointer;' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </button>
            @if ($cantidad > 0)
            <span class="absolute flex items-center justify-center font-bold text-white leading-none"
                  style="top:-4px; right:-4px; min-width:16px; height:16px; border-radius:50%; background:#e24b4a; font-size:9px; padding:0 2px;">
                {{ $cantidad > 9 ? '9+' : $cantidad }}
            </span>
            @endif
        </div>

    </div>
</div>

{{-- ── STATS BAR — MÓVIL (grid 2×2 + carrito) ─────────────────────────── --}}
<div class="md:hidden bg-white border-b border-gray-100 px-2 py-2 flex flex-col gap-1.5">

    {{-- CLIENTE --}}
    <div style="background-color:#EEEDFE; border:0.5px solid rgba(206,203,246,0.6); border-radius:8px; padding:8px 10px; width:100%; box-shadow:0 2px 8px rgba(124,58,237,0.08),0 1px 3px rgba(0,0,0,0.05);">
        <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">CLIENTE</span>
        @if ($clienteId)
        <span style="font-size:13px; font-weight:500; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' - ' : '' }}{{ $clienteNombre }}</span>
        @else
        <span style="font-size:13px; font-weight:500; color:#d1d5db; display:block;">—</span>
        @endif
    </div>

    <div class="flex gap-1.5 items-stretch">

        {{-- Grid 2×2 --}}
        <div class="flex-1 grid grid-cols-2 gap-1.5">

            {{-- SALDO --}}
            <div class="flex flex-col items-center justify-center px-2 py-1.5"
                 style="background:#EEEDFE; border-radius:8px; border:0.5px solid #CECBF6;">
                <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Saldo Bs</p>
                <p class="font-bold text-xs leading-none tabular-nums" style="color:#3C3489;">
                    @if ($clienteId) {{ number_format($total, 2) }} @else <span style="color:#d1d5db;">0.00</span> @endif
                </p>
            </div>

            {{-- CARGADO --}}
            <div class="flex flex-col items-center justify-center px-2 py-1.5"
                 style="background:#EEEDFE; border-radius:8px; border:0.5px solid #CECBF6;">
                <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Cargado</p>
                <p class="font-bold text-xs leading-none tabular-nums" style="color:#BA7517;">
                    @if ($clienteId) {{ $cantidad }} @else <span style="color:#d1d5db;">0</span> @endif
                </p>
            </div>

            {{-- DISP. --}}
            <div class="flex flex-col items-center justify-center px-2 py-1.5"
                 style="background:#EEEDFE; border-radius:8px; border:0.5px solid #CECBF6;">
                <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Disp.</p>
                <p class="font-bold text-xs leading-none tabular-nums" style="color:#0F6E56;">
                    @if ($clienteId) {{ count($oferta) }} @else <span style="color:#d1d5db;">—</span> @endif
                </p>
            </div>

            {{-- PUNTOS --}}
            <div class="flex flex-col items-center justify-center px-2 py-1.5"
                 style="background:#EEEDFE; border-radius:8px; border:0.5px solid #CECBF6;">
                <p class="text-[9px] font-semibold uppercase tracking-wide leading-none mb-1" style="color:#534AB7;">Puntos</p>
                <p class="font-bold text-xs leading-none tabular-nums" style="color:#0F6E56;">
                    @if ($clienteId) +{{ $puntos }} @else <span style="color:#d1d5db;">0</span> @endif
                </p>
            </div>

        </div>

        {{-- CARRITO --}}
        <div class="relative flex items-center justify-center flex-shrink-0"
             style="width:44px; border-radius:8px; background:#f97316; box-shadow:0 2px 8px rgba(249,115,22,0.25);">
            <button wire:click="irResumen"
                    @disabled(empty($carrito))
                    class="w-full h-full flex items-center justify-center transition-all active:scale-95"
                    style="min-height:80px; {{ empty($carrito) ? 'opacity:0.45; cursor:default;' : 'cursor:pointer;' }}">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </button>
            @if ($cantidad > 0)
            <span class="absolute flex items-center justify-center font-bold text-white leading-none"
                  style="top:-4px; right:-4px; min-width:16px; height:16px; border-radius:50%; background:#e24b4a; font-size:9px; padding:0 2px;">
                {{ $cantidad > 9 ? '9+' : $cantidad }}
            </span>
            @endif
        </div>

    </div>{{-- /flex grid+carrito --}}
</div>

{{-- ── FILTROS + BUSCADOR (solo en step oferta con cliente) ──────────────── --}}
@if ($clienteId && !$sinListasComunes && $step === 'oferta')

{{-- Pills de lista --}}
<div class="bg-white border-b border-gray-100 px-3 py-2 flex items-center gap-2 flex-wrap">
    <span class="flex-shrink-0" style="font-size:11px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.03em;">LISTAS DE PRECIOS:</span>
    <button wire:click="$set('filterLista', '')"
            class="font-semibold transition-all flex-shrink-0"
            style="font-size:12px; padding:4px 14px; border-radius:6px;
                   {{ $filterLista === '' ? 'background:#7c3aed; color:#fff; border:1.5px solid #7c3aed;' : 'background:#fff; color:#7c3aed; border:1.5px solid #7c3aed;' }}">
        Todos
    </button>
    @foreach ($listasInfo as $lid => $info)
    <button wire:click="$set('filterLista', '{{ $lid }}')"
            class="font-semibold transition-all flex-shrink-0"
            style="font-size:12px; padding:4px 14px; border-radius:6px;
                   {{ $filterLista === (string)$lid ? 'background:#7c3aed; color:#fff; border:1.5px solid #7c3aed;' : 'background:#fff; color:#7c3aed; border:1.5px solid #7c3aed;' }}">
        {{ $info['nombre'] }}
    </button>
    @endforeach
</div>

{{-- Buscador de producto --}}
<div class="bg-white px-3 py-2 border-b border-gray-100">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live.debounce.300ms="searchProducto" type="text"
               placeholder="Buscar producto..."
               class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border-0 focus:outline-none"
               style="background:#f3f4f6;">
    </div>
</div>
@endif

{{-- ── SIN CLIENTE: empty state ─────────────────────────────────────────── --}}
@if ($step === 'cliente')
<div class="flex flex-col items-center justify-center py-20 text-center px-4">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4" style="background:#FAEEDA;">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#633806;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
    </div>
    <p class="font-bold text-gray-700">Seleccioná un cliente</p>
    <p class="text-sm text-gray-400 mt-1">Buscá por nombre o CI en el campo de arriba</p>
</div>
@endif

{{-- ── STEP OFERTA ──────────────────────────────────────────────────────── --}}
@if ($step === 'oferta')

@if ($sinListasComunes)
<div class="flex flex-col items-center justify-center py-16 text-center px-4">
    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:#EEEDFE;">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#7c3aed;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
    </div>
    <p class="font-bold text-gray-700">Sin listas compartidas</p>
    <p class="text-gray-400 text-sm mt-1 max-w-xs">Este cliente no tiene acceso a listas compartidas contigo.</p>
    <button wire:click="cambiarCliente"
            class="mt-5 px-5 py-2 text-white text-sm font-semibold rounded-xl transition-colors"
            style="background:#7c3aed;">
        Buscar otro cliente
    </button>
</div>
@else

{{-- Separador Carrito de Productos --}}
<div class="px-3 pt-3 pb-1 flex items-center gap-3">
    <span class="text-xs font-bold uppercase tracking-widest" style="color:#534AB7;">Carrito de Productos</span>
    <div class="flex-1 h-px" style="background:#CECBF6;"></div>
</div>

<div class="px-3 pt-2 pb-10 space-y-5">
    @forelse ($ofertaPorLista as $listaId => $productos)
    @php $listaNombre = $listasInfo[(string)$listaId]['nombre'] ?? ''; @endphp
    <div>

        {{-- Header de lista --}}
        <div class="flex items-center justify-between mb-2 px-0.5">
            <span class="text-gray-500" style="font-size:13px;">{{ $listaNombre }}</span>
            <span class="text-gray-400" style="font-size:11px;">{{ $productos->count() }} productos</span>
        </div>

        {{-- Grid de cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:16px;">
            @foreach ($productos as $p)
            @php
                $pid       = (string)$p['product_id'];
                $enCarrito = isset($carrito[$pid]);
                $qty       = $enCarrito ? $carrito[$pid]['cantidad'] : 0;
            @endphp
            <div class="flex flex-col w-full"
                 x-data="{ n: 0, precio: @js((float)$p['precio']), puntos: @js((int)$p['puntos']), maxStock: @js((int)$p['stock']) }"
                 x-on:carrito-vaciado.window="n = 0"
                 style="background:#fff; border:none; border-radius:12px; overflow:hidden; box-shadow:2px 6px 20px rgba(0,0,0,0.22);"
                 wire:key="prod-{{ $pid }}">

                {{-- FOTO --}}
                <div class="relative flex-shrink-0"
                     style="width:100%; height:160px; overflow:hidden; background:#ffffff; border-bottom:0.5px solid #e5e7eb;">
                    @if ($p['image'])
                    <img src="{{ $p['image'] }}" alt="{{ $p['nombre'] }}"
                         style="width:100%; height:100%; object-fit:contain; object-position:center; display:block;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    @endif
                    <div class="w-full h-full items-center justify-center" style="display:{{ $p['image'] ? 'none' : 'flex' }};">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#CECBF6;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Badge naranja top-left: cantidad en carrito --}}
                    @if ($qty > 0)
                    <span class="absolute flex items-center justify-center font-bold text-white leading-none"
                          style="top:8px; left:8px; width:26px; height:26px; border-radius:50%; background:#f97316; font-size:12px;">{{ $qty }}</span>
                    @endif
                </div>

                {{-- CUERPO --}}
                <div style="padding:10px 12px 8px; display:flex; flex-direction:column; gap:6px; flex:1;">

                    {{-- Recuadro 1: Info del producto --}}
                    <div style="background:#F8F7FF; border-radius:8px; padding:8px 10px; display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; font-weight:700; color:#534AB7;">{{ $p['code'] ?? '' }}</span>
                        <span style="font-size:11px; font-weight:700; color:#3C3489; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $p['nombre'] }}">{{ $p['nombre'] }}</span>
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:6px;">
                            <span style="font-size:10px; font-weight:500; color:#534AB7;">Precio Bs</span>
                            <span style="font-size:14px; font-weight:500; color:#7c3aed;">{{ number_format($p['precio'], 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:6px;">
                            <span style="font-size:10px; font-weight:500; color:#534AB7;">Puntos</span>
                            <span style="font-size:11px; font-weight:500; color:#0F6E56;">{{ $p['puntos'] }}</span>
                        </div>
                    </div>

                    {{-- Recuadro 2: Totalizados en tiempo real (Alpine) --}}
                    <div style="background:#EEEDFE; border-radius:8px; padding:8px 10px; display:flex; flex-direction:column; gap:3px;">
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:6px;">
                            <span style="font-size:11px; font-weight:500; color:#534AB7;">Total Bs:</span>
                            <span x-text="(precio * n).toFixed(2)" style="font-size:15px; font-weight:500; color:#3C3489;">{{ number_format($p['precio'] * $qty, 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:baseline; gap:6px;">
                            <span style="font-size:11px; font-weight:500; color:#534AB7;">Total Puntos:</span>
                            <span x-text="'+' + (puntos * n) + ' pts'" style="font-size:13px; font-weight:500; color:#0F6E56;">+{{ $p['puntos'] * $qty }} pts</span>
                        </div>
                    </div>

                </div>

                {{-- PIE --}}
                <div style="padding:8px 12px 12px; display:flex; align-items:center; gap:6px; flex-shrink:0;">
                    {{-- [−] --}}
                    <button @click="n > 0 ? n-- : null"
                            class="flex items-center justify-center font-bold flex-shrink-0 transition-colors"
                            :style="n === 0 ? 'opacity:0.35; cursor:default;' : ''"
                            style="width:28px; height:28px; border-radius:50%; background:#F8F7FF; border:1px solid #CECBF6; color:#534AB7; font-size:16px; line-height:1;">−</button>
                    {{-- num --}}
                    <span x-text="n" style="font-size:13px; font-weight:500; color:#3C3489; min-width:18px; text-align:center; flex-shrink:0;">{{ $qty }}</span>
                    {{-- [+] --}}
                    <button @click="n < maxStock ? n++ : null"
                            class="flex items-center justify-center font-bold flex-shrink-0 transition-colors"
                            style="width:28px; height:28px; border-radius:50%; background:#F8F7FF; border:1px solid #CECBF6; color:#534AB7; font-size:16px; line-height:1;">+</button>

                    {{-- Botón Agregar (outline, siempre visible) --}}
                    <button @click="if(n === 0) n = 1; $wire.agregar({{ $p['product_id'] }}, n).then(() => n = 0)"
                            class="flex items-center justify-center gap-1 transition-all active:scale-95 flex-1"
                            style="background:#fff; border:1.5px solid #7c3aed; color:#7c3aed; border-radius:8px; padding:8px 10px; font-size:12px; font-weight:500;">
                        <svg style="width:15px; height:15px; flex-shrink:0;" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Agregar
                    </button>

                    {{-- Botón Basurita (solo cuando hay qty en carrito) --}}
                    @if ($qty > 0)
                    <button wire:click="quitar({{ $p['product_id'] }})"
                            class="flex items-center justify-center flex-shrink-0 transition-all active:scale-95"
                            style="width:34px; height:34px; border-radius:8px; background:#fff; border:1.5px solid #e24b4a;">
                        <svg style="width:15px; height:15px;" fill="none" stroke="#e24b4a" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
        <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-medium text-sm">No hay productos disponibles</p>
    </div>
    @endforelse
</div>

{{-- Botón flotante carrito --}}
@if (!$sinListasComunes && $cantidad > 0)
<div style="position:fixed; bottom:24px; right:20px; z-index:50;">
    <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
        <button wire:click="irResumen"
                style="width:64px; height:64px; border-radius:50%; background:#F97316; border:none; cursor:pointer;
                       display:flex; align-items:center; justify-content:center;
                       box-shadow:0 4px 18px rgba(249,115,22,0.5); position:relative;">
            <svg style="width:28px;height:28px;" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span style="position:absolute; top:-4px; right:-4px; min-width:20px; height:20px;
                         background:#ef4444; color:#fff; font-size:10px; font-weight:700;
                         border-radius:999px; display:flex; align-items:center; justify-content:center;
                         padding:0 4px; border:2px solid #fff; line-height:1;">{{ $cantidad }}</span>
        </button>
        <span style="font-size:10px; font-weight:700; color:#F97316; letter-spacing:0.02em;">Ir carrito</span>
    </div>
</div>
@endif

@endif {{-- sinListasComunes --}}
@endif {{-- step oferta --}}
@endif {{-- step cliente || oferta --}}


{{-- ═════════════════════════════════════════════ STEP: RESUMEN ══════════ --}}
@if ($step === 'resumen')
<div>

{{-- Topbar --}}
<div class="px-4 py-3 flex items-center justify-between" style="background:#FAEEDA;">
    <h1 class="font-bold text-base" style="color:#633806;">Registrar Nuevo Plan</h1>
    <span class="text-sm font-medium" style="color:#854F0B;">{{ now()->format('d/m/Y') }}</span>
</div>

<div class="max-w-2xl mx-auto px-4 pb-10">

    {{-- Card título VERIFICACIÓN con botones adentro --}}
    <div style="background:#EEEDFE; border:1px solid #CECBF6; border-radius:14px; padding:14px 18px; margin-bottom:16px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
            <button wire:click="volverOferta"
                    style="background:#fff; border:1.5px solid #CECBF6; border-radius:8px; padding:5px 10px; display:flex; align-items:center; gap:5px; flex-shrink:0; cursor:pointer;">
                <svg width="13" height="13" fill="none" stroke="#534AB7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <svg width="13" height="13" fill="none" stroke="#534AB7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-9-4h4"/>
                </svg>
                <span style="font-size:11px; font-weight:500; color:#534AB7;">Carrito</span>
            </button>
            <div style="flex:1;"></div>
            <button wire:click="irEntrega"
                    @disabled(empty($carrito))
                    style="{{ !empty($carrito) ? 'background:#fff; border:1.5px solid #D97706; cursor:pointer;' : 'background:#f3f4f6; border:1.5px solid #d1d5db; opacity:0.5; cursor:not-allowed;' }} border-radius:8px; padding:5px 10px; display:flex; align-items:center; gap:5px; flex-shrink:0;">
                <span style="font-size:11px; font-weight:500; color:{{ !empty($carrito) ? '#854F0B' : '#9ca3af' }};">Entrega</span>
                <svg width="14" height="14" fill="none" stroke="{{ !empty($carrito) ? '#854F0B' : '#9ca3af' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5" stroke-width="1.8"/>
                    <circle cx="18.5" cy="18.5" r="2.5" stroke-width="1.8"/>
                </svg>
                <svg width="12" height="12" fill="none" stroke="{{ !empty($carrito) ? '#854F0B' : '#9ca3af' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <h2 style="font-size:20px; font-weight:800; color:#3C3489; letter-spacing:-0.3px; margin:0; text-align:center;">VERIFICACIÓN</h2>
        <p style="font-size:11px; color:#534AB7; margin:4px 0 0; text-align:center;">Revisá tu pedido antes de continuar</p>
    </div>


    {{-- Card cliente --}}
    <div style="background-color:#EEEDFE; border:0.5px solid #CECBF6; border-radius:8px; padding:8px 10px; margin-bottom:12px;">
        <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">CLIENTE</span>
        <span style="font-size:13px; font-weight:500; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' - ' : '' }}{{ $clienteNombre }}</span>
    </div>

    {{-- Separador Resumen Productos --}}
    <div class="flex items-center gap-3 mb-3">
        <span class="text-xs font-bold uppercase tracking-widest" style="color:#534AB7;">Resumen Productos</span>
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
    </div>


    {{-- Sección productos --}}
    <p class="text-[11px] font-semibold uppercase tracking-wide mb-2" style="color:#9ca3af;">Productos del Pedido</p>

    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:2px 6px 20px rgba(0,0,0,0.22);">
        @foreach ($carrito as $pid => $item)
        <div class="flex items-center gap-2.5 px-3 py-2.5" wire:key="res-{{ $pid }}"
             style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">

            {{-- Foto 44px --}}
            <div class="flex-shrink-0 overflow-hidden" style="width:44px;height:44px;border-radius:8px;border:0.5px solid #e5e7eb;background:#fff;">
                @if ($item['image'])
                <img src="{{ $item['image'] }}" alt="{{ $item['nombre'] }}"
                     style="width:100%;height:100%;object-fit:contain;">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#CECBF6;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <span class="inline-block px-1.5 py-0.5 text-[9px] font-bold rounded uppercase tracking-wide mb-0.5"
                      style="background:#EEEDFE; color:#534AB7;">
                    {{ $item['code'] ?? '' }}
                </span>
                <p class="text-xs font-medium text-gray-800 truncate leading-tight">{{ $item['nombre'] }}</p>
                <p class="text-[10px] text-gray-400 leading-tight">
                    {{ $item['cantidad'] }} × Bs {{ number_format($item['precio'], 2) }}
                </p>
            </div>

            {{-- Total + puntos --}}
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold" style="color:#7c3aed;">Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                @if ($item['puntos'] > 0)
                <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full"
                      style="background:#E1F5EE; color:#0F6E56;">+{{ $item['puntos'] * $item['cantidad'] }} pts</span>
                @endif
            </div>

            {{-- Eliminar --}}
            <button wire:click="quitar({{ $item['product_id'] }})"
                    class="w-7 h-7 flex items-center justify-center flex-shrink-0 transition-colors hover:opacity-80"
                    style="background:#fef2f2; border-radius:6px;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        @endforeach

        {{-- Fila total --}}
        <div class="flex justify-end items-center gap-2 px-3 py-2.5" style="border-top:0.5px solid #e5e7eb;">
            <p class="font-bold" style="font-size:16px; color:#3C3489;">Total: Bs {{ number_format($total, 2) }}</p>
            <span class="font-semibold px-2 py-0.5 rounded-full" style="font-size:12px; background:#E1F5EE; color:#0F6E56;">+{{ number_format($puntos) }} pts</span>
        </div>
    </div>

    {{-- Separador Plan de Pagos --}}
    <div class="flex items-center gap-3 mb-4 mt-2">
        <span class="text-xs font-bold uppercase tracking-widest" style="color:#534AB7;">Plan de Pagos</span>
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
    </div>

    @if ($simulacion && !empty($simulacion['cuotas_preview']))

    {{-- Título cuotas --}}
    <p class="text-[11px] font-semibold uppercase tracking-wide mb-2" style="color:#9ca3af;">Detalle de Cuotas</p>

    {{-- Tabla cuotas --}}
    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:2px 6px 20px rgba(0,0,0,0.22);">

        {{-- Header --}}
        <div class="grid grid-cols-3 px-3 py-2" style="background:#F8F7FF;">
            <p class="text-[10px] font-semibold text-gray-500">Cuota</p>
            <p class="text-[10px] font-semibold text-gray-500">Fecha Venc.</p>
            <p class="text-[10px] font-semibold text-gray-500 text-right">Monto</p>
        </div>

        {{-- Filas --}}
        @foreach ($simulacion['cuotas_preview'] as $cuota)
        <div class="grid grid-cols-3 items-center px-3 py-2.5"
             style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">

            <div class="flex items-center gap-1.5">
                @if ($cuota['tipo'] === 'inicial')
                <span class="flex-shrink-0 flex items-center justify-center font-bold text-[9px] leading-none"
                      style="width:26px;height:26px;border-radius:50%;background:#E1F5EE;color:#0F6E56;">0</span>
                <div class="min-w-0">
                    <p class="text-[11px] font-medium text-gray-700 leading-tight truncate">Cuota Inicial</p>
                    <span class="text-[8px] font-bold px-1 py-0.5 rounded uppercase leading-none"
                          style="background:#E1F5EE;color:#0F6E56;">Inicial</span>
                </div>
                @else
                <span class="flex-shrink-0 flex items-center justify-center font-bold text-[10px] leading-none"
                      style="width:26px;height:26px;border-radius:50%;background:#EEEDFE;color:#534AB7;">{{ $cuota['numero'] }}</span>
                <p class="text-[11px] font-medium text-gray-700 truncate">Cuota {{ $cuota['numero'] }}</p>
                @endif
            </div>

            @if ($cuota['tipo'] === 'inicial')
            <p class="text-[11px] font-medium" style="color:#0F6E56;">{{ $cuota['fecha'] }}</p>
            @else
            <p class="text-[11px] text-gray-500">{{ $cuota['fecha'] }}</p>
            @endif

            <p class="text-sm font-bold text-right" style="color:#7c3aed;">Bs {{ number_format($cuota['monto'], 2) }}</p>
        </div>
        @endforeach

    </div>
    @endif

</div>
</div>
@endif {{-- step resumen --}}


{{-- ═════════════════════════════════════════════ STEP: ENTREGA ══════════ --}}
@if ($step === 'entrega')
<div style="background:#F5F4FC; min-height:100vh;">

    {{-- Franja superior (igual que todas las páginas) --}}
    <div class="px-4 py-3 flex items-center justify-between" style="background:#FAEEDA;">
        <h1 class="font-bold text-base" style="color:#633806;">Registrar Nuevo Plan</h1>
        <span class="text-sm font-medium" style="color:#854F0B;">{{ now()->format('d/m/Y') }}</span>
    </div>

    <div class="max-w-2xl mx-auto px-4 pt-4 pb-10">

    {{-- Card título COMPLEMENTO --}}
    <div style="background:#EEEDFE; border:1px solid #CECBF6; border-radius:14px; padding:14px 18px; margin-bottom:16px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
            <button wire:click="volverResumen"
                    style="background:#fff; border:1.5px solid #CECBF6; border-radius:8px; padding:5px 10px; display:flex; align-items:center; gap:5px; flex-shrink:0; cursor:pointer;">
                <svg width="13" height="13" fill="none" stroke="#534AB7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <svg width="13" height="13" fill="none" stroke="#534AB7" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
                </svg>
                <span style="font-size:11px; font-weight:500; color:#534AB7;">Verificación</span>
            </button>
        </div>
        <h2 style="font-size:20px; font-weight:800; color:#3C3489; letter-spacing:-0.3px; margin:0; text-align:center;">COMPLEMENTO</h2>
        <p style="font-size:11px; color:#534AB7; margin:4px 0 0; text-align:center;">Documentación y Entrega</p>
    </div>

        {{-- Card Cliente --}}
        <div style="background:#EEEDFE; border-radius:8px; padding:8px 12px; margin-bottom:16px; box-shadow:0 2px 8px rgba(124,58,237,0.08);">
            <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:2px; text-transform:uppercase; letter-spacing:0.04em;">CLIENTE</span>
            <span style="font-size:13px; font-weight:500; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' - ' : '' }}{{ $clienteNombre }}</span>
        </div>

        {{-- Separador: Documentación del Plan --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
            <span style="font-size:11px; font-weight:500; color:#534AB7; letter-spacing:0.5px; white-space:nowrap;">DOCUMENTACIÓN DEL PLAN</span>
            <div style="flex:1; height:0.5px; background:#CECBF6;"></div>
        </div>

        {{-- Card Documentación --}}
        <div style="background:white; border-radius:12px; padding:12px; box-shadow:2px 4px 12px rgba(0,0,0,0.08); margin-bottom:20px;">
            <div class="doc-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
            <style>@media(min-width:480px){.doc-grid{grid-template-columns:repeat(5,1fr)!important;}}</style>

                {{-- 1. Anverso CI --}}
                <label style="cursor:pointer;">
                    <div style="{{ $docAnversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #CECBF6; background:#FAFAFE;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                        <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                            @if($docAnversoCi)
                            <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                            @endif
                        </div>
                        <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:{{ $docAnversoCi ? '#0F6E56' : '#534AB7' }};">Anverso CI</span>
                        <span style="font-size:8px; color:{{ $docAnversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoCi ? 'OK' : 'JPG/PDF' }}</span>
                    </div>
                    <input type="file" wire:model="docAnversoCi" accept="image/*,application/pdf" class="hidden">
                </label>

                {{-- 2. Reverso CI --}}
                <label style="cursor:pointer;">
                    <div style="{{ $docReversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #CECBF6; background:#FAFAFE;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                        <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                            @if($docReversoCi)
                            <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                            @endif
                        </div>
                        <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:{{ $docReversoCi ? '#0F6E56' : '#534AB7' }};">Reverso CI</span>
                        <span style="font-size:8px; color:{{ $docReversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoCi ? 'OK' : 'JPG/PDF' }}</span>
                    </div>
                    <input type="file" wire:model="docReversoCi" accept="image/*,application/pdf" class="hidden">
                </label>

                {{-- 3. Anverso Documento --}}
                <label style="cursor:pointer;">
                    <div style="{{ $docAnversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #CECBF6; background:#FAFAFE;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                        <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                            @if($docAnversoDoc)
                            <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:{{ $docAnversoDoc ? '#0F6E56' : '#534AB7' }};">Anverso Doc</span>
                        <span style="font-size:8px; color:{{ $docAnversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoDoc ? 'OK' : 'JPG/PDF' }}</span>
                    </div>
                    <input type="file" wire:model="docAnversoDoc" accept="image/*,application/pdf" class="hidden">
                </label>

                {{-- 4. Reverso Documento --}}
                <label style="cursor:pointer;">
                    <div style="{{ $docReversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #CECBF6; background:#FAFAFE;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                        <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                            @if($docReversoDoc)
                            <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:{{ $docReversoDoc ? '#0F6E56' : '#534AB7' }};">Reverso Doc</span>
                        <span style="font-size:8px; color:{{ $docReversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoDoc ? 'OK' : 'JPG/PDF' }}</span>
                    </div>
                    <input type="file" wire:model="docReversoDoc" accept="image/*,application/pdf" class="hidden">
                </label>

                {{-- 5. Aviso de Luz --}}
                <label style="cursor:pointer;">
                    <div style="{{ $docAvisoLuz ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #CECBF6; background:#FAFAFE;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                        <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAvisoLuz ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                            @if($docAvisoLuz)
                            <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @endif
                        </div>
                        <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:{{ $docAvisoLuz ? '#0F6E56' : '#534AB7' }};">Aviso Luz</span>
                        <span style="font-size:8px; color:{{ $docAvisoLuz ? '#0F6E56' : '#AFA9EC' }};">{{ $docAvisoLuz ? 'OK' : 'JPG/PDF' }}</span>
                    </div>
                    <input type="file" wire:model="docAvisoLuz" accept="image/*,application/pdf" class="hidden">
                </label>

            </div>
        </div>

        {{-- Separador: Dirección de Entrega --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
            <span style="font-size:11px; font-weight:500; color:#534AB7; letter-spacing:0.5px; white-space:nowrap;">DIRECCIÓN DE ENTREGA</span>
            <div style="flex:1; height:0.5px; background:#CECBF6;"></div>
        </div>

        {{-- Card Dirección --}}
        <div style="background:white; border-radius:12px; padding:12px; box-shadow:2px 4px 12px rgba(0,0,0,0.08); margin-bottom:20px;">

            {{-- Toggle --}}
            <div class="grid grid-cols-2 gap-2 mb-3">
                <button wire:click="$set('tipoEntrega','domicilio')" type="button"
                        style="{{ $tipoEntrega === 'domicilio' ? 'background:#EEEDFE; border:1.5px solid #7c3aed; color:#3C3489;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s;">
                    🏠 Domicilio
                </button>
                <button wire:click="$set('tipoEntrega','nuevo')" type="button"
                        style="{{ $tipoEntrega === 'nuevo' ? 'background:#FEF3C7; border:1.5px solid #D97706; color:#92400E;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s;">
                    📍 Nuevo lugar
                </button>
            </div>

            {{-- Campos Domicilio (readonly) --}}
            @if ($tipoEntrega === 'domicilio')
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Ciudad</p>
                    <div style="background:#f3f4f6; border-radius:6px; padding:7px 10px; font-size:12px; color:#374151; font-weight:500;">{{ $entregaClienteCiudad ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Provincia</p>
                    <div style="background:#f3f4f6; border-radius:6px; padding:7px 10px; font-size:12px; color:#374151; font-weight:500;">{{ $entregaClienteProvincia ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Municipio</p>
                    <div style="background:#f3f4f6; border-radius:6px; padding:7px 10px; font-size:12px; color:#374151; font-weight:500;">{{ $entregaClienteMunicipio ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Dirección</p>
                    <div style="background:#f3f4f6; border-radius:6px; padding:7px 10px; font-size:12px; color:#374151; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaClienteDireccion ?: '—' }}</div>
                </div>
            </div>
            @error('entregaClienteDireccion')
            <p style="font-size:11px; color:#ef4444; margin-bottom:8px;">{{ $message }}</p>
            @enderror
            @endif

            {{-- Campos Nuevo lugar (editables) --}}
            @if ($tipoEntrega === 'nuevo')
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Ciudad *</p>
                    <select wire:model.live="entregaNuevoCiudad" style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:7px 10px; font-size:12px; outline:none;">
                        <option value="">-- Seleccionar --</option>
                        @foreach($ciudadesAll as $c)
                        <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entregaNuevoCiudad')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Provincia</p>
                    <select wire:model.live="entregaNuevaProvincia" style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:7px 10px; font-size:12px; outline:none;" @disabled(!$entregaNuevoCiudad)>
                        <option value="">-- Seleccionar --</option>
                        @foreach($entregaProvincias as $p)
                        <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Municipio</p>
                    <select wire:model.live="entregaNuevoMunicipio" style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:7px 10px; font-size:12px; outline:none;" @disabled(!$entregaNuevaProvincia)>
                        <option value="">-- Seleccionar --</option>
                        @foreach($entregaMunicipios as $m)
                        <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Dirección *</p>
                    <input wire:model="entregaNuevaDireccion" type="text" placeholder="Calle y número"
                           style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:7px 10px; font-size:12px; outline:none; @error('entregaNuevaDireccion') border-color:#fca5a5; @enderror">
                    @error('entregaNuevaDireccion')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            @endif

            {{-- Referencia (siempre editable) --}}
            <div>
                <p style="font-size:10px; color:#9ca3af; font-weight:500; margin-bottom:3px;">Referencia <span style="color:#d1d5db;">(opcional)</span></p>
                <input wire:model="entregaReferencia" type="text"
                       placeholder="Ej: Portón azul, frente al parque..."
                       style="width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:7px 10px; font-size:12px; outline:none;">
            </div>
        </div>

        {{-- Errores de documentos --}}
        @php
            $docErrors = collect(['docAnversoCi','docReversoCi','docAnversoDoc','docReversoDoc','docAvisoLuz'])
                ->map(fn($f) => $errors->first($f))->filter();
        @endphp
        @if($docErrors->isNotEmpty())
        <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:6px; padding:10px 12px; margin-bottom:10px;">
            @foreach($docErrors as $err)
            <p style="color:#dc2626; font-size:12px; margin:2px 0;">Falta subir: {{ $err }}</p>
            @endforeach
        </div>
        @endif

        {{-- Error general --}}
        @error('pedido')
            <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:6px; padding:10px 12px; margin-bottom:10px; color:#dc2626; font-size:13px;">
                {{ $message }}
            </div>
        @enderror

        {{-- Botones --}}
        <button wire:click="confirmarPedido" wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 font-bold text-white transition-all mb-3"
                style="background:#7c3aed; border-radius:8px; padding:12px; font-size:14px;">
            <span wire:loading.remove wire:target="confirmarPedido">Confirmar Pedido</span>
            <span wire:loading wire:target="confirmarPedido">Procesando...</span>
        </button>
        <button wire:click="volverResumen" type="button"
                class="w-full font-semibold transition-colors"
                style="background:transparent; border:1px solid #d1d5db; border-radius:8px; padding:12px; font-size:14px; color:#6b7280;">
            Cancelar
        </button>

    </div>
</div>
@endif {{-- step entrega --}}

</div>
