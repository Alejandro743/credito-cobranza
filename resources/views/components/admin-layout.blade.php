@props(['title' => ''])
<?php header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0'); ?>
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Crediessen</title>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    lavanda: {
                        50:  '#F0F4F7',
                        100: '#FFFFE3',
                        200: '#CBCBCB',
                        300: '#A8B8C8',
                        400: '#8FA0B0',
                        500: '#6D8196',
                        600: '#5C6F80',
                        700: '#4A4A4A',
                    },
                    mint:      { 50:'#F0F4F7',100:'#E8F0F7',200:'#D4E0EA',500:'#6D8196',600:'#5C6F80',700:'#4A4A4A' },
                    melocoton: { 50:'#F0F4F7',100:'#E8F0F7',200:'#D4E0EA',500:'#6D8196',600:'#5C6F80',700:'#4A4A4A' },
                    celeste:   { 50:'#F0F4F7',100:'#E8F0F7',200:'#D4E0EA',500:'#6D8196',600:'#5C6F80',700:'#4A4A4A' },
                }
            }
        }
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar scroll */
        .sidebar-nav::-webkit-scrollbar       { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }

        /* Sidebar */
        .sidebar-wrap {
            background: #0B1120;
            transition: width 0.28s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }

        /* Ocultar texto en modo colapsado con fade */
        .nav-label {
            transition: opacity 0.2s ease, width 0.28s ease;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Tooltip en modo colapsado */
        .nav-tooltip {
            display: none;
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: #1E2A50;
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,.3);
        }
        .nav-item-wrap:hover .nav-tooltip { display: block; }
    </style>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/crediessen.css') }}?v={{ @filemtime(public_path('css/crediessen.css')) }}">
</head>
<body style="background:#F0F2F5;" class="font-sans antialiased">

