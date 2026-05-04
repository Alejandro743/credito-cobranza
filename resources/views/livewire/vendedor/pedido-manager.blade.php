<div>

<style>
.mp-wrap { overflow-x: auto; background: #fff; }
.mp-table {
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
}
.mp-table .sticky-combined {
    position: sticky;
    left: 0;
    z-index: 2;
    background: #fff;
    padding: 0;
    box-shadow: 4px 0 6px -2px rgba(0,0,0,0.07);
}
.mp-table thead .sticky-combined { background: #EFF6FF; }
</style>

@php
    $theadStyle = 'background:#FAEEDA; color:#633806; font-size:10px; font-weight:500; letter-spacing:0.5px;';
@endphp

{{-- Topbar --}}
<div class="px-3 py-3 flex items-center justify-between" style="background:#FAEEDA;">
    <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
            class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0 transition-colors hover:opacity-75"
            style="background:rgba(99,56,6,0.12);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#633806;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <h1 class="font-bold text-base flex-1" style="color:#633806;">Revisión del Crédito</h1>
    <span class="text-sm font-medium" style="color:#633806;">{{ now()->format('d/m/Y') }}</span>
</div>

{{-- Toast --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="p-4 sm:p-6">

{{-- ══════════════════════ DETAIL ══════════════════════ --}}
@if ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = $p->estado === 'aprobado'; @endphp

<div class="max-w-2xl mx-auto" style="padding:0 0 40px;">

    @php
        $estadoConfig = match($p->estado) {
            'en_espera' => ['color' => '#854F0B', 'bg' => '#FEF3C7', 'border' => '#FCD34D', 'dot' => '#D97706'],
            'revision'   => ['color' => '#0369A1', 'bg' => '#F0F9FF', 'border' => '#7DD3FC', 'dot' => '#0284C7'],
            'aprobado'  => ['color' => '#15803D', 'bg' => '#F0FDF4', 'border' => '#86EFAC', 'dot' => '#16A34A'],
            'rechazado' => ['color' => '#B91C1C', 'bg' => '#FEF2F2', 'border' => '#FCA5A5', 'dot' => '#DC2626'],
            default     => ['color' => '#6b7280', 'bg' => '#f3f4f6', 'border' => '#d1d5db', 'dot' => '#9ca3af'],
        };
    @endphp

    {{-- Cabecera --}}
    <div style="background:{{ $estadoConfig['bg'] }}; border:1px solid {{ $estadoConfig['border'] }}; border-radius:14px; padding:16px 18px; margin:0 0 20px;">

        {{-- Fila: volver | título | espacio --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="backToList"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid {{ $estadoConfig['border'] }}; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="{{ $estadoConfig['color'] }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:{{ $estadoConfig['color'] }};">Volver</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:24px; font-weight:800; color:#3C3489; letter-spacing:-0.3px; margin:0;">
                SOLICITUD DE CRÉDITO
            </h1>
            <div style="width:52px; flex-shrink:0;"></div>
        </div>

        {{-- Estado centrado --}}
        <p style="text-align:center; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:{{ $estadoConfig['color'] }}; margin-bottom:8px;">{{ $p->estado_badge['label'] }}</p>

        {{-- Nro solicitud centrado --}}
        <div style="text-align:center;">
            <span style="font-size:11px; font-weight:500; color:#AFA9EC;">
                Nro. Solicitud: <span style="font-family:monospace; font-weight:700; color:#534AB7;">{{ $p->numero }}</span>
            </span>
        </div>

    </div>

    {{-- Separador Datos Cliente --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#534AB7;">Datos Cliente</span>
        <div style="flex:1; height:1px; background:#9C96E8;"></div>
    </div>

    {{-- Card cliente --}}
    <div x-data="{ modal: false }">
        <div class="bg-white overflow-hidden mb-3" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none; padding:10px 12px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:3px;">
                <span style="font-size:9px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em;">Cliente</span>
                <button @click="modal = true"
                        style="display:inline-flex; align-items:center; gap:4px; background:#EEEDFE; border:none; border-radius:6px; padding:2px 8px; cursor:pointer;">
                    <svg width="10" height="10" fill="none" stroke="#534AB7" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span style="font-size:9px; font-weight:600; color:#534AB7;">Ver Cliente</span>
                </button>
            </div>
            <span style="font-size:13px; font-weight:600; color:#3C3489; display:block;">
                {{ $p->cliente->ci ? $p->cliente->ci . ' — ' : '' }}{{ $p->cliente->nombre_completo }}
            </span>
        </div>

        {{-- Modal datos cliente --}}
        <div x-show="modal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(20,10,40,0.4);"
             @click.self="modal = false">

            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 style="background:#EEEDF7; border-radius:18px; width:100%; max-width:420px; overflow:hidden; position:relative;">

                {{-- X cerrar --}}
                <button @click="modal = false"
                        style="position:absolute; top:14px; right:14px; width:28px; height:28px; border-radius:8px; background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                    <svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div style="padding:20px 18px 18px;">

                    {{-- ── Sección Datos Personales ── --}}
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                        <span style="font-size:12px; font-weight:700; color:#534AB7; white-space:nowrap;">Datos personales</span>
                        <div style="flex:1; height:1px; background:#CECBF6;"></div>
                    </div>

                    @php
                    $iconPersona  = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z';
                    $iconCI       = 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0';
                    $iconTel      = 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z';
                    $iconNit      = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                    $iconMail     = 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z';
                    $iconPin      = 'M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z';
                    $iconMapa     = 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7';
                    $iconEdif     = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4';
                    $iconCasa     = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
                    @endphp

                    @php
                    $fieldStyle = 'background:#fff; border-radius:10px; padding:8px 10px; display:flex; align-items:center; gap:8px;';
                    $iconBox    = 'width:32px; height:32px; border-radius:8px; background:#EEEDFE; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                    $valColor   = 'font-size:11px; font-weight:700; color:#1E1B5E;';
                    $lblColor   = 'font-size:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#AFA9EC; margin-bottom:1px;';
                    @endphp

                    {{-- Fila 1: Nombre | CI --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBox }}">
                                <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPersona }}"/></svg>
                            </div>
                            <div style="min-width:0;">
                                <p style="{{ $lblColor }}">Nombre Completo</p>
                                <p style="{{ $valColor }} word-break:break-word;">{{ $p->cliente->nombre_completo }}</p>
                            </div>
                        </div>
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBox }}">
                                <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconCI }}"/></svg>
                            </div>
                            <div>
                                <p style="{{ $lblColor }}">CI</p>
                                <p style="{{ $valColor }} font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Fila 2: Teléfono | NIT --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBox }}">
                                <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconTel }}"/></svg>
                            </div>
                            <div>
                                <p style="{{ $lblColor }}">Teléfono</p>
                                <p style="{{ $valColor }}">{{ $p->cliente->telefono ?: '—' }}</p>
                            </div>
                        </div>
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBox }}">
                                <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconNit }}"/></svg>
                            </div>
                            <div>
                                <p style="{{ $lblColor }}">NIT</p>
                                <p style="{{ $valColor }}">{{ $p->cliente->nit ?: '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Correo full --}}
                    <div style="{{ $fieldStyle }} margin-bottom:16px;">
                        <div style="{{ $iconBox }}">
                            <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconMail }}"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <p style="{{ $lblColor }}">Correo</p>
                            <p style="{{ $valColor }} word-break:break-all;">{{ $p->cliente->correo ?: '—' }}</p>
                        </div>
                    </div>

                    {{-- ── Sección Datos de Dirección ── --}}
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                        <span style="font-size:12px; font-weight:700; color:#534AB7; white-space:nowrap;">Datos de dirección</span>
                        <div style="flex:1; height:1px; background:#CECBF6;"></div>
                    </div>

                    {{-- Ciudad | Provincia | Municipio --}}
                    @php $iconBoxSm = 'width:24px; height:24px; border-radius:6px; background:#EEEDFE; display:flex; align-items:center; justify-content:center; flex-shrink:0;'; @endphp
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBoxSm }}">
                                <svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPin }}"/></svg>
                            </div>
                            <div style="min-width:0;">
                                <p style="{{ $lblColor }}">Ciudad</p>
                                <p style="{{ $valColor }} word-break:break-word;">{{ strtoupper($p->cliente->ciudad ?: '—') }}</p>
                            </div>
                        </div>
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBoxSm }}">
                                <svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconMapa }}"/></svg>
                            </div>
                            <div style="min-width:0;">
                                <p style="{{ $lblColor }}">Provincia</p>
                                <p style="{{ $valColor }} word-break:break-word;">{{ strtoupper($p->cliente->provincia ?: '—') }}</p>
                            </div>
                        </div>
                        <div style="{{ $fieldStyle }}">
                            <div style="{{ $iconBoxSm }}">
                                <svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconEdif }}"/></svg>
                            </div>
                            <div style="min-width:0;">
                                <p style="{{ $lblColor }}">Municipio</p>
                                <p style="{{ $valColor }} word-break:break-word;">{{ strtoupper($p->cliente->municipio ?: '—') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dirección full --}}
                    <div style="{{ $fieldStyle }}">
                        <div style="{{ $iconBox }}">
                            <svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconCasa }}"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <p style="{{ $lblColor }}">Dirección</p>
                            <p style="{{ $valColor }}">{{ $p->cliente->direccion ?: '—' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @if ($p->notas)
    @if ($p->estado === 'rechazado')
    <div style="display:flex; align-items:center; gap:8px; margin:12px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#B91C1C; white-space:nowrap;">Motivo de Rechazo</span>
        <div style="flex:1; height:1px; background:#FCA5A5;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-3" style="border:0.5px solid #FCA5A5; border-radius:10px; box-shadow:none; padding:10px 12px;">
        <span style="font-size:13px; font-weight:600; color:#B91C1C; display:block;">{{ $p->notas }}</span>
    </div>
    @else
    <div class="bg-white overflow-hidden mb-3" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none; padding:10px 12px;">
        <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">Notas</span>
        <span style="font-size:13px; font-weight:600; color:#3C3489; display:block;">{{ $p->notas }}</span>
    </div>
    @endif
    @endif

    @php
        $docs = [
            'Anverso CI'   => $p->doc_anverso_ci,
            'Reverso CI'   => $p->doc_reverso_ci,
            'Anverso Doc.' => $p->doc_anverso_doc,
            'Reverso Doc.' => $p->doc_reverso_doc,
            'Aviso de Luz' => $p->doc_aviso_luz,
        ];
        $docsExisten = collect($docs)->filter()->isNotEmpty();
    @endphp

    @if (true)
    <div style="display:flex; align-items:center; gap:8px; margin:16px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#534AB7;">Documentos</span>
        <div style="flex:1; height:1px; background:#9C96E8;"></div>
    </div>
    <div style="background:white; border-radius:10px; border:0.5px solid #CECBF6; padding:12px; box-shadow:none; margin-bottom:20px;">
        @php
            $docIconos = [
                'Anverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
                'Reverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
                'Anverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'Reverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'Aviso de Luz' => 'M13 10V3L4 14h7v7l9-11h-7z',
            ];
        @endphp
        <div class="doc-grid-view" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
        <style>@media(min-width:480px){.doc-grid-view{grid-template-columns:repeat(5,1fr)!important;}}</style>
        @foreach ($docs as $label => $path)
        @if ($path)
        @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
        <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
            <div style="border:1.5px solid #0F6E56; background:#F0FDF4; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                <div style="width:28px; height:28px; border-radius:6px; background:#DCFCE7; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $docIconos[$label] ?? 'M9 12h6m-6 4h6' }}"/>
                    </svg>
                </div>
                <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:#0F6E56;">{{ $label }}</span>
                <span style="display:inline-flex; align-items:center; gap:2px; font-size:8px; color:#0F6E56;">
                    <svg style="width:9px;height:9px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar
                </span>
            </div>
        </a>
        @else
        <div style="border:1.5px dashed #CECBF6; background:#FAFAFE; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
            <div style="width:28px; height:28px; border-radius:6px; background:#EEEDFE; display:flex; align-items:center; justify-content:center;">
                <svg style="width:16px;height:16px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $docIconos[$label] ?? 'M9 12h6m-6 4h6' }}"/>
                </svg>
            </div>
            <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:#534AB7;">{{ $label }}</span>
            <span style="font-size:8px; color:#AFA9EC;">Sin archivo</span>
        </div>
        @endif
        @endforeach
        </div>
    </div>
    @endif

    <div style="display:flex; align-items:center; gap:8px; margin:16px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#534AB7;">Productos del Pedido</span>
        <div style="flex:1; height:1px; background:#9C96E8;"></div>
        <span style="font-size:10px; color:#AFA9EC;">{{ $p->items->count() }} {{ $p->items->count() === 1 ? 'producto' : 'productos' }}</span>
    </div>

    {{-- Lista productos --}}
    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none;">
        @foreach ($p->items as $item)
        <div class="flex items-center gap-2.5 px-3 py-2.5"
             style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">

            {{-- Imagen --}}
            <div class="flex-shrink-0 overflow-hidden" style="width:44px;height:44px;border-radius:8px;border:0.5px solid #e5e7eb;background:#fff;">
                @if ($item->product?->foto_url)
                <img src="{{ $item->product->foto_url }}" alt="{{ $item->product->name }}"
                     style="width:100%;height:100%;object-fit:contain;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                @endif
                <div class="w-full h-full flex items-center justify-center" style="{{ $item->product?->foto_url ? 'display:none;' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#CECBF6;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                @if ($item->product?->code)
                <span class="inline-block px-1.5 py-0.5 text-[9px] font-bold rounded uppercase tracking-wide mb-0.5"
                      style="background:#EEEDFE; color:#534AB7;">{{ $item->product->code }}</span>
                @endif
                <p class="text-xs font-medium text-gray-800 truncate leading-tight">{{ $item->product?->name }}</p>
                <p class="text-[10px] text-gray-400 leading-tight">
                    {{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}
                </p>
            </div>

            {{-- Subtotal + puntos --}}
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold" style="color:#7c3aed;">Bs {{ number_format($item->subtotal, 2) }}</p>
                @if ($item->puntos > 0)
                <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full"
                      style="background:#E1F5EE; color:#0F6E56;">+{{ $item->puntos * $item->cantidad }} pts</span>
                @endif
            </div>
        </div>
        @endforeach

        {{-- Total --}}
        @php $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad); @endphp
        <div class="flex justify-end items-center gap-2 px-3 py-2.5" style="border-top:0.5px solid #e5e7eb;">
            <p class="font-bold" style="font-size:16px; color:#3C3489;">Total: Bs {{ number_format($p->total, 2) }}</p>
            @if ($totalPuntos > 0)
            <span class="font-semibold px-2 py-0.5 rounded-full" style="font-size:12px; background:#E1F5EE; color:#0F6E56;">+{{ number_format($totalPuntos) }} pts</span>
            @endif
        </div>
    </div>

    {{-- Plan de Pagos --}}
    @if ($plan)
    <div style="display:flex; align-items:center; gap:8px; margin:16px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#534AB7;">Plan de Pagos</span>
        <div style="flex:1; height:1px; background:#9C96E8;"></div>
        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full" style="background:#EEEDFE; color:#534AB7;">
            {{ $plan->cantidad_cuotas }} {{ $plan->cantidad_cuotas === 1 ? 'cuota' : 'cuotas' }}
        </span>
    </div>

    {{-- Lista cuotas --}}
    <p class="text-[11px] font-semibold uppercase tracking-wide mb-2" style="color:#9ca3af;">Detalle de Cuotas</p>
    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none;">

        <div class="grid px-3 py-2" style="background:#F8F7FF; grid-template-columns: 1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr;">
            <p style="font-size:10px; font-weight:600; color:#6b7280;">Cuota</p>
            <p style="font-size:10px; font-weight:600; color:#6b7280;">Vencimiento</p>
            @if ($aprobado)
            <p style="font-size:10px; font-weight:600; color:#6b7280;">Estado</p>
            @endif
            <p style="font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto</p>
        </div>

        @foreach ($plan->cuotas as $cuota)
        <div class="grid items-center px-3 py-2.5"
             style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }} grid-template-columns: 1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr;">

            <div class="flex items-center gap-1.5">
                @if ($cuota->numero === 0)
                <span class="flex-shrink-0 flex items-center justify-center font-bold text-[9px] leading-none"
                      style="width:24px;height:24px;border-radius:50%;background:#E1F5EE;color:#0F6E56;">0</span>
                <span style="font-size:11px; font-weight:500; color:#0F6E56;">Inicial</span>
                @else
                <span class="flex-shrink-0 flex items-center justify-center font-bold text-[10px] leading-none"
                      style="width:24px;height:24px;border-radius:50%;background:#EEEDFE;color:#534AB7;">{{ $cuota->numero }}</span>
                <span style="font-size:11px; font-weight:500; color:#374151;">Cuota {{ $cuota->numero }}</span>
                @endif
            </div>

            <p style="font-size:11px; color:#6b7280;">
                {{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}
            </p>

            @if ($aprobado)
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $cuota->estado_badge['class'] }}">
                {{ $cuota->estado_badge['label'] }}
            </span>
            @endif

            <p style="font-size:13px; font-weight:700; color:#7c3aed; text-align:right;">Bs {{ number_format($cuota->monto, 2) }}</p>
        </div>
        @endforeach
    </div>
    @endif

    @if ($p->entrega_direccion || $p->entrega_ciudad)
    <div style="display:flex; align-items:center; gap:8px; margin:16px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#534AB7;">Dirección de Entrega</span>
        <div style="flex:1; height:1px; background:#9C96E8;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none; padding:10px 12px;">
        @php $partes = array_filter([$p->entrega_ciudad, $p->entrega_provincia, $p->entrega_municipio, $p->entrega_direccion]); @endphp
        <span style="font-size:13px; font-weight:600; color:#3C3489; display:block;">{{ implode(', ', $partes) }}</span>
        @if ($p->entrega_referencia)
        <span style="font-size:11px; color:#AFA9EC;">Ref: {{ $p->entrega_referencia }}</span>
        @endif
    </div>
    @endif

