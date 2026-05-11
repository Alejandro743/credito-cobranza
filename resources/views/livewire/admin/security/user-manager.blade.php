<div>

@php
    $theadClass = match($moduleColor ?? '') {
        'lavanda'   => 'bg-lavanda-100 text-lavanda-700',
        'mint'      => 'bg-mint-100 text-mint-700',
        'melocoton' => 'bg-melocoton-100 text-melocoton-700',
        'celeste'   => 'bg-celeste-100 text-celeste-700',
        default     => 'bg-gray-50 text-gray-600',
    };
    $avatarColor = fn($tipo) => match($tipo ?? 'administrativo') {
        'vendedor' => ['bg-melocoton-100', 'text-melocoton-700'],
        'cliente'  => ['bg-celeste-100',   'text-celeste-700'],
        default    => ['bg-lavanda-100',   'text-lavanda-700'],
    };
    $tipoClass = fn($tipo) => match($tipo ?? 'administrativo') {
        'vendedor' => 'bg-melocoton-100 text-melocoton-700',
        'cliente'  => 'bg-celeste-100 text-celeste-700',
        default    => 'bg-lavanda-100 text-lavanda-700',
    };
@endphp

{{-- ── Flash ───────────────────────────────────────────────────────────────── --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50 bg-emerald-500 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    {{ session('success') }}
</div>
@endif

{{-- ── Modal: Cambio de contraseña ─────────────────────────────────────────── --}}
@if ($showPasswordModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div wire:click="closePasswordModal" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-lavanda-50 border-b border-lavanda-100 px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Cambio de contraseña</h2>
                    <div class="mt-2 space-y-0.5">
                        <p class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-700">Usuario:</span>
                            <span class="font-mono text-lavanda-700 ml-1">{{ $passwordModalUsuario }}</span>
                        </p>
                        <p class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-700">Nombre:</span>
                            <span class="ml-1">{{ $passwordModalNombre }}</span>
                        </p>
                    </div>
                </div>
                <button wire:click="closePasswordModal"
                        class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nueva contraseña</label>
            <div x-data="{ show: false }" class="relative">
                <input wire:model="passwordModalNew"
                       :type="show ? 'text' : 'password'"
                       placeholder="Mínimo 6 caracteres"
                       autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm pr-11 focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100 @error('passwordModalNew') border-red-300 @enderror">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('passwordModalNew')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
            <p class="text-gray-400 text-xs mt-2">La nueva contraseña reemplaza inmediatamente a la anterior.</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <button wire:click="closePasswordModal"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Cancelar
            </button>
            <button wire:click="savePasswordModal"
                    class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white transition-colors">
                Guardar contraseña
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── Toolbar ──────────────────────────────────────────────────────────────── --}}
<div class="mb-5">
    {{-- Mobile: botón primero --}}
    <div class="sm:hidden mb-3">
        <button wire:click="showAdd"
                class="w-full flex items-center justify-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-3 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            + Nuevo Usuario
        </button>
    </div>

    {{-- Desktop: todo en una fila --}}
    <div class="hidden sm:flex gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o usuario..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
        </div>
        <select wire:model.live="filterTipo" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los tipos</option>
            <option value="administrativo">Administrativo</option>
            <option value="vendedor">Vendedor</option>
            <option value="cliente">Cliente</option>
        </select>
        <select wire:model.live="filterRole" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los estados</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
        <button wire:click="showAdd"
                class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Usuario
        </button>
    </div>

    {{-- Mobile: search + filtros --}}
    <div class="sm:hidden space-y-2.5">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o usuario..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
        </div>
        <select wire:model.live="filterTipo" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los tipos</option>
            <option value="administrativo">Administrativo</option>
            <option value="vendedor">Vendedor</option>
            <option value="cliente">Cliente</option>
        </select>
        <select wire:model.live="filterRole" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            <option value="">Todos los estados</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
    </div>
</div>

{{-- ── Formulario: Nuevo usuario ────────────────────────────────────────────── --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <h3 class="text-sm font-bold text-lavanda-700 mb-4">Nuevo Usuario</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre completo *</label>
            <input wire:model="newName" type="text" placeholder="Ej. Alejandro León"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Usuario *</label>
            <input wire:model="newUsuario" type="text" placeholder="Ej. alejandro.leon"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white font-mono">
            @error('newUsuario') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Contraseña *</label>
            <input wire:model="newPassword" type="password" placeholder="Mín. 6 caracteres"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
            @error('newPassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo *</label>
            <select wire:model="newTipo" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                <option value="administrativo">Administrativo</option>
                <option value="vendedor">Vendedor</option>
                <option value="cliente">Cliente</option>
            </select>
            @error('newTipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Rol *</label>
            <select wire:model="newRole" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                <option value="">— Seleccionar —</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            @error('newRole') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-end pb-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="newActive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 focus:ring-lavanda-400">
                <span class="text-sm font-medium text-gray-700">Activo</span>
            </label>
        </div>
    </div>
    <div class="flex gap-3">
        <button wire:click="saveNew" class="px-5 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">Guardar</button>
        <button wire:click="cancelAdd" class="px-5 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">Cancelar</button>
    </div>
</div>
@endif

{{-- ── Mobile: edit form inline ─────────────────────────────────────────────── --}}
@if ($editingId && request()->header('sec-ch-ua-mobile') !== null)
@php $eu = $users->firstWhere('id', $editingId); @endphp
@endif

{{-- ── MOBILE: Cards ────────────────────────────────────────────────────────── --}}
<div class="sm:hidden space-y-3">
    @forelse ($users as $user)
    @php
        [$avBg, $avText] = $avatarColor($user->tipo ?? 'administrativo');
        $roleName = $user->getRoleNames()->first() ?? '—';
    @endphp

    @if ($editingId === $user->id)
    {{-- Card en modo edición --}}
    <div wire:key="card-edit-{{ $user->id }}" class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4">
        <p class="text-xs font-bold text-lavanda-700 mb-3">Editando usuario</p>
        <div class="space-y-2.5 mb-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre completo</label>
                <input wire:model="editName" type="text"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Usuario</label>
                <input wire:model="editUsuario" type="text"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white font-mono">
                @error('editUsuario') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                    <select wire:model="editTipo" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                        <option value="administrativo">Administrativo</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="cliente">Cliente</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Rol</label>
                    <select wire:model="editRole" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
                        <option value="">— Rol —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="editActive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 focus:ring-lavanda-400">
                <span class="text-sm font-medium text-gray-700">Activo</span>
            </label>
        </div>
        <div class="flex gap-2">
            <button wire:click="saveEdit" class="flex-1 py-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold rounded-xl transition-colors">Guardar</button>
            <button wire:click="cancelEdit" class="flex-1 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-xl transition-colors">Cancelar</button>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $user->id }}" class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full {{ $avBg }} {{ $avText }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ $user->name }}</p>
                <p class="text-xs text-gray-400 font-mono truncate">{{ $user->email }}</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium flex-shrink-0
                         {{ $user->active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                {{ $user->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <div class="flex items-center gap-2 mb-3 flex-wrap">
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoClass($user->tipo ?? 'administrativo') }}">
                {{ ucfirst($user->tipo ?? 'administrativo') }}
            </span>
            <span class="text-xs text-gray-400">Rol: <span class="font-medium text-gray-600 capitalize">{{ $roleName }}</span></span>
        </div>
        <div class="flex gap-2">
            <button wire:click="openPasswordModal({{ $user->id }})"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver
            </button>
            <button wire:click="startEdit({{ $user->id }})"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium bg-lavanda-50 hover:bg-lavanda-100 text-lavanda-600 border border-lavanda-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
            <button wire:click="toggleActive({{ $user->id }})"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium transition-colors border
                           {{ $user->active
                               ? 'bg-red-50 hover:bg-red-100 text-red-500 border-red-100'
                               : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border-emerald-100' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/>
                </svg>
                {{ $user->active ? 'Desactivar' : 'Activar' }}
            </button>
        </div>
    </div>
    @endif

    @empty
    <p class="py-12 text-center text-sm text-gray-400">No hay usuarios registrados.</p>
    @endforelse

    @if ($users->hasPages())
    <div class="pt-2">{{ $users->links() }}</div>
    @endif
</div>

{{-- ── DESKTOP: Tabla ───────────────────────────────────────────────────────── --}}
<div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @if ($users->isEmpty())
        <div class="py-16 text-center text-gray-400 text-sm">No hay usuarios registrados.</div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Nombre</th>
                    <th class="px-5 py-3 text-left">Acceso</th>
                    <th class="px-5 py-3 text-center">Tipo</th>
                    <th class="px-5 py-3 text-center">Rol</th>
                    <th class="px-5 py-3 text-center">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($users as $user)
                @php
                    [$avBg, $avText] = $avatarColor($user->tipo ?? 'administrativo');
                    $roleName = $user->getRoleNames()->first() ?? '—';
                @endphp

                {{-- ── Fila edición inline ─────────────────────────────────── --}}
                @if ($editingId === $user->id)
                <tr wire:key="edit-{{ $user->id }}" class="bg-lavanda-50/60">
                    <td class="px-3 py-2.5" colspan="2">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <input wire:model="editName" type="text" placeholder="Nombre completo"
                                       class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                                @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input wire:model="editUsuario" type="text" placeholder="usuario"
                                       class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white font-mono">
                                @error('editUsuario') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-2.5">
                        <select wire:model="editTipo" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                            <option value="administrativo">Administrativo</option>
                            <option value="vendedor">Vendedor</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </td>
                    <td class="px-3 py-2.5">
                        <select wire:model="editRole" class="w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                            <option value="">— Rol —</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-3 py-2.5 text-center">
                        <input wire:model="editActive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    </td>
                    <td class="px-3 py-2.5 text-right">
                        <div class="flex gap-1 justify-end">
                            <button wire:click="saveEdit"
                                    class="px-3 py-1.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    class="px-3 py-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-medium rounded-lg transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- ── Fila normal ──────────────────────────────────────────── --}}
                @else
                <tr wire:key="u-{{ $user->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $avBg }} {{ $avText }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-mono text-xs text-gray-500">{{ $user->email }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoClass($user->tipo ?? 'administrativo') }}">
                            {{ ucfirst($user->tipo ?? 'administrativo') }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                            @switch($roleName)
                                @case('admin')    bg-lavanda-100 text-lavanda-700    @break
                                @case('vendedor') bg-melocoton-100 text-melocoton-700 @break
                                @case('cliente')  bg-celeste-100 text-celeste-700    @break
                                @default          bg-gray-100 text-gray-600
                            @endswitch">
                            {{ $roleName }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                     {{ $user->active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="inline-flex items-center gap-1">
                            <button wire:click="openPasswordModal({{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-50 hover:bg-gray-100 text-gray-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver
                            </button>
                            <button wire:click="startEdit({{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-lavanda-50 hover:bg-lavanda-100 text-lavanda-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </button>
                            <button wire:click="toggleActive({{ $user->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
                                           {{ $user->active
                                               ? 'bg-red-50 hover:bg-red-100 text-red-500'
                                               : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-600' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/>
                                </svg>
                                {{ $user->active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>

</div>
