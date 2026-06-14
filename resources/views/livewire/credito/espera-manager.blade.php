<div>

{{-- ══ LIST ══ --}}
@if ($mode === 'list')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente o Nº pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($pedidos as $p)
    <div wire:key="ec-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#FEF3C7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#D97706;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }} @if($p->ciclo_code) <span style="color:#9CA3AF;">· {{ $p->ciclo_code }}</span>@endif</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0; background:#FEF3C7; color:#D97706;">
                En espera
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Tomar para Revisión
            </button>
        </div>
    </div>
    @empty
    <div wire:key="ec-mobile-empty" style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pedidos en espera</p>
    </div>
    @endforelse
    @if ($pedidos->hasPages())
    <div style="padding-top:8px;">{{ $pedidos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">En Espera de Aprobación</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    @php
    $sortColsE = ['Cod. Pedido'=>'numero','CI'=>'ci','Cliente'=>'cliente','Vendedor'=>'vendedor','Fecha'=>'fecha','Total Bs.'=>'total'];
    @endphp
    <table style="width:100%; min-width:800px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 10px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Ciclo</th>
                @foreach($sortColsE as $label => $key)
                @php $isActive = $sortBy === $key; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="padding:10px 14px; text-align:{{ $label === 'Total Bs.' ? 'right' : 'left' }}; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; cursor:pointer; user-select:none; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                    <span style="display:inline-flex; align-items:center; gap:5px;">{{ $label }}
                        @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                        @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                        @endif
                    </span>
                </th>
                @endforeach
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="ed-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $pedidos->firstItem() + $loop->index }}</td>
                <td style="padding:10px 10px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; font-family:monospace; white-space:nowrap; background:#F8F7FF;">{{ $p->ciclo_code ?? '—' }}</td>
                <td style="padding:10px 14px; font-family:monospace; font-size:12px; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap;">{{ ucwords(strtolower($p->vendedor->user->name ?? '—')) }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; text-align:right; font-size:13px; font-weight:700; color:#111827; white-space:nowrap;">
                    @if ($p->total_pagar > 0)
                        {{ number_format($p->total_pagar, 2) }}
                    @else
                        <span style="color:#D1D5DB;">—</span>
                    @endif
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="ver({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'"
                            title="Tomar para revisión">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="ed-empty">
                <td colspan="9" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos en espera</p>
                    <p style="font-size:12px; color:#9CA3AF;">Todos los pedidos han sido tomados para revisión</p>
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
    $p          = $pedidoDetalle;
    $plan       = $p->planPago;
    $aprobado   = false;
    $estadoConfig = match($p->estado) {
        'en_espera' => ['color' => '#854F0B', 'bg' => '#FEF3C7', 'border' => '#FCD34D'],
        'revision'  => ['color' => '#0369A1', 'bg' => '#F0F9FF', 'border' => '#7DD3FC'],
        'aprobado'  => ['color' => '#15803D', 'bg' => '#F0FDF4', 'border' => '#86EFAC'],
        'rechazado' => ['color' => '#B91C1C', 'bg' => '#FEF2F2', 'border' => '#CBCBCB'],
        default     => ['color' => '#6b7280', 'bg' => '#f3f4f6', 'border' => '#d1d5db'],
    };
    $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad);
    $cuotasNum   = $plan?->cantidad_cuotas ?? null;
    $montoCuota  = ($cuotasNum && $cuotasNum > 0) ? $p->total / $cuotasNum : null;
@endphp

<style>
.em-tomar-wrap { display:flex; flex-direction:column; gap:8px; margin-top:16px; }
@media (min-width:640px) {
    .em-tomar-wrap { flex-direction:row; justify-content:flex-end; }
}
</style>

<div style="max-width:900px; margin:0 auto;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; margin-bottom:12px;">
        <span style="font-size:11px; color:#CBCBCB; white-space:nowrap; margin-left:auto;">{{ $p->created_at->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Cabecera --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 14px;">
            SOLICITUD DE CRÉDITO
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; letter-spacing:0.02em; margin:0 0 8px;">
            Nro. {{ $p->numero }}
        </p>
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:{{ $estadoConfig['color'] }};">
            {{ $p->estado_badge['label'] }}
        </span>
    </div>

    <div class="em-body" style="padding:12px 0 16px;">

        {{-- Dato Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Dato Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        @php
        $eField = 'background:#fff; border:1px solid #E5E7EB; border-radius:8px; padding:9px 12px; font-size:13px; font-weight:600; color:#111827; min-height:38px; display:flex; align-items:center;';
        $eLabel = 'font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#6B7280; margin:0 0 4px 0;';
        $eCard  = 'border:1px solid #E5E7EB; border-radius:12px; padding:14px 16px; background:#fff;';
        $eSec   = 'display:flex; align-items:center; gap:6px; margin-bottom:12px;';
        @endphp

        <div x-data="{ modal: false }">
            <div style="background:#fff; border:1px solid #FED7AA; border-radius:10px; padding:14px 16px; text-align:center; margin-bottom:4px; cursor:pointer; box-shadow:0 2px 8px rgba(249,115,22,0.08);"
                 @click="modal = true">
                <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom:3px;">
                    <svg width="11" height="11" fill="none" stroke="#EA580C" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span style="font-size:13px; font-weight:800; color:#EA580C; letter-spacing:0.05em;">Ver Cliente</span>
                </div>
                <span style="font-size:16px; font-weight:800; color:#111827; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</span>
                <span style="font-size:12px; font-weight:600; color:#6B7280; display:block;">CI: {{ $p->cliente->ci ?: '—' }}</span>
            </div>

            <template x-teleport="body">
                <div x-show="modal"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     style="position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:16px; background:rgba(0,0,0,.45);"
                     @click.self="modal = false" @keydown.escape.window="modal = false">

                    <div style="background:#fff; border-radius:20px; width:100%; max-width:460px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 24px 64px rgba(0,0,0,.22);">

                        <div style="padding:16px 20px; border-bottom:1px solid #F3F4F6; display:flex; align-items:center; gap:12px; flex-shrink:0;">
                            <div style="width:36px; height:36px; border-radius:10px; background:#FFEDD5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="18" height="18" fill="none" stroke="#EA580C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <p style="font-size:16px; font-weight:800; color:#111827; margin:0; flex:1;">Datos del Cliente</p>
                            <button @click="modal = false"
                                    style="width:32px; height:32px; border:1px solid #E5E7EB; background:#fff; color:#9CA3AF; border-radius:9px; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                                    @mouseenter="$el.style.background='#F9FAFB'" @mouseleave="$el.style.background='#fff'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div style="overflow:auto; flex:1; padding:16px 20px; display:flex; flex-direction:column; gap:12px;">

                            <div style="{{ $eCard }}">
                                <div style="{{ $eSec }}">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#F97316; flex-shrink:0;"></div>
                                    <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.6px;">Datos Personales</span>
                                </div>
                                <div style="margin-bottom:10px;">
                                    <p style="{{ $eLabel }}">Nombre</p>
                                    <div style="{{ $eField }}">{{ $p->cliente->nombre_completo }}</div>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div><p style="{{ $eLabel }}">CI</p><div style="{{ $eField }} font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</div></div>
                                    <div><p style="{{ $eLabel }}">Teléfono</p><div style="{{ $eField }}">{{ $p->cliente->telefono ?: '—' }}</div></div>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                    <div><p style="{{ $eLabel }}">NIT</p><div style="{{ $eField }}">{{ $p->cliente->nit ?: '—' }}</div></div>
                                    <div><p style="{{ $eLabel }}">Correo</p><div style="{{ $eField }} word-break:break-all; align-items:flex-start; padding-top:9px;">{{ $p->cliente->correo ?: '—' }}</div></div>
                                </div>
                            </div>

                            <div style="{{ $eCard }}">
                                <div style="{{ $eSec }}">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#F97316; flex-shrink:0;"></div>
                                    <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.6px;">Dirección</span>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div><p style="{{ $eLabel }}">Ciudad</p><div style="{{ $eField }}">{{ $p->cliente->ciudad ?: '—' }}</div></div>
                                    <div><p style="{{ $eLabel }}">Provincia</p><div style="{{ $eField }}">{{ $p->cliente->provincia ?: '—' }}</div></div>
                                    <div><p style="{{ $eLabel }}">Municipio</p><div style="{{ $eField }}">{{ $p->cliente->municipio ?: '—' }}</div></div>
                                </div>
                                <div><p style="{{ $eLabel }}">Dirección</p><div style="{{ $eField }}">{{ $p->cliente->direccion ?: '—' }}</div></div>
                            </div>

                        </div>

                        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; flex-shrink:0;">
                            <button @click="modal = false"
                                    style="width:100%; padding:10px; background:#F9FAFB; border:1px solid #E5E7EB; border-radius:10px; font-size:13px; font-weight:700; color:#374151; cursor:pointer;"
                                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#F9FAFB'">
                                Cerrar
                            </button>
                        </div>

                    </div>
                </div>
            </template>
        </div>

        {{-- Motivo de rechazo / Notas --}}
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
        @php
            $docs = ['Anverso CI' => $p->doc_anverso_ci, 'Reverso CI' => $p->doc_reverso_ci, 'Anverso Doc.' => $p->doc_anverso_doc, 'Reverso Doc.' => $p->doc_reverso_doc, 'Aviso de Luz' => $p->doc_aviso_luz];
            $docIconos = [
                'Anverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
                'Reverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
                'Anverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'Reverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'Aviso de Luz' => 'M13 10V3L4 14h7v7l9-11h-7z',
            ];
        @endphp
        <style>@media(min-width:400px){.doc-em-grid{grid-template-columns:repeat(5,1fr)!important;}}</style>
        <div class="doc-em-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
        @foreach ($docs as $label => $path)
        @if ($path)
        @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
        <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
            <div style="border:1.5px solid #0F6E56; background:#F0FDF4; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box;">
                <div style="width:24px; height:24px; border-radius:6px; background:#DCFCE7; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:13px;height:13px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:#0F6E56;">{{ $label }}</span>
                <span style="display:inline-flex; align-items:center; gap:2px; font-size:8px; color:#0F6E56;"><svg style="width:9px;height:9px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Descargar</span>
            </div>
        </a>
        @else
        <div style="border:1.5px dashed #9CA3AF; background:#fff; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:68px; box-sizing:border-box;">
            <div style="width:24px; height:24px; border-radius:6px; background:#EEEDFE; display:flex; align-items:center; justify-content:center;"><svg style="width:13px;height:13px;" fill="none" stroke="#534AB7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $docIconos[$label] ?? 'M9 12h6m-6 4h6' }}"/></svg></div>
            <span style="font-size:9px; font-weight:600; display:block; line-height:1.2; color:#534AB7;">{{ $label }}</span>
            <span style="font-size:8px; color:#AFA9EC;">Sin archivo</span>
        </div>
        @endif
        @endforeach
        </div>

        {{-- Artículos Seleccionados --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Artículos Seleccionados</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="display:flex; flex-direction:column; gap:8px;">
            @foreach ($p->items as $item)
            <div style="background:#fff; border:1.5px solid #D1D5DB; border-radius:12px; padding:14px 12px; box-shadow:0 2px 4px rgba(0,0,0,0.06), 0 8px 20px rgba(0,0,0,0.10);">
                <div style="display:flex; align-items:flex-start; gap:8px; margin-bottom:6px;">
                    <div style="width:24px; height:24px; border-radius:50%; background:#f97316; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
                        <span style="font-size:11px; font-weight:800; color:#fff; line-height:1;">{{ $item->cantidad }}</span>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <span style="font-size:16px; font-weight:800; color:#111827; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1.2;">{{ ucwords(strtolower($item->product?->name ?? '—')) }}</span>
                        @if ($item->product?->code)
                        <span style="font-size:10px; font-weight:600; color:#9CA3AF; display:block; margin-top:1px;">{{ $item->product->code }}</span>
                        @endif
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:2px 16px; padding-top:6px; border-top:1px solid #f3f4f6;">
                    <span style="font-size:11px; color:#6B7280;">Precio Bs <strong style="color:#374151;">{{ number_format($item->precio_unitario, 2) }}</strong></span>
                    <span style="font-size:11px; color:#6B7280;">Puntos <strong style="color:#0F6E56;">{{ $item->puntos }}</strong></span>
                    <span style="font-size:11px; color:#6B7280;">Total Bs <strong style="color:#7c3aed;">{{ number_format($item->subtotal, 2) }}</strong></span>
                    <span style="font-size:11px; color:#6B7280;">Total Puntos <strong style="color:#0F6E56;">+{{ $item->puntos * $item->cantidad }}</strong></span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Resumen --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Resumen</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div style="background:#fff; border:1.5px solid #C4B5FD; border-radius:16px; overflow:hidden; box-shadow:0 4px 20px rgba(123,111,232,0.18), 0 1px 4px rgba(123,111,232,0.10);">
            <div style="height:4px; background:linear-gradient(90deg,#7B6FE8 0%,#f97316 100%);"></div>
            <div style="padding:14px;">
                <div style="margin-bottom:8px; text-align:center;">
                    <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Total Pedido Bs.</span>
                    <span style="font-size:26px; font-weight:900; color:#3C3489; line-height:1; display:block;">{{ number_format($p->total, 2) }}</span>
                </div>
                <div style="height:1px; background:#EDE9FE; margin-bottom:8px;"></div>
                <div style="display:grid; grid-template-columns:{{ $cuotasNum ? 'repeat(3,1fr)' : '1fr' }}; text-align:center;">
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Puntos</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($totalPuntos) }}</span>
                    </div>
                    @if ($cuotasNum)
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">N° Cuotas</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotasNum }}</span>
                    </div>
                    <div style="padding:0 6px;">
                        <span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Monto x Cuota</span>
                        <span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($montoCuota, 2) }}</span>
                    </div>
                    @endif
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
        <div class="bg-white overflow-hidden" style="border:0.5px solid #CECBF6; border-radius:10px;">
            <div class="grid px-3 py-2" style="background:#F8F7FF; grid-template-columns:1fr 1fr 1fr;">
                <p style="font-size:10px; font-weight:600; color:#6b7280;">Cuota</p>
                <p style="font-size:10px; font-weight:600; color:#6b7280;">Vencimiento</p>
                <p style="font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto Bs.</p>
            </div>
            @foreach ($plan->cuotas as $cuota)
            <div class="grid items-center px-3 py-2.5"
                 style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }} grid-template-columns:1fr 1fr 1fr;">
                <span style="font-size:11px; font-weight:600; color:{{ $cuota->numero === 0 ? '#0F6E56' : '#374151' }};">
                    {{ $cuota->numero === 0 ? 'Inicial' : 'Cuota '.$cuota->numero }}
                </span>
                <p style="font-size:11px; color:#6b7280;">{{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}</p>
                <p style="font-size:13px; font-weight:700; color:#7c3aed; text-align:right;">{{ number_format($cuota->monto, 2) }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Dirección de Entrega --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección de Entrega</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
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

    </div>{{-- /em-body --}}

    {{-- Botones acción --}}
    <div class="em-tomar-wrap">
        <button wire:click="backToList"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Regresar
        </button>
        <button wire:click="tomarRevision({{ $p->id }})"
                wire:confirm="¿Tomás este pedido para revisión? Quedará asignado a vos."
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Para Revisión
        </button>
    </div>

</div>

@endif
</div>
