<div id="wb-root-{{ $this->getId() }}" style="height:100%; display:flex; flex-direction:column;"
     x-data="{
         labels: {{ \Illuminate\Support\Js::from(collect($tabsInfo)->map(fn($t) => $t['label'])->all()) }},
         syncTab(key) {
             const root = document.getElementById('wb-root-{{ $this->getId() }}');
             if (!root) return;
             root.querySelectorAll('[data-tab-key]').forEach(p => {
                 const active = p.dataset.tabKey === key;
                 p.style.background = active ? '#fff' : '#F0EDFC';
                 p.style.color = active ? '#7B6FE8' : '#6B7280';
                 p.style.fontWeight = active ? '700' : '600';
                 p.style.borderColor = active ? '#7B6FE8' : 'transparent';
             });
             root.querySelectorAll('[data-pane-key]').forEach(p => {
                 p.style.display = p.dataset.paneKey === key ? '' : 'none';
             });
             const titleEl = document.getElementById('wb-title-{{ $this->getId() }}');
             if (titleEl) titleEl.textContent = this.labels[key] || 'Espacio de trabajo';
         }
     }"
     x-on:abrir-pestana.window="$wire.abrirPestana($event.detail.key)"
     x-on:workbench-tab-changed.window="syncTab($event.detail.slug)"
     @click="const el = $event.target.closest('[data-tab-key]'); if (el) { syncTab(el.dataset.tabKey); window.dispatchEvent(new CustomEvent('workbench-tab-changed', { detail: { slug: el.dataset.tabKey } })); }">

    {{-- Barra de pestañas --}}
    <div style="flex:none; display:flex; align-items:stretch; background:#F9F8FF; border-bottom:1px solid #EDE9FE; padding:6px 6px 0; gap:3px;">
        @php $tabFlex = count($openTabs) === 1 ? 'flex:0 0 50%; max-width:50%;' : 'flex:1;'; @endphp
        @forelse ($openTabs as $key)
        @php $t = $tabsInfo[$key] ?? null; @endphp
        @continue(!$t)
        @php $isActive = $activeTab === $key; @endphp
        <div wire:key="wb-tabbtn-{{ $key }}"
             data-tab-key="{{ $key }}"
             style="{{ $tabFlex }} min-width:0; display:flex; align-items:center; gap:7px; padding:8px 8px 9px 12px; border-radius:9px 9px 0 0; cursor:pointer; transition:background-color .15s ease;
                    background:{{ $isActive ? '#fff' : '#F0EDFC' }}; color:{{ $isActive ? '#7B6FE8' : '#6B7280' }}; font-size:12.5px; font-weight:{{ $isActive ? '700' : '600' }};
                    border:2px solid {{ $isActive ? '#7B6FE8' : 'transparent' }}; border-bottom:none; position:relative; top:1px;"
             onmouseenter="if (this.style.background !== 'rgb(255, 255, 255)') this.style.background='#E5DFFB';"
             onmouseleave="if (this.style.background !== 'rgb(255, 255, 255)') this.style.background='#F0EDFC';">
            <span style="flex:1; min-width:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t['label'] }}</span>
            <button type="button" wire:click.stop="cerrarPestana('{{ $key }}')" title="Cerrar pestaña"
                    style="width:16px; height:16px; border-radius:5px; border:none; background:transparent; color:#9CA3AF; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    onmouseenter="this.style.background='#FEE2E2';this.style.color='#B91C1C';"
                    onmouseleave="this.style.background='transparent';this.style.color='#9CA3AF';">
                <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @empty
        <div style="padding:12px 14px; font-size:12px; color:#9CA3AF;">Elegí un módulo del menú para empezar.</div>
        @endforelse
    </div>

    {{-- Franja de título --}}
    @php $tituloActivo = $tabsInfo[$activeTab]['label'] ?? 'Espacio de trabajo'; @endphp
    <header class="flex items-center flex-shrink-0"
            style="background:#fff; border-bottom:1px solid #E5E7EB; min-height:56px; box-shadow:0 1px 3px rgba(0,0,0,.04); gap:0;">
        <div class="flex items-center flex-1 min-w-0" style="gap:5px; padding:0 12px; overflow:hidden;">
            <div style="width:34px; height:34px; border-radius:9px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="17" height="17" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <span id="wb-title-{{ $this->getId() }}" style="font-family:'Oswald',sans-serif; font-size:22px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1;">{{ $tituloActivo }}</span>
        </div>
        <div style="font-size:11px; color:#D1D5DB; flex-shrink:0; padding-right:16px;" class="hidden sm:block">{{ now()->format('d M Y') }}</div>
    </header>

    {{-- Contenido de la pestaña activa --}}
    <div style="flex:1; min-height:0; position:relative; background:#fff;">
        @forelse ($openTabs as $key)
        @php $t = $tabsInfo[$key] ?? null; @endphp
        @continue(!$t)
        <div wire:key="wb-pane-{{ $key }}"
             data-pane-key="{{ $key }}"
             style="position:absolute; inset:0; overflow:auto; padding:16px; {{ $activeTab === $key ? '' : 'display:none;' }}">
            @livewire($t['component'], $t['params'] ?? [], 'wb-comp-'.$key)
        </div>
        @empty
        <div style="height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#9CA3AF; gap:8px;">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span style="font-size:13px;">Sin pestañas abiertas — elegí un módulo del menú.</span>
        </div>
        @endforelse
    </div>
</div>
