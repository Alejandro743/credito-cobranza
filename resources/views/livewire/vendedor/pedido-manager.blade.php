<div>


{{-- ══════════════════════ DETAIL ══════════════════════ --}}
@if ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; $aprobado = $p->estado === 'aprobado'; @endphp

<style>@media(min-width:680px){.pm-grid{display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start;}}</style>
<div style="max-width:1040px; margin:0 auto; padding:0 0 40px;">

    @php
        $estadoConfig = match($p->estado) {
            'en_espera' => ['color' => '#854F0B', 'bg' => '#FEF3C7', 'border' => '#FCD34D', 'dot' => '#D97706'],
            'revision'   => ['color' => '#0369A1', 'bg' => '#F0F9FF', 'border' => '#7DD3FC', 'dot' => '#0284C7'],
            'aprobado'  => ['color' => '#15803D', 'bg' => '#F0FDF4', 'border' => '#86EFAC', 'dot' => '#16A34A'],
            'rechazado' => ['color' => '#B91C1C', 'bg' => '#FEF2F2', 'border' => '#CBCBCB', 'dot' => '#DC2626'],
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

<div class="pm-grid" style="margin-top:20px;">
<div>
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
        <div style="flex:1; height:1px; background:#CBCBCB;"></div>
    </div>
    <div class="bg-white overflow-hidden mb-3" style="border:0.5px solid #CBCBCB; border-radius:10px; box-shadow:none; padding:10px 12px;">
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

</div>
<div>
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
    <p class="text-[11px] font-semibold uppercase tracking-wide mb-2" style="color:#9ca3af;">
        Detalle de Cuotas
        @if ($plan->version > 1)
        <span style="font-size:9px; font-weight:700; background:#FEF3C7; color:#854F0B; border-radius:4px; padding:2px 6px; margin-left:4px; text-transform:none; letter-spacing:0;">Reprogramado v{{ $plan->version }}</span>
        @endif
    </p>
    <div class="bg-white overflow-hidden mb-5" style="border:0.5px solid #CECBF6; border-radius:10px; box-shadow:none;">

        <div class="grid px-3 py-2" style="background:#F8F7FF; grid-template-columns: 1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr;">
            <p style="font-size:10px; font-weight:600; color:#6b7280;">Cuota</p>
            <p style="font-size:10px; font-weight:600; color:#6b7280;">Vencimiento</p>
            @if ($aprobado)
            <p style="font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</p>
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
            <div style="display:flex; align-items:center; justify-content:center;">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"
                      style="background:{{ $cuota->estadoFinancieroBadge['bg'] }}; color:{{ $cuota->estadoFinancieroBadge['cl'] }};">
                    {{ $cuota->estadoFinancieroBadge['lb'] }}
                </span>
            </div>
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
</div>

</div>

{{-- ══════════════════════ LIST ══════════════════════ --}}
@else

@php
$filtros = [
    ''           => ['label' => 'Todos',       'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
    'en_espera'  => ['label' => 'En espera',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'revision'   => ['label' => 'En revisión', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    'aprobado'   => ['label' => 'Aprobado',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'rechazado'  => ['label' => 'Rechazado',   'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
$estilosActivos = [
    ''           => 'background:#E8F0F7; border-color:#6D8196; color:#6D8196;',
    'en_espera'  => 'background:#FFF9E3; border-color:#FCD34D; color:#B45309;',
    'revision'   => 'background:#EFF6FF; border-color:#93C5FD; color:#1D4ED8;',
    'aprobado'   => 'background:#F0FDF4; border-color:#6EE7B7; color:#065F46;',
    'rechazado'  => 'background:#FEF2F2; border-color:#FCA5A5; color:#DC2626;',
];
@endphp

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>

    <div class="flex flex-wrap gap-1.5">
        @foreach($filtros as $valor => $filtro)
        <button wire:click="$set('filtroEstado', '{{ $valor }}')"
                style="{{ $filtroEstado === $valor ? $estilosActivos[$valor] : 'background:#fff; border-color:#E5E7EB; color:#6B7280;' }}
                       border:1px solid; border-radius:8px; padding:5px 10px; height:36px;
                       font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap;
                       display:inline-flex; align-items:center; gap:5px; box-sizing:border-box;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="{{ $filtro['icon'] }}"/>
            </svg>
            {{ $filtro['label'] }}
        </button>
        @endforeach
    </div>

    <a href="{{ route('vendedor.oferta') }}"
       class="w-full sm:w-auto"
       style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; text-decoration:none; box-sizing:border-box; flex-shrink:0;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <line x1="9" y1="12" x2="15" y2="12"/>
            <line x1="9" y1="16" x2="13" y2="16"/>
        </svg>
        Nueva Solicitud
    </a>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    <div wire:key="card-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#FFF0E8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#EA580C;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; {{ $p->estado_badge['style'] ?? '' }}">
                {{ $p->estado_badge['label'] }}
            </span>
        </div>
        <div style="padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha solicitud</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Total Bs.</span>
                <span style="font-size:13px; font-weight:700; color:#EA580C;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="ver({{ $p->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos todavía</p>
        <p style="font-size:12px; color:#9CA3AF; margin-top:4px;">Generá tu primer pedido desde Oferta / Carrito</p>
        <a href="{{ route('vendedor.oferta') }}"
           style="display:inline-block; margin-top:12px; padding:8px 20px; background:#EA580C; color:#fff; font-size:13px; font-weight:600; border-radius:10px; text-decoration:none;">
            Ir a Oferta / Carrito
        </a>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Mis solicitudes</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:860px; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cod. Pedido</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Nombre</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Fecha Solicitud</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Estado</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Fecha Contestación</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Total Bs.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="p-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 14px; font-family:monospace; font-size:12px; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ $p->cliente->nombre_completo }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px;">
                    <span style="{{ $p->estado_badge['style'] ?? '' }} padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;">
                        {{ $p->estado_badge['label'] }}
                    </span>
                </td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">
                    @if (in_array($p->estado, ['aprobado','rechazado']) && $p->updated_at)
                        {{ $p->updated_at->format('d/m/Y') }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:700; color:#111827; white-space:nowrap;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="ver({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'"
                            title="Ver detalle">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos todavía</p>
                    <p style="font-size:12px; color:#9CA3AF;">Generá tu primer pedido desde Oferta / Carrito</p>
                    <a href="{{ route('vendedor.oferta') }}"
                       style="display:inline-block; margin-top:12px; padding:8px 20px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; border-radius:10px; text-decoration:none;">
                        Ir a Oferta / Carrito
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if ($pedidos->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $pedidos->links() }}</div>
    @endif
</div>

@endif
</div>
