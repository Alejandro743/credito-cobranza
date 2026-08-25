<div>

{{-- ══ LIST ══ --}}

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    <div wire:key="rc-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($p->cliente?->nombre_completo ?? '?', 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente?->nombre_completo ?? '—' }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }} @if($p->ciclo_code) <span style="color:#9CA3AF;">· {{ $p->ciclo_code }}</span>@endif</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; background:#EDE9FE; color:#7B6FE8;">
                En Revisión
            </span>
        </div>
        <div style="padding:10px 14px; display:flex; align-items:center; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Vendedor</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->vendedor->user->name ?? '—' }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $p->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Total Bs.</span>
                <span style="font-size:13px; font-weight:700; color:#374151;">{{ $p->total_pagar > 0 ? number_format($p->total_pagar, 2) : '—' }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="ver({{ $p->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Revisar
            </button>
        </div>
    </div>
    @empty
    <div wire:key="rc-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos en revisión</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
@php
$fI   = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
$fW   = 'position:relative; margin-top:4px;';
$fIc  = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
$fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
$thC  = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
$sortColsR = ['Cod. Pedido'=>'numero','Fecha del Plan'=>'fecha_plan','Fecha a Revisar'=>'fecha_revisar','CI'=>'ci','Cliente'=>'cliente','Vendedor'=>'vendedor','Total Bs.'=>'total'];
$colFiltersR = ['numero'=>'colFilterNumero','ci'=>'colFilterCi','cliente'=>'colFilterCliente','vendedor'=>'colFilterVendedor'];
$colFiltersFechaR = ['fecha_plan'=>'colFilterFechaPlan','fecha_revisar'=>'colFilterFechaRevisar'];
@endphp
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">En Proceso de Revisión</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
        @if($selectedPedidoId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            <button wire:click="verSeleccionado"
                    style="{{ $btnH }} background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Revisar
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
    <table style="width:100%; min-width:1230px; border-collapse:collapse; font-size:13px;">
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
                <th style="{{ $thC }} text-align:center; min-width:60px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Ver
                </th>

                {{-- Ciclo (solo filtro, sin orden) --}}
                <th style="{{ $thC }} text-align:center; min-width:110px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Ciclo
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiclo" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                @foreach($sortColsR as $label => $key)
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
                    @if(isset($colFiltersFechaR[$key]))
                    <div style="{{ $fW }}" @click.stop><input wire:model.live="{{ $colFiltersFechaR[$key] }}" @click.stop type="date" style="{{ $fI }} padding-left:6px;"></div>
                    @elseif(isset($colFiltersR[$key]))
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="{{ $colFiltersR[$key] }}" @click.stop type="text" style="{{ $fI }}"></div>
                    @endif
                </th>
                @endforeach

                {{-- Asignado por --}}
                <th style="{{ $thC }} text-align:center; min-width:150px;">
                    Asignado por
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterAsignadoPor" @click.stop type="text" style="{{ $fI }}"></div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            @php $selP = $selectedPedidoId === $p->id; @endphp
            <tr wire:key="rd-{{ $p->id }}"
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
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->revisado_en?->format('d/m/Y') ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->cliente?->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ ucwords(strtolower($p->cliente?->nombre_completo ?? '—')) }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ ucwords(strtolower($p->vendedor?->user?->name ?? '—')) }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#374151; white-space:nowrap;">{{ $p->asignadoPor->name ?? '—' }}</td>
            </tr>
            @empty
            <tr wire:key="rd-empty">
                <td colspan="11" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos en revisión</p>
                    <p style="font-size:12px; color:#9CA3AF;">Tomá pedidos desde "Pendiente de Revisión"</p>
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

{{-- ══ DETAIL MODAL ══ --}}
@if ($mode === 'detail' && $pedidoDetalle)

<style>
.rv-act-wrap { display:flex; flex-direction:column; gap:8px; }
@@media (min-width:640px) { .rv-act-wrap { flex-direction:row; } }
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
                <p style="font-size:17px; font-weight:700; color:#6B7280; margin:0; letter-spacing:-0.2px;">{{ $pedidoDetalle->numero }} - {{ $pedidoDetalle->estado_badge['label'] }}</p>
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
                'p'               => $pedidoDetalle,
                'plan'            => $pedidoDetalle->planPago,
                'aprobado'        => false,
                'editable'        => true,
                'clienteCalificacion' => $clienteCalificacion,
                'clienteHistorial'    => $clienteHistorial,
                'vendedorCalificacion' => $vendedorCalificacion,
                'vendedorHistorial'    => $vendedorHistorial,
                'editTipoEntrega' => $editTipoEntrega,
                'ciudadesAll'     => $ciudadesAll,
                'editProvincias'  => $editProvincias,
                'editMunicipios'  => $editMunicipios,
                'articulosEdit'          => $articulosEdit,
                'articulosFiltradosEdit' => $articulosFiltradosEdit,
                'articulosListasInfo'    => $articulosListasInfo,
                'filterListaEdit'        => $filterListaEdit,
                'searchProductoEdit'     => $searchProductoEdit,
            ])

        </div>

        {{-- Footer: acciones --}}
        <div style="padding:14px 20px; border-top:1px solid #F0EEFF; flex-shrink:0;">
        @if (!$confirmandoRechazo)
        <div class="rv-act-wrap">
            <button wire:click="devolverEspera" wire:confirm="¿Devolvés este pedido a Pendiente de Revisión?"
                    style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#F0F9FF; color:#0369A1; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #7DD3FC; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Pendiente de Revisión
            </button>
            <button wire:click="$set('confirmandoRechazo', true)"
                    style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF2F2; color:#B91C1C; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FECACA; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Rechazar
            </button>
            <button wire:click="aprobar" wire:confirm="¿Confirmás la aprobación de este pedido?"
                    style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Aprobar
            </button>
        </div>
        @else
        <div style="background:#FEF2F2; border:1.5px solid #FECACA; border-radius:12px; padding:16px;">
            <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
                <svg width="14" height="14" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span style="font-size:13px; font-weight:700; color:#B91C1C;">Motivo del rechazo</span>
            </div>
            <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..."
                      style="width:100%; display:block; background:#fff; border:1px solid #FECACA; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
            @error('notaRechazo')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
            <div style="display:flex; gap:8px; margin-top:12px;">
                <button wire:click="$set('confirmandoRechazo', false)"
                        style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:12px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <span style="font-size:16px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Cancelar
                </button>
                <button wire:click="rechazar"
                        style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#B91C1C; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Confirmar Rechazo
                </button>
            </div>
        </div>
        @endif
        </div>

    </div>
</div>

@endif
</div>
