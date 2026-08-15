<div x-data="{ toastShow: false, toastMsg: '', showSearch: false, showProductos: false, ubEntModal: false, ubEntTipo: '', ubEntOpciones: [], ubEntSearch: '', appToastShow: false, appToastMsg: '', appToastType: 'success' }"
     x-effect="document.body.style.overflow = (showSearch || showProductos || ubEntModal) ? 'hidden' : ''; if (!showSearch) $wire.set('searchCliente', '')"
     x-on:producto-agregado.window="toastMsg = $event.detail.nombre; toastShow = true; setTimeout(() => toastShow = false, 2200)"
     x-on:app-toast.window="appToastMsg=$event.detail.msg; appToastType=$event.detail.type; appToastShow=true; setTimeout(()=>appToastShow=false, 3200)"
     x-on:app-redirect.window="setTimeout(()=>window.location=$event.detail.url, $event.detail.delay||1800)">

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

{{-- Toast éxito flotante --}}
<div x-show="appToastShow && appToastType==='success'" x-cloak
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
     class="flex flex-row items-center gap-2"
     style="position:fixed;bottom:28px;left:50%;transform:translateX(-50%);z-index:9999;background:#10B981;border-radius:16px;padding:13px 24px;box-shadow:0 8px 32px rgba(16,185,129,0.35);font-size:15px;font-weight:700;color:#fff;pointer-events:none;white-space:nowrap;">
    <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    <span x-text="appToastMsg"></span>
</div>

{{-- ══════════════════════════════════ STEPS: CLIENTE + OFERTA ══════════════ --}}
@if ($step === 'cliente' || $step === 'oferta')

{{-- ── BUSCADOR DE CLIENTE ───────────────────────────────────────────────── --}}

{{-- ════════════ HEADER (stats + filtros) ════════════ --}}
<div style="background:#fff;">

{{-- ── STATS BAR — DESKTOP ──────────────────────────────────────────────── --}}
<div class="hidden md:block bg-white px-4 pt-2 pb-2.5" style="max-width:900px; margin:0 auto;">
    {{-- Label ancho completo --}}
    <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Dato Cliente</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>
    {{-- Card cliente + botón carrito --}}
    <div class="flex gap-3 items-stretch" style="min-height:52px;">
        {{-- CLIENTE --}}
        <div @click="showSearch = true"
             style="flex:1; min-width:0; background:#fff; border:1px solid #EDE9FE; border-radius:10px; cursor:pointer; transition:background 0.15s; box-shadow:0 2px 8px rgba(123,111,232,0.08); {{ $sinListasActivas ? 'display:flex; align-items:center; gap:12px; padding:0 16px;' : 'display:flex; flex-direction:column; align-items:center; justify-content:center; padding:10px 16px; text-align:center;' }}"
             @mouseenter="$el.style.background='#F8F7FF'" @mouseleave="$el.style.background='#fff'">
            @if ($clienteId)
            <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom:3px;">
                <svg width="12" height="12" fill="none" stroke="#f97316" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span style="font-size:14px; font-weight:800; color:#f97316; letter-spacing:0.05em;">Cambiar Cliente</span>
            </div>
            <span style="font-size:19px; font-weight:800; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%;">{{ $clienteNombre }}</span>
            <span style="font-size:13px; font-weight:700; color:#7B6FE8; display:block;">CI: {{ $clienteCI }}</span>
            @elseif ($sinListasActivas)
            <div style="width:34px; height:34px; border-radius:10px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="14" height="14" fill="none" stroke="#C4B5FD" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div style="min-width:0; flex:1;">
                <div style="display:flex; align-items:center; gap:5px;">
                    <svg width="11" height="11" fill="none" stroke="#e24b4a" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:12px; font-weight:500; color:#e24b4a;">Sin listas activas</span>
                </div>
            </div>
            @else
            <div style="width:44px; height:44px; border-radius:12px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-bottom:4px;">
                <svg width="20" height="20" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <span style="font-size:22px; font-weight:900; color:#7B6FE8; display:block; letter-spacing:0.01em;">Seleccionar Cliente</span>
            @endif
        </div>
        {{-- CARRITO --}}
        <div class="relative flex-shrink-0"
             style="display:none; width:58px; border-radius:10px; background:{{ empty($carrito) ? '#f9fafb' : '#f97316' }}; border:1.5px solid {{ empty($carrito) ? '#E5E7EB' : '#f97316' }}; box-shadow:{{ empty($carrito) ? 'none' : '0 2px 10px rgba(249,115,22,0.30)' }}; transition:all 0.2s;">
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
<div class="md:hidden bg-white px-2 pt-2 pb-2" style="max-width:900px; margin:0 auto;">
    {{-- Label ancho completo --}}
    <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Dato Cliente</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>
    {{-- Card cliente + botón carrito --}}
    <div class="flex gap-1.5 items-stretch" style="min-height:44px;">
        {{-- CLIENTE --}}
        <div @click="showSearch = true"
             style="flex:1; min-width:0; background:#fff; border:1px solid #EDE9FE; border-radius:8px; cursor:pointer; box-shadow:0 2px 8px rgba(123,111,232,0.08); {{ $sinListasActivas ? 'display:flex; align-items:center; gap:8px; padding:6px 10px;' : 'display:flex; flex-direction:column; align-items:center; justify-content:center; padding:8px 10px; text-align:center;' }}">
            @if ($clienteId)
            <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom:2px;">
                <svg width="11" height="11" fill="none" stroke="#f97316" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span style="font-size:13px; font-weight:800; color:#f97316; letter-spacing:0.05em;">Cambiar Cliente</span>
            </div>
            <span style="font-size:17px; font-weight:800; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%;">{{ $clienteNombre }}</span>
            <span style="font-size:12px; font-weight:700; color:#7B6FE8; display:block;">CI: {{ $clienteCI }}</span>
            @elseif ($sinListasActivas)
            <div style="width:28px; height:28px; border-radius:8px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="13" height="13" fill="none" stroke="#C4B5FD" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <span style="font-size:11px; font-weight:500; color:#e24b4a;">Sin listas activas</span>
            @else
            <div style="width:36px; height:36px; border-radius:10px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-bottom:3px;">
                <svg width="17" height="17" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <span style="font-size:20px; font-weight:900; color:#7B6FE8; display:block;">Seleccionar Cliente</span>
            @endif
        </div>
        {{-- CARRITO --}}
        <div class="relative flex items-center justify-center flex-shrink-0"
             style="display:none; width:44px; border-radius:8px; background:{{ empty($carrito) ? '#f3f4f6' : '#f97316' }}; box-shadow:{{ empty($carrito) ? 'none' : '0 2px 8px rgba(249,115,22,0.30)' }};">
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

