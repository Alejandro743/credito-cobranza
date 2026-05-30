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
@if ($mode === 'form')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Regla' : 'Nueva Regla' }}</h2>
    </div>
    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
                <input wire:model="name" type="text" placeholder="Regla para segmento norte"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tipo *</label>
                <select wire:model="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400">
                    <option value="segmento">Segmento</option>
                    <option value="geografica">Geográfica</option>
                    <option value="comercial">Comercial</option>
                    <option value="personalizado">Personalizado</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Prioridad</label>
                <input wire:model="priority" type="number" min="0" placeholder="0"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Condición</label>
            <textarea wire:model="condicion" rows="3" placeholder="ej: region = 'norte' AND tipo_cliente = 'A'"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 resize-none"></textarea>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Descripción</label>
            <textarea wire:model="description" rows="2" placeholder="Descripción interna opcional..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 resize-none"></textarea>
        </div>

        {{-- Grupos --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2">Grupos aplicables</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach ($groups as $g)
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" wire:model="selectedGroups" value="{{ $g->id }}"
                           class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
                    {{ $g->name }}
                </label>
                @endforeach
            </div>
        </div>

        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" wire:model="active" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
            <span class="text-sm text-gray-700">Regla activa</span>
        </label>
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" wire:click="backToList" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Cancelar</button>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white transition-colors">
                {{ $editing ? 'Guardar cambios' : 'Crear regla' }}
            </button>
        </div>
    </form>
</div>

@else
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-lavanda-50 border border-lavanda-100 rounded-2xl p-4 sm:col-span-2">
        <p class="text-xs text-lavanda-500 font-medium uppercase tracking-wide">Total reglas</p>
        <p class="text-3xl font-bold text-lavanda-700 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-mint-50 border border-mint-100 rounded-2xl p-4 sm:col-span-2">
        <p class="text-xs text-mint-500 font-medium uppercase tracking-wide">Activas</p>
        <p class="text-3xl font-bold text-mint-700 mt-1">{{ $stats['activas'] }}</p>
    </div>
</div>

<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar regla..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterType" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los tipos</option>
        <option value="segmento">Segmento</option>
        <option value="geografica">Geográfica</option>
        <option value="comercial">Comercial</option>
        <option value="personalizado">Personalizado</option>
    </select>
    <button wire:click="create" class="bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        + Nueva Regla
    </button>
</div>

@php
$sortColsR = ['Nombre'=>'name','Tipo'=>'type','Prioridad'=>'priority','Grupos'=>'groups_count'];
@endphp
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">
    <div style="overflow:auto; flex:1;">
    <table style="width:100%; min-width:600px; border-collapse:collapse; font-size:13px;">
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                @foreach($sortColsR as $label => $key)
                @php $isActive = $sortBy === $key; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="padding:10px 14px; text-align:{{ in_array($label,['Prioridad','Grupos']) ? 'center' : 'left' }}; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; cursor:pointer; user-select:none; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                    <span style="display:inline-flex; align-items:center; gap:5px;">{{ $label }}
                        @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                        @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                        @endif
                    </span>
                </th>
                @endforeach
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rules as $r)
            <tr wire:key="r-{{ $r->id }}"
                style="border-bottom:1px solid #F3F4F6; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
                <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $rules->firstItem() + $loop->index }}</td>
                <td style="padding:10px 14px; font-size:13px; font-weight:500; color:#111827; white-space:nowrap;">{{ ucwords(strtolower($r->name)) }}</td>
                <td style="padding:10px 14px;">
                    @php
                    $tipoStyle = match($r->type) {
                        'segmento'     => 'background:#EDE9FE;color:#7B6FE8;',
                        'geografica'   => 'background:#E0F2FE;color:#0369A1;',
                        'comercial'    => 'background:#D1FAE5;color:#059669;',
                        default        => 'background:#FEF3C7;color:#B45309;',
                    };
                    @endphp
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; {{ $tipoStyle }}">{{ ucfirst($r->type) }}</span>
                </td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#6B7280;">{{ $r->priority }}</td>
                <td style="padding:10px 14px; text-align:center; font-size:13px; color:#6B7280;">{{ $r->groups_count }}</td>
                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700;
                                 background:{{ $r->active ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $r->active ? '#059669' : '#9CA3AF' }};">
                        {{ $r->active ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td style="padding:10px 14px; text-align:center;">
                    <button wire:click="edit({{ $r->id }})"
                            style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F5F3FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto; -webkit-appearance:none; appearance:none;"
                            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F5F3FF'" title="Editar">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="padding:64px 24px; text-align:center; color:#9CA3AF; font-size:13px;">No hay reglas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($rules->hasPages())
    <div style="padding:10px 16px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $rules->links() }}</div>
    @endif
</div>

{{-- MOBILE --}}
<div class="sm:hidden">
    @forelse ($rules as $r)
    <div wire:key="mobile-r-{{ $r->id }}" class="bg-white rounded-2xl border border-gray-100 mb-3 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 p-3">
            <div style="width:30px;height:30px;border-radius:8px;background:#EDE9FE;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:12px;font-weight:700;color:#7B6FE8;">{{ strtoupper(substr($r->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-sm text-gray-800 truncate">{{ $r->name }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold shrink-0
                        @switch($r->type)
                            @case('segmento') bg-lavanda-100 text-lavanda-700 @break
                            @case('geografica') bg-celeste-100 text-celeste-700 @break
                            @case('comercial') bg-mint-100 text-mint-700 @break
                            @default bg-melocoton-100 text-melocoton-700
                        @endswitch">
                        {{ ucfirst($r->type) }}
                    </span>
                </div>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-xs text-gray-400">Prioridad {{ $r->priority }} · {{ $r->groups_count }} grupos</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $r->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $r->active ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 px-3 py-2">
            <button wire:click="edit({{ $r->id }})"
                    class="w-full h-8 bg-lavanda-50 text-lavanda-700 border border-lavanda-100 rounded-lg text-xs font-semibold">
                Editar
            </button>
        </div>
    </div>
    @empty
    <p class="text-center text-gray-400 text-sm py-12">No hay reglas registradas.</p>
    @endforelse
    @if ($rules->hasPages())
    <div class="mt-2">{{ $rules->links() }}</div>
    @endif
</div>
@endif

@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif
</div>
