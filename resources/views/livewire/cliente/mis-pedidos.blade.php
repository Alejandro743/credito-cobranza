<div>

{{-- ══════════════════════ DETAIL ══════════════════════ --}}
@if ($mode === 'detail' && $pedidoDetalle)
@php $p = $pedidoDetalle; $plan = $p->planPago; @endphp

<div class="max-w-3xl mx-auto">
    <button wire:click="backToList"
            class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Mis Pedidos
    </button>

    {{-- Header tarjeta --}}
    <div class="bg-gradient-to-br from-celeste-50 to-white rounded-2xl border border-celeste-100 shadow-sm p-5 mb-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <p class="font-mono text-xs text-celeste-400 mb-1">{{ $p->numero }}</p>
                <p class="text-xs text-gray-400">{{ $p->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold" style="{{ $p->estado_badge['style'] }}">
                {{ $p->estado_badge['label'] }}
            </span>
        </div>
        @if ($p->entrega_direccion || $p->entrega_ciudad)
        <div class="mt-3 flex items-start gap-2">
            <svg class="w-4 h-4 text-celeste-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            @php $partes = array_filter([$p->entrega_ciudad, $p->entrega_provincia, $p->entrega_municipio, $p->entrega_direccion]); @endphp
            <p class="text-sm text-gray-600">{{ implode(', ', $partes) }}{{ $p->entrega_referencia ? ' (Ref: '.$p->entrega_referencia.')' : '' }}</p>
        </div>
        @endif
        @if ($p->notas)
        <div class="mt-3 pt-3 border-t border-celeste-100">
            <p class="text-xs text-gray-400 mb-0.5">Notas del pedido</p>
            <p class="text-sm text-gray-600">{{ $p->notas }}</p>
        </div>
        @endif
    </div>

    {{-- Productos --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-3 border-b border-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Mis productos</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach ($p->items as $item)
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-12 h-12 rounded-xl overflow-hidden bg-lavanda-50 flex-shrink-0">
                    @if ($item->product?->foto_url)
                    <img src="{{ $item->product->foto_url }}" class="w-full h-full object-cover"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    @endif
                    <div class="w-full h-full flex items-center justify-center" style="{{ $item->product?->foto_url ? 'display:none;' : '' }}">
                        <svg class="w-5 h-5 text-lavanda-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $item->product?->name }}</p>
                    @if ($item->puntos > 0)
                    <p class="text-xs text-mint-600">+{{ $item->puntos * $item->cantidad }} puntos</p>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-bold text-celeste-600">Bs {{ number_format($item->subtotal, 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-600">Total productos</span>
            <span class="text-base font-bold text-celeste-700">Bs {{ number_format($p->total, 2) }}</span>
        </div>
    </div>

    {{-- Documentos --}}
    @php
        $docs = [
            'Anverso CI'   => $p->doc_anverso_ci,
            'Reverso CI'   => $p->doc_reverso_ci,
            'Anverso Doc.' => $p->doc_anverso_doc,
            'Reverso Doc.' => $p->doc_reverso_doc,
            'Aviso de Luz' => $p->doc_aviso_luz,
        ];
        $docIconos = [
            'Anverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
            'Reverso CI'   => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
            'Anverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'Reverso Doc.' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'Aviso de Luz' => 'M13 10V3L4 14h7v7l9-11h-7z',
        ];
        $docsExisten = collect($docs)->filter()->isNotEmpty();
    @endphp
    @if ($docsExisten)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-3 border-b border-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Mis Documentos</h3>
        </div>
        <div class="p-4">
            <div class="doc-grid-cliente" style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
            <style>@media(min-width:480px){.doc-grid-cliente{grid-template-columns:repeat(5,1fr)!important;}}</style>
            @foreach ($docs as $label => $path)
            @if ($path)
            @php $url = \Illuminate\Support\Facades\Storage::url($path); @endphp
            <a href="{{ $url }}" target="_blank" style="text-decoration:none;">
                <div style="border:1.5px solid #0F6E56; background:#F0FDF4; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                    <div style="width:28px; height:28px; border-radius:6px; background:#DCFCE7; display:flex; align-items:center; justify-content:center;">
                        <svg style="width:16px;height:16px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $docIconos[$label] ?? 'M9 12h6m-6 4h6' }}"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:#0F6E56;">{{ $label }}</span>
                    <span style="display:inline-flex; align-items:center; gap:2px; font-size:8px; color:#0F6E56;">
                        <svg style="width:9px;height:9px;" fill="none" stroke="#0F6E56" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Ver
                    </span>
                </div>
            </a>
            @else
            <div style="border:1.5px dashed #e5e7eb; background:#f9fafb; border-radius:8px; padding:6px 4px; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; width:100%; height:80px; box-sizing:border-box;">
                <div style="width:28px; height:28px; border-radius:6px; background:#f3f4f6; display:flex; align-items:center; justify-content:center;">
                    <svg style="width:16px;height:16px;" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $docIconos[$label] ?? 'M9 12h6m-6 4h6' }}"/>
                    </svg>
                </div>
                <span style="font-size:9px; font-weight:500; display:block; line-height:1.2; color:#9ca3af;">{{ $label }}</span>
                <span style="font-size:8px; color:#d1d5db;">Sin archivo</span>
            </div>
            @endif
            @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Plan de Pago --}}
    @if ($plan)
    <div class="bg-white rounded-2xl border border-lavanda-100 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-3 bg-lavanda-50 border-b border-lavanda-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lavanda-700 text-sm">Mi Plan de Pago</h3>
                <p class="text-xs text-lavanda-500">{{ $plan->matriz_nombre }}</p>
            </div>
            <span class="text-xs font-bold text-lavanda-700 bg-white px-2.5 py-1 rounded-full border border-lavanda-200">
                {{ $plan->cantidad_cuotas }} {{ $plan->cantidad_cuotas === 1 ? 'cuota' : 'cuotas' }}
            </span>
        </div>

        {{-- Resumen financiero --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 divide-x divide-y divide-gray-100 border-b border-gray-100">
            @if ($plan->cuota_inicial > 0)
            <div class="px-4 py-3 text-center">
                <p class="text-xs text-gray-400 mb-1">Cuota inicial</p>
                <p class="font-bold text-melocoton-600 text-lg">Bs {{ number_format($plan->cuota_inicial, 2) }}</p>
            </div>
            @endif
            <div class="px-4 py-3 text-center">
                <p class="text-xs text-gray-400 mb-1">Cuota mensual</p>
                <p class="font-bold text-lavanda-700 text-lg">Bs {{ number_format($plan->monto_cuota, 2) }}</p>
            </div>
            <div class="px-4 py-3 text-center {{ $plan->cuota_inicial > 0 ? 'col-span-2 sm:col-span-1' : '' }}">
                <p class="text-xs text-gray-400 mb-1">Total a pagar</p>
                <p class="font-bold text-gray-800 text-lg">Bs {{ number_format($plan->total_pagar, 2) }}</p>
            </div>
        </div>

        {{-- Lista de cuotas --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-lavanda-50 text-lavanda-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-semibold">Cuota</th>
                        <th class="px-4 py-2.5 text-right font-semibold">Monto</th>
                        <th class="px-4 py-2.5 text-center font-semibold">Estado</th>
                        <th class="px-4 py-2.5 text-center font-semibold hidden sm:table-cell">Vencimiento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($plan->cuotas as $cuota)
                    <tr class="{{ $cuota->estado === 'vencido' ? 'bg-red-50/50' : '' }}">
                        <td class="px-4 py-2.5 font-medium text-gray-700">
                            @if ($cuota->numero === 0)
                                <span class="text-melocoton-600 font-semibold">Cuota Inicial</span>
                            @else
                                <span class="text-gray-800">Cuota {{ $cuota->numero }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-right font-bold text-gray-800">
                            Bs {{ number_format($cuota->monto, 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                  style="background:{{ $cuota->estadoFinancieroBadge['bg'] }}; color:{{ $cuota->estadoFinancieroBadge['cl'] }};">
                                @if ($cuota->estadoFinanciero === 'pagado')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                @elseif ($cuota->estadoFinanciero === 'en_mora')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                                {{ $cuota->estadoFinancieroBadge['lb'] }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-center text-xs text-gray-400 hidden sm:table-cell">
                            {{ $cuota->fecha_vencimiento ? $cuota->fecha_vencimiento->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- ══════════════════════ LIST ══════════════════════ --}}
@else

@if ($sinCliente)
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
    <p class="font-semibold text-yellow-700">No tenés un perfil de cliente vinculado.</p>
    <p class="text-sm text-yellow-600 mt-1">Contactá al administrador para vincular tu cuenta.</p>
</div>

@elseif ($pedidos->isEmpty())
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 flex flex-col items-center gap-4 text-center">
    <div class="w-16 h-16 bg-celeste-50 rounded-2xl flex items-center justify-center">
        <svg class="w-8 h-8 text-celeste-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <div>
        <p class="font-bold text-gray-700 text-lg">Todavía no tenés pedidos</p>
        <p class="text-sm text-gray-400 mt-1">Cuando tu vendedor genere un pedido para vos, aparecerá aquí.</p>
    </div>
</div>

@else
<div class="space-y-3">
    @foreach ($pedidos as $p)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
         wire:click="ver({{ $p->id }})" wire:key="ped-{{ $p->id }}">
        <div class="p-4 flex items-center gap-4">
            {{-- Icono estado --}}
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0
                {{ match($p->estado) {
                    'aprobado'  => 'bg-mint-50',
                    'revision'   => 'bg-celeste-50',
                    'rechazado' => 'bg-red-50',
                    default     => 'bg-gray-50',
                } }}">
                @if ($p->estado === 'aprobado')
                <svg class="w-5 h-5 text-mint-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                @elseif ($p->estado === 'revision')
                <svg class="w-5 h-5 text-celeste-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                @else
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="font-bold text-gray-800 text-sm truncate">{{ $p->numero }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $p->estado_badge['style'] }}">
                        {{ $p->estado_badge['label'] }}
                    </span>
                </div>
                <p class="text-xs text-gray-400">{{ $p->created_at->format('d/m/Y') }}</p>
                @if ($p->planPago)
                <p class="text-xs text-lavanda-600 mt-0.5">Plan: {{ $p->planPago->cantidad_cuotas }} cuotas · Bs {{ number_format($p->planPago->monto_cuota, 2) }}/mes</p>
                @endif
            </div>

            {{-- Totales --}}
            <div class="text-right flex-shrink-0">
                <p class="font-bold text-celeste-700 text-base">Bs {{ number_format($p->total_pagar > 0 ? $p->total_pagar : $p->total, 2) }}</p>
                @if ($p->total_pagar > 0 && $p->total_pagar != $p->total)
                <p class="text-xs text-gray-400">prod. Bs {{ number_format($p->total, 2) }}</p>
                @endif
            </div>

            <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </div>
    @endforeach

    @if ($pedidos->hasPages())
    <div class="pt-2">{{ $pedidos->links() }}</div>
    @endif
</div>
@endif
@endif

</div>