{{-- ── DOCUMENTACIÓN DEL PLAN ──────────────────────────────────────────────── --}}
@if ($clienteId && $step === 'oferta')
<div class="bg-white px-4 py-2" style="max-width:900px; margin:0 auto;"
     x-data="{
         up: { ci1:false, ci2:false, d1:false, d2:false, luz:false },
         err: { ci1:false, ci2:false, d1:false, d2:false, luz:false },
         doUpload(key, prop, file) {
             if (!file) return;
             const wire = $wire, self = this;
             self.up[key] = true; self.err[key] = false;
             const send = (f) => wire.upload(prop, f,
                 () => { self.up[key] = false; },
                 () => { self.up[key] = false; self.err[key] = true; setTimeout(() => self.err[key] = false, 4000); }
             );
             if (!file.type.startsWith('image/')) { send(file); return; }
             const url = URL.createObjectURL(file);
             const img = new Image();
             img.onerror = () => { URL.revokeObjectURL(url); send(file); };
             img.onload = () => {
                 const max = 1200, w0 = img.width, h0 = img.height;
                 let w = w0, h = h0;
                 if (w > max || h > max) { if (w > h) { h = Math.round(h*max/w); w = max; } else { w = Math.round(w*max/h); h = max; } }
                 const c = document.createElement('canvas'); c.width = w; c.height = h;
                 c.getContext('2d').drawImage(img, 0, 0, w, h);
                 URL.revokeObjectURL(url);
                 c.toBlob(b => send(new File([b], 'foto.jpg', {type:'image/jpeg'})), 'image/jpeg', 0.82);
             };
             img.src = url;
         }
     }">
    <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Documentación del Plan</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
        {{-- 1. Anverso CI --}}
        <label style="cursor:pointer; position:relative; display:block;">
            <div style="{{ $docAnversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box; position:relative; overflow:hidden;">
                <template x-if="up.ci1"><div style="position:absolute;inset:0;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;border-radius:7px;z-index:2;"><svg style="width:20px;height:20px;animation:spin 1s linear infinite;" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div></template>
                <template x-if="err.ci1"><div style="position:absolute;inset:0;background:rgba(254,242,242,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:7px;z-index:2;gap:2px;"><svg style="width:16px;height:16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span style="font-size:9px;color:#ef4444;font-weight:600;">Error</span></div></template>
                <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                    @if($docAnversoCi)
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                    @endif
                </div>
                <span style="font-size:11px; font-weight:600; line-height:1.2; color:{{ $docAnversoCi ? '#0F6E56' : '#534AB7' }};">Anverso CI</span>
                <span style="font-size:10px; color:{{ $docAnversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoCi ? 'OK' : 'JPG/PDF' }}</span>
            </div>
            <input type="file" accept="image/*,application/pdf" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" x-on:change="doUpload('ci1','docAnversoCi',$event.target.files[0])">
        </label>
        {{-- 2. Reverso CI --}}
        <label style="cursor:pointer; position:relative; display:block;">
            <div style="{{ $docReversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box; position:relative; overflow:hidden;">
                <template x-if="up.ci2"><div style="position:absolute;inset:0;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;border-radius:7px;z-index:2;"><svg style="width:20px;height:20px;animation:spin 1s linear infinite;" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div></template>
                <template x-if="err.ci2"><div style="position:absolute;inset:0;background:rgba(254,242,242,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:7px;z-index:2;gap:2px;"><svg style="width:16px;height:16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span style="font-size:9px;color:#ef4444;font-weight:600;">Error</span></div></template>
                <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                    @if($docReversoCi)
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                    @endif
                </div>
                <span style="font-size:11px; font-weight:600; line-height:1.2; color:{{ $docReversoCi ? '#0F6E56' : '#534AB7' }};">Reverso CI</span>
                <span style="font-size:10px; color:{{ $docReversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoCi ? 'OK' : 'JPG/PDF' }}</span>
            </div>
            <input type="file" accept="image/*,application/pdf" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" x-on:change="doUpload('ci2','docReversoCi',$event.target.files[0])">
        </label>
        {{-- 3. Anverso Documento --}}
        <label style="cursor:pointer; position:relative; display:block;">
            <div style="{{ $docAnversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box; position:relative; overflow:hidden;">
                <template x-if="up.d1"><div style="position:absolute;inset:0;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;border-radius:7px;z-index:2;"><svg style="width:20px;height:20px;animation:spin 1s linear infinite;" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div></template>
                <template x-if="err.d1"><div style="position:absolute;inset:0;background:rgba(254,242,242,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:7px;z-index:2;gap:2px;"><svg style="width:16px;height:16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span style="font-size:9px;color:#ef4444;font-weight:600;">Error</span></div></template>
                <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                    @if($docAnversoDoc)
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    @endif
                </div>
                <span style="font-size:11px; font-weight:600; line-height:1.2; color:{{ $docAnversoDoc ? '#0F6E56' : '#534AB7' }};">Anverso Doc</span>
                <span style="font-size:10px; color:{{ $docAnversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoDoc ? 'OK' : 'JPG/PDF' }}</span>
            </div>
            <input type="file" accept="image/*,application/pdf" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" x-on:change="doUpload('d1','docAnversoDoc',$event.target.files[0])">
        </label>
        {{-- 4. Reverso Documento --}}
        <label style="cursor:pointer; position:relative; display:block;">
            <div style="{{ $docReversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box; position:relative; overflow:hidden;">
                <template x-if="up.d2"><div style="position:absolute;inset:0;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;border-radius:7px;z-index:2;"><svg style="width:20px;height:20px;animation:spin 1s linear infinite;" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div></template>
                <template x-if="err.d2"><div style="position:absolute;inset:0;background:rgba(254,242,242,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:7px;z-index:2;gap:2px;"><svg style="width:16px;height:16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span style="font-size:9px;color:#ef4444;font-weight:600;">Error</span></div></template>
                <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                    @if($docReversoDoc)
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                    @endif
                </div>
                <span style="font-size:11px; font-weight:600; line-height:1.2; color:{{ $docReversoDoc ? '#0F6E56' : '#534AB7' }};">Reverso Doc</span>
                <span style="font-size:10px; color:{{ $docReversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoDoc ? 'OK' : 'JPG/PDF' }}</span>
            </div>
            <input type="file" accept="image/*,application/pdf" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" x-on:change="doUpload('d2','docReversoDoc',$event.target.files[0])">
        </label>
        {{-- 5. Aviso Luz --}}
        <label style="cursor:pointer; position:relative; display:block;">
            <div style="{{ $docAvisoLuz ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box; position:relative; overflow:hidden;">
                <template x-if="up.luz"><div style="position:absolute;inset:0;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;border-radius:7px;z-index:2;"><svg style="width:20px;height:20px;animation:spin 1s linear infinite;" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div></template>
                <template x-if="err.luz"><div style="position:absolute;inset:0;background:rgba(254,242,242,0.95);display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:7px;z-index:2;gap:2px;"><svg style="width:16px;height:16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span style="font-size:9px;color:#ef4444;font-weight:600;">Error</span></div></template>
                <div style="width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAvisoLuz ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                    @if($docAvisoLuz)
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @endif
                </div>
                <span style="font-size:11px; font-weight:600; line-height:1.2; color:{{ $docAvisoLuz ? '#0F6E56' : '#534AB7' }};">Aviso Luz</span>
                <span style="font-size:10px; color:{{ $docAvisoLuz ? '#0F6E56' : '#AFA9EC' }};">{{ $docAvisoLuz ? 'OK' : 'JPG/PDF' }}</span>
            </div>
            <input type="file" accept="image/*,application/pdf" style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;" x-on:change="doUpload('luz','docAvisoLuz',$event.target.files[0])">
        </label>
    </div>
</div>
@endif


</div>{{-- /header --}}

{{-- ── DOCUMENTACIÓN DEL PLAN (movida dentro del sticky, antes de filtros) ── --}}

{{-- ── STEP OFERTA ──────────────────────────────────────────────────────── --}}
@if ($step === 'oferta')

@if ($sinListasComunes)
<div class="flex flex-col items-center justify-center py-16 text-center px-4">
    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:#EEEDFE;">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#7B6FE8;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
        </svg>
    </div>
    <p class="font-bold text-gray-700">Sin listas compartidas</p>
    <p class="text-gray-400 text-sm mt-1 max-w-xs">Este cliente no tiene acceso a listas compartidas contigo.</p>
    <button wire:click="cambiarCliente"
            class="mt-5 px-5 py-2 text-white text-sm font-semibold rounded-xl transition-colors"
            style="background:#7B6FE8;">
        Buscar otro cliente
    </button>
</div>
@else

