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

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-4 py-3 font-semibold">Vendedor</th>
                <th class="text-left px-4 py-3 font-semibold hidden sm:table-cell">Grupo</th>
                <th class="text-left px-4 py-3 font-semibold hidden md:table-cell">Usuario</th>
                <th class="text-center px-4 py-3 font-semibold">Estado</th>
                <th class="text-right px-4 py-3 font-semibold">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($vendedores as $v)
            <tr class="hover:bg-melocoton-50/20 transition-colors" wire:key="v-{{ $v->id }}">
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-melocoton-100 flex items-center justify-center text-melocoton-700 font-bold text-xs flex-shrink-0">
                            {{ strtoupper(substr($v->nombre, 0, 1) . substr($v->apellido, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $v->nombre_completo }}</p>
                            @if ($v->email) <p class="text-xs text-gray-400">{{ $v->email }}</p> @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3.5 hidden sm:table-cell">
                    @if ($v->grupo)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-melocoton-50 text-melocoton-700 text-xs font-medium">
                            {{ $v->grupo->name }}
                        </span>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 hidden md:table-cell text-xs text-gray-500">
                    {{ $v->user?->name ?? '—' }}
                </td>
                <td class="px-4 py-3.5 text-center">
                    <button wire:click="toggleActivo({{ $v->id }})"
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                   {{ $v->activo ? 'bg-mint-100 text-mint-700 hover:bg-mint-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                        {{ $v->activo ? 'Activo' : 'Inactivo' }}
                    </button>
                </td>
                <td class="px-4 py-3.5 text-right">
                    <button wire:click="edit({{ $v->id }})"
                            class="p-1.5 rounded-lg hover:bg-celeste-50 text-celeste-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    No hay vendedores registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if ($vendedores->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $vendedores->links() }}</div>
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
