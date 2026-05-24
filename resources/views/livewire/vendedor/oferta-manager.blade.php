<div x-data="{ toastShow: false, toastMsg: '', showSearch: false }"
     x-effect="document.body.style.overflow = showSearch ? 'hidden' : ''; if (!showSearch) $wire.set('searchCliente', '')"
     x-on:producto-agregado.window="toastMsg = $event.detail.nombre; toastShow = true; setTimeout(() => toastShow = false, 2200)">

{{-- Toast --}}
<div x-show="toastShow" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-3 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0 translate-y-3 scale-95"
     class="fixed bottom-5 left-1/2 -translate-x-1/2 z-50 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2.5 pointer-events-none whitespace-nowrap"
     style="background:#6D8196;">
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

{{-- ── BUSCADOR DE CLIENTE ───────────────────────────────────────────────── --}}
@if (false) {{-- compact search bar replaced by modal --}}
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
        <div class="absolute left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
            <p class="px-4 py-2.5 text-center text-xs text-gray-400 border-b border-gray-50">
                Sin resultados para "<strong class="text-gray-600">{{ $searchCliente }}</strong>"
            </p>
            <button wire:click="abrirRegistroCliente"
                    class="w-full flex items-center gap-3 px-4 py-3 transition-colors text-left"
                    style="background:#F8F7FF;"
                    onmouseover="this.style.background='#EEEDFE'" onmouseout="this.style.background='#F8F7FF'">
                <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full" style="background:#EEEDFE;">
                    <svg width="14" height="14" fill="none" stroke="#7c3aed" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm" style="color:#3C3489;">Registrar nuevo cliente</p>
                    <p class="text-xs text-gray-400">CI: {{ $searchCliente }}</p>
                </div>
            </button>
        </div>
        @endif
    </div>
    @endif
</div>
@endif {{-- /compact search bar --}}

{{-- ── STATS BAR — DESKTOP ──────────────────────────────────────────────── --}}
<div class="hidden md:block bg-white border-b border-gray-100 px-4 py-2.5">
    <div class="flex gap-3 items-stretch" style="min-height:52px;">

        {{-- BUSCAR --}}
        <div class="flex-shrink-0"
             style="background:#F97316; border-radius:999px; box-shadow:0 2px 10px rgba(249,115,22,0.35); overflow:hidden;">
            <button @click="showSearch = true"
                    class="w-full h-full flex items-center gap-2 active:scale-95"
                    style="padding:0 22px; cursor:pointer;">
                <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span style="font-size:13px; font-weight:600; color:#fff; white-space:nowrap;">Buscar cliente</span>
            </button>
        </div>

        {{-- CLIENTE --}}
        <div style="flex:1; min-width:0; background:#EEEDFE; border:1px solid #DDD8FB; border-radius:10px; padding:0 16px; display:flex; align-items:center; gap:12px;">
            <div style="width:34px; height:34px; border-radius:50%; background:#fff; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                @if ($clienteId)
                <span style="font-size:14px; font-weight:700; color:#7c3aed;">{{ strtoupper(substr($clienteNombre, 0, 1)) }}</span>
                @else
                <svg width="15" height="15" fill="none" stroke="#C4B5FD" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                @endif
            </div>
            <div style="min-width:0; flex:1;">
                <span style="font-size:9px; font-weight:700; color:#9B93E0; display:block; text-transform:uppercase; letter-spacing:0.08em;">Cliente seleccionado</span>
                @if ($clienteId)
                <span style="font-size:14px; font-weight:700; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' — ' : '' }}{{ $clienteNombre }}</span>
                @elseif ($sinListasActivas)
                <div style="display:flex; align-items:center; gap:5px;">
                    <svg width="11" height="11" fill="none" stroke="#e24b4a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:12px; font-weight:500; color:#e24b4a;">Sin listas activas</span>
                </div>
                @else
                <span style="font-size:13px; font-weight:400; color:#C4B5FD; display:block;">Seleccioná un cliente para comenzar...</span>
                @endif
            </div>
            @if ($clienteId)
            <button wire:click="cambiarCliente" style="flex-shrink:0; background:transparent; border:none; cursor:pointer; padding:4px;">
                <svg width="12" height="12" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @endif
        </div>

        {{-- CARRITO --}}
        <div class="relative flex-shrink-0"
             style="width:58px; border-radius:10px; background:{{ empty($carrito) ? '#f3f4f6' : '#f97316' }}; box-shadow:{{ empty($carrito) ? 'none' : '0 2px 10px rgba(249,115,22,0.30)' }}; transition:all 0.2s;">
            <button wire:click="irResumen"
                    @disabled(empty($carrito))
                    class="w-full h-full flex items-center justify-center transition-all active:scale-95"
                    style="cursor:{{ empty($carrito) ? 'default' : 'pointer' }};">
                <svg class="w-5 h-5" fill="none" stroke="{{ empty($carrito) ? '#d1d5db' : '#fff' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </button>
            @if ($cantidad > 0)
            <span class="absolute flex items-center justify-center font-bold text-white leading-none"
                  style="top:-5px; right:-5px; min-width:18px; height:18px; border-radius:50%; background:#e24b4a; font-size:10px; padding:0 3px; border:2px solid #fff;">
                {{ $cantidad > 9 ? '9+' : $cantidad }}
            </span>
            @endif
        </div>

    </div>
