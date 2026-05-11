<div>

@php
$filtros = ['' => 'Todos', 'aprobado' => 'Aprobados', 'rechazado' => 'Rechazados', 'cerrado' => 'Cerrados'];
$badgeEstado = [
    'aprobado'  => 'background:#FFFFE3;color:#6D8196;border:1px solid #6D8196;',
    'rechazado' => 'background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;',
    'cerrado'   => 'background:#F4F4F4;color:#4A4A4A;border:1px solid #CBCBCB;',
];
@endphp

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#D1FAE5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Gestión de Crédito</h2>
        <p>Aprobados, rechazados y cerrados</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $pedidos->total() }} resultado{{ $pedidos->total() !== 1 ? 's' : '' }}</span>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:300px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
                   style="padding-left:32px;width:100%;">
        </div>
        {{-- Filtros de estado --}}
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            @foreach($filtros as $valor => $label)
            <button wire:click="$set('filtroEstado', '{{ $valor }}')"
                    style="padding:5px 12px;font-size:11px;font-weight:600;cursor:pointer;white-space:nowrap;
                           border:1.5px solid {{ $filtroEstado === $valor ? '#6D8196' : '#CBCBCB' }};
                           background:{{ $filtroEstado === $valor ? '#FFFFE3' : 'transparent' }};
                           color:{{ $filtroEstado === $valor ? '#6D8196' : '#CBCBCB' }};">
                {{ $label }}
            </button>
            @endforeach
        </div>
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
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Total Bs.</th>
                <th>Pagado Bs.</th>
                <th>Saldo Bs.</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $p)
            @php
                $sinImpacto = $p->estado === 'cerrado' && !($p->cierre?->motivoCierre?->afecta_mora);
                $esRechazado = $p->estado === 'rechazado';
                $pagado = ($sinImpacto || $esRechazado) ? 0 : ($p->total_pagado ?? 0);
                $saldo  = ($sinImpacto || $esRechazado) ? 0 : max(0, $p->total_pagar - $pagado);
                $estiloEstado = $badgeEstado[$p->estado] ?? 'background:#F4F4F4;color:#4A4A4A;border:1px solid #CBCBCB;';
            @endphp
            <tr wire:key="a-{{ $p->id }}">
                <td class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="text-align:center;">
                    <span class="ds-badge" style="{{ $estiloEstado }}">{{ ucfirst($p->estado) }}</span>
                </td>
                <td style="text-align:center;">{{ $p->vendedor->user->name ?? '—' }}</td>
                <td style="text-align:center;">{{ $p->updated_at->format('d/m/Y') }}</td>
                <td style="text-align:center;font-weight:700;color:#4A4A4A;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</td>
                <td style="text-align:center;font-weight:700;color:#10B981;">{{ number_format($pagado, 2) }}</td>
                <td style="text-align:center;font-weight:700;color:{{ $saldo > 0 ? '#DC2626' : '#4A4A4A' }};">{{ number_format($saldo, 2) }}</td>
                <td style="text-align:center;">
                    <button wire:click="ver({{ $p->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Sin resultados</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($pedidos->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- ══ DETAIL ══ --}}
@elseif ($mode === 'detail' && $pedidoDetalle)
@php
    $p = $pedidoDetalle;
    $plan = $p->planPago;
    $cerrado = $p->estado === 'cerrado';
    $aprobado = $p->estado === 'aprobado';
    $tieneCuotasPagadas = $p->planes->flatMap(fn($pl) => $pl->cuotas)->where('numero', '>', 0)->where('estado', 'pagado')->isNotEmpty()
                       || $p->planes->count() > 1;
@endphp