{{-- ── ARTÍCULOS SELECCIONADOS + BOTÓN CARRITO ──────────────────────────── --}}
<div style="padding:16px 16px 24px; max-width:900px; margin:0 auto;">

    {{-- Separador Artículos Seleccionados (siempre visible) --}}
    <div style="display:flex; align-items:center; gap:7px; margin-top:8px; margin-bottom:16px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Artículos Seleccionados</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>

    {{-- Botón Seleccionar Artículos --}}
    <button @click="showProductos = true"
            style="width:100%; padding:11px 20px; background:#6B7280; color:#fff; font-size:14px; font-weight:800; border-radius:10px; border:1.5px solid #6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; -webkit-appearance:none; appearance:none; margin-bottom:{{ !empty($carrito) ? '14px' : '0' }};">
        <span style="width:26px; height:26px; border-radius:50%; background:#f97316; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </span>
        <span style="text-decoration:underline; text-underline-offset:3px;">Ir a Seleccionar Artículos</span>
    </button>

    {{-- Lista artículos seleccionados --}}
    @if (!empty($carrito))
    <div style="display:flex; flex-direction:column; gap:8px;">
        @foreach ($carrito as $pid => $item)
        <div style="background:#fff; border:1.5px solid #D1D5DB; border-radius:12px; padding:14px 12px; box-shadow:0 2px 4px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.10);" wire:key="sel-{{ $pid }}">
            {{-- Código + descripción --}}
            <div style="display:flex; align-items:flex-start; gap:7px; margin-bottom:8px;">
                <div style="width:22px; height:22px; border-radius:50%; background:#f97316; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                    <span style="font-size:10px; font-weight:800; color:#fff; line-height:1;">{{ $item['cantidad'] }}</span>
                </div>
                <div style="flex:1; min-width:0;">
                    <span style="font-size:16px; font-weight:800; color:#111827; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($item['nombre'])) }}</span>
                    <span style="font-size:13px; font-weight:400; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item['code'] ?? '' }}</span>
                </div>
            </div>
            {{-- Precios --}}
            <div style="background:#F8F7FF; border-radius:8px; padding:8px 10px; margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:4px;">
                    <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Precio Bs Un: <span style="color:#7B6FE8; font-size:14px; font-weight:400;">{{ number_format($item['precio'], 2) }}</span></span>
                    <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Puntos: <span style="color:#111827; font-size:14px; font-weight:400;">{{ $item['puntos'] }}</span></span>
                </div>
                <div style="display:flex; align-items:center; gap:14px;">
                    <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Bs: <span style="color:#3C3489; font-size:14px; font-weight:400;">{{ number_format($item['precio'] * $item['cantidad'], 2) }}</span></span>
                    <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Puntos: <span style="color:#111827; font-size:14px; font-weight:400;">+{{ $item['puntos'] * $item['cantidad'] }}</span></span>
                </div>
            </div>
            {{-- Pie: lista_nombre + trash --}}
            <div style="display:flex; align-items:center; gap:6px;">
                <span style="font-size:13px; font-weight:700; color:#f97316; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-left:10px;">{{ $item['lista_nombre'] ?? '' }}</span>
                <button wire:click="quitar({{ $item['item_id'] }})"
                        style="width:30px; height:30px; border-radius:50%; background:#ef4444; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; -webkit-appearance:none; appearance:none; box-shadow:0 2px 8px rgba(239,68,68,0.40);">
                    <svg style="width:13px; height:13px;" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
        @endforeach

        {{-- Separador Resumen --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:16px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Resumen</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Card Resumen --}}
        @php
        $listaLockedId = !empty($carrito) ? (collect($carrito)->first()['lista_id'] ?? null) : null;
        $cuotasLista   = $listaLockedId ? ($listasInfo[$listaLockedId]['cantidad_cuotas'] ?? null) : null;
        $montoCuota    = ($cuotasLista && $cuotasLista > 0) ? $total / $cuotasLista : null;
        @endphp
        <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18), 0 1px 4px rgba(123,111,232,0.10);">
            <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#f97316 100%);"></div>
            <div style="padding:14px 14px;">
                {{-- Hero: Total Pedido full width --}}
                <div style="margin-bottom:8px; text-align:center;">
                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Pedido Bs.</span>
                    <span style="font-size:26px; font-weight:900; color:#3C3489; line-height:1; display:block;">{{ number_format($total, 2) }}</span>
                </div>
                {{-- Divider --}}
                <div style="height:1px; background:#EDE9FE; margin-bottom:8px;"></div>
                {{-- Secundarios: Puntos + Cuotas + Monto centrados --}}
                <div style="display:grid; grid-template-columns:{{ $cuotasLista ? 'repeat(3,1fr)' : '1fr' }}; text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Puntos</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($puntos) }}</span>
                    </div>
                    @if ($cuotasLista)
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">N° Cuotas</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotasLista }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Monto x Cuota</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($montoCuota, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Separador Dirección de Entrega --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:16px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección de Entrega</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Card Dirección de Entrega --}}
        <div style="background:#fff; border-radius:12px; padding:12px; box-shadow:0 4px 20px rgba(123,111,232,0.10); margin-bottom:14px;">
            <div class="grid grid-cols-2 gap-2 mb-3">
                <button wire:click="$set('tipoEntrega','domicilio')" type="button"
                        style="{{ $tipoEntrega === 'domicilio' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    🏠 Domicilio
                </button>
                <button wire:click="$set('tipoEntrega','nuevo')" type="button"
                        style="{{ $tipoEntrega === 'nuevo' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    📍 Nuevo lugar
                </button>
            </div>
            @if ($tipoEntrega === 'domicilio')
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad</p>
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteCiudad ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p>
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteProvincia ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p>
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteMunicipio ?: '—' }}</div>
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección</p>
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaClienteDireccion ?: '—' }}</div>
                </div>
            </div>
            @error('entregaClienteDireccion')
            <p style="font-size:11px; color:#ef4444; margin-bottom:8px;">{{ $message }}</p>
            @enderror
            @endif
            @if ($tipoEntrega === 'nuevo')
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad *</p>
                    <button type="button"
                            @click="ubEntModal=true; ubEntTipo='ciudad'; ubEntOpciones=@js($ciudadesAll->pluck('nombre')->toArray()); ubEntSearch=''"
                            style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevoCiudad ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:#fff; cursor:pointer; box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                        <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevoCiudad ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevoCiudad ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevoCiudad ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevoCiudad ? ucwords(strtolower($entregaNuevoCiudad)) : 'Seleccionar ciudad' }}</span>
                        <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    @error('entregaNuevoCiudad')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p>
                    <button type="button"
                            @if($entregaNuevoCiudad) @click="ubEntModal=true; ubEntTipo='provincia'; ubEntOpciones=@js($entregaProvincias->pluck('nombre')->toArray()); ubEntSearch=''" @endif
                            style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevaProvincia ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:{{ $entregaNuevoCiudad ? '#fff' : '#F8F7FF' }}; {{ $entregaNuevoCiudad ? 'cursor:pointer;' : 'cursor:not-allowed;' }} box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                        <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevaProvincia ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevaProvincia ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevaProvincia ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevaProvincia ? ucwords(strtolower($entregaNuevaProvincia)) : 'Seleccionar provincia' }}</span>
                        <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p>
                    <button type="button"
                            @if($entregaNuevaProvincia) @click="ubEntModal=true; ubEntTipo='municipio'; ubEntOpciones=@js($entregaMunicipios->pluck('nombre')->toArray()); ubEntSearch=''" @endif
                            style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevoMunicipio ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:{{ $entregaNuevaProvincia ? '#fff' : '#F8F7FF' }}; {{ $entregaNuevaProvincia ? 'cursor:pointer;' : 'cursor:not-allowed;' }} box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                        <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevoMunicipio ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevoMunicipio ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevoMunicipio ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevoMunicipio ? ucwords(strtolower($entregaNuevoMunicipio)) : 'Seleccionar municipio' }}</span>
                        <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <div>
                    <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección *</p>
                    <input wire:model="entregaNuevaDireccion" type="text" placeholder="Calle y número" autocomplete="off"
                           style="width:100%; background:#fff; -webkit-box-shadow:0 0 0 30px #fff inset; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box; @error('entregaNuevaDireccion') border-color:#fca5a5; @enderror">
                    @error('entregaNuevaDireccion')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            @endif
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Referencia <span style="color:#9CA3AF; font-weight:400; text-transform:none;">(opcional)</span></p>
                <input wire:model="entregaReferencia" type="text"
                       placeholder="Ej: Portón azul, frente al parque..." autocomplete="off"
                       style="width:100%; background:#fff; -webkit-box-shadow:0 0 0 30px #fff inset; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;">
            </div>
        </div>

        {{-- Acciones finales --}}
        <div x-show="appToastShow && appToastType==='error'" x-cloak
             class="flex flex-row items-center gap-2"
             style="background:#FEF2F2;border:1.5px solid #FECACA;border-radius:12px;padding:10px 14px;margin-bottom:8px;">
            <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#EF4444" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span style="font-size:13px;font-weight:700;color:#EF4444;white-space:nowrap;" x-text="appToastMsg"></span>
        </div>
        <div style="display:flex; gap:10px;">
            <button wire:click="cambiarCliente"
                    style="flex:1; padding:13px 10px; background:#fff; color:#9CA3AF; font-size:13px; font-weight:700; border-radius:16px; border:1.5px solid #E5E7EB; cursor:pointer; -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:6px;">
                <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancelar
            </button>
            <button wire:click="confirmarPedido"
                    style="flex:2; padding:13px 10px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:800; border-radius:16px; border:none; cursor:pointer; box-shadow:0 4px 18px rgba(123,111,232,0.35); -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:6px;">
                <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Confirmar Plan
            </button>
        </div>
    </div>
    @endif

</div>

{{-- ── (grid movido al modal showProductos) ──────────────────────────────── --}}

