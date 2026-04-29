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

@if ($mode === 'permissions')
{{-- ── ÁRBOL DE PERMISOS ──────────────────────────────────────────────────── --}}
<div class="max-w-lg mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <button wire:click="backToList" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800">Accesos del rol</h2>
            <p class="text-xs text-lavanda-600 font-semibold capitalize">{{ $permissionsRoleName }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-4 px-1">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Todo:</span>
        <button wire:click="toggleTodos(true)" class="px-3 py-1 text-xs font-semibold rounded-lg bg-lavanda-100 hover:bg-lavanda-200 text-lavanda-700 transition-colors">Dar acceso a todo</button>
        <button wire:click="toggleTodos(false)" class="px-3 py-1 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">Quitar todo</button>
    </div>

    <div class="space-y-3 mb-6">
        @forelse ($modulosArbol as $modulo)
        @php
            $colores = [
                'lavanda'   => ['head_bg'=>'bg-lavanda-50','head_border'=>'border-lavanda-100','dot'=>'bg-lavanda-300','btn_all'=>'bg-lavanda-200 hover:bg-lavanda-300 text-lavanda-800'],
                'mint'      => ['head_bg'=>'bg-mint-50','head_border'=>'border-mint-100','dot'=>'bg-mint-300','btn_all'=>'bg-mint-200 hover:bg-mint-300 text-mint-800'],
                'melocoton' => ['head_bg'=>'bg-melocoton-50','head_border'=>'border-melocoton-100','dot'=>'bg-melocoton-300','btn_all'=>'bg-melocoton-200 hover:bg-melocoton-300 text-melocoton-800'],
                'celeste'   => ['head_bg'=>'bg-celeste-50','head_border'=>'border-celeste-100','dot'=>'bg-celeste-300','btn_all'=>'bg-celeste-200 hover:bg-celeste-300 text-celeste-800'],
            ];
            $cc = $colores[$modulo->color] ?? $colores['lavanda'];
        @endphp
        <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm" wire:key="m-{{ $modulo->id }}">
            <div class="flex items-center justify-between px-4 py-2.5 {{ $cc['head_bg'] }} border-b {{ $cc['head_border'] }}">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $modulo->icon }}"/></svg>
                    <span class="font-bold text-sm text-gray-800">{{ $modulo->name }}</span>
                </div>
                <div class="flex gap-1.5">
                    <button wire:click="toggleModulo({{ $modulo->id }}, true)" class="text-xs px-2.5 py-1 rounded-lg {{ $cc['btn_all'] }} font-bold transition-colors">Todos</button>
                    <button wire:click="toggleModulo({{ $modulo->id }}, false)" class="text-xs px-2.5 py-1 rounded-lg bg-white hover:bg-gray-100 text-gray-500 font-bold border border-gray-200 transition-colors">Ninguno</button>
                </div>
            </div>
            @foreach ($modulo->submodulos as $sub)
                @if ($sub->isGroup())
                <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $sub->name }}</span>
                </div>
                @foreach ($sub->children as $leaf)
                @php $key = (string) $leaf->id; @endphp
                <div class="flex items-center justify-between px-4 py-2.5 pl-8 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 transition-colors" wire:key="s-{{ $leaf->id }}">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cc['dot'] }} flex-shrink-0"></span>
                        {{ $leaf->name }}
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer select-none group">
                        <span class="text-xs font-medium transition-colors {{ ($permissions[$key]['puede_ver'] ?? false) ? 'text-lavanda-600' : 'text-gray-300 group-hover:text-gray-400' }}">
                            {{ ($permissions[$key]['puede_ver'] ?? false) ? 'Con acceso' : 'Sin acceso' }}
                        </span>
                        <div class="relative w-10 h-5 flex-shrink-0">
                            <input type="checkbox" wire:model.live="permissions.{{ $key }}.puede_ver" class="sr-only peer">
                            <div class="absolute inset-0 bg-gray-200 peer-checked:bg-lavanda-500 rounded-full transition-colors duration-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
                @endforeach
                @else
                @php $key = (string) $sub->id; @endphp
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 transition-colors" wire:key="s-{{ $sub->id }}">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cc['dot'] }} flex-shrink-0"></span>
                        {{ $sub->name }}
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer select-none group">
                        <span class="text-xs font-medium transition-colors {{ ($permissions[$key]['puede_ver'] ?? false) ? 'text-lavanda-600' : 'text-gray-300 group-hover:text-gray-400' }}">
                            {{ ($permissions[$key]['puede_ver'] ?? false) ? 'Con acceso' : 'Sin acceso' }}
                        </span>
                        <div class="relative w-10 h-5 flex-shrink-0">
                            <input type="checkbox" wire:model.live="permissions.{{ $key }}.puede_ver" class="sr-only peer">
                            <div class="absolute inset-0 bg-gray-200 peer-checked:bg-lavanda-500 rounded-full transition-colors duration-200"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
                @endif
            @endforeach
        </div>
        @empty
        <div class="text-center py-10 text-gray-400 text-sm">No hay módulos en BD.</div>
        @endforelse
    </div>

    <div class="flex items-center justify-between">
        <p class="text-xs text-gray-400">Solo submódulos con acceso activo serán visibles en el menú.</p>
        <div class="flex gap-3">
            <button wire:click="backToList" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">Cancelar</button>
            <button wire:click="savePermissions" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl shadow-sm transition-colors disabled:opacity-60">
                <span wire:loading.remove wire:target="savePermissions">Guardar permisos</span>
                <span wire:loading wire:target="savePermissions">Guardando...</span>
            </button>
        </div>
    </div>
