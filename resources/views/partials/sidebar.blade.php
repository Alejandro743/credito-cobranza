@php
    $workbenchSlugs = \App\Livewire\Workbench::slugsDisponibles();
    $tabInicial = (string) request()->query('tab', '');

    $workbenchMap = [];
    $navActiveModule = '';
    $navActiveGroup = '';

    foreach ($navModulos as $wm) {
        foreach ($wm->submodulosVisibles as $ws) {
            if ($ws->isGroup()) {
                foreach ($ws->childrenVisibles as $wc) {
                    if (in_array($wc->slug, $workbenchSlugs, true)) {
                        $workbenchMap[$wc->slug] = ['modulo' => $wm->slug, 'grupo' => $ws->slug];
                    }
                    $isRouteMatch = $wc->route_name && request()->routeIs($wc->route_name);
                    $isWbMatch = request()->routeIs('workbench') && $wc->slug === $tabInicial;
                    if ($isRouteMatch || $isWbMatch) {
                        $navActiveModule = $wm->slug;
                        $navActiveGroup = $ws->slug;
                    }
                }
            } else {
                if (in_array($ws->slug, $workbenchSlugs, true)) {
                    $workbenchMap[$ws->slug] = ['modulo' => $wm->slug, 'grupo' => ''];
                }
                $isRouteMatch = $ws->route_name && request()->routeIs($ws->route_name);
                $isWbMatch = request()->routeIs('workbench') && $ws->slug === $tabInicial;
                if ($isRouteMatch || $isWbMatch) {
                    $navActiveModule = $wm->slug;
                }
            }
        }
    }
