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

{{-- ═══════════════════════════════════════════════════════ ITEMS MODE ═══ --}}
@if ($mode === 'items' && $viewingMaestra)

<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <p class="font-mono text-xs text-lavanda-600 font-semibold">{{ $viewingMaestra->code }}</p>
            <h2 class="text-base font-bold text-gray-800">{{ $viewingMaestra->name }}</h2>
        </div>
        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $viewingMaestra->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
            {{ $viewingMaestra->active ? 'Activa' : 'Inactiva' }}
        </span>
    </div>
    <div class="flex gap-2">
        <button wire:click="refreshFromCatalog"
                class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Actualizar
        </button>
        <button wire:click="showAddItem"
                class="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Producto
        </button>
    </div>
</div>

{{-- Formulario nuevo producto --}}
@if ($showAddItemForm)
@php
    $iStyle = 'border:1px solid #CECBF6; border-radius:6px; padding:7px 10px; font-size:12px; background:#fff; outline:none; width:100%; text-align:center;';
    $lStyle = 'display:block; font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;';
@endphp
<div class="mb-4" style="background:#FAFAFE; border:1px solid #CECBF6; border-radius:10px; padding:14px 16px;">
    <div class="flex items-center gap-2 mb-3">
        <div style="width:3px; height:16px; background:#7c3aed; border-radius:2px; flex-shrink:0;"></div>
        <span style="font-size:12px; font-weight:600; color:#3C3489;">Crear producto en catálogo y agregar a esta lista</span>
        @if ($viewingMaestra?->tipo_incremento)
        <span class="ml-2" style="font-size:10px; color:#0F6E56; background:#e6f4ef; border:1px solid #b7e4d1; border-radius:20px; padding:2px 8px; font-weight:600;">
            Incremento: {{ $viewingMaestra->tipo_incremento === 'porcentaje' ? $viewingMaestra->valor_incremento.'%' : 'Bs '.$viewingMaestra->valor_incremento }} se aplicará automáticamente
        </span>
        @endif
    </div>
    <div class="flex flex-wrap items-end gap-2">
        <div style="width:100px;">
            <label style="{{ $lStyle }}">Código *</label>
            <input wire:model="newItemCode" type="text" placeholder="PROD-001" style="{{ $iStyle }} text-align:left; font-family:monospace;">
            @error('newItemCode') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:150px;">
            <label style="{{ $lStyle }}">Nombre *</label>
            <input wire:model="newItemNombre" type="text" placeholder="Nombre del producto" style="{{ $iStyle }} text-align:left;">
            @error('newItemNombre') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div style="width:85px;">
            <label style="{{ $lStyle }}">Precio (Bs) *</label>
            <input wire:model="newItemPrecio" type="number" step="0.01" min="0" placeholder="0.00" style="{{ $iStyle }}">
            @error('newItemPrecio') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div style="width:65px;">
            <label style="{{ $lStyle }}">Puntos</label>
            <input wire:model="newItemPuntos" type="number" min="0" placeholder="0" style="{{ $iStyle }}">
        </div>
        <div style="width:75px;">
            <label style="{{ $lStyle }}">Stock ini.</label>
            <input wire:model="newItemStock" type="number" step="0.01" min="0" placeholder="0" style="{{ $iStyle }}">
        </div>
        <div style="width:110px;">
            <label style="{{ $lStyle }}">Unidad</label>
            <select wire:model="newItemUnidadId" style="{{ $iStyle }}">
                <option value="">— —</option>
                @foreach ($unidades as $u)
                    <option value="{{ $u->id }}">{{ $u->abreviatura ?? $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="width:120px;">
            <label style="{{ $lStyle }}">Categoría</label>
            <select wire:model="newItemCatId" style="{{ $iStyle }}">
                <option value="">— —</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col items-center" style="padding-bottom:2px;">
            <label style="{{ $lStyle }} text-align:center;">Activo</label>
            <input type="checkbox" wire:model="newItemActive" class="cursor-pointer" style="width:16px; height:16px; margin-top:6px; accent-color:#7c3aed;">
        </div>
        <div class="flex gap-2" style="padding-bottom:2px;">
            <button wire:click="saveNewItem"
                    class="transition-colors hover:opacity-90"
                    style="background:#7c3aed; color:#fff; font-size:12px; font-weight:600; padding:7px 16px; border-radius:6px; border:none; cursor:pointer;">
                Guardar
            </button>
            <button wire:click="cancelAddItem"
                    class="transition-colors hover:bg-gray-50"
                    style="border:1px solid #d1d5db; color:#6b7280; font-size:12px; padding:7px 14px; border-radius:6px; cursor:pointer; background:#fff;">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- Filtros del catálogo --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    <input wire:model.live.debounce.300ms="filterCodigo" type="text" placeholder="Código..."
           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 w-32">
    <input wire:model.live.debounce.300ms="filterProducto" type="text" placeholder="Nombre del producto..."
           class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
    <select wire:model.live="filterEnLista" class="border border-gray-200 rounded-xl px-3 py-2 text-sm bg-white focus:outline-none focus:border-lavanda-400">
        <option value="">Todos</option>
        <option value="1">En lista</option>
        <option value="0">Disponibles</option>
    </select>
</div>

{{-- Tabla de productos --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase w-20">Código</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Producto</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Precio Base</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Puntos</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">St. Inicial</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Consumido</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Actual</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden xl:table-cell">Tipo Inc.</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden xl:table-cell">Incremento</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">P. Final</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($products as $p)
                @php $item = $itemsMap->get($p->id); $inLista = $item !== null; @endphp

                @if ($inLista && $editItemId === $item->id)
                {{-- EDICIÓN INLINE ÍTEM — fila expandida --}}
                <tr wire:key="item-edit-{{ $item->id }}"
                    x-data="{
                        precio:  parseFloat(@js((float)$editItemPrecio)) || 0,
                        tipo:    @js($editItemTipoIncremento),
                        factor:  parseFloat(@js((float)$editItemFactorIncremento)) || 0,
                        get monto() {
                            if (!this.tipo || !this.factor) return 0;
                            return this.tipo === 'porcentaje'
                                ? Math.round(this.precio * this.factor / 100 * 100) / 100
                                : parseFloat(this.factor);
                        },
                        get final() { return (this.precio + this.monto).toFixed(2); }
                    }"
                    style="background:#F8F7FF; border-left:3px solid #7c3aed;">
                    <td colspan="12" style="padding:12px 16px;">
                        <div class="flex flex-wrap items-end gap-2">

                            {{-- Código (disabled) --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">Código</p>
                                <input type="text" value="{{ $p->code }}" disabled
                                       style="width:90px; border:1px solid #CECBF6; border-radius:6px; padding:6px 10px; font-size:12px; background:#F8F7FF; color:#9ca3af; font-family:monospace; cursor:not-allowed;">
                            </div>

                            {{-- Precio --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">Precio</p>
                                <div style="display:flex; border:1px solid #CECBF6; border-radius:6px; overflow:hidden; background:#fff;">
                                    <span style="padding:6px 8px; background:#f5f3ff; border-right:1px solid #CECBF6; font-size:11px; font-weight:600; color:#7c3aed; display:flex; align-items:center; white-space:nowrap;">Bs</span>
                                    <input wire:model="editItemPrecio" x-on:input="precio = parseFloat($event.target.value) || 0"
                                           type="number" step="0.01" min="0" style="width:75px; border:none; outline:none; padding:6px 8px; font-size:12px; background:#fff; text-align:center;">
                                </div>
                                @error('editItemPrecio') <p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>

                            {{-- Puntos --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">Puntos</p>
                                <div style="display:flex; border:1px solid #CECBF6; border-radius:6px; overflow:hidden; background:#fff;">
                                    <span style="padding:6px 8px; background:#f5f3ff; border-right:1px solid #CECBF6; font-size:12px; color:#7c3aed; display:flex; align-items:center;">★</span>
                                    <input wire:model="editItemPuntos" type="number" min="0"
                                           style="width:60px; border:none; outline:none; padding:6px 8px; font-size:12px; background:#fff; text-align:center;">
                                </div>
                            </div>

                            {{-- Stock Inicial --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">St. Inicial</p>
                                <div style="display:flex; border:1px solid #CECBF6; border-radius:6px; overflow:hidden; background:#fff;">
                                    <span style="padding:6px 8px; background:#f5f3ff; border-right:1px solid #CECBF6; display:flex; align-items:center;">
                                        <svg style="width:12px; height:12px; color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </span>
                                    <input wire:model="editItemStock" type="number" step="0.01" min="0"
                                           style="width:70px; border:none; outline:none; padding:6px 8px; font-size:12px; background:#fff; text-align:center;">
                                </div>
                            </div>

                            {{-- Tipo Incremento --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">Tipo Inc.</p>
                                <select wire:model="editItemTipoIncremento" x-on:change="tipo = $event.target.value"
                                        style="border:1px solid #CECBF6; border-radius:6px; padding:6px 10px; font-size:12px; background:#fff; outline:none; width:130px;">
                                    <option value="">— Sin inc. —</option>
                                    <option value="porcentaje">% Porcentaje</option>
                                    <option value="monto_fijo">Bs Monto Fijo</option>
                                </select>
                            </div>

                            {{-- Valor Incremento --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">Valor Inc.</p>
                                <div style="display:flex; border:1px solid #CECBF6; border-radius:6px; overflow:hidden; background:#fff;">
                                    <span x-text="tipo === 'porcentaje' ? '%' : (tipo === 'monto_fijo' ? 'Bs' : '#')"
                                          style="padding:6px 8px; background:#f5f3ff; border-right:1px solid #CECBF6; font-size:11px; font-weight:600; color:#7c3aed; display:flex; align-items:center; min-width:26px; justify-content:center;"></span>
                                    <input wire:model="editItemFactorIncremento" x-on:input="factor = parseFloat($event.target.value) || 0"
                                           type="number" step="0.01" min="0" placeholder="0"
                                           style="width:70px; border:none; outline:none; padding:6px 8px; font-size:12px; background:#fff; text-align:center;">
                                </div>
                                @error('editItemFactorIncremento') <p style="font-size:10px; color:#ef4444; margin-top:2px;">{{ $message }}</p> @enderror
                            </div>

                            {{-- P. Final (Alpine live) --}}
                            <div>
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;">P. Final</p>
                                <div style="border:1px solid #CECBF6; border-radius:6px; padding:6px 12px; background:#f5f3ff; min-width:90px; text-align:center;">
                                    <span style="font-size:11px; color:#9ca3af; margin-right:2px;">Bs</span>
                                    <span x-text="final" style="font-size:14px; font-weight:700; color:#7c3aed;"></span>
                                </div>
                            </div>

                            {{-- Toggle Activo --}}
                            <div class="flex flex-col items-center" style="padding-bottom:2px;">
                                <p style="font-size:10px; font-weight:600; color:#534AB7; margin-bottom:6px;">Activo</p>
                                <input type="checkbox" wire:model="editItemActive" class="cursor-pointer" style="width:16px; height:16px; accent-color:#7c3aed;">
                            </div>

                            {{-- Botones --}}
                            <div class="flex gap-1.5" style="padding-bottom:2px;">
                                <button wire:click="saveEditItem" title="Guardar"
                                        style="width:32px; height:32px; border-radius:50%; background:#d1fae5; border:1.5px solid #6ee7b7; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.15s;"
                                        onmouseover="this.style.background='#6ee7b7'" onmouseout="this.style.background='#d1fae5'">
                                    <svg style="width:14px; height:14px; color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button wire:click="cancelEditItem" title="Cancelar"
                                        style="width:32px; height:32px; border-radius:50%; background:#fee2e2; border:1.5px solid #fca5a5; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.15s;"
                                        onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fee2e2'">
                                    <svg style="width:14px; height:14px; color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                        </div>
                    </td>
                </tr>

                @elseif (!$inLista && $quickAddProductId === $p->id)
                {{-- QUICK-ADD INLINE --}}
                <tr wire:key="qa-{{ $p->id }}" class="bg-celeste-50 border-l-2 border-celeste-400">
                    <td class="px-3 py-2 text-center font-mono text-xs text-gray-500">{{ $p->code }}</td>
                    <td class="px-4 py-2 text-center text-gray-700">{{ $p->name }}</td>
                    <td class="px-4 py-2 text-center">
                        <input wire:model="quickAddPrecio" type="number" step="0.01" min="0" placeholder="0.00"
                               class="w-24 border border-celeste-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none bg-white">
                        @error('quickAddPrecio') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2 text-center">
                        <input wire:model="quickAddPuntos" type="number" min="0" placeholder="0"
                               class="w-20 border border-celeste-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none bg-white">
                    </td>
                    <td class="px-4 py-2 text-center hidden lg:table-cell">
                        <input wire:model="quickAddStock" type="number" step="0.01" min="0" placeholder="0"
                               class="w-24 border border-celeste-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none bg-white">
                    </td>
                    <td colspan="2" class="hidden lg:table-cell"></td>
                    <td colspan="3" class="hidden xl:table-cell"></td>
                    <td class="px-4 py-2 text-center"><span class="text-xs text-celeste-600 font-medium">Agregar</span></td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button wire:click="saveQuickAdd" class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button wire:click="cancelQuickAdd" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>

                @else
                {{-- FILA NORMAL --}}
                <tr wire:key="prod-{{ $p->id }}" class="{{ $inLista ? 'hover:bg-gray-50' : 'hover:bg-gray-50 opacity-60' }} transition-colors">
                    <td class="px-3 py-3 text-center font-mono text-xs text-gray-500">{{ $p->code }}</td>
                    <td class="px-4 py-3 text-center font-medium text-gray-800">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-center text-gray-700">
                        @if ($inLista) Bs {{ number_format($item->precio_base, 2) }}
                        @else <span class="text-gray-300">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-700">
                        @if ($inLista) {{ $item->puntos }}
                        @else <span class="text-gray-300">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-500 hidden lg:table-cell text-xs">
                        @if ($inLista) {{ number_format($item->stock_inicial, 2) }}
                        @else <span class="text-gray-300">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-500 hidden lg:table-cell text-xs">
                        @if ($inLista) {{ number_format($item->stock_consumido, 2) }}
                        @else <span class="text-gray-300">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center hidden lg:table-cell text-xs
                        {{ $inLista && $item->stock_actual <= 0 ? 'text-red-500 font-semibold' : 'text-gray-700' }}">
                        @if ($inLista) {{ number_format($item->stock_actual, 2) }}
                        @else <span class="text-gray-300">—</span> @endif
                    </td>
                    <td class="px-4 py-3 text-center hidden xl:table-cell">
                        @if ($inLista && $item->tipo_incremento)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-lavanda-100 text-lavanda-700">
                            {{ $item->tipo_incremento === 'porcentaje' ? '%' : 'Bs' }}
                        </span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center hidden xl:table-cell text-xs text-gray-700">
                        @if ($inLista && $item->factor_incremento > 0)
                            {{ $item->tipo_incremento === 'porcentaje'
                                ? number_format($item->factor_incremento, 2).'%'
                                : 'Bs '.number_format($item->factor_incremento, 2) }}
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-xs font-semibold {{ $inLista ? 'text-lavanda-700' : 'text-gray-300' }}">
                        @if ($inLista) Bs {{ number_format($item->precio_final, 2) }}
                        @else —
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($inLista)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $item->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
                            {{ $item->active ? 'Activo' : 'Inactivo' }}
                        </span>
                        @else
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-400">Sin agregar</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            @if ($inLista)
                                <button wire:click="startEditItem({{ $item->id }})" title="Editar"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="toggleItemActive({{ $item->id }})" title="{{ $item->active ? 'Desactivar' : 'Activar' }}"
                                        class="p-1.5 rounded-lg transition-colors {{ $item->active ? 'text-gray-400 hover:text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                    @if ($item->active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                                <button wire:click="removeItem({{ $item->id }})" title="Quitar de lista"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @else
                                <button wire:click="startQuickAdd({{ $p->id }})" title="Agregar a lista"
                                        class="flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs text-celeste-700 bg-celeste-100 hover:bg-celeste-200 font-medium transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Agregar
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="12" class="px-5 py-14 text-center text-gray-400 text-sm">No hay productos en el catálogo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ ACCESO MODE ══ --}}
@elseif ($mode === 'acceso' && $viewingMaestra)

<div class="flex items-center gap-3 mb-6">
    <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <div>
        <h2 class="text-base font-bold text-gray-800">Acceso — {{ $viewingMaestra->name }}</h2>
        <p class="text-xs text-gray-500 font-mono">{{ $viewingMaestra->cycle?->code ?? '—' }}</p>
    </div>
    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $viewingMaestra->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
        {{ $viewingMaestra->active ? 'Activa' : 'Inactiva' }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- SECCIÓN A: CLIENTES --}}
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-mint-100 text-mint-700 flex items-center justify-center text-xs font-bold">A</span>
            Clientes
        </h3>

        {{-- Consulta dinámica --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Consulta dinámica</p>
            <textarea wire:model="sqlCliente" rows="2" placeholder="email LIKE '%@empresa.com' OR id IN (1,2,3)"
                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-mono focus:outline-none focus:border-lavanda-400 bg-white resize-none"></textarea>
            @if ($sqlClienteError)
                <p class="text-red-500 text-xs mt-1">{{ $sqlClienteError }}</p>
            @endif
        </div>

        {{-- Agregar manualmente --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Agregar manualmente</p>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="searchCliente" type="text" placeholder="Buscar por código, nombre o apellido..."
                       class="w-full pl-10 pr-4 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            </div>
            @if ($manualClienteResult !== null)
            <div class="mt-2 rounded-xl border border-gray-200 bg-white overflow-hidden">
                @forelse ($manualClienteResult as $u)
                <button wire:click="addClienteManual({{ $u['id'] }})"
                        class="w-full flex items-center gap-3 px-3 py-2.5 border-b border-gray-50 last:border-0 hover:bg-mint-50 transition-colors text-left">
                    <span class="font-mono text-xs text-gray-400 w-8 shrink-0">#{{ $u['id'] }}</span>
                    <span class="text-sm font-medium text-gray-800">{{ $u['name'] }}</span>
                </button>
                @empty
                <p class="px-3 py-3 text-gray-400 text-xs">Sin resultados.</p>
                @endforelse
            </div>
            @endif
        </div>

        {{-- Resumen clientes --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-600">Clientes con acceso ({{ $accesosClientes->count() }})</p>
            </div>
            @if ($accesosClientes->count())
            <table class="w-full text-xs">
                <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-500 font-semibold uppercase w-10">ID</th>
                        <th class="px-3 py-2 text-left text-gray-500 font-semibold uppercase">Nombre</th>
                        <th class="px-3 py-2 text-center text-gray-500 font-semibold uppercase">Origen</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($accesosClientes as $acc)
                    <tr wire:key="acc-c-{{ $acc->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-2 font-mono text-gray-400">#{{ $acc->user?->id }}</td>
                        <td class="px-3 py-2 font-medium text-gray-800">{{ $acc->user?->name }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full {{ $acc->origen === 'sql' ? 'bg-celeste-100 text-celeste-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($acc->origen) }}</span>
                        </td>
                        <td class="px-3 py-2 text-right">
                            <button wire:click="removeAcceso({{ $acc->id }})" class="text-red-400 hover:text-red-600 transition-colors p-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-4 py-6 text-center space-y-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-mint-50 border border-mint-200 rounded-full text-mint-700 text-xs font-semibold">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h.5A2.5 2.5 0 0020 5.5v-1.5"/></svg>
                    Acceso abierto a todos los clientes
                </div>
                <p class="text-gray-400 text-[10px]">Sin restricciones — todos los clientes pueden acceder a esta lista</p>
            </div>
            @endif
        </div>
    </div>

    {{-- SECCIÓN B: VENDEDORES --}}
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-melocoton-100 text-melocoton-700 flex items-center justify-center text-xs font-bold">B</span>
            Vendedores
        </h3>

        {{-- Consulta dinámica --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Consulta dinámica</p>
            <textarea wire:model="sqlVendedor" rows="2" placeholder="email LIKE '%@empresa.com' OR id IN (1,2,3)"
                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-mono focus:outline-none focus:border-lavanda-400 bg-white resize-none"></textarea>
            @if ($sqlVendedorError)
                <p class="text-red-500 text-xs mt-1">{{ $sqlVendedorError }}</p>
            @endif
        </div>

        {{-- Agregar manualmente --}}
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Agregar manualmente</p>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="searchVendedor" type="text" placeholder="Buscar por código, nombre o apellido..."
                       class="w-full pl-10 pr-4 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            </div>
            @if ($manualVendedorResult !== null)
            <div class="mt-2 rounded-xl border border-gray-200 bg-white overflow-hidden">
                @forelse ($manualVendedorResult as $u)
                <button wire:click="addVendedorManual({{ $u['id'] }})"
                        class="w-full flex items-center gap-3 px-3 py-2.5 border-b border-gray-50 last:border-0 hover:bg-melocoton-50 transition-colors text-left">
                    <span class="font-mono text-xs text-gray-400 w-8 shrink-0">#{{ $u['id'] }}</span>
                    <span class="text-sm font-medium text-gray-800">{{ $u['name'] }}</span>
                </button>
                @empty
                <p class="px-3 py-3 text-gray-400 text-xs">Sin resultados.</p>
                @endforelse
            </div>
            @endif
        </div>

        {{-- Resumen vendedores --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-2.5 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-600">Vendedores con acceso ({{ $accesosVendedores->count() }})</p>
            </div>
            @if ($accesosVendedores->count())
            <table class="w-full text-xs">
                <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-500 font-semibold uppercase w-10">ID</th>
                        <th class="px-3 py-2 text-left text-gray-500 font-semibold uppercase">Nombre</th>
                        <th class="px-3 py-2 text-center text-gray-500 font-semibold uppercase">Origen</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($accesosVendedores as $acc)
                    <tr wire:key="acc-v-{{ $acc->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-2 font-mono text-gray-400">#{{ $acc->user?->id }}</td>
                        <td class="px-3 py-2 font-medium text-gray-800">{{ $acc->user?->name }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full {{ $acc->origen === 'sql' ? 'bg-celeste-100 text-celeste-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($acc->origen) }}</span>
                        </td>
                        <td class="px-3 py-2 text-right">
                            <button wire:click="removeAcceso({{ $acc->id }})" class="text-red-400 hover:text-red-600 transition-colors p-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-4 py-6 text-center space-y-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-melocoton-50 border border-melocoton-200 rounded-full text-melocoton-700 text-xs font-semibold">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h.5A2.5 2.5 0 0020 5.5v-1.5"/></svg>
                    Acceso abierto a todos los vendedores
                </div>
                <p class="text-gray-400 text-[10px]">Sin restricciones — todos los vendedores pueden acceder a esta lista</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════ LIST MODE ════ --}}
@else

<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código o nombre..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterCycleId" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los ciclos</option>
        @foreach ($cycles as $cycle)
            <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
        @endforeach
    </select>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activa</option>
        <option value="0">Inactiva</option>
    </select>
    <button wire:click="showAdd" class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nueva Lista
    </button>
</div>

@if ($showAddForm)
@php
    $inputStyle  = 'border:1px solid #CECBF6; border-radius:6px; padding:7px 10px; font-size:12px; background:#FAFAFE; outline:none; width:100%;';
    $labelStyle  = 'display:block; font-size:10px; font-weight:600; color:#534AB7; margin-bottom:4px;';
    $selectStyle = $inputStyle;
@endphp
<div class="rounded-2xl border border-lavanda-200 p-5 mb-5" style="background:#FAFAFE;"
     x-data="{
         tipoInc: @js($newTipoIncremento),
         valorInc: parseFloat(@js($newValorIncremento)) || 0,
         cantCuotas: parseInt(@js($newCantidadCuotas)) || 0,
         diasCuotas: parseInt(@js($newDiasEntreCuotas)) || 30,
         tipoCuotaIni: @js($newTipoCuotaInicial ?: 'ninguna'),
         valorCuotaIni: parseFloat(@js($newValorCuotaInicial)) || 0,
         get badgeInc() {
             if (!this.tipoInc || !this.valorInc) return null;
             let base = 100, v = parseFloat(this.valorInc) || 0;
             let inc = this.tipoInc === 'porcentaje' ? (base * v / 100) : v;
             let label = this.tipoInc === 'porcentaje' ? v + '%' : 'Bs ' + v.toFixed(2);
             return 'Bs 100 + ' + label + ' → Bs ' + (base + inc).toFixed(2);
         },
         get badgeResumen() {
             if (!this.cantCuotas) return null;
             let pedido = 1000, inicial = 0, textoIni = '';
             if (this.tipoCuotaIni !== 'ninguna' && this.valorCuotaIni > 0) {
                 let v = parseFloat(this.valorCuotaIni) || 0;
                 inicial = this.tipoCuotaIni === 'porcentaje' ? pedido * v / 100 : v;
                 textoIni = ' → Inicial ' + (this.tipoCuotaIni === 'porcentaje' ? v + '%' : 'Bs ' + v.toFixed(2)) + ' = Bs ' + inicial.toFixed(2);
             }
             let saldo = pedido - inicial;
             let cuota = this.cantCuotas > 0 ? saldo / this.cantCuotas : 0;
             return 'Ej: Pedido Bs 1,000' + textoIni + ' → Saldo Bs ' + saldo.toFixed(2) + ' ÷ ' + this.cantCuotas + ' cuotas = Bs ' + cuota.toFixed(2);
         }
     }">

    <h3 class="font-bold mb-4" style="font-size:13px; color:#3C3489;">Nueva Lista de Precios</h3>

    {{-- FILA 1: Información general --}}
    <div class="flex flex-wrap items-end gap-3 mb-4">
        <div style="width:130px;">
            <label style="{{ $labelStyle }}">Código *</label>
            <input wire:model="newCode" type="text" maxlength="30" placeholder="LP-202601"
                   style="{{ $inputStyle }} font-family:monospace;">
            @error('newCode') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:160px;">
            <label style="{{ $labelStyle }}">Nombre *</label>
            <input wire:model="newName" type="text" placeholder="Lista Enero 2026" style="{{ $inputStyle }}">
            @error('newName') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div style="width:140px;">
            <label style="{{ $labelStyle }}">Ciclo *</label>
            <select wire:model="newCycleId" style="{{ $selectStyle }}">
                <option value="">— Seleccionar —</option>
                @foreach ($cycles as $cycle)
                    <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
                @endforeach
            </select>
            @error('newCycleId') <p class="text-red-500 mt-1" style="font-size:10px;">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center gap-2 pb-1">
            <input wire:model="newActive" type="checkbox" id="newActive" class="w-4 h-4 rounded cursor-pointer" style="accent-color:#7c3aed;">
            <label for="newActive" style="font-size:12px; font-weight:500; color:#3C3489; cursor:pointer;">Activa</label>
        </div>
    </div>

    {{-- Separador --}}
    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
        <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Incremento de Precio</span>
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
    </div>

    {{-- FILA 2: Incremento --}}
    <div class="flex flex-wrap items-end gap-3 mb-4">
        <div style="width:160px;">
            <label style="{{ $labelStyle }}">Tipo Incremento</label>
            <select wire:model="newTipoIncremento" x-on:change="tipoInc = $event.target.value"
                    style="{{ $selectStyle }}">
                <option value="">— Sin incremento —</option>
                <option value="porcentaje">Porcentaje %</option>
                <option value="monto_fijo">Monto Fijo Bs</option>
            </select>
        </div>
        <div style="width:110px;">
            <label style="{{ $labelStyle }}">Valor</label>
            <input wire:model="newValorIncremento" x-on:input="valorInc = parseFloat($event.target.value) || 0"
                   type="number" step="0.01" min="0" placeholder="Ej: 10"
                   style="{{ $inputStyle }} text-align:center;">
        </div>
        <div x-show="badgeInc" class="flex items-center px-3 py-1.5 rounded-lg" style="background:#e6f4ef; border:1px solid #b7e4d1;">
            <span x-text="badgeInc" style="font-size:11px; font-weight:600; color:#0F6E56;"></span>
        </div>
    </div>

    {{-- Separador --}}
    <div class="flex items-center gap-3 my-4">
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
        <span style="font-size:10px; font-weight:700; color:#534AB7; text-transform:uppercase; letter-spacing:0.06em;">Plan de Financiamiento</span>
        <div class="flex-1 h-px" style="background:#CECBF6;"></div>
    </div>

    {{-- FILA 3: Financiamiento --}}
    <div class="flex flex-wrap items-end gap-3 mb-3">
        <div style="width:150px;">
            <label style="{{ $labelStyle }}">Cantidad de Cuotas</label>
            <input wire:model="newCantidadCuotas" x-on:input="cantCuotas = parseInt($event.target.value) || 0"
                   type="number" min="1" max="999" placeholder="Ej: 6"
                   style="{{ $inputStyle }} text-align:center;">
        </div>
        <div style="width:150px;">
            <label style="{{ $labelStyle }}">Días entre Cuotas</label>
            <input wire:model="newDiasEntreCuotas" x-on:input="diasCuotas = parseInt($event.target.value) || 30"
                   type="number" min="1" max="365" placeholder="Ej: 30"
                   style="{{ $inputStyle }} text-align:center;">
        </div>
        <div style="width:160px;">
            <label style="{{ $labelStyle }}">Tipo Cuota Inicial</label>
            <select wire:model="newTipoCuotaInicial" x-on:change="tipoCuotaIni = $event.target.value"
                    style="{{ $selectStyle }}">
                <option value="ninguna">Sin cuota inicial</option>
                <option value="porcentaje">Porcentaje %</option>
                <option value="monto_fijo">Monto Fijo Bs</option>
            </select>
        </div>
        <div style="width:110px;" x-show="tipoCuotaIni !== 'ninguna'" x-cloak>
            <label style="{{ $labelStyle }}">Valor Inicial</label>
            <input wire:model="newValorCuotaInicial" x-on:input="valorCuotaIni = parseFloat($event.target.value) || 0"
                   type="number" step="0.01" min="0" placeholder="Ej: 20"
                   style="{{ $inputStyle }} text-align:center;">
        </div>
    </div>

    {{-- Badge resumen financiamiento --}}
    <div x-show="badgeResumen" class="flex items-center px-3 py-2 rounded-lg mb-4" style="background:#e6f4ef; border:1px solid #b7e4d1;">
        <span x-text="badgeResumen" style="font-size:10px; font-weight:600; color:#0F6E56;"></span>
    </div>

    {{-- Botones --}}
    <div class="flex gap-3">
        <button wire:click="saveNew"
                class="px-5 py-2 text-white font-semibold rounded-xl transition-colors hover:opacity-90"
                style="background:#7c3aed; font-size:13px;">Guardar</button>
        <button wire:click="cancelAdd"
                class="px-5 py-2 font-medium rounded-xl transition-colors hover:bg-gray-50"
                style="border:1px solid #d1d5db; color:#6b7280; font-size:13px;">Cancelar</button>
    </div>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Código</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Ciclo</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Cuotas</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">C. Inicial</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($maestras as $m)
                @if ($editingId === $m->id)
                <tr wire:key="m-edit-{{ $m->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                    <td class="px-4 py-2 font-mono text-xs text-lavanda-700 font-semibold">{{ $m->code ?? '—' }}</td>
                    <td class="px-4 py-2">
                        <input wire:model="editName" type="text"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                        @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2 hidden md:table-cell">
                        <select wire:model="editCycleId" class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                            <option value="">— Ciclo —</option>
                            @foreach ($cycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->code }}</option>
                            @endforeach
                        </select>
                        @error('editCycleId') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2 text-center hidden lg:table-cell">
                        <input wire:model="editCantidadCuotas" type="number" min="1" max="999" placeholder="—"
                               class="w-16 border border-lavanda-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none bg-white">
                    </td>
                    <td class="px-4 py-2 text-center hidden lg:table-cell">
                        <input type="checkbox" wire:model="editUsaCuotaInicial" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-2 text-center">
                        <input type="checkbox" wire:model="editActive" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="saveEdit" class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button wire:click="cancelEdit" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                {{-- Sub-fila: incremento + financiamiento --}}
                <tr class="bg-lavanda-50 border-l-2 border-lavanda-400">
                    <td colspan="7" class="px-4 pb-3 pt-0">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-lavanda-600 font-semibold">Incremento:</span>
                                <select wire:model="editTipoIncremento"
                                        class="border border-lavanda-300 rounded-lg px-2 py-1 text-xs bg-white focus:outline-none">
                                    <option value="">— Sin incremento —</option>
                                    <option value="porcentaje">Porcentaje %</option>
                                    <option value="monto_fijo">Monto Fijo Bs</option>
                                </select>
                                <input wire:model="editValorIncremento" type="number" step="0.01" min="0" placeholder="0"
                                       class="w-20 border border-lavanda-300 rounded-lg px-2 py-1 text-xs text-center bg-white focus:outline-none">
                                <span class="text-xs text-gray-400">{{ $editTipoIncremento === 'porcentaje' ? '%' : ($editTipoIncremento === 'monto_fijo' ? 'Bs' : '') }}</span>
                            </div>
                            <div class="flex items-center gap-2 pl-4 border-l border-lavanda-200">
                                <span class="text-xs text-lavanda-600 font-semibold">Días:</span>
                                <input wire:model="editDiasEntreCuotas" type="number" min="1" max="365" placeholder="30"
                                       class="w-16 border border-lavanda-300 rounded-lg px-2 py-1 text-xs text-center bg-white focus:outline-none">
                            </div>
                            <div class="flex items-center gap-2 pl-4 border-l border-lavanda-200">
                                <span class="text-xs text-lavanda-600 font-semibold">C. Inicial:</span>
                                <select wire:model="editTipoCuotaInicial"
                                        class="border border-lavanda-300 rounded-lg px-2 py-1 text-xs bg-white focus:outline-none">
                                    <option value="ninguna">Sin cuota inicial</option>
                                    <option value="porcentaje">Porcentaje %</option>
                                    <option value="monto_fijo">Monto Fijo Bs</option>
                                </select>
                                @if ($editTipoCuotaInicial !== 'ninguna')
                                <input wire:model="editValorCuotaInicial" type="number" step="0.01" min="0" placeholder="0"
                                       class="w-20 border border-lavanda-300 rounded-lg px-2 py-1 text-xs text-center bg-white focus:outline-none">
                                <span class="text-xs text-gray-400">{{ $editTipoCuotaInicial === 'porcentaje' ? '%' : 'Bs' }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @else
                <tr wire:key="m-{{ $m->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3.5 font-mono text-xs text-lavanda-700 font-semibold">{{ $m->code ?? '—' }}</td>
                    <td class="px-4 py-3.5 font-medium text-gray-800">{{ $m->name }}</td>
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        @if ($m->cycle)
                        <span class="text-xs font-mono text-gray-500">{{ $m->cycle->code }}</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center hidden lg:table-cell">
                        @if ($m->cantidad_cuotas)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-lavanda-100 text-lavanda-700">{{ $m->cantidad_cuotas }}c</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center hidden lg:table-cell">
                        @if ($m->usa_cuota_inicial)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-melocoton-100 text-melocoton-700">
                            {{ $m->tipo_cuota_inicial === 'porcentaje' ? number_format($m->valor_cuota_inicial, 0).'%' : 'Bs '.number_format($m->valor_cuota_inicial, 2) }}
                        </span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $m->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
                            {{ $m->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="startEdit({{ $m->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="viewItems({{ $m->id }})" title="Ver productos"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-celeste-600 hover:bg-celeste-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button wire:click="viewAcceso({{ $m->id }})" title="Gestionar acceso"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-melocoton-600 hover:bg-melocoton-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $m->id }})" title="{{ $m->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors {{ $m->active ? 'text-gray-400 hover:text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                @if ($m->active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="7" class="px-5 py-14 text-center text-gray-400 text-sm">No hay listas de precios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($maestras->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $maestras->links() }}</div>
    @endif
</div>
@endif

</div>