</div>

@else
{{-- ── LIST ──────────────────────────────────────────────────────────────── --}}

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar rol..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 bg-white">
    </div>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Rol
    </button>
</div>

{{-- Inline add form --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-lavanda-700 mb-4">Nuevo Rol</h3>
    <div class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre *</label>
            <input wire:model="newRoleName" type="text" placeholder="ej: ejecutivo"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newRoleName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center gap-2 pb-2">
            <input wire:model="newActivo" type="checkbox" id="newActivo" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 focus:ring-lavanda-400 cursor-pointer">
            <label for="newActivo" class="text-sm font-medium text-gray-700 cursor-pointer">Activo</label>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveNew" class="px-5 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">Guardar</button>
            <button wire:click="cancelAdd" class="px-5 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">Cancelar</button>
        </div>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Rol</th>
                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Usuarios</th>
                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse ($roles as $role)
            @if ($editingId === $role->id)
            {{-- FILA EDICIÓN INLINE --}}
            <tr wire:key="role-edit-{{ $role->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                <td class="px-3 py-2">
                    @if ($role->name === 'admin')
                        <span class="font-medium text-gray-500 text-sm capitalize">admin</span>
                    @else
                        <input wire:model="editRoleName" type="text"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                        @error('editRoleName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    @endif
                </td>
                <td class="px-3 py-2 text-center text-gray-400 text-xs">{{ $role->users_count }}</td>
                <td class="px-3 py-2 text-center">
                    @if ($role->name === 'admin')
                        <span class="text-xs text-lavanda-600 font-medium">Siempre activo</span>
                    @else
                        <input type="checkbox" wire:model="editActivo" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    @endif
                </td>
                <td class="px-3 py-2 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <button wire:click="saveEdit" title="Guardar"
                                class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                        <button wire:click="cancelEdit" title="Cancelar"
                                class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @else
            {{-- FILA NORMAL --}}
            <tr wire:key="role-{{ $role->id }}" class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-lavanda-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-lavanda-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="font-medium text-gray-800 capitalize">{{ $role->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3.5 text-center">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">{{ $role->users_count }}</span>
                </td>
                <td class="px-4 py-3.5 text-center">
                    @if ($role->name === 'admin')
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-lavanda-100 text-lavanda-700">Siempre activo</span>
                    @elseif ($role->activo ?? true)
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-mint-100 text-mint-700">Activo</span>
                    @else
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Inactivo</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <button wire:click="openPermissions({{ $role->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-lavanda-50 hover:bg-lavanda-100 text-lavanda-600 text-xs font-medium transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Permisos
                        </button>
                        <button wire:click="startEdit({{ $role->id }})" title="Editar"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @if ($role->name !== 'admin')
                        <button wire:click="toggleActivo({{ $role->id }})" title="{{ ($role->activo ?? true) ? 'Desactivar' : 'Activar' }}"
                                class="p-1.5 rounded-lg transition-colors {{ ($role->activo ?? true) ? 'text-gray-400 hover:text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                            @if ($role->activo ?? true)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @endif
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No hay roles registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if ($roles->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $roles->links() }}</div>
    @endif
</div>
@endif

@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     class="fixed bottom-5 right-5 z-50 bg-red-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50 bg-mint-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

</div>
