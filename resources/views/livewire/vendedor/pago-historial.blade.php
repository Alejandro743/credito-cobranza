<div>
<style>
.vph-badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10px; font-weight:600; }
</style>

<div class="p-4 sm:p-6">

{{-- ══ LIST ══ --}}
@if($mode === 'list')
@php $theadStyle = 'background:#FEF3C7; color:#92400E; font-size:10px; font-weight:600; letter-spacing:0.5px;'; @endphp

<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    <div style="position:relative; flex-shrink:0; width:260px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px;"
             viewBox="0 0 24 24" fill="none" stroke="#FCD34D" stroke-width="2" stroke-linecap="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, nombre o pedido..."
               style="width:100%; padding:7px 10px 7px 30px; border:0.5px solid #FCD34D; border-radius:8px;
                      background:#FFFBEB; font-size:12px; outline:none;" />
    </div>
    <span style="font-size:12px; color:#9ca3af;">{{ $pagos->total() }} pago{{ $pagos->total() !== 1 ? 's' : '' }} registrado{{ $pagos->total() !== 1 ? 's' : '' }}</span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div style="overflow-x:auto;">
    <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px; min-width:650px;">
        <thead style="{{ $theadStyle }}">
            <tr>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FDE68A; width:150px;">Código</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FDE68A; width:120px;">Pedido</th>
                <th style="padding:8px 12px; text-align:left; font-weight:700; border:0.5px solid #FDE68A;">Cliente</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A; width:80px;">Cuotas</th>
                <th style="padding:8px 12px; text-align:right; font-weight:700; border:0.5px solid #FDE68A; width:130px;">Monto total</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A; width:100px;">Fecha</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A; width:90px;">Estado</th>
                <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A; width:60px;">Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pg)
            @php $esAnulado = $pg->estado === 'anulado'; @endphp
            <tr wire:key="pg-{{ $pg->id }}" class="hover:bg-amber-50 transition-colors" style="cursor:pointer; {{ $esAnulado ? 'opacity:0.55;' : '' }}"
                wire:click="verPago({{ $pg->id }})">
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#92400E; font-weight:700; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}">
                    {{ $pg->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; font-family:monospace; font-size:11px; color:#374151; font-weight:600;">
                    {{ $pg->pedido->numero }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb;">
                    <p style="font-weight:600; font-size:13px; color:#92400E; margin:0;">{{ $pg->pedido->cliente->nombre_completo }}</p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;">CI: {{ $pg->pedido->cliente->ci ?? '—' }}</p>
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-weight:700; color:#374151;">
                    {{ $pg->cantidad_cuotas }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:right; font-family:monospace; font-weight:700; color:{{ $esAnulado ? '#9ca3af' : '#92400E' }}; font-size:12px;">
                    Bs. {{ number_format($pg->monto_total, 2) }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#374151;">
                    {{ $pg->created_at->format('d/m/Y') }}
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                    @if($esAnulado)
                    <span class="vph-badge" style="background:#FEF2F2; color:#B91C1C;">Anulado</span>
                    @else
                    <span class="vph-badge" style="background:#FEF3C7; color:#92400E;">Activo</span>
                    @endif
                </td>
                <td style="padding:8px 12px; border:0.5px solid #e5e7eb; text-align:center; width:48px;">
                    <button wire:click.stop="verPago({{ $pg->id }})" title="Ver detalle"
                            class="p-1.5 rounded-lg hover:bg-amber-50 transition-colors" style="color:#92400E;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-14 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Sin pagos registrados.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pagos->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $pagos->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pagoDetalle)
@php
    $pg          = $pagoDetalle;
    $cuotas      = $cuotasDetalle;
    $pgVersion   = $pg->planPago?->version ?? 1;
    $pgPlanLabel = $pgVersion > 1 ? 'Reprogramación: V' . $pgVersion : 'Plan Original';
    $esAnulado   = $pg->estado === 'anulado';
    $hBg         = $esAnulado ? '#FEF2F2' : '#FFFBEB';
    $hBorder     = $esAnulado ? '#CBCBCB' : '#FCD34D';
    $hColor      = $esAnulado ? '#B91C1C' : '#92400E';
    $hColorDark  = $esAnulado ? '#991B1B' : '#78350F';
@endphp
<div class="max-w-2xl mx-auto" style="padding-bottom:40px;">

    {{-- Cabecera --}}
    <div style="background:{{ $hBg }}; border:1px solid {{ $hBorder }}; border-radius:14px; padding:16px 18px; margin:0 0 20px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
            <button wire:click="volver"
                    style="display:inline-flex; align-items:center; gap:5px; background:#fff; border:1.5px solid {{ $hBorder }}; border-radius:20px; padding:5px 12px 5px 8px; cursor:pointer; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <svg width="14" height="14" fill="none" stroke="{{ $hColor }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 18l-6-6 6-6"/>
                </svg>
                <span style="font-size:11px; font-weight:700; color:{{ $hColor }};">Historial</span>
            </button>
            <h1 style="flex:1; text-align:center; font-size:22px; font-weight:800; color:{{ $hColorDark }}; letter-spacing:-0.3px; margin:0;">HISTORIAL DE PAGO</h1>
            <div style="width:70px; flex-shrink:0;"></div>
        </div>
        <div style="text-align:center; margin-top:6px;">
            <div style="font-family:monospace; font-size:15px; font-weight:800; color:{{ $hColor }};">{{ $pg->pedido->numero }}</div>
            <div style="margin-top:4px; display:flex; align-items:center; justify-content:center; gap:4px;">
                <span style="font-family:monospace; font-size:17px; font-weight:800; color:{{ $hColor }};">{{ $pg->numero }}</span>
                @if($esAnulado)
                <span class="vph-badge" style="background:#FEF2F2; color:#B91C1C; font-size:12px; font-weight:800; letter-spacing:0.06em; padding:3px 10px;">(ANULADO)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- DATOS DEL CLIENTE --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#92400E;">Datos del Cliente</span>
        <div style="flex:1; height:1px; background:#FCD34D;"></div>
    </div>
    <div x-data="{ modal: false }" class="bg-white overflow-hidden mb-4" style="border:0.5px solid #FDE68A; border-radius:10px; padding:10px 12px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <span style="font-size:9px; font-weight:600; color:#92400E; text-transform:uppercase; letter-spacing:0.04em;">Cliente</span>
            <button @click="modal = true"
                    style="display:inline-flex; align-items:center; gap:4px; background:#FEF3C7; border:none; border-radius:6px; padding:2px 8px; cursor:pointer;">
                <svg width="10" height="10" fill="none" stroke="#92400E" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span style="font-size:9px; font-weight:600; color:#92400E;">Ver Cliente</span>
            </button>
        </div>
        <span style="font-size:13px; font-weight:600; color:#78350F; display:block;">
            {{ $pg->pedido->cliente->ci ? $pg->pedido->cliente->ci . ' — ' : '' }}{{ $pg->pedido->cliente->nombre_completo }}
        </span>

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
                 style="background:#FFFBEB; border-radius:18px; width:100%; max-width:420px; overflow:hidden; position:relative; max-height:90vh; overflow-y:auto;">
                <button @click="modal = false"
                        style="position:absolute; top:14px; right:14px; width:28px; height:28px; border-radius:8px; background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 4px rgba(0,0,0,0.1); z-index:1;">
                    <svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div style="padding:20px 18px 18px;">
                    @php
                    $cli  = $pg->pedido->cliente;
                    $fSty = 'background:#fff; border-radius:10px; padding:8px 10px; display:flex; align-items:center; gap:8px;';
                    $iBox = 'width:32px; height:32px; border-radius:8px; background:#FEF3C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                    $vClr = 'font-size:11px; font-weight:700; color:#78350F; margin:0;';
                    $lClr = 'font-size:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#FCD34D; margin-bottom:1px;';
                    @endphp
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                        <span style="font-size:12px; font-weight:700; color:#92400E; white-space:nowrap;">Datos del Cliente</span>
                        <div style="flex:1; height:1px; background:#FCD34D;"></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                            <div style="min-width:0;"><p style="{{ $lClr }}">Nombre</p><p style="{{ $vClr }} word-break:break-word;">{{ $cli->nombre_completo }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div>
                            <div><p style="{{ $lClr }}">CI</p><p style="{{ $vClr }} font-family:monospace;">{{ $cli->ci ?: '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                            <div><p style="{{ $lClr }}">Teléfono</p><p style="{{ $vClr }}">{{ $cli->telefono ?: '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                            <div><p style="{{ $lClr }}">Correo</p><p style="{{ $vClr }} word-break:break-all;">{{ $cli->correo ?: '—' }}</p></div>
                        </div>
                    </div>
                    @if($cli->ciudad || $cli->direccion)
                    <div style="display:flex; align-items:center; gap:8px; margin:8px 0;">
                        <span style="font-size:11px; font-weight:700; color:#92400E; white-space:nowrap;">Dirección</span>
                        <div style="flex:1; height:1px; background:#FCD34D;"></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        @if($cli->ciudad)
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <div><p style="{{ $lClr }}">Ciudad</p><p style="{{ $vClr }}">{{ strtoupper($cli->ciudad) }}</p></div>
                        </div>
                        @endif
                        @if($cli->direccion)
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="14" height="14" fill="none" stroke="#92400E" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                            <div><p style="{{ $lClr }}">Dirección</p><p style="{{ $vClr }} word-break:break-word;">{{ $cli->direccion }}</p></div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- CUOTAS --}}
    <div style="display:flex; align-items:center; gap:8px; margin:4px 0 10px;">
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:{{ $esAnulado ? '#B91C1C' : '#92400E' }};">
            {{ $esAnulado ? 'Cuotas Anuladas' : 'Cuotas Pagadas' }}
        </span>
        <div style="flex:1; height:1px; background:{{ $esAnulado ? '#CBCBCB' : '#FCD34D' }};"></div>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#FFFBEB; color:#92400E; border:1px solid #FCD34D;">{{ $pgPlanLabel }}</span>
        <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:20px; background:#FEF3C7; color:#92400E;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div style="overflow-x:auto;">
        <table style="border-collapse:separate; border-spacing:0; width:100%; font-size:13px;">
            <thead style="background:#FEF3C7; color:#92400E; font-size:10px; font-weight:600; letter-spacing:0.5px;">
                <tr>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A; width:90px;">Cuota</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A;">Monto</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A;">Vencimiento</th>
                    <th style="padding:8px 12px; text-align:center; font-weight:700; border:0.5px solid #FDE68A;">Fecha pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                <tr wire:key="dc-{{ $c->id }}">
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        <span style="font-size:12px; font-weight:600; color:#374151;">Cuota {{ $c->numero }}</span>
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-family:monospace; font-weight:700; color:#92400E;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center; font-size:12px; color:#6b7280;">
                        {{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}
                    </td>
                    <td style="padding:9px 12px; border:0.5px solid #e5e7eb; text-align:center;">
                        @if($c->fecha_pago)
                            <span style="font-size:12px; font-weight:600; color:#92400E;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                        @else
                            <span style="font-size:12px; color:#9ca3af;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Sin cuotas</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background:{{ $esAnulado ? '#FEF2F2' : '#FFFBEB' }};">
                    <td colspan="4" style="padding:11px 16px; border-top:2px solid {{ $esAnulado ? '#CBCBCB' : '#FDE68A' }}; text-align:center;">
                        <span style="font-size:13px; font-weight:700; color:#374151;">Total: </span>
                        <span style="font-family:monospace; font-size:15px; font-weight:800; color:{{ $esAnulado ? '#B91C1C' : '#92400E' }}; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}">Bs. {{ number_format($pg->monto_total, 2) }}</span>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    {{-- Info anulación --}}
    @if($esAnulado)
    <div style="background:#FEF2F2; border:0.5px solid #CBCBCB; border-radius:10px; padding:10px 14px; margin-top:16px; display:flex; align-items:center; gap:10px;">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="#B91C1C" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p style="font-size:11px; font-weight:700; color:#B91C1C; margin:0;">Pago anulado</p>
            <p style="font-size:11px; color:#6b7280; margin:0;">
                Por {{ $pg->anuladoPor->name ?? '—' }} el {{ $pg->anulado_at?->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    @endif

</div>
@endif

</div>
</div>
