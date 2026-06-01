<div>

{{-- ══ HOME ══ --}}
@if($mode === 'home')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Reprogramación de Planes</h2>
        <p>Seleccioná una acción para comenzar</p>
    </div>
</div>

<div style="max-width:520px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    <button wire:click="irNueva"
            style="background:#fff;border:1.5px solid #CBCBCB;border-radius:8px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s,box-shadow .15s;"
            onmouseover="this.style.borderColor='#6D8196';this.style.boxShadow='0 4px 16px rgba(109,129,150,0.12)'"
            onmouseout="this.style.borderColor='#CBCBCB';this.style.boxShadow=''">
        <div style="width:48px;height:48px;border-radius:8px;background:#E8F0F7;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <svg width="24" height="24" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <p style="font-size:14px;font-weight:700;color:#4A4A4A;margin:0 0 4px;">Nueva Reprogramación</p>
        <p style="font-size:11px;color:#CBCBCB;margin:0;line-height:1.4;">Reprogramá el saldo pendiente de un plan activo</p>
    </button>

    <button wire:click="irHistorial"
            style="background:#fff;border:1.5px solid #CBCBCB;border-radius:8px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .15s,box-shadow .15s;"
            onmouseover="this.style.borderColor='#6D8196';this.style.boxShadow='0 4px 16px rgba(109,129,150,0.12)'"
            onmouseout="this.style.borderColor='#CBCBCB';this.style.boxShadow=''">
        <div style="width:48px;height:48px;border-radius:8px;background:#FFFFE3;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <svg width="24" height="24" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p style="font-size:14px;font-weight:700;color:#4A4A4A;margin:0 0 4px;">Historial</p>
        <p style="font-size:11px;color:#CBCBCB;margin:0;line-height:1.4;">Consultá el historial de planes reprogramados</p>
    </button>

</div>

{{-- ══ NUEVA: BUSCAR ══ --}}
@elseif($mode === 'nueva_buscar')

<div class="ds-section-header">
    <button wire:click="backHome" class="ds-btn ds-btn-secondary ds-btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
        Inicio
    </button>
    <div style="flex:1;">
        <h2>Nueva Reprogramación</h2>
        <p>Buscá el pedido a reprogramar</p>
    </div>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:340px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="CI, nombre o Nº pedido..." style="padding-left:32px;width:100%;">
        </div>
    </div>

    @if(strlen(trim($search)) >= 2)
        @if($resultados->isEmpty())
        <div class="ds-empty">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>Sin resultados para esa búsqueda</p>
        </div>
        @else
        <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;">
            @foreach($resultados as $p)
            @php
                $plan      = $p->planPago;
                $pagadas   = $plan?->cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $pendiente = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
                $nPend     = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->count() ?? 0;
            @endphp
            <div wire:click="seleccionarPedido({{ $p->id }})"
                 style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 16px;cursor:pointer;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;"
                 onmouseover="this.style.borderColor='#6D8196'"
                 onmouseout="this.style.borderColor='#CBCBCB'">
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                        <span style="font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;">{{ $p->numero }}</span>
                        @if($plan)<span class="ds-badge ds-badge-active">v{{ $plan->version }}</span>@endif
                    </div>
                    <p style="font-size:14px;font-weight:700;color:#4A4A4A;margin:0 0 2px;">{{ $p->cliente->nombre_completo }}</p>
                    <p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <p style="font-size:10px;color:#CBCBCB;margin:0 0 2px;">Saldo pendiente</p>
                    <p style="font-size:16px;font-weight:800;color:#DC2626;margin:0;font-family:monospace;">Bs. {{ number_format($pendiente, 2) }}</p>
                    <p style="font-size:10px;color:#CBCBCB;margin:2px 0 0;">{{ $nPend }} cuota{{ $nPend !== 1 ? 's' : '' }} pend.</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    @elseif(strlen(trim($search)) > 0)
        <p style="font-size:11px;color:#CBCBCB;margin:8px 16px;">Ingresá al menos 2 caracteres.</p>
    @else
    <div class="ds-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/></svg>
        <p>Escribí CI, nombre o número de pedido para buscar</p>
    </div>
    @endif
</div>