@endif {{-- sinListasComunes --}}
@endif {{-- step oferta --}}
@endif {{-- step cliente || oferta --}}


{{-- ═════════════════════════════════════════════ STEP: RESUMEN ══════════ --}}
@if ($step === 'resumen')
<div style="background:#fff; min-height:100vh;">
<div class="mx-auto px-4 pb-10 pt-4" style="max-width:900px;">

    {{-- Header: VERIFICACIÓN --}}
    <div style="background:#7B6FE8; border-radius:14px; padding:16px 18px; margin-bottom:14px; box-shadow:0 4px 18px rgba(123,111,232,0.35);">
        <h2 style="font-size:20px; font-weight:800; color:#fff; letter-spacing:-0.3px; margin:0 0 2px; text-align:center;">VERIFICACIÓN</h2>
        <p style="font-size:11px; color:rgba(255,255,255,0.75); margin:0; text-align:center;">Revisá tu pedido antes de continuar</p>
    </div>

    {{-- Separador Cliente --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Dato Cliente</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
    </div>

    {{-- Cliente --}}
    <div style="background:#fff; border:1px solid #EDE9FE; border-radius:10px; padding:12px 14px; margin-bottom:14px; box-shadow:0 2px 12px rgba(123,111,232,0.12); display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <span style="font-size:17px; font-weight:800; color:#7B6FE8;">{{ strtoupper(substr($clienteNombre, 0, 1)) }}</span>
        </div>
        <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:4px;">
            @if ($clienteCI)
            <div style="display:flex; align-items:baseline; gap:6px;">
                <span style="font-size:10px; font-weight:700; color:#9B93E0; white-space:nowrap; text-transform:uppercase; letter-spacing:0.06em;">CI</span>
                <span style="font-size:13px; font-weight:700; color:#534AB7;">{{ $clienteCI }}</span>
            </div>
            @endif
            <span style="font-size:13px; font-weight:700; color:#3C3489; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">{{ $clienteNombre }}</span>
        </div>
    </div>

    {{-- Separador Productos --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Productos del Pedido</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
        <span style="font-size:11px; font-weight:600; color:#9B93E0;">{{ count($carrito) }} {{ count($carrito) === 1 ? 'ítem' : 'ítems' }}</span>
    </div>

    <style>
    @media (max-width:767px) { .prod-desk-wrap { display:none !important; } }
    @media (min-width:768px) { .prod-mob-cards { display:none !important; } }
    </style>

    {{-- DESKTOP: tabla --}}
    <div class="prod-desk-wrap" style="background:#fff; border-radius:12px; overflow:hidden; box-shadow:2px 6px 20px rgba(60,52,137,0.10); margin-bottom:14px;">
        @foreach ($carrito as $pid => $item)
        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}" wire:key="res-{{ $pid }}">
            <div style="width:52px; height:52px; border-radius:10px; border:1px solid #EDE9FE; background:#FAFAFE; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                @if ($item['image'])
                <img src="{{ $item['image'] }}" alt="{{ $item['nombre'] }}" style="width:100%; height:100%; object-fit:contain;">
                @else
                <svg width="22" height="22" fill="none" stroke="#CECBF6" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @endif
            </div>
            <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:2px;">
                    <span style="font-size:9px; font-weight:700; color:#7B6FE8; background:#EEEDFE; border-radius:4px; padding:1px 5px; text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">{{ $item['code'] ?? '' }}</span>
                    <span style="font-size:10px; font-weight:600; color:#9B93E0; white-space:nowrap;">{{ $item['cantidad'] }}×</span>
                </div>
                <span style="font-size:13px; font-weight:700; color:#3C3489; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item['nombre'] }}</span>
                <span style="font-size:11px; color:#9B93E0;">Bs {{ number_format($item['precio'], 2) }} c/u</span>
            </div>
            <div style="text-align:right; flex-shrink:0;">
                <span style="font-size:15px; font-weight:400; color:#7B6FE8; display:block;">Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                @if ($item['puntos'] > 0)
                <span style="font-size:10px; font-weight:400; background:#E1F5EE; color:#0F6E56; border-radius:99px; padding:1px 7px;">+{{ $item['puntos'] * $item['cantidad'] }} pts</span>
                @endif
            </div>
            <button wire:click="quitar({{ $item['item_id'] }})"
                    style="width:36px; height:36px; flex-shrink:0; display:flex; align-items:center; justify-content:center; background:#FEF2F2; border-radius:10px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none; clip-path:inset(0 round 10px);">
                <svg width="17" height="17" fill="none" stroke="#ef4444" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
        @endforeach
        <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px; padding:10px 14px; background:#F8F7FF; border-top:1px solid #EDE9FE;">
            <span style="font-size:12px; font-weight:600; color:#534AB7;">Total</span>
            <span style="font-size:18px; font-weight:400; color:#3C3489;">Bs {{ number_format($total, 2) }}</span>
            <span style="font-size:11px; font-weight:400; background:#E1F5EE; color:#0F6E56; border-radius:99px; padding:2px 9px;">+{{ number_format($puntos) }} pts</span>
        </div>
    </div>

    {{-- MOBILE: cards carrito --}}
    <div class="prod-mob-cards" style="margin-bottom:14px;">
        @foreach ($carrito as $pid => $item)
        <div style="background:#fff; border-radius:14px; box-shadow:2px 6px 20px rgba(60,52,137,0.10); margin-bottom:10px; overflow:hidden;" wire:key="mob-{{ $pid }}">
            <div style="display:flex; gap:12px; padding:12px;">
                {{-- Imagen --}}
                <div style="width:68px; height:68px; border-radius:12px; border:1px solid #EDE9FE; background:#FAFAFE; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                    @if ($item['image'])
                    <img src="{{ $item['image'] }}" alt="{{ $item['nombre'] }}" style="width:100%; height:100%; object-fit:contain;">
                    @else
                    <svg width="26" height="26" fill="none" stroke="#CECBF6" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                {{-- Info --}}
                <div style="flex:1; min-width:0;">
                    <span style="font-size:14px; font-weight:800; color:#3C3489; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; letter-spacing:0.01em;">{{ $item['nombre'] }}</span>
                    <div style="display:flex; align-items:center; gap:6px; margin-top:3px;">
                        <span style="font-size:9px; font-weight:700; color:#7B6FE8; background:#EEEDFE; border-radius:4px; padding:1px 6px; text-transform:uppercase; letter-spacing:0.06em;">{{ $item['code'] ?? '' }}</span>
                        <span style="font-size:12px; font-weight:700; color:#A89FD8;">× {{ $item['cantidad'] }}</span>
                    </div>
                    <span style="font-size:11px; color:#B0A8E0; display:block; margin-top:2px;">Bs {{ number_format($item['precio'], 2) }} c/u</span>
                </div>
                {{-- Botón eliminar --}}
                <button wire:click="quitar({{ $item['item_id'] }})"
                        style="width:32px; height:32px; flex-shrink:0; align-self:flex-start; display:flex; align-items:center; justify-content:center; background:#FEF2F2; border-radius:9px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none; clip-path:inset(0 round 9px);">
                    <svg width="15" height="15" fill="none" stroke="#ef4444" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            {{-- Pie: total + puntos --}}
            <div style="display:flex; align-items:center; gap:8px; padding:8px 12px; background:#F8F7FF; border-top:1px solid #EDE9FE;">
                <span style="font-size:11px; font-weight:600; color:#9B93E0; flex:1;">Total</span>
                <span style="font-size:17px; font-weight:400; color:#7B6FE8;">Bs {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                @if ($item['puntos'] > 0)
                <span style="font-size:10px; font-weight:400; background:#E1F5EE; color:#0F6E56; border-radius:99px; padding:2px 8px; white-space:nowrap;">+{{ $item['puntos'] * $item['cantidad'] }} pts</span>
                @endif
            </div>
        </div>
        @endforeach
        {{-- Total general --}}
        <div style="display:flex; align-items:center; gap:10px; padding:12px 14px; background:#F8F7FF; border-radius:14px; border:1px solid #EDE9FE;">
            <span style="font-size:13px; font-weight:700; color:#534AB7; flex:1;">Total Pedido</span>
            <span style="font-size:19px; font-weight:400; color:#3C3489;">Bs {{ number_format($total, 2) }}</span>
            <span style="font-size:11px; font-weight:400; background:#E1F5EE; color:#0F6E56; border-radius:99px; padding:3px 10px; white-space:nowrap;">+{{ number_format($puntos) }} pts</span>
        </div>
    </div>

    @if ($simulacion && !empty($simulacion['cuotas_preview']))

    {{-- Separador Plan de Pagos --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Plan de Pagos</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
        <span style="font-size:11px; font-weight:600; color:#9B93E0;">{{ count($simulacion['cuotas_preview']) }} cuotas</span>
    </div>

    {{-- Tabla cuotas --}}
    <div style="background:#fff; border-radius:12px; overflow:hidden; box-shadow:2px 6px 20px rgba(60,52,137,0.10); margin-bottom:14px;">
        {{-- Header --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; padding:8px 12px; background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Cuota</span>
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Vencimiento</span>
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Monto</span>
        </div>
        {{-- Filas --}}
        @foreach ($simulacion['cuotas_preview'] as $cuota)
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; align-items:center; padding:10px 12px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}">
            <div style="display:flex; align-items:center; gap:8px;">
                @if ($cuota['tipo'] === 'inicial')
                <span style="width:26px; height:26px; border-radius:50%; background:#E1F5EE; color:#0F6E56; font-size:9px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;">0</span>
                <div>
                    <span style="font-size:11px; font-weight:700; color:#0F6E56; display:block;">Inicial</span>
                    <span style="font-size:9px; font-weight:600; background:#E1F5EE; color:#0F6E56; border-radius:4px; padding:1px 4px;">Inicial</span>
                </div>
                @else
                <span style="width:26px; height:26px; border-radius:50%; background:#EEEDFE; color:#534AB7; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $cuota['numero'] }}</span>
                <span style="font-size:11px; font-weight:600; color:#3C3489;">Cuota {{ $cuota['numero'] }}</span>
                @endif
            </div>
            <span style="font-size:11px; {{ $cuota['tipo'] === 'inicial' ? 'font-weight:700; color:#0F6E56;' : 'color:#6B7280;' }}">{{ $cuota['fecha'] }}</span>
            <span style="font-size:14px; font-weight:800; color:#7B6FE8; text-align:right;">Bs {{ number_format($cuota['monto'], 2) }}</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Navegación inferior --}}
    <div style="display:flex; align-items:center; gap:8px; margin-top:8px;">
        <button wire:click="volverOferta"
                style="background:#F97316; border:1.5px solid #F97316; border-radius:10px; padding:10px 16px; display:flex; align-items:center; gap:6px; flex-shrink:0; cursor:pointer; box-shadow:0 2px 10px rgba(249,115,22,0.35); -webkit-appearance:none; appearance:none; clip-path:inset(0 round 10px);">
            <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
            </svg>
            <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9m-9-4h4"/>
            </svg>
            <span style="font-size:12px; font-weight:700; color:#fff;">Carrito</span>
        </button>
        <div style="flex:1;"></div>
        <button wire:click="irEntrega"
                @disabled(empty($carrito))
                style="{{ !empty($carrito) ? 'background:#7B6FE8; border:1.5px solid #7B6FE8; cursor:pointer; box-shadow:0 2px 10px rgba(123,111,232,0.35);' : 'background:#f3f4f6; border:1.5px solid #d1d5db; opacity:0.5; cursor:not-allowed;' }} border-radius:10px; padding:10px 16px; display:flex; align-items:center; gap:6px; flex-shrink:0; -webkit-appearance:none; appearance:none; clip-path:inset(0 round 10px);">
            <span style="font-size:12px; font-weight:700; color:{{ !empty($carrito) ? '#fff' : '#9ca3af' }};">Ir a Entrega</span>
            <svg width="14" height="14" fill="none" stroke="{{ !empty($carrito) ? '#fff' : '#9ca3af' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1 3h15v13H1zM16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5" stroke-width="1.8"/>
                <circle cx="18.5" cy="18.5" r="2.5" stroke-width="1.8"/>
            </svg>
            <svg width="12" height="12" fill="none" stroke="{{ !empty($carrito) ? '#fff' : '#9ca3af' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

</div>
</div>
@endif {{-- step resumen --}}


{{-- ═════════════════════════════════════════════ STEP: ENTREGA ══════════ --}}
@if ($step === 'entrega')
<div style="background:#fff; min-height:100vh;">
<div class="max-w-2xl mx-auto px-4 pt-4 pb-10">

    {{-- Header: COMPLEMENTO --}}
    <div style="background:#7B6FE8; border-radius:14px; padding:16px 18px; margin-bottom:14px; box-shadow:0 4px 18px rgba(123,111,232,0.35);">
        <h2 style="font-size:20px; font-weight:800; color:#fff; letter-spacing:-0.3px; margin:0 0 2px; text-align:center;">COMPLEMENTO</h2>
        <p style="font-size:11px; color:rgba(255,255,255,0.75); margin:0; text-align:center;">Documentación y Entrega</p>
    </div>

    {{-- Separador Dato Cliente --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Dato Cliente</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
    </div>

    {{-- Card Cliente --}}
    <div style="background:#fff; border:1px solid #EDE9FE; border-radius:10px; padding:12px 14px; margin-bottom:14px; box-shadow:0 2px 12px rgba(123,111,232,0.12); display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#EEEDFE; border:1.5px solid #C4B5FD; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <span style="font-size:17px; font-weight:800; color:#7B6FE8;">{{ strtoupper(substr($clienteNombre, 0, 1)) }}</span>
        </div>
        <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:4px;">
            @if ($clienteCI)
            <div style="display:flex; align-items:baseline; gap:6px;">
                <span style="font-size:10px; font-weight:700; color:#9B93E0; white-space:nowrap; text-transform:uppercase; letter-spacing:0.06em;">CI</span>
                <span style="font-size:13px; font-weight:700; color:#534AB7;">{{ $clienteCI }}</span>
            </div>
            @endif
            <span style="font-size:13px; font-weight:700; color:#3C3489; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">{{ $clienteNombre }}</span>
        </div>
    </div>

    @if(false){{-- doc section moved to step oferta --}}

            {{-- 1. Anverso CI --}}
            <label style="cursor:pointer;">
                <div style="{{ $docAnversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                        @if($docAnversoCi)
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                        @endif
                    </div>
                    <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:{{ $docAnversoCi ? '#0F6E56' : '#534AB7' }};">Anverso CI</span>
                    <span style="font-size:10px; color:{{ $docAnversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoCi ? 'OK' : 'JPG/PDF' }}</span>
                </div>
                <input type="file" wire:model="docAnversoCi" accept="image/*,application/pdf" class="hidden">
            </label>

            {{-- 2. Reverso CI --}}
            <label style="cursor:pointer;">
                <div style="{{ $docReversoCi ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoCi ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                        @if($docReversoCi)
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                        @endif
                    </div>
                    <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:{{ $docReversoCi ? '#0F6E56' : '#534AB7' }};">Reverso CI</span>
                    <span style="font-size:10px; color:{{ $docReversoCi ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoCi ? 'OK' : 'JPG/PDF' }}</span>
                </div>
                <input type="file" wire:model="docReversoCi" accept="image/*,application/pdf" class="hidden">
            </label>

            {{-- 3. Anverso Documento --}}
            <label style="cursor:pointer;">
                <div style="{{ $docAnversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAnversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                        @if($docAnversoDoc)
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </div>
                    <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:{{ $docAnversoDoc ? '#0F6E56' : '#534AB7' }};">Anverso Doc</span>
                    <span style="font-size:10px; color:{{ $docAnversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docAnversoDoc ? 'OK' : 'JPG/PDF' }}</span>
                </div>
                <input type="file" wire:model="docAnversoDoc" accept="image/*,application/pdf" class="hidden">
            </label>

            {{-- 4. Reverso Documento --}}
            <label style="cursor:pointer;">
                <div style="{{ $docReversoDoc ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docReversoDoc ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                        @if($docReversoDoc)
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </div>
                    <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:{{ $docReversoDoc ? '#0F6E56' : '#534AB7' }};">Reverso Doc</span>
                    <span style="font-size:10px; color:{{ $docReversoDoc ? '#0F6E56' : '#AFA9EC' }};">{{ $docReversoDoc ? 'OK' : 'JPG/PDF' }}</span>
                </div>
                <input type="file" wire:model="docReversoDoc" accept="image/*,application/pdf" class="hidden">
            </label>

            {{-- 5. Aviso de Luz --}}
            <label style="cursor:pointer;">
                <div style="{{ $docAvisoLuz ? 'border:1.5px solid #0F6E56; background:#F0FDF4;' : 'border:1.5px dashed #9CA3AF; background:#fff;' }} border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; {{ $docAvisoLuz ? 'background:#DCFCE7;' : 'background:#EEEDFE;' }}">
                        @if($docAvisoLuz)
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @endif
                    </div>
                    <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:{{ $docAvisoLuz ? '#0F6E56' : '#534AB7' }};">Aviso Luz</span>
                    <span style="font-size:10px; color:{{ $docAvisoLuz ? '#0F6E56' : '#AFA9EC' }};">{{ $docAvisoLuz ? 'OK' : 'JPG/PDF' }}</span>
                </div>
                <input type="file" wire:model="docAvisoLuz" accept="image/*,application/pdf" class="hidden">
            </label>

        </div>
    </div>
    @endif{{-- /doc section moved --}}

    {{-- Separador Dirección de Entrega --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Dirección de Entrega</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
    </div>

    {{-- Card Dirección --}}
    <div style="background:#fff; border-radius:12px; padding:12px; box-shadow:2px 6px 20px rgba(60,52,137,0.10); margin-bottom:14px;">

        {{-- Toggle --}}
        <div class="grid grid-cols-2 gap-2 mb-3">
            <button wire:click="$set('tipoEntrega','domicilio')" type="button"
                    style="{{ $tipoEntrega === 'domicilio' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                🏠 Domicilio
            </button>
            <button wire:click="$set('tipoEntrega','nuevo')" type="button"
                    style="{{ $tipoEntrega === 'nuevo' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                📍 Nuevo lugar
            </button>
        </div>

        {{-- Campos Domicilio (readonly) --}}
        @if ($tipoEntrega === 'domicilio')
        <div class="grid grid-cols-2 gap-2 mb-3">
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad</p>
                <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteCiudad ?: '—' }}</div>
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p>
                <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteProvincia ?: '—' }}</div>
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p>
                <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $entregaClienteMunicipio ?: '—' }}</div>
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección</p>
                <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaClienteDireccion ?: '—' }}</div>
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
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad *</p>
                <button type="button"
                        @click="ubEntModal=true; ubEntTipo='ciudad'; ubEntOpciones=@js($ciudadesAll->pluck('nombre')->toArray()); ubEntSearch=''"
                        style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevoCiudad ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:#fff; cursor:pointer; box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                    <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevoCiudad ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevoCiudad ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevoCiudad ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevoCiudad ? ucwords(strtolower($entregaNuevoCiudad)) : 'Seleccionar ciudad' }}</span>
                    <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                @error('entregaNuevoCiudad')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p>
                <button type="button"
                        @if($entregaNuevoCiudad) @click="ubEntModal=true; ubEntTipo='provincia'; ubEntOpciones=@js($entregaProvincias->pluck('nombre')->toArray()); ubEntSearch=''" @endif
                        style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevaProvincia ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:{{ $entregaNuevoCiudad ? '#fff' : '#F8F7FF' }}; {{ $entregaNuevoCiudad ? 'cursor:pointer;' : 'cursor:not-allowed;' }} box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                    <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevaProvincia ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevaProvincia ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevaProvincia ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevaProvincia ? ucwords(strtolower($entregaNuevaProvincia)) : 'Seleccionar provincia' }}</span>
                    <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p>
                <button type="button"
                        @if($entregaNuevaProvincia) @click="ubEntModal=true; ubEntTipo='municipio'; ubEntOpciones=@js($entregaMunicipios->pluck('nombre')->toArray()); ubEntSearch=''" @endif
                        style="width:100%; padding:8px 10px; border:1.5px solid {{ $entregaNuevoMunicipio ? '#C4B5FD' : '#EDE9FE' }}; border-radius:8px; background:{{ $entregaNuevaProvincia ? '#fff' : '#F8F7FF' }}; {{ $entregaNuevaProvincia ? 'cursor:pointer;' : 'cursor:not-allowed;' }} box-sizing:border-box; display:flex; align-items:center; gap:6px; overflow:hidden; transition:all 0.15s;">
                    <svg width="11" height="11" fill="none" stroke="{{ $entregaNuevoMunicipio ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span style="flex:1; min-width:0; text-align:left; font-size:12px; color:{{ $entregaNuevoMunicipio ? '#3C3489' : '#4B5563' }}; font-weight:{{ $entregaNuevoMunicipio ? '500' : '500' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $entregaNuevoMunicipio ? ucwords(strtolower($entregaNuevoMunicipio)) : 'Seleccionar municipio' }}</span>
                    <svg width="8" height="8" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            <div>
                <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección *</p>
                <input wire:model="entregaNuevaDireccion" type="text" placeholder="Calle y número"
                       style="width:100%; background:#fff; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box; @error('entregaNuevaDireccion') border-color:#fca5a5; @enderror">
                @error('entregaNuevaDireccion')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        {{-- Referencia --}}
        <div>
            <p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Referencia <span style="color:#9CA3AF; font-weight:400; text-transform:none;">(opcional)</span></p>
            <input wire:model="entregaReferencia" type="text"
                   placeholder="Ej: Portón azul, frente al parque..."
                   style="width:100%; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;">
        </div>
    </div>

    {{-- Errores de documentos --}}
    @php
        $docErrors = collect(['docAnversoCi','docReversoCi','docAnversoDoc','docReversoDoc','docAvisoLuz'])
            ->map(fn($f) => $errors->first($f))->filter();
    @endphp
    @if($docErrors->isNotEmpty())
    <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:10px; padding:10px 14px; margin-bottom:12px;">
        @foreach($docErrors as $err)
        <p style="color:#dc2626; font-size:12px; margin:2px 0;">• Falta subir: {{ $err }}</p>
        @endforeach
    </div>
    @endif

    {{-- Error general --}}
    @error('pedido')
    <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:10px; padding:10px 14px; margin-bottom:12px; color:#dc2626; font-size:13px;">
        {{ $message }}
    </div>
    @enderror

    {{-- Botones pie --}}
    <div x-show="appToastShow && appToastType==='error'" x-cloak
         class="flex flex-row items-center gap-2"
         style="background:#FEF2F2;border:1.5px solid #FECACA;border-radius:12px;padding:10px 14px;margin-bottom:8px;margin-top:8px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#EF4444" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 3h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span style="font-size:13px;font-weight:700;color:#EF4444;white-space:nowrap;" x-text="appToastMsg"></span>
    </div>
    <div style="display:flex; align-items:center; gap:8px; margin-top:8px;">
        <button wire:click="volverResumen" type="button"
                style="background:#F97316; border:1.5px solid #F97316; border-radius:10px; padding:10px 16px; display:flex; align-items:center; gap:6px; flex-shrink:0; cursor:pointer; box-shadow:0 2px 10px rgba(249,115,22,0.35); -webkit-appearance:none; appearance:none; clip-path:inset(0 round 10px);">
            <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
            </svg>
            <svg width="13" height="13" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
            </svg>
            <span style="font-size:12px; font-weight:700; color:#fff;">Verificación</span>
        </button>
        <button wire:click="confirmarPedido" wire:loading.attr="disabled"
                style="flex:1; background:#7B6FE8; border:1.5px solid #7B6FE8; border-radius:10px; padding:10px 16px; display:flex; align-items:center; justify-content:center; gap:6px; cursor:pointer; box-shadow:0 2px 10px rgba(123,111,232,0.35); -webkit-appearance:none; appearance:none; clip-path:inset(0 round 10px);">
            <span wire:loading.remove wire:target="confirmarPedido" style="font-size:13px; font-weight:700; color:#fff;">Confirmar Pedido</span>
            <span wire:loading wire:target="confirmarPedido" style="font-size:13px; font-weight:700; color:#fff;">Procesando...</span>
            <svg wire:loading.remove wire:target="confirmarPedido" width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </button>
    </div>

