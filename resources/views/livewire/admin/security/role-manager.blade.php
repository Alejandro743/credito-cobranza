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

{{-- ══ TOOLBAR ══ --}}
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <div class="relative w-full sm:flex-1" style="min-width:0;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar rol..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Rol
    </button>
</div>

{{-- ══ FORM: Nuevo rol ══ --}}
@if ($showAddForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:11px 18px; display:flex; align-items:center; justify-content:space-between;">
        <p style="font-size:13px; font-weight:700; color:#5B21B6; margin:0; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Nuevo Rol
        </p>
        <button wire:click="cancelAdd" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:3px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 18px;">
        <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; margin-bottom:14px;">
            <div style="flex:1; min-width:160px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Nombre *</label>
                <input wire:model="newRoleName" type="text" placeholder="ej: ejecutivo"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box;">
                @error('newRoleName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div style="padding-bottom:4px;">
                <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                    <input wire:model="newActivo" type="checkbox" style="width:14px; height:14px; cursor:pointer; accent-color:#7B6FE8;">
                    <span style="font-size:13px; font-weight:500; color:#374151;">Activo</span>
                </label>
            </div>
        </div>
        <div style="display:flex; gap:8px; padding-top:12px; border-top:1px solid #F3F4F6;">
            <button wire:click="saveNew"
                    style="height:36px; padding:0 20px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; box-sizing:border-box;">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:36px; padding:0 16px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; box-sizing:border-box;">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ TABLA ══ --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Roles registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $roles->total() }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <button type="button" wire:click="$refresh"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualizar
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        @if ($roles->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay roles registrados.</p>
        @else
        <table style="table-layout:fixed; width:100%; min-width:500px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:220px;">
                <col style="width:100px;">
                <col style="width:120px;">
                <col style="width:180px;">
            </colgroup>
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:120px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Rol</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:center; position:relative; user-select:none; overflow:hidden; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Usuarios</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:center; position:relative; user-select:none; overflow:hidden; min-width:80px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:center;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)

                {{-- Fila edición inline --}}
                @if ($editingId === $role->id)
                <tr wire:key="edit-{{ $role->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td style="padding:7px 10px; text-align:left;">
                        @if ($role->name === 'admin')
                            <span style="font-size:13px; font-weight:600; color:#6B7280; text-transform:capitalize;">admin</span>
                        @else
                            <input wire:model="editRoleName" type="text" placeholder="Nombre del rol"
                                   style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                            @error('editRoleName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                        @endif
                    </td>
                    <td style="padding:7px 10px; text-align:center; color:#9CA3AF; font-size:12px;">{{ $role->users_count }}</td>
                    <td style="padding:7px 10px; text-align:center;">
                        @if ($role->name === 'admin')
                            <span style="font-size:12px; color:#7B6FE8; font-weight:500;">Siempre activo</span>
                        @else
                            <label style="display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                                <input type="checkbox" wire:model="editActivo" style="width:14px; height:14px; cursor:pointer; accent-color:#7B6FE8;">
                                <span style="font-size:12px; color:#374151;">Activo</span>
                            </label>
                        @endif
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="saveEdit"
                                    style="height:30px; padding:0 10px; background:#7B6FE8; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    style="height:30px; padding:0 8px; background:#F3F4F6; color:#6B7280; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
                                Cancelar
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                <tr wire:key="role-{{ $role->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    <td style="padding:10px 16px; overflow:hidden; text-align:left;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:28px; height:28px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span style="font-size:13px; font-weight:600; color:#111827; text-transform:capitalize; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $role->name }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:99px; background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600;">{{ $role->users_count }}</span>
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        @if ($role->name === 'admin')
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#EDE9FE; color:#7B6FE8;">Siempre activo</span>
                        @elseif ($role->activo ?? true)
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#D1FAE5; color:#059669;">Activo</span>
                        @else
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#FEE2E2; color:#EF4444;">Inactivo</span>
                        @endif
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="openPermissions({{ $role->id }})" title="Permisos"
                                    style="height:28px; padding:0 10px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:600; white-space:nowrap;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Permisos
                            </button>
                            <button wire:click="startEdit({{ $role->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            @if ($role->name !== 'admin')
                            <button wire:click="toggleActivo({{ $role->id }})" title="{{ ($role->activo ?? true) ? 'Desactivar' : 'Activar' }}"
                                    style="width:28px; height:28px; border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center;
                                           border:1px solid {{ ($role->activo ?? true) ? '#FEE2E2' : '#D1FAE5' }};
                                           background:{{ ($role->activo ?? true) ? '#FEF2F2' : '#ECFDF5' }};
                                           color:{{ ($role->activo ?? true) ? '#EF4444' : '#10B981' }};"
                                    @mouseenter="$el.style.opacity='.7'" @mouseleave="$el.style.opacity='1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif

                @empty
                <tr><td colspan="4" style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay roles registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        @endif
    </div>

    @if ($roles->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $roles->links() }}</div>
    @endif
</div>

<script>
window.colResize = function () {
    return {
        start(e) {
            e.preventDefault();
            const th = this.$el.closest('th');
            const table = th.closest('table');
            const idx = Array.from(th.closest('tr').querySelectorAll('th')).indexOf(th);
            const col = table.querySelectorAll('colgroup col')[idx];
            const startX = e.clientX;
            const startW = col ? (parseFloat(col.style.width) || th.getBoundingClientRect().width)
                               : th.getBoundingClientRect().width;
            const onMove = mv => {
                const w = Math.max(60, startW + mv.clientX - startX);
                if (col) col.style.width = w + 'px';
            };
            const onUp = () => {
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
            };
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        }
    };
};
</script>

@endif

@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#EF4444; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

</div>
