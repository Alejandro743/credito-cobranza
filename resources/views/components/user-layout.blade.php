@props(['title' => '', 'noHeader' => false, 'noPadding' => false])
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Crediessen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    lavanda:   { 50:'#F0F4F7',100:'#FFFFE3',200:'#CBCBCB',300:'#A8B8C8',400:'#8FA0B0',500:'#6D8196',600:'#5C6F80',700:'#4A4A4A' },
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
        .sidebar-nav::-webkit-scrollbar       { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
        .sidebar-wrap {
            background: #0B1120;
            transition: width 0.28s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        .nav-label {
            transition: opacity 0.2s ease, width 0.28s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        .nav-tooltip {
            display: none;
            position: absolute;
            left: 60px; top: 50%;
            transform: translateY(-50%);
            background: #1E2A50; color: #fff;
            font-size: 11px; font-weight: 600;
            padding: 5px 10px; border-radius: 6px;
            white-space: nowrap; pointer-events: none;
            z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,.3);
        }
        .nav-item-wrap:hover .nav-tooltip { display: block; }
        aside .nav-item-wrap,
        .sidebar-nav button.w-full,
        .sidebar-nav a.flex { transition: box-shadow 0.15s ease; }
        aside .nav-item-wrap:hover       { box-shadow: inset 0 0 0 100px rgba(255,255,255,.07); }
        .sidebar-nav button.w-full:hover { box-shadow: inset 0 0 0 100px rgba(255,255,255,.06); }
        .sidebar-nav a.flex:hover        { box-shadow: inset 0 0 0 100px rgba(255,255,255,.06); }
        form .nav-item-wrap:hover        { box-shadow: inset 0 0 0 100px rgba(239,68,68,.08); }
    </style>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/crediessen.css') }}?v={{ @filemtime(public_path('css/crediessen.css')) }}">
</head>
<body style="background:#F0F2F5;" class="font-sans antialiased">

@php
use App\Services\PermisoService;

$subIconos = [
    'seguridad'                                 => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    'catalogo'                                  => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
    'definiciones'                              => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
    'config-ciclo'                              => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
    'credito-gestion'                           => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    'credito-pagos'                             => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    'credito-reprogramacion'                    => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'credito-indicadores'                       => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    'vendedor-gestion-planes'                   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    'seg-usuarios'                              => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    'seg-roles'                                 => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
    'cat-productos'                             => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
    'cat-categorias'                            => 'M3 7a2 2 0 012-2h3.5l2 2H19a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z',
    'cat-unidades'                              => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z',
    'cat-listas'                                => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    'def-correlativo'                           => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
    'def-peso-indicadores'                      => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
    'def-rango-calificacion'                    => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
    'def-motivo-cierre'                         => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z',
    'ciclos-comerciales'                        => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
    'ciclo-puntos'                              => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'matriz-financiera'                         => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    'credito-clientes'                          => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'credito-espera'                            => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'credito-revision'                          => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
    'credito-aprobado'                          => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'credito-cerrado'                           => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8',
    'credito-cobranza'                          => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    'credito-reprog-nueva'                      => 'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    'credito-reprog-historial'                  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'credito-pagos-pasarela'                    => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    'credito-pagos-manuales'                    => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
    'credito-pagos-historial'                   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    'credito-indicadores-calificacion'          => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    'credito-indicadores-calificacion-clientes' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'vendedor-clientes'                         => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'vendedor-oferta'                           => 'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    'vendedor-pedidos'                          => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    'vendedor-pagos-saldos'                     => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    'cliente-cuenta'                            => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'cliente-pedidos'                           => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
    'cliente-plan'                              => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    'cliente-cuotas'                            => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'cliente-pagos'                             => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'cliente-mi-calificacion'                   => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
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
$pageTitle = $activeLeaf?->name ?? ($activeModuloName ?: 'Panel de Inicio');

$dashRoute = match($navUser->tipo ?? '') {
    'vendedor' => route('vendedor.dashboard'),
    'cliente'  => route('cliente.dashboard'),
    default    => route('administrativo.dashboard'),
};
$dashActivo = request()->routeIs('administrativo.dashboard')
           || request()->routeIs('vendedor.dashboard')
           || request()->routeIs('cliente.dashboard');
@endphp

<div class="flex h-screen overflow-hidden"
     x-on:open-sidebar.window="sidebarOpen = true">

    @include('partials.sidebar')

    {{-- ═══ MAIN ═══ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        @unless($noHeader)
        {{-- Topbar --}}
        <header class="flex items-center gap-3 px-4 flex-shrink-0"
                style="background:#fff; border-bottom:1px solid #E5E7EB; min-height:56px; box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden flex items-center justify-center"
                    style="width:32px; height:32px; border-radius:8px; background:#F3F4F6; border:none; cursor:pointer; flex-shrink:0;">
                <svg class="w-4 h-4" style="color:#6B7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-1.5 flex-1 min-w-0">
                @if($activeModuloName)
                <span style="font-size:11px; font-weight:600; color:#9CA3AF; letter-spacing:.5px; text-transform:uppercase; white-space:nowrap;">{{ $activeModuloName }}</span>
                <span style="color:#D1D5DB; font-size:11px;">/</span>
                @endif
                <span style="font-size:14px; font-weight:700; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $pageTitle }}</span>
            </div>
            <div style="font-size:11px; color:#D1D5DB; flex-shrink:0;" class="hidden sm:block">{{ now()->format('d M Y') }}</div>
        </header>
        @endunless

        {{-- Flash --}}
        @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-4 mt-3 px-4 py-3 text-sm font-medium"
             style="border-radius:8px; {{ session('success') ? 'background:#DCFCE7; color:#166534; border:1px solid #BBF7D0;' : 'background:#FEE2E2; color:#991B1B; border:1px solid #FECACA;' }}">
            {{ session('success') ?? session('error') }}
        </div>
        @endif

        <main class="{{ $noPadding ? 'p-0' : 'p-4 sm:p-6' }} flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
