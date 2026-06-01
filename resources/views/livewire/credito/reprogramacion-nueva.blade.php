<div>

{{-- ══ BUSCAR ══ --}}
@if($mode === 'buscar')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Nueva Reprogramación</h2>
        <p>Buscá un pedido aprobado para reprogramar su saldo</p>
    </div>
    <span style="font-size:12px;color:#CBCBCB;white-space:nowrap;">{{ $resultados->count() }} pedido{{ $resultados->count() !== 1 ? 's' : '' }}</span>
</div>

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Header --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Pedidos disponibles</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $resultados->count() }}</span>
        <div style="margin-left:auto; position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:#9CA3AF;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="CI, nombre o Nº pedido..."
                   style="padding-left:32px; height:36px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; width:260px; background:#fff;">
        </div>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Código</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Versión</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Total Plan</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Pagado</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Saldo Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cuotas Pend.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $p)
            @php
                $plan      = $p->planPago;
                $pagadas   = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
            @endphp
            <tr wire:key="res-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s; cursor:pointer;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap;">{{ $loop->iteration }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">v{{ $plan?->version ?? 1 }}</span>
                </td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; color:#111827;">{{ number_format($plan?->total_pagar ?? 0, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; color:#059669;">{{ number_format($pagadas, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:12px; font-family:monospace; font-weight:700; color:#DC2626;">{{ number_format($pendiente, 2) }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#111827;">{{ $nPend }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="seleccionarPedido({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Ver">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">{{ strlen(trim($search)) >= 2 ? 'Sin resultados para esa búsqueda.' : 'No hay pedidos aprobados con saldo pendiente.' }}</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══ PREVIEW ══ --}}
@elseif($mode === 'preview' && $pedidoDetalle)
@php
    $p        = $pedidoDetalle;
    $plan     = $p->planPago;
    $cuotas   = $plan?->cuotas ?? collect();
    $pagadas  = $cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto');
    $pendiente= $cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto');
    $nPend    = $cuotas->where('estado','!=','pagado')->where('numero','>',0)->count();
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:40px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Búsqueda
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }} — Plan activo del pedido</p>
        </div>
        <span class="ds-badge ds-badge-aprobado">v{{ $plan?->version ?? 1 }}</span>
    </div>

    <div class="ds-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:14px;">
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</div>
            <div class="ds-stat-label">Total plan</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#059669;">Bs. {{ number_format($pagadas, 2) }}</div>
            <div class="ds-stat-label">Pagado</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</div>
            <div class="ds-stat-label">Pendiente</div>
        </div>
    </div>

    <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:10px 14px;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;justify-content:space-between;background:#F8F7FF;">
            <p style="font-size:12px;font-weight:700;color:#534AB7;margin:0;">Plan Activo — v{{ $plan?->version ?? 1 }}</p>
            <span style="font-size:11px;color:#CBCBCB;">{{ $plan?->matriz_nombre }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#F8F7FF;">
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">#</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Cuotas</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Monto</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Vencimiento</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas->where('numero','>',0)->sortBy('numero') as $c)
                @php
                    $badge = $c->estadoFinancieroBadge;
                @endphp
                <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                    <td style="padding:8px 12px;font-size:13px;text-align:center;">{{ $c->numero }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;font-weight:600;">Cuota {{ $c->numero }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;font-family:monospace;font-weight:700;color:#7c3aed;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td style="padding:8px 12px;text-align:center;"><span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="ds-empty"><p>Sin cuotas</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($nPend > 0)
    <div style="display:flex;justify-content:flex-end;">
        <button wire:click="irForm" class="ds-btn ds-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Nueva Reprogramación
        </button>
    </div>
    @else
    <div style="text-align:center;padding:16px;background:#F7F7F0;border-radius:8px;color:#6D8196;font-size:13px;font-weight:600;">
        Todas las cuotas están pagadas — no hay saldo a reprogramar.
    </div>
    @endif
</div>

{{-- ══ FORM ══ --}}
@elseif($mode === 'form' && $pedidoDetalle)
@php
    $p         = $pedidoDetalle;
    $plan      = $p->planPago;
    $pendiente = round((float)($plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0), 2);
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:60px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Ver plan
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }} — Saldo a reprogramar: <strong style="color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</strong></p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
            <span class="ds-badge ds-badge-cerrado">v{{ $plan?->version ?? 1 }}</span>
            <svg width="12" height="12" fill="none" stroke="#CBCBCB" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            <span class="ds-badge ds-badge-aprobado">v{{ ($plan?->version ?? 1) + 1 }}</span>
        </div>
    </div>

    {{-- Configurador --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px 18px;margin-bottom:14px;">
        <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 12px;">Configurar plan propuesto</p>
        <div style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;">
            <div style="flex:0 0 160px;">
                <label class="ds-form-label">Cantidad de cuotas</label>
                <input wire:model="cantidadCuotas" type="number" min="1" max="120" placeholder="Ej: 6" style="width:100%;">
            </div>
            <div style="flex:0 0 190px;">
                <label class="ds-form-label">Fecha 1ª cuota</label>
                <input wire:model="fechaPrimera" type="date" style="width:100%;">
            </div>
            <div>
                <button wire:click="generarPlan" class="ds-btn ds-btn-primary ds-btn-sm">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Generar plan
                </button>
            </div>
        </div>
    </div>

    {{-- Cuotas editables con cuadre Alpine en tiempo real --}}
    <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;margin-bottom:14px;"
         x-data="{
            saldo: {{ $pendiente }},
            recalc() {
                let inputs = this.$el.querySelectorAll('.monto-cuota');
                let total = Array.from(inputs).reduce((s, el) => s + (parseFloat(el.value) || 0), 0);
                this.$refs.totalDisplay.textContent = 'Bs. ' + total.toFixed(2);
                let diff = Math.round((total - this.saldo) * 100) / 100;
                let el = this.$refs.diffDisplay;
                if (Math.abs(diff) < 0.01) {
                    el.textContent = '✓ Cuadra exacto';
                    el.style.color = '#059669';
                } else if (diff > 0) {
                    el.textContent = '+Bs. ' + diff.toFixed(2) + ' sobre saldo';
                    el.style.color = '#B45309';
                } else {
                    el.textContent = '−Bs. ' + Math.abs(diff).toFixed(2) + ' bajo saldo';
                    el.style.color = '#DC2626';
                }
            }
         }"
         x-init="recalc()">
        <div style="padding:10px 14px;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;justify-content:space-between;background:#F8F7FF;">
            <span style="font-size:12px;font-weight:700;color:#534AB7;">Cuotas del nuevo plan</span>
            <button wire:click="agregarCuota" @click="$nextTick(()=>recalc())" style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#EDE9FE;color:#534AB7;font-size:12px;font-weight:700;border:1px solid #C4B5FD;border-radius:8px;cursor:pointer;-webkit-appearance:none;appearance:none;">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar cuota
            </button>
        </div>
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#F8F7FF;">
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">#</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Cuotas</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Monto (Bs.)</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Fecha vencimiento</th>
                    <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nuevasCuotas as $i => $cuota)
                <tr wire:key="nc-{{ $i }}" style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}">
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;font-weight:600;">{{ $cuota['numero'] }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;font-weight:600;">Cuota {{ $cuota['numero'] }}</td>
                    <td style="padding:8px 12px;text-align:center;">
                        <input wire:model="nuevasCuotas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                               class="monto-cuota" @input="recalc()" style="width:90%;padding:4px 8px;border:1px solid #C4B5FD;border-radius:6px;font-size:12px;text-align:center;outline:none;background:#fff;">
                        @error("nuevasCuotas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:8px 12px;text-align:center;">
                        <input wire:model="nuevasCuotas.{{ $i }}.fecha" type="date" style="width:90%;padding:4px 8px;border:1px solid #C4B5FD;border-radius:6px;font-size:12px;outline:none;background:#fff;">
                        @error("nuevasCuotas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:8px 12px;text-align:center;">
                        @if(count($nuevasCuotas) > 1)
                        <button wire:click="quitarCuota({{ $i }})" @click="$nextTick(()=>recalc())" style="width:28px;height:28px;border-radius:7px;border:1px solid #FECACA;background:#FEF2F2;color:#DC2626;cursor:pointer;display:flex;align-items:center;justify-content:center;margin:0 auto;-webkit-appearance:none;appearance:none;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#F5F3FF;border-top:1.5px solid #EDE9FE;">
                    <td colspan="5" style="padding:10px 16px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:12px;font-weight:700;color:#534AB7;">Total: <span x-ref="totalDisplay" style="font-family:monospace;font-weight:700;color:#111827;margin-left:6px;"></span></span>
                            <span x-ref="diffDisplay" style="font-size:11px;font-weight:700;"></span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px 18px;margin-bottom:14px;">
        <label class="ds-form-label">Motivo de la reprogramación <span style="color:#DC2626;">*</span></label>
        <textarea wire:model="motivo" rows="3" placeholder="Ej: Cliente solicitó extensión de plazo por dificultades económicas..." style="width:100%;display:block;resize:vertical;"></textarea>
        @error('motivo')<p class="ds-form-error">{{ $message }}</p>@enderror
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary">Cancelar</button>
        <button wire:click="confirmar" wire:loading.attr="disabled" class="ds-btn ds-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="confirmar">Confirmar Reprogramación</span>
            <span wire:loading wire:target="confirmar">Procesando...</span>
        </button>
    </div>
</div>
@endif

</div>
