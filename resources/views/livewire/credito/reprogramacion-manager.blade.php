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

    <div class="ds-table-card" style="margin-bottom:14px;">
        <div style="padding:11px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:8px;">
            <span style="font-size:13px;font-weight:700;color:#4A4A4A;">Plan Activo</span>
            @if($plan)<span class="ds-badge ds-badge-active">v{{ $plan->version }}</span>@endif
            <span style="font-size:11px;color:#CBCBCB;margin-left:auto;">{{ $plan?->matriz_nombre }}</span>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead><tr>
                <th style="width:44px;text-align:center;">#</th>
                <th style="text-align:right;">Monto</th>
                <th style="text-align:center;">Vencimiento</th>
                <th style="text-align:center;">Estado</th>
            </tr></thead>
            <tbody>
                @forelse($cuotas->where('numero','>',0)->sortBy('numero') as $c)
                @php
                    $badgeCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                    $lb       = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pendiente' };
                @endphp
                <tr style="{{ $c->estado==='pagado' ? 'opacity:0.55;' : '' }}">
                    <td style="text-align:center;font-weight:700;">{{ $c->numero }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($c->monto,2) }}</td>
                    <td style="text-align:center;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td style="text-align:center;"><span class="ds-badge {{ $badgeCls }}">{{ $lb }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="ds-empty"><p>Sin cuotas</p></div></td></tr>
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

    <div class="ds-table-card" style="margin-bottom:14px;">
        <div style="padding:11px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:13px;font-weight:700;color:#4A4A4A;">Cuotas del nuevo plan</span>
            <button wire:click="agregarCuota" class="ds-btn ds-btn-ghost ds-btn-sm">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Agregar cuota
            </button>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead><tr>
                <th style="width:40px;text-align:center;">#</th>
                <th>Monto (Bs.)</th>
                <th>Fecha vencimiento</th>
                <th style="width:36px;"></th>
            </tr></thead>
            <tbody>
                @foreach($nuevasCuotas as $i => $cuota)
                <tr wire:key="nc-{{ $i }}">
                    <td style="text-align:center;font-weight:700;color:#CBCBCB;">{{ $cuota['numero'] }}</td>
                    <td>
                        <input wire:model="nuevasCuotas.{{ $i }}.monto" type="number" step="0.01" min="0.01" style="width:100%;font-family:monospace;"/>
                        @error("nuevasCuotas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td>
                        <input wire:model="nuevasCuotas.{{ $i }}.fecha" type="date" style="width:100%;"/>
                        @error("nuevasCuotas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                    </td>
                    <td style="text-align:center;">
                        @if(count($nuevasCuotas) > 1)
                        <button wire:click="quitarCuota({{ $i }})" class="ds-btn ds-btn-danger ds-btn-sm" style="padding:3px 6px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#FFFFE3;border-top:2px solid #CBCBCB;">
                    <td colspan="2" style="padding:10px 12px;font-size:12px;font-weight:700;color:#4A4A4A;">
                        Total nuevo plan:
                        <span style="font-size:14px;color:#6D8196;font-family:monospace;margin-left:6px;">Bs. {{ number_format($totalNuevo, 2) }}</span>
                    </td>
                    <td colspan="2" style="padding:10px 12px;text-align:right;">
                        @if(abs($diff) < 0.01)
                            <span style="font-size:11px;color:#10B981;font-weight:600;">✓ Cuadra exacto</span>
                        @elseif($diff > 0)
                            <span style="font-size:11px;color:#B45309;font-weight:600;">+Bs. {{ number_format($diff,2) }} sobre saldo</span>
                        @else
                            <span style="font-size:11px;color:#DC2626;font-weight:600;">−Bs. {{ number_format(abs($diff),2) }} bajo saldo</span>
                        @endif
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

<div class="ds-section-header">
    <button wire:click="backHome" class="ds-btn ds-btn-secondary ds-btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
        Inicio
    </button>
    <div style="flex:1;">
        <h2>Historial de Reprogramaciones</h2>
        <p>Pedidos con planes reprogramados</p>
    </div>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:300px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.400ms="searchHist" type="text" placeholder="CI, nombre o Nº pedido..." style="padding-left:32px;width:100%;">
        </div>
    </div>

    @if($pedidosHist->isEmpty())
    <div class="ds-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p>Sin reprogramaciones registradas</p>
    </div>
    @else
    <div style="padding:12px 16px;display:flex;flex-direction:column;gap:8px;">
        @foreach($pedidosHist as $p)
        @php $totalPlanes = $p->planes->count(); @endphp
        <div wire:click="verHistorialPedido({{ $p->id }})"
             style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 16px;cursor:pointer;display:flex;align-items:center;justify-content:space-between;gap:12px;"
             onmouseover="this.style.borderColor='#6D8196'"
             onmouseout="this.style.borderColor='#CBCBCB'">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                    <span style="font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;">{{ $p->numero }}</span>
                    <span class="ds-badge ds-badge-pending">{{ $totalPlanes - 1 }} reprog.</span>
                </div>
                <p style="font-size:14px;font-weight:700;color:#4A4A4A;margin:0 0 2px;">{{ $p->cliente->nombre_completo }}</p>
                <p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci ?? '—' }}</p>
            </div>
            <svg width="16" height="16" fill="none" stroke="#CBCBCB" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
        @endforeach
    </div>
    @if($pedidosHist->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $pedidosHist->links() }}</div>
    @endif
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
        <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;opacity:0.85;">
            <div style="padding:10px 14px;background:#F4F4F4;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:6px;">
                <span style="font-size:12px;font-weight:700;color:#4A4A4A;">Plan anterior</span>
                <span class="ds-badge ds-badge-inactive">v{{ $rp->version_anterior }}</span>
            </div>
            <div style="padding:4px 0;">
            @if($planViejo)
                @php $cuotasViejas = $planViejo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                @foreach($cuotasViejas as $c)
                @php
                    $bCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                    $bLb  = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pend.' };
                @endphp
                <div style="padding:6px 14px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F4F4F4;{{ $c->estado==='pagado' ? 'opacity:0.5;' : '' }}">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-size:11px;font-weight:700;color:#6D8196;width:16px;">{{ $c->numero }}</span>
                        <span class="ds-badge {{ $bCls }}" style="font-size:9px;">{{ $bLb }}</span>
                    </div>
                    <span style="font-size:11px;font-family:monospace;font-weight:700;color:#4A4A4A;">Bs. {{ number_format($c->monto,2) }}</span>
                </div>
                @endforeach
                <div style="padding:8px 14px;border-top:1px solid #CBCBCB;">
                    <span style="font-size:11px;color:#CBCBCB;">{{ $cuotasViejas->where('estado','pagado')->count() }} pag. · {{ $cuotasViejas->where('estado','!=','pagado')->count() }} pend.</span>
                </div>
            @else
                <p style="padding:20px;text-align:center;font-size:12px;color:#CBCBCB;">Sin datos</p>
            @endif
            </div>
        </div>

        {{-- Plan nuevo --}}
        <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;">
            <div style="padding:10px 14px;background:#FFFFE3;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:6px;">
                <span style="font-size:12px;font-weight:700;color:#4A4A4A;">Plan nuevo</span>
                <span class="ds-badge ds-badge-active">v{{ $rp->version_nueva }}</span>
                @php $esActivo = $planNuevo?->estado === 'activo'; @endphp
                <span class="ds-badge {{ $esActivo ? 'ds-badge-aprobado' : 'ds-badge-inactive' }}" style="margin-left:auto;">{{ $esActivo ? 'Activo' : 'Inactivo' }}</span>
            </div>
            <div style="padding:4px 0;">
            @if($planNuevo)
                @php $cuotasNuevas = $planNuevo->cuotas->where('numero','>',0)->sortBy('numero'); @endphp
                @foreach($cuotasNuevas as $c)
                @php
                    $bCls = match($c->estado) { 'pagado' => 'ds-badge-aprobado', 'vencido' => 'ds-badge-danger', default => 'ds-badge-pending' };
                    $bLb  = match($c->estado) { 'pagado' => 'Pagado', 'vencido' => 'Vencido', default => 'Pend.' };
                @endphp
                <div style="padding:6px 14px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #F4F4F4;{{ $c->estado==='pagado' ? 'opacity:0.6;' : '' }}">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-size:11px;font-weight:700;color:#6D8196;width:16px;">{{ $c->numero }}</span>
                        <span class="ds-badge {{ $bCls }}" style="font-size:9px;">{{ $bLb }}</span>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:11px;font-family:monospace;font-weight:700;color:#4A4A4A;">Bs. {{ number_format($c->monto,2) }}</span>
                        @if($c->fecha_vencimiento)
                        <p style="font-size:9px;color:#CBCBCB;margin:0;">{{ \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
                <div style="padding:8px 14px;border-top:1px solid #CBCBCB;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:11px;color:#CBCBCB;">{{ $cuotasNuevas->where('estado','pagado')->count() }} pag. · {{ $cuotasNuevas->where('estado','!=','pagado')->count() }} pend.</span>
                    <span style="font-size:11px;font-weight:700;color:#4A4A4A;font-family:monospace;">Bs. {{ number_format($planNuevo->total_pagar,2) }}</span>
                </div>
            @else
                <p style="padding:20px;text-align:center;font-size:12px;color:#CBCBCB;">Sin datos</p>
            @endif
            </div>
        </div>

    </div>
</div>

@endif
</div>