</div>

{{-- ── STATS BAR — MÓVIL ────────────────────────────────────────────────── --}}
<div class="md:hidden bg-white border-b border-gray-100 px-2 pt-2 pb-2 flex flex-col gap-1.5">

    {{-- FILA 1: Buscar Cliente (ancho completo) --}}
    <button @click="showSearch = true"
            class="w-full flex items-center justify-center gap-2 active:scale-95"
            style="background:#f97316; border-radius:8px; padding:9px; cursor:pointer; box-shadow:0 2px 6px rgba(249,115,22,0.25);">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="#fff" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#fff;">Buscar Cliente</span>
    </button>

    {{-- FILA 2: Cliente + Carrito --}}
    <div class="flex gap-1.5 items-stretch" style="min-height:38px;">

        {{-- CLIENTE --}}
        <div style="flex:1; min-width:0; background:#EEEDFE; border:0.5px solid rgba(206,203,246,0.6); border-radius:8px; padding:6px 10px; display:flex; flex-direction:column; justify-content:center;">
            <span style="font-size:9px; font-weight:600; color:#534AB7; display:block; margin-bottom:1px; text-transform:uppercase; letter-spacing:0.06em;">Cliente</span>
            @if ($clienteId)
            <span style="font-size:12px; font-weight:600; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $clienteCI ? $clienteCI . ' · ' : '' }}{{ $clienteNombre }}</span>
            @elseif ($sinListasActivas)
            <span style="font-size:11px; font-weight:500; color:#e24b4a;">Sin listas activas</span>
            @else
            <span style="font-size:11px; font-weight:400; color:#c4b5fd; display:block;">Seleccioná un cliente...</span>
            @endif
        </div>

        {{-- CARRITO --}}
        <div class="relative flex items-center justify-center flex-shrink-0"
             style="width:44px; border-radius:8px; background:{{ empty($carrito) ? '#f3f4f6' : '#f97316' }}; box-shadow:{{ empty($carrito) ? 'none' : '0 2px 8px rgba(249,115,22,0.30)' }};">
            <button wire:click="irResumen"
                    @disabled(empty($carrito))
                    class="w-full h-full flex items-center justify-center transition-all active:scale-95"
                    style="cursor:{{ empty($carrito) ? 'default' : 'pointer' }};">
                <svg class="w-5 h-5" fill="none" stroke="{{ empty($carrito) ? '#d1d5db' : '#fff' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </button>
            @if ($cantidad > 0)
            <span class="absolute flex items-center justify-center font-bold text-white leading-none"
                  style="top:-5px; right:-5px; min-width:18px; height:18px; border-radius:50%; background:#e24b4a; font-size:10px; padding:0 3px; border:2px solid #fff;">
                {{ $cantidad > 9 ? '9+' : $cantidad }}
            </span>
            @endif
        </div>

    </div>
</div>

