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
    <div wire:key="rc-{{ $p->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($p->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $p->numero }}</p>
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
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">En Revisión</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:800px; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cod. Pedido</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Vendedor</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Fecha</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Total Bs.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
            <tr wire:key="rd-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 14px; font-family:monospace; font-size:12px; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ $p->cliente->nombre_completo }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#6B7280; white-space:nowrap;">{{ $p->vendedor->user->name ?? '—' }}</td>
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
                            title="Revisar">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr wire:key="rd-empty">
                <td colspan="7" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px; margin-bottom:4px;">Sin pedidos en revisión</p>
                    <p style="font-size:12px; color:#9CA3AF;">Tomá pedidos desde "En Espera"</p>
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
    $p            = $pedidoDetalle;
    $plan         = $p->planPago;
    $estadoConfig = match($p->estado) {
        'en_espera' => ['color'=>'#854F0B','bg'=>'#FEF3C7','border'=>'#FCD34D'],
        'revision'  => ['color'=>'#0369A1','bg'=>'#F0F9FF','border'=>'#7DD3FC'],
        'aprobado'  => ['color'=>'#15803D','bg'=>'#F0FDF4','border'=>'#86EFAC'],
        'rechazado' => ['color'=>'#B91C1C','bg'=>'#FEF2F2','border'=>'#CBCBCB'],
        default     => ['color'=>'#6b7280','bg'=>'#f3f4f6','border'=>'#d1d5db'],
    };
    $totalPuntos = $p->items->sum(fn($i) => $i->puntos * $i->cantidad);
    $cuotasNum   = $plan?->cantidad_cuotas ?? null;
    $montoCuota  = ($cuotasNum && $cuotasNum > 0) ? $p->total / $cuotasNum : null;
    $docs = ['Anverso CI'=>$p->doc_anverso_ci,'Reverso CI'=>$p->doc_reverso_ci,'Anverso Doc.'=>$p->doc_anverso_doc,'Reverso Doc.'=>$p->doc_reverso_doc,'Aviso de Luz'=>$p->doc_aviso_luz];
    $docCampos = ['Anverso CI'=>'doc_anverso_ci','Reverso CI'=>'doc_reverso_ci','Anverso Doc.'=>'doc_anverso_doc','Reverso Doc.'=>'doc_reverso_doc','Aviso de Luz'=>'doc_aviso_luz'];
    $docProps  = ['doc_anverso_ci'=>'docAnversoCi','doc_reverso_ci'=>'docReversoCi','doc_anverso_doc'=>'docAnversoDoc','doc_reverso_doc'=>'docReversoDoc','doc_aviso_luz'=>'docAvisoLuz'];
@endphp

<style>
.rv-act-wrap { display:flex; flex-direction:column; gap:8px; margin-top:16px; }
@media (min-width:640px) { .rv-act-wrap { flex-direction:row; } }
</style>

