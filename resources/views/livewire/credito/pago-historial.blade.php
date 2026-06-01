<div>

<style>
.ph-btns { display:flex; flex-direction:column; gap:8px; margin-top:20px; }
@media (min-width:640px) { .ph-btns { flex-direction:row; } }
</style>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-2.5 mb-5">
    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, nombre o pedido..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse($pagos as $pg)
    @php $esAnulado = $pg->estado === 'anulado'; @endphp
    <div wire:key="pg-mob-{{ $pg->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden; {{ $esAnulado ? 'opacity:0.7;' : '' }}">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($pg->pedido->cliente->nombre_completo, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ ucwords(strtolower($pg->pedido->cliente->nombre_completo)) }}</p>
                <p style="font-size:12px; color:#9CA3AF; margin:2px 0 0;">CI: {{ $pg->pedido->cliente->ci ?: '—' }}</p>
            </div>
            @if($esAnulado)
            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; flex-shrink:0; background:#FEE2E2; color:#DC2626;">Anulado</span>
            @else
            <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; flex-shrink:0; background:#D1FAE5; color:#059669;">Activo</span>
            @endif
        </div>
        <div style="padding:10px 14px; display:flex; gap:8px; flex-wrap:wrap;">
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Código</span>
                <span style="font-size:12px; font-weight:700; color:#7B6FE8; font-family:monospace; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}">{{ $pg->numero }}</span>
            </div>
            <div style="flex:1;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Monto</span>
                <span style="font-size:13px; font-weight:700; color:#374151; font-family:monospace;">Bs. {{ number_format($pg->monto_total, 2) }}</span>
            </div>
            <div style="text-align:right;">
                <span style="font-size:11px; color:#9CA3AF; display:block;">Fecha</span>
                <span style="font-size:12px; font-weight:600; color:#374151;">{{ $pg->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6;">
            <button wire:click="verPago({{ $pg->id }})"
                    style="width:100%; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px; -webkit-appearance:none; appearance:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 24px;">
        <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pagos registrados</p>
    </div>
    @endforelse
    @if($pagos->hasPages())
    <div style="padding-top:8px;">{{ $pagos->links() }}</div>
    @endif
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Historial de Pagos</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pagos->total() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:780px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Código</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cliente</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Pedido</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Cuotas</th>
                <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Monto</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Fecha</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Estado</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Registrado por</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagos as $pg)
            @php $esAnulado = $pg->estado === 'anulado'; @endphp
            <tr wire:key="pg-{{ $pg->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s; {{ $esAnulado ? 'opacity:0.6;' : '' }}"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap;">{{ $pagos->firstItem() + $loop->index }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#7B6FE8; white-space:nowrap; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}">{{ $pg->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $pg->pedido->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($pg->pedido->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; color:#111827; white-space:nowrap;">{{ $pg->pedido->numero }}</td>
                <td style="padding:10px 14px; text-align:center; font-weight:700;">{{ $pg->cantidad_cuotas }}</td>
                <td style="padding:10px 14px; text-align:right; font-family:monospace; font-weight:700; white-space:nowrap;">Bs. {{ number_format($pg->monto_total, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:11px; color:#6B7280; white-space:nowrap;">{{ $pg->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    @if($esAnulado)
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#FEE2E2; color:#DC2626;">Anulado</span>
                    @else
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#D1FAE5; color:#059669;">Activo</span>
                    @endif
                </td>
                <td style="padding:10px 14px; font-size:12px; color:#6B7280; white-space:nowrap;">{{ $pg->creadoPor->name ?? '—' }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <div style="display:flex; align-items:center; justify-content:center; gap:5px;">
                        <button wire:click.stop="verPago({{ $pg->id }})"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                                @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Ver">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        @if(!$esAnulado && $pg->planPago?->estado === 'activo')
                        <button wire:click.stop="anularPago({{ $pg->id }})"
                                wire:confirm="¿Anular este pago? Las cuotas volverán a estado pendiente."
                                style="height:28px; padding:0 8px; border-radius:7px; border:1px solid #FECACA; background:#FEF2F2; color:#DC2626; font-size:11px; font-weight:700; cursor:pointer; white-space:nowrap; -webkit-appearance:none; appearance:none;"
                                @mouseenter="$el.style.background='#FEE2E2'" @mouseleave="$el.style.background='#FEF2F2'">
                            Anular
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr wire:key="pg-empty">
                <td colspan="11" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin pagos registrados</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pagos->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $pagos->links() }}</div>
    @endif
</div>

{{-- ══ DETALLE ══ --}}
@elseif($mode === 'detalle' && $pagoDetalle)
@php
    $pg          = $pagoDetalle;
    $cuotas      = $cuotasDetalle;
    $esAnulado   = $pg->estado === 'anulado';
    $pgVersion   = $pg->planPago?->version ?? 1;
    $pgPlanLabel = $pgVersion > 1 ? 'Reprogramación: V' . $pgVersion : 'Plan Original';
@endphp

<div style="max-width:900px; margin:0 auto;">

    {{-- Cabecera lila --}}
    <div style="background:#EDE9FE; border:1px solid #C4B5FD; border-radius:14px; padding:16px 18px; margin:0 0 4px; text-align:center;">
        <h1 style="font-size:20px; font-weight:800; color:#534AB7; letter-spacing:-0.3px; margin:0 0 10px;">
            DETALLE DE PAGO
        </h1>
        <p style="font-size:15px; font-weight:700; color:#534AB7; font-family:monospace; margin:0 0 8px;">
            {{ $pg->numero }}
        </p>
        @if($esAnulado)
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:#DC2626;">ANULADO</span>
        @else
        <span style="font-size:14px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; color:#15803D;">ACTIVO</span>
        @endif
    </div>

    <div style="padding:12px 0 16px;">

        {{-- Separador Datos del Cliente --}}
        <div style="display:flex; align-items:center; gap:7px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em;">Datos del Cliente</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div x-data="{ modal: false }" style="background:#fff; border:1px solid #E5E7EB; border-radius:10px; padding:14px 16px; margin-bottom:4px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                <p style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#9CA3AF; margin:0;">Cliente</p>
                <button @click="modal = true"
                        style="display:flex; align-items:center; gap:5px; padding:5px 10px; border:1px solid #EDE9FE; border-radius:7px; background:#F5F3FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Ver datos
                </button>
            </div>
            <p style="font-size:14px; font-weight:600; color:#374151; margin:0;">
                {{ $pg->pedido->cliente->ci ? $pg->pedido->cliente->ci . ' — ' : '' }}{{ $pg->pedido->cliente->nombre_completo }}
            </p>

            {{-- Modal datos cliente --}}
            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 style="background:rgba(20,10,40,0.4);"
                 @click.self="modal = false">
                <div x-show="modal"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;width:100%;max-width:420px;overflow:hidden;position:relative;max-height:90vh;overflow-y:auto;">
                    <div style="padding:16px 18px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;justify-content:space-between;">
                        <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Datos del Cliente</p>
                        <button @click="modal = false"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #E5E7EB; background:#F9FAFB; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center; -webkit-appearance:none; appearance:none;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @php $cli = $pg->pedido->cliente; @endphp
                    <div style="padding:16px 18px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div><span class="ds-form-label">Nombre</span><p style="font-size:12px;font-weight:600;color:#4A4A4A;margin:0;">{{ $cli->nombre_completo }}</p></div>
                        <div><span class="ds-form-label">CI</span><p style="font-size:12px;font-weight:600;color:#4A4A4A;margin:0;font-family:monospace;">{{ $cli->ci ?: '—' }}</p></div>
                        <div><span class="ds-form-label">Teléfono</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ $cli->telefono ?: '—' }}</p></div>
                        <div><span class="ds-form-label">Correo</span><p style="font-size:12px;color:#4A4A4A;margin:0;word-break:break-all;">{{ $cli->correo ?: '—' }}</p></div>
                        @if($cli->ciudad)<div><span class="ds-form-label">Ciudad</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ strtoupper($cli->ciudad) }}</p></div>@endif
                        @if($cli->direccion)<div><span class="ds-form-label">Dirección</span><p style="font-size:12px;color:#4A4A4A;margin:0;">{{ $cli->direccion }}</p></div>@endif
                    </div>
                </div>
            </div>
        </div>

        @if($confirmandoAnulacion)
        <div style="background:#FFF0F0; border:1px solid #FCA5A5; border-radius:10px; padding:14px 18px; margin-bottom:14px;">
            <p style="font-size:13px; font-weight:700; color:#DC2626; margin:0 0 6px;">Confirmar anulación</p>
            <p style="font-size:12px; color:#6b7280; margin:0 0 12px;">
                Las {{ $pg->cuotas->where('numero','>',0)->count() }} cuota(s) volverán a estado <strong>pendiente</strong>. Esta acción no se puede deshacer.
            </p>
            <div style="display:flex; gap:8px;">
                <button wire:click="$set('confirmandoAnulacion', false)"
                        style="flex:1; padding:10px; background:#F4F4F4; color:#6D8196; font-size:13px; font-weight:700; border-radius:8px; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">Cancelar</button>
                <button wire:click="anularPago" wire:loading.attr="disabled"
                        style="flex:1; padding:10px; background:#DC2626; color:#fff; font-size:13px; font-weight:700; border-radius:8px; border:none; cursor:pointer; -webkit-appearance:none; appearance:none;">
                    <span wire:loading.remove wire:target="anularPago">Sí, anular pago</span>
                    <span wire:loading wire:target="anularPago">Anulando...</span>
                </button>
            </div>
        </div>
        @endif

        {{-- Separador Cuotas --}}
        <div style="display:flex; align-items:center; gap:7px; margin-top:20px; margin-bottom:12px;">
            <svg width="14" height="14" fill="none" stroke="#9CA3AF" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span style="font-size:12px; font-weight:700; color:#6B7280; letter-spacing:0.05em; white-space:nowrap;">{{ $esAnulado ? 'Cuotas Anuladas' : 'Cuotas Pagadas' }}</span>
            <div style="flex:1; height:1.5px; background:#D1D5DB;"></div>
        </div>

        <div style="background:#fff; border:0.5px solid #CECBF6; border-radius:10px; overflow:hidden; margin-bottom:14px;">
            <div style="padding:10px 14px; border-bottom:1px solid #EDE9FE; display:flex; align-items:center; gap:8px; background:#F8F7FF; flex-wrap:wrap;">
                <span style="font-size:12px; font-weight:700; color:#534AB7;">{{ $pgPlanLabel }}</span>
                <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">{{ $cuotas->count() }} cuota{{ $cuotas->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#F8F7FF;">
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Cuota</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:right;">Monto</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Vencimiento</th>
                        <th style="padding:8px 12px; font-size:10px; font-weight:600; color:#6b7280; text-align:center;">Fecha pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $c)
                    <tr wire:key="dc-{{ $c->id }}" style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
                        <td style="padding:8px 12px; text-align:center; font-size:11px; font-weight:600; color:#374151;">Cuota {{ $c->numero }}</td>
                        <td style="padding:8px 12px; text-align:right; font-family:monospace; font-weight:700; color:#7c3aed; font-size:13px;">Bs. {{ number_format($c->monto, 2) }}</td>
                        <td style="padding:8px 12px; text-align:center; font-size:11px; color:#6b7280;">{{ $c->fecha_vencimiento ? $c->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                        <td style="padding:8px 12px; text-align:center;">
                            @if($c->fecha_pago)
                            <span style="font-size:11px; font-weight:600; color:#059669;">{{ $c->fecha_pago->format('d/m/Y') }}</span>
                            @else
                            <span style="font-size:11px; color:#D1D5DB;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding:32px 12px; text-align:center; font-size:13px; color:#9CA3AF;">Sin cuotas</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background:#F8F7FF; border-top:1.5px solid #EDE9FE;">
                        <td colspan="4" style="padding:10px 16px; text-align:center;">
                            Total:
                            <span style="font-family:monospace; font-size:15px; font-weight:800; color:{{ $esAnulado ? '#DC2626' : '#059669' }}; {{ $esAnulado ? 'text-decoration:line-through;' : '' }}margin-left:6px;">
                                Bs. {{ number_format($pg->monto_total, 2) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        @if($esAnulado)
        <div style="background:#FFF0F0; border:1px solid #FCA5A5; border-radius:10px; padding:12px 14px; display:flex; align-items:center; gap:10px; margin-bottom:4px;">
            <svg style="width:16px; height:16px; flex-shrink:0;" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p style="font-size:11px; font-weight:700; color:#DC2626; margin:0;">Pago anulado</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">Por {{ $pg->anuladoPor->name ?? '—' }} el {{ $pg->anulado_at?->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        @endif

    </div>{{-- /body --}}

    {{-- Botones grandes al pie --}}
    <div class="ph-btns">
        <button wire:click="volver"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #CBCBCB; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Volver
        </button>
        @if($pg->esAnulable && !$confirmandoAnulacion)
        <button wire:click="$set('confirmandoAnulacion', true)"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:#FEF2F2; color:#DC2626; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; box-sizing:border-box; border:1.5px solid #FCA5A5; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Anular Pago
        </button>
        @endif
    </div>

</div>

@endif
</div>
