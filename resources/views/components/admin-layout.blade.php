@props(['title' => ''])
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Essen</title>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
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
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-item-active {
            background: rgba(190,24,93,.15);
            color: #FB7185;
            border-left: 3px solid #BE185D;
            padding-left: 13px;
        }
        .sidebar-item-inactive {
            color: #A8A29E;
            border-left: 3px solid transparent;
            padding-left: 13px;
        }
        .sidebar-item-inactive:hover {
            background: rgba(255,255,255,.04);
            color: #D6D3D1;
        }
        .sidebar-sub-active {
            color: #FB7185;
            background: rgba(190,24,93,.1);
        }
        .sidebar-sub-inactive {
            color: #78716C;
        }
        .sidebar-sub-inactive:hover {
            color: #D6D3D1;
            background: rgba(255,255,255,.04);
        }
        .sidebar-leaf-active {
            color: #FB7185;
            background: rgba(190,24,93,.1);
        }
        .sidebar-leaf-inactive {
            color: #78716C;
        }
        .sidebar-leaf-inactive:hover {
            color: #D6D3D1;
            background: rgba(255,255,255,.04);
        }
        /* Scrollbar del sidebar */
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #292524; border-radius: 4px; }
    </style>
    @livewireStyles
</head>
<body style="background:#F5F0ED;" class="font-sans antialiased">

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

    $moduloActivoHeader = $navModulos->first(function ($m) {
        return $m->submodulosVisibles->contains(function ($s) {
            if (!$s->isGroup()) return $s->route_name && request()->routeIs($s->route_name);
            return $s->childrenVisibles->contains(fn($ch) => $ch->route_name && request()->routeIs($ch->route_name));
        });
    });
    $activeModuloSlug = $moduloActivoHeader?->slug ?? '';
    $activeModuloName = $moduloActivoHeader?->name ?? config('app.name');
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
         style="background:rgba(28,25,23,.6);"></div>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="-translate-x-full md:translate-x-0
                  fixed inset-y-0 left-0 z-50 w-64
                  md:static md:inset-auto md:z-auto
                  flex flex-col
                  shadow-2xl md:shadow-none
                  transition-transform duration-300 ease-in-out"
           style="background:#374151;"
           :class="{ 'translate-x-0': sidebarOpen }">

        {{-- Logo / Marca --}}
        <div style="padding:20px 20px 18px; border-bottom:1px solid rgba(255,255,255,.06); flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:38px; height:38px; background:#BE185D; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:18px; font-weight:800; color:#fff; letter-spacing:2.5px; line-height:1;">ESSEN</p>
                    <p style="font-size:9px; color:#57534E; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; margin-top:3px;">{{ $navUser->getRoleNames()->first() }}</p>
                </div>
            </div>
        </div>

        {{-- Panel Inicio --}}
        @php
            $dashActivo = request()->routeIs('administrativo.dashboard');
        @endphp
        <div style="padding:10px 12px 4px; flex-shrink:0;">
            <a href="{{ route('administrativo.dashboard') }}"
               class="flex items-center gap-3 rounded-xl text-sm font-medium transition-all"
               style="padding:10px 13px; {{ $dashActivo ? 'background:rgba(190,24,93,.15); color:#FB7185; border-left:3px solid #BE185D; padding-left:10px;' : 'color:#A8A29E; border-left:3px solid transparent; padding-left:10px;' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Panel Inicio
            </a>
        </div>

        {{-- Navegación dinámica --}}
        <nav class="sidebar-nav flex-1 overflow-y-auto py-2 space-y-1" style="padding:8px 12px;"
             x-data="{ activeModule: '{{ $activeModuloSlug }}' }">

            @foreach ($navModulos as $modulo)
            @php $slug = $modulo->slug; @endphp

            <div>
                {{-- Botón de módulo --}}
                <button @click="activeModule = (activeModule === '{{ $slug }}' ? '' : '{{ $slug }}')"
                        class="w-full flex items-center gap-3 rounded-xl text-sm font-medium transition-all"
                        :style="activeModule === '{{ $slug }}'
                            ? 'background:rgba(190,24,93,.15); color:#FB7185; border-left:3px solid #BE185D; padding:10px 12px 10px 10px;'
                            : 'color:#A8A29E; border-left:3px solid transparent; padding:10px 12px 10px 10px;'"
                        @mouseenter="if(activeModule !== '{{ $slug }}') $event.target.style.background='rgba(255,255,255,.04)'; $event.target.style.color='#D6D3D1';"
                        @mouseleave="if(activeModule !== '{{ $slug }}') $event.target.style.background=''; $event.target.style.color='#A8A29E';">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modulo->icon }}"/>
                    </svg>
                    <span class="flex-1 text-left">{{ $modulo->name }}</span>
                    <svg :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''"
                         class="w-3.5 h-3.5 transition-transform flex-shrink-0"
                         style="color:#57534E;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Submenú --}}
                <div x-show="activeModule === '{{ $slug }}'"
                     x-transition:enter="transition-all duration-150 ease-out"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-1 space-y-0.5"
                     style="margin-left:16px; padding-left:12px; border-left:1px solid rgba(255,255,255,.06);">

                    @foreach ($modulo->submodulosVisibles as $sub)

                    @if ($sub->isGroup())
                    @php
                        $grupActivo = $sub->childrenVisibles->contains(
                            fn($ch) => $ch->route_name && request()->routeIs($ch->route_name)
                        );
                    @endphp
                    <div x-data="{ subOpen: {{ $grupActivo ? 'true' : 'false' }} }">
                        <button @click="subOpen = !subOpen"
                                class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all"
                                style="{{ $grupActivo ? 'color:#FB7185; background:rgba(190,24,93,.1);' : 'color:#78716C;' }}">
                            @if(isset($subIconos[$sub->slug]))
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$sub->slug] }}"/>
                            </svg>
                            @endif
                            <span class="flex-1 text-left font-medium">{{ $sub->name }}</span>
                            <svg :class="subOpen ? 'rotate-180':''"
                                 class="w-3 h-3 transition-transform flex-shrink-0"
                                 style="color:#57534E;"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="subOpen"
                             class="mt-0.5 space-y-0.5"
                             style="margin-left:12px; padding-left:10px; border-left:1px solid rgba(255,255,255,.04);">
                            @foreach ($sub->childrenVisibles as $child)
                            @php
                                $childActivo = $child->route_name && request()->routeIs($child->route_name);
                                $href = ($child->route_name && RouteHelper::has($child->route_name)) ? route($child->route_name) : '#';
                            @endphp
                            <a href="{{ $href }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs transition-all"
                               style="{{ $childActivo ? 'color:#FB7185; background:rgba(190,24,93,.1); font-weight:600;' : 'color:#78716C;' }}">
                                @if(isset($subIconos[$child->slug]))
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$child->slug] }}"/>
                                </svg>
                                @else
                                <span class="w-1 h-1 rounded-full flex-shrink-0"
                                      style="background:{{ $childActivo ? '#BE185D' : '#44403C' }};"></span>
                                @endif
                                {{ $child->name }}
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
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all"
                       style="{{ $subActivo ? 'color:#FB7185; background:rgba(190,24,93,.1); font-weight:600;' : 'color:#78716C;' }}">
                        @if(isset($subIconos[$sub->slug]))
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $subIconos[$sub->slug] }}"/>
                        </svg>
                        @else
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                              style="background:{{ $subActivo ? '#BE185D' : '#44403C' }};"></span>
                        @endif
                        {{ $sub->name }}
                    </a>
                    @endif

                    @endforeach
                </div>
            </div>
            @endforeach

            @if ($navModulos->isEmpty())
            <p class="px-3 py-6 text-xs text-center" style="color:#57534E;">
                Sin módulos asignados.
            </p>
            @endif
        </nav>

        {{-- Usuario + Logout --}}
        <div style="padding:14px 16px; border-top:1px solid rgba(255,255,255,.06); flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px; padding:0 4px;">
                <div style="width:34px; height:34px; border-radius:50%; background:#BE185D; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($navUser->name, 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:600; color:#D6D3D1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $navUser->name }}</p>
                    <p style="font-size:10px; color:#57534E; text-transform:capitalize;">{{ $navUser->getRoleNames()->first() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-xl text-sm font-medium transition-colors"
                        style="padding:9px; background:rgba(220,38,38,.1); color:#FCA5A5; border:none; cursor:pointer; font-family:'Inter',sans-serif;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══ MAIN ═══ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-4 py-3 flex-shrink-0"
                style="background:#374151; border-bottom:1px solid rgba(255,255,255,.06);">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden flex items-center justify-center rounded-lg transition-colors"
                    style="min-width:44px; min-height:44px; color:#A8A29E; background:rgba(255,255,255,.05); border:none; cursor:pointer;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1">
                <h1 class="text-sm font-semibold" style="color:#fff;">@yield('page-title', 'Panel')</h1>
                @if($moduloActivoHeader)
                <p class="text-xs" style="color:#57534E;">{{ $activeModuloName }}</p>
                @endif
            </div>
            <div class="text-xs hidden sm:block" style="color:#57534E;">{{ now()->format('d/m/Y') }}</div>
        </header>

        {{-- Flash messages --}}
        @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-4 mt-3 px-4 py-3 rounded-xl text-sm font-medium"
             style="{{ session('success') ? 'background:#DCFCE7; color:#166534; border:1px solid #BBF7D0;' : 'background:#FEE2E2; color:#991B1B; border:1px solid #FECACA;' }}">
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
