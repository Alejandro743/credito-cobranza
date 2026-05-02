@props(['title' => '', 'noHeader' => false, 'noPadding' => false])
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lavanda:   { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9' },
                        mint:      { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d' },
                        melocoton: { 50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c' },
                        celeste:   { 50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#0369a1' },
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">

@php
    use App\Services\PermisoService;
    use Illuminate\Support\Facades\Route as RouteHelper;

    $paleta = [
        'lavanda'   => ['badge'=>'bg-lavanda-100 text-lavanda-600',   'icon'=>'text-lavanda-400',   'active_btn'=>'bg-lavanda-200 text-lavanda-700 font-semibold',   'sub_active_btn'=>'bg-lavanda-100 text-lavanda-700 font-medium',   'active_link'=>'bg-lavanda-50 text-lavanda-600 font-medium',   'dot_active'=>'bg-lavanda-500',   'header_bg'=>'bg-lavanda-100',   'header_border'=>'border-lavanda-200',   'header_text'=>'text-lavanda-700',   'header_sub'=>'text-lavanda-500',   'header_btn'=>'text-lavanda-600',   'header_btn_hover'=>'hover:bg-lavanda-200'],
        'mint'      => ['badge'=>'bg-mint-100 text-mint-600',         'icon'=>'text-mint-400',       'active_btn'=>'bg-mint-200 text-mint-700 font-semibold',         'sub_active_btn'=>'bg-mint-100 text-mint-700 font-medium',         'active_link'=>'bg-mint-50 text-mint-600 font-medium',         'dot_active'=>'bg-mint-500',       'header_bg'=>'bg-mint-100',       'header_border'=>'border-mint-200',       'header_text'=>'text-mint-700',       'header_sub'=>'text-mint-500',       'header_btn'=>'text-mint-600',       'header_btn_hover'=>'hover:bg-mint-200'],
        'melocoton' => ['badge'=>'bg-melocoton-100 text-melocoton-600','icon'=>'text-melocoton-400', 'active_btn'=>'bg-melocoton-200 text-melocoton-700 font-semibold','sub_active_btn'=>'bg-melocoton-100 text-melocoton-700 font-medium','active_link'=>'bg-melocoton-50 text-melocoton-600 font-medium', 'dot_active'=>'bg-melocoton-500', 'header_bg'=>'bg-melocoton-100', 'header_border'=>'border-melocoton-200', 'header_text'=>'text-melocoton-700', 'header_sub'=>'text-melocoton-500', 'header_btn'=>'text-melocoton-600', 'header_btn_hover'=>'hover:bg-melocoton-200'],
        'celeste'   => ['badge'=>'bg-celeste-100 text-celeste-600',    'icon'=>'text-celeste-400',   'active_btn'=>'bg-celeste-200 text-celeste-700 font-semibold',   'sub_active_btn'=>'bg-celeste-100 text-celeste-700 font-medium',   'active_link'=>'bg-celeste-50 text-celeste-600 font-medium',   'dot_active'=>'bg-celeste-500',   'header_bg'=>'bg-celeste-100',   'header_border'=>'border-celeste-200',   'header_text'=>'text-celeste-700',   'header_sub'=>'text-celeste-500',   'header_btn'=>'text-celeste-600',   'header_btn_hover'=>'hover:bg-celeste-200'],
    ];

    $subIconos = [
        // ── Administrativo ──────────────────────────────────────────
        'seguridad'               => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'seg-usuarios'            => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'seg-roles'               => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
        'catalogo'                => 'M4 6h16M4 10h16M4 14h16M4 18h16',
        'cat-productos'           => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'cat-categorias'          => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z',
        'cat-unidades'            => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
        'cat-listas'              => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'definiciones'            => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
        'def-correlativo'         => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
        'def-peso-indicadores'    => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
        'def-rango-calificacion'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'config-ciclo'            => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'ciclos-comerciales'      => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'ciclo-puntos'            => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        'matriz-financiera'       => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        // ── Crédito / Cobranza ──────────────────────────────────────
        'credito-gestion'         => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'credito-clientes'        => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'credito-espera'          => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-revision'        => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'credito-aprobado'        => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-cobranza'        => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'credito-reprogramacion'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'credito-reprog-nueva'    => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-reprog-historial'=> 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-pagos'           => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'credito-pagos-pasarela'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'credito-pagos-manuales'  => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
        'credito-pagos-historial' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'credito-indicadores'           => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'credito-indicadores-calificacion' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        // ── Vendedor / EIE ──────────────────────────────────────────
        'vendedor-clientes'       => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'vendedor-gestion-planes' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'vendedor-oferta'         => 'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'vendedor-pedidos'        => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'vendedor-pagos-saldos'   => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        // ── Cliente ─────────────────────────────────────────────────
        'cliente-cuenta'          => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'cliente-pedidos'         => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'cliente-plan'            => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        'cliente-cuotas'          => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'cliente-pagos'           => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    ];

    $navUser   = auth()->user();
    $navRol    = $navUser->getRoleNames()->first() ?? '—';

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

    // Detectar módulo activo para colorear el topbar
    $moduloActivoHeader = $navModulos->first(function ($m) {
        return $m->submodulosVisibles->contains(function ($s) {
            if (!$s->isGroup()) return $s->route_name && request()->routeIs($s->route_name);
            return $s->childrenVisibles->contains(fn($ch) => $ch->route_name && request()->routeIs($ch->route_name));
        });
    });
    $activeModuloSlug = $moduloActivoHeader?->slug ?? '';
    $hP        = $moduloActivoHeader ? $paleta[$moduloActivoHeader->color] : null;
    $hBg       = $hP ? $hP['header_bg']       : 'bg-white';
    $hBorder   = $hP ? $hP['header_border']   : 'border-gray-100';
    $hText     = $hP ? $hP['header_text']     : 'text-gray-800';
    $hSubText  = $hP ? $hP['header_sub']      : 'text-gray-400';
    $hBtnText  = $hP ? $hP['header_btn']      : 'text-gray-500';
    $hBtnHover = $hP ? $hP['header_btn_hover']: 'hover:bg-gray-100';
@endphp

<div class="flex h-screen overflow-hidden"
     x-on:open-sidebar.window="sidebarOpen = true">

    <!-- Overlay móvil — solo visible cuando sidebar está abierto en mobile -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

    <!-- ═══ SIDEBAR ═══ -->
    <!-- Mobile: fixed, oculto con -translate-x-full; abierto agrega translate-x-0 -->
    <!-- Desktop (md+): static en el flujo, siempre visible con md:translate-x-0 -->
    <aside class="-translate-x-full md:translate-x-0
                  fixed inset-y-0 left-0 z-50 w-64
                  md:static md:inset-auto md:z-auto
                  bg-white border-r border-gray-100 flex flex-col
                  shadow-xl md:shadow-none
                  transition-transform duration-300 ease-in-out"
           :class="{ 'translate-x-0': sidebarOpen }">

        <!-- Cabecera -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 bg-gray-50">
            <div class="w-9 h-9 rounded-xl bg-lavanda-500 flex items-center justify-center text-white flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-800 text-sm leading-tight">{{ config('app.name') }}</p>
                <p class="text-xs text-lavanda-600 font-semibold capitalize">{{ $navRol }}</p>
            </div>
        </div>

        <!-- Navegación -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1"
             x-data="{ activeModule: '{{ $activeModuloSlug }}' }">

            @forelse ($navModulos as $modulo)
                @php
                    $c           = $paleta[$modulo->color] ?? $paleta['lavanda'];
                    $slug        = $modulo->slug;
                    $btnActivo   = $c['active_btn'];
                    $btnInactivo = 'font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900';
                @endphp

                <div>
                    <button @click="activeModule = (activeModule === '{{ $slug }}' ? '' : '{{ $slug }}')"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all"
                            :class="activeModule === '{{ $slug }}' ? '{{ $btnActivo }}' : '{{ $btnInactivo }}'">
                        <svg class="w-4 h-4 flex-shrink-0 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modulo->icon }}"/>
                        </svg>
                        <span class="flex-1 text-left">{{ $modulo->name }}</span>
                        <svg :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform text-gray-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="activeModule === '{{ $slug }}'"
                         x-transition:enter="transition-all duration-150 ease-out"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="ml-5 mt-1 space-y-0.5 border-l-2 border-gray-100 pl-3">

                        @foreach ($modulo->submodulosVisibles as $sub)
                            @if ($sub->isGroup())
                                {{-- Subgrupo con hijos --}}
                                @php
                                    $grupActivo = $sub->childrenVisibles->contains(
                                        fn($ch) => $ch->route_name && request()->routeIs($ch->route_name)
                                    );
                                @endphp
                                <div x-data="{ subOpen: {{ $grupActivo ? 'true' : 'false' }} }">
                                    <button @click="subOpen = !subOpen"
                                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all
                                                   {{ $grupActivo ? $c['sub_active_btn'] : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                                        @if(isset($subIconos[$sub->slug]))
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$sub->slug] }}"/>
                                        </svg>
                                        @endif
                                        <span class="flex-1 text-left font-medium">{{ $sub->name }}</span>
                                        <svg :class="subOpen ? 'rotate-180':''"
                                             class="w-3 h-3 transition-transform text-gray-400 flex-shrink-0"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="subOpen" class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-gray-100 pl-3">
                                        @foreach ($sub->childrenVisibles as $child)
                                            @php
                                                $childActivo = $child->route_name && request()->routeIs($child->route_name);
                                                $href = ($child->route_name && RouteHelper::has($child->route_name))
                                                    ? route($child->route_name) : '#';
                                            @endphp
                                            <a href="{{ $href }}"
                                               class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition-all
                                                      {{ $childActivo ? $c['active_link'] : 'text-gray-400 hover:text-gray-700 hover:bg-gray-50' }}">
                                                @if(isset($subIconos[$child->slug]))
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$child->slug] }}"/>
                                                </svg>
                                                @else
                                                <span class="w-1 h-1 rounded-full flex-shrink-0 {{ $childActivo ? $c['dot_active'] : 'bg-gray-300' }}"></span>
                                                @endif
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                {{-- Hoja directa --}}
                                @php
                                    $subActivo = $sub->route_name && request()->routeIs($sub->route_name);
                                    $href = ($sub->route_name && RouteHelper::has($sub->route_name))
                                        ? route($sub->route_name) : '#';
                                @endphp
                                <a href="{{ $href }}"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all
                                          {{ $subActivo ? $c['active_link'] : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                                    @if(isset($subIconos[$sub->slug]))
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$sub->slug] }}"/>
                                    </svg>
                                    @else
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $subActivo ? $c['dot_active'] : 'bg-gray-300' }}"></span>
                                    @endif
                                    {{ $sub->name }}
                                </a>
                            @endif
                        @endforeach

                    </div>
                </div>

            @empty
                <p class="px-3 py-8 text-xs text-center text-gray-400">
                    No tenés módulos asignados.<br>
                    Contactá al administrador.
                </p>
            @endforelse

        </nav>

        <!-- Usuario + Logout — SIEMPRE VISIBLE -->
        <div class="p-4 border-t border-gray-100 bg-white">
            <div class="flex items-center gap-3 mb-3 px-1">
                <div class="w-9 h-9 rounded-full bg-lavanda-100 flex items-center justify-center text-lavanda-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($navUser->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $navUser->name }}</p>
                    <p class="text-xs text-gray-400 truncate capitalize">{{ $navRol }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl
                               bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══ MAIN ═══ -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        @unless($noHeader)
        <header class="flex items-center gap-4 px-4 py-3 border-b shadow-sm {{ $hBg }} {{ $hBorder }}">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 min-h-[44px] min-w-[44px] flex items-center justify-center rounded-lg transition-colors {{ $hBtnText }} {{ $hBtnHover }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1">
                <h1 class="text-base font-semibold {{ $hText }}">@yield('page-title', 'Panel')</h1>
            </div>
            <div class="text-xs hidden sm:block {{ $hSubText }}">{{ now()->format('d/m/Y') }}</div>
        </header>
        @endunless

        <main class="{{ $noPadding ? 'p-0' : 'p-4 sm:p-6' }} flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
