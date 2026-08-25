{{--
    Partial: detalle de pedido — formato lila.
    Variables: $p, $plan, $aprobado (bool), $editable (bool),
               $ciudadesAll, $editProvincias, $editMunicipios
--}}
@php
    $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad);
    $cuotasNum   = $plan?->cantidad_cuotas ?? null;
    $montoCuota  = $plan?->monto_cuota;
    $cuotaInicial = ($plan && $plan->cuota_inicial > 0) ? $plan->cuota_inicial : null;
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
    $editable        = $editable        ?? false;
    $editTipoEntrega = $editTipoEntrega ?? 'domicilio';
    $articulosEdit        = $articulosEdit        ?? [];
    $articulosAgrupados   = $articulosAgrupados   ?? [];
    $articulosTodos       = $articulosTodos       ?? [];
    $searchProductoEdit   = $searchProductoEdit   ?? '';
@endphp

<div style="padding:12px 0 16px;">

    {{-- Dato Cliente --}}
    <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Dato Cliente</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>

    @php
    $vField = 'background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:9px 12px; font-size:13px; font-weight:600; color:#111827; min-height:38px; display:flex; align-items:center;';
    $vLabel = 'font-size:10px; font-weight:700; letter-spacing:0.06em; color:#7B6FE8; margin:0 0 4px 0;';
    $vSec   = 'display:flex; align-items:center; gap:7px; margin-bottom:12px;';

    $calColors = [
        'A'         => ['bg' => '#DCFCE7', 'cl' => '#15803D'],
        'B'         => ['bg' => '#ECFEFF', 'cl' => '#0E7490'],
        'C'         => ['bg' => '#FEF3C7', 'cl' => '#854F0B'],
        'D'         => ['bg' => '#FFF7ED', 'cl' => '#C2410C'],
        'BLOQUEADO' => ['bg' => '#FEF2F2', 'cl' => '#B91C1C'],
    ];
    $showHistorial = isset($clienteCalificacion) || isset($clienteHistorial);
    $clienteCalificacion ??= null;
    $clienteHistorial    ??= collect();
    $calBadge = $clienteCalificacion ? ($calColors[$clienteCalificacion['calificacion']] ?? ['bg' => '#F3F4F6', 'cl' => '#6B7280']) : null;
    @endphp

    <div x-data="{ modal: false }">
        {{-- Trigger --}}
        <div style="position:relative; cursor:pointer;" @click="modal = true">
            <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; pointer-events:none;" fill="none" stroke="#7B6FE8" stroke-width="2.3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <div style="width:100%; padding:8px 32px; font-size:12px; font-weight:700; border-radius:10px; border:1px solid #E5E7EB; background:#F9F8FF; color:#3C3489; box-sizing:border-box; min-height:20px; display:flex; align-items:center; gap:8px;">
                <span style="flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }} — {{ $p->cliente->nombre_completo }}</span>
                @if ($calBadge)
                <span style="flex-shrink:0; font-size:10px; font-weight:800; padding:2px 9px; border-radius:99px; background:{{ $calBadge['bg'] }}; color:{{ $calBadge['cl'] }};">{{ $clienteCalificacion['calificacion'] }}</span>
                @endif
            </div>
        </div>

        <template x-teleport="body">
            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 flex items-center justify-center p-4"
                 style="z-index:9999; background:rgba(0,0,0,.45);"
                 @click.self="modal = false" @keydown.escape.window="modal = false">

                <div style="background:#fff; border-radius:8px; width:100%; max-width:460px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22);">

                    <div style="padding:16px 20px; border-bottom:1px solid #F0EEFF; display:flex; align-items:center; gap:9px; flex-shrink:0;">
                        <div style="width:30px; height:30px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <p style="font-size:17px; font-weight:700; color:#6B7280; margin:0; letter-spacing:-0.2px; flex:1;">Datos del Cliente</p>
                        <button @click="modal = false"
                                style="width:32px; height:32px; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#7B6FE8; transition:background .15s, color .15s;"
                                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div style="overflow:auto; flex:1; padding:16px 20px; display:flex; flex-direction:column; gap:12px;">

                        <div>
                            <div style="{{ $vSec }}">
                                <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Datos Personales</span>
                                <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                            </div>
                            <div style="margin-bottom:10px;">
                                <p style="{{ $vLabel }}">Nombre</p>
                                <div style="{{ $vField }}">{{ $p->cliente->nombre_completo }}</div>
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                <div><p style="{{ $vLabel }}">CI</p><div style="{{ $vField }} font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</div></div>
                                <div><p style="{{ $vLabel }}">Teléfono</p><div style="{{ $vField }}">{{ $p->cliente->telefono ?: '—' }}</div></div>
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <div><p style="{{ $vLabel }}">NIT</p><div style="{{ $vField }}">{{ $p->cliente->nit ?: '—' }}</div></div>
                                <div><p style="{{ $vLabel }}">Correo</p><div style="{{ $vField }} word-break:break-all; align-items:flex-start; padding-top:9px;">{{ $p->cliente->correo ?: '—' }}</div></div>
                            </div>
                        </div>

                        <div>
                            <div style="{{ $vSec }}">
                                <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección</span>
                                <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                            </div>
                            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:10px;">
                                <div><p style="{{ $vLabel }}">Ciudad</p><div style="{{ $vField }}">{{ $p->cliente->ciudad ?: '—' }}</div></div>
                                <div><p style="{{ $vLabel }}">Provincia</p><div style="{{ $vField }}">{{ $p->cliente->provincia ?: '—' }}</div></div>
                                <div><p style="{{ $vLabel }}">Municipio</p><div style="{{ $vField }}">{{ $p->cliente->municipio ?: '—' }}</div></div>
                            </div>
                            <div><p style="{{ $vLabel }}">Dirección</p><div style="{{ $vField }}">{{ $p->cliente->direccion ?: '—' }}</div></div>
                        </div>

                        @if ($showHistorial)
                        <div>
                            <div style="{{ $vSec }}">
                                <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Historial Crediticio</span>
                                <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                                @if ($calBadge)
                                <span style="font-size:11px; font-weight:800; padding:2px 10px; border-radius:99px; background:{{ $calBadge['bg'] }}; color:{{ $calBadge['cl'] }}; flex-shrink:0;">{{ $clienteCalificacion['calificacion'] }} · {{ number_format($clienteCalificacion['puntaje'], 1) }} pts</span>
                                @endif
                            </div>

                            @if (!$clienteCalificacion)
                            <p style="font-size:12px; color:#9CA3AF; margin:0;">Sin pedidos previos con plan de pago para calificar.</p>
                            @else
                            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:12px;">
                                <div style="background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:7px 9px;">
                                    <p style="{{ $vLabel }} margin-bottom:2px;">Puntualidad</p>
                                    <p style="font-size:14px; font-weight:800; color:#3C3489; margin:0;">{{ number_format($clienteCalificacion['puntualidad'], 0) }}%</p>
                                </div>
                                <div style="background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:7px 9px;">
                                    <p style="{{ $vLabel }} margin-bottom:2px;">Mora</p>
                                    <p style="font-size:14px; font-weight:800; color:{{ $clienteCalificacion['mora'] > 0 ? '#DC2626' : '#3C3489' }}; margin:0;">{{ number_format($clienteCalificacion['mora'], 0) }}%</p>
                                </div>
                                <div style="background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:7px 9px;">
                                    <p style="{{ $vLabel }} margin-bottom:2px;">En riesgo</p>
                                    <p style="font-size:14px; font-weight:800; color:{{ $clienteCalificacion['riesgo'] > 0 ? '#DC2626' : '#3C3489' }}; margin:0;">{{ number_format($clienteCalificacion['riesgo'], 0) }}%</p>
                                </div>
                            </div>

                            <p style="{{ $vLabel }} margin-bottom:6px;">Pedidos anteriores ({{ $clienteCalificacion['total_pedidos'] }})</p>
                            <div style="border:1px solid #E5E7EB; border-radius:8px; overflow:hidden; background:#fff;">
                                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; padding:6px 10px; background:#F9F8FF; border-bottom:1px solid #E5E7EB;">
                                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase;">Pedido</span>
                                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; text-align:center;">Puntual.</span>
                                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; text-align:center;">Mora</span>
                                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; text-align:right;">Monto Bs.</span>
                                </div>
                                @foreach ($clienteHistorial as $h)
                                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; align-items:center; padding:7px 10px; {{ !$loop->last ? 'border-bottom:1px solid #F3F4F6;' : '' }}">
                                    <span style="font-size:11px; font-weight:600; color:#374151;">{{ $h['numero'] }}</span>
                                    <span style="font-size:11px; color:#374151; text-align:center;">{{ number_format($h['puntualidad'], 0) }}%</span>
                                    <span style="text-align:center;">
                                        @if ($h['en_mora'])
                                        <span style="font-size:9.5px; font-weight:700; padding:1px 7px; border-radius:99px; background:#FEF2F2; color:#B91C1C;">Sí</span>
                                        @else
                                        <span style="font-size:9.5px; font-weight:700; padding:1px 7px; border-radius:99px; background:#F3F4F6; color:#9CA3AF;">No</span>
                                        @endif
                                    </span>
                                    <span style="font-size:11px; font-weight:600; color:#3C3489; text-align:right;">{{ number_format($h['monto'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>

                    <div style="padding:12px 20px; border-top:1px solid #F3F4F6; flex-shrink:0;">
                        <button @click="modal = false"
                                style="width:100%; padding:10px; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:10px; font-size:13px; font-weight:700; color:#7B6FE8; cursor:pointer; transition:background .15s, color .15s;"
                                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </div>

    {{-- Notas / Motivo de rechazo --}}
    @if ($p->notas)
    <div style="margin-top:8px;">
        @if ($p->estado === 'rechazado')
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:8px; margin-top:16px;">
            <svg width="14" height="14" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#B91C1C; letter-spacing:0.05em; white-space:nowrap;">Motivo de Rechazo</span>
            <div style="flex:1; height:1.5px; background:#FECACA;"></div>
        </div>
        <div style="background:#FEF2F2; border:0.5px solid #CBCBCB; border-radius:10px; padding:10px 12px;">
            <span style="font-size:13px; font-weight:600; color:#B91C1C; display:block;">{{ $p->notas }}</span>
        </div>
        @else
        <div style="background:white; border:0.5px solid #CECBF6; border-radius:10px; padding:10px 12px; margin-top:8px;">
            <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">Notas</span>
            <span style="font-size:13px; font-weight:600; color:#3C3489; display:block;">{{ $p->notas }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- Documentación del Plan --}}
    <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Documentación del Plan</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>
    <style>@media(min-width:400px){.doc-pd-grid{grid-template-columns:repeat(5,1fr)!important;}}</style>
    <div class="doc-pd-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
    @foreach ($docs as $label => $path)
    @php $campo = $docCampos[$label]; $prop = $docProps[$campo]; @endphp
    <div style="display:flex; flex-direction:column; gap:4px;">
        @if ($path)
        @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
        <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
            <div style="border:1.5px solid #0F6E56; background:#F0FDF4; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box;">
                <div style="width:24px; height:24px; border-radius:6px; background:#DCFCE7; display:flex; align-items:center; justify-content:center;"><svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:#0F6E56;">{{ $label }}</span>
                <span style="display:inline-flex; align-items:center; gap:2px; font-size:8px; color:#0F6E56;"><svg style="width:9px;height:9px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Descargar</span>
            </div>
        </a>
        @else
        <div style="border:1.5px dashed #9CA3AF; background:#fff; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box;">
            <div style="width:24px; height:24px; border-radius:6px; background:#EEEDFE; display:flex; align-items:center; justify-content:center;"><svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
            <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:#534AB7;">{{ $label }}</span>
            <span style="font-size:8px; color:#AFA9EC;">Sin archivo</span>
        </div>
        @endif
        @if ($editable)
        <div x-data="{ subido: false }">
            <input type="file" wire:model="{{ $prop }}" x-ref="fi_{{ $campo }}" accept="image/*,.pdf" style="display:none"
                   x-on:livewire-upload-finish="$wire.subirDocumento('{{ $campo }}'); subido = true;">
            <button @click="$refs['fi_{{ $campo }}'].click()" :style="{ background: subido ? '#DCFCE7' : '#F5F3FF' }"
                    style="width:100%; color:#7B6FE8; border:1.5px solid #C4B5FD; font-size:10px; font-weight:600; border-radius:6px; padding:4px 6px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:3px; -webkit-appearance:none; appearance:none;">
                <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span x-text="subido ? 'Listo ✓' : '{{ $path ? 'Reemplazar' : 'Subir' }}'"></span>
            </button>
            @error($prop)<p style="font-size:9px; color:#DC2626; margin-top:2px;">{{ $message }}</p>@enderror
        </div>
        @endif
    </div>
    @endforeach
    </div>

    {{-- Artículos Seleccionados --}}
    <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Artículos Seleccionados</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        @if($editable)
        <button wire:click="abrirEditarArticulos"
                style="display:flex; align-items:center; gap:4px; padding:4px 10px; background:#F5F3FF; color:#7B6FE8; font-size:11px; font-weight:700; border-radius:6px; border:1px solid #EDE9FE; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Editar
        </button>
        @endif
    </div>
    <style>
    .pd-art-grid, .pd-art-row { display:grid; grid-template-columns:40px minmax(0,1fr) 68px 88px 54px; align-items:stretch; }
    @media (max-width:480px) {
        .pd-art-grid, .pd-art-row { grid-template-columns:36px minmax(0,1fr) 34px; }
        .pd-art-grid .pd-precio, .pd-art-grid .pd-total, .pd-art-grid .pd-pts,
        .pd-art-row .pd-precio, .pd-art-row .pd-total, .pd-art-row .pd-pts { display:none; }
    }
    </style>
    <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:14px; overflow:hidden; box-shadow:0 2px 4px rgba(60,52,137,0.06), 0 8px 20px rgba(60,52,137,0.08);">
        <div class="pd-art-grid">
            <div style="padding:8px 4px 8px 10px; background:#F9F8FF; border-bottom:2px solid #EDE9FE;"></div>
            <div style="padding:8px 8px 8px 0; background:#F9F8FF; border-bottom:2px solid #EDE9FE; font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Producto</div>
            <div class="pd-precio" style="padding:8px 8px 8px 0; background:#F9F8FF; border-bottom:2px solid #EDE9FE; font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Precio</div>
            <div class="pd-total" style="padding:8px 8px 8px 0; background:#F9F8FF; border-bottom:2px solid #EDE9FE; font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Total</div>
            <div class="pd-pts" style="padding:8px 10px 8px 0; background:#F9F8FF; border-bottom:2px solid #EDE9FE; font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Pts</div>
        </div>

        @foreach ($p->items as $item)
        @php
            $rowBorder = $loop->last ? 'none' : '1px solid #E7E3FA';
            $itemNombre = $item->product?->name
                ?? $item->listaMaestraItem?->product?->name
                ?? $item->listaMaestraItem?->maestroArticulo?->nombre;
            $itemCodigo = $item->product?->code
                ?? $item->listaMaestraItem?->product?->code
                ?? $item->listaMaestraItem?->maestroArticulo?->codigo;
        @endphp
        <div class="pd-art-row" wire:key="pd-item-{{ $item->id }}">
            <div style="padding:10px 4px 10px 10px; background:#fff; border-bottom:{{ $rowBorder }}; display:flex; align-items:center; justify-content:center;">
                <div style="width:20px; height:20px; border-radius:50%; background:#f97316; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:9px; font-weight:800; color:#fff; line-height:1;">{{ $item->cantidad }}</span>
                </div>
            </div>
            <div style="padding:10px 8px 10px 0; background:#fff; border-bottom:{{ $rowBorder }}; min-width:0; overflow:hidden; display:flex; flex-direction:column; justify-content:center;">
                <span style="font-size:13px; font-weight:700; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($itemNombre ?? '—')) }}</span>
                <span style="font-size:10.5px; color:#9CA3AF; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1.3;">{{ $itemCodigo }}</span>
            </div>
            <div class="pd-precio" style="padding:10px 8px 10px 0; background:#fff; border-bottom:{{ $rowBorder }}; font-size:13px; font-weight:400; color:#3C3489; font-variant-numeric:tabular-nums; display:flex; align-items:center; justify-content:flex-end;">{{ number_format($item->precio_unitario, 2) }}</div>
            <div class="pd-total" style="padding:10px 8px 10px 0; background:#fff; border-bottom:{{ $rowBorder }}; font-size:13px; font-weight:400; color:#3C3489; font-variant-numeric:tabular-nums; display:flex; align-items:center; justify-content:flex-end;">{{ number_format($item->subtotal, 2) }}</div>
            <div class="pd-pts" style="padding:10px 10px 10px 0; background:#fff; border-bottom:{{ $rowBorder }}; font-size:13px; font-weight:400; color:#3C3489; font-variant-numeric:tabular-nums; display:flex; align-items:center; justify-content:flex-end;">{{ $item->puntos * $item->cantidad }}</div>
        </div>
        @endforeach
    </div>

    @if($editable)
    <style>
    .modal-art-panel {
        overflow: hidden;
        min-height: 0;
    }
    @media (min-width: 768px) {
        .modal-art-outer {
            background: rgba(20,10,40,0.45) !important;
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .modal-art-panel {
            max-height: 85vh;
            border-radius: 10px;
            box-shadow: 0 24px 60px rgba(60,52,137,0.22), 0 0 0 1px rgba(196,181,253,0.15);
            flex: none;
            max-width: 900px;
            width: 100%;
        }
    }
    </style>

    <div x-data="{ modalArt: false }"
         @art-modal-open.window="modalArt = true"
         @art-modal-close.window="modalArt = false">

        <div x-show="modalArt"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 md:absolute md:inset-0 flex flex-col modal-art-outer"
             style="z-index:400; background:#fff;"
             @click.self="$wire.call('cerrarEditarArticulos')">
            <div x-show="modalArt"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="flex flex-col flex-1 w-full modal-art-panel"
                 style="background:#fff;">

                {{-- TOP BAR --}}
                <div style="flex-shrink:0; background:#fff; border-bottom:1.5px solid #EDE9FE; padding:12px 14px 10px;">
                <div style="max-width:900px; margin:0 auto;">
                    <div style="text-align:center; margin-bottom:10px;">
                        <span style="font-size:16px; font-weight:900; color:#1a1a1a; letter-spacing:0.08em; text-transform:uppercase; display:block;">Editar Artículos</span>
                        <div style="height:2px; background:linear-gradient(to right,#7B6FE8,#C4B5FD); border-radius:1px; margin-top:4px;"></div>
                    </div>
                    <div style="position:relative;">
                        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; pointer-events:none;" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="searchProductoEdit" type="text"
                               placeholder="Buscar producto..."
                               style="width:100%; padding:8px 10px 8px 28px; background:#F8F7FF; border:2px solid #C4B5FD; border-radius:10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#7c3aed'; this.style.background='#fff';"
                               onblur="this.style.borderColor='#C4B5FD'; this.style.background='#F8F7FF';">
                    </div>
                </div>
                </div>

                {{-- LISTA SCROLLABLE --}}
                <div style="flex:1; overflow-y:auto; padding:10px 12px;">
                <div style="max-width:900px; margin:0 auto;">
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @forelse ($articulosTodos as $grupo)
                            @foreach ($grupo['productos'] as $prod)
                            @php
                                $editEntry = null;
                                foreach ($articulosEdit as $ea) {
                                    if ($ea['product_id'] == $prod['product_id']) { $editEntry = $ea; break; }
                                }
                                $editQty = $editEntry ? (int) $editEntry['cantidad'] : 0;
                            @endphp
                            <div x-data="{ n: {{ $editQty > 0 ? $editQty : 1 }}, maxStock: @js((int)$prod['stock']) }"
                                 wire:key="art-rev-{{ $prod['product_id'] }}"
                                 style="background:#fff; border:1.5px solid #D1D5DB; border-radius:12px; padding:20px 12px; box-shadow:0 2px 4px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.10), 0 24px 40px rgba(0,0,0,0.06);">

                                <div style="display:flex; align-items:flex-start; gap:7px; margin-bottom:7px;">
                                    <div style="width:22px; height:22px; border-radius:50%; background:{{ $editQty > 0 ? '#f97316' : '#EDE9FE' }}; border:1.5px solid {{ $editQty > 0 ? '#f97316' : '#D4CFF8' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                                        @if ($editQty > 0)
                                        <span style="font-size:10px; font-weight:800; color:#fff; line-height:1;">{{ $editQty }}</span>
                                        @endif
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <span style="font-size:16px; font-weight:800; color:#3C3489; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ ucwords(strtolower($prod['nombre'])) }}">{{ ucwords(strtolower($prod['nombre'])) }}</span>
                                        <span style="font-size:13px; font-weight:400; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $prod['codigo'] ?? '' }}</span>
                                    </div>
                                </div>

                                <div style="margin-bottom:8px; background:#F8F7FF; border-radius:8px; padding:8px 10px;">
                                    <div style="display:flex; align-items:center; gap:14px; margin-bottom:4px;">
                                        <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Precio Bs Un: <span style="color:#7c3aed; font-size:14px; font-weight:400;">{{ number_format($prod['precio'], 2) }}</span></span>
                                        <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Puntos: <span style="color:#111827; font-size:14px; font-weight:400;">{{ $prod['puntos'] }}</span></span>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:14px;">
                                        <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Bs: <span style="color:#3C3489; font-size:14px; font-weight:400;" x-text="({{ $prod['precio'] }} * n).toFixed(2)">—</span></span>
                                        <span style="font-size:12px; font-weight:400; color:#9CA3AF; white-space:nowrap;">Total Pts: <span style="color:#111827; font-size:14px; font-weight:400;" x-text="'+' + ({{ $prod['puntos'] }} * n)">—</span></span>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div style="display:flex; align-items:center; gap:0; border:1.5px solid #EDE9FE; border-radius:8px; overflow:hidden; flex-shrink:0;">
                                        <button @click="if(n>1) n--" style="width:30px; height:30px; background:#F5F3FF; border:none; cursor:pointer; font-size:16px; font-weight:700; color:#7B6FE8; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;">−</button>
                                        <span x-text="n" style="min-width:30px; text-align:center; font-size:13px; font-weight:700; color:#111827;"></span>
                                        <button @click="if(n < maxStock) n++" style="width:30px; height:30px; background:#F5F3FF; border:none; cursor:pointer; font-size:16px; font-weight:700; color:#7B6FE8; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;">+</button>
                                    </div>
                                    <div style="flex:1;"></div>
                                    <button @click="$wire.agregarOActualizarEdit({{ $prod['product_id'] }}, n)"
                                            style="background:#fff; color:#f97316; border:1.5px solid #f97316; border-radius:8px; padding:7px 14px; font-size:13px; font-weight:700; cursor:pointer; -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:6px; flex-shrink:0;">
                                        <svg style="width:14px; height:14px; flex-shrink:0;" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        {{ $editQty > 0 ? 'Actualizar' : 'Agregar' }}
                                    </button>
                                    @if ($editQty > 0)
                                    <button wire:click="quitarPorProductoEdit({{ $prod['product_id'] }})"
                                            style="width:30px; height:30px; border-radius:50%; background:#ef4444; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; -webkit-appearance:none; appearance:none; box-shadow:0 2px 8px rgba(239,68,68,0.40);">
                                        <svg style="width:13px; height:13px;" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @empty
                        <div style="text-align:center; padding:40px 16px;">
                            <svg style="width:40px; height:40px; margin:0 auto 10px; display:block;" fill="none" stroke="#CECBF6" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p style="font-size:13px; font-weight:500; color:#9B93E0; margin:0;">No hay productos disponibles</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                </div>

                {{-- PIE MODAL --}}
                <div style="flex-shrink:0; padding:10px 14px; background:#fff; border-top:1.5px solid #EDE9FE;">
                <div style="max-width:900px; margin:0 auto;">
                    <button wire:click="guardarArticulos" wire:loading.attr="disabled" wire:target="guardarArticulos"
                            style="width:100%; padding:13px; background:{{ !empty($articulosEdit) ? '#7B6FE8' : '#6B7280' }}; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none; display:flex; align-items:center; justify-content:center; gap:8px;">
                        @if (!empty($articulosEdit))
                        <span style="width:22px; height:22px; border-radius:50%; background:#f97316; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span wire:loading.remove wire:target="guardarArticulos" style="text-decoration:underline; text-underline-offset:3px;">Guardar &mdash; {{ count($articulosEdit) }} artículos</span>
                        <span wire:loading wire:target="guardarArticulos">Guardando...</span>
                        @else
                        <span style="width:22px; height:22px; border-radius:50%; background:#f97316; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                        <span style="text-decoration:underline; text-underline-offset:3px;">Sin artículos</span>
                        @endif
                    </button>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Resumen --}}
    <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Resumen</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
    </div>
    @php
        $pdResColCount = 1 + ($cuotaInicial ? 1 : 0) + ($cuotasNum ? 2 : 0) + 1;
        $pdResCols = 'repeat(' . $pdResColCount . ',1fr)';
    @endphp
    <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(123,111,232,0.12);">
        <div style="display:grid; grid-template-columns:{{ $pdResCols }}; text-align:center; padding:6px 6px; background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
            <div style="padding:0 6px;"><span style="font-size:8.5px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Puntos</span></div>
            @if ($cuotaInicial)
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;"><span style="font-size:8.5px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Cuota Inicial</span></div>
            @endif
            @if ($cuotasNum)
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;"><span style="font-size:8.5px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">N° Cuotas</span></div>
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;"><span style="font-size:8.5px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Cuota</span></div>
            @endif
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;"><span style="font-size:8.5px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Total Bs.</span></div>
        </div>
        <div style="display:grid; grid-template-columns:{{ $pdResCols }}; text-align:center; padding:8px 6px; background:#fff;">
            <div style="padding:0 6px;">
                <span style="font-size:13px; font-weight:400; color:#111827; line-height:1.1;">{{ number_format($totalPuntos) }}</span>
            </div>
            @if ($cuotaInicial)
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;">
                <span style="font-size:13px; font-weight:400; color:#111827; line-height:1.1;">{{ number_format($cuotaInicial, 2) }}</span>
            </div>
            @endif
            @if ($cuotasNum)
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;">
                <span style="font-size:13px; font-weight:400; color:#111827; line-height:1.1;">{{ $cuotasNum }}</span>
            </div>
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;">
                <span style="font-size:13px; font-weight:400; color:#111827; line-height:1.1;">{{ number_format($montoCuota, 2) }}</span>
            </div>
            @endif
            <div style="padding:0 6px; border-left:1px solid #E7E3FA;">
                <span style="font-size:13px; font-weight:400; color:#3C3489; line-height:1.1;">{{ number_format($p->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Plan de Pagos --}}
    @if ($plan)
    <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
        <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Plan de Pagos</span>
        <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        <span style="font-size:10px; font-weight:700; background:#EEEDFE; color:#534AB7; border-radius:99px; padding:2px 8px;">{{ $plan->cantidad_cuotas }} {{ $plan->cantidad_cuotas === 1 ? 'cuota' : 'cuotas' }}</span>
    </div>
    @if ($plan->version > 1)
    <p style="font-size:10px; font-weight:700; color:#854F0B; background:#FEF3C7; border-radius:6px; padding:4px 10px; display:inline-block; margin-bottom:10px;">Reprogramado v{{ $plan->version }}</p>
    @endif
    <div style="border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; background:#fff;">
        <div style="display:grid; grid-template-columns:1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr; padding:8px 12px; background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
            <p style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Cuota</p>
            <p style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em;">Vencimiento</p>
            @if ($aprobado)<p style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; text-align:center;">Estado</p>@endif
            <p style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.06em; text-align:right;">Monto Bs.</p>
        </div>
        @foreach ($plan->cuotas as $cuota)
        <div style="display:grid; grid-template-columns:1fr 1fr {{ $aprobado ? '1fr' : '' }} 1fr; align-items:center; padding:10px 12px; {{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
            <span style="font-size:11px; font-weight:600; color:{{ $cuota->numero === 0 ? '#0F6E56' : '#374151' }};">{{ $cuota->numero === 0 ? 'Inicial' : 'Cuota '.$cuota->numero }}</span>
            <p style="font-size:11px; color:#6b7280;">{{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}</p>
            @if ($aprobado)
            @php $cbadge = $cuota->estadoFinancieroBadge; @endphp
            <div style="display:flex; align-items:center; justify-content:center;">
                <span style="background:{{ $cbadge['bg'] }}; color:{{ $cbadge['cl'] }}; font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $cbadge['lb'] }}</span>
            </div>
            @endif
            <p style="font-size:13px; font-weight:700; color:#7c3aed; text-align:right;">{{ number_format($cuota->monto, 2) }}</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Dirección de Entrega --}}
    <div x-data="{ modalDir: false, tipo: '{{ $editTipoEntrega }}', ubDir: false, ubDirTipo: '', ubDirOpciones: [], ubDirSearch: '' }"
         x-init="$watch('modalDir', v => { if (!v) $wire.call('resetDireccionEdit'); })"
         @direccion-guardada.window="modalDir = false"
         @reset-dir.window="tipo = $event.detail.tipo"
         @ub-dir-sel.window="
             if($event.detail.tipo==='ciudad') $wire.set('editCiudad', $event.detail.valor);
             else if($event.detail.tipo==='provincia') $wire.set('editProvincia', $event.detail.valor);
             else $wire.set('editMunicipio', $event.detail.valor);
         ">
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección de Entrega</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
            @if ($editable)
            <button @click="modalDir = true" style="display:flex; align-items:center; gap:4px; padding:4px 10px; background:#F5F3FF; color:#7B6FE8; font-size:11px; font-weight:700; border-radius:6px; border:1px solid #EDE9FE; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
            @endif
        </div>
        <div style="background:#fff; border-radius:12px; padding:12px; box-shadow:0 4px 20px rgba(123,111,232,0.10); border:0.5px solid #EDE9FE;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                <div style="{{ $p->tipo_entrega === 'domicilio' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px;">🏠 Domicilio</div>
                <div style="{{ $p->tipo_entrega === 'nuevo' ? 'background:#f97316; border:1.5px solid #f97316; color:#fff;' : 'background:#f9fafb; border:1.5px solid #e5e7eb; color:#9ca3af;' }} border-radius:8px; padding:8px; font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:5px;">📍 Nuevo lugar</div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_ciudad ? ucwords(strtolower($p->entrega_ciudad)) : '—' }}</div></div>
                <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_provincia ? ucwords(strtolower($p->entrega_provincia)) : '—' }}</div></div>
                <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_municipio ? ucwords(strtolower($p->entrega_municipio)) : '—' }}</div></div>
                <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->entrega_direccion ?: '—' }}</div></div>
            </div>
            <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Referencia <span style="color:#9CA3AF; font-weight:400; text-transform:none;">(opcional)</span></p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_referencia ?: '—' }}</div></div>
        </div>

        @if ($editable)
        <style>
        .rdir-label { font-size:10px; font-weight:700; color:#6B65B0; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:5px; }
        .rdir-input { width:100%; padding:10px 12px; border:1.5px solid #EDE9FE; border-radius:10px; font-size:13px; color:#3C3489; background:#fff; outline:none; box-sizing:border-box; -webkit-appearance:none; appearance:none; transition:border-color 0.15s; display:block; }
        .rdir-input:focus { border-color:#C4B5FD; }
        .rdir-readonly { width:100%; padding:10px 12px; border:1.5px solid #EDE9FE; border-radius:10px; font-size:13px; color:#3C3489; background:#F8F7FF; box-sizing:border-box; display:block; }
        </style>

        {{-- Modal principal --}}
        <div x-show="modalDir"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(20,10,40,0.4); backdrop-filter:blur(2px);" @click.self="modalDir = false">
            <div x-show="modalDir"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 style="background:#fff; border-radius:20px; width:100%; max-width:460px; max-height:90vh; display:flex; flex-direction:column; box-shadow:0 24px 60px rgba(60,52,137,0.18), 0 0 0 1px rgba(196,181,253,0.15); overflow:hidden;">

                {{-- Header --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
                    <div style="display:flex; align-items:center; gap:9px;">
                        <div style="width:30px; height:30px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p style="font-size:17px; font-weight:700; color:#3C3489; margin:0; letter-spacing:-0.2px;">Dirección de Entrega</p>
                    </div>
                    <button type="button" @click="modalDir = false"
                            style="width:28px; height:28px; border-radius:8px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="10" height="10" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div style="overflow-y:auto; flex:1; min-height:0; padding:14px 18px 8px;">

                    {{-- Toggle --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px;">
                        <button type="button"
                                @click="tipo='domicilio'"
                                :style="tipo==='domicilio' ? 'background:#7B6FE8;color:#fff;border-color:#7B6FE8;' : 'background:#F9FAFB;color:#6B7280;border-color:#E5E7EB;'"
                                style="padding:14px 6px; border-radius:10px; border:1.5px solid; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s; -webkit-appearance:none; appearance:none;">
                            🏠 Domicilio
                        </button>
                        <button type="button"
                                @click="tipo='nuevo'"
                                :style="tipo==='nuevo' ? 'background:#7B6FE8;color:#fff;border-color:#7B6FE8;' : 'background:#F9FAFB;color:#6B7280;border-color:#E5E7EB;'"
                                style="padding:14px 6px; border-radius:10px; border:1.5px solid; font-size:12px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; transition:all 0.15s; -webkit-appearance:none; appearance:none;">
                            📍 Nuevo lugar
                        </button>
                    </div>

                    {{-- Domicilio: mismo layout que Nuevo lugar, solo lectura --}}
                    <div x-show="tipo==='domicilio'" style="background:#FAFAFE; border-radius:14px; padding:14px 16px; border:1px solid #F0EEFF; margin-bottom:4px;">
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:12px;">
                            <div style="width:5px; height:5px; border-radius:50%; background:#C4B5FD; flex-shrink:0;"></div>
                            <span style="font-size:9px; font-weight:700; color:#6B65B0; text-transform:uppercase; letter-spacing:.12em;">Dirección registrada del cliente</span>
                        </div>
                        <div style="display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:10px;">
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Ciudad</label>
                                <div class="rdir-readonly" style="display:flex; align-items:center; gap:8px;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $p->cliente->ciudad ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span style="font-size:13px; color:{{ $p->cliente->ciudad ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $p->cliente->ciudad ? '500' : '400' }};">{{ $p->cliente->ciudad ? ucwords(strtolower($p->cliente->ciudad)) : '—' }}</span>
                                </div>
                            </div>
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Provincia</label>
                                <div class="rdir-readonly" style="display:flex; align-items:center; gap:8px;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $p->cliente->provincia ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span style="font-size:13px; color:{{ $p->cliente->provincia ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $p->cliente->provincia ? '500' : '400' }};">{{ $p->cliente->provincia ? ucwords(strtolower($p->cliente->provincia)) : '—' }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="rdir-label">Municipio</label>
                                <div class="rdir-readonly" style="display:flex; align-items:center; gap:8px;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $p->cliente->municipio ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span style="font-size:13px; color:{{ $p->cliente->municipio ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $p->cliente->municipio ? '500' : '400' }};">{{ $p->cliente->municipio ? ucwords(strtolower($p->cliente->municipio)) : '—' }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="rdir-label">Dirección</label>
                                <div class="rdir-readonly">{{ $p->cliente->direccion ?: '—' }}</div>
                            </div>
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Referencia <span style="color:#D1D5DB; font-weight:400; text-transform:none; letter-spacing:0;">· opcional</span></label>
                                <div class="rdir-readonly">—</div>
                            </div>
                        </div>
                    </div>

                    {{-- Nuevo lugar: editable --}}
                    <div x-show="tipo==='nuevo'" style="background:#FAFAFE; border-radius:14px; padding:14px 16px; border:1px solid #F0EEFF; margin-bottom:4px;">
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:12px;">
                            <div style="width:5px; height:5px; border-radius:50%; background:#FBD0A4; flex-shrink:0;"></div>
                            <span style="font-size:9px; font-weight:700; color:#6B65B0; text-transform:uppercase; letter-spacing:.12em;">Nuevo lugar de entrega</span>
                        </div>
                        <div style="display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:10px;">
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Ciudad <span style="color:#F97316;">*</span></label>
                                <button type="button"
                                        @click="ubDir=true; ubDirTipo='ciudad'; ubDirOpciones=@js($ciudadesAll->pluck('nombre')->toArray()); ubDirSearch=''"
                                        style="width:100%; padding:10px 12px; border:1.5px solid {{ $editCiudad ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $editCiudad ? '#EEEDFE' : '#fff' }}; cursor:pointer; box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $editCiudad ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $editCiudad ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $editCiudad ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $editCiudad ? ucwords(strtolower($editCiudad)) : 'Seleccionar' }}</span>
                                    <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                @error('editCiudad')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                            </div>
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Provincia</label>
                                <button type="button"
                                        @if($editCiudad) @click="ubDir=true; ubDirTipo='provincia'; ubDirOpciones=@js($editProvincias->pluck('nombre')->toArray()); ubDirSearch=''" @endif
                                        style="width:100%; padding:10px 12px; border:1.5px solid {{ $editProvincia ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $editProvincia ? '#EEEDFE' : ($editCiudad ? '#fff' : '#FAFAFE') }}; {{ $editCiudad ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.5;' }} box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $editProvincia ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $editProvincia ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $editProvincia ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $editProvincia ? ucwords(strtolower($editProvincia)) : 'Seleccionar' }}</span>
                                    <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <div>
                                <label class="rdir-label">Municipio</label>
                                <button type="button"
                                        @if($editProvincia) @click="ubDir=true; ubDirTipo='municipio'; ubDirOpciones=@js($editMunicipios->pluck('nombre')->toArray()); ubDirSearch=''" @endif
                                        style="width:100%; padding:10px 12px; border:1.5px solid {{ $editMunicipio ? '#C4B5FD' : '#EDE9FE' }}; border-radius:10px; background:{{ $editMunicipio ? '#EEEDFE' : ($editProvincia ? '#fff' : '#FAFAFE') }}; {{ $editProvincia ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.5;' }} box-sizing:border-box; display:flex; align-items:center; gap:8px; overflow:hidden; transition:all 0.15s;">
                                    <svg width="13" height="13" fill="none" stroke="{{ $editMunicipio ? '#7c3aed' : '#C4B5FD' }}" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span style="flex:1; min-width:0; text-align:left; font-size:13px; color:{{ $editMunicipio ? '#3C3489' : '#9CA3AF' }}; font-weight:{{ $editMunicipio ? '500' : '400' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $editMunicipio ? ucwords(strtolower($editMunicipio)) : 'Seleccionar' }}</span>
                                    <svg width="9" height="9" fill="none" stroke="#C4B5FD" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </div>
                            <div>
                                <label class="rdir-label">Dirección <span style="color:#F97316;">*</span></label>
                                <input wire:model="editDireccion" type="text" placeholder="Calle y número" class="rdir-input">
                                @error('editDireccion')<p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                            </div>
                            <div style="grid-column:span 2;">
                                <label class="rdir-label">Referencia <span style="color:#D1D5DB; font-weight:400; text-transform:none; letter-spacing:0;">· opcional</span></label>
                                <input wire:model="editReferencia" type="text" placeholder="Portón azul, frente al parque..." class="rdir-input">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div style="padding:12px 18px 16px; border-top:1px solid #F0EEFF; display:flex; gap:8px; flex-shrink:0;">
                    <button type="button" @click="modalDir = false" style="flex:1; padding:11px; background:#F4F4F4; color:#6D8196; font-size:13px; font-weight:700; border-radius:10px; border:1.5px solid #E5E7EB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
                    <button type="button" @click="$wire.call('guardarDireccion', tipo)" wire:loading.attr="disabled" wire:target="guardarDireccion" style="flex:2; padding:11px; background:linear-gradient(135deg,#f97316 0%,#ea6000 100%); color:#fff; font-size:13px; font-weight:800; border-radius:10px; border:none; cursor:pointer; box-shadow:0 4px 18px rgba(249,115,22,0.35); -webkit-appearance:none; appearance:none;"><span wire:loading.remove wire:target="guardarDireccion">Guardar</span><span wire:loading wire:target="guardarDireccion">Guardando...</span></button>
                </div>
            </div>
        </div>

        {{-- Sub-modal selector Ciudad / Provincia / Municipio --}}
        <div x-show="ubDir" x-cloak
             x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex items-center justify-center"
             style="z-index:200; background:rgba(30,24,80,0.22); backdrop-filter:blur(2px);"
             @click.self="ubDir=false; ubDirSearch=''">
            <div style="background:#fff; border-radius:16px; width:85%; max-width:300px; max-height:60vh; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 8px 32px rgba(60,52,137,0.22);">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:13px 16px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
                    <span x-text="ubDirTipo==='ciudad' ? 'Seleccionar ciudad' : ubDirTipo==='provincia' ? 'Seleccionar provincia' : 'Seleccionar municipio'"
                          style="font-size:13px; font-weight:600; color:#534AB7;"></span>
                    <button type="button" @click="ubDir=false; ubDirSearch=''"
                            style="width:24px; height:24px; border-radius:6px; background:#F5F3FF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                        <svg width="9" height="9" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div style="padding:10px 12px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
                    <input x-model="ubDirSearch" type="text" placeholder="Buscar..."
                           style="width:100%; padding:7px 10px; border:1.5px solid #EDE9FE; border-radius:8px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#EDE9FE'">
                </div>
                <div style="overflow-y:auto; flex:1; min-height:0; padding:4px 0;">
                    <template x-for="op in ubDirOpciones.filter(o => o.toLowerCase().includes(ubDirSearch.toLowerCase()))" :key="op">
                        <button type="button"
                                @click="$dispatch('ub-dir-sel', { tipo: ubDirTipo, valor: op }); ubDir=false; ubDirSearch=''"
                                x-text="op.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())"
                                style="width:100%; text-align:left; padding:9px 16px; font-size:13px; color:#3C3489; background:transparent; border:none; cursor:pointer; transition:background 0.1s;"
                                onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='transparent'">
                        </button>
                    </template>
                    <p x-show="ubDirOpciones.filter(o => o.toLowerCase().includes(ubDirSearch.toLowerCase())).length === 0"
                       style="text-align:center; padding:16px; font-size:12px; color:#9B93E0; margin:0;">Sin resultados</p>
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
