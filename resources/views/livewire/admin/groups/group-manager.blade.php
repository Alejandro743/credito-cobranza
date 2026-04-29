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

{{-- ═══════════════════════════════════════════════ DETAIL MODE ══ --}}
@if ($mode === 'detail' && $viewingGroup)
<div>

    {{-- Cabecera --}}
    <div class="flex items-start gap-3 mb-6">
        <button wire:click="backToList" class="mt-1 p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-lg font-bold text-gray-800">{{ $viewingGroup->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $viewingGroup->type === 'clientes' ? 'bg-celeste-100 text-celeste-700' : 'bg-melocoton-100 text-melocoton-700' }}">
                    {{ $viewingGroup->type === 'clientes' ? 'Clientes' : 'Vendedores' }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $viewingGroup->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $viewingGroup->active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            @if ($viewingGroup->description)
            <p class="text-xs text-gray-500 mt-0.5">{{ $viewingGroup->description }}</p>
            @endif
        </div>
    </div>

    {{-- ══ SECCIÓN A: MIEMBROS ══ --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                A. Miembros
                <span class="ml-2 text-xs font-normal text-gray-400 normal-case tracking-normal">({{ count($allMembers) }} total)</span>
            </h3>
            <button wire:click="toggleAddMember"
                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl transition-colors
                    {{ $showAddMemberForm ? 'bg-gray-100 text-gray-600' : 'bg-lavanda-500 hover:bg-lavanda-600 text-white' }}">
                @if ($showAddMemberForm)
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancelar
                @else
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Agregar Miembro
                @endif
            </button>
        </div>

        {{-- Formulario inline agregar miembro --}}
        @if ($showAddMemberForm)
        <div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4 mb-4">
            <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">
                Agregar {{ $viewingGroup->type === 'clientes' ? 'Cliente' : 'Vendedor' }}
            </p>
            <div class="flex gap-2">
                <div class="flex-1">
                    <select wire:model="addMemberUserId"
                            class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                        <option value="">— Seleccionar usuario —</option>
                        @foreach ($availableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} · {{ $u->email }}</option>
                        @endforeach
                    </select>
                    @error('addMemberUserId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button wire:click="saveAddMember"
                        class="flex items-center gap-1.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Agregar
                </button>
            </div>
            @if ($availableUsers->isEmpty())
            <p class="text-xs text-gray-400 mt-2">No hay {{ $viewingGroup->type === 'clientes' ? 'clientes' : 'vendedores' }} disponibles para agregar.</p>
            @endif
        </div>
        @endif

        {{-- Filtros miembros --}}
        <div class="flex flex-col sm:flex-row gap-2 mb-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input wire:model.live.debounce.300ms="searchMiembro" type="text" placeholder="Buscar por nombre..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            </div>
            <select wire:model.live="filterOrigenMiembro"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-lavanda-400 bg-white">
                <option value="">Todos los orígenes</option>
                <option value="auto">Automático</option>
                <option value="manual">Manual</option>
            </select>
        </div>

        {{-- Tabla miembros --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Email</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Origen</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($allMembers as $m)
                        <tr wire:key="mbr-{{ $m['id'] }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $m['id'] }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $m['name'] }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500 hidden sm:table-cell">{{ $m['email'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                $tipoClr = match($m['tipo']) {
                                    'administrativo' => 'bg-lavanda-100 text-lavanda-700',
                                    'vendedor'       => 'bg-melocoton-100 text-melocoton-700',
                                    'cliente'        => 'bg-celeste-100 text-celeste-700',
                                    default          => 'bg-gray-100 text-gray-500',
                                };
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $tipoClr }}">
                                    {{ ucfirst($m['tipo']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($m['origen'] === 'auto')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Regla
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-lavanda-100 text-lavanda-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Manual
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($m['origen'] === 'manual')
                                <button wire:click="removeMember({{ $m['id'] }})"
                                        wire:confirm="¿Quitar a {{ $m['name'] }} del grupo?"
                                        title="Quitar del grupo"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @else
                                <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                                Este grupo no tiene miembros.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ SECCIÓN B: LISTAS ASIGNADAS ══ --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">
                B. Listas Asignadas
                <span class="ml-2 text-xs font-normal text-gray-400 normal-case tracking-normal">({{ count($assignedListas) }})</span>
            </h3>
            <button wire:click="toggleAddLista"
                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl transition-colors
                    {{ $showAddListaForm ? 'bg-gray-100 text-gray-600' : 'bg-lavanda-500 hover:bg-lavanda-600 text-white' }}">
                @if ($showAddListaForm)
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancelar
                @else
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Asignar Lista
                @endif
            </button>
        </div>

        {{-- Formulario inline asignar lista --}}
        @if ($showAddListaForm)
        <div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4 mb-4">
            <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Asignar Lista de Precios</p>
            <div class="flex gap-2">
                <div class="flex-1">
                    <select wire:model="addListaId"
                            class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
                        <option value="">— Seleccionar lista —</option>
                        @foreach ($availableListas as $lista)
                            <option value="{{ $lista->id }}">
                                {{ $lista->name }}
                                @if($lista->cycle) · {{ $lista->cycle->name }} @endif
                                · {{ ucfirst($lista->estado) }}
                            </option>
                        @endforeach
                    </select>
                    @error('addListaId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button wire:click="saveAddLista"
                        class="flex items-center gap-1.5 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Asignar
                </button>
            </div>
            @if ($availableListas->isEmpty())
            <p class="text-xs text-gray-400 mt-2">No hay listas disponibles para asignar.</p>
            @endif
        </div>
        @endif

        {{-- Tabla listas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre de la Lista</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Ciclo</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($assignedListas as $lista)
                        <tr wire:key="lst-{{ $lista->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $lista->name }}</td>
                            <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">{{ $lista->cycle?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $lista->estado === 'activa' ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($lista->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="removeLista({{ $lista->id }})"
                                        wire:confirm="¿Quitar esta lista del grupo?"
                                        title="Quitar lista"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-400 text-sm">
                                No hay listas asignadas a este grupo.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════ LIST MODE ══ --}}
@else

{{-- Barra superior --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar grupo..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterType" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los tipos</option>
        <option value="clientes">Clientes</option>
        <option value="vendedores">Vendedores</option>
    </select>
    <select wire:model.live="filterStatus" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Grupo
    </button>
</div>

{{-- Formulario inline agregar --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-4 mb-4">
    <p class="text-xs font-bold text-lavanda-700 uppercase tracking-wide mb-3">Nuevo Grupo</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <input wire:model="newName" type="text" placeholder="Nombre *"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
            @error('newName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <select wire:model="newType"
                    class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="clientes">Clientes</option>
                <option value="vendedores">Vendedores</option>
            </select>
        </div>
        <div class="lg:col-span-2">
            <input wire:model="newDescription" type="text" placeholder="Descripción (opcional)"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
        </div>
    </div>
    <div class="flex items-center gap-3 mt-3">
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" wire:model="newActive" class="w-4 h-4 rounded text-lavanda-500 border-gray-300">
            Activo
        </label>
        <div class="flex-1"></div>
        <button wire:click="cancelAdd" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors font-medium">Cancelar</button>
        <button wire:click="saveNew" class="px-5 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">Guardar</button>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Descripción</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($groups as $g)

                @if ($editingId === $g->id)
                {{-- FILA EN EDICIÓN --}}
                <tr wire:key="g-edit-{{ $g->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                    <td class="px-4 py-2">
                        <input wire:model="editName" type="text" placeholder="Nombre *"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 focus:ring-2 focus:ring-lavanda-100 bg-white">
                        @error('editName') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-4 py-2">
                        <select wire:model="editType"
                                class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                            <option value="clientes">Clientes</option>
                            <option value="vendedores">Vendedores</option>
                        </select>
                    </td>
                    <td class="px-4 py-2 hidden md:table-cell">
                        <input wire:model="editDescription" type="text" placeholder="Descripción"
                               class="w-full border border-lavanda-300 rounded-lg px-2.5 py-1.5 text-sm focus:outline-none focus:border-lavanda-500 bg-white">
                    </td>
                    <td class="px-4 py-2 text-center">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" wire:model="editActive" class="w-4 h-4 rounded text-lavanda-500">
                            <span class="text-xs text-gray-600">{{ $editActive ? 'Activo' : 'Inactivo' }}</span>
                        </label>
                    </td>
                    <td class="px-4 py-2 text-right">
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
                {{-- FILA EN LECTURA --}}
                <tr wire:key="g-{{ $g->id }}" class="hover:bg-gray-50 transition-colors {{ !$g->active ? 'opacity-60' : '' }}">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $g->name }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ $g->users_count + $g->miembros_manual_count }} miembros · {{ $g->listas_count }} listas
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $g->type === 'clientes' ? 'bg-celeste-100 text-celeste-700' : 'bg-melocoton-100 text-melocoton-700' }}">
                            {{ $g->type === 'clientes' ? 'Clientes' : 'Vendedores' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell">
                        {{ $g->description ? \Illuminate\Support\Str::limit($g->description, 60) : '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $g->active ? 'bg-mint-100 text-mint-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $g->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="viewDetail({{ $g->id }})" title="Ver detalle"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-celeste-600 hover:bg-celeste-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button wire:click="startEdit({{ $g->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $g->id }})"
                                    title="{{ $g->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors
                                    {{ $g->active ? 'text-gray-400 hover:text-melocoton-600 hover:bg-melocoton-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                @if ($g->active)
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
                <tr>
                    <td colspan="5" class="px-5 py-14 text-center text-gray-400 text-sm">
                        No hay grupos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($groups->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $groups->links() }}</div>
    @endif
</div>

@endif

</div>
