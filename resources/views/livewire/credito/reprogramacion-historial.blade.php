<div>

{{-- ══ LIST ══ --}}
@if($mode === 'list')

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Historial de Reprogramaciones</h2>
        <p>Planes de pago reprogramados</p>
    </div>
</div>

<div class="ds-table-card">
    <div class="ds-table-toolbar">
        <div style="position:relative;flex:1;max-width:280px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Código, CI, cliente o pedido..."
                   style="padding-left:32px;width:100%;">
        </div>

        <div style="display:flex;gap:6px;">
            <button wire:click="$set('filtro','todos')" class="ds-btn ds-btn-sm" style="{{ $filtro==='todos' ? 'background:#6D8196;color:#fff;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Todos</button>
            <button wire:click="$set('filtro','activo')" class="ds-btn ds-btn-sm" style="{{ $filtro==='activo' ? 'background:#6D8196;color:#fff;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Plan activo</button>
            <button wire:click="$set('filtro','inactivo')" class="ds-btn ds-btn-sm" style="{{ $filtro==='inactivo' ? 'background:#FCA5A5;color:#991B1B;' : 'background:#FFFFE3;color:#6D8196;border:1.5px solid #CBCBCB;' }}">Plan inactivo</button>
        </div>

        <div style="margin-left:auto;">
            <a href="{{ route('credito.reprogramacion.nueva') }}" class="ds-btn ds-btn-primary ds-btn-sm">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Reprogramación
            </a>
        </div>
    </div>

    <div style="overflow-x:auto;">
    <table style="min-width:700px;">
        <thead>
            <tr>
                <th class="ds-sticky-col" style="padding:0;height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:130px;padding:10px 12px;border-right:1px solid #CBCBCB;display:flex;align-items:center;justify-content:center;">Código</div>
                        <div style="flex:1;padding:10px 12px;display:flex;align-items:center;justify-content:center;">Cliente</div>
                    </div>
                </th>
                <th>Pedido</th>
                <th style="text-align:center;">Versión</th>
                <th style="text-align:center;">Fecha</th>
                <th style="text-align:center;">Saldo reprog.</th>
                <th style="text-align:center;">Plan</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reprogramaciones as $rp)
            @php $esActivo = $rp->planNuevo?->estado === 'activo'; @endphp
            <tr wire:key="rp-{{ $rp->id }}">
                <td class="ds-sticky-col" style="height:1px;">
                    <div style="display:flex;align-items:stretch;height:100%;">
                        <div style="width:130px;padding:10px 12px;border-right:1px solid #e5e7eb;font-family:monospace;font-size:11px;color:#6D8196;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $rp->numero }}</div>
                        <div style="flex:1;padding:10px 12px;">
                            <p style="font-weight:600;font-size:13px;color:#4A4A4A;margin:0;">{{ $rp->pedido->cliente->nombre_completo }}</p>
                            @if($rp->pedido->cliente->ci)<p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $rp->pedido->cliente->ci }}</p>@endif
                        </div>
                    </div>
                </td>
                <td style="text-align:center;font-family:monospace;font-size:11px;font-weight:600;">{{ $rp->pedido->numero }}</td>
                <td style="text-align:center;">
                    <div style="display:flex;align-items:center;justify-content:center;gap:4px;">
                        <span class="ds-badge ds-badge-cerrado">v{{ $rp->version_anterior }}</span>
                        <svg width="11" height="11" fill="none" stroke="#CBCBCB" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <span class="ds-badge ds-badge-aprobado">v{{ $rp->version_nueva }}</span>
                    </div>
                </td>
                <td style="text-align:center;color:#CBCBCB;font-size:11px;">{{ $rp->created_at->format('d/m/Y') }}</td>
                <td style="text-align:center;font-family:monospace;font-weight:700;color:#DC2626;font-size:12px;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</td>
                <td style="text-align:center;">
                    <span class="ds-badge {{ $esActivo ? 'ds-badge-aprobado' : 'ds-badge-cerrado' }}">{{ $esActivo ? 'Activo' : 'Inactivo' }}</span>
                </td>
                <td style="text-align:center;">
                    <button wire:click="verDetalle({{ $rp->id }})" class="ds-btn ds-btn-ghost ds-btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="ds-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Sin reprogramaciones registradas</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($reprogramaciones->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">{{ $reprogramaciones->links() }}</div>
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
<div style="max-width:680px;margin:0 auto;padding-bottom:40px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Historial
        </button>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($esActivo)
            <button wire:click="editarPlan" class="ds-btn ds-btn-ghost ds-btn-sm">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar plan
            </button>
            @endif
            <a href="{{ route('credito.reprogramacion.nueva') }}" class="ds-btn ds-btn-primary ds-btn-sm">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Reprogramación
            </a>
        </div>
    </div>

    {{-- Header --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <div style="padding:14px 18px;border-bottom:1px solid #CBCBCB;">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <div>
                    <p style="font-family:monospace;font-size:13px;font-weight:800;color:#6D8196;margin:0;">{{ $rp->numero }}</p>
                    <p style="font-size:15px;font-weight:700;color:#4A4A4A;margin:2px 0 0;">{{ $p->cliente->nombre_completo }}</p>
                    <p style="font-size:11px;color:#CBCBCB;margin:0;">CI: {{ $p->cliente->ci ?? '—' }} · Pedido: <span style="font-family:monospace;font-weight:600;">{{ $p->numero }}</span></p>
                </div>
                <div style="text-align:right;">
                    <div style="display:flex;align-items:center;gap:5px;justify-content:flex-end;margin-bottom:4px;">
                        <span class="ds-badge ds-badge-cerrado">v{{ $rp->version_anterior }}</span>
                        <svg width="11" height="11" fill="none" stroke="#CBCBCB" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        <span class="ds-badge ds-badge-aprobado">v{{ $rp->version_nueva }}</span>
                        <span class="ds-badge {{ $esActivo ? 'ds-badge-aprobado' : 'ds-badge-cerrado' }}">{{ $esActivo ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <p style="font-size:11px;color:#CBCBCB;margin:0;">{{ $rp->created_at->format('d/m/Y H:i') }} · {{ $rp->creadoPor->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div style="padding:12px 18px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
            <div style="flex:1;">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 3px;">Motivo</p>
                <p style="font-size:12px;color:#4A4A4A;margin:0;line-height:1.5;">{{ $rp->motivo }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#CBCBCB;margin:0 0 3px;">Saldo reprog.</p>
                <p style="font-size:14px;font-weight:800;color:#DC2626;font-family:monospace;margin:0;">Bs. {{ number_format($rp->saldo_reprogramado, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="ds-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:14px;">
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;">Bs. {{ number_format($planNuevo?->total_pagar ?? 0, 2) }}</div>
            <div class="ds-stat-label">Total plan</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#059669;">Bs. {{ number_format($pagado, 2) }}</div>
            <div class="ds-stat-label">Pagado</div>
        </div>
        <div class="ds-stat-card" style="flex-direction:column;align-items:center;text-align:center;padding:12px 8px;gap:4px;">
            <div class="ds-stat-value" style="font-size:15px;color:#DC2626;">Bs. {{ number_format($pendiente, 2) }}</div>
            <div class="ds-stat-label">Pendiente</div>
        </div>
    </div>

    {{-- Plan anterior (collapsible) --}}
    @if($planViejo && $cuotasViej->isNotEmpty())
    <div x-data="{ abierto: false }" style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;">
        <button @click="abierto = !abierto"
                style="width:100%;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;background:#F7F7F0;border:none;cursor:pointer;text-align:left;">
            <div style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:13px;font-weight:700;color:#CBCBCB;">Plan anterior · v{{ $rp->version_anterior }}</span>
                <span class="ds-badge ds-badge-cerrado">REEMPLAZADO</span>
            </div>
            <svg :class="abierto ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" style="color:#CBCBCB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="abierto" x-collapse>
            <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th style="text-align:center;width:50px;">#</th>
                        <th style="text-align:right;">Monto</th>
                        <th style="text-align:center;">Vencimiento</th>
                        <th style="text-align:center;width:110px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuotasViej as $c)
                    @php $badge = $c->estadoFinancieroBadge; @endphp
                    <tr style="{{ $c->estado==='pagado' ? 'opacity:0.55;' : '' }}">
                        <td style="text-align:center;font-weight:700;">{{ $c->numero }}</td>
                        <td style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($c->monto, 2) }}</td>
                        <td style="text-align:center;color:#CBCBCB;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                        <td style="text-align:center;"><span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:right;">Total: <span style="font-family:monospace;">Bs. {{ number_format($planViejo->total_pagar, 2) }}</span></td>
                        <td colspan="2" style="text-align:center;">{{ $cuotasViej->where('estado','pagado')->count() }} pagadas · {{ $cuotasViej->where('estado','!=','pagado')->count() }} reemplazadas</td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Plan de pago --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;">
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Plan de pago · v{{ $rp->version_nueva }}</p>
            <span style="font-size:11px;color:#CBCBCB;">{{ $planNuevo?->matriz_nombre }}</span>
            @if($planNuevo)
            @php $efBadge = $planNuevo->estadoFinancieroBadge; @endphp
            <span class="ds-badge" style="background:{{ $efBadge['bg'] }};color:{{ $efBadge['cl'] }};">{{ $efBadge['lb'] }}</span>
            @endif
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;width:50px;">#</th>
                    <th style="text-align:right;">Monto</th>
                    <th style="text-align:center;">Vencimiento</th>
                    <th style="text-align:center;width:110px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cuotas as $c)
                @php $badge = $c->estadoFinancieroBadge; @endphp
                <tr style="{{ $c->estado==='pagado' ? 'opacity:0.55;' : '' }}">
                    <td style="text-align:center;font-weight:700;">{{ $c->numero }}</td>
                    <td style="text-align:right;font-family:monospace;font-weight:700;">Bs. {{ number_format($c->monto, 2) }}</td>
                    <td style="text-align:center;color:#CBCBCB;">{{ $c->fecha_vencimiento ? \Carbon\Carbon::parse($c->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                    <td style="text-align:center;"><span class="ds-badge" style="background:{{ $badge['bg'] }};color:{{ $badge['cl'] }};">{{ $badge['lb'] }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="ds-empty"><p>Sin cuotas</p></div></td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;">Total: <span style="font-family:monospace;color:#6D8196;">Bs. {{ number_format($planNuevo?->total_pagar ?? 0, 2) }}</span></td>
                    <td colspan="2" style="text-align:center;">{{ $cuotas->where('estado','pagado')->count() }} pagadas · {{ $cuotas->where('estado','!=','pagado')->count() }} pendientes</td>
                </tr>
            </tfoot>
        </table>
        </div>
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
@endphp
<div style="max-width:680px;margin:0 auto;padding-bottom:60px;">

    <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary ds-btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
            Ver detalle
        </button>
        <div style="flex:1;">
            <p style="font-size:16px;font-weight:700;color:#4A4A4A;margin:0;">{{ $p->cliente->nombre_completo }}</p>
            <p style="font-size:11px;color:#CBCBCB;margin:0;font-family:monospace;">{{ $rp->numero }} — Editar cuotas del plan</p>
        </div>
    </div>

    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;padding:14px 18px;margin-bottom:14px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <div>
                <span class="ds-form-label">Saldo pendiente actual</span>
                <p style="font-size:18px;font-weight:800;color:#DC2626;margin:0;font-family:monospace;">Bs. {{ number_format($pendActual, 2) }}</p>
            </div>
            <div style="text-align:right;">
                <span class="ds-form-label">Pagado</span>
                <p style="font-size:14px;font-weight:700;color:#059669;margin:0;font-family:monospace;">Bs. {{ number_format($pagado, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Tabla editable con cuadre Alpine --}}
    <div style="background:#fff;border:1px solid #CBCBCB;border-radius:8px;overflow:hidden;margin-bottom:14px;"
         x-data="{
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
        <div style="padding:12px 16px;border-bottom:1px solid #CBCBCB;display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:13px;font-weight:700;color:#4A4A4A;margin:0;">Cuotas · v{{ $rp->version_nueva }}</p>
            <button wire:click="agregarCuotaEdicion" class="ds-btn ds-btn-secondary ds-btn-sm">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar cuota
            </button>
        </div>
        <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="text-align:center;width:40px;">#</th>
                    <th>Monto (Bs.)</th>
                    <th>Fecha vencimiento</th>
                    <th style="text-align:center;width:90px;">Estado</th>
                    <th style="width:36px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuotasEditadas as $i => $ce)
                <tr wire:key="ce-{{ $i }}" style="{{ $ce['pagado'] ? 'opacity:0.5;background:#f9fafb;' : '' }}">
                    <td style="text-align:center;font-weight:700;color:#CBCBCB;">{{ $ce['numero'] }}</td>
                    <td>
                        @if($ce['pagado'])
                        <span style="font-family:monospace;font-weight:700;color:#4A4A4A;">Bs. {{ number_format((float)$ce['monto'], 2) }}</span>
                        @else
                        <input wire:model="cuotasEditadas.{{ $i }}.monto" type="number" step="0.01" min="0.01"
                               class="monto-edit" style="width:100%;">
                        @error("cuotasEditadas.{$i}.monto")<p class="ds-form-error">{{ $message }}</p>@enderror
                        @endif
                    </td>
                    <td>
                        @if($ce['pagado'])
                        <span style="color:#CBCBCB;">{{ $ce['fecha'] ? \Carbon\Carbon::parse($ce['fecha'])->format('d/m/Y') : '—' }}</span>
                        @else
                        <input wire:model="cuotasEditadas.{{ $i }}.fecha" type="date" style="width:100%;">
                        @error("cuotasEditadas.{$i}.fecha")<p class="ds-form-error">{{ $message }}</p>@enderror
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span class="ds-badge {{ $ce['pagado'] ? 'ds-badge-aprobado' : 'ds-badge-pending' }}">{{ $ce['pagado'] ? 'Pagado' : 'Pendiente' }}</span>
                    </td>
                    <td style="text-align:center;">
                        @if(!$ce['pagado'])
                        <button wire:click="quitarCuotaEdicion({{ $i }})" class="ds-btn ds-btn-danger ds-btn-sm" style="padding:4px 6px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- Resumen en tiempo real --}}
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1px;background:#CBCBCB;border-top:2px solid #CBCBCB;">
            <div style="padding:12px 16px;background:#F7F7F0;text-align:center;">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#CBCBCB;margin:0 0 4px;">Saldo a cubrir</p>
                <p style="font-size:16px;font-weight:800;color:#4A4A4A;font-family:monospace;margin:0;">Bs. {{ number_format($pendActual, 2) }}</p>
            </div>
            <div style="padding:12px 16px;background:#F7F7F0;text-align:center;">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#CBCBCB;margin:0 0 4px;">Total cuotas</p>
                <p x-text="'Bs. ' + total.toFixed(2)" style="font-size:16px;font-weight:800;color:#6D8196;font-family:monospace;margin:0;"></p>
            </div>
            <div :style="'padding:12px 16px;text-align:center;border:1.5px solid ' + diffBorder + ';background:' + diffBg + ';'">
                <p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#CBCBCB;margin:0 0 4px;">Diferencia</p>
                <p x-text="diffLabel" :style="'font-size:16px;font-weight:800;font-family:monospace;margin:0;color:' + diffColor"></p>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <button wire:click="volver" class="ds-btn ds-btn-secondary">Cancelar</button>
        <button wire:click="guardarEdicion" wire:loading.attr="disabled" class="ds-btn ds-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span wire:loading.remove wire:target="guardarEdicion">Guardar cambios</span>
            <span wire:loading wire:target="guardarEdicion">Guardando...</span>
        </button>
    </div>

</div>
@endif

</div>