<div style="max-width:680px;margin:0 auto;">

    <div style="margin-bottom:16px;">
        <button wire:click="backToList" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Volver al listado
        </button>
    </div>

    @include('livewire.credito.partials.pedido-detail')

    {{-- ══ ESTADO: CERRADO ══ --}}
    @if ($cerrado)
    @php $cierre = $p->cierre; @endphp

    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px;margin-top:16px;">
        <p style="font-size:10px;font-weight:700;color:#CBCBCB;text-transform:uppercase;letter-spacing:.5px;margin:0 0 12px;">Registro de Cierre</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:12px;">
            <div>
                <span class="ds-form-label">Motivo</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $cierre?->motivoCierre?->nombre ?? '—' }}</span>
                @if($cierre?->motivoCierre?->afecta_mora)
                <span class="ds-badge ds-badge-danger" style="margin-left:6px;">Afecta indicadores</span>
                @endif
            </div>
            <div>
                <span class="ds-form-label">Cerrado por</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $cierre?->cerradoPor?->name ?? '—' }}</span>
                <span style="display:block;font-size:11px;color:#CBCBCB;">{{ $cierre?->created_at?->format('d/m/Y H:i') }}</span>
            </div>
            @if($cierre?->observacion)
            <div style="grid-column:1/-1;">
                <span class="ds-form-label">Observación</span>
                <span style="color:#4A4A4A;">{{ $cierre->observacion }}</span>
            </div>
            @endif
        </div>
    </div>

    @if (!$confirmandoReversion)
    <div style="margin-top:12px;display:flex;justify-content:flex-start;">
        <button wire:click="$set('confirmandoReversion', true)" class="ds-btn ds-btn-warning ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Revertir cierre
        </button>
    </div>
    @else
    <div class="ds-confirm-panel warning">
        <p class="ds-confirm-panel-title">Motivo de la reversión</p>
        <textarea wire:model="motivoReversion" rows="3" placeholder="Explicá por qué se revierte el cierre..."
                  style="width:100%;display:block;"></textarea>
        @error('motivoReversion')<p class="ds-form-error">{{ $message }}</p>@enderror
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoReversion', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="revertir" class="ds-btn ds-btn-warning ds-btn-sm">Confirmar Reversión</button>
        </div>
    </div>
    @endif

    {{-- ══ ESTADO: APROBADO / RECHAZADO ══ --}}
    @elseif (!$confirmandoRechazo && !$confirmandoCierre)
    <div style="display:flex;align-items:center;gap:10px;margin-top:12px;flex-wrap:wrap;">

        @if (!$tieneCuotasPagadas)
        <button wire:click="devolverRevision"
                wire:confirm="¿Devolvés este pedido a Revisión? La nota de rechazo se eliminará."
                class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            A Revisión
        </button>
        @endif

        @if ($tieneCuotasPagadas && $aprobado)
        <button wire:click="$set('confirmandoCierre', true)" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/>
            </svg>
            Cerrar Crédito
        </button>
        @endif

        <div style="flex:1;"></div>

        @if (!$tieneCuotasPagadas && $aprobado)
        <button wire:click="$set('confirmandoRechazo', true)" class="ds-btn ds-btn-danger ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Rechazar
        </button>
        @elseif ($p->estado === 'rechazado')
        <button wire:click="aprobar"
                wire:confirm="¿Confirmás la aprobación? La nota de rechazo se eliminará."
                class="ds-btn ds-btn-success">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Aprobar
        </button>
        @endif
    </div>

    @elseif ($confirmandoRechazo)
    <div class="ds-confirm-panel danger">
        <p class="ds-confirm-panel-title">Motivo del rechazo</p>
        <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..."
                  style="width:100%;display:block;"></textarea>
        @error('notaRechazo')<p class="ds-form-error">{{ $message }}</p>@enderror
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoRechazo', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="rechazar" class="ds-btn ds-btn-danger ds-btn-sm">Confirmar Rechazo</button>
        </div>
    </div>

    @elseif ($confirmandoCierre)
    <div class="ds-confirm-panel neutral">
        <p class="ds-confirm-panel-title" style="color:#4A4A4A;">Cerrar Crédito</p>
        <div class="ds-form-group">
            <label class="ds-form-label">Motivo *</label>
            <select wire:model="motivoCierreId" style="width:100%;">
                <option value="">Seleccioná un motivo...</option>
                @foreach($motivosCierre as $m)
                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
            @error('motivoCierreId')<p class="ds-form-error">{{ $message }}</p>@enderror
        </div>
        <div class="ds-form-group">
            <label class="ds-form-label">Observación <span style="font-weight:400;text-transform:none;">(opcional)</span></label>
            <textarea wire:model="observacionCierre" rows="3" placeholder="Detalle adicional sobre el cierre..."
                      style="width:100%;display:block;"></textarea>
        </div>
        <div class="ds-confirm-panel-actions">
            <button wire:click="$set('confirmandoCierre', false)" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="cerrar" class="ds-btn ds-btn-primary ds-btn-sm">Confirmar Cierre</button>
        </div>
    </div>
    @endif

    {{-- Historial de cierres --}}
    @if($p->cierres->isNotEmpty())
    <div x-data="{ abierto: false }" style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-top:16px;">
        <button @click="abierto = !abierto"
                style="width:100%;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;background:#F7F7F0;border:none;cursor:pointer;text-align:left;">
            <div style="display:flex;align-items:center;gap:8px;">
                <svg style="width:14px;height:14px;color:#6D8196;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="font-size:13px;font-weight:700;color:#4A4A4A;">Historial de cierres</span>
                <span class="ds-badge ds-badge-inactive">{{ $p->cierres->count() }}</span>
            </div>
            <svg :class="abierto ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" style="color:#CBCBCB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="abierto" x-collapse>
            <div style="padding:12px 16px;display:flex;flex-direction:column;gap:10px;">
            @foreach($p->cierres as $histCierre)
            @php $revertido = $histCierre->estaRevertido(); @endphp

            @if($revertido)
            <div style="padding:12px 14px;border-radius:6px;border:1px solid #FCD34D;background:#FFFBEB;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px;">
                    <span style="font-size:11px;font-weight:700;color:#B45309;">Reversión de cierre</span>
                    <span class="ds-badge ds-badge-pending">REVERTIDO</span>
                </div>
                <div style="font-size:11px;color:#B45309;">
                    <strong>Revertido por:</strong> {{ $histCierre->revertidoPor?->name ?? '—' }}<br>
                    <strong>Fecha:</strong> {{ $histCierre->revertido_at->format('d/m/Y H:i') }}<br>
                    <strong>Motivo:</strong> {{ $histCierre->motivo_reversion }}
                </div>
            </div>
            @endif

            <div style="padding:12px 14px;border-radius:6px;border:1px solid #CBCBCB;background:#F7F7F0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:8px;">
                    <div>
                        <span style="font-size:12px;font-weight:700;color:#4A4A4A;">{{ $histCierre->motivoCierre?->nombre ?? '—' }}</span>
                        @if($histCierre->motivoCierre?->afecta_mora)
                        <span class="ds-badge ds-badge-danger" style="margin-left:6px;">Afecta indicadores</span>
                        @endif
                    </div>
                    <span class="ds-badge {{ $revertido ? 'ds-badge-anulado' : 'ds-badge-cerrado' }}">CIERRE{{ $revertido ? ' (anulado)' : '' }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:11px;color:#CBCBCB;">
                    <div>
                        <strong style="color:#4A4A4A;">Cerrado por:</strong> {{ $histCierre->cerradoPor?->name ?? '—' }}<br>
                        <strong style="color:#4A4A4A;">Fecha:</strong> {{ $histCierre->created_at->format('d/m/Y H:i') }}
                    </div>
                    @if($histCierre->observacion)
                    <div>
                        <strong style="color:#4A4A4A;">Observación:</strong> {{ $histCierre->observacion }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@endif
</div>
