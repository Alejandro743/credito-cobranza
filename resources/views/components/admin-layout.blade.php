@props(['title' => ''])
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Admin</title>
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
        'lavanda'   => ['badge'=>'bg-lavanda-100 text-lavanda-600','icon_stroke'=>'text-lavanda-400','active_btn'=>'bg-lavanda-200 text-lavanda-700 font-semibold','sub_active_btn'=>'bg-lavanda-100 text-lavanda-700 font-medium','active_link'=>'bg-lavanda-50 text-lavanda-600 font-medium','dot_active'=>'bg-lavanda-500','header_bg'=>'bg-lavanda-100','header_border'=>'border-lavanda-200','header_text'=>'text-lavanda-700','header_sub'=>'text-lavanda-500','header_btn'=>'text-lavanda-600','header_btn_hover'=>'hover:bg-lavanda-200'],
        'mint'      => ['badge'=>'bg-mint-100 text-mint-600','icon_stroke'=>'text-mint-400','active_btn'=>'bg-mint-200 text-mint-700 font-semibold','sub_active_btn'=>'bg-mint-100 text-mint-700 font-medium','active_link'=>'bg-mint-50 text-mint-600 font-medium','dot_active'=>'bg-mint-500','header_bg'=>'bg-mint-100','header_border'=>'border-mint-200','header_text'=>'text-mint-700','header_sub'=>'text-mint-500','header_btn'=>'text-mint-600','header_btn_hover'=>'hover:bg-mint-200'],
        'melocoton' => ['badge'=>'bg-melocoton-100 text-melocoton-600','icon_stroke'=>'text-melocoton-400','active_btn'=>'bg-melocoton-200 text-melocoton-700 font-semibold','sub_active_btn'=>'bg-melocoton-100 text-melocoton-700 font-medium','active_link'=>'bg-melocoton-50 text-melocoton-600 font-medium','dot_active'=>'bg-melocoton-500','header_bg'=>'bg-melocoton-100','header_border'=>'border-melocoton-200','header_text'=>'text-melocoton-700','header_sub'=>'text-melocoton-500','header_btn'=>'text-melocoton-600','header_btn_hover'=>'hover:bg-melocoton-200'],
        'celeste'   => ['badge'=>'bg-celeste-100 text-celeste-600','icon_stroke'=>'text-celeste-400','active_btn'=>'bg-celeste-200 text-celeste-700 font-semibold','sub_active_btn'=>'bg-celeste-100 text-celeste-700 font-medium','active_link'=>'bg-celeste-50 text-celeste-600 font-medium','dot_active'=>'bg-celeste-500','header_bg'=>'bg-celeste-100','header_border'=>'border-celeste-200','header_text'=>'text-celeste-700','header_sub'=>'text-celeste-500','header_btn'=>'text-celeste-600','header_btn_hover'=>'hover:bg-celeste-200'],
    ];

    $subIconos = [
        // Vendedor
        'vendedor-clientes'       => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'vendedor-gestion-planes' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'vendedor-oferta'         => 'M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'vendedor-pedidos'        => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'vendedor-pagos-saldos'   => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        // Crédito
        'credito-gestion'         => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'credito-clientes'        => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'credito-espera'          => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-revision'        => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'credito-aprobado'        => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'credito-cobranza'        => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
        'credito-reprogramacion'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    ];

    $navUser = auth()->user();

    // Carga módulos con submodulos raíz activos y sus hijos activos
    $navModulos = \App\Models\Modulo::with([
            'submodulosActivos' => fn($q) => $q->with([
                'children' => fn($q2) => $q2->where('active', true)->orderBy('sort_order')
            ])
        ])
        ->where('active', true)
        ->orderBy('sort_order')
        ->get()
        ->map(function ($modulo) use ($navUser) {
            // Para cada submodulo raíz, calcular visibilidad
            $modulo->submodulosActivos->each(function ($sub) use ($navUser) {
                if ($sub->isGroup()) {
                    // Subgrupo: visible si algún hijo tiene puede_ver
                    $sub->childrenVisibles = $sub->children->filter(
                        fn($child) => PermisoService::check($navUser, $child->slug)
                    );
                    $sub->esVisible = $sub->childrenVisibles->isNotEmpty();
                } else {
                    // Hoja directa
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

<div class="flex h-screen overflow-hidden">

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
                  bg-white border-r border-lavanda-100 flex flex-col
                  shadow-xl md:shadow-none
                  transition-transform duration-300 ease-in-out"
           :class="{ 'translate-x-0': sidebarOpen }">

        <!-- Cabecera -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-lavanda-100 bg-lavanda-50">
            <div class="w-9 h-9 rounded-xl bg-lavanda-500 flex items-center justify-center text-white flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-gray-800 text-sm leading-tight">{{ config('app.name') }}</p>
                <p class="text-xs text-lavanda-600 font-semibold capitalize">{{ $navUser->getRoleNames()->first() }}</p>
            </div>
        </div>

        <!-- Dashboard — siempre visible, fuera del área scrollable -->
        @php
            $dashRoute  = $navUser->hasRole('admin') ? route('admin.dashboard') : route('dashboard');
            $dashActivo = request()->routeIs('admin.dashboard');
        @endphp
        <div class="px-3 pt-3 pb-1 flex-shrink-0">
            <a href="{{ $dashRoute }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ $dashActivo ? 'bg-lavanda-100 text-lavanda-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                Dashboard
            </a>
        </div>

        <!-- Navegación dinámica — scrollable -->
        <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1"
             x-data="{ activeModule: '{{ $activeModuloSlug }}' }">

            {{-- Módulos --}}
            @foreach ($navModulos as $modulo)
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
                        <svg class="w-4 h-4 flex-shrink-0 {{ $c['icon_stroke'] }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modulo->icon }}"/>
                        </svg>
                        <span class="flex-1 text-left">{{ $modulo->name }}</span>
                        <svg :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''"
                             class="w-3.5 h-3.5 transition-transform flex-shrink-0 text-gray-400"
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
                                {{-- Subgrupo con hijos (2.º nivel) --}}
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
            @endforeach

            @if ($navModulos->isEmpty())
                <p class="px-3 py-6 text-xs text-center text-gray-400">
                    Sin módulos.<br>Ejecuta <code class="text-lavanda-500">db:seed</code>
                </p>
            @endif
        </nav>

        <!-- Usuario + Logout -->
        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3 px-1 mb-3">
                <div class="w-9 h-9 rounded-full bg-lavanda-200 flex items-center justify-center text-lavanda-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($navUser->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $navUser->name }}</p>
                    <p class="text-xs text-gray-400 truncate capitalize">{{ $navUser->getRoleNames()->first() }}</p>
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

        <!-- Topbar -->
        <header class="flex items-center gap-4 px-4 py-3 border-b shadow-sm {{ $hBg }} {{ $hBorder }}">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 min-h-[44px] min-w-[44px] flex items-center justify-center rounded-lg transition-colors {{ $hBtnText }} {{ $hBtnHover }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1">
                <h1 class="text-base font-semibold {{ $hText }}">@yield('page-title', 'Panel Administrativo')</h1>
            </div>
            <div class="text-xs hidden sm:block {{ $hSubText }}">{{ now()->format('d/m/Y') }}</div>
        </header>

        <!-- Flash messages -->
        @if (session('success') || session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-4 mt-3 px-4 py-3 rounded-xl text-sm font-medium
                        {{ session('success') ? 'bg-mint-100 text-mint-800 border border-mint-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <!-- Contenido -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