</div>
</div>
@endif {{-- step entrega --}}

{{-- ══ MODAL: BUSCAR CLIENTE ════════════════════════════════════════════════ --}}
<style>
.modal-productos-panel {
    overflow: hidden;
    min-height: 0;
}
@media (min-width: 768px) {
    .modal-productos-outer {
        background: rgba(20,10,40,0.45) !important;
        backdrop-filter: blur(2px);
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    .modal-productos-panel {
        max-height: 85vh;
        border-radius: 10px;
        box-shadow: 0 24px 60px rgba(60,52,137,0.22), 0 0 0 1px rgba(196,181,253,0.15);
        flex: none;
        max-width: 900px;
        width: 100%;
    }
}
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

{{-- ═══════════════════════════ MODAL: AGREGAR PRODUCTOS ═══════════════════ --}}
@if ($step === 'oferta' && $clienteId && !$sinListasComunes)
<div x-show="showProductos" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 md:absolute md:inset-0 flex flex-col modal-productos-outer"
     style="z-index:400; background:#fff;">

    {{-- Panel centrado (desktop: dialog card; mobile: full screen) --}}
    <div class="flex flex-col flex-1 w-full modal-productos-panel"
         style="background:#fff;">

        {{-- TOP BAR --}}
        <div style="flex-shrink:0; background:#fff; border-bottom:1.5px solid #EDE9FE; padding:12px 14px 10px;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="position:relative; text-align:center; margin-bottom:10px;">
                <span style="font-size:16px; font-weight:900; color:#1a1a1a; letter-spacing:0.08em; text-transform:uppercase; display:block;">Seleccionar Artículos</span>
                <div style="height:2px; background:linear-gradient(to right,#7B6FE8,#C4B5FD); border-radius:1px; margin-top:4px;"></div>
                <button @click="showProductos = false"
                        style="position:absolute; top:0; right:0; width:28px; height:28px; border-radius:50%; background:#F3F4F6; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;">
                    <svg width="14" height="14" fill="none" stroke="#6B7280" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @php
            $mColors = [
                ['bg'=>'#FFF7ED','iconBg'=>'#F97316','selBorder'=>'#F97316','selCard'=>'#FFF7ED','text'=>'#C2410C'],
                ['bg'=>'#F0EEFF','iconBg'=>'#7B6FE8','selBorder'=>'#7B6FE8','selCard'=>'#EDE9FE','text'=>'#3C3489'],
                ['bg'=>'#F0FDF4','iconBg'=>'#059669','selBorder'=>'#059669','selCard'=>'#DCFCE7','text'=>'#065F46'],
                ['bg'=>'#EFF6FF','iconBg'=>'#3B82F6','selBorder'=>'#3B82F6','selCard'=>'#DBEAFE','text'=>'#1D4ED8'],
            ];
            $mIcons = [
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 8v1m0-8c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
            ];
            $listaLocked = !empty($carrito) ? (collect($carrito)->first()['lista_id'] ?? null) : null;
            $mSelAll   = !$listaLocked && $filterLista === '';
            $mActiveId = $listaLocked ?? ($filterLista !== '' ? $filterLista : null);
            $mLabel    = $mActiveId ? ucwords(strtolower($listasInfo[(string)$mActiveId]['nombre'] ?? '')) : 'Seleccionar Oferta Comercial';
            $mIdx      = $mActiveId ? array_search((string)$mActiveId, array_keys($listasInfo)) : -1;
            $mCol      = ($mActiveId && $mIdx !== false) ? $mColors[$mIdx % count($mColors)] : null;
            @endphp
            <div style="display:flex; flex-direction:column; gap:8px;" x-data="{ promoOpen: false }">

                {{-- Fila 1: Oferta Comercial --}}
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="hidden md:block" style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Oferta Comercial:</span>

                    {{-- Botón dropdown lista --}}
                    <div style="position:relative; flex:1;">
                        <button @click="{{ $listaLocked ? '' : 'promoOpen = !promoOpen' }}"
                                style="width:100%; display:flex; align-items:center; gap:6px; padding:8px 10px; border-radius:10px;
                                       border:1.5px solid {{ $listaLocked ? '#D1D5DB' : ($mSelAll ? '#E5E7EB' : ($mCol['selBorder'] ?? '#7B6FE8')) }};
                                       background:{{ $listaLocked ? '#F9FAFB' : ($mSelAll ? '#fff' : ($mCol['selCard'] ?? '#EEEDFE')) }};
                                       cursor:{{ $listaLocked ? 'default' : 'pointer' }}; -webkit-appearance:none; appearance:none;">
                            <div style="width:22px; height:22px; border-radius:6px; background:{{ $listaLocked ? '#E5E7EB' : ($mSelAll ? '#F3F4F6' : ($mCol['iconBg'] ?? '#7B6FE8')) }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                @if ($listaLocked)
                                <svg width="11" height="11" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                @elseif ($mSelAll)
                                <svg width="11" height="11" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                @else
                                <svg width="11" height="11" fill="none" stroke="#fff" viewBox="0 0 24 24">{!! $mIcons[$mIdx % count($mIcons)] !!}</svg>
                                @endif
                            </div>
                            <span style="font-size:12px; font-weight:700; color:{{ $listaLocked ? '#6B7280' : ($mSelAll ? '#9CA3AF' : ($mCol['text'] ?? '#534AB7')) }}; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:left;">{{ $mLabel }}</span>
                            @if (!$listaLocked)
                            <svg width="10" height="10" fill="none" stroke="#9CA3AF" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                            @endif
                        </button>

                        {{-- Panel dropdown --}}
                        <div x-show="promoOpen" x-cloak @click.away="promoOpen = false"
                             style="position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff;
                                    border-radius:12px; box-shadow:0 8px 24px rgba(60,52,137,0.18);
                                    border:1px solid #EDE9FE; z-index:500; overflow:hidden;">

                            <button wire:click="$set('filterLista', '')" @click="promoOpen = false"
                                    style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 14px;
                                           background:{{ $mSelAll ? '#FFF7ED' : '#fff' }}; border:none; cursor:pointer;
                                           border-bottom:1px solid #F3F4F6; -webkit-appearance:none; appearance:none;">
                                <div style="width:28px; height:28px; border-radius:8px; background:{{ $mSelAll ? '#F97316' : '#F3F4F6' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $mSelAll ? '#fff' : '#9CA3AF' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                </div>
                                <span style="font-size:13px; font-weight:700; color:{{ $mSelAll ? '#C2410C' : '#374151' }};">Todos</span>
                            </button>

                            @foreach ($listasInfo as $lid => $info)
                            @php
                                $mCi  = $loop->index % count($mColors);
                                $mC   = $mColors[$mCi];
                                $mS   = $filterLista === (string)$lid;
                                $mIco = $mIcons[$mCi];
                            @endphp
                            <button wire:click="$set('filterLista', '{{ $lid }}')" @click="promoOpen = false"
                                    style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 14px;
                                           background:{{ $mS ? $mC['selCard'] : '#fff' }}; border:none; cursor:pointer;
                                           {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }} -webkit-appearance:none; appearance:none;">
                                <div style="width:28px; height:28px; border-radius:8px; background:{{ $mS ? $mC['iconBg'] : $mC['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $mS ? '#fff' : $mC['iconBg'] }}" viewBox="0 0 24 24">{!! $mIco !!}</svg>
                                </div>
                                <span style="font-size:13px; font-weight:700; color:{{ $mS ? $mC['text'] : '#374151' }}; white-space:nowrap;">{{ ucwords(strtolower($info['nombre'])) }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Fila 2: Buscar Productos --}}
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="hidden md:block" style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Buscar Productos:</span>
                    <div style="position:relative; flex:1;">
                        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; pointer-events:none;" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="searchProducto" type="text"
                               placeholder="Buscar producto..."
                               style="width:100%; padding:8px 10px 8px 28px; background:#F8F7FF; border:2px solid #C4B5FD; border-radius:10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#7B6FE8'; this.style.background='#fff';"
                               onblur="this.style.borderColor='#C4B5FD'; this.style.background='#F8F7FF';">
                    </div>
                </div>

            </div>
        </div>
        </div>

        {{-- LISTA SCROLLABLE --}}
        <div style="flex:1; overflow-y:auto; padding:10px 12px;">
        <div style="max-width:900px; margin:0 auto;">
            <div style="display:flex; flex-direction:column; gap:8px;">
                @php
                $productosModal = $listaLocked
                    ? collect($ofertaPorLista)->filter(fn($items, $lid) => (string)$lid === (string)$listaLocked)->flatten(1)
                    : collect($ofertaPorLista)->flatten(1);
                @endphp
                @forelse ($productosModal as $p)
                @php $iid2=(string)$p['item_id']; $qty2=isset($carrito[$iid2])?$carrito[$iid2]['cantidad']:0; @endphp
                <div x-data="{ n: 0, maxStock: @js((int)$p['stock']) }"
                     x-on:carrito-vaciado.window="n = 0"
                     wire:key="mod-{{ $iid2 }}"
                     style="background:#fff; border:1.5px solid #D1D5DB; border-radius:12px; padding:20px 12px; box-shadow:0 2px 4px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.10), 0 24px 40px rgba(0,0,0,0.06);">

                    {{-- Fila 1: indicador circular + código arriba, descripción abajo --}}
                    <div style="display:flex; align-items:flex-start; gap:7px; margin-bottom:7px;">
                        <div style="width:22px; height:22px; border-radius:50%; background:{{ $qty2 > 0 ? '#f97316' : '#EDE9FE' }}; border:1.5px solid {{ $qty2 > 0 ? '#f97316' : '#D4CFF8' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                            @if ($qty2 > 0)
                            <span style="font-size:10px; font-weight:800; color:#fff; line-height:1;">{{ $qty2 }}</span>
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <span style="font-size:16px; font-weight:800; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ ucwords(strtolower($p['nombre'])) }}">{{ ucwords(strtolower($p['nombre'])) }}</span>
                            <span style="font-size:13px; font-weight:400; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p['code'] ?? '' }}</span>
                        </div>
                    </div>

                    {{-- Fila 2: Precio Bs Un / Total Bs Total Puntos --}}
                    <div style="margin-bottom:8px; background:#F8F7FF; border-radius:8px; padding:8px 10px;">
                        <div style="display:flex; align-items:center; gap:14px; margin-bottom:4px;">
                            <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Precio Bs Un: <span style="color:#7B6FE8; font-size:14px; font-weight:400;">{{ number_format($p['precio'], 2) }}</span></span>
                            <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Puntos: <span style="color:#111827; font-size:14px; font-weight:400;">{{ $p['puntos'] }}</span></span>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Bs: <span style="color:#3C3489; font-size:14px; font-weight:400;">{{ number_format($p['precio'] * $qty2, 2) }}</span></span>
                            <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Puntos: <span style="color:#111827; font-size:14px; font-weight:400;">+{{ $p['puntos'] * $qty2 }}</span></span>
                        </div>
                    </div>

                    {{-- Pie --}}
                    <div style="display:flex; align-items:center; gap:6px;">
                        <span style="font-size:13px; font-weight:700; color:#f97316; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-left:10px; padding-right:8px;">{{ $p['lista_nombre'] }}</span>
                        <button @click="if(n===0) n=1; $wire.agregar({{ $p['item_id'] }}, n).then(() => n=0)"
                                style="background:#fff; color:#f97316; border:1.5px solid #f97316; border-radius:8px; padding:7px 14px; font-size:13px; font-weight:700; cursor:pointer; -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:6px; flex-shrink:0;">
                            <svg style="width:14px; height:14px; flex-shrink:0;" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Agregar
                        </button>
                        @if ($qty2 > 0)
                        <button wire:click="quitar({{ $p['item_id'] }})"
                                style="width:30px; height:30px; border-radius:50%; background:#ef4444; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; -webkit-appearance:none; appearance:none; box-shadow:0 2px 8px rgba(239,68,68,0.40);">
                            <svg style="width:13px; height:13px;" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding:40px 16px;">
                    <svg style="width:40px; height:40px; margin:0 auto 10px; display:block;" fill="none" stroke="#CECBF6" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p style="font-size:13px; font-weight:500; color:#9B93E0; margin:0;">No hay productos disponibles</p>
                </div>
                @endforelse
            </div>
        </div>
        </div>

        {{-- PIE MODAL: cerrar --}}
        <div style="flex-shrink:0; padding:10px 14px; background:#fff; border-top:1.5px solid #EDE9FE;">
        <div style="max-width:900px; margin:0 auto;">
            <button @click="showProductos = false"
                    style="width:100%; padding:13px; background:{{ $cantidad > 0 ? '#7B6FE8' : '#6B7280' }}; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:8px;">
                @if ($cantidad > 0)
                    <span style="width:22px; height:22px; border-radius:50%; background:#f97316; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span style="text-decoration:underline; text-underline-offset:3px;">Confirmar &mdash; {{ $cantidad }} Un.</span>
                @else
                    <span style="width:22px; height:22px; border-radius:50%; background:#f97316; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                    <span style="text-decoration:underline; text-underline-offset:3px;">Regresar</span>
                @endif
            </button>
        </div>
        </div>
    </div>{{-- /modal-productos-panel --}}