{{-- ══ NUEVA: PREVIEW ══ --}}
@elseif($mode === 'nueva_preview' && $pedidoDetalle)
@php
    $p        = $pedidoDetalle;
    $plan     = $p->planPago;
    $cuotas   = $plan?->cuotas ?? collect();
    $pagadas  = $cuotas->where('estado','pagado')->where('numero','>',0)->sum('monto');
    $pendiente= $cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto');
    $nPend    = $cuotas->where('estado','!=','pagado')->where('numero','>',0)->count();
@endphp

<div style="max-width:680px;margin:0 auto;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="irNueva" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Búsqueda
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }}</p>
        </div>
        @if($plan)<span class="ds-badge ds-badge-active">Plan v{{ $plan->version }}</span>@endif
    </div>

    <div class="ds-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:14px;">
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;">Bs. {{ number_format($plan?->total_pagar ?? 0, 2) }}</div>
            <div class="ds-stat-label">Total plan</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#10B981;">Bs. {{ number_format($pagadas, 2) }}</div>
            <div class="ds-stat-label">Pagado</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</div>
            <div class="ds-stat-label">Pendiente</div>
        </div>
    </div>

    <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:10px 14px;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;justify-content:space-between;background:#F8F7FF;">
            <span style="font-size:12px;font-weight:700;color:#534AB7;">Plan Activo @if($plan) · v{{ $plan->version }} @endif</span>
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
                    $badgeCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                    $lb       = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pendiente' };
                @endphp
                <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.55;' : '' }}">
                    <td style="padding:8px 12px;font-size:13px;text-align:center;">{{ $c->numero }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;font-weight:600;">Cuota {{ $c->numero }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;font-family:monospace;font-weight:700;color:#7c3aed;">Bs. {{ number_format($c->monto,2) }}</td>
                    <td style="padding:8px 12px;font-size:13px;text-align:center;color:#6b7280;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td style="padding:8px 12px;text-align:center;"><span class="ds-badge {{ $badgeCls }}">{{ $lb }}</span></td>
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
    <div style="text-align:center;padding:16px;background:#FFFFE3;border:1px solid #CBCBCB;border-radius:8px;color:#6D8196;font-size:13px;font-weight:600;">
        Todas las cuotas están pagadas — no hay saldo a reprogramar.
    </div>
    @endif
</div>

{{-- ══ NUEVA: FORM ══ --}}
@elseif($mode === 'nueva_form' && $pedidoDetalle)
@php
    $p          = $pedidoDetalle;
    $plan       = $p->planPago;
    $pendiente  = $plan?->cuotas->where('estado','!=','pagado')->where('numero','>',0)->sum('monto') ?? 0;
    $totalNuevo = collect($nuevasCuotas)->sum(fn($c) => (float)$c['monto']);
    $diff       = round($totalNuevo - (float)$pendiente, 2);
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:40px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="$set('mode','nueva_preview')" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Ver plan
        </button>
        <div style="flex:1;">
            <p style="font-size:15px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;">{{ $p->numero }} · Saldo: Bs. {{ number_format($pendiente, 2) }} · Plan v{{ $plan?->version ?? 1 }} → v{{ ($plan?->version ?? 1) + 1 }}</p>
        </div>
    </div>

    <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:10px 14px;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;justify-content:space-between;background:#F8F7FF;">
            <span style="font-size:12px;font-weight:700;color:#534AB7;">Cuotas del nuevo plan</span>
            <button wire:click="agregarCuota" style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#EDE9FE;color:#534AB7;font-size:12px;font-weight:700;border:1px solid #C4B5FD;border-radius:8px;cursor:pointer;-webkit-appearance:none;appearance:none;">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
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
                        <input wire:model="nuevasCuotas.{{ $i }}.monto" type="number" step="0.01" min="0.01" style="width:90%;padding:4px 8px;border:1px solid #C4B5FD;border-radius:6px;font-size:12px;text-align:center;outline:none;background:#fff;"/>
                        @error("nuevasCuotas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:8px 12px;text-align:center;">
                        <input wire:model="nuevasCuotas.{{ $i }}.fecha" type="date" style="width:90%;padding:4px 8px;border:1px solid #C4B5FD;border-radius:6px;font-size:12px;outline:none;background:#fff;"/>
                        @error("nuevasCuotas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td style="padding:8px 12px;text-align:center;">
                        @if(count($nuevasCuotas) > 1)
                        <button wire:click="quitarCuota({{ $i }})" style="width:28px;height:28px;border-radius:7px;border:1px solid #FECACA;background:#FEF2F2;color:#DC2626;cursor:pointer;display:flex;align-items:center;justify-content:center;margin:0 auto;-webkit-appearance:none;appearance:none;">
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
                            <span style="font-size:12px;font-weight:700;color:#534AB7;">Total nuevo plan:
                                <span style="font-size:14px;font-family:monospace;color:#111827;margin-left:6px;">Bs. {{ number_format($totalNuevo, 2) }}</span>
                            </span>
                            <span>
                                @if(abs($diff) < 0.01)
                                    <span style="font-size:11px;color:#10B981;font-weight:600;">✓ Cuadra exacto</span>
                                @elseif($diff > 0)
                                    <span style="font-size:11px;color:#B45309;font-weight:600;">+Bs. {{ number_format($diff,2) }} sobre saldo</span>
                                @else
                                    <span style="font-size:11px;color:#DC2626;font-weight:600;">−Bs. {{ number_format(abs($diff),2) }} bajo saldo</span>
                                @endif
                            </span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px;margin-bottom:14px;">
        <label class="ds-form-label">Motivo de la reprogramación <span style="color:#DC2626;">*</span></label>
        <textarea wire:model="motivo" rows="3" placeholder="Ej: Cliente solicitó extensión de plazo por dificultades económicas..." style="width:100%;display:block;resize:vertical;"></textarea>
        @error('motivo')<p class="ds-form-error">{{ $message }}</p>@enderror
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <button wire:click="$set('mode','nueva_preview')" class="ds-btn ds-btn-secondary">Cancelar</button>
        <button wire:click="confirmar" wire:loading.attr="disabled" class="ds-btn ds-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span wire:loading.remove wire:target="confirmar">Confirmar Reprogramación</span>
            <span wire:loading wire:target="confirmar">Procesando...</span>
        </button>
    </div>
</div>

{{-- ══ HIST: LIST ══ --}}
@elseif($mode === 'hist_list')

<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Header --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-shrink:0;">
        <button wire:click="backHome"
                style="display:flex; align-items:center; gap:5px; padding:5px 10px; background:#F4F4F4; color:#6D8196; font-size:12px; font-weight:600; border:1px solid #CBCBCB; border-radius:7px; cursor:pointer; -webkit-appearance:none; appearance:none;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Inicio
        </button>
        <span style="font-size:13px; font-weight:700; color:#111827;">Historial de Reprogramaciones</span>
        <span style="background:#EDE9FE; color:#7B6FE8; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $pedidosHist->total() }}</span>
        <div style="margin-left:auto; position:relative;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:#9CA3AF;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.400ms="searchHist" type="text" placeholder="CI, nombre o Nº pedido..."
                   style="padding-left:32px; height:36px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; width:260px; background:#fff;">
        </div>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:600px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap;">Código</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">CI</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cliente</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Reprog.</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidosHist as $p)
            @php $totalPlanes = $p->planes->count(); @endphp
            <tr wire:key="hist-{{ $p->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td style="padding:10px 8px; text-align:center; font-size:11px; white-space:nowrap;">{{ $pedidosHist->firstItem() + $loop->index }}</td>
                <td style="padding:10px 14px; font-size:12px; font-family:monospace; font-weight:700; color:#111827; white-space:nowrap;">{{ $p->numero }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $p->cliente->ci ?: '—' }}</td>
                <td style="padding:10px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($p->cliente->nombre_completo)) }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:2px 8px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">{{ $totalPlanes - 1 }}</span>
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="verHistorialPedido({{ $p->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Ver historial">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:64px 24px; text-align:center;">
                    <svg style="width:48px; height:48px; color:#E5E7EB; margin:0 auto 12px; display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-weight:600; color:#6B7280; font-size:13px;">Sin reprogramaciones registradas</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($pedidosHist->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $pedidosHist->links() }}</div>
    @endif
