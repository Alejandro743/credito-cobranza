<div>

{{-- Toolbar --}}
<div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
    <a href="{{ $enWorkbench ? route('workbench', ['tab' => 'vendedor-oferta']) : route('vendedor.oferta') }}"
       @if($enWorkbench)
       @click.prevent="window.dispatchEvent(new CustomEvent('abrir-pestana', { detail: { key: 'vendedor-oferta' } })); window.dispatchEvent(new CustomEvent('nueva-oferta'))"
       @else
       wire:navigate
       @endif
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
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }} @if($p->ciclo_code) <span style="color:#9CA3AF;">· {{ $p->ciclo_code }}</span>@endif</p>
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
            <button type="button" wire:click="ver({{ $p->id }})"
               style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </button>
        </div>
    </div>
    @empty
    <div wire:key="pm-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos todavía</p>
        <p style="font-size:12px; color:#9CA3AF; margin-top:4px;">Generá tu primer pedido desde Oferta / Carrito</p>
        <a href="{{ $enWorkbench ? route('workbench', ['tab' => 'vendedor-oferta']) : route('vendedor.oferta') }}"
           @if($enWorkbench)
           @click.prevent="window.dispatchEvent(new CustomEvent('abrir-pestana', { detail: { key: 'vendedor-oferta' } })); window.dispatchEvent(new CustomEvent('nueva-oferta'))"
           @else
           wire:navigate
           @endif
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
@php
$fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
$fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
$fW  = 'position:relative; margin-top:4px;';
$fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
$fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
$thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
@endphp
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Mis solicitudes</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
        @if ($selectedPedidoId)
        @php $selPedido = $pedidos->firstWhere('id', $selectedPedidoId); @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            <button type="button" wire:click="ver({{ $selectedPedidoId }})"
               style="height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; background:#7B6FE8; color:#fff;">
                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver detalle
            </button>
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:900px; border-collapse:collapse; font-size:13px;">
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
                            <option value="en_espera">Pendiente</option>
                            <option value="revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>
                </th>

                {{-- Ciclo --}}
                <th style="{{ $thC }} text-align:center; min-width:100px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Ciclo
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCiclo" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- Cod. Pedido --}}
                @php $isA = $sortBy === 'numero'; @endphp
                <th wire:click="toggleSort('numero')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Cod. Pedido
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNumero" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- CI --}}
                <th style="{{ $thC }} text-align:center; min-width:120px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    CI
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterCi" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- Nombre --}}
                <th style="{{ $thC }} text-align:center; min-width:160px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Nombre
                    <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                </th>

                {{-- Fecha Solicitud --}}
                @php $isA = $sortBy === 'created_at'; @endphp
                <th wire:click="toggleSort('created_at')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:130px; box-shadow:inset -1px 0 0 #E5E7EB; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Fecha Solicitud
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                    <div style="{{ $fW }}" @click.stop><input wire:model.live="colFilterFecha" @click.stop type="date" style="{{ $fI }} padding-left:6px;"></div>
                </th>

                {{-- Fecha Contestación --}}
                <th style="{{ $thC }} text-align:center; min-width:140px; box-shadow:inset -1px 0 0 #E5E7EB;">
                    Fecha Contestación
                    <div style="{{ $fW }}" @click.stop><input wire:model.live="colFilterFechaCont" @click.stop type="date" style="{{ $fI }} padding-left:6px;"></div>
                </th>

                {{-- Total Bs. --}}
                @php $isA = $sortBy === 'total_pagar'; @endphp
                <th wire:click="toggleSort('total_pagar')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Total Bs.
                        <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                            <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                        </span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            @php
                $selP = $selectedPedidoId === $p->id;
                $rowBg = $selP ? '#F5F3FF' : '';
            @endphp
            <tr wire:key="p-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s; background:{{ $rowBg }}; {{ $selP ? 'border-left:3px solid #7B6FE8;' : '' }}"
                @mouseenter="$el.style.background='{{ $selP ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selP ? '#F5F3FF' : '' }}'">
                <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selP ? '#F5F3FF' : '#fff' }}; box-shadow:inset -1px 0 0 #E5E7EB;">
                    <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                        <input type="checkbox"
                               :checked="$wire.selectedPedidoId === {{ $p->id }}"
                               @click="$wire.selectPedido({{ $p->id }})"
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
                <td style="padding:10px 14px; font-size:13px; font-weight:700; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->estado_badge['label'] }}</td>
                <td style="padding:10px 10px; text-align:center; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->ciclo_code ?? '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->cliente->nombre_completo }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:400; color:#111827; white-space:nowrap; box-shadow:inset -1px 0 0 #E5E7EB;">
                    @if (in_array($p->estado, ['aprobado','rechazado']) && $p->updated_at)
                        {{ $p->updated_at->format('d/m/Y') }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:400; color:#111827; white-space:nowrap;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr wire:key="pm-desktop-empty">
                <td colspan="9" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos todavía</p>
                    <p style="font-size:12px; color:#9CA3AF;">Generá tu primer pedido desde Oferta / Carrito</p>
                    <a href="{{ $enWorkbench ? route('workbench', ['tab' => 'vendedor-oferta']) : route('vendedor.oferta') }}"
                       @if($enWorkbench)
                       @click.prevent="window.dispatchEvent(new CustomEvent('abrir-pestana', { detail: { key: 'vendedor-oferta' } })); window.dispatchEvent(new CustomEvent('nueva-oferta'))"
                       @else
                       wire:navigate
                       @endif
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

{{-- ══ DETAIL MODAL ══ --}}
@if ($mode === 'detail' && $pedidoDetalle)
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
                    style="width:28px; height:28px; border-radius:8px; background:#F5F3FF; color:#9CA3AF; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s, color .15s;"
                    @mouseenter="$el.style.background='#7B6FE8'; $el.style.color='#fff';"
                    @mouseleave="$el.style.background='#F5F3FF'; $el.style.color='#9CA3AF';">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="overflow-y:auto; flex:1; min-height:0; padding:18px 20px;">
            @include('livewire.vendedor.partials.pedido-detalle-body', ['pedido' => $pedidoDetalle, 'mostrarCodEstadoInline' => false])
        </div>

    </div>
</div>
@endif

</div>