</div>
@endif

<div x-show="showSearch" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 flex items-center justify-center"
     style="z-index:300; background:rgba(30,24,80,0.22); backdrop-filter:blur(2px);"
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
                        <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="1.7" viewBox="0 0 24 24">
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
                        style="padding:9px 11px; background:#fff; border-radius:11px; border:1px solid #E5E7EB; cursor:pointer; transition:all 0.12s;"
                        onmouseover="this.style.background='#F9FAFB'; this.style.borderColor='#D1D5DB'; this.querySelector('.nombre-text').style.color='#3C3489';"
                        onmouseout="this.style.background='#fff'; this.style.borderColor='#E5E7EB'; this.querySelector('.nombre-text').style.color='#534AB7';">
                    <div style="width:32px; height:32px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <span style="font-weight:700; font-size:13px; color:#7B6FE8;">{{ strtoupper(substr($c['nombre'], 0, 1)) }}</span>
                    </div>
                    <div style="min-width:0; flex:1; text-align:left;">
                        <p class="nombre-text" style="font-weight:600; font-size:13px; color:#534AB7; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; transition:color 0.12s;">{{ $c['nombre'] }}</p>
                        <p style="font-size:11px; font-weight:700; color:#6B7280; margin:1px 0 0; font-family:monospace;">CI: {{ $c['ci'] }}</p>
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
{{-- Modal selector Ciudad/Provincia/Municipio para dirección de entrega --}}
<div x-show="ubEntModal" x-cloak
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 flex items-center justify-center"
     style="z-index:500; background:rgba(30,24,80,0.22); backdrop-filter:blur(2px);"
     @click.self="ubEntModal=false; ubEntSearch=''">
    <div style="background:#fff; border-radius:16px; width:85%; max-width:300px; max-height:60vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 8px 32px rgba(60,52,137,0.22);">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:13px 16px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
            <span x-text="ubEntTipo === 'ciudad' ? 'Seleccionar ciudad' : ubEntTipo === 'provincia' ? 'Seleccionar provincia' : 'Seleccionar municipio'"
                  style="font-size:13px; font-weight:600; color:#534AB7;"></span>
            <button type="button" @click="ubEntModal=false; ubEntSearch=''"
                    style="width:24px; height:24px; border-radius:6px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                <svg width="9" height="9" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:10px 12px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
            <input x-model="ubEntSearch" type="text" placeholder="Buscar..."
                   style="width:100%; padding:7px 10px; border:1.5px solid #EDE9FE; border-radius:8px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"
                   onfocus="this.style.borderColor='#7B6FE8'" onblur="this.style.borderColor='#EDE9FE'">
        </div>
        <div style="overflow-y:auto; flex:1; min-height:0; padding:4px 0;">
            <template x-for="op in ubEntOpciones.filter(o => o.toLowerCase().includes(ubEntSearch.toLowerCase()))" :key="op">
                <button type="button"
                        @click="
                            if(ubEntTipo==='ciudad') $wire.set('entregaNuevoCiudad', op);
                            else if(ubEntTipo==='provincia') $wire.set('entregaNuevaProvincia', op);
                            else $wire.set('entregaNuevoMunicipio', op);
                            ubEntModal=false; ubEntSearch='';
                        "
                        x-text="op.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())"
                        style="width:100%; text-align:left; padding:9px 16px; font-size:13px; color:#3C3489; background:transparent; border:none; cursor:pointer; transition:background 0.1s;"
                        onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='transparent'">
                </button>
            </template>
            <p x-show="ubEntOpciones.filter(o => o.toLowerCase().includes(ubEntSearch.toLowerCase())).length === 0"
               style="text-align:center; padding:16px; font-size:12px; color:#9B93E0; margin:0;">Sin resultados</p>
        </div>
    </div>
</div>

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
                            <svg width="13" height="13" fill="none" stroke="{{ $regCiudad ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
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
                            <svg width="13" height="13" fill="none" stroke="{{ $regProvincia ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
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
                            <svg width="13" height="13" fill="none" stroke="{{ $regMunicipio ? '#7B6FE8' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;">
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
                            onmouseover="this.style.background='#F5F3FF'; this.style.color='#7B6FE8';"
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