<div style="max-width:900px; margin:0 auto;">

    {{-- Nav --}}
    <div style="display:flex; align-items:center; margin-bottom:12px;">
        <span style="font-size:11px; color:#CBCBCB; white-space:nowrap; margin-left:auto;">{{ $p->created_at->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Cabecera --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 14px;">SOLICITUD DE CRÉDITO</h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; letter-spacing:0.02em; margin:0 0 8px;">Nro. {{ $p->numero }}</p>
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:{{ $estadoConfig['color'] }};">{{ $p->estado_badge['label'] }}</span>
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Dato Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Dato Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>
        <div x-data="{ modal: false }">
            <div style="background:#fff; border:1px solid #EDE9FE; border-radius:10px; padding:14px 16px; text-align:center; margin-bottom:4px; cursor:pointer; box-shadow:0 2px 8px rgba(123,111,232,0.08);" @click="modal = true">
                <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom:3px;">
                    <svg width="11" height="11" fill="none" stroke="#DC2626" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span style="font-size:13px; font-weight:800; color:#DC2626; letter-spacing:0.05em;">Ver Cliente</span>
                </div>
                <span style="font-size:19px; font-weight:800; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->cliente->nombre_completo }}</span>
                <span style="font-size:13px; font-weight:700; color:#7B6FE8; display:block;">CI: {{ $p->cliente->ci ?: '—' }}</span>
            </div>
            <div x-show="modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(20,10,40,0.4);" @click.self="modal = false">
                <div x-show="modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="background:#EEEDF7; border-radius:18px; width:100%; max-width:420px; overflow:hidden; position:relative;">
                    <button @click="modal = false" style="position:absolute; top:14px; right:14px; width:28px; height:28px; border-radius:8px; background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                        <svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div style="padding:20px 18px 18px;">
                        @php
                        $fs='background:#fff; border-radius:10px; padding:8px 10px; display:flex; align-items:center; gap:8px;';
                        $ib='width:32px; height:32px; border-radius:8px; background:#EEEDFE; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                        $ibSm='width:24px; height:24px; border-radius:6px; background:#EEEDFE; display:flex; align-items:center; justify-content:center; flex-shrink:0;';
                        $vc='font-size:11px; font-weight:700; color:#1E1B5E;';
                        $lc='font-size:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#AFA9EC; margin-bottom:1px;';
                        @endphp
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;"><span style="font-size:12px; font-weight:700; color:#534AB7; white-space:nowrap;">Datos personales</span><div style="flex:1; height:1px; background:#CECBF6;"></div></div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                            <div style="{{ $fs }}"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Nombre</p><p style="{{ $vc }}">{{ $p->cliente->nombre_completo }}</p></div></div>
                            <div style="{{ $fs }}"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div><div><p style="{{ $lc }}">CI</p><p style="{{ $vc }} font-family:monospace;">{{ $p->cliente->ci ?: '—' }}</p></div></div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                            <div style="{{ $fs }}"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><div><p style="{{ $lc }}">Teléfono</p><p style="{{ $vc }}">{{ $p->cliente->telefono ?: '—' }}</p></div></div>
                            <div style="{{ $fs }}"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div><p style="{{ $lc }}">NIT</p><p style="{{ $vc }}">{{ $p->cliente->nit ?: '—' }}</p></div></div>
                        </div>
                        <div style="{{ $fs }} margin-bottom:16px;"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Correo</p><p style="{{ $vc }} word-break:break-all;">{{ $p->cliente->correo ?: '—' }}</p></div></div>
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;"><span style="font-size:12px; font-weight:700; color:#534AB7; white-space:nowrap;">Datos de dirección</span><div style="flex:1; height:1px; background:#CECBF6;"></div></div>
                        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; margin-bottom:8px;">
                            <div style="{{ $fs }}"><div style="{{ $ibSm }}"><svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Ciudad</p><p style="{{ $vc }}">{{ strtoupper($p->cliente->ciudad ?: '—') }}</p></div></div>
                            <div style="{{ $fs }}"><div style="{{ $ibSm }}"><svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Provincia</p><p style="{{ $vc }}">{{ strtoupper($p->cliente->provincia ?: '—') }}</p></div></div>
                            <div style="{{ $fs }}"><div style="{{ $ibSm }}"><svg width="12" height="12" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Municipio</p><p style="{{ $vc }}">{{ strtoupper($p->cliente->municipio ?: '—') }}</p></div></div>
                        </div>
                        <div style="{{ $fs }}"><div style="{{ $ib }}"><svg width="14" height="14" fill="none" stroke="#534AB7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div><div style="min-width:0;"><p style="{{ $lc }}">Dirección</p><p style="{{ $vc }}">{{ $p->cliente->direccion ?: '—' }}</p></div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notas --}}
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
        <style>@media(min-width:400px){.doc-rv-grid{grid-template-columns:repeat(5,1fr)!important;}}</style>
        <div class="doc-rv-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
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
            <div x-data="{ subido: false }">
                <input type="file" wire:model="{{ $prop }}" x-ref="fi_{{ $campo }}" accept="image/*,.pdf" style="display:none" x-on:livewire-upload-finish="$wire.subirDocumento('{{ $campo }}'); subido = true;">
                <button @click="$refs['fi_{{ $campo }}'].click()" :style="{ background: subido ? '#DCFCE7' : '#F5F3FF' }" style="width:100%; color:#7B6FE8; border:1.5px solid #C4B5FD; font-size:10px; font-weight:600; border-radius:6px; padding:4px 6px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:3px; -webkit-appearance:none; appearance:none;">
                    <svg style="width:10px;height:10px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span x-text="subido ? 'Listo ✓' : '{{ $path ? 'Reemplazar' : 'Subir' }}'"></span>
                </button>
                @error($prop)<p style="font-size:9px; color:#DC2626; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>
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
                        @if ($item->product?->code)<span style="font-size:10px; font-weight:600; color:#9CA3AF; display:block; margin-top:1px;">{{ $item->product->code }}</span>@endif
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
                    <div style="padding:0 6px;"><span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Puntos</span><span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($totalPuntos) }}</span></div>
                    @if ($cuotasNum)
                    <div style="padding:0 6px; border-left:1px solid #EDE9FE; border-right:1px solid #EDE9FE;"><span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">N° Cuotas</span><span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ $cuotasNum }}</span></div>
                    <div style="padding:0 6px;"><span style="font-size:9px; font-weight:800; color:#9CA3AF; text-transform:uppercase; letter-spacing:0.07em; display:block; margin-bottom:2px;">Monto x Cuota</span><span style="font-size:14px; font-weight:900; color:#111827; line-height:1.1;">{{ number_format($montoCuota, 2) }}</span></div>
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
        @if ($plan->version > 1)<p style="font-size:10px; font-weight:700; color:#854F0B; background:#FEF3C7; border-radius:6px; padding:4px 10px; display:inline-block; margin-bottom:10px;">Reprogramado v{{ $plan->version }}</p>@endif
        <div style="border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; background:#fff;">
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; padding:8px 12px; background:#F8F7FF;">
                <p style="font-size:10px; font-weight:600; color:#6b7280;">Cuota</p>
                <p style="font-size:10px; font-weight:600; color:#6b7280;">Vencimiento</p>
                <p style="font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto Bs.</p>
            </div>
            @foreach ($plan->cuotas as $cuota)
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; align-items:center; padding:10px 12px; {{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
                <span style="font-size:11px; font-weight:600; color:{{ $cuota->numero === 0 ? '#0F6E56' : '#374151' }};">{{ $cuota->numero === 0 ? 'Inicial' : 'Cuota '.$cuota->numero }}</span>
                <p style="font-size:11px; color:#6b7280;">{{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}</p>
                <p style="font-size:13px; font-weight:700; color:#7c3aed; text-align:right;">{{ number_format($cuota->monto, 2) }}</p>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Dirección de Entrega --}}
        <div x-data="{ modalDir: false }" @direccion-guardada.window="modalDir = false">
            <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
                <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">Dirección de Entrega</span>
                <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
                <button @click="modalDir = true" style="display:flex; align-items:center; gap:4px; padding:4px 10px; background:#F5F3FF; color:#7B6FE8; font-size:11px; font-weight:700; border-radius:6px; border:1px solid #EDE9FE; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </button>
            </div>
            <div style="background:#fff; border-radius:12px; padding:12px; box-shadow:0 4px 20px rgba(123,111,232,0.10); border:0.5px solid #EDE9FE;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:8px;">
                    <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Ciudad</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_ciudad ? ucwords(strtolower($p->entrega_ciudad)) : '—' }}</div></div>
                    <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Provincia</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_provincia ? ucwords(strtolower($p->entrega_provincia)) : '—' }}</div></div>
                    <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Municipio</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_municipio ? ucwords(strtolower($p->entrega_municipio)) : '—' }}</div></div>
                    <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Dirección</p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->entrega_direccion ?: '—' }}</div></div>
                </div>
                <div><p style="font-size:10px; color:#6B7280; font-weight:700; margin-bottom:3px; text-transform:uppercase; letter-spacing:0.05em;">Referencia <span style="color:#9CA3AF; font-weight:400; text-transform:none;">(opcional)</span></p><div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; font-weight:500;">{{ $p->entrega_referencia ?: '—' }}</div></div>
            </div>
            {{-- Modal editar dirección --}}
            <div x-show="modalDir" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(20,10,40,0.4);" @click.self="modalDir = false">
                <div x-show="modalDir" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="background:#EEEDF7; border-radius:18px; width:100%; max-width:440px; overflow:hidden; position:relative; max-height:90vh; overflow-y:auto;">
                    <button @click="modalDir = false" style="position:absolute; top:14px; right:14px; width:28px; height:28px; border-radius:8px; background:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 1px 4px rgba(0,0,0,0.1);"><svg width="12" height="12" fill="none" stroke="#6b7280" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div style="padding:20px 18px 18px;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;"><span style="font-size:13px; font-weight:700; color:#534AB7;">Dirección de Entrega</span><div style="flex:1; height:1px; background:#CECBF6;"></div></div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                            <div><p style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Ciudad *</p><select wire:model.live="editCiudad" style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"><option value="">-- Seleccionar --</option>@foreach($ciudadesAll as $c)<option value="{{ $c->nombre }}">{{ $c->nombre }}</option>@endforeach</select>@error('editCiudad')<p style="font-size:9px; color:#DC2626; margin-top:2px;">{{ $message }}</p>@enderror</div>
                            <div><p style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Provincia</p><select wire:model.live="editProvincia" style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;" @disabled(!$editCiudad)><option value="">-- Seleccionar --</option>@foreach($editProvincias as $prov)<option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>@endforeach</select></div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                            <div><p style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Municipio</p><select wire:model.live="editMunicipio" style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;" @disabled(!$editProvincia)><option value="">-- Seleccionar --</option>@foreach($editMunicipios as $mun)<option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>@endforeach</select></div>
                            <div><p style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Dirección *</p><input wire:model="editDireccion" type="text" placeholder="Calle y número" style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;">@error('editDireccion')<p style="font-size:9px; color:#DC2626; margin-top:2px;">{{ $message }}</p>@enderror</div>
                        </div>
                        <div style="margin-bottom:16px;"><p style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Referencia <span style="font-weight:400; text-transform:none;">(opcional)</span></p><input wire:model="editReferencia" type="text" placeholder="Portón azul, frente al parque..." style="width:100%; background:#fff; border:1px solid #C4B5FD; border-radius:8px; padding:8px 10px; font-size:12px; color:#3C3489; outline:none; box-sizing:border-box;"></div>
                        <div style="display:flex; gap:8px;">
                            <button @click="modalDir = false" style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:13px; font-weight:700; border-radius:8px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
                            <button wire:click="guardarDireccion" wire:loading.attr="disabled" style="flex:1; padding:10px; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; border-radius:8px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;"><span wire:loading.remove wire:target="guardarDireccion">Guardar</span><span wire:loading wire:target="guardarDireccion">Guardando...</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /body --}}

    {{-- Botones acción --}}
    @if (!$confirmandoRechazo)
    <div class="rv-act-wrap">
        <button wire:click="backToList" style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Regresar
        </button>
        <button wire:click="devolverEspera" wire:confirm="¿Devolvés este pedido a En Espera?" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#F0F9FF; color:#0369A1; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #7DD3FC; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            En Espera
        </button>
        <button wire:click="$set('confirmandoRechazo', true)" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF2F2; color:#B91C1C; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FECACA; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Rechazar
        </button>
        <button wire:click="aprobar" wire:confirm="¿Confirmás la aprobación de este pedido?" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#7B6FE8; color:#fff; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Aprobar
        </button>
    </div>
    @else
    <div style="background:#FEF2F2; border:1.5px solid #FECACA; border-radius:12px; padding:16px; margin-top:16px;">
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#B91C1C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:13px; font-weight:700; color:#B91C1C;">Motivo del rechazo</span>
        </div>
        <textarea wire:model="notaRechazo" rows="3" placeholder="Explicá el motivo del rechazo..." style="width:100%; display:block; background:#fff; border:1px solid #FECACA; border-radius:8px; padding:10px 12px; font-size:13px; color:#374151; outline:none; box-sizing:border-box; resize:vertical;"></textarea>
        @error('notaRechazo')<p style="font-size:11px; color:#B91C1C; margin-top:4px;">{{ $message }}</p>@enderror
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button wire:click="$set('confirmandoRechazo', false)" style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:12px; background:#F4F4F4; color:#6D8196; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <span style="font-size:16px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span> Cancelar
            </button>
            <button wire:click="rechazar" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#B91C1C; color:#fff; font-size:14px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:10px; box-sizing:border-box; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Confirmar Rechazo
            </button>
        </div>
    </div>
    @endif

</div>

@endif
</div>
