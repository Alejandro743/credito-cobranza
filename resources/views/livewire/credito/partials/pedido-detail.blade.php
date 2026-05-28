{{--
    Partial: detalle de pedido para las vistas del analista de crédito.
    Variables esperadas: $p (Pedido), $plan ($p->planPago), $aprobado (bool)
--}}
@php
    $estadoConfig = match($p->estado) {
        'en_espera'  => ['color' => '#B45309', 'bg' => '#FFF9E3', 'border' => '#FCD34D'],
        'revision'   => ['color' => '#6D8196', 'bg' => '#E8F0F7', 'border' => '#CBCBCB'],
        'aprobado'   => ['color' => '#15803D', 'bg' => '#F0FDF4', 'border' => '#86EFAC'],
        'rechazado'  => ['color' => '#B91C1C', 'bg' => '#FEF2F2', 'border' => '#FCA5A5'],
        'cerrado'    => ['color' => '#6D8196', 'bg' => '#F4F4F4', 'border' => '#CBCBCB'],
        default      => ['color' => '#6D8196', 'bg' => '#F7F7F0', 'border' => '#CBCBCB'],
    };
@endphp

{{-- Cabecera del pedido --}}
<div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; padding:16px 18px; margin-bottom:16px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
        <div>
            <p style="font-size:11px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.4px; margin:0 0 2px;">
                Solicitud de Crédito
            </p>
            <p style="font-size:20px; font-weight:800; color:#4A4A4A; margin:0; font-family:monospace; letter-spacing:1px;">
                {{ $p->numero }}
            </p>
        </div>
        <span class="ds-badge" style="background:{{ $estadoConfig['bg'] }}; color:{{ $estadoConfig['color'] }}; border:1px solid {{ $estadoConfig['border'] }};">
            {{ ucfirst(str_replace('_', ' ', $p->estado)) }}
        </span>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div>
            <p style="font-size:10px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.3px; margin:0 0 2px;">Cliente</p>
            <p style="font-size:13px; font-weight:600; color:#4A4A4A; margin:0;">{{ $p->cliente->nombre_completo }}</p>
            @if($p->cliente->ci)
            <p style="font-size:11px; color:#6D8196; margin:2px 0 0;">CI: {{ $p->cliente->ci }}</p>
            @endif
        </div>
        <div>
            <p style="font-size:10px; font-weight:700; color:#CBCBCB; text-transform:uppercase; letter-spacing:.3px; margin:0 0 2px;">Vendedor</p>
            <p style="font-size:13px; font-weight:600; color:#4A4A4A; margin:0;">{{ $p->vendedor->user->name ?? '—' }}</p>
            <p style="font-size:11px; color:#CBCBCB; margin:2px 0 0;">{{ $p->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
</div>

{{-- Plan de Pagos --}}
@if ($plan)
<div style="margin-bottom:16px;">
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>
        </svg>
        <span style="font-size:12px; font-weight:700; color:#3C3489; white-space:nowrap;">Plan de Pagos</span>
        <div style="flex:1; height:1px; background:#CECBF6;"></div>
        @if ($plan->version > 1)
        <span style="font-size:9px; font-weight:700; background:#FEF3C7; color:#854F0B; border-radius:4px; padding:2px 6px;">Reprogramado v{{ $plan->version }}</span>
        @endif
        <span style="font-size:11px; font-weight:600; color:#9B93E0;">{{ $plan->cantidad_cuotas }} {{ $plan->cantidad_cuotas === 1 ? 'cuota' : 'cuotas' }}</span>
    </div>

    <div style="background:#fff; border-radius:12px; overflow:hidden; box-shadow:2px 6px 20px rgba(60,52,137,0.10); margin-bottom:4px;">
        <div style="display:grid; grid-template-columns:1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr; padding:8px 12px; background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Cuota</span>
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Vencimiento</span>
            @if ($aprobado)
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em; text-align:center;">Estado</span>
            @endif
            <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Monto</span>
        </div>

        @foreach ($plan->cuotas as $cuota)
        <div style="display:grid; grid-template-columns:1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr; align-items:center; padding:10px 12px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}">
            <div style="display:flex; align-items:center; gap:8px;">
                @if ($cuota->numero === 0)
                <span style="width:26px; height:26px; border-radius:50%; background:#E1F5EE; color:#0F6E56; font-size:9px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;">0</span>
                <span style="font-size:11px; font-weight:700; color:#0F6E56;">Inicial</span>
                @else
                <span style="width:26px; height:26px; border-radius:50%; background:#EEEDFE; color:#534AB7; font-size:10px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0;">{{ $cuota->numero }}</span>
                <span style="font-size:11px; font-weight:600; color:#3C3489;">Cuota {{ $cuota->numero }}</span>
                @endif
            </div>
            <span style="font-size:11px; color:#6B7280;">{{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}</span>
            @if ($aprobado)
            @php $cbadge = $cuota->estadoFinancieroBadge; @endphp
            <div style="display:flex; align-items:center; justify-content:center;">
                <span style="background:{{ $cbadge['bg'] }}; color:{{ $cbadge['cl'] }}; font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $cbadge['lb'] }}</span>
            </div>
            @endif
            <span style="font-size:14px; font-weight:800; color:#7c3aed; text-align:right;">Bs {{ number_format($cuota->monto, 2) }}</span>
        </div>
        @endforeach

        <div style="display:flex; justify-content:flex-end; padding:8px 12px; background:#F8F7FF; border-top:1px solid #EDE9FE;">
            <span style="font-size:15px; font-weight:800; color:#3C3489;">Total: Bs {{ number_format($plan->cuotas->sum('monto'), 2) }}</span>
        </div>
    </div>
</div>
@endif

{{-- Datos Cliente (modal) --}}
<div x-data="{ modal: false }">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
        <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0;">Datos del Cliente</p>
        <button @click="modal = true" class="ds-btn ds-btn-ghost ds-btn-sm">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Ver ficha
        </button>
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
         style="background:rgba(74,74,74,.5);"
         @click.self="modal = false">

        <div x-show="modal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             style="background:#FFFFE3; border:1px solid #CBCBCB; border-radius:10px; width:100%; max-width:440px; overflow:hidden; position:relative; max-height:90vh; overflow-y:auto;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #CBCBCB; background:#fff;">
                <p style="font-size:13px; font-weight:700; color:#4A4A4A; margin:0;">Ficha del cliente</p>
                <button @click="modal = false"
                        style="width:26px; height:26px; border-radius:6px; background:#F4F4F4; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg width="12" height="12" fill="none" stroke="#4A4A4A" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div style="padding:16px;">
                @php
                $fieldStyle = 'background:#fff; border:1px solid #CBCBCB; border-radius:6px; padding:8px 10px; display:flex; align-items:center; gap:8px;';
                $iconBox    = 'width:28px; height:28px; border-radius:5px; background:#E8F0F7; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                $valColor   = 'font-size:12px; font-weight:600; color:#4A4A4A;';
                $lblColor   = 'font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.3px; color:#CBCBCB; margin-bottom:1px;';
                @endphp

                <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0 0 8px;">Datos personales</p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:6px;">
                    <div style="{{ $fieldStyle }}">
                        <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                        <div style="min-width:0;">
                            <p style="{{ $lblColor }}">Nombre</p>
                            <p style="{{ $valColor }}">{{ $p->cliente->nombre_completo }}</p>
                        </div>
                    </div>
                    <div style="{{ $fieldStyle }}">
                        <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div>
                        <div>
                            <p style="{{ $lblColor }}">CI</p>
                            <p style="{{ $valColor }}; font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-bottom:6px;">
                    <div style="{{ $fieldStyle }}">
                        <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                        <div>
                            <p style="{{ $lblColor }}">Teléfono</p>
                            <p style="{{ $valColor }}">{{ $p->cliente->telefono ?: '—' }}</p>
                        </div>
                    </div>
                    <div style="{{ $fieldStyle }}">
                        <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                        <div>
                            <p style="{{ $lblColor }}">NIT</p>
                            <p style="{{ $valColor }}">{{ $p->cliente->nit ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                <div style="{{ $fieldStyle }} margin-bottom:12px;">
                    <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <div style="min-width:0;">
                        <p style="{{ $lblColor }}">Correo</p>
                        <p style="{{ $valColor }}; word-break:break-all;">{{ $p->cliente->correo ?: '—' }}</p>
                    </div>
                </div>

                <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0 0 8px;">Dirección</p>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px; margin-bottom:6px;">
                    <div style="{{ $fieldStyle }}">
                        <div style="min-width:0; flex:1;">
                            <p style="{{ $lblColor }}">Ciudad</p>
                            <p style="{{ $valColor }}">{{ strtoupper($p->cliente->ciudad ?: '—') }}</p>
                        </div>
                    </div>
                    <div style="{{ $fieldStyle }}">
                        <div style="min-width:0; flex:1;">
                            <p style="{{ $lblColor }}">Provincia</p>
                            <p style="{{ $valColor }}">{{ strtoupper($p->cliente->provincia ?: '—') }}</p>
                        </div>
                    </div>
                    <div style="{{ $fieldStyle }}">
                        <div style="min-width:0; flex:1;">
                            <p style="{{ $lblColor }}">Municipio</p>
                            <p style="{{ $valColor }}">{{ strtoupper($p->cliente->municipio ?: '—') }}</p>
                        </div>
                    </div>
                </div>

                <div style="{{ $fieldStyle }}">
                    <div style="{{ $iconBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                    <div style="min-width:0;">
                        <p style="{{ $lblColor }}">Dirección</p>
                        <p style="{{ $valColor }}">{{ $p->cliente->direccion ?: '—' }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Motivo de rechazo --}}
@if ($p->notas && $p->estado === 'rechazado')
<div class="ds-confirm-panel danger" style="margin-bottom:12px; margin-top:0;">
    <p class="ds-confirm-panel-title">Motivo de Rechazo</p>
    <p style="font-size:13px; color:#DC2626;">{{ $p->notas }}</p>
</div>
@elseif ($p->notas)
<div class="ds-confirm-panel neutral" style="margin-bottom:12px; margin-top:0;">
    <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.3px; margin:0 0 4px;">Notas</p>
    <p style="font-size:13px; color:#4A4A4A;">{{ $p->notas }}</p>
</div>
@endif

{{-- Documentos --}}
@php
$docs = [
    'Anverso CI'   => $p->doc_anverso_ci,
    'Reverso CI'   => $p->doc_reverso_ci,
    'Anverso Doc.' => $p->doc_anverso_doc,
    'Reverso Doc.' => $p->doc_reverso_doc,
    'Aviso de Luz' => $p->doc_aviso_luz,
];
$docCampos = [
    'Anverso CI'   => 'doc_anverso_ci',
    'Reverso CI'   => 'doc_reverso_ci',
    'Anverso Doc.' => 'doc_anverso_doc',
    'Reverso Doc.' => 'doc_reverso_doc',
    'Aviso de Luz' => 'doc_aviso_luz',
];
$docProps = [
    'doc_anverso_ci'  => 'docAnversoCi',
    'doc_reverso_ci'  => 'docReversoCi',
    'doc_anverso_doc' => 'docAnversoDoc',
    'doc_reverso_doc' => 'docReversoDoc',
    'doc_aviso_luz'   => 'docAvisoLuz',
];
$editable = $editable ?? false;
@endphp

<div style="margin-top:16px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
        <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0;">Documentos</p>
        @if ($editable)
        <span style="font-size:10px; font-weight:600; color:#6D8196; background:#E8F0F7; border:1px solid #CBCBCB; border-radius:3px; padding:2px 8px;">Editable</span>
        @endif
    </div>

    <div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; padding:12px;">
        <div class="doc-grid-credito" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
        <style>@media(min-width:480px){.doc-grid-credito{grid-template-columns:repeat(5,1fr)!important;}}</style>
        @foreach ($docs as $label => $path)
        @php $campo = $docCampos[$label]; $prop = $docProps[$campo]; @endphp
        <div style="display:flex; flex-direction:column; gap:4px;">
            @if ($path)
            @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
            <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
                <div style="border:1.5px solid #10B981; background:#F0FDF4; border-radius:6px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:3px; height:76px;">
                    <div style="width:26px; height:26px; border-radius:5px; background:#D1FAE5; display:flex; align-items:center; justify-content:center;">
                        <svg width="13" height="13" fill="none" stroke="#10B981" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span style="font-size:9px; font-weight:600; color:#15803D; line-height:1.2;">{{ $label }}</span>
                    <span style="font-size:8px; color:#15803D;">↓ Ver</span>
                </div>
            </a>
            @else
            <div style="border:1.5px dashed #CBCBCB; background:#F7F7F0; border-radius:6px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:3px; height:76px;">
                <div style="width:26px; height:26px; border-radius:5px; background:#E8F0F7; display:flex; align-items:center; justify-content:center;">
                    <svg width="13" height="13" fill="none" stroke="#CBCBCB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span style="font-size:9px; font-weight:500; color:#6D8196; line-height:1.2;">{{ $label }}</span>
                <span style="font-size:8px; color:#CBCBCB;">Sin archivo</span>
            </div>
            @endif

            @if ($editable)
            <div x-data="{ subido: false }">
                <input type="file"
                       wire:model="{{ $prop }}"
                       x-ref="fi_{{ $campo }}"
                       accept="image/*,.pdf"
                       style="display:none"
                       x-on:livewire-upload-finish="$wire.subirDocumento('{{ $campo }}'); subido = true;">
                <button @click="$refs['fi_{{ $campo }}'].click()"
                        :style="{ background: subido ? '#D1FAE5' : '#FFFFE3' }"
                        style="width:100%; color:#6D8196; border:1.5px solid #6D8196; font-size:10px; font-weight:600; border-radius:3px; padding:4px 6px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px; font-family:'Inter',sans-serif;">
                    <svg x-show="!subido" style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <svg x-show="subido" x-cloak style="width:11px;height:11px;" fill="none" stroke="#15803D" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="subido ? 'Listo' : '{{ $path ? 'Reemplazar' : 'Subir' }}'"></span>
                </button>
                @error($prop)<p style="font-size:9px; color:#DC2626; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            @endif
        </div>
        @endforeach
        </div>
    </div>
</div>

{{-- Productos --}}
<div style="margin-top:16px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
        <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0;">Productos del pedido</p>
        <span style="font-size:10px; color:#CBCBCB;">{{ $p->items->count() }} {{ $p->items->count() === 1 ? 'producto' : 'productos' }}</span>
    </div>

    <div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; overflow:hidden;">
        @foreach ($p->items as $item)
        <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; {{ !$loop->last ? 'border-bottom:1px solid #F0F0E8;' : '' }}">
            <div style="width:42px; height:42px; border-radius:6px; border:1px solid #CBCBCB; background:#F7F7F0; overflow:hidden; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                @if ($item->product?->foto_url)
                <img src="{{ $item->product->foto_url }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:contain;"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                @endif
                <div style="{{ $item->product?->foto_url ? 'display:none;' : '' }} width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="#CBCBCB" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div style="flex:1; min-width:0;">
                @if ($item->product?->code)
                <span style="display:inline-block; padding:1px 6px; font-size:9px; font-weight:700; background:#E8F0F7; color:#6D8196; border-radius:3px; text-transform:uppercase; margin-bottom:2px;">{{ $item->product->code }}</span>
                @endif
                <p style="font-size:12px; font-weight:600; color:#4A4A4A; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item->product?->name }}</p>
                <p style="font-size:11px; color:#CBCBCB; margin:1px 0 0;">{{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}</p>
            </div>
            <div style="text-align:right; flex-shrink:0;">
                <p style="font-size:13px; font-weight:700; color:#6D8196; margin:0;">Bs {{ number_format($item->subtotal, 2) }}</p>
                @if ($item->puntos > 0)
                <span style="font-size:9px; font-weight:600; background:#D1FAE5; color:#15803D; border-radius:3px; padding:1px 5px;">+{{ $item->puntos * $item->cantidad }} pts</span>
                @endif
            </div>
        </div>
        @endforeach

        @php $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad); @endphp
        <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px; padding:10px 12px; border-top:2px solid #CBCBCB; background:#F7F7F0;">
            @if ($totalPuntos > 0)
            <span style="font-size:11px; font-weight:600; background:#D1FAE5; color:#15803D; border-radius:3px; padding:2px 8px;">+{{ number_format($totalPuntos) }} pts</span>
            @endif
            <p style="font-size:15px; font-weight:800; color:#4A4A4A; margin:0;">Total: Bs {{ number_format($p->total, 2) }}</p>
        </div>
    </div>
</div>

{{-- Dirección de Entrega --}}
@php $tieneEntrega = $p->entrega_direccion || $p->entrega_ciudad; @endphp
@if ($tieneEntrega || ($editable ?? false))
<div x-data="{ modalDir: false }" @direccion-guardada.window="modalDir = false" style="margin-top:16px;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
        <p style="font-size:10px; font-weight:700; color:#6D8196; text-transform:uppercase; letter-spacing:.4px; margin:0;">Dirección de Entrega</p>
        @if ($editable ?? false)
        <button @click="modalDir = true" class="ds-btn ds-btn-ghost ds-btn-sm">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Editar
        </button>
        @endif
    </div>

    <div style="background:#fff; border:1px solid #CBCBCB; border-radius:10px; padding:12px 14px;">
        @if ($tieneEntrega)
        @php $partesDir = array_filter([$p->entrega_direccion, $p->entrega_ciudad, $p->entrega_provincia, $p->entrega_municipio]); @endphp
        <p style="font-size:13px; font-weight:600; color:#4A4A4A; margin:0;">{{ implode(', ', $partesDir) }}</p>
        @if ($p->entrega_referencia)
        <p style="font-size:11px; color:#CBCBCB; margin:3px 0 0;">Ref: {{ $p->entrega_referencia }}</p>
        @endif
        @else
        <p style="font-size:12px; color:#CBCBCB; font-style:italic; margin:0;">Sin dirección registrada</p>
        @endif
    </div>

    @if ($editable ?? false)
    <div x-show="modalDir"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(74,74,74,.5);"
         @click.self="modalDir = false">

        <div x-show="modalDir"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             style="background:#FFFFE3; border:1px solid #CBCBCB; border-radius:10px; width:100%; max-width:440px; overflow:hidden; position:relative; max-height:90vh; overflow-y:auto;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #CBCBCB; background:#fff;">
                <p style="font-size:13px; font-weight:700; color:#4A4A4A; margin:0;">Dirección de Entrega</p>
                <button @click="modalDir = false"
                        style="width:26px; height:26px; border-radius:6px; background:#F4F4F4; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <svg width="12" height="12" fill="none" stroke="#4A4A4A" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div style="padding:16px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div class="ds-form-group" style="margin-bottom:0;">
                        <label class="ds-form-label">Ciudad *</label>
                        <select wire:model.live="editCiudad" style="width:100%;">
                            <option value="">-- Seleccionar --</option>
                            @foreach($ciudadesAll as $c)
                            <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                        @error('editCiudad')<p class="ds-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="ds-form-group" style="margin-bottom:0;">
                        <label class="ds-form-label">Provincia</label>
                        <select wire:model.live="editProvincia" style="width:100%;" @disabled(!$editCiudad)>
                            <option value="">-- Seleccionar --</option>
                            @foreach($editProvincias as $prov)
                            <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                    <div class="ds-form-group" style="margin-bottom:0;">
                        <label class="ds-form-label">Municipio</label>
                        <select wire:model.live="editMunicipio" style="width:100%;" @disabled(!$editProvincia)>
                            <option value="">-- Seleccionar --</option>
                            @foreach($editMunicipios as $mun)
                            <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ds-form-group" style="margin-bottom:0;">
                        <label class="ds-form-label">Dirección *</label>
                        <input wire:model="editDireccion" type="text" placeholder="Calle y número" style="width:100%;">
                        @error('editDireccion')<p class="ds-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="ds-form-group">
                    <label class="ds-form-label">Referencia <span style="font-weight:400;text-transform:none;">(opcional)</span></label>
                    <input wire:model="editReferencia" type="text" placeholder="Portón azul, frente al parque..." style="width:100%;">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:8px;">
                    <button @click="modalDir = false" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
                    <button wire:click="guardarDireccion" wire:loading.attr="disabled" class="ds-btn ds-btn-primary ds-btn-sm">
                        <span wire:loading.remove wire:target="guardarDireccion">Guardar</span>
                        <span wire:loading wire:target="guardarDireccion">Guardando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endif