</div>

{{-- ══════════════════════ LIST ══════════════════════ --}}
@else

@php
$filtros = [
    ''           => ['label' => 'Todos',       'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
    'en_espera'  => ['label' => 'En espera',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'revision'    => ['label' => 'En revisión', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    'aprobado'   => ['label' => 'Aprobado',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'rechazado'  => ['label' => 'Rechazado',   'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
$estilosActivos = [
    ''           => 'background:#EEEDFE; border-color:#7c3aed; color:#534AB7;',
    'en_espera'  => 'background:#FEF3C7; border-color:#D97706; color:#854F0B;',
    'revision'    => 'background:#F0F9FF; border-color:#0284C7; color:#0369A1;',
    'aprobado'   => 'background:#F0FDF4; border-color:#16A34A; color:#15803D;',
    'rechazado'  => 'background:#FEF2F2; border-color:#DC2626; color:#B91C1C;',
];
@endphp

{{-- Toolbar --}}
<div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-bottom:16px;">

    {{-- Buscador --}}
    <div style="position:relative; flex-shrink:0; width:180px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#AFA9EC" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.debounce.300ms="search"
               type="text"
               placeholder="Buscar cliente..."
               style="width:100%; padding:7px 10px 7px 30px;
                      border:0.5px solid #CECBF6;
                      border-radius:8px;
                      background:#FAFAFE;
                      font-size:12px;
                      outline:none;" />
    </div>

    {{-- Filtros estado --}}
    @foreach($filtros as $valor => $filtro)
    <button wire:click="$set('filtroEstado', '{{ $valor }}')"
            style="{{ $filtroEstado === $valor ? $estilosActivos[$valor] : 'background:transparent; border-color:#CECBF6; color:#AFA9EC;' }}
                   border:0.5px solid; border-radius:6px; padding:6px 10px;
                   font-size:11px; font-weight:500; cursor:pointer; transition:all .15s; white-space:nowrap;
                   display:inline-flex; align-items:center; gap:5px;
                   box-shadow:0 2px 6px rgba(0,0,0,0.10), 0 1px 2px rgba(0,0,0,0.06);">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="{{ $filtro['icon'] }}"/>
        </svg>
        {{ $filtro['label'] }}
    </button>
    @endforeach

    {{-- Nuevo Plan --}}
    <a href="{{ route('vendedor.oferta') }}"
       style="display:inline-flex; align-items:center; gap:6px;
              background:transparent; color:#633806;
              border:1.5px solid #633806; border-radius:8px;
              padding:7px 14px; font-size:12px; font-weight:500;
              text-decoration:none; white-space:nowrap; margin-left:auto;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#633806" stroke-width="2" stroke-linecap="round">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <line x1="9" y1="12" x2="15" y2="12"/>
            <line x1="9" y1="16" x2="13" y2="16"/>
        </svg>
        NUEVO PLAN
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="mp-wrap" style="-webkit-overflow-scrolling:touch;">
    <table class="mp-table" style="width:100%; min-width:600px; font-size:13px;">
        <thead style="{{ $theadStyle }}" class="tracking-wide">
            <tr>
                <th class="sticky-combined" style="border:0.5px solid #e5e7eb; font-weight:700; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; display:flex; align-items:center; justify-content:center;">Pedido</div>
                        <div style="flex:1; padding:8px 10px; text-align:center; display:flex; align-items:center; justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th style="width:110px; padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb;">Estado</th>
                <th style="width:110px; padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb;">Fecha Solicitud</th>
                <th style="width:115px; padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb;">Fecha Aprobación</th>
                <th style="width:90px; padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb;">Total Bs.</th>
                <th style="width:60px; padding:8px 10px; text-align:center; font-weight:700; border:0.5px solid #e5e7eb;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="p-{{ $p->id }}">
                <td class="sticky-combined" style="border:0.5px solid #e5e7eb; height:1px;">
                    <div style="display:flex; align-items:stretch; height:100%;">
                        <div style="width:110px; padding:8px 10px; text-align:center; border-right:1.5px solid #d1d5db; flex-shrink:0; font-family:monospace; font-size:11px; color:#534AB7; display:flex; align-items:center; justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1; padding:8px 10px; text-align:center;">
                            <p style="font-weight:600; font-size:13px; color:#534AB7;">{{ $p->cliente->nombre_completo }}</p>
                            @if ($p->cliente->ci)<p style="font-size:11px; color:#AFA9EC;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <span class="inline-flex items-center text-xs font-semibold" style="{{ $p->estado_badge['style'] ?? '' }} padding:4px 10px; border-radius:6px;">
                        {{ $p->estado_badge['label'] }}
                    </span>
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">
                    {{ $p->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-size:12px; color:#534AB7;">
                    @if ($p->estado === 'aprobado' && $p->updated_at)
                        {{ $p->updated_at->format('d/m/Y') }}
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb; font-weight:700; color:#534AB7;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#d1d5db;">—</span>
                    @endif
                </td>
                <td style="padding:8px 10px; text-align:center; border:0.5px solid #e5e7eb;">
                    <button wire:click="ver({{ $p->id }})"
                            class="p-1.5 rounded-lg hover:bg-melocoton-50 text-melocoton-500 transition-colors"
                            title="Ver detalle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-14 text-center">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-500">Sin pedidos todavía</p>
                            <p class="text-sm mt-0.5">Generá tu primer pedido desde Oferta / Carrito</p>
                        </div>
                        <a href="{{ route('vendedor.oferta') }}"
                           class="mt-1 px-4 py-2 bg-melocoton-500 hover:bg-melocoton-600 text-white text-sm font-semibold rounded-xl transition-colors">
                            Ir a Oferta / Carrito
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if ($pedidos->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $pedidos->links() }}</div>
    @endif
</div>
@endif

</div>{{-- /padding --}}
</div>
