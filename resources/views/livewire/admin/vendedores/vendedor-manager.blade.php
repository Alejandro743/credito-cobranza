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
{{-- ── FORM ──────────────────────────────────────────────────────────────── --}}
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h2 class="text-lg font-bold text-gray-800">{{ $editing ? 'Editar Vendedor' : 'Nuevo Vendedor' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

        {{-- Nombre / Apellido --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre *</label>
                <input wire:model="nombre" type="text"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-melocoton-400 focus:ring-2 focus:ring-melocoton-100 @error('nombre') border-red-300 @enderror">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Apellido *</label>
                <input wire:model="apellido" type="text"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-melocoton-400 focus:ring-2 focus:ring-melocoton-100 @error('apellido') border-red-300 @enderror">
                @error('apellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Teléfono / Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Teléfono</label>
                <input wire:model="telefono" type="text"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-melocoton-400 focus:ring-2 focus:ring-melocoton-100">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input wire:model="email" type="email"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-melocoton-400 focus:ring-2 focus:ring-melocoton-100 @error('email') border-red-300 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Grupo --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Grupo</label>
            <select wire:model="grupoId"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-melocoton-400">
                <option value="">Sin grupo</option>
                @foreach ($grupos as $g)
                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Acceso al sistema --}}
        <div class="border border-gray-100 rounded-xl p-4 space-y-4 bg-gray-50/50">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <div class="relative w-10 h-5 flex-shrink-0">
                    <input type="checkbox" wire:model.live="tieneAcceso" class="sr-only peer">
                    <div class="absolute inset-0 bg-gray-200 peer-checked:bg-celeste-400 rounded-full transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                </div>
                <div>
                    <span class="text-sm font-semibold {{ $tieneAcceso ? 'text-celeste-700' : 'text-gray-500' }}">¿Tiene acceso al sistema?</span>
                    <p class="text-xs text-gray-400">Permite que el vendedor inicie sesión</p>
                </div>
            </label>

            @if ($tieneAcceso)
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email de acceso *</label>
                    <input wire:model="userEmail" type="email"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-celeste-400 focus:ring-2 focus:ring-celeste-100 @error('userEmail') border-red-300 @enderror">
                    @error('userEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                        Contraseña {!! $userIdActual ? '<span class="font-normal text-gray-400">(vacío = no cambiar)</span>' : '*' !!}
                    </label>
                    <input wire:model="userPassword" type="password"
                           placeholder="{{ $userIdActual ? 'Dejar vacío para no cambiar' : 'Mínimo 6 caracteres' }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-celeste-400 focus:ring-2 focus:ring-celeste-100 @error('userPassword') border-red-300 @enderror">
                    @error('userPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rol *</label>
                    <select wire:model="userRol"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-celeste-400 @error('userRol') border-red-300 @enderror">
                        <option value="">Seleccionar rol...</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                    @error('userRol') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            @endif
        </div>

        {{-- Estado activo --}}
        <div>
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <div class="relative w-10 h-5 flex-shrink-0">
                    <input type="checkbox" wire:model.live="activo" class="sr-only peer">
                    <div class="absolute inset-0 bg-gray-200 peer-checked:bg-mint-500 rounded-full transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                </div>
                <span class="text-sm font-medium {{ $activo ? 'text-mint-600' : 'text-gray-400' }}">
                    {{ $activo ? 'Activo' : 'Inactivo' }}
                </span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" wire:click="backToList"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Cancelar
            </button>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-melocoton-500 hover:bg-melocoton-600 text-white transition-colors">
                {{ $editing ? 'Guardar cambios' : 'Crear vendedor' }}
            </button>
        </div>
    </form>
</div>

@else
{{-- ── LIST ──────────────────────────────────────────────────────────────── --}}

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar vendedor..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-300 bg-white">
    </div>
    <select wire:model.live="filtroGrupo"
            class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-300 bg-white">
        <option value="">Todos los grupos</option>
        @foreach ($grupos as $g)
            <option value="{{ $g->id }}">{{ $g->name }}</option>
        @endforeach
    </select>
    <select wire:model.live="filtroActivo"
            class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-melocoton-300 bg-white">
        <option value="">Todos</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
    <button wire:click="create"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-melocoton-500 hover:bg-melocoton-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Vendedor
    </button>
</div>

{{-- Tabla desktop --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Vendedores registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $vendedores->total() }}</span>
        </div>
        <button type="button" wire:click="$refresh"
                style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Actualizar
        </button>
    </div>

    <div style="overflow:auto; flex:1;">
    @php $sortCols = ['Vendedor'=>'apellido','Grupo'=>'grupo_id','Usuario'=>'user_id','Estado'=>'activo']; @endphp
    <table style="table-layout:fixed; width:100%; min-width:600px; border-collapse:collapse; font-size:13px;">
        <colgroup>
            <col style="width:44px;">
            <col style="width:240px;">
            <col style="width:150px;">
            <col style="width:160px;">
            <col style="width:90px;">
            <col style="width:90px;">
        </colgroup>
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                @foreach($sortCols as $label => $key)
                @php $isActive = $sortBy === $key; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="padding:10px 14px; text-align:{{ $label==='Estado' ? 'center' : 'left' }}; position:relative; user-select:none; overflow:hidden; min-width:70px; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                    <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                        {{ $label }}
                        @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                        @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                        @endif
                    </span>
                </th>
                @endforeach
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendedores as $v)
            <tr wire:key="v-{{ $v->id }}"
                style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; white-space:nowrap;">{{ $vendedores->firstItem() + $loop->index }}</td>

                <td style="padding:10px 14px; overflow:hidden;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($v->nombre, 0, 1) . substr($v->apellido, 0, 1)) }}</span>
                        </div>
                        <div style="min-width:0;">
                            <span style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($v->nombre_completo)) }}</span>
                            @if($v->email) <span style="font-size:12px; color:#6B7280; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $v->email }}</span> @endif
                        </div>
                    </div>
                </td>

                <td style="padding:10px 14px; overflow:hidden;">
                    @if($v->grupo)
                    <span style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($v->grupo->name)) }}</span>
                    @else
                    <span style="font-size:13px; color:#D1D5DB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 14px; overflow:hidden;">
                    <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $v->user?->name ? ucwords(strtolower($v->user->name)) : '—' }}</span>
                </td>

                <td style="padding:10px 14px; text-align:center;">
                    <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                 background:{{ $v->activo ? '#D1FAE5' : '#F3F4F6' }};
                                 color:{{ $v->activo ? '#059669' : '#9CA3AF' }};">
                        {{ $v->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>

                <td style="padding:10px 16px; text-align:center;">
                    <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                        <button wire:click="edit({{ $v->id }})" title="Editar"
                                style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay vendedores registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if ($vendedores->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $vendedores->links() }}</div>
    @endif
</div>

{{-- Mobile cards (unchanged) --}}
<div class="sm:hidden">
    @forelse ($vendedores as $v)
    <div wire:key="mv-{{ $v->id }}" class="bg-white rounded-2xl border border-gray-100 mb-3 shadow-sm overflow-hidden">
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($v->nombre, 0, 1) . substr($v->apellido, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $v->nombre_completo }}</p>
                @if($v->email)<p style="font-size:12px; color:#6B7280; margin:2px 0 0;">{{ $v->email }}</p>@endif
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $v->activo ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $v->activo ? '#059669' : '#9CA3AF' }};">
                {{ $v->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div style="padding:10px 14px; display:flex; gap:7px;">
            <button wire:click="edit({{ $v->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay vendedores registrados.</p>
    @endforelse
    @if ($vendedores->hasPages())
    <div style="padding-top:8px;">{{ $vendedores->links() }}</div>
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
