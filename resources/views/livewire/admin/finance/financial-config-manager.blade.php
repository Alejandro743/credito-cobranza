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
    {{-- Flash message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
         class="mb-4 flex items-center gap-2 px-4 py-3 bg-mint-50 border border-mint-200 rounded-xl text-mint-700 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar plan financiero..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 bg-white">
        </div>
        <button wire:click="openCreate"
                class="flex items-center gap-2 px-4 py-2.5 bg-celeste-500 hover:bg-celeste-600 text-white rounded-xl text-sm font-medium transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Plan
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Nombre</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden md:table-cell">Tasa</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden md:table-cell">Plazo</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden lg:table-cell">Montos</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide hidden lg:table-cell">Condiciones</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Estado</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($plans as $plan)
                <tr wire:key="plan-{{ $plan->id }}" class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3.5">
                        <p class="font-medium text-gray-800">{{ $plan->name }}</p>
                        @if($plan->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($plan->description, 50) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-celeste-100 text-celeste-700">
                            {{ number_format($plan->interest_rate, 2) }}%
                        </span>
                    </td>
                    <td class="px-4 py-3.5 hidden md:table-cell">
                        <span class="text-sm text-gray-600">{{ $plan->term_months }} meses</span>
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <div class="text-xs text-gray-500">
                            <span class="text-mint-600">${{ number_format($plan->min_amount, 0) }}</span>
                            <span class="text-gray-300 mx-1">—</span>
                            <span>{{ $plan->max_amount ? '$'.number_format($plan->max_amount, 0) : '∞' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 hidden lg:table-cell">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-lavanda-100 text-lavanda-700">
                            {{ $plan->conditions_count }} condición{{ $plan->conditions_count !== 1 ? 'es' : '' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $plan->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $plan->active ? 'bg-mint-500' : 'bg-gray-400' }}"></span>
                            {{ $plan->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="openConditions({{ $plan->id }})"
                                    class="p-1.5 rounded-lg hover:bg-lavanda-50 text-lavanda-500 transition-colors" title="Condiciones">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            <button wire:click="openEdit({{ $plan->id }})"
                                    class="p-1.5 rounded-lg hover:bg-celeste-50 text-celeste-500 transition-colors" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $plan->id }})"
                                    class="p-1.5 rounded-lg hover:bg-melocoton-50 text-melocoton-500 transition-colors" title="Activar/Desactivar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        No se encontraron planes financieros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $plans->links() }}</div>

    {{-- Modal crear/editar plan --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ $editing ? 'Editar Plan' : 'Nuevo Plan Financiero' }}</h3>
                <button wire:click="$set('showModal', false)" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                    <input wire:model="planName" type="text" placeholder="Ej: Plan Estándar 12 meses"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('planName') border-red-300 @enderror">
                    @error('planName') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tasa de interés (%) <span class="text-red-400">*</span></label>
                        <input wire:model="interest_rate" type="number" step="0.0001" min="0" placeholder="0.0000"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('interest_rate') border-red-300 @enderror">
                        @error('interest_rate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Plazo (meses) <span class="text-red-400">*</span></label>
                        <input wire:model="term_months" type="number" min="1" placeholder="12"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('term_months') border-red-300 @enderror">
                        @error('term_months') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto mínimo <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input wire:model="min_amount" type="number" step="0.01" min="0" placeholder="0.00"
                                   class="w-full pl-7 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('min_amount') border-red-300 @enderror">
                        </div>
                        @error('min_amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Monto máximo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input wire:model="max_amount" type="number" step="0.01" min="0" placeholder="Sin límite"
                                   class="w-full pl-7 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descripción</label>
                    <textarea wire:model="description" rows="2" placeholder="Descripción del plan..."
                              class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 resize-none"></textarea>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" wire:model="active" class="w-4 h-4 rounded text-celeste-500 border-gray-300">
                    <span class="text-sm text-gray-700">Plan activo</span>
                </label>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 rounded-xl hover:bg-gray-100 transition-colors">Cancelar</button>
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                        class="px-5 py-2 bg-celeste-500 hover:bg-celeste-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">{{ $editing ? 'Actualizar' : 'Crear Plan' }}</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal condiciones --}}
    @if($showCondModal && $viewingPlan)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('showCondModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="font-semibold text-gray-800">Condiciones: {{ $viewingPlan->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $viewingPlan->conditions->count() }} condición(es)</p>
                </div>
                <button wire:click="$set('showCondModal', false)" class="p-1 rounded-lg hover:bg-gray-100 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Add condition form --}}
            <div class="px-6 py-4 bg-celeste-50 border-b border-celeste-100">
                <p class="text-xs font-semibold text-celeste-700 mb-3 uppercase tracking-wide">Agregar Condición</p>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <input wire:model="condType" type="text" placeholder="Tipo (ej: score)"
                               class="w-full px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('condType') border-red-300 @enderror">
                        @error('condType') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <select wire:model="condOperator"
                                class="w-full px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 bg-white">
                            <option value="=">=</option>
                            <option value="!=">!=</option>
                            <option value=">">&gt;</option>
                            <option value=">=">&gt;=</option>
                            <option value="<">&lt;</option>
                            <option value="<=">&lt;=</option>
                        </select>
                    </div>
                    <div>
                        <input wire:model="condValue" type="text" placeholder="Valor"
                               class="w-full px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300 @error('condValue') border-red-300 @enderror">
                        @error('condValue') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <input wire:model="condDescription" type="text" placeholder="Descripción (opcional)"
                       class="w-full mt-2 px-3 py-2 border border-celeste-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-celeste-300">
                <button wire:click="addCondition" wire:loading.attr="disabled" wire:target="addCondition"
                        class="mt-3 px-4 py-2 bg-celeste-500 hover:bg-celeste-600 text-white text-sm font-medium rounded-xl transition-colors">
                    Agregar
                </button>
            </div>

            {{-- Conditions list --}}
            <div class="divide-y divide-gray-50">
                @forelse($viewingPlan->conditions as $cond)
                <div wire:key="cond-{{ $cond->id }}" class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-gray-100 text-gray-700">{{ $cond->condition_type }}</span>
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-mono bg-celeste-100 text-celeste-700">{{ $cond->operator }}</span>
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-mint-100 text-mint-700">{{ $cond->value }}</span>
                        @if($cond->description)
                        <span class="text-xs text-gray-400">{{ $cond->description }}</span>
                        @endif
                    </div>
                    <button wire:click="removeCondition({{ $cond->id }})"
                            class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Sin condiciones definidas</div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