</div>

{{-- ══ HIST: PEDIDO ══ --}}
@elseif($mode === 'hist_pedido' && $pedidoDetalle)
@php $p = $pedidoDetalle; @endphp

<div style="max-width:680px;margin:0 auto;padding-bottom:40px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="irHistorial" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Historial
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }}</p>
        </div>
    </div>

    @php $planActivo = $p->planPago; @endphp
    @if($planActivo)
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 16px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:12px;font-weight:600;color:#4A4A4A;">Plan activo:</span>
            <span class="ds-badge ds-badge-active">v{{ $planActivo->version }}</span>
            <span style="font-size:11px;color:#CBCBCB;">{{ $planActivo->matriz_nombre }}</span>
        </div>
        <span style="font-size:13px;font-weight:700;color:#4A4A4A;font-family:monospace;">Bs. {{ number_format($planActivo->total_pagar, 2) }}</span>
    </div>
    @endif

    @if($reprogramaciones->isEmpty())
    <div class="ds-empty" style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;">
        <p>No hay reprogramaciones registradas para este pedido.</p>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:8px;">
        @foreach($reprogramaciones as $rp)
        <div wire:click="verDetalle({{ $rp->id }})"
             style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 16px;cursor:pointer;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;"
             onmouseover="this.style.borderColor='#6D8196'"
             onmouseout="this.style.borderColor='#CBCBCB'">
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;">
                    <span class="ds-badge ds-badge-inactive">v{{ $rp->version_anterior }}</span>
                    <svg width="14" height="14" fill="none" stroke="#CBCBCB" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <span class="ds-badge ds-badge-active">v{{ $rp->version_nueva }}</span>
                    @if($rp->version_nueva === ($planActivo?->version ?? 0))
                    <span class="ds-badge ds-badge-aprobado">Activo</span>
                    @endif
                </div>
                <p style="font-size:12px;color:#4A4A4A;margin:0 0 3px;"><span style="color:#CBCBCB;">Motivo:</span> {{ Str::limit($rp->motivo, 60) }}</p>
                <p style="font-size:11px;color:#CBCBCB;margin:0;">{{ $rp->created_at->format('d/m/Y H:i') }} · {{ $rp->creadoPor->name ?? '—' }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <p style="font-size:10px;color:#CBCBCB;margin:0 0 2px;">Saldo reprog.</p>
                <p style="font-size:14px;font-weight:800;color:#DC2626;margin:0;font-family:monospace;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══ HIST: DETALLE ══ --}}
@elseif($mode === 'hist_detalle' && $reprogramacionDetalle)
@php
    $rp        = $reprogramacionDetalle;
    $p         = $rp->pedido;
    $planViejo = $rp->planViejo;
    $planNuevo = $rp->planNuevo;
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:40px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="verHistorialPedido({{ $rp->pedido_id }})" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Reprogramaciones
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $p->numero }}</p>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:16px;margin-bottom:14px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
                <span class="ds-form-label">Versión</span>
                <div style="display:flex;align-items:center;gap:6px;margin-top:3px;">
                    <span class="ds-badge ds-badge-inactive">v{{ $rp->version_anterior }}</span>
                    <svg width="14" height="14" fill="none" stroke="#CBCBCB" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <span class="ds-badge ds-badge-active">v{{ $rp->version_nueva }}</span>
                </div>
            </div>
            <div>
                <span class="ds-form-label">Saldo reprogramado</span>
                <span style="font-size:14px;font-weight:700;color:#DC2626;font-family:monospace;display:block;margin-top:3px;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</span>
            </div>
            <div>
                <span class="ds-form-label">Fecha</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $rp->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="ds-form-label">Realizado por</span>
                <span style="font-weight:600;color:#4A4A4A;">{{ $rp->creadoPor->name ?? '—' }}</span>
            </div>
            <div style="grid-column:span 2;">
                <span class="ds-form-label">Motivo</span>
                <span style="color:#4A4A4A;">{{ $rp->motivo }}</span>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

        {{-- Plan anterior --}}
        <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;opacity:0.85;">
            <div style="padding:10px 14px;background:#F8F7FF;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;gap:6px;">
                <span style="font-size:12px;font-weight:700;color:#534AB7;">Plan anterior</span>
                <span class="ds-badge ds-badge-inactive">v{{ $rp->version_anterior }}</span>
            </div>
            @if($planViejo)
                @php $cuotasViejas = $planViejo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#F8F7FF;">
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">#</th>
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Monto</th>
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotasViejas as $c)
                        @php
                            $bCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                            $bLb  = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pend.' };
                        @endphp
                        <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                            <td style="padding:8px 12px;font-size:13px;text-align:center;font-weight:600;color:#6b7280;">{{ $c->numero }}</td>
                            <td style="padding:8px 12px;font-size:13px;text-align:center;font-family:monospace;font-weight:700;color:#7c3aed;">Bs. {{ number_format($c->monto,2) }}</td>
                            <td style="padding:8px 12px;text-align:center;"><span class="ds-badge {{ $bCls }}" style="font-size:9px;">{{ $bLb }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#F5F3FF;border-top:1.5px solid #EDE9FE;">
                            <td colspan="3" style="padding:8px 12px;font-size:11px;color:#6b7280;text-align:center;">
                                {{ $cuotasViejas->where('estado','pagado')->count() }} pag. · {{ $cuotasViejas->where('estado','!=','pagado')->count() }} pend.
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <p style="padding:20px;text-align:center;font-size:12px;color:#CBCBCB;">Sin datos</p>
            @endif
        </div>

        {{-- Plan nuevo --}}
        @php $esActivo = $planNuevo?->estado === 'activo'; @endphp
        <div style="background:#fff;border:0.5px solid #CECBF6;border-radius:10px;overflow:hidden;">
            <div style="padding:10px 14px;background:#F8F7FF;border-bottom:1px solid #EDE9FE;display:flex;align-items:center;gap:6px;">
                <span style="font-size:12px;font-weight:700;color:#534AB7;">Plan nuevo</span>
                <span class="ds-badge ds-badge-active">v{{ $rp->version_nueva }}</span>
                <span class="ds-badge {{ $esActivo ? 'ds-badge-aprobado' : 'ds-badge-inactive' }}" style="margin-left:auto;">{{ $esActivo ? 'Activo' : 'Inactivo' }}</span>
            </div>
            @if($planNuevo)
                @php $cuotasNuevas = $planNuevo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#F8F7FF;">
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">#</th>
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Monto</th>
                            <th style="padding:8px 12px;font-size:10px;font-weight:600;color:#6b7280;text-align:center;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotasNuevas as $c)
                        @php
                            $bCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                            $bLb  = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pend.' };
                        @endphp
                        <tr style="{{ !$loop->last ? 'border-bottom:0.5px solid #e5e7eb;' : '' }}{{ $c->estado==='pagado' ? 'opacity:0.6;' : '' }}">
                            <td style="padding:8px 12px;font-size:13px;text-align:center;font-weight:600;color:#6b7280;">{{ $c->numero }}</td>
                            <td style="padding:8px 12px;text-align:center;">
                                <span style="font-size:13px;font-family:monospace;font-weight:700;color:#7c3aed;">Bs. {{ number_format($c->monto,2) }}</span>
                                @if($c->fecha_vencimiento)
                                <p style="font-size:9px;color:#CBCBCB;margin:2px 0 0;">{{ \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') }}</p>
                                @endif
                            </td>
                            <td style="padding:8px 12px;text-align:center;"><span class="ds-badge {{ $bCls }}" style="font-size:9px;">{{ $bLb }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#F5F3FF;border-top:1.5px solid #EDE9FE;">
                            <td colspan="3" style="padding:8px 12px;text-align:center;">
                                <span style="font-size:11px;color:#6b7280;">{{ $cuotasNuevas->where('estado','pagado')->count() }} pag. · {{ $cuotasNuevas->where('estado','!=','pagado')->count() }} pend.</span>
                                <span style="font-size:11px;font-weight:700;color:#534AB7;font-family:monospace;margin-left:8px;">Bs. {{ number_format($planNuevo->total_pagar,2) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <p style="padding:20px;text-align:center;font-size:12px;color:#CBCBCB;">Sin datos</p>
            @endif
        </div>

    </div>
</div>

@endif
</div>
