<div>

@php
    $theadClass = match($moduleColor ?? '') {
        'lavanda'   => 'bg-lavanda-100 text-lavanda-700',
        'mint'      => 'bg-mint-100 text-mint-700',
        'melocoton' => 'bg-melocoton-100 text-melocoton-700',
        'celeste'   => 'bg-celeste-100 text-celeste-700',
        default     => 'bg-gray-50 text-gray-600',
    };
@endphp

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

{{-- ══════════════════════════════════════════════════ CONFIG MODE ═══ --}}
@if ($mode === 'config' && $configMatriz)

{{-- Cabecera --}}
<div class="flex items-center gap-3 mb-6">
    <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <div>
        <p class="font-mono text-xs text-lavanda-600 font-semibold">{{ $configMatriz->code }}</p>
        <h2 class="text-base font-bold text-gray-800">{{ $configMatriz->name }}</h2>
        @if ($configMatriz->cycle)
        <p class="text-xs text-gray-400 font-mono">{{ $configMatriz->cycle->code }}</p>
        @endif
    </div>
    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $configMatriz->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
        {{ $configMatriz->active ? 'Activa' : 'Inactiva' }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Configuración financiera editable --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Configuración financiera</h3>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 space-y-4">

            {{-- Cuotas --}}
            <div>
                <label class="block text-xs text-gray-500 font-medium mb-1">Cantidad de cuotas *</label>
                <div class="flex items-center gap-3">
                    <input wire:model.live="cfgCantidadCuotas" type="number" min="1" placeholder="1"
                           class="w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ (int)$cfgCantidadCuotas === 1 ? 'bg-celeste-100 text-celeste-700' : 'bg-lavanda-100 text-lavanda-700' }}">
                        {{ (int)$cfgCantidadCuotas === 1 ? 'Contado' : 'Crédito' }}
                    </span>
                </div>
                @error('cfgCantidadCuotas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Cuota inicial --}}
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mb-2">
                    <input type="checkbox" wire:model.live="cfgUsaCuotaInicial" class="w-4 h-4 rounded text-lavanda-500 border-gray-300 cursor-pointer">
                    <span class="font-medium">Cuota inicial</span>
                </label>
                @if ($cfgUsaCuotaInicial)
                <div class="flex items-center gap-2 mt-1">
                    <select wire:model="cfgTipoCuotaInicial" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="monto_fijo">Monto Fijo (Bs)</option>
                    </select>
                    <input wire:model="cfgValorCuotaInicial" type="number" step="0.01" min="0" placeholder="0"
                           class="w-28 border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    @error('cfgValorCuotaInicial') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            {{-- Incremento --}}
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mb-2">
                    <input type="checkbox" wire:model.live="cfgUsaIncremento" class="w-4 h-4 rounded text-lavanda-500 border-gray-300 cursor-pointer">
                    <span class="font-medium">Incremento</span>
                </label>
                @if ($cfgUsaIncremento)
                <div class="flex items-center gap-2 mt-1">
                    <select wire:model="cfgTipoIncremento" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="monto_fijo">Monto Fijo (Bs)</option>
                    </select>
                    <input wire:model="cfgValorIncremento" type="number" step="0.01" min="0" placeholder="0"
                           class="w-28 border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    @error('cfgValorIncremento') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            <button wire:click="saveConfig"
                    class="w-full py-2.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Guardar configuración
            </button>
        </div>

        {{-- Resumen visual --}}
        <h3 class="text-sm font-semibold text-gray-700 mt-5 mb-3">Resumen actual</h3>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm divide-y divide-gray-50">
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-xs text-gray-500">Cuotas</span>
                <span class="text-sm font-semibold text-gray-800">{{ $configMatriz->cantidad_cuotas }}</span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-xs text-gray-500">Tipo</span>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $configMatriz->isContado() ? 'bg-celeste-100 text-celeste-700' : 'bg-lavanda-100 text-lavanda-700' }}">
                    {{ $configMatriz->isContado() ? 'Contado' : 'Crédito' }}
                </span>
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-xs text-gray-500">Cuota inicial</span>
                @if ($configMatriz->usa_cuota_inicial)
                    <span class="text-xs font-medium text-gray-700">
                        {{ $configMatriz->tipo_cuota_inicial === 'porcentaje' ? 'Porcentaje' : 'Monto Fijo' }}
                        · {{ number_format($configMatriz->valor_cuota_inicial, 2) }}{{ $configMatriz->tipo_cuota_inicial === 'porcentaje' ? '%' : ' Bs' }}
                    </span>
                @else
                    <span class="text-xs text-gray-400">No</span>
                @endif
            </div>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-xs text-gray-500">Incremento</span>
                @if ($configMatriz->usa_incremento)
                    <span class="text-xs font-medium text-gray-700">
                        {{ $configMatriz->tipo_incremento === 'porcentaje' ? 'Porcentaje' : 'Monto Fijo' }}
                        · {{ number_format($configMatriz->valor_incremento, 2) }}{{ $configMatriz->tipo_incremento === 'porcentaje' ? '%' : ' Bs' }}
                    </span>
                @else
                    <span class="text-xs text-gray-400">No</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Simulador --}}
    <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Simulador</h3>
        <div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5">
            <div class="flex gap-3 mb-4">
                <div class="flex-1">
                    <label class="block text-xs text-lavanda-600 font-medium mb-1">Monto del pedido (Bs)</label>
                    <input wire:model="simMonto" type="number" step="0.01" min="0.01" placeholder="0.00"
                           class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    @error('simMonto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <button wire:click="simular"
                            class="px-5 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">
                        Simular
                    </button>
                </div>
            </div>

            @if ($simResult)
            <div class="bg-white rounded-xl border border-lavanda-100 divide-y divide-gray-50">
                @if ($simResult['cuota_inicial'] > 0)
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-gray-500">Cuota inicial</span>
                    <span class="text-sm font-semibold text-melocoton-600">Bs {{ number_format($simResult['cuota_inicial'], 2) }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-gray-500">Saldo a financiar</span>
                    <span class="text-sm font-semibold text-gray-700">Bs {{ number_format($simResult['saldo_financiar'], 2) }}</span>
                </div>
                @endif
                @if ($simResult['incremento'] > 0)
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-gray-500">Incremento aplicado</span>
                    <span class="text-sm font-semibold text-melocoton-600">+ Bs {{ number_format($simResult['incremento'], 2) }}</span>
                </div>
                @endif
                @if (!$simResult['es_contado'])
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-gray-500">Cuota mensual</span>
                    <span class="text-sm font-semibold text-lavanda-700">
                        Bs {{ number_format($simResult['monto_cuota'], 2) }} × {{ $simResult['cantidad_cuotas'] }}
                    </span>
                </div>
                @endif
                <div class="flex items-center justify-between px-4 py-3 bg-lavanda-50 rounded-b-xl">
                    <span class="text-sm font-bold text-gray-700">Total a pagar</span>
                    <span class="text-base font-bold text-lavanda-700">Bs {{ number_format($simResult['total_pagar'], 2) }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════ LIST MODE ════ --}}
@else

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activas</option>
        <option value="0">Inactivas</option>
    </select>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Matriz
    </button>
</div>

{{-- Inline add form --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <p class="text-sm text-lavanda-600 font-semibold mb-4">Nueva Matriz Financiera</p>
    <div class="flex flex-wrap items-end gap-3">
        <div class="w-52">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Ciclo</p>
            <select wire:model="newCycleId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Sin ciclo —</option>
                @foreach ($cycles as $c)
                    <option value="{{ $c->id }}">{{ $c->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-28">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Código *</p>
            <input wire:model="newCode" type="text" maxlength="30" placeholder="MAT-01"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
            @error('newCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex-1 min-w-40">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Descripción</p>
            <input wire:model="newDescription" type="text" placeholder="Contado, Crédito 3 cuotas..."
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
        </div>
        <div class="flex flex-col items-center pb-0.5">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Activa</p>
            <input type="checkbox" wire:model="newActive" class="w-4 h-4 mt-1.5 rounded text-lavanda-500 border-gray-300 cursor-pointer">
        </div>
        <div class="flex gap-2 pb-0.5">
            <button wire:click="cancelAdd" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors">Cancelar</button>
            <button wire:click="saveNew" class="px-5 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">Guardar</button>
        </div>
    </div>
</div>
@endif

{{-- Tabla --}}
@php $sortColsM = ['Ciclo'=>'cycle_id','Código'=>'code','Descripción'=>'description','Estado'=>'active']; @endphp
<div class="hidden sm:block" style="background:#fff;border-radius:16px;border:1px solid #E5E7EB;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;display:flex;flex-direction:column;max-height:calc(100vh - 180px);">
    <div style="overflow:auto;flex:1;">
        <table style="width:100%;border-collapse:collapse;min-width:600px;">
            <thead style="position:sticky;top:0;z-index:10;">
                <tr style="background:#F9F8FF;border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 8px;text-align:center;font-size:11px;font-weight:700;color:#C4B5FD;text-transform:uppercase;letter-spacing:.5px;">#</th>
                    @foreach($sortColsM as $label => $key)
                    @php $isActive = $sortBy === $key; @endphp
                    <th wire:click="toggleSort('{{ $key }}')"
                        style="padding:10px 14px;text-align:{{ $label==='Estado' ? 'center' : 'left' }};font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap;cursor:pointer;user-select:none;{{ $isActive ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                        <span style="display:inline-flex;align-items:center;gap:5px;">{{ $label }}
                            @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                            @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                            @endif
                        </span>
                    </th>
                    @endforeach
                    <th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#7B6FE8;text-transform:uppercase;letter-spacing:.5px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($matrices as $m)
                @if ($editingId === $m->id)
                @php $iE = 'width:100%;height:30px;border:1px solid #EDE9FE;border-radius:7px;padding:0 8px;font-size:12px;outline:none;box-sizing:border-box;background:#fff;color:#374151;'; @endphp
                {{-- EDICIÓN INLINE --}}
                <tr wire:key="edit-{{ $m->id }}" style="background:#F8F7FF;border-bottom:1px solid #EDE9FE;border-left:3px solid #7B6FE8;">
                    <td class="col-row-num" style="padding:10px 8px;text-align:center;font-size:11px;font-weight:700;white-space:nowrap;">{{ $matrices->firstItem() + $loop->index }}</td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editCycleId" style="{{ $iE }} cursor:pointer;">
                            <option value="">—</option>
                            @foreach ($cycles as $c)
                                <option value="{{ $c->id }}">{{ $c->code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editCode" type="text" maxlength="30" style="{{ $iE }} font-family:monospace;text-transform:uppercase;">
                        @error('editCode') <p style="font-size:10px;color:#EF4444;margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <input wire:model="editDescription" type="text" placeholder="Descripción..." style="{{ $iE }}">
                    </td>
                    <td style="padding:7px 10px;text-align:center;">
                        <input type="checkbox" wire:model="editActive" style="width:16px;height:16px;cursor:pointer;accent-color:#7B6FE8;">
                    </td>
                    <td style="padding:7px 10px;text-align:center;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:4px;">
                            <button wire:click="saveEdit" title="Guardar" style="height:28px;padding:0 10px;border-radius:7px;border:none;background:#7B6FE8;color:#fff;font-size:12px;font-weight:700;cursor:pointer;" @mouseenter="$el.style.opacity='.85'" @mouseleave="$el.style.opacity='1'">✓</button>
                            <button wire:click="cancelEdit" title="Cancelar" style="height:28px;padding:0 8px;border-radius:7px;border:1px solid #E5E7EB;background:#F3F4F6;color:#6B7280;font-size:12px;cursor:pointer;" @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">✕</button>
                        </div>
                    </td>
                </tr>

                @else
                {{-- FILA NORMAL --}}
                <tr wire:key="m-{{ $m->id }}" style="border-bottom:1px solid #F3F4F6;transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                    <td class="col-row-num" style="padding:10px 8px;text-align:center;font-size:11px;font-weight:700;white-space:nowrap;">{{ $matrices->firstItem() + $loop->index }}</td>
                    <td style="padding:10px 14px;font-size:12px;font-family:monospace;color:#6B7280;white-space:nowrap;">{{ $m->cycle?->code ?? '—' }}</td>
                    <td style="padding:10px 14px;font-size:12px;font-family:monospace;font-weight:700;color:#111827;white-space:nowrap;">{{ $m->code }}</td>
                    <td style="padding:10px 14px;font-size:13px;color:#6B7280;">{{ $m->description ? Str::limit($m->description, 60) : '—' }}</td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="padding:3px 10px;border-radius:6px;font-size:12px;font-weight:700;
                                     background:{{ $m->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $m->active ? '#059669' : '#9CA3AF' }};">
                            {{ $m->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        <div style="display:inline-flex;align-items:center;justify-content:center;gap:4px;">
                            <button wire:click="startEdit({{ $m->id }})" title="Editar"
                                    style="width:28px;height:28px;border-radius:7px;border:1px solid #EDE9FE;background:#F5F3FF;color:#7B6FE8;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="openConfig({{ $m->id }})" title="Configuración"
                                    style="width:28px;height:28px;border-radius:7px;border:1px solid #BAE6FD;background:#F0F9FF;color:#0369A1;cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                    @mouseenter="$el.style.background='#E0F2FE'" @mouseleave="$el.style.background='#F0F9FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $m->id }})" title="{{ $m->active ? 'Desactivar' : 'Activar' }}"
                                    style="width:28px;height:28px;border-radius:7px;border:1px solid {{ $m->active ? '#FECACA' : '#BBF7D0' }};background:{{ $m->active ? '#FEF2F2' : '#F0FDF4' }};color:{{ $m->active ? '#DC2626' : '#059669' }};cursor:pointer;display:flex;align-items:center;justify-content:center;"
                                    @mouseenter="$el.style.opacity='.75'" @mouseleave="$el.style.opacity='1'">
                                @if ($m->active)
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                @else
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" style="padding:48px;text-align:center;color:#9CA3AF;font-size:13px;">No hay matrices registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($matrices->hasPages())
    <div style="padding:10px 16px;border-top:1px solid #F3F4F6;flex-shrink:0;">{{ $matrices->links() }}</div>
    @endif
</div>

{{-- MOBILE --}}
<div class="sm:hidden">
    @forelse ($matrices as $m)
    @if ($editingId === $m->id)
    <div wire:key="edit-mobile-m-{{ $m->id }}" class="bg-lavanda-50 border border-lavanda-200 rounded-2xl mb-3 p-4">
        <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Editando matriz</p>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-xs font-semibold text-lavanda-600 mb-1">Ciclo</label>
                <select wire:model="editCycleId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="">—</option>
                    @foreach ($cycles as $c)
                        <option value="{{ $c->id }}">{{ $c->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-lavanda-600 mb-1">Código *</label>
                <input wire:model="editCode" type="text" maxlength="30"
                       class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
                @error('editCode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="block text-xs font-semibold text-lavanda-600 mb-1">Descripción</label>
            <input wire:model="editDescription" type="text"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
        </div>
        <div class="mb-3">
            <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                <input type="checkbox" wire:model="editActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
                <span>Activa</span>
            </label>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveEdit" class="flex-1 h-9 bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl text-sm font-semibold transition-colors">Guardar</button>
            <button wire:click="cancelEdit" class="flex-1 h-9 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-semibold transition-colors">Cancelar</button>
        </div>
    </div>
    @else
    <div wire:key="mobile-m-{{ $m->id }}" class="bg-white rounded-2xl border border-gray-100 mb-3 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 p-3">
            <div style="width:30px;height:30px;border-radius:8px;background:#EDE9FE;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:12px;font-weight:700;color:#7B6FE8;">{{ strtoupper(substr($m->code, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-sm font-bold text-lavanda-700">{{ $m->code }}</span>
                    @if ($m->cycle)
                    <span class="font-mono text-xs text-gray-400">{{ $m->cycle->code }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-xs text-gray-600 truncate">{{ $m->description ?? '—' }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold shrink-0
                        {{ $m->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
                        {{ $m->active ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 px-3 py-2 flex gap-2">
            <button wire:click="startEdit({{ $m->id }})"
                    class="flex-1 h-8 bg-lavanda-50 text-lavanda-700 border border-lavanda-100 rounded-lg text-xs font-semibold">
                Editar
            </button>
            <button wire:click="openConfig({{ $m->id }})"
                    class="flex-1 h-8 bg-celeste-50 text-celeste-700 border border-celeste-100 rounded-lg text-xs font-semibold">
                Configurar
            </button>
        </div>
    </div>
    @endif
    @empty
    <p class="text-center text-gray-400 text-sm py-12">No hay matrices registradas.</p>
    @endforelse
    @if ($matrices->hasPages())
    <div class="mt-2">{{ $matrices->links() }}</div>
    @endif
</div>

@endif
</div>