@php
    use App\Services\PermisoService;
    use Illuminate\Support\Facades\Route as RouteHelper;

    $subIconos = [
        'vendedor-clientes'       => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'vendedor-gestion-planes' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'vendedor-oferta'         => 'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'vendedor-pedidos'        => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'vendedor-pagos-saldos'   => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'credito-gestion'         => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'credito-clientes'        => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'credito-espera'          => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-revision'        => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'credito-aprobado'        => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'def-motivo-cierre'       => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z',
        'credito-cobranza'        => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'credito-reprogramacion'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    ];

    $navUser = auth()->user();

    $navModulos = \App\Models\Modulo::with([
            'submodulosActivos' => fn($q) => $q->with([
                'children' => fn($q2) => $q2->where('active', true)->orderBy('sort_order')
            ])
        ])
        ->where('active', true)
        ->orderBy('sort_order')
        ->get()
        ->map(function ($modulo) use ($navUser) {
            $modulo->submodulosActivos->each(function ($sub) use ($navUser) {
                if ($sub->isGroup()) {
                    $sub->childrenVisibles = $sub->children->filter(
                        fn($child) => PermisoService::check($navUser, $child->slug)
                    );
                    $sub->esVisible = $sub->childrenVisibles->isNotEmpty();
                } else {
                    $sub->childrenVisibles = collect();
                    $sub->esVisible = PermisoService::check($navUser, $sub->slug);
                }
            });
            $modulo->submodulosVisibles = $modulo->submodulosActivos->filter(fn($s) => $s->esVisible);
            return $modulo;
        })
        ->filter(fn($m) => $m->submodulosVisibles->isNotEmpty());

    $moduloActivo = $navModulos->first(function ($m) {
        return $m->submodulosVisibles->contains(function ($s) {
            if (!$s->isGroup()) return $s->route_name && request()->routeIs($s->route_name);
            return $s->childrenVisibles->contains(fn($ch) => $ch->route_name && request()->routeIs($ch->route_name));
        });
    });
    $activeModuloSlug = $moduloActivo?->slug ?? '';
    $activeModuloName = $moduloActivo?->name ?? '';

    $activeLeaf = null;
    if ($moduloActivo) {
        foreach ($moduloActivo->submodulosVisibles as $sub) {
            if (!$sub->isGroup() && $sub->route_name && request()->routeIs($sub->route_name)) {
                $activeLeaf = $sub; break;
            }
            if ($sub->isGroup()) {
                foreach ($sub->childrenVisibles as $child) {
                    if ($child->route_name && request()->routeIs($child->route_name)) {
                        $activeLeaf = $child; break 2;
                    }
                }
            }
        }
    }
    $pageTitle    = $activeLeaf?->name ?? ($activeModuloName ?: 'Panel de Inicio');
    $pageIconPath = ($activeLeaf && isset($subIconos[$activeLeaf->slug]))
        ? $subIconos[$activeLeaf->slug]
        : ($moduloActivo?->icon ?? 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6');
@endphp

<div class="flex h-screen overflow-hidden">

    {{-- Overlay móvil --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 md:hidden"
         style="background:rgba(0,0,0,.5);"></div>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar-wrap fixed inset-y-0 left-0 z-50 flex flex-col
                  md:static md:inset-auto md:z-auto
                  -translate-x-full md:translate-x-0
                  transition-transform duration-300 ease-in-out md:transition-none"
           :class="{ 'translate-x-0': sidebarOpen }"
           :style="{ width: sidebarCollapsed ? '64px' : '240px' }"
           style="width:240px;">

        {{-- ── Logo ── --}}
        <div style="padding:16px 14px; border-bottom:1px solid rgba(255,255,255,.07); flex-shrink:0; min-height:64px; display:flex; align-items:center; gap:10px;">
            {{-- Ícono siempre visible --}}
            <div style="width:36px; height:36px; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            {{-- Texto: oculto cuando colapsado --}}
            <div class="nav-label" :style="sidebarCollapsed ? 'opacity:0;width:0;' : 'opacity:1;width:auto;'">
                <p style="font-size:13px; font-weight:800; color:#fff; letter-spacing:1.5px; line-height:1; white-space:nowrap;">CREDIESSEN</p>
                <p style="font-size:9px; color:rgba(255,255,255,.55); font-weight:500; letter-spacing:1px; text-transform:uppercase; margin-top:3px; white-space:nowrap;">Sistema de Crédito</p>
            </div>
        </div>

        {{-- ── Toggle Desktop ── --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden md:flex items-center justify-center transition-colors"
                style="position:absolute; top:18px; right:-12px; width:24px; height:24px; background:#1E2A50; border:2px solid rgba(255,255,255,.12); border-radius:50%; cursor:pointer; z-index:10; flex-shrink:0;">
            <svg :style="sidebarCollapsed ? 'transform:rotate(180deg)' : ''"
                 style="transition:transform .25s; width:10px; height:10px; color:rgba(255,255,255,.6);"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- ── Panel Inicio ── --}}
        @php $dashActivo = request()->routeIs('administrativo.dashboard'); @endphp
        <div style="padding:10px 10px 4px; flex-shrink:0;">
            <a href="{{ route('administrativo.dashboard') }}"
               class="nav-item-wrap flex items-center gap-3 transition-all"
               style="padding:9px 10px; border-radius:8px; position:relative;
                      {{ $dashActivo ? 'background:rgba(123,111,232,.25);' : '' }}">
                {{-- Tooltip colapsado --}}
                <span class="nav-tooltip">Panel Inicio</span>
                <div style="width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                            {{ $dashActivo ? 'background:#7B6FE8;' : 'background:rgba(255,255,255,.14);' }}">
                    <svg width="15" height="15" fill="none" stroke="{{ $dashActivo ? '#fff' : 'rgba(255,255,255,.6)' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="nav-label" :style="sidebarCollapsed ? 'opacity:0;width:0;' : 'opacity:1;'"
                      style="font-size:13px; font-weight:{{ $dashActivo ? '600' : '500' }}; color:{{ $dashActivo ? '#fff' : 'rgba(255,255,255,.80)' }};">
                    Panel Inicio
                </span>
            </a>
        </div>

        {{-- ── Navegación ── --}}
        <nav class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden"
             style="padding:4px 10px 8px;"
             x-data="{ activeModule: '{{ $activeModuloSlug }}' }">

            @foreach ($navModulos as $modulo)
            @php $slug = $modulo->slug; @endphp
            <div style="margin-bottom:2px;">

                {{-- Encabezado de módulo --}}
                <button @click="!sidebarCollapsed && (activeModule = (activeModule === '{{ $slug }}' ? '' : '{{ $slug }}'))"
                        class="nav-item-wrap w-full flex items-center gap-3 transition-all"
                        style="padding:9px 10px; border-radius:8px; position:relative;
                               {{ $activeModuloSlug === $slug ? 'background:rgba(123,111,232,.15);' : '' }}">
                    {{-- Tooltip colapsado --}}
                    <span class="nav-tooltip">{{ $modulo->name }}</span>
                    {{-- Ícono módulo --}}
                    <div style="width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                                {{ $activeModuloSlug === $slug ? 'background:#7B6FE8;' : 'background:rgba(255,255,255,.14);' }}">
                        <svg width="15" height="15" fill="none"
                             stroke="{{ $activeModuloSlug === $slug ? '#fff' : 'rgba(255,255,255,.6)' }}"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="{{ $modulo->icon }}"/>
                        </svg>
                    </div>
                    {{-- Nombre + chevron --}}
                    <div class="nav-label flex items-center flex-1 gap-1"
                         :style="sidebarCollapsed ? 'opacity:0;width:0;overflow:hidden;' : 'opacity:1;'">
                        <span style="flex:1; text-align:left; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.6px;
                                     color:{{ $activeModuloSlug === $slug ? 'rgba(255,255,255,.9)' : 'rgba(255,255,255,.60)' }};">
                            {{ $modulo->name }}
                        </span>
                        <svg :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''"
                             class="w-3 h-3 transition-transform flex-shrink-0"
                             style="color:rgba(255,255,255,.3);"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Submenú (oculto cuando colapsado) --}}
                <div x-show="activeModule === '{{ $slug }}' && !sidebarCollapsed"
                     x-transition:enter="transition-all duration-150 ease-out"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="margin-left:16px; padding-left:12px; border-left:1px solid rgba(255,255,255,.1); margin-top:2px; margin-bottom:4px;">

                    @foreach ($modulo->submodulosVisibles as $sub)

                    @if ($sub->isGroup())
                    @php
                        $grupActivo = $sub->childrenVisibles->contains(
                            fn($ch) => $ch->route_name && request()->routeIs($ch->route_name)
                        );
                    @endphp
                    <div x-data="{ subOpen: {{ $grupActivo ? 'true' : 'false' }} }" style="margin-bottom:1px;">
                        <button @click="subOpen = !subOpen"
                                class="w-full flex items-center gap-2.5 transition-all"
                                style="padding:7px 8px; border-radius:6px; width:100%;
                                       {{ $grupActivo ? 'background:rgba(123,111,232,.12);' : '' }}">
                            <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                                        {{ $grupActivo ? 'background:rgba(123,111,232,.5);' : 'background:rgba(255,255,255,.12);' }}">
                                <svg width="12" height="12" fill="none"
                                     stroke="{{ $grupActivo ? '#C4B5FD' : 'rgba(255,255,255,.65)' }}"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="{{ $subIconos[$sub->slug] ?? 'M4 6h16M4 12h16M4 18h16' }}"/>
                                </svg>
                            </div>
                            <span style="flex:1; text-align:left; font-size:12px; font-weight:500;
                                         color:{{ $grupActivo ? 'rgba(255,255,255,.85)' : 'rgba(255,255,255,.65)' }};">
                                {{ $sub->name }}
                            </span>
                            <svg :class="subOpen ? 'rotate-180' : ''"
                                 class="w-3 h-3 transition-transform flex-shrink-0"
                                 style="color:rgba(255,255,255,.25);"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="subOpen"
                             style="margin-left:12px; padding-left:10px; border-left:1px solid rgba(255,255,255,.08); margin-top:1px;">
                            @foreach ($sub->childrenVisibles as $child)
                            @php
                                $childActivo = $child->route_name && request()->routeIs($child->route_name);
                                $href = ($child->route_name && RouteHelper::has($child->route_name)) ? route($child->route_name) : '#';
                            @endphp
                            <a href="{{ $href }}"
                               class="flex items-center gap-2 transition-all"
                               style="padding:6px 8px; border-radius:6px; margin-bottom:1px;
                                      {{ $childActivo ? 'background:#7B6FE8;' : '' }}">
                                <span style="width:5px; height:5px; border-radius:50%; flex-shrink:0;
                                             background:{{ $childActivo ? '#fff' : 'rgba(255,255,255,.25)' }};"></span>
                                <span style="font-size:12px; font-weight:{{ $childActivo ? '600' : '400' }};
                                             color:{{ $childActivo ? '#fff' : 'rgba(255,255,255,.65)' }};">
                                    {{ $child->name }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    @else
                    @php
                        $subActivo = $sub->route_name && request()->routeIs($sub->route_name);
                        $href = ($sub->route_name && RouteHelper::has($sub->route_name)) ? route($sub->route_name) : '#';
                    @endphp
                    <a href="{{ $href }}"
                       class="flex items-center gap-2.5 transition-all"
                       style="padding:7px 8px; border-radius:6px; margin-bottom:1px;
                              {{ $subActivo ? 'background:#7B6FE8;' : '' }}">
                        <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                                    {{ $subActivo ? 'background:rgba(255,255,255,.2);' : 'background:rgba(255,255,255,.12);' }}">
                            <svg width="12" height="12" fill="none"
                                 stroke="{{ $subActivo ? '#fff' : 'rgba(255,255,255,.65)' }}"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="{{ $subIconos[$sub->slug] ?? 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2' }}"/>
                            </svg>
                        </div>
                        <span style="font-size:12px; font-weight:{{ $subActivo ? '600' : '400' }};
                                     color:{{ $subActivo ? '#fff' : 'rgba(255,255,255,.70)' }};">
                            {{ $sub->name }}
                        </span>
                    </a>
                    @endif

                    @endforeach
                </div>

            </div>
            @endforeach

            @if ($navModulos->isEmpty())
            <p style="padding:24px 12px; font-size:11px; color:rgba(255,255,255,.25); text-align:center;">Sin módulos asignados.</p>
            @endif
        </nav>

        {{-- ── Usuario + Logout ── --}}
        <div style="padding:12px 10px; border-top:1px solid rgba(255,255,255,.07); flex-shrink:0;">
            <div class="flex items-center gap-2.5" style="margin-bottom:10px; overflow:hidden;">
                {{-- Avatar --}}
                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($navUser->name, 0, 2)) }}
                </div>
                {{-- Info usuario --}}
                <div class="nav-label" :style="sidebarCollapsed ? 'opacity:0;width:0;' : 'opacity:1;'" style="flex:1; min-width:0;">
                    <p style="font-size:12px; font-weight:600; color:rgba(255,255,255,.85); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $navUser->name }}</p>
                    <p style="font-size:10px; color:rgba(255,255,255,.35); text-transform:capitalize;">{{ $navUser->getRoleNames()->first() }}</p>
                </div>
            </div>
            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="nav-item-wrap w-full flex items-center gap-2.5 transition-all"
                        style="padding:8px 10px; border-radius:8px; background:rgba(239,68,68,.12); border:none; cursor:pointer; font-family:'Inter',sans-serif; position:relative;">
                    <span class="nav-tooltip">Cerrar sesión</span>
                    <div style="width:24px; height:24px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="15" height="15" fill="none" stroke="rgba(239,68,68,.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <span class="nav-label" :style="sidebarCollapsed ? 'opacity:0;width:0;' : 'opacity:1;'"
                          style="font-size:12px; font-weight:500; color:rgba(239,68,68,.8); white-space:nowrap;">
                        Cerrar sesión
                    </span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══ MAIN ═══ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex items-center gap-3 px-4 flex-shrink-0"
                style="background:#fff; border-bottom:1px solid #E5E7EB; min-height:56px; box-shadow:0 1px 3px rgba(0,0,0,.04);">

            {{-- Hamburger mobile --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden flex items-center justify-center"
                    style="width:32px; height:32px; border-radius:8px; background:#F3F4F6; border:none; cursor:pointer; flex-shrink:0;">
                <svg class="w-4 h-4" style="color:#6B7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                @if($activeModuloName)
                <span style="font-size:11px; font-weight:600; color:#9CA3AF; letter-spacing:.5px; text-transform:uppercase; white-space:nowrap;">
                    {{ $activeModuloName }}
                </span>
                <span style="color:#D1D5DB; font-size:11px;">/</span>
                @endif
                <span style="font-size:14px; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $pageTitle }}
                </span>
            </div>

            <div style="font-size:11px; color:#D1D5DB; flex-shrink:0;" class="hidden sm:block">
                {{ now()->format('d M Y') }}
            </div>
        </header>

        {{-- Flash --}}
        @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-4 mt-3 px-4 py-3 text-sm font-medium"
             style="border-radius:8px; {{ session('success')
                 ? 'background:#DCFCE7; color:#166534; border:1px solid #BBF7D0;'
                 : 'background:#FEE2E2; color:#991B1B; border:1px solid #FECACA;' }}">
            {{ session('success') ?? session('error') }}
        </div>
        @endif

        {{-- Contenido --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