{{-- ── FILTROS + BUSCADOR (solo en step oferta con cliente) ──────────────── --}}
@if ($clienteId && !$sinListasComunes && $step === 'oferta')
<div class="bg-white border-b border-gray-100 px-4 py-3">

    {{-- Heading (solo desktop) --}}
    <div class="hidden md:flex items-center gap-2 mb-3">
        <div style="width:24px; height:24px; border-radius:50%; background:#FFF7ED; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="12" height="12" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <span style="font-size:13px; font-weight:700; color:#3C3489;">Promociones disponibles para este cliente</span>
        <svg width="14" height="14" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    {{-- Pills + Buscador: misma fila en desktop, apilados en móvil --}}
    <div class="flex flex-col md:flex-row md:items-center gap-2">

        {{-- Cards de filtro --}}
        @php
        $cardColors = [
            ['bg'=>'#FFF7ED','iconBg'=>'#F97316','selBorder'=>'#F97316','selCard'=>'#FFF7ED','text'=>'#C2410C','sub'=>'#FB923C'],
            ['bg'=>'#F0EEFF','iconBg'=>'#7c3aed','selBorder'=>'#7c3aed','selCard'=>'#EDE9FE','text'=>'#5B21B6','sub'=>'#8B5CF6'],
            ['bg'=>'#F0FDF4','iconBg'=>'#059669','selBorder'=>'#059669','selCard'=>'#DCFCE7','text'=>'#065F46','sub'=>'#10B981'],
            ['bg'=>'#EFF6FF','iconBg'=>'#3B82F6','selBorder'=>'#3B82F6','selCard'=>'#DBEAFE','text'=>'#1D4ED8','sub'=>'#60A5FA'],
        ];
        $iconPaths = [
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>',
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1m0-8c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
        ];
        @endphp
        <div class="flex items-center gap-2 flex-wrap flex-1">

            {{-- Todos --}}
            @php $selTodos = $filterLista === ''; @endphp
            <button wire:click="$set('filterLista', '')"
                    class="flex items-center gap-2.5 flex-shrink-0 transition-all active:scale-95"
                    style="padding:8px 16px 8px 8px; border-radius:20px; border:1.5px solid {{ $selTodos ? '#F97316' : '#E5E7EB' }}; background:{{ $selTodos ? '#FFF7ED' : '#fff' }}; cursor:pointer; box-shadow:{{ $selTodos ? '0 2px 8px rgba(249,115,22,0.15)' : '0 1px 3px rgba(0,0,0,0.06)' }};">
                <div style="width:34px; height:34px; border-radius:12px; background:{{ $selTodos ? '#F97316' : '#F3F4F6' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="{{ $selTodos ? '#fff' : '#9CA3AF' }}" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <div style="text-align:left;">
                    <span style="font-size:12px; font-weight:700; color:{{ $selTodos ? '#C2410C' : '#374151' }}; display:block; line-height:1.3;">Todos</span>
                    <span style="font-size:10px; color:{{ $selTodos ? '#FB923C' : '#9CA3AF' }}; display:block; line-height:1.2;">Ver todo</span>
                </div>
            </button>

            {{-- Una card por lista --}}
            @foreach ($listasInfo as $lid => $info)
            @php
                $ci  = $loop->index % count($cardColors);
                $col = $cardColors[$ci];
                $sel = $filterLista === (string)$lid;
                $ico = $iconPaths[$ci];
            @endphp
            <button wire:click="$set('filterLista', '{{ $lid }}')"
                    class="flex items-center gap-2.5 flex-shrink-0 transition-all active:scale-95"
                    style="padding:8px 16px 8px 8px; border-radius:20px; border:1.5px solid {{ $sel ? $col['selBorder'] : '#E5E7EB' }}; background:{{ $sel ? $col['selCard'] : '#fff' }}; cursor:pointer; max-width:200px; box-shadow:{{ $sel ? '0 2px 8px rgba(0,0,0,0.10)' : '0 1px 3px rgba(0,0,0,0.06)' }};">
                <div style="width:34px; height:34px; border-radius:12px; background:{{ $sel ? $col['iconBg'] : $col['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="{{ $sel ? '#fff' : $col['iconBg'] }}" viewBox="0 0 24 24">{!! $ico !!}</svg>
                </div>
                <div style="text-align:left; min-width:0;">
                    <span style="font-size:12px; font-weight:700; color:{{ $sel ? $col['text'] : '#374151' }}; display:block; line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:130px;">{{ $info['nombre'] }}</span>
                    <span style="font-size:10px; color:{{ $sel ? $col['sub'] : '#9CA3AF' }}; display:block; line-height:1.2;">{{ $info['code'] }}</span>
                </div>
            </button>
            @endforeach

        </div>

        {{-- Buscador + contador --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="searchProducto" type="text"
                       placeholder="Buscar producto..."
                       class="pl-8 pr-3 py-2 text-sm focus:outline-none"
                       style="background:#F8F7FF; border:1.5px solid #EDE9FE; border-radius:10px; font-size:12px; color:#3C3489; width:200px;"
                       onfocus="this.style.borderColor='#C4B5FD'; this.style.background='#fff';"
                       onblur="this.style.borderColor='#EDE9FE'; this.style.background='#F8F7FF';">
            </div>
            @php $totalProductos = collect($ofertaPorLista)->flatten(1)->count(); @endphp
            <span class="hidden md:block flex-shrink-0" style="font-size:12px; font-weight:600; color:#C4B5FD; white-space:nowrap;">{{ $totalProductos }} {{ $totalProductos === 1 ? 'producto' : 'productos' }}</span>
        </div>

    </div>
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

{{-- Separador Productos --}}
<div class="px-4 pt-4 pb-1 flex items-center gap-3">
    <span class="text-xs font-bold uppercase tracking-widest" style="color:#C4B5FD; letter-spacing:0.12em;">Productos</span>
    <div class="flex-1 h-px" style="background:#F0EEFF;"></div>
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

                    {{-- Botón Agregar --}}
                    <button @click="if(n === 0) n = 1; $wire.agregar({{ $p['product_id'] }}, n).then(() => n = 0)"
                            class="flex items-center justify-center gap-1 transition-all active:scale-95 flex-1"
                            style="background:#F97316; border:1.5px solid #F97316; color:#fff; border-radius:8px; padding:8px 10px; font-size:12px; font-weight:700; box-shadow:0 2px 8px rgba(249,115,22,0.28);">
                        <svg style="width:15px; height:15px; flex-shrink:0;" fill="none" stroke="#fff" viewBox="0 0 24 24">
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

<div class="max-w-2xl mx-auto px-4 pb-10 pt-4">

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

{{-- ══ MODAL: BUSCAR CLIENTE ════════════════════════════════════════════════ --}}
<style>
.buscar-sheet {
    width: calc(100% - 32px);
    max-height: 66vh;
    border-radius: 0;
    background: #fff;
    box-shadow: 0 8px 40px rgba(60,52,137,0.16), 0 0 0 1px rgba(196,181,253,0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
@media (min-width: 640px) {
    .buscar-sheet {
        width: 100%;
        max-width: 400px;
        max-height: 80vh;
        border-radius: 0;
    }
}
</style>

<div x-show="showSearch" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 flex items-center justify-center"
     style="z-index:98; background:rgba(30,24,80,0.22); backdrop-filter:blur(2px);"
     @click.self="showSearch = false">

    <div class="buscar-sheet"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">

        {{-- Header --}}
        <div style="padding:14px 16px 12px; flex-shrink:0;">
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    {{-- Ícono persona outline sutil --}}
                    <div style="width:28px; height:28px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="#7c3aed" stroke-width="1.7" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span style="font-size:17px; font-weight:700; color:#534AB7; letter-spacing:-0.2px;">Buscar cliente</span>
                </div>
                <button @click="showSearch = false"
                        style="width:26px; height:26px; border-radius:8px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="10" height="10" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Separador sutil --}}
            <div style="height:1px; background:#F0EEFF; margin:12px -16px 12px;"></div>

            {{-- Input --}}
            <div style="position:relative;">
                <svg fill="none" stroke="#C4B5FD" viewBox="0 0 24 24"
                     style="position:absolute; left:11px; top:50%; transform:translateY(-50%); width:14px; height:14px; pointer-events:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="clienteSearchInput"
                       wire:model.live.debounce.300ms="searchCliente"
                       type="text"
                       placeholder="CI, nombre o apellido..."
                       style="width:100%; padding:9px 32px 9px 30px; font-size:13px; border-radius:10px; border:1.5px solid #EDE9FE; background:#FAFAFE; outline:none; color:#3C3489; box-sizing:border-box; transition:border-color 0.15s, background 0.15s;"
                       onfocus="this.style.borderColor='#C4B5FD'; this.style.background='#fff';"
                       onblur="this.style.borderColor='#EDE9FE'; this.style.background='#FAFAFE';">
                @if (trim($searchCliente) !== '')
                <button wire:click="$set('searchCliente','')"
                        style="position:absolute; right:9px; top:50%; transform:translateY(-50%); width:17px; height:17px; border-radius:50%; background:#E9E7FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg width="7" height="7" fill="none" stroke="#9CA3AF" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>

        {{-- Lista scrollable --}}
        <div style="overflow-y:auto; flex:1; min-height:0; padding:4px 14px 18px;">

            @php
                $busquedaActiva = strlen(trim($searchCliente)) >= 2;
                $listaActual    = $busquedaActiva ? $resultadosCliente : $clientesPropios;
                $labelLista     = $busquedaActiva ? null : 'Tus clientes';
            @endphp

            @if (!$busquedaActiva && count($clientesPropios) === 0)
            {{-- Sin clientes propios y sin búsqueda --}}
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px 16px; text-align:center;">
                <div style="width:40px; height:40px; border-radius:50%; background:#F5F3FF; display:flex; align-items:center; justify-content:center; margin-bottom:8px;">
                    <svg width="18" height="18" fill="none" stroke="#C4B5FD" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p style="font-size:12px; font-weight:500; color:#C4B5FD; margin:0;">Escribí CI, nombre o apellido</p>
            </div>

            @elseif ($busquedaActiva && count($resultadosCliente) === 0)
            {{-- Sin resultados en búsqueda --}}
            <div style="padding:14px 2px 10px; text-align:center;">
                <p style="font-size:12px; color:#D1D5DB; margin:0 0 12px;">
                    Sin resultados para "<span style="color:#9CA3AF;">{{ $searchCliente }}</span>"
                </p>
                <button wire:click="abrirRegistroCliente"
                        @click="showSearch = false"
                        style="width:100%; display:flex; align-items:center; gap:10px; padding:11px 12px; background:#FFF7ED; border-radius:12px; border:1px dashed #FBD0A4; cursor:pointer; box-sizing:border-box; transition:background 0.12s, border-color 0.12s;"
                        onmouseover="this.style.background='#FEF3E2'; this.style.borderColor='#F97316';"
                        onmouseout="this.style.background='#FFF7ED'; this.style.borderColor='#FBD0A4';">
                    <div style="width:32px; height:32px; border-radius:50%; background:#FFEDD5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="12" height="12" fill="none" stroke="#F97316" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div style="text-align:left;">
                        <p style="font-weight:600; font-size:13px; color:#C2410C; margin:0;">Registrar nuevo cliente</p>
                        <p style="font-size:11px; color:#FDBA74; margin:1px 0 0; font-family:monospace;">CI: {{ $searchCliente }}</p>
                    </div>
                    <svg width="11" height="11" fill="none" stroke="#FBD0A4" viewBox="0 0 24 24" style="flex-shrink:0; margin-left:auto;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            @else
            {{-- Label de sección --}}
            @if ($labelLista)
            <p style="font-size:9px; font-weight:600; color:#C4B5FD; text-transform:uppercase; letter-spacing:0.1em; margin:0 2px 8px; padding-top:2px;">{{ $labelLista }}</p>
            @endif

            {{-- Cards de clientes --}}
            <div style="display:flex; flex-direction:column; gap:4px;">
                @foreach ($listaActual as $c)
                <button wire:click="seleccionarCliente({{ $c['id'] }}, {{ $c['user_id'] }}, '{{ addslashes($c['nombre']) }}', '{{ addslashes($c['ci']) }}')"
                        @click="showSearch = false"
                        class="w-full flex items-center gap-3 text-left"
                        style="padding:9px 11px; background:#FAFAFE; border-radius:11px; border:1px solid #F0EEFF; cursor:pointer; transition:all 0.12s;"
                        onmouseover="this.style.background='#EDE9FE'; this.style.borderColor='#D4CFF8'; this.querySelector('.nombre-text').style.color='#3C3489';"
                        onmouseout="this.style.background='#FAFAFE'; this.style.borderColor='#F0EEFF'; this.querySelector('.nombre-text').style.color='#534AB7';">
                    <div style="width:32px; height:32px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <span style="font-weight:700; font-size:13px; color:#7c3aed;">{{ strtoupper(substr($c['nombre'], 0, 1)) }}</span>
                    </div>
                    <div style="min-width:0; flex:1; text-align:left;">
                        <p class="nombre-text" style="font-weight:600; font-size:13px; color:#534AB7; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; transition:color 0.12s;">{{ $c['nombre'] }}</p>
                        <p style="font-size:11px; font-weight:700; color:#9B93E0; margin:1px 0 0; font-family:monospace;">CI: {{ $c['ci'] }}</p>
                    </div>
                    <svg width="11" height="11" fill="none" stroke="#DDD8FB" viewBox="0 0 24 24" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                @endforeach
            </div>

            @if ($busquedaActiva)
            {{-- Opción registrar nuevo al final de resultados de búsqueda --}}
            <div style="margin-top:8px;">
                <button wire:click="abrirRegistroCliente"
                        @click="showSearch = false"
                        style="width:100%; display:flex; align-items:center; gap:10px; padding:8px 11px; background:#FFF7ED; border-radius:10px; border:1px dashed #FBD0A4; cursor:pointer; box-sizing:border-box; transition:background 0.12s, border-color 0.12s;"
                        onmouseover="this.style.background='#FEF3E2'; this.style.borderColor='#F97316';"
                        onmouseout="this.style.background='#FFF7ED'; this.style.borderColor='#FBD0A4';">
                    <div style="width:26px; height:26px; border-radius:50%; background:#FFEDD5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="10" height="10" fill="none" stroke="#F97316" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span style="font-size:12px; font-weight:500; color:#C2410C;">Registrar nuevo cliente</span>
                </button>
            </div>
            @endif

            @endif

        </div>
    </div>
</div>

{{-- ══ MODAL: REGISTRAR NUEVO CLIENTE ══════════════════════════════════════ --}}
@if ($showRegistroCliente)
<style>
.reg-input {
    width: 100%; padding: 10px 12px; border: 1.5px solid #EDE9FE;
    border-radius: 10px; font-size: 13px; color: #3C3489;
    background: #FAFAFE; outline: none; box-sizing: border-box;
    transition: border-color 0.15s, background 0.15s;
    -webkit-appearance: none; appearance: none;
}
.reg-input:focus { border-color: #C4B5FD; background: #fff; }
.reg-input:disabled { opacity: 0.45; cursor: not-allowed; }
.reg-select-wrap { position: relative; }
.reg-select-wrap::after {
    content: '';
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    width: 0; height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid #C4B5FD;
    pointer-events: none;
}
.reg-label {
    font-size: 10px; font-weight: 700; color: #6B65B0;
    text-transform: uppercase; letter-spacing: .05em;
    display: block; margin-bottom: 5px;
}
.reg-err { font-size: 10px; color: #ef4444; margin-top: 3px; }
</style>

<div x-data="{ ubModal: false, ubTipo: '', ubOpciones: [], ubSearch: '' }"
     style="position:fixed; inset:0; z-index:100; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(30,24,80,0.28); backdrop-filter:blur(2px);"
     wire:click.self="cancelarRegistroCliente">

    <div style="background:#fff; border-radius:20px; width:100%; max-width:560px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 24px 60px rgba(60,52,137,0.18), 0 0 0 1px rgba(196,181,253,0.15); position:relative;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:9px;">
                <div style="width:30px; height:30px; border-radius:50%; background:#FFEDD5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" fill="none" stroke="#F97316" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:17px; font-weight:700; color:#3C3489; margin:0; letter-spacing:-0.2px;">Nuevo cliente</p>
                </div>
            </div>
            <button wire:click="cancelarRegistroCliente"
                    style="width:28px; height:28px; border-radius:8px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="10" height="10" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body (scrollable) --}}
        <div style="overflow-y:auto; flex:1; min-height:0; padding:14px 18px 8px;">

            {{-- ▸ Datos personales --}}
            <div style="background:#FAFAFE; border-radius:14px; padding:14px 16px; margin-bottom:12px; border:1px solid #F0EEFF;">

                <div style="display:flex; align-items:center; gap:6px; margin-bottom:12px;">
                    <div style="width:5px; height:5px; border-radius:50%; background:#C4B5FD; flex-shrink:0;"></div>
                    <span style="font-size:9px; font-weight:700; color:#6B65B0; text-transform:uppercase; letter-spacing:.12em;">Datos personales</span>
                </div>

                <div style="display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:10px;">

                    <div style="grid-column:span 2;">
                        <label class="reg-label">CI <span style="color:#F97316;">*</span></label>
                        <input wire:model="regCi" type="text" placeholder="1234567"
                               class="reg-input" style="font-family:monospace;">
                        @error('regCi')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    <div style="grid-column:span 2;">
                        <label class="reg-label">Nombre <span style="color:#F97316;">*</span></label>
                        <input wire:model="regNombre" type="text" placeholder="María"
                               class="reg-input">
                        @error('regNombre')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    <div style="grid-column:span 2;">
                        <label class="reg-label">Apellido <span style="color:#F97316;">*</span></label>
                        <input wire:model="regApellido" type="text" placeholder="García"
                               class="reg-input">
                        @error('regApellido')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="reg-label">NIT <span style="color:#D1D5DB; font-weight:400; text-transform:none; letter-spacing:0;">· opcional</span></label>
                        <input wire:model="regNit" type="text" placeholder="—" class="reg-input">
                    </div>

                    <div>
                        <label class="reg-label">Teléfono <span style="color:#F97316;">*</span></label>
                        <input wire:model="regTelefono" type="text" placeholder="70012345"
                               class="reg-input">
                        @error('regTelefono')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    <div style="grid-column:span 2;">
                        <label class="reg-label">Correo <span style="color:#D1D5DB; font-weight:400; text-transform:none; letter-spacing:0;">· opcional</span></label>
                        <input wire:model="regCorreo" type="email" placeholder="—" class="reg-input">
                        @error('regCorreo')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            {{-- ▸ Dirección --}}
            <div style="background:#FAFAFE; border-radius:14px; padding:14px 16px; margin-bottom:12px; border:1px solid #F0EEFF;">

                <div style="display:flex; align-items:center; gap:6px; margin-bottom:12px;">
                    <div style="width:5px; height:5px; border-radius:50%; background:#FBD0A4; flex-shrink:0;"></div>
                    <span style="font-size:9px; font-weight:700; color:#6B65B0; text-transform:uppercase; letter-spacing:.12em;">Dirección</span>
                </div>

                <div style="display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:10px;">

                    {{-- Ciudad --}}
                    <div style="grid-column:span 2;">
                        <label class="reg-label">Ciudad <span style="color:#F97316;">*</span></label>
                        <button type="button"
                                @click="ubModal=true; ubTipo='ciudad'; ubOpciones=@js($ciudadesAll->pluck('nombre')->toArray()); ubSearch=''"
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $regCiudad ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $regCiudad ? '#EEEDFE' : '#fff' }}; cursor:pointer; box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                            <svg width="13" height="13" fill="none" stroke="{{ $regCiudad ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $regCiudad ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $regCiudad ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $regCiudad ? ucwords(strtolower($regCiudad)) : 'Seleccionar' }}</span>
                            <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        @error('regCiudad')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    {{-- Provincia --}}
                    <div style="grid-column:span 2;">
                        <label class="reg-label">Provincia <span style="color:#F97316;">*</span></label>
                        <button type="button"
                                @if($regCiudad) @click="ubModal=true; ubTipo='provincia'; ubOpciones=@js($regProvincias->pluck('nombre')->toArray()); ubSearch=''" @endif
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $regProvincia ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $regProvincia ? '#EEEDFE' : ($regCiudad ? '#fff' : '#FAFAFE') }}; {{ $regCiudad ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.5;' }} box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                            <svg width="13" height="13" fill="none" stroke="{{ $regProvincia ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $regProvincia ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $regProvincia ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $regProvincia ? ucwords(strtolower($regProvincia)) : 'Seleccionar' }}</span>
                            <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        @error('regProvincia')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    {{-- Municipio (ancho completo) --}}
                    <div style="grid-column:span 2;">
                        <label class="reg-label">Municipio <span style="color:#F97316;">*</span></label>
                        <button type="button"
                                @if($regProvincia) @click="ubModal=true; ubTipo='municipio'; ubOpciones=@js($regMunicipios->pluck('nombre')->toArray()); ubSearch=''" @endif
                                style="width:100%; padding:10px 12px; border:1.5px solid {{ $regMunicipio ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $regMunicipio ? '#EEEDFE' : ($regProvincia ? '#fff' : '#FAFAFE') }}; {{ $regProvincia ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.5;' }} box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                            <svg width="13" height="13" fill="none" stroke="{{ $regMunicipio ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $regMunicipio ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $regMunicipio ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $regMunicipio ? ucwords(strtolower($regMunicipio)) : 'Seleccionar municipio' }}</span>
                            <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        @error('regMunicipio')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                    {{-- Observación (ancho completo) --}}
                    <div style="grid-column:span 2;">
                        <label class="reg-label">Observación <span style="color:#D1D5DB; font-weight:400; text-transform:none; letter-spacing:0;">· opcional</span></label>
                        <input wire:model="regDireccion" type="text" placeholder="Referencia, notas..."
                               class="reg-input">
                        @error('regDireccion')<p class="reg-err">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div style="display:flex; gap:8px; padding:14px 20px; border-top:1px solid #F0EEFF; flex-shrink:0;">
            <button wire:click="cancelarRegistroCliente" type="button"
                    style="padding:10px 16px; border:1.5px solid #E5E7EB; border-radius:10px; font-size:13px; font-weight:600; color:#6B7280; background:#fff; cursor:pointer; flex-shrink:0;">
                Cancelar
            </button>
            <button wire:click="guardarNuevoCliente" wire:loading.attr="disabled"
                    style="flex:1; padding:11px; border-radius:10px; font-size:13px; font-weight:700; color:#fff; background:#F97316; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; box-shadow:0 2px 10px rgba(249,115,22,0.30);">
                <svg wire:loading.remove wire:target="guardarNuevoCliente" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span wire:loading.remove wire:target="guardarNuevoCliente">Guardar y seleccionar</span>
                <span wire:loading wire:target="guardarNuevoCliente">Guardando...</span>
            </button>
        </div>

    </div>

    {{-- ── Mini modal ubicación (fixed, fuera del card) ──────────────────── --}}
    {{-- x-show va en el overlay sin display:flex para que Alpine no pise el layout --}}
    <div x-show="ubModal" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         style="position:fixed; inset:0; z-index:150; background:rgba(30,24,80,0.32); backdrop-filter:blur(2px);">

        {{-- div hijo siempre flex para centrar --}}
        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;"
             @click.self="ubModal=false; ubSearch=''">

        <div x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             style="background:#fff; border-radius:16px; width:85%; max-width:300px; max-height:60vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 8px 32px rgba(60,52,137,0.22);">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:13px 16px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
                <span x-text="ubTipo === 'ciudad' ? 'Seleccionar ciudad' : ubTipo === 'provincia' ? 'Seleccionar provincia' : 'Seleccionar municipio'"
                      style="font-size:13px; font-weight:600; color:#534AB7;"></span>
                <button type="button" @click="ubModal=false; ubSearch=''"
                        style="width:24px; height:24px; border-radius:6px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg width="9" height="9" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Buscador --}}
            <div style="padding:10px 12px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
                <div style="position:relative;">
                    <svg style="position:absolute; left:9px; top:50%; transform:translateY(-50%); pointer-events:none;" width="12" height="12" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="ubSearch" type="text" placeholder="Buscar..."
                           style="width:100%; padding:7px 10px 7px 26px; border:1.5px solid #EDE9FE; border-radius:8px; font-size:12px; color:#3C3489; background:#FAFAFE; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#C4B5FD'" onblur="this.style.borderColor='#EDE9FE'">
                </div>
            </div>

            {{-- Lista --}}
            <div style="overflow-y:auto; flex:1; min-height:0; padding:4px 0;">
                <template x-for="op in ubOpciones.filter(o => o.toLowerCase().includes(ubSearch.toLowerCase()))" :key="op">
                    <button type="button"
                            @click="
                                if(ubTipo==='ciudad') $wire.set('regCiudad', op);
                                else if(ubTipo==='provincia') $wire.set('regProvincia', op);
                                else $wire.set('regMunicipio', op);
                                ubModal=false; ubSearch='';
                            "
                            x-text="op.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())"
                            style="width:100%; padding:10px 16px; text-align:left; font-size:13px; color:#374151; border:none; background:transparent; cursor:pointer; display:block;"
                            onmouseover="this.style.background='#F5F3FF'; this.style.color='#7c3aed';"
                            onmouseout="this.style.background='transparent'; this.style.color='#374151';">
                    </button>
                </template>
                <p x-show="ubOpciones.filter(o => o.toLowerCase().includes(ubSearch.toLowerCase())).length === 0"
                   style="text-align:center; font-size:12px; color:#C4B5FD; padding:20px;">
                    Sin resultados
                </p>
            </div>

        </div>

        </div>{{-- /centering --}}
    </div>{{-- /overlay --}}

</div>
@endif

</div>
