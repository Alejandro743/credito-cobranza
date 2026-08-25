<div>

@php
$badgeEstado = [
    'aprobado'  => 'background:#FFFFE3;color:#6D8196;border:1px solid #6D8196;',
    'rechazado' => 'background:#FEE2E2;color:#DC2626;border:1px solid #FCA5A5;',
    'cerrado'   => 'background:#F4F4F4;color:#4A4A4A;border:1px solid #CBCBCB;',
];
@endphp

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    @php
        $sinImpacto  = $p->estado === 'cerrado' && !($p->cierre?->motivoCierre?->afecta_mora);
        $esRechazado = $p->estado === 'rechazado';
        $pagado      = ($sinImpacto || $esRechazado) ? 0 : ($p->total_pagado ?? 0);
        $saldo       = ($sinImpacto || $esRechazado) ? 0 : max(0, $p->total_pagar - $pagado);
        $estiloEstado = $badgeEstado[$p->estado] ?? 'background:#F4F4F4;color:#4A4A4A;border:1px solid #CBCBCB;';
    @endphp
    <div wire:key="am-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }} @if($p->ciclo_code) <span style="color:#9CA3AF;">· {{ $p->ciclo_code }}</span>@endif</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; {{ $estiloEstado }}">{{ ucfirst($p->estado) }}</span>
        </div>
        <div style="padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Vendedor</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->vendedor->user->name ?? '—' }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha Revisión</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->revisado_en?->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Total Bs.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</span>
            </div>
        </div>
        @if ($pagado > 0 || $saldo > 0)
        <div style="padding:6px 14px 10px; display:flex; gap:8px; border-top:1px solid #F3F4F6;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Pagado Bs.</span>
                <span style="font-size:12px; font-weight:700; color:#10B981;">{{ number_format($pagado, 2) }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Saldo Bs.</span>
                <span style="font-size:12px; font-weight:700; color:{{ $saldo > 0 ? '#DC2626' : '#374151' }};">{{ number_format($saldo, 2) }}</span>
            </div>
        </div>
        @endif
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="ver({{ $p->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver
            </button>
        </div>
    </div>
    @empty
    <div wire:key="am-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin resultados</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Gestión de Crédito</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
        @if($selectedPedidoId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            <button wire:click="verSeleccionado"
                    style="{{ $btnH }} background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
        </div>
        @endif

        <button type="button" wire:click="refrescarPorEvento"
                style="margin-left:auto; height:28px; padding:0 10px; border:1px solid #EDE9FE; border-radius:7px; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:inline-flex; align-items:center; gap:5px; flex-shrink:0; font-size:11px; font-weight:700; white-space:nowrap; transition:background .15s, color .15s;"
                onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';"
                onclick="const ic=this.querySelector('svg'); const deg=(parseInt(ic.dataset.deg||'0')+360); ic.dataset.deg=deg; ic.style.transition='transform .5s ease'; ic.style.transform='rotate('+deg+'deg)';">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15"/></svg>
            Actualizar
        </button>
    </div>

    <div style="overflow:auto; flex:1;">
    @php
    $fI   = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
    $fS   = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
    $fW   = 'position:relative; margin-top:4px;';
    $fIc  = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
    $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
    $thC  = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
    $sortColsA = ['Cod. Pedido'=>'numero','Fecha Revisión'=>'fecha_revision','CI'=>'ci','Cliente'=>'cliente','Vendedor'=>'vendedor','Total Bs.'=>'total'];
    $colFiltersA = ['numero'=>'colFilterNumero','ci'=>'colFilterCi','cliente'=>'colFilterCliente','vendedor'=>'colFilterVendedor'];
    @endphp
    <table style="width:100%; min-width:1500px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF; box-shadow:inset -1px 0 0 #E5E7EB;">
                    #
                    <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                        <input type="checkbox"
                               :checked="$wire.selectedPedidoId !== null"
                               :disabled="$wire.selectedPedidoId === null"
                               @click.prevent="$wire.selectedPedidoId !== null && $wire.set('selectedPedidoId', null)"
                               :style="$wire.selectedPedidoId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                    </div>
                </th>

                {{-- Ver --}}
                <th style="{{ $thC }} text-align:center; min-width:60px; box-shadow:inset -1px 0 0 #E5E7EB;">Ver</th>

                {{-- Ciclo (solo filtro, sin orden) --}}
                <th style="{{ $thC }} text-align:center; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Ciclo
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiclo" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- Estado --}}
                @php $isA = $sortBy === 'estado'; @endphp
                <th wire:click="toggleSort('estado')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:140px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Estado
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>
                        {!! $fSvg !!}
                        <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                            <option value="">Todos</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="cerrado">Cerrado</option>
                        </select>
                    </div>
                </th>

                @foreach($sortColsA as $label => $key)
                @php $isActive = $sortBy === $key; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isActive?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isActive?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">{{ $label }}
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isActive && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isActive && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    @if($key === 'fecha_revision')
                    <div style="{{ $fW }}" @click.stop><input wire:model.live="colFilterFechaRevision" @click.stop type="date" style="{{ $fI }} padding-left:6px;"></div>
                    @elseif(isset($colFiltersA[$key]))
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="{{ $colFiltersA[$key] }}" @click.stop type="text" style="{{ $fI }}"></div>
                    @endif
                </th>
                @endforeach

                {{-- Pagado / Saldo (sin orden ni filtro) --}}
                <th style="{{ $thC }} text-align:right; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB;">Pagado Bs.</th>
                <th style="{{ $thC }} text-align:right; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB;">Saldo Bs.</th>

                {{-- Asignado por --}}
                <th style="{{ $thC }} text-align:center; min-width:150px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Asignado por
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterAsignadoPor" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- Revisado por --}}
                <th style="{{ $thC }} text-align:center; min-width:150px;">
                    Revisado por
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterRevisadoPor" @click.stop type="text" style="{{ $fI }}"></div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            @php
                $sinImpacto  = $p->estado === 'cerrado' && !($p->cierre?->motivoCierre?->afecta_mora);
                $esRechazado = $p->estado === 'rechazado';
                $pagado      = ($sinImpacto || $esRechazado) ? 0 : ($p->total_pagado ?? 0);
                $saldo       = ($sinImpacto || $esRechazado) ? 0 : max(0, $p->total_pagar - $pagado);
            @endphp
            @php $selP = $selectedPedidoId === $p->id; @endphp
            <tr wire:key="ad-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $selP ? '#F5F3FF' : '' }}; {{ $selP ? 'border-left:3px solid #7B6FE8;' : '' }}"
                @mouseenter="$el.style.background='{{ $selP ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selP ? '#F5F3FF' : '' }}'">
                <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selP ? '#F5F3FF' : '#fff' }}; box-shadow:inset -1px 0 0 #E5E7EB;">
                    <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                        <input type="checkbox"
                               :checked="$wire.selectedPedidoId === {{ $p->id }}"
                               @click="$wire.selectedPedidoId === {{ $p->id }} ? $wire.set('selectedPedidoId', null) : $wire.selectPedido({{ $p->id }})"
                               style="accent-color:#7B6FE8; width:13px; height:13px; cursor:pointer;">
                        <span style="font-size:13px; color:#111827;">{{ $pedidos->firstItem() + $loop->index }}</span>
                    </div>
                </td>
                <td style="padding:10px 14px; text-align:center; box-shadow:inset -1px 0 0 #E5E7EB;">
                    <button type="button" wire:click="ver({{ $p->id }})"
                            style="width:26px; height:26px; border:none; background:transparent; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; color:#7B6FE8; padding:0;">
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
                <td style="padding:10px 10px; text-align:center; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->ciclo_code ?? '—' }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; font-weight:700; color:#374151; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ ucfirst($p->estado) }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->revisado_en?->format('d/m/Y') ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:400; color:#10B981; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ number_format($pagado, 2) }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:400; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB; color:{{ $saldo > 0 ? '#DC2626' : '#111827' }};">{{ number_format($saldo, 2) }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#374151; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->asignadoPor->name ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $p->revisadoPor->name ?? '—' }}</td>
            </tr>
            @empty
            <tr wire:key="ad-empty">
                <td colspan="13" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin resultados</p>
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

<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(20,10,40,0.4); backdrop-filter:blur(2px);">
    <div style="background:#fff; border-radius:20px; width:100%; max-width:900px; max-height:92vh; display:flex; flex-direction:column; box-shadow:0 24px 60px rgba(60,52,137,0.18), 0 0 0 1px rgba(196,181,253,0.15); overflow:hidden;">

        {{-- Header --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #F0EEFF; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:9px;">
                <div style="width:30px; height:30px; border-radius:50%; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" fill="none" stroke="#7B6FE8" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <line x1="9" y1="12" x2="15" y2="12"/>
                        <line x1="9" y1="16" x2="13" y2="16"/>
                    </svg>
                </div>
                <p style="font-size:17px; font-weight:700; color:#6B7280; margin:0; letter-spacing:-0.2px;">{{ $p->numero }} - {{ $p->estado_badge['label'] }}</p>
            </div>
            <button type="button" wire:click="backToList"
                    style="width:28px; height:28px; border-radius:8px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s, color .15s;"
                    @mouseenter="$el.style.background='#7B6FE8'; $el.style.color='#fff';"
                    @mouseleave="$el.style.background='#F8F7FF'; $el.style.color='#7B6FE8';">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="overflow-y:auto; flex:1; min-height:0; padding:18px 20px;">

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

    </div>
</div>
@endif
</div>
