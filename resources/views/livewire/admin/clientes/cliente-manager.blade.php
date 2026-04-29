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

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row gap-3 mb-5">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por ID, CI, nombre o apellido..."
               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lavanda-400 focus:ring-2 focus:ring-lavanda-100">
    </div>
    <select wire:model.live="filterCiudad" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todas las ciudades</option>
        @foreach ($ciudades as $ciudad)
        <option value="{{ $ciudad }}">{{ $ciudad }}</option>
        @endforeach
    </select>
    <select wire:model.live="filterActivo" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-lavanda-400 bg-white">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
    <button wire:click="openCorrelativo"
            class="flex items-center gap-2 border border-gray-200 text-gray-500 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Correlativo
    </button>
    <button wire:click="showAdd"
            class="flex items-center gap-2 bg-lavanda-500 hover:bg-lavanda-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Cliente
    </button>
</div>

{{-- Config correlativo --}}
@if ($showCorrelativo)
<div class="bg-celeste-50 border border-celeste-200 rounded-2xl p-5 mb-5">
    <p class="text-sm text-celeste-700 font-semibold mb-4">Configuración de Correlativo ID_LN</p>
    <div class="flex flex-wrap items-end gap-3">
        <div class="w-24">
            <p class="text-xs text-celeste-600 font-medium mb-1">Prefijo</p>
            <input wire:model="cfgPrefijo" type="text" maxlength="10" placeholder="LN"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-celeste-400 uppercase">
            @error('cfgPrefijo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="w-36">
            <p class="text-xs text-celeste-600 font-medium mb-1">Siguiente número</p>
            <input wire:model="cfgSiguienteNumero" type="number" min="1"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-celeste-400">
            @error('cfgSiguienteNumero') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="w-28">
            <p class="text-xs text-celeste-600 font-medium mb-1">Dígitos (longitud)</p>
            <input wire:model="cfgLongitud" type="number" min="1" max="10"
                   class="w-full border border-celeste-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-celeste-400">
            @error('cfgLongitud') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="text-xs text-celeste-600 pb-2">
            Ejemplo: <span class="font-mono font-bold">{{ strtoupper($cfgPrefijo ?: 'LN') }}{{ str_pad($cfgSiguienteNumero ?: 1, (int)($cfgLongitud ?: 6), '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex gap-2 pb-0.5">
            <button wire:click="cancelCorrelativo" class="px-4 py-2 text-sm text-gray-600 hover:bg-celeste-100 rounded-xl transition-colors">Cancelar</button>
            <button wire:click="saveCorrelativo" class="px-5 py-2 text-sm font-semibold bg-celeste-500 hover:bg-celeste-600 text-white rounded-xl transition-colors">Guardar</button>
        </div>
    </div>
</div>
@endif

{{-- Formulario nuevo cliente --}}
@if ($showAddForm)
<div class="bg-lavanda-50 border border-lavanda-200 rounded-2xl p-5 mb-5">
    <p class="text-sm text-lavanda-600 font-semibold mb-4">Nuevo Cliente</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-3">
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">CI *</p>
            <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
            @error('newCi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Nombre *</p>
            <input wire:model="newNombre" type="text" placeholder="Juan"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newNombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Apellido *</p>
            <input wire:model="newApellido" type="text" placeholder="Pérez"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newApellido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Teléfono * <span class="text-gray-400">(será contraseña)</span></p>
            <input wire:model="newTelefono" type="text" maxlength="20" placeholder="70012345"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
            @error('newTelefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Correo electrónico *</p>
            <input wire:model="newEmail" type="email" placeholder="cliente@email.com"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">NIT</p>
            <input wire:model="newNit" type="text" maxlength="30" placeholder="Opcional"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm font-mono focus:outline-none focus:border-lavanda-400">
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Ciudad *</p>
            <select wire:model.live="newCiudad" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">-- Seleccionar --</option>
                @foreach($ciudadesAll as $c)
                <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
            @error('newCiudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Provincia *</p>
            <select wire:model.live="newProvincia" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400" @disabled(!$newCiudad)>
                <option value="">-- Seleccionar --</option>
                @foreach($newProvincias as $p)
                <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                @endforeach
            </select>
            @error('newProvincia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Municipio *</p>
            <select wire:model.live="newMunicipio" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400" @disabled(!$newProvincia)>
                <option value="">-- Seleccionar --</option>
                @foreach($newMunicipios as $m)
                <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
            @error('newMunicipio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2 lg:col-span-2">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Dirección *</p>
            <input wire:model="newDireccion" type="text" placeholder="Av. Principal #123"
                   class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
            @error('newDireccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        @if (!$esVendedor)
        <div>
            <p class="text-xs text-lavanda-600 font-medium mb-1">Vendedor</p>
            <select wire:model="newVendedorId" class="w-full border border-lavanda-200 bg-white rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-lavanda-400">
                <option value="">— Sin vendedor —</option>
                @foreach ($vendedores as $v)
                <option value="{{ $v->id }}">{{ $v->name }} {{ $v->apellido }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex flex-col items-center justify-end pb-0.5">
            <p class="text-xs text-lavanda-600 font-medium mb-1">Activo</p>
            <input type="checkbox" wire:model="newActive" class="w-4 h-4 mt-1.5 rounded text-lavanda-500 border-gray-300 cursor-pointer">
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <button wire:click="cancelAdd" class="px-4 py-2 text-sm text-gray-600 hover:bg-lavanda-100 rounded-xl transition-colors">Cancelar</button>
        <button wire:click="saveNew" class="px-6 py-2 text-sm font-semibold bg-lavanda-500 hover:bg-lavanda-600 text-white rounded-xl transition-colors">Guardar</button>
    </div>
</div>
@endif

{{-- Tabla --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="{{ $theadClass }} text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID_LN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">CI</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Apellido</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Teléfono</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Ciudad</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($clientes as $c)
                @if ($editingId === $c->id)
                {{-- EDICIÓN INLINE --}}
                <tr wire:key="edit-{{ $c->id }}" class="bg-lavanda-50 border-l-2 border-lavanda-400">
                    <td class="px-3 py-2 font-mono text-xs text-gray-500">{{ $c->id_ln ?? '—' }}</td>
                    <td class="px-3 py-2 font-mono text-xs text-gray-500">{{ $c->ci }}</td>
                    <td class="px-3 py-2">
                        <input wire:model="editNombre" type="text"
                               class="w-full border border-lavanda-200 bg-white rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400">
                        @error('editNombre') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2">
                        <input wire:model="editApellido" type="text"
                               class="w-full border border-lavanda-200 bg-white rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400">
                        @error('editApellido') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </td>
                    <td class="px-3 py-2 hidden md:table-cell">
                        <input wire:model="editTelefono" type="text"
                               class="w-28 border border-lavanda-200 bg-white rounded-lg px-2 py-1.5 text-xs font-mono focus:outline-none focus:border-lavanda-400">
                    </td>
                    <td class="px-3 py-2 hidden lg:table-cell">
                        <select wire:model.live="editCiudad" class="w-28 border border-lavanda-200 bg-white rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:border-lavanda-400">
                            <option value="">--</option>
                            @foreach($ciudadesAll as $c)
                            <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-3 py-2 text-center">
                        <input type="checkbox" wire:model="editActive" class="w-4 h-4 mx-auto block rounded border-gray-300 text-lavanda-500 cursor-pointer">
                    </td>
                    <td class="px-3 py-2 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="saveEdit" class="p-1.5 rounded-lg bg-mint-100 text-mint-700 hover:bg-mint-200 transition-colors" title="Guardar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button wire:click="cancelEdit" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors" title="Cancelar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>

                @else
                {{-- FILA NORMAL --}}
                <tr wire:key="c-{{ $c->id }}" class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs font-semibold text-celeste-700">{{ $c->id_ln ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $c->ci }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $c->usuario->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $c->usuario->apellido ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 hidden md:table-cell font-mono text-xs">{{ $c->telefono }}</td>
                    <td class="px-4 py-3 text-gray-500 hidden lg:table-cell text-xs">{{ $c->ciudad }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $c->active ? 'bg-mint-100 text-mint-700' : 'bg-red-100 text-red-600' }}">
                            {{ $c->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-lavanda-600 hover:bg-lavanda-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="toggleActive({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    class="p-1.5 rounded-lg transition-colors {{ $c->active ? 'text-gray-400 hover:text-red-500 hover:bg-red-50' : 'text-gray-400 hover:text-mint-600 hover:bg-mint-50' }}">
                                @if ($c->active)
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
                <tr><td colspan="8" class="px-5 py-14 text-center text-gray-400 text-sm">No hay clientes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($clientes->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $clientes->links() }}</div>
    @endif
</div>

</div>
