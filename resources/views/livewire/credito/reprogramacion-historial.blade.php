<div>

<style>
.rp-det-btns { display:flex; flex-direction:column; gap:8px; margin-top:20px; }
@media (min-width:640px) { .rp-det-btns { flex-direction:row; } }
</style>

{{-- Toast global --}}
<div x-data="{ show: false, message: '' }"
     @rp-toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3500)"
     x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="position:fixed; top:20px; right:20px; z-index:9999; padding:12px 18px; background:#F0FDF4; border:1px solid #86EFAC; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); display:flex; align-items:center; gap:10px; min-width:260px;">
    <svg width="18" height="18" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    <span x-text="message" style="font-size:13px; font-weight:700; color:#166534;"></span>
</div>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, cliente o pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
    <div style="display:flex; gap:6px; flex-wrap:wrap;">
        @foreach(['todos'=>'Todos','activo'=>'Plan activo','inactivo'=>'Plan inactivo'] as $val => $lbl)
        <button wire:click="$set('filtro','{{ $val }}')"
                style="padding:5px 12px; font-size:11px; font-weight:600; cursor:pointer; white-space:nowrap; border-radius:7px; -webkit-appearance:none; appearance:none;
                       border:1.5px solid {{ $filtro===$val ? '#C4B5FD' : '#E5E7EB' }};
                       background:{{ $filtro===$val ? '#EDE9FE' : 'transparent' }};
                       color:{{ $filtro===$val ? '#7B6FE8' : '#9CA3AF' }};">
            {{ $lbl }}
        </button>
        @endforeach
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($reprogramaciones as $rp)
    @php $esActivo = $rp->planNuevo?->estado === 'activo'; @endphp
    <div wire:key="rp-mob-{{ $rp->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($rp->pedido->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($rp->pedido->cliente->nombre_completo)) }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $rp->numero }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; flex-shrink:0;
                         background:{{ $esActivo ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $esActivo ? '#059669' : '#9CA3AF' }};">{{ $esActivo ? 'Activo' : 'Inactivo' }}</span>
        </div>
        <div style="padding:10px 14px; display:flex; gap:8px;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Pedido</span>
                <span style="font-size:12px; font-weight:600; color:#374151; font-family:monospace;">{{ $rp->pedido->numero }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $rp->created_at->format('d/m/Y') }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Saldo reprog.</span>
                <span style="font-size:13px; font-weight:700; color:#DC2626;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="verDetalle({{ $rp->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin reprogramaciones registradas</p>
    </div>
    @endforelse
    @if($reprogramaciones->hasPages())
    <div style="padding-top:8px;">{{ $reprogramaciones->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
@php
$sortColsRH = ['Pedido'=>null,'Código'=>'numero','CI'=>null,'Cliente'=>null,'Versión'=>null,'Fecha'=>'fecha','Saldo reprog.'=>'saldo','Plan'=>null];
@endphp
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Historial de Reprogramaciones</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $reprogramaciones->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:780px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                @foreach($sortColsRH as $label => $key)
                @php $isActive = $key && $sortBy === $key; @endphp
                <th @if($key) wire:click="toggleSort('{{ $key }}')" @endif
                    style="padding:10px 14px; text-align:{{ in_array($label,['Versión','Fecha','Saldo reprog.','Plan','Ver']) ? 'center' : 'left' }}; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; {{ $key ? 'cursor:pointer; user-select:none;' : '' }} {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @if($key) @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'" @endif>
                    <span style="display:inline-flex; align-items:center; gap:5px;">{{ $label }}
                        @if($key)
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        @endif
                    </span>
                </th>
                @endforeach
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reprogramaciones as $rp)
            @php $esActivo = $rp->planNuevo?->estado === 'activo'; @endphp
            <tr wire:key="rp-{{ $rp->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap; background:#F9F8FF; color:#C4B5FD; font-weight:700;">{{ $reprogramaciones->firstItem() + $loop->index }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; color:#111827; white-space:nowrap;">{{ $rp->pedido->numero }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $rp->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $rp->pedido->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($rp->pedido->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                        <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#F3F4F6; color:#6B7280;">v{{ $rp->version_anterior }}</span>
                        <svg width="11" height="11" fill="none" stroke="#D1D5DB" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">v{{ $rp->version_nueva }}</span>
                    </div>
                </td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#6B7280; white-space:nowrap;">{{ $rp->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#DC2626; white-space:nowrap; font-family:monospace;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700;
                                 background:{{ $esActivo ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $esActivo ? '#059669' : '#9CA3AF' }};">
                        {{ $esActivo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="verDetalle({{ $rp->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Ver">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="rp-empty">
                <td colspan="10" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin reprogramaciones registradas</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($reprogramaciones->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $reprogramaciones->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $reprogramacionDetalle)
@php
    $rp         = $reprogramacionDetalle;
    $p          = $rp->pedido;
    $planViejo  = $rp->planViejo;
    $planNuevo  = $rp->planNuevo;
    $cuotasViej = $planViejo?->cuotas->where('numero', '>', 0)->sortBy('numero') ?? collect();
    $cuotas     = $planNuevo?->cuotas->where('numero', '>', 0)->sortBy('numero') ?? collect();
    $pagado     = $cuotas->where('estado','pagado')->sum('monto');
    $pendiente  = $cuotas->where('estado','!=','pagado')->sum('monto');
    $esActivo   = $planNuevo?->estado === 'activo';
@endphp

<div style="max-width:900px; margin:0 auto;">

    {{-- Timestamp --}}
    <div style="display:flex; align-items:center; justify-content:flex-end; margin-bottom:12px;">
        <span style="font-size:11px; color:#CBCBCB; white-space:nowrap;">{{ $rp->created_at->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            REPROGRAMACIÓN DE CRÉDITO
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $rp->numero }} - {{ $p->numero }}
        </p>
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:{{ $esActivo ? '#15803D' : '#6b7280' }};">
            {{ $esActivo ? 'ACTIVO' : 'INACTIVO' }}
        </span>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $p->cliente->ci ?: '—' }} - {{ ucwords(strtolower($p->cliente->nombre_completo)) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}
            </p>
        </div>

        {{-- ── Separador Plan de Pagos ── --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Plan de Pagos</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Tabla cuotas nuevas --}}
        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">#</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Vencimiento</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha pago</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $c)
                    @php $badge = $c->estadoFinancieroBadge; @endphp
                    <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.6;' : '' }}">
                        <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">{{ $c->numero }}</td>
                        <td style="padding:8px 12px; font-size:11px; color:#6b7280; font-weight:600; text-align:center;">Cuota {{ $c->numero }}</td>
                        <td style="padding:8px 12px; font-size:13px; font-weight:700; color:#7c3aed; font-family:monospace; text-align:center;">{{ number_format($c->monto, 2) }}</td>
                        <td style="padding:8px 12px; font-size:11px; color:#6b7280; text-align:center;">{{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                        <td style="padding:8px 12px; font-size:11px; text-align:center; font-weight:{{ $c->fecha_pago ? '600' : '400' }}; color:{{ $c->fecha_pago ? '#059669' : '#D1D5DB' }};">{{ $c->fecha_pago ? $c->fecha_pago->format('d/m/Y') : '—' }}</td>
                        <td style="padding:8px 12px; text-align:center;">
                            <span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:32px 12px; text-align:center; font-size:13px; color:#9CA3AF;">Sin cuotas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Acordeón plan anterior --}}
        @if($planViejo && $cuotasViej->isNotEmpty())
        <div x-data="{ abierto: false }" style="margin-top:14px; border:1px solid #E5E7EB; border-radius:10px; overflow:hidden;">
            <button @click="abierto = !abierto"
                    style="width:100%; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; background:#FFF7ED; border:none; cursor:pointer; text-align:left;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:12px; font-weight:700; color:#9CA3AF;">Plan anterior · v{{ $rp->version_anterior }}</span>
                    <span class="ds-badge ds-badge-cerrado">REEMPLAZADO</span>
                </div>
                <svg :class="abierto ? 'rotate-180' : ''" class="transition-transform" style="color:#CBCBCB; width:16px; height:16px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="abierto" x-collapse>
                <div style="border-top:1px solid #E5E7EB;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#F8F7FF;">
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">#</th>
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto</th>
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Vencimiento</th>
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha pago</th>
                                <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($cuotasViej as $c)
                        @php $badge = $c->estadoFinancieroBadge; @endphp
                        <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.6;' : '' }}">
                            <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">{{ $c->numero }}</td>
                            <td style="padding:8px 12px; font-size:11px; color:#6b7280; font-weight:600; text-align:center;">Cuota {{ $c->numero }}</td>
                            <td style="padding:8px 12px; font-size:13px; font-weight:700; color:#9CA3AF; font-family:monospace; text-align:center;">{{ number_format($c->monto, 2) }}</td>
                            <td style="padding:8px 12px; font-size:11px; color:#6b7280; text-align:center;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                            <td style="padding:8px 12px; font-size:11px; text-align:center; font-weight:{{ $c->fecha_pago ? '600' : '400' }}; color:{{ $c->fecha_pago ? '#059669' : '#D1D5DB' }};">{{ $c->fecha_pago ? $c->fecha_pago->format('d/m/Y') : '—' }}</td>
                            <td style="padding:8px 12px; text-align:center;">
                                <span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#F5F3FF; border-top:1.5px solid #EDE9FE;">
                                <td colspan="6" style="padding:10px 16px;">
                                    <div style="display:flex; justify-content:space-around; align-items:center;">
                                        <div style="text-align:center;">
                                            <span style="display:block; font-size:9px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px;">Monto Total</span>
                                            <span style="font-size:13px; font-weight:900; color:#111827; font-family:monospace;">Bs. {{ number_format($planViejo->total_pagar, 2) }}</span>
                                        </div>
                                        <div style="width:1px; height:28px; background:#EDE9FE;"></div>
                                        <div style="text-align:center;">
                                            <span style="display:block; font-size:9px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px;">Saldo Reprog.</span>
                                            <span style="font-size:13px; font-weight:900; color:#111827; font-family:monospace;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</span>
                                        </div>
                                        <div style="width:1px; height:28px; background:#EDE9FE;"></div>
                                        <div style="text-align:center;">
                                            <span style="display:block; font-size:9px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px;">Total Cuotas</span>
                                            <span style="font-size:13px; font-weight:900; color:#111827;">{{ $cuotasViej->count() }}</span>
                                        </div>
                                        <div style="width:1px; height:28px; background:#EDE9FE;"></div>
                                        <div style="text-align:center;">
                                            <span style="display:block; font-size:9px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px;">Reemplazadas</span>
                                            <span style="font-size:13px; font-weight:900; color:#111827;">{{ $cuotasViej->where('estado','!=','pagado')->count() }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Separador Resumen ── --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Resumen</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18), 0 1px 4px rgba(123,111,232,0.10); margin-bottom:12px;">
            <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#DC2626 100%);"></div>
            <div style="padding:14px;">
                <div style="display:grid; grid-template-columns:repeat(3,1fr); text-align:center; margin-bottom:10px;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Reprog.</span>
                        <span style="font-size:13px; font-weight:900; color:#DC2626; line-height:1.1; font-family:monospace;">{{ number_format($planNuevo?->total_pagar ?? 0, 2) }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Pagado</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; line-height:1.1; font-family:monospace;">{{ number_format($pagado, 2) }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Pendiente</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; line-height:1.1; font-family:monospace;">{{ number_format($pendiente, 2) }}</span>
                    </div>
                </div>
                <div style="height:1px; background:#EDE9FE; margin-bottom:10px;"></div>
                <div style="display:grid; grid-template-columns:repeat(3,1fr); text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Cuotas</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotas->count() }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Cuotas Pagadas</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotas->where('estado','pagado')->count() }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Cuotas Pendientes</span>
                        <span style="font-size:13px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotas->where('estado','!=','pagado')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; padding:10px 12px;">
            <span style="font-size:9px; font-weight:500; color:#534AB7; display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.04em;">Motivo</span>
            <span style="font-size:13px; color:#3C3489; display:block; line-height:1.5;">{{ $rp->motivo }}</span>
        </div>

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="rp-det-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
    </div>

</div>

{{-- ══ EDITAR ══ --}}
@elseif($mode === 'editar' && $reprogramacionDetalle)
@php
    $rp          = $reprogramacionDetalle;
    $p           = $rp->pedido;
    $planNuevo   = $rp->planNuevo;
    $pagado      = $planNuevo?->cuotas->where('numero','>',0)->where('estado','pagado')->sum('monto') ?? 0;
    $pendActual  = $planNuevo?->cuotas->where('numero','>',0)->where('estado','!=','pagado')->sum('monto') ?? 0;
    $totalEditado = round(collect($cuotasEditadas)->filter(fn($c) => !($c['pagado'] ?? false))->sum(fn($c) => (float)$c['monto']), 2);
    $difEditado   = round($totalEditado - $pendActual, 2);
    $esActivo     = $planNuevo?->estado === 'activo';
@endphp

<div style="max-width:900px; margin:0 auto;">

    {{-- Timestamp --}}
    <div style="display:flex; align-items:center; justify-content:flex-end; margin-bottom:12px;">
        <span style="font-size:11px; color:#CBCBCB; white-space:nowrap;">{{ $rp->created_at->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            EDITAR PLAN DE PAGOS
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $rp->numero }} - {{ $p->numero }}
        </p>
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:{{ $esActivo ? '#15803D' : '#6b7280' }};">
            {{ $esActivo ? 'ACTIVO' : 'INACTIVO' }}
        </span>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px;">
            <p style="font-size:13px; color:#374151; margin:0 0 6px;">
                <span style="font-weight:700; color:#6B7280;">Cliente:</span>
                {{ $p->cliente->ci ?: '—' }} - {{ ucwords(strtolower($p->cliente->nombre_completo)) }}
            </p>
            <p style="font-size:13px; color:#374151; margin:0;">
                <span style="font-weight:700; color:#6B7280;">Vendedor:</span>
                {{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}
            </p>
        </div>

        {{-- Separador Editar Cuotas --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Editar Cuotas</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        {{-- Tabla editable con cuadre Alpine --}}
        <div x-data="{
                saldo: {{ $pendActual }},
                total: {{ $totalEditado }},
                diff:  {{ $difEditado }},
                get diffLabel() {
                    if (Math.abs(this.diff) < 0.01) return '✓ Cuadra exacto';
                    return (this.diff > 0 ? '+' : '−') + 'Bs. ' + Math.abs(this.diff).toFixed(2);
                },
                get diffColor() {
                    if (Math.abs(this.diff) < 0.01) return '#059669';
                    return this.diff > 0 ? '#B45309' : '#DC2626';
                },
                get diffBg() {
                    if (Math.abs(this.diff) < 0.01) return '#F0FDF4';
                    return this.diff > 0 ? '#FFFBEB' : '#FFF0F0';
                },
                get diffBorder() {
                    if (Math.abs(this.diff) < 0.01) return '#6ee7b7';
                    return this.diff > 0 ? '#FCD34D' : '#FCA5A5';
                },
                recalc() {
                    let inputs = this.$el.querySelectorAll('.monto-edit');
                    let raw = Array.from(inputs).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
                    this.total = Math.round(raw * 100) / 100;
                    this.diff  = Math.round((this.total - this.saldo) * 100) / 100;
                }
             }"
             x-init="
                $el.addEventListener('input', (e) => { if (e.target.classList.contains('monto-edit')) recalc(); });
                $wire.$watch('cuotasEditadas', () => $nextTick(() => recalc()));
             ">

            <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:14px;">
                <div style="padding:10px 14px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; justify-content:space-between; background:#F8F7FF;">
                    <span style="font-size:12px; font-weight:700; color:#534AB7;">Cuotas · v{{ $rp->version_nueva }}</span>
                    <button wire:click="agregarCuotaEdicion"
                            style="display:flex; align-items:center; gap:5px; padding:5px 12px; background:#EDE9FE; color:#534AB7; font-size:12px; font-weight:700; border:1px solid #C4B5FD; border-radius:8px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Agregar cuota
                    </button>
                </div>
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#F8F7FF;">
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">#</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuotas</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Monto (Bs.)</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha vencimiento</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Estado</th>
                            <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotasEditadas as $i => $ce)
                        <tr wire:key="ce-{{ $i }}" style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $ce['pagado'] ? 'opacity:0.5;background:#f9fafb;' : '' }}">
                            <td style="padding:8px 12px; font-size:11px; color:#374151; text-align:center;">{{ $ce['numero'] }}</td>
                            <td style="padding:8px 12px; font-size:11px; color:#6b7280; font-weight:600; text-align:center;">Cuota {{ $ce['numero'] }}</td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="font-family:monospace; font-weight:700; color:#374151;">{{ number_format((float)$ce['monto'], 2) }}</span>
                                @else
                                <input wire:model="cuotasEditadas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                                       class="monto-edit" style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; text-align:center; outline:none; background:#fff;">
                                @error("cuotasEditadas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="font-size:11px; color:#6b7280;">{{ $ce['fecha'] ? \Carbon\Carbon::parse($ce['fecha'])->format('d/m/Y') : '—' }}</span>
                                @else
                                <input wire:model="cuotasEditadas.{{ $i }}.fecha" type="date"
                                       style="width:90%; padding:4px 8px; border:1px solid #C4B5FD; border-radius:6px; font-size:12px; outline:none; background:#fff;">
                                @error("cuotasEditadas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if($ce['pagado'])
                                <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#D1FAE5; color:#059669;">Pagado</span>
                                @else
                                <span style="padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; background:#FEE2E2; color:#DC2626;">Pendiente</span>
                                @endif
                            </td>
                            <td style="padding:8px 12px; text-align:center;">
                                @if(!$ce['pagado'])
                                <button wire:click="quitarCuotaEdicion({{ $i }})"
                                        style="width:28px; height:28px; border-radius:7px; border:1px solid #FECACA; background:#FEF2F2; color:#DC2626; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Resumen real-time --}}
            <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18);">
                <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#DC2626 100%);"></div>
                <div style="padding:14px; display:grid; grid-template-columns:repeat(3,1fr); text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Saldo a cubrir</span>
                        <span style="font-size:13px; font-weight:900; color:#DC2626; font-family:monospace;">Bs. {{ number_format($pendActual, 2) }}</span>
                    </div>
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total cuotas</span>
                        <p x-text="'Bs. ' + total.toFixed(2)" style="font-size:13px; font-weight:900; color:#111827; font-family:monospace; margin:0;"></p>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Diferencia</span>
                        <p x-text="diffLabel" :style="'font-size:13px; font-weight:900; font-family:monospace; margin:0; color:' + diffColor"></p>
                    </div>
                </div>
            </div>

        </div>{{-- /x-data --}}

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="rp-det-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        <button wire:click="guardarEdicion" wire:loading.attr="disabled"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="guardarEdicion">Guardar cambios</span>
            <span wire:loading wire:target="guardarEdicion">Guardando...</span>
        </button>
    </div>

</div>
@endif

</div>
