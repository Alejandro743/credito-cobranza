@php
$mobileColorMap = [
    'lavanda'   => ['bg' => 'rgba(123,111,232,.13)', 'icon' => '#7B6FE8'],
    'mint'      => ['bg' => 'rgba(16,185,129,.13)',  'icon' => '#059669'],
    'melocoton' => ['bg' => 'rgba(249,115,22,.13)',  'icon' => '#EA580C'],
    'celeste'   => ['bg' => 'rgba(59,130,246,.13)',  'icon' => '#2563EB'],
];
@endphp

{{-- ═══ PANEL DE MÓDULOS (móvil) ═══ --}}
<div x-cloak x-show="mobileTab === 'modulos'"
     class="md:hidden fixed inset-0 z-40 flex flex-col"
     style="background:#F0F2F5; padding-bottom:60px;">

    {{-- Cabecera --}}
    <div style="padding:18px 20px 14px; background:#fff; border-bottom:1px solid #E5E7EB; flex-shrink:0;">
        <p style="font-size:11px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.8px; margin-bottom:2px;">Sistema Crédito</p>
        <p style="font-size:20px; font-weight:800; color:#111827; line-height:1.2;">Menú principal</p>
        <p style="font-size:12px; color:#9CA3AF; margin-top:2px;">Selecciona un módulo</p>
    </div>

    {{-- Lista de módulos --}}
    <div class="flex-1 overflow-y-auto" style="padding:10px 0 4px;">

        @foreach($navModulos as $modulo)
        @php
            $mc  = $mobileColorMap[$modulo->color] ?? ['bg' => 'rgba(123,111,232,.13)', 'icon' => '#7B6FE8'];
            $isOpen = $moduloActivo?->slug === $modulo->slug;
        @endphp
        <div x-data="{ mOpen: {{ $isOpen ? 'true' : 'false' }} }"
             style="background:#fff; margin-bottom:8px;">

            {{-- Fila del módulo --}}
            <button @click="mOpen = !mOpen"
                    class="w-full flex items-center justify-between"
                    style="padding:14px 20px;">
                <div class="flex items-center gap-3">
                    <div style="width:44px; height:44px; border-radius:13px; background:{{ $mc['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="{{ $mc['icon'] }}" stroke-width="1.9"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="{{ $modulo->icon }}"/>
                        </svg>
                    </div>
                    <div style="text-align:left;">
                        <p style="font-size:14px; font-weight:700; color:#111827; line-height:1.3;">{{ $modulo->name }}</p>
                        <p style="font-size:11px; color:#9CA3AF; margin-top:1px;">
                            {{ $modulo->submodulosVisibles->count() }} acceso{{ $modulo->submodulosVisibles->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>
                <svg class="w-5 h-5 flex-shrink-0"
                     :class="mOpen ? 'rotate-180' : ''"
                     style="color:#D1D5DB; transition:transform .2s;"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Grid de acciones --}}
            <div x-cloak x-show="mOpen"
                 style="padding:4px 16px 18px; background:#F7F8FC; border-top:1px solid #F0F0F0;">
                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-top:10px;">

                    @foreach($modulo->submodulosVisibles as $sub)
                        @if(!$sub->isGroup())
                        @php
                            $href = ($sub->route_name && \Illuminate\Support\Facades\Route::has($sub->route_name))
                                ? route($sub->route_name) : '#';
                        @endphp
                        <a href="{{ $href }}" wire:navigate @click="mobileTab = 'inicio'"
                           style="display:flex; flex-direction:column; align-items:center; gap:6px; text-decoration:none;">
                            <div style="width:54px; height:54px; border-radius:15px; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,.09);">
                                <svg width="20" height="20" fill="none" stroke="{{ $mc['icon'] }}"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="{{ $subIconos[$sub->slug] ?? 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2' }}"/>
                                </svg>
                            </div>
                            <span style="font-size:10px; font-weight:500; color:#374151; text-align:center; line-height:1.25; word-break:break-word;">{{ $sub->name }}</span>
                        </a>

                        @else
                        @foreach($sub->childrenVisibles as $child)
                        @php
                            $href = ($child->route_name && \Illuminate\Support\Facades\Route::has($child->route_name))
                                ? route($child->route_name) : '#';
                        @endphp
                        <a href="{{ $href }}" wire:navigate @click="mobileTab = 'inicio'"
                           style="display:flex; flex-direction:column; align-items:center; gap:6px; text-decoration:none;">
                            <div style="width:54px; height:54px; border-radius:15px; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,.09);">
                                <svg width="20" height="20" fill="none" stroke="{{ $mc['icon'] }}"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="{{ $subIconos[$child->slug] ?? 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2' }}"/>
                                </svg>
                            </div>
                            <span style="font-size:10px; font-weight:500; color:#374151; text-align:center; line-height:1.25; word-break:break-word;">{{ $child->name }}</span>
                        </a>
                        @endforeach
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- ═══ BOTTOM NAVIGATION (móvil) ═══ --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 flex"
     style="background:#fff; border-top:1px solid #E8EAED; height:60px; box-shadow:0 -2px 12px rgba(0,0,0,.06);">

    <a href="{{ $dashRoute }}" wire:navigate @click="mobileTab = 'inicio'"
       class="flex-1 flex flex-col items-center justify-center"
       style="gap:3px; text-decoration:none;"
       :style="mobileTab === 'inicio' ? 'color:#7B6FE8;' : 'color:#9CA3AF;'">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span style="font-size:10px; font-weight:600; letter-spacing:.2px;">Inicio</span>
    </a>

    <button @click="mobileTab = mobileTab === 'modulos' ? 'inicio' : 'modulos'"
            class="flex-1 flex flex-col items-center justify-center"
            style="gap:3px; border:none; background:transparent; cursor:pointer;"
            :style="mobileTab === 'modulos' ? 'color:#7B6FE8;' : 'color:#9CA3AF;'">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
        </svg>
        <span style="font-size:10px; font-weight:600; letter-spacing:.2px;">Módulos</span>
    </button>

    <button class="flex-1 flex flex-col items-center justify-center"
            style="gap:3px; border:none; background:transparent; cursor:pointer; color:#9CA3AF;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span style="font-size:10px; font-weight:600; letter-spacing:.2px;">Actividad</span>
    </button>

    <a href="{{ route('profile.edit') }}" wire:navigate @click="mobileTab = 'inicio'"
       class="flex-1 flex flex-col items-center justify-center"
       style="gap:3px; text-decoration:none; color:#9CA3AF;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span style="font-size:10px; font-weight:600; letter-spacing:.2px;">Perfil</span>
    </a>

</nav>
