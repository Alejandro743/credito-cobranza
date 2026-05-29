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
                <td data-label="Pedido / Cliente" class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:110px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $p->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
                            @if($p->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td data-label="Estado" style="text-align:center;">
                    <span class="ds-badge" style="{{ $estiloEstado }}">{{ ucfirst($p->estado) }}</span>
                </td>
                <td data-label="Vendedor" style="text-align:center;">{{ $p->vendedor->user->name ?? '—' }}</td>
                <td data-label="Fecha" style="text-align:center;">{{ $p->updated_at->format('d/m/Y') }}</td>
                <td data-label="Total Bs." style="text-align:center;font-weight:700;color:#4A4A4A;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</td>
                <td data-label="Pagado Bs." style="text-align:center;font-weight:700;color:#10B981;">{{ number_format($pagado, 2) }}</td>
                <td data-label="Saldo Bs." style="text-align:center;font-weight:700;color:{{ $saldo > 0 ? '#DC2626' : '#4A4A4A' }};">{{ number_format($saldo, 2) }}</td>
                <td data-label="" style="text-align:center;">
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

<style>
.am-act-wrap { display:flex; flex-direction:column; gap:8px; margin-top:16px; }
@@media (min-width:640px) { .am-act-wrap { flex-direction:row; } }
</style>

<div style="max-width:900px;margin:0 auto;">

    @include('livewire.credito.partials.pedido-detail', [
        'p'        => $p,
        'plan'     => $plan,
        'aprobado' => $aprobado,
        'editable' => false,
    ])

    {{-- ══ ESTADO: CERRADO ══ --}}
    @if ($cerrado)
    @php $cierre = $p->cierre; @endphp

    <div style="background:#fff; border:1px solid #C4B5FD; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/></svg>
            <span style="font-size:12px; font-weight:700; color:#534AB7; letter-spacing:0.05em; white-space:nowrap;">Registro de Cierre</span>
            <div style="flex:1; height:1.5px; background:#EDE9FE;"></div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:12px;">
            <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Motivo</p>
                <span style="font-weight:600; color:#3C3489;">{{ $cierre?->motivoCierre?->nombre ?? '—' }}</span>
                @if($cierre?->motivoCierre?->afecta_mora)
                <span style="display:inline-block; margin-left:4px; background:#FEF2F2; color:#B91C1C; font-size:9px; font-weight:700; border-radius:4px; padding:1px 6px;">Afecta indicadores</span>
                @endif
            </div>
            <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Cerrado por</p>
                <span style="font-weight:600; color:#3C3489;">{{ $cierre?->cerradoPor?->name ?? '—' }}</span>
                <span style="display:block; font-size:11px; color:#AFA9EC;">{{ $cierre?->created_at?->format('d/m/Y H:i') }}</span>
            </div>
            @if($cierre?->observacion)
            <div style="grid-column:span 2; background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px;">
                <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 2px;">Observación</p>
                <span style="color:#3C3489;">{{ $cierre->observacion }}</span>
            </div>
            @endif
        </div>
    </div>

    @if (!$confirmandoReversion)
    <div class="am-act-wrap">
        <button wire:click="backToList"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Regresar
        </button>
        <button wire:click="$set('confirmandoReversion', true)"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF3C7; color:#854F0B; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FCD34D; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Revertir Cierre
        </button>
    </div>
    @else
    <div style="background:#FEF3C7; border:1.5px solid #FCD34D; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#854F0B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:13px; font-weight:700; color:#854F0B;">Motivo de la reversión</span>
        </div>
        <textarea wire:model="motivoReversion" rows="3" placeholder="Explicá por qué se revierte el cierre..."
                  style="width:100%; display:block; background:#fff; border:1px solid #FCD34D; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
        @error('motivoReversion')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button wire:click="$set('confirmandoReversion', false)"
                    style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
            <button wire:click="revertir"
                    style="flex:1; padding:10px; background:#854F0B; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">Confirmar Reversión</button>
        </div>
    </div>
    @endif

    {{-- ══ ESTADO: APROBADO / RECHAZADO ══ --}}
    @elseif (!$confirmandoRechazo && !$confirmandoCierre)
    <div class="am-act-wrap">
        <button wire:click="backToList"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Regresar
        </button>

        @if (!$tieneCuotasPagadas)
        <button wire:click="devolverRevision"
                wire:confirm="¿Devolvés este pedido a Revisión? La nota de rechazo se eliminará."
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            A Revisión
        </button>
        @endif

        @if ($tieneCuotasPagadas && $aprobado)
        <button wire:click="$set('confirmandoCierre', true)"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#EDE9FE; color:#534AB7; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #C4B5FD; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/></svg>
            Cerrar Crédito
        </button>
        @endif

        @if (!$tieneCuotasPagadas && $aprobado)
        <button wire:click="$set('confirmandoRechazo', true)"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF2F2; color:#B91C1C; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FECACA; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Rechazar
        </button>
        @elseif ($p->estado === 'rechazado')
        <button wire:click="aprobar"
                wire:confirm="¿Confirmás la aprobación? La nota de rechazo se eliminará."
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#D1FAE5; color:#065F46; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #6EE7B7; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Aprobar
        </button>
        @endif
    </div>

    @elseif ($confirmandoRechazo)
    <div style="background:#FEF2F2; border:1.5px solid #FECACA; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:13px; font-weight:700; color:#B91C1C;">Motivo del rechazo</span>
        </div>
        <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..."
                  style="width:100%; display:block; background:#fff; border:1px solid #FECACA; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
        @error('notaRechazo')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button wire:click="$set('confirmandoRechazo', false)"
                    style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
            <button wire:click="rechazar"
                    style="flex:1; padding:10px; background:#B91C1C; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">Confirmar Rechazo</button>
        </div>
    </div>

    @elseif ($confirmandoCierre)
    <div style="background:#F8F7FF; border:1.5px solid #C4B5FD; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8"/></svg>
            <span style="font-size:13px; font-weight:700; color:#534AB7;">Cerrar Crédito</span>
        </div>
        <div style="margin-bottom:10px;">
            <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 4px;">Motivo *</p>
            <select wire:model="motivoCierreId"
                    style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box;">
                <option value="">Seleccioná un motivo...</option>
                @foreach($motivosCierre as $m)
                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
            @error('motivoCierreId')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <div style="margin-bottom:10px;">
            <p style="font-size:9px; font-weight:700; color:#AFA9EC; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 4px;">Observación <span style="font-weight:400;text-transform:none;font-size:10px;">(opcional)</span></p>
            <textarea wire:model="observacionCierre" rows="3" placeholder="Detalle adicional sobre el cierre..."
                      style="width:100%; display:block; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
        </div>
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button wire:click="$set('confirmandoCierre', false)"
                    style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
            <button wire:click="cerrar"
                    style="flex:1; padding:10px; background:#534AB7; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">Confirmar Cierre</button>
        </div>
    </div>
    @endif

    {{-- Historial de cierres --}}
    @if($p->cierres->isNotEmpty())
    <div x-data="{ abierto: false }" style="background:#fff; border:1px solid #C4B5FD; border-radius:12px; overflow:hidden; margin-top:16px;">
        <button @click="abierto = !abierto"
                style="width:100%; padding:12px 16px; display:flex; align-items:center; justify-content:space-between; background:#F8F7FF; border:none; cursor:pointer; text-align:left;">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg width="14" height="14" fill="none" stroke="#7B6FE8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:13px; font-weight:700; color:#534AB7;">Historial de cierres</span>
                <span style="background:#EDE9FE; color:#534AB7; font-size:10px; font-weight:700; border-radius:4px; padding:1px 7px;">{{ $p->cierres->count() }}</span>
            </div>
            <svg :class="abierto ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="#AFA9EC" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="abierto" x-collapse>
            <div style="padding:12px 16px; display:flex; flex-direction:column; gap:10px;">
            @foreach($p->cierres as $histCierre)
            @php $revertido = $histCierre->estaRevertido(); @endphp

            @if($revertido)
            <div style="padding:12px 14px; border-radius:8px; border:1px solid #FCD34D; background:#FFFBEB;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:6px;">
                    <span style="font-size:11px; font-weight:700; color:#B45309;">Reversión de cierre</span>
                    <span style="background:#FEF3C7; color:#B45309; font-size:9px; font-weight:700; border-radius:4px; padding:2px 7px; border:1px solid #FCD34D;">REVERTIDO</span>
                </div>
                <div style="font-size:11px; color:#B45309;">
                    <strong>Revertido por:</strong> {{ $histCierre->revertidoPor?->name ?? '—' }}<br>
                    <strong>Fecha:</strong> {{ $histCierre->revertido_at->format('d/m/Y H:i') }}<br>
                    <strong>Motivo:</strong> {{ $histCierre->motivo_reversion }}
                </div>
            </div>
            @endif

            <div style="padding:12px 14px; border-radius:8px; border:1px solid #EDE9FE; background:#F8F7FF;">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px; margin-bottom:8px;">
                    <div>
                        <span style="font-size:12px; font-weight:700; color:#3C3489;">{{ $histCierre->motivoCierre?->nombre ?? '—' }}</span>
                        @if($histCierre->motivoCierre?->afecta_mora)
                        <span style="display:inline-block; margin-left:4px; background:#FEF2F2; color:#B91C1C; font-size:9px; font-weight:700; border-radius:4px; padding:1px 6px;">Afecta indicadores</span>
                        @endif
                    </div>
                    <span style="background:{{ $revertido ? '#FEF3C7' : '#EDE9FE' }}; color:{{ $revertido ? '#B45309' : '#534AB7' }}; font-size:9px; font-weight:700; border-radius:4px; padding:2px 7px; white-space:nowrap; border:1px solid {{ $revertido ? '#FCD34D' : '#C4B5FD' }};">CIERRE{{ $revertido ? ' (anulado)' : '' }}</span>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; font-size:11px; color:#AFA9EC;">
                    <div>
                        <strong style="color:#534AB7;">Cerrado por:</strong> {{ $histCierre->cerradoPor?->name ?? '—' }}<br>
                        <strong style="color:#534AB7;">Fecha:</strong> {{ $histCierre->created_at->format('d/m/Y H:i') }}
                    </div>
                    @if($histCierre->observacion)
                    <div>
                        <strong style="color:#534AB7;">Observación:</strong> {{ $histCierre->observacion }}
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