@endphp
{{-- Overlay móvil --}}
<div x-cloak x-show="sidebarOpen"
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
              md:relative md:inset-auto md:z-auto
              -translate-x-full md:translate-x-0
              transition-transform duration-300 ease-in-out md:transition-none"
       :class="{ 'translate-x-0': sidebarOpen, 'is-collapsed': sidebarCollapsed }"
       :style="{ width: sidebarCollapsed ? '64px' : sidebarWidth + 'px', transition: sidebarDragging ? 'none' : '' }"
       style="width:240px; background:#0B1120;">

    {{-- ── Logo ── --}}
    <div style="padding:16px 14px; border-bottom:1px solid rgba(255,255,255,.07); flex-shrink:0; min-height:64px; display:flex; align-items:center; gap:10px;">
        <div style="width:36px; height:36px; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5"/>
                <path d="M2 12l10 5 10-5"/>
            </svg>
        </div>
        <div class="nav-label">
            <p style="font-size:13px; font-weight:800; color:#fff; letter-spacing:1.5px; line-height:1; white-space:nowrap;">CREDIESSEN</p>
            <p style="font-size:9px; color:rgba(255,255,255,.55); font-weight:500; letter-spacing:1px; text-transform:uppercase; margin-top:3px; white-space:nowrap;">Sistema de Crédito</p>
        </div>
    </div>


    {{-- ── Navegación ── --}}
    <nav class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden"
         style="padding:4px 10px 8px;"
         x-data="{
             activeModule: '{{ $navActiveModule }}',
             activeGroup: '{{ $navActiveGroup }}',
             wbActiveSlug: '{{ $tabInicial }}',
             wbMap: {{ \Illuminate\Support\Js::from($workbenchMap) }},
             setActiveFromSlug(slug) {
                 this.wbActiveSlug = slug || '';
                 const m = this.wbMap[this.wbActiveSlug];
                 this.activeModule = m ? m.modulo : '';
                 this.activeGroup = m ? m.grupo : '';
             }
         }"
         x-on:workbench-tab-changed.window="setActiveFromSlug($event.detail.slug)">

        {{-- Panel Inicio — primer ítem del nav --}}
        <div style="margin-bottom:2px;">
            <a href="{{ $dashRoute }}" wire:navigate
               class="nav-item-wrap flex items-center gap-3"
               @mouseenter="navTipText='Panel Inicio'; navTipY=$event.currentTarget.getBoundingClientRect().top+$event.currentTarget.getBoundingClientRect().height/2; navTipVisible=true"
               @mouseleave="navTipVisible=false"
               style="padding:9px 10px 7px; border-radius:8px; position:relative; border-bottom:2px solid {{ $dashActivo ? '#fff' : 'transparent' }};">
                <span class="nav-tooltip">Panel Inicio</span>
                <div style="width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $dashActivo ? 'background:rgba(255,255,255,.2);' : 'background:rgba(255,255,255,.14);' }}">
                    <svg width="15" height="15" fill="none" stroke="{{ $dashActivo ? '#fff' : 'rgba(255,255,255,.6)' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="nav-label"
                      style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:{{ $dashActivo ? '#fff' : 'rgba(255,255,255,.80)' }}; white-space:nowrap;">
                    Panel Inicio
                </span>
            </a>
        </div>

        @foreach ($navModulos as $modulo)
        @php $slug = $modulo->slug; @endphp
        <div style="margin-bottom:2px;">

            <button @click="sidebarCollapsed ? (sidebarCollapsed = false, sidebarWidth = 240, activeModule = '{{ $slug }}') : (activeModule = activeModule === '{{ $slug }}' ? '' : '{{ $slug }}', activeGroup = '')"
                    @mouseenter="navTipText='{{ $modulo->name }}'; navTipY=$event.currentTarget.getBoundingClientRect().top+$event.currentTarget.getBoundingClientRect().height/2; navTipVisible=true"
                    @mouseleave="navTipVisible=false"
                    class="nav-item-wrap w-full flex items-center gap-3"
                    style="padding:9px 10px; border-radius:8px; position:relative; {{ $navActiveModule === $slug ? 'background:rgba(123,111,232,.15);' : '' }}"
                    :style="{ background: activeModule === '{{ $slug }}' ? 'rgba(123,111,232,.15)' : '' }">
                <span class="nav-tooltip">{{ $modulo->name }}</span>
                <div style="width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $navActiveModule === $slug ? 'background:#7B6FE8;' : 'background:rgba(255,255,255,.14);' }}"
                     :style="{ background: activeModule === '{{ $slug }}' ? '#7B6FE8' : 'rgba(255,255,255,.14)' }">
                    <svg width="15" height="15" fill="none"
                         stroke="{{ $navActiveModule === $slug ? '#fff' : 'rgba(255,255,255,.6)' }}"
                         :stroke="activeModule === '{{ $slug }}' ? '#fff' : 'rgba(255,255,255,.6)'"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="{{ $modulo->icon }}"/>
                    </svg>
                </div>
                <div class="nav-label flex items-center flex-1 gap-1" style="min-width:0; overflow:hidden;">
                    <span style="flex:1; text-align:left; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:{{ $navActiveModule === $slug ? 'rgba(255,255,255,.9)' : 'rgba(255,255,255,.60)' }};"
                          :style="{ color: activeModule === '{{ $slug }}' ? 'rgba(255,255,255,.9)' : 'rgba(255,255,255,.60)' }">
                        {{ $modulo->name }}
                    </span>
                    <svg class="w-3 h-3 flex-shrink-0"
                         :class="activeModule === '{{ $slug }}' ? 'rotate-180' : ''"
                         style="transition:transform .2s; color:rgba(255,255,255,.3);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <div x-cloak
                 x-show="activeModule === '{{ $slug }}' && !sidebarCollapsed"
                 style="margin-left:16px; padding-left:12px; border-left:1px solid rgba(255,255,255,.1); margin-top:2px; margin-bottom:4px;">

                @foreach ($modulo->submodulosVisibles as $sub)

                @if ($sub->isGroup())
                @php
                    $grupActivoInicial = $navActiveGroup === $sub->slug;
                @endphp
                <div style="margin-bottom:1px;">
                    <button @click="activeGroup = activeGroup === '{{ $sub->slug }}' ? '' : '{{ $sub->slug }}'"
                            class="w-full flex items-center gap-2.5"
                            style="padding:7px 8px; border-radius:6px; {{ $grupActivoInicial ? 'background:rgba(123,111,232,.12);' : '' }}"
                            :style="{ background: activeGroup === '{{ $sub->slug }}' ? 'rgba(123,111,232,.12)' : '' }">
                        <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $grupActivoInicial ? 'background:rgba(123,111,232,.5);' : 'background:rgba(255,255,255,.12);' }}"
                             :style="{ background: activeGroup === '{{ $sub->slug }}' ? 'rgba(123,111,232,.5)' : 'rgba(255,255,255,.12)' }">
                            <svg width="12" height="12" fill="none"
                                 stroke="{{ $grupActivoInicial ? '#C4B5FD' : 'rgba(255,255,255,.65)' }}"
                                 :stroke="activeGroup === '{{ $sub->slug }}' ? '#C4B5FD' : 'rgba(255,255,255,.65)'"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="{{ $subIconos[$sub->slug] ?? 'M4 6h16M4 12h16M4 18h16' }}"/>
                            </svg>
                        </div>
                        <span style="flex:1; text-align:left; font-size:12px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:{{ $grupActivoInicial ? 'rgba(255,255,255,.85)' : 'rgba(255,255,255,.65)' }};"
                              :style="{ color: activeGroup === '{{ $sub->slug }}' ? 'rgba(255,255,255,.85)' : 'rgba(255,255,255,.65)' }">
                            {{ $sub->name }}
                        </span>
                        <svg class="w-3 h-3 flex-shrink-0"
                             :class="activeGroup === '{{ $sub->slug }}' ? 'rotate-180' : ''"
                             style="transition:transform .2s; color:rgba(255,255,255,.25);"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-cloak x-show="activeGroup === '{{ $sub->slug }}'"
                         style="margin-left:12px; padding-left:10px; border-left:1px solid rgba(255,255,255,.08); margin-top:1px;">
                        @foreach ($sub->childrenVisibles as $child)
                        @php
                            $childActivoInicial = ($child->route_name && request()->routeIs($child->route_name))
                                || (request()->routeIs('workbench') && $child->slug === $tabInicial);
                            $childEsWorkbench = in_array($child->slug, $workbenchSlugs, true);
                            $onWorkbench = $childEsWorkbench && request()->routeIs('workbench');
                            $href = $childEsWorkbench
                                ? route('workbench', ['tab' => $child->slug])
                                : (($child->route_name && \Illuminate\Support\Facades\Route::has($child->route_name)) ? route($child->route_name) : '#');
                        @endphp
                        <a href="{{ $href }}"
                           @if($onWorkbench)
                           @click.prevent="window.dispatchEvent(new CustomEvent('abrir-pestana', { detail: { key: '{{ $child->slug }}' } })); setActiveFromSlug('{{ $child->slug }}')"
                           @elseif(!$childEsWorkbench)
                           wire:navigate
                           @endif
                           class="flex items-center gap-2"
                           style="padding:6px 8px 5px; border-radius:6px; margin-bottom:1px; border-bottom:2px solid {{ $childActivoInicial ? '#fff' : 'transparent' }};"
                           :style="{ borderBottomColor: wbActiveSlug === '{{ $child->slug }}' ? '#fff' : 'transparent' }">
                            <span style="width:5px; height:5px; border-radius:50%; flex-shrink:0; background:{{ $childActivoInicial ? '#fff' : 'rgba(255,255,255,.25)' }};"
                                  :style="{ background: wbActiveSlug === '{{ $child->slug }}' ? '#fff' : 'rgba(255,255,255,.25)' }"></span>
                            <span style="font-size:12px; font-weight:{{ $childActivoInicial ? '600' : '400' }}; color:{{ $childActivoInicial ? '#fff' : 'rgba(255,255,255,.65)' }};"
                                  :style="{ fontWeight: wbActiveSlug === '{{ $child->slug }}' ? '600' : '400', color: wbActiveSlug === '{{ $child->slug }}' ? '#fff' : 'rgba(255,255,255,.65)' }">{{ $child->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                @else
                @php
                    $subActivoInicial = ($sub->route_name && request()->routeIs($sub->route_name))
                        || (request()->routeIs('workbench') && $sub->slug === $tabInicial);
                    $subEsWorkbench = in_array($sub->slug, $workbenchSlugs, true);
                    $subOnWorkbench = $subEsWorkbench && request()->routeIs('workbench');
                    $href = $subEsWorkbench
                        ? route('workbench', ['tab' => $sub->slug])
                        : (($sub->route_name && \Illuminate\Support\Facades\Route::has($sub->route_name)) ? route($sub->route_name) : '#');
                @endphp
                <a href="{{ $href }}"
                   @if($subOnWorkbench)
                   @click.prevent="window.dispatchEvent(new CustomEvent('abrir-pestana', { detail: { key: '{{ $sub->slug }}' } })); setActiveFromSlug('{{ $sub->slug }}')"
                   @elseif(!$subEsWorkbench)
                   wire:navigate
                   @endif
                   class="flex items-center gap-2.5"
                   style="padding:7px 8px 6px; border-radius:6px; margin-bottom:1px; border-bottom:2px solid {{ $subActivoInicial ? '#fff' : 'transparent' }};"
                   :style="{ borderBottomColor: wbActiveSlug === '{{ $sub->slug }}' ? '#fff' : 'transparent' }">
                    <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; flex-shrink:0; {{ $subActivoInicial ? 'background:rgba(255,255,255,.2);' : 'background:rgba(255,255,255,.12);' }}"
                         :style="{ background: wbActiveSlug === '{{ $sub->slug }}' ? 'rgba(255,255,255,.2)' : 'rgba(255,255,255,.12)' }">
                        <svg width="12" height="12" fill="none"
                             stroke="{{ $subActivoInicial ? '#fff' : 'rgba(255,255,255,.65)' }}"
                             :stroke="wbActiveSlug === '{{ $sub->slug }}' ? '#fff' : 'rgba(255,255,255,.65)'"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="{{ $subIconos[$sub->slug] ?? 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2' }}"/>
                        </svg>
                    </div>
                    <span style="font-size:12px; font-weight:{{ $subActivoInicial ? '600' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:{{ $subActivoInicial ? '#fff' : 'rgba(255,255,255,.70)' }};"
                          :style="{ fontWeight: wbActiveSlug === '{{ $sub->slug }}' ? '600' : '400', color: wbActiveSlug === '{{ $sub->slug }}' ? '#fff' : 'rgba(255,255,255,.70)' }">
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
    <div style="padding:10px 10px 12px; border-top:1px solid rgba(255,255,255,.07); flex-shrink:0;">
        <div class="flex items-center gap-2.5" style="padding:6px 8px; margin-bottom:4px; overflow:hidden;">
            <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#fff; flex-shrink:0;">
                {{ strtoupper(substr($navUser->name, 0, 2)) }}
            </div>
            <div class="nav-label" style="flex:1; min-width:0; overflow:hidden;">
                <p style="font-size:12px; font-weight:600; color:rgba(255,255,255,.85); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1.3;">{{ $navUser->name }}</p>
                <p style="font-size:10px; color:rgba(255,255,255,.35); text-transform:capitalize; line-height:1.3;">{{ $navUser->getRoleNames()->first() }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-item-wrap w-full flex items-center"
                    style="padding:7px 8px; gap:5px; border-radius:8px; border:none; cursor:pointer; font-family:'Inter',sans-serif; position:relative; background:transparent;">
                <span class="nav-tooltip">Cerrar sesión</span>
                <svg style="flex-shrink:0;" width="15" height="15" fill="none" stroke="rgba(239,68,68,.8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                <span class="nav-label"
                      style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:rgba(252,165,165,.9); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    Cerrar sesión
                </span>
            </button>
        </form>
    </div>
</aside>
