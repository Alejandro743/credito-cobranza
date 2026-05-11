<div>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Gestión de Pagos</h2>
        <p>Registrá y seguí los pagos de cuotas</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pedidos->count() }} pedido{{ $pedidos->count() !== 1 ? 's' : '' }}</span>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:280px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
                   style="padding-left:32px;width:100%;">
        </div>

        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <button wire:click="$set('filtro','todos')" class="ds-btn ds-btn-sm" style="{{ $filtro==='todos' ? 'background:#6D8196;color:#fff;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Todos</button>
            <button wire:click="$set('filtro','vigente')" class="ds-btn ds-btn-sm" style="{{ $filtro==='vigente' ? 'background:#FCD34D;color:#78350F;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Vigente</button>
            <button wire:click="$set('filtro','en_mora')" class="ds-btn ds-btn-sm" style="{{ $filtro==='en_mora' ? 'background:#FCA5A5;color:#991B1B;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">En Mora</button>
            <button wire:click="$set('filtro','pagado')" class="ds-btn ds-btn-sm" style="{{ $filtro==='pagado' ? 'background:#6ee7b7;color:#065F46;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Pagado</button>
        </div>

        <button wire:click="irUpload" class="ds-btn ds-btn-primary ds-btn-sm" style="margin-left:auto;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Subir Pagos
        </button>
    </div>

    <div style="overflow-x:auto;">
    <table style="min-width:780px;">
        <thead>
            <tr>
                <th class="ds-sticky-col" style="padding:0;height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #CBCBCB;display:flex;align-items:center;justify-content:center;">Pedido</div>
                        <div style="flex:1;padding:10px 12px;display:flex;align-items:center;justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th>Estado</th>
                <th>Versión</th>
                <th style="text-align:right;">Total plan</th>
                <th style="text-align:right;">Pagado</th>
                <th style="text-align:right;">Saldo pend.</th>
                <th style="text-align:center;">Cuotas pend.</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $ped)
            @php
                $plan      = $ped->planPago;
                $pagado    = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
                $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#F7F7F0','cl'=>'#6D8196','lb'=>'—'];
            @endphp
            <tr wire:key="ped-{{ $ped->id }}" wire:click="seleccionarPedido({{ $ped->id }})" style="cursor:pointer;">
                <td class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $ped->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $ped->cliente->nombre_completo }}</p>
                            @if($ped->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $ped->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="text-align:center;">
                    <span class="ds-badge" style="background:{{ $efBadge['bg'] }};color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
                </td>
                <td style="text-align:center;">
                    <span class="ds-badge ds-badge-aprobado">v{{ $plan?->version ?? 1 }}</span>
                </td>
                <td style="text-align:right;font-family:monospace;font-size:12px;font-weight:600;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</td>
                <td style="text-align:right;font-family:monospace;font-size:12px;font-weight:600;color:#059669;">Bs. {{ number_format($pagado, 2) }}</td>
                <td style="text-align:right;font-family:monospace;font-weight:700;color:#DC2626;font-size:12px;">Bs. {{ number_format($pendiente, 2) }}</td>
                <td style="text-align:center;font-weight:700;">{{ $nPend }}</td>
                <td style="text-align:center;">
                    <button wire:click.stop="seleccionarPedido({{ $ped->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p>{{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos con cuotas pendientes.' }}</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pedidoDetalle)
@php
    $ped       = $pedidoDetalle;
    $plan      = $ped->planPago;
    $cuotas    = $plan?->cuotas->where('numero', '>', 0)->sortBy('numero') ?? collect();
    $pagado    = $cuotas->where('estado','pagado')->sum('monto');
    $pendiente = $cuotas->where('estado','!=','pagado')->sum('monto');
    $nPend     = $cuotas->where('estado','!=','pagado')->count();
    $montoSel  = $cuotas->whereIn('id', $cuotasSeleccionadas)->sum('monto');
    $planLabel = ($plan?->version ?? 1) > 1 ? 'Reprogramación: V' . $plan->version : 'Plan Original';
    $efBadge   = $plan?->estadoFinancieroBadge ?? ['bg'=>'#F7F7F0','cl'=>'#6D8196','lb'=>'Sin plan'];
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:60px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $ped->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $ped->numero }} — Pago Manual</p>
        </div>
        <span class="ds-badge" style="background:{{ $efBadge['bg'] }};color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
    </div>

    {{-- Resumen --}}
    <div class="ds-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:14px;">
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:16px;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</div>
            <div class="ds-stat-label">Total plan</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:16px;color:#059669;">Bs. {{ number_format($pagado, 2) }}</div>
            <div class="ds-stat-label">Pagado</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:16px;color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</div>
            <div class="ds-stat-label">Pendiente</div>
        </div>
    </div>

    {{-- Plan de pagos --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Plan de Pagos</p>
            <div style="display:flex;gap:6px;align-items:center;">
                <span class="ds-badge ds-badge-cerrado">{{ $planLabel }}</span>
                <span class="ds-badge ds-badge-aprobado">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
                <span style="font-size:11px;color:#CBCBCB;">{{ $nPend }} pend. · Sel: <strong style="color:#4A4A4A;">{{ count($cuotasSeleccionadas) }}</strong></span>
            </div>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:44px;text-align:center;">✓</th>
                    <th>Cuota</th>
                    <th style="text-align:right;">Monto</th>
                    <th style="text-align:center;">Vencimiento</th>
                    <th style="text-align:center;">Fecha pago</th>
                    <th style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                @php
                    $esPagada  = $c->estado === 'pagado';
                    $esSelec   = in_array($c->id, $cuotasSeleccionadas);
                    $badgeCuota = $c->estadoFinancieroBadge;
                    $diffDias  = ($c->fecha_vencimiento && $c->fecha_pago)
                        ? (int) $c->fecha_vencimiento->diffInDays($c->fecha_pago, false) : null;
                    $lowerUnpaid = $cuotas->where('numero', '<', $c->numero)->where('estado', '!=', 'pagado');
                    $bloqueada   = !$esPagada && !$esSelec && !$lowerUnpaid->every(fn($x) => in_array($x->id, $cuotasSeleccionadas));
                    $clickable   = !$esPagada && !$bloqueada;
                @endphp
                <tr wire:key="c-{{ $c->id }}"
                    style="{{ $esPagada ? 'opacity:0.5;background:#f9fafb;' : ($esSelec ? 'background:#FFFEF5;' : ($bloqueada ? 'opacity:0.5;' : '')) }}cursor:{{ $clickable ? 'pointer' : 'default' }};"
                    {{ $clickable ? "wire:click=toggleCuota({$c->id})" : '' }}>
                    <td style="text-align:center;">
                        @if($esPagada)
                            {{-- pagada: sin checkbox --}}
                        @elseif($bloqueada)
                        <svg style="width:14px;height:14px;color:#CBCBCB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        @else
                        <div style="width:18px;height:18px;border-radius:3px;border:2px solid {{ $esSelec ? '#6D8196' : '#CBCBCB' }};background:{{ $esSelec ? '#6D8196' : '#fff' }};display:inline-flex;align-items:center;justify-content:center;">
                            @if($esSelec)<svg style="width:11px;height:11px;" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@endif
                        </div>
                        @endif
                    </td>
                    <td style="font-weight:600;">Cuota {{ $c->numero }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="text-align:center;color:#CBCBCB;">{{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                    <td style="text-align:center;">
                        @if($c->fecha_pago)
                        <span style="font-weight:600;color:#059669;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                        @if($diffDias !== null)
                        <br><span style="font-size:10px;font-weight:600;color:{{ $diffDias > 0 ? '#DC2626' : ($diffDias < 0 ? '#059669' : '#B45309') }};">
                            {{ $diffDias > 0 ? '+' . $diffDias . 'd mora' : ($diffDias < 0 ? abs($diffDias) . 'd antes' : 'a tiempo') }}
                        </span>
                        @endif
                        @else
                        <span style="color:#CBCBCB;">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span class="ds-badge" style="background:{{ $badgeCuota['bg'] }};color:{{ $badgeCuota['cl'] }};">{{ $badgeCuota['lb'] }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="ds-empty"><p>Sin cuotas</p></div></td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">{{ $cuotas->where('estado','pagado')->count() }} pag. · {{ $nPend }} pend.</td>
                    <td colspan="4" style="text-align:right;">
                        @if(count($cuotasSeleccionadas) > 0)
                        <span style="font-weight:700;color:#6D8196;">Seleccionado: Bs. {{ number_format($montoSel, 2) }}</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    @if(count($cuotasSeleccionadas) > 0)
    <div style="background:#FFFFE3;border:1px solid #6D8196;border-radius:8px;padding:14px 18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">{{ count($cuotasSeleccionadas) }} cuota{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }} seleccionada{{ count($cuotasSeleccionadas) !== 1 ? 's' : '' }}</p>
            <p style="font-size:12px;color:#6D8196;margin:2px 0 0;">Total a registrar: <strong style="font-family:monospace;">Bs. {{ number_format($montoSel, 2) }}</strong></p>
        </div>
        <button wire:click="registrarPago" wire:loading.attr="disabled" class="ds-btn ds-btn-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="registrarPago">Registrar pago</span>
            <span wire:loading wire:target="registrarPago">Registrando...</span>
        </button>
    </div>
    @else
    <div style="text-align:center;padding:14px;background:#F7F7F0;border-radius:8px;color:#CBCBCB;font-size:13px;">
        Seleccioná una o más cuotas pendientes para registrar el pago.
    </div>
    @endif

</div>

{{-- ══ UPLOAD ══ --}}
@elseif($mode === 'upload')
<div style="max-width:680px;margin:0 auto;padding-bottom:60px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">Pagos Masivos</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;">Subí un archivo CSV o Excel con el detalle de pagos</p>
        </div>
    </div>

    {{-- Formato esperado --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;">
            <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0;">Formato del Archivo</p>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>transaccion</th>
                    <th>fecha_de_pago</th>
                    <th>numero_de_pedido</th>
                    <th>numero_de_cuota</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>TXN-001</td>
                    <td>29/04/2026</td>
                    <td style="font-family:monospace;">PED-000003</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>TXN-001</td>
                    <td>29/04/2026</td>
                    <td style="font-family:monospace;">PED-000003</td>
                    <td>4</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

    {{-- Archivo --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:20px;margin-bottom:14px;">
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 12px;">Seleccioná el archivo <span style="font-weight:400;">(CSV o Excel, máx. 10 MB)</span></p>
        <label style="display:block;cursor:pointer;">
            <input type="file" wire:model="archivo" accept=".csv,.xlsx,.xls" style="display:none;" id="pm-file-input">
            <div style="border:2px dashed #CBCBCB;border-radius:8px;padding:28px;text-align:center;background:#FFFFE3;"
                 onclick="document.getElementById('pm-file-input').click()">
                <svg style="width:32px;height:32px;margin:0 auto 8px;display:block;" fill="none" stroke="#CBCBCB" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                @if($archivo)
                <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">{{ $archivo->getClientOriginalName() }}</p>
                <p style="font-size:11px;color:#CBCBCB;margin:4px 0 0;">{{ number_format($archivo->getSize() / 1024, 1) }} KB — clic para cambiar</p>
                @else
                <p style="font-size:13px;font-weight:600;color:#4A4A4A;margin:0;">Seleccioná el archivo</p>
                <p style="font-size:11px;color:#CBCBCB;margin:4px 0 0;">CSV o Excel · máx. 10 MB</p>
                @endif
            </div>
        </label>
        @error('archivo')<p class="ds-form-error">{{ $message }}</p>@enderror
    </div>

    @if($archivo)
    <button wire:click="procesarArchivo" wire:loading.attr="disabled" class="ds-btn ds-btn-primary" style="width:100%;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        <span wire:loading.remove wire:target="procesarArchivo">Procesar archivo</span>
        <span wire:loading wire:target="procesarArchivo">Procesando...</span>
    </button>
    @endif

</div>

{{-- ══ UPLOAD RESULTADO ══ --}}
@elseif($mode === 'upload-resultado')
@php
    $totalOk  = count($resultadosOk);
    $totalErr = count($resultadosError);
@endphp
<div style="max-width:780px;margin:0 auto;padding-bottom:60px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">Resultado de la carga</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;">
                <span style="color:#059669;font-weight:700;">✓ {{ $totalOk }} cuota{{ $totalOk !== 1 ? 's' : '' }} aplicada{{ $totalOk !== 1 ? 's' : '' }}</span>
                @if($totalErr > 0) &nbsp;·&nbsp; <span style="color:#DC2626;font-weight:700;">✗ {{ $totalErr }} línea{{ $totalErr !== 1 ? 's' : '' }} con error</span>@endif
            </p>
        </div>
    </div>

    @if($totalOk > 0)
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Cuotas Aplicadas</p>
            <span class="ds-badge" style="background:#DCFCE7;color:#059669;">{{ $totalOk }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Transacción</th>
                    <th>Pedido</th>
                    <th style="text-align:center;">Cuota</th>
                    <th style="text-align:center;">Fecha pago</th>
                    <th style="text-align:right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultadosOk as $r)
                <tr>
                    <td style="font-family:monospace;font-size:11px;">{{ $r['transaccion'] }}</td>
                    <td style="font-family:monospace;font-size:11px;font-weight:700;color:#6D8196;">{{ $r['pedido'] }}</td>
                    <td style="text-align:center;">Cuota {{ $r['cuota'] }}</td>
                    <td style="text-align:center;color:#CBCBCB;">{{ \Carbon\Carbon::parse($r['fecha'])->format('d/m/Y') }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:700;color:#059669;">Bs. {{ number_format($r['monto'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

    @if($totalErr > 0)
    <div style="background:#fff;border:1px solid #FCA5A5;border-radius:8px;overflow:hidden;">
        <div style="padding:12px 16px;border-bottom:1px solid #FCA5A5;display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:700;color:#DC2626;margin:0;">Líneas No Aplicadas</p>
            <span class="ds-badge ds-badge-danger">{{ $totalErr }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">Fila</th>
                    <th>Transacción</th>
                    <th>Pedido</th>
                    <th style="text-align:center;">Cuota</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultadosError as $e)
                <tr>
                    <td style="text-align:center;color:#CBCBCB;">{{ $e['fila'] }}</td>
                    <td style="font-family:monospace;font-size:11px;">{{ $e['transaccion'] }}</td>
                    <td style="font-family:monospace;font-size:11px;">{{ $e['pedidoNum'] }}</td>
                    <td style="text-align:center;">{{ $e['cuotaNum'] }}</td>
                    <td style="font-weight:600;color:#DC2626;font-size:11px;">{{ $e['motivo'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif

</div>
@endif

</div>
