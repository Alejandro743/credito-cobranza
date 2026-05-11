@props(['title' => ''])
<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
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
                    // Paleta pizarra — acento uniforme (reemplaza lavanda)
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
                    // Verde semántico — estados positivos (pagado, activo)
                    mint: {
                        50:  '#F0F4F7',
                        100: '#E8F0F7',
                        200: '#D4E0EA',
                        500: '#6D8196',
                        600: '#5C6F80',
                        700: '#4A4A4A',
                    },
                    // Ámbar semántico → pizarra uniforme
                    melocoton: {
                        50:  '#F0F4F7',
                        100: '#E8F0F7',
                        200: '#D4E0EA',
                        500: '#6D8196',
                        600: '#5C6F80',
                        700: '#4A4A4A',
                    },
                    // Celeste semántico → pizarra uniforme
                    celeste: {
                        50:  '#F0F4F7',
                        100: '#E8F0F7',
                        200: '#D4E0EA',
                        500: '#6D8196',
                        600: '#5C6F80',
                        700: '#4A4A4A',
                    },
                }
            }
        }
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-nav::-webkit-scrollbar       { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #CBCBCB; border-radius: 4px; }
    </style>
    @livewireStyles
    {{-- Design system — carga ÚLTIMO para máxima prioridad --}}
    <link rel="stylesheet" href="{{ asset('css/crediessen.css') }}?v={{ @filemtime(public_path('css/crediessen.css')) }}">
</head>
<body style="background:#F5F5F0;" class="font-sans antialiased">

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

    // Colores vivos por submodulo [bgPastel, strokeVivo]
    $subIconColors = [
        'vendedor-clientes'       => ['#FCE7F3', '#EC4899'],
        'vendedor-gestion-planes' => ['#E0E7FF', '#6366F1'],
        'vendedor-oferta'         => ['#CFFAFE', '#06B6D4'],
        'vendedor-pedidos'        => ['#E0E7FF', '#6366F1'],
        'vendedor-pagos-saldos'   => ['#DBEAFE', '#3B82F6'],
        'credito-gestion'         => ['#D1FAE5', '#10B981'],
        'credito-clientes'        => ['#FCE7F3', '#EC4899'],
        'credito-espera'          => ['#FEF3C7', '#F59E0B'],
        'credito-revision'        => ['#FEF3C7', '#F59E0B'],
        'credito-aprobado'        => ['#D1FAE5', '#10B981'],
        'def-motivo-cierre'       => ['#EDE9FE', '#8B5CF6'],
        'credito-cobranza'        => ['#DBEAFE', '#3B82F6'],
        'credito-reprogramacion'  => ['#FFEDD5', '#F97316'],
    ];

    // Colores vivos por modulo [bgPastel, strokeVivo]
    $moduloIconColors = [
        'administrativo'   => ['#EDE9FE', '#8B5CF6'],
        'credito-cobranza' => ['#D1FAE5', '#10B981'],
        'vendedor-eie'     => ['#FCE7F3', '#EC4899'],
        'cliente'          => ['#DBEAFE', '#3B82F6'],
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

    // Leaf submodulo activo → page header automático
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
        : ($moduloActivo?->icon
            ?? 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6');
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
         style="background:rgba(74,74,74,.4);"></div>

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="-translate-x-full md:translate-x-0
                  fixed inset-y-0 left-0 z-50 w-64
                  md:static md:inset-auto md:z-auto
                  flex flex-col
                  shadow-xl md:shadow-none
                  transition-transform duration-300 ease-in-out"
           style="background:#fff; border-right:1px solid #CBCBCB;"
           :class="{ 'translate-x-0': sidebarOpen }">

        {{-- Logo / Marca --}}
        <div style="padding:18px 20px; border-bottom:1px solid #CBCBCB; background:#6D8196; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:38px; height:38px; background:rgba(255,255,255,.18); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:15px; font-weight:800; color:#fff; letter-spacing:2px; line-height:1;">CREDIESSEN</p>
                    <p style="font-size:9px; color:rgba(255,255,255,.6); font-weight:600; letter-spacing:1.5px; text-transform:uppercase; margin-top:3px;">{{ $navUser->getRoleNames()->first() }}</p>
                </div>
            </div>
        </div>

        {{-- Panel Inicio --}}
        @php $dashActivo = request()->routeIs('administrativo.dashboard'); @endphp
        <div style="padding:10px 12px 4px; flex-shrink:0;">
            <a href="{{ route('administrativo.dashboard') }}"
               class="flex items-center gap-3 text-sm font-medium transition-all"
               style="padding:9px 12px; border-left:3px solid {{ $dashActivo ? '#6D8196' : 'transparent' }};
                      background:{{ $dashActivo ? '#FFFFE3' : 'transparent' }};
                      color:{{ $dashActivo ? '#6D8196' : '#4A4A4A' }};
                      font-weight:{{ $dashActivo ? '600' : '500' }};">
                <div style="width:26px; height:26px; background:#D1FAE5; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="13" height="13" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                Panel Inicio
            </a>
        </div>

        {{-- Navegación dinámica --}}
        <nav class="sidebar-nav flex-1 overflow-y-auto"
             style="padding:4px 12px 8px;"
             x-data="{ activeModule: '{{ $activeModuloSlug }}' }">

            @foreach ($navModulos as $modulo)
            @php
                $slug = $modulo->slug;
                $mColors = $moduloIconColors[$slug] ?? ['#E5E7EB', '#6B7280'];
            @endphp
            <div style="margin-bottom:2px;">

                {{-- Botón módulo --}}
                <button @click="activeModule = (activeModule === '{{ $slug }}' ? '' : '{{ $slug }}')"
                        class="w-full flex items-center gap-3 text-sm transition-all"
                        style="padding:9px 12px; font-weight:500;"
                        :style="activeModule === '{{ $slug }}'
                            ? 'background:#FFFFE3; color:#6D8196; font-weight:600; border-left:3px solid #6D8196;'
                            : 'color:#4A4A4A; border-left:3px solid transparent; background:transparent;'">
                    <div style="width:26px; height:26px; background:{{ $mColors[0] }}; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="13" height="13" fill="none" stroke="{{ $mColors[1] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="{{ $modulo->icon }}"/>
                        </svg>
                    </div>
                    <span class="flex-1 text-left">{{ $modulo->name }}</span>
                    <svg :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''"
                         class="w-3.5 h-3.5 transition-transform flex-shrink-0"
                         style="color:#CBCBCB;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Submenú --}}
                <div x-show="activeModule === '{{ $slug }}'"
                     x-transition:enter="transition-all duration-150 ease-out"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="margin-left:14px; padding-left:10px; border-left:2px solid #CBCBCB; margin-top:2px;">

                    @foreach ($modulo->submodulosVisibles as $sub)
                    @php $sColors = $subIconColors[$sub->slug] ?? ['#E5E7EB', '#6B7280']; @endphp

                    @if ($sub->isGroup())
                    @php
                        $grupActivo = $sub->childrenVisibles->contains(
                            fn($ch) => $ch->route_name && request()->routeIs($ch->route_name)
                        );
                    @endphp
                    <div x-data="{ subOpen: {{ $grupActivo ? 'true' : 'false' }} }" style="margin-bottom:1px;">
                        <button @click="subOpen = !subOpen"
                                class="w-full flex items-center gap-2 text-sm transition-all"
                                style="padding:8px 10px; {{ $grupActivo ? 'color:#6D8196; font-weight:600; background:#FFFFE3;' : 'color:#4A4A4A; font-weight:500;' }}">
                            @if(isset($subIconos[$sub->slug]))
                            <div style="width:22px; height:22px; background:{{ $sColors[0] }}; border-radius:5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="11" height="11" fill="none" stroke="{{ $sColors[1] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="{{ $subIconos[$sub->slug] }}"/>
                                </svg>
                            </div>
                            @endif
                            <span class="flex-1 text-left">{{ $sub->name }}</span>
                            <svg :class="subOpen ? 'rotate-180':''"
                                 class="w-3 h-3 transition-transform flex-shrink-0"
                                 style="color:#CBCBCB;"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="subOpen"
                             style="margin-left:10px; padding-left:8px; border-left:2px solid #E5E7EB; margin-top:1px;">
                            @foreach ($sub->childrenVisibles as $child)
                            @php
                                $childActivo = $child->route_name && request()->routeIs($child->route_name);
                                $chColors = $subIconColors[$child->slug] ?? ['#E5E7EB', '#6B7280'];
                                $href = ($child->route_name && RouteHelper::has($child->route_name)) ? route($child->route_name) : '#';
                            @endphp
                            <a href="{{ $href }}"
                               class="flex items-center gap-2 text-xs transition-all"
                               style="padding:7px 10px; margin-bottom:1px; {{ $childActivo ? 'color:#6D8196; font-weight:600; background:#FFFFE3;' : 'color:#CBCBCB;' }}">
                                @if(isset($subIconos[$child->slug]))
                                <div style="width:20px; height:20px; background:{{ $chColors[0] }}; border-radius:4px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="10" height="10" fill="none" stroke="{{ $chColors[1] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="{{ $subIconos[$child->slug] }}"/>
                                    </svg>
                                </div>
                                @else
                                <span class="w-1 h-1 rounded-full flex-shrink-0"
                                      style="background:{{ $childActivo ? '#6D8196' : '#CBCBCB' }};"></span>
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
                       class="flex items-center gap-2.5 text-sm transition-all"
                       style="padding:8px 10px; margin-bottom:1px; {{ $subActivo ? 'color:#6D8196; font-weight:600; background:#FFFFE3;' : 'color:#4A4A4A;' }}">
                        @if(isset($subIconos[$sub->slug]))
                        <div style="width:22px; height:22px; background:{{ $sColors[0] }}; border-radius:5px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="11" height="11" fill="none" stroke="{{ $sColors[1] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="{{ $subIconos[$sub->slug] }}"/>
                            </svg>
                        </div>
                        @else
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                              style="background:{{ $subActivo ? '#6D8196' : '#CBCBCB' }};"></span>
                        @endif
                        {{ $sub->name }}
                    </a>
                    @endif

                    @endforeach
                </div>
            </div>
            @endforeach

            @if ($navModulos->isEmpty())
            <p class="px-3 py-6 text-xs text-center" style="color:#CBCBCB;">Sin módulos asignados.</p>
            @endif
        </nav>

        {{-- Usuario + Logout --}}
        <div style="padding:14px 16px; border-top:1px solid #CBCBCB; background:#FFFFE3; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <div style="width:34px; height:34px; border-radius:50%; background:#6D8196; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($navUser->name, 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:600; color:#4A4A4A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $navUser->name }}</p>
                    <p style="font-size:10px; color:#CBCBCB; text-transform:capitalize;">{{ $navUser->getRoleNames()->first() }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 text-sm font-medium transition-colors"
                        style="padding:9px; background:#FEE2E2; color:#DC2626; border:none; cursor:pointer; font-family:'Inter',sans-serif; border-radius:3px;">
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

        {{-- Topbar — ícono + módulo/título (único encabezado) --}}
        <header class="flex items-center gap-3 px-4 flex-shrink-0"
                style="background:#fff; border-bottom:1px solid #EBEBDF; min-height:56px; box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden flex items-center justify-center transition-colors"
                    style="min-width:32px; min-height:32px; color:#6D8196; background:#F0F0E8; border:1px solid #E0E0D8; border-radius:5px; cursor:pointer; flex-shrink:0;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            {{-- Ícono del submodulo --}}
            <div style="width:38px; height:38px; background:#EEF2F7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 1px 4px rgba(109,129,150,.13);">
                <svg width="18" height="18" fill="none" stroke="#6D8196" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="{{ $pageIconPath }}"/>
                </svg>
            </div>
            {{-- Módulo pequeño + Título grande --}}
            <div class="flex-1 min-w-0">
                @if($activeModuloName)
                <p style="font-size:9px; font-weight:700; color:#C0C0B8; letter-spacing:1.3px; text-transform:uppercase; margin:0; line-height:1;">{{ $activeModuloName }}</p>
                @endif
                <p style="font-size:15px; font-weight:800; color:#4A4A4A; margin:0; line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $pageTitle }}</p>
            </div>
            <div style="font-size:11px; color:#C8C8C0; display:none; letter-spacing:.3px; flex-shrink:0;" class="sm:block">
                {{ now()->format('d M Y') }}
            </div>
            <div style="width:30px; height:30px; border-radius:50%; background:#6D8196; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#fff; flex-shrink:0; letter-spacing:.5px;">
                {{ strtoupper(substr($navUser->name, 0, 1)) }}
            </div>
        </header>


        {{-- Flash messages --}}
        @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-4 mt-3 px-4 py-3 text-sm font-medium"
             style="border-radius:3px; {{ session('success')
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
