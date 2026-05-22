<div>

@php
    $tipoBadge = fn($tipo) => match($tipo ?? 'administrativo') {
        'vendedor' => ['bg' => 'rgba(249,115,22,.12)', 'color' => '#EA580C'],
        'cliente'  => ['bg' => 'rgba(59,130,246,.12)',  'color' => '#2563EB'],
        default    => ['bg' => 'rgba(123,111,232,.12)', 'color' => '#7B6FE8'],
    };
    $rolBadge = fn($rol) => match($rol) {
        'admin'    => ['bg' => 'rgba(123,111,232,.12)', 'color' => '#7B6FE8'],
        'credito'  => ['bg' => 'rgba(16,185,129,.12)',  'color' => '#059669'],
        'vendedor' => ['bg' => 'rgba(249,115,22,.12)',  'color' => '#EA580C'],
        'cliente'  => ['bg' => 'rgba(59,130,246,.12)',  'color' => '#2563EB'],
        default    => ['bg' => '#F3F4F6',               'color' => '#6B7280'],
    };
@endphp

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

{{-- Modal: Cambio de contraseña --}}
@if ($showPasswordModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div wire:click="closePasswordModal" class="absolute inset-0" style="background:rgba(17,24,39,.4); backdrop-filter:blur(4px);"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:18px 22px; display:flex; align-items:flex-start; justify-content:space-between; gap:16px;">
            <div>
                <p style="font-size:14px; font-weight:700; color:#1F2937; margin-bottom:6px;">Cambio de contraseña</p>
                <p style="font-size:12px; color:#6B7280; margin-bottom:2px;">
                    <span style="font-weight:600; color:#374151;">Usuario:</span>
                    <span style="font-family:monospace; color:#7B6FE8; margin-left:4px;">{{ $passwordModalUsuario }}</span>
                </p>
                <p style="font-size:12px; color:#6B7280;">
                    <span style="font-weight:600; color:#374151;">Nombre:</span>
                    <span style="margin-left:4px;">{{ $passwordModalNombre }}</span>
                </p>
            </div>
            <button wire:click="closePasswordModal" style="padding:5px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:#9CA3AF; flex-shrink:0; display:flex;">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:22px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Nueva contraseña</label>
            <div x-data="{ show: false }" style="position:relative;">
                <input wire:model="passwordModalNew" :type="show ? 'text' : 'password'"
                       placeholder="Mínimo 6 caracteres" autofocus
                       style="width:100%; border:1px solid #E5E7EB; border-radius:10px; padding:10px 40px 10px 14px; font-size:13px; outline:none; box-sizing:border-box;">
                <button type="button" @click="show = !show"
                        style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:#9CA3AF;">
                    <svg x-show="!show" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('passwordModalNew')
            <p style="color:#EF4444; font-size:11px; margin-top:5px;">{{ $message }}</p>
            @enderror
        </div>
        <div style="padding:14px 22px; background:#F9FAFB; border-top:1px solid #F3F4F6; display:flex; justify-content:flex-end; gap:10px;">
            <button wire:click="closePasswordModal" style="padding:8px 18px; border-radius:10px; font-size:13px; font-weight:600; color:#6B7280; background:transparent; border:1px solid #E5E7EB; cursor:pointer;">Cancelar</button>
            <button wire:click="savePasswordModal" style="padding:8px 18px; border-radius:10px; font-size:13px; font-weight:600; color:#fff; background:#7B6FE8; border:none; cursor:pointer;">Guardar contraseña</button>
        </div>
    </div>
</div>
@endif

{{-- ══ TOOLBAR ══ --}}
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%; sm:max-width:280px;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o usuario..."
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>

    <select wire:model.live="filterTipo" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todos los tipos</option>
        <option value="administrativo">Administrativo</option>
        <option value="vendedor">Vendedor</option>
        <option value="cliente">Cliente</option>
    </select>

    <select wire:model.live="filterRole" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todos los roles</option>
        @foreach ($roles as $role)
            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
        @endforeach
    </select>

    <select wire:model.live="filterStatus" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todos los estados</option>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select>

    <button wire:click="showAdd" class="w-full sm:w-auto"
            style="height:36px; padding:0 18px; display:flex; align-items:center; justify-content:center; gap:6px; border:none; border-radius:9px; background:#7B6FE8; font-size:13px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; box-sizing:border-box;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo usuario
    </button>
</div>

{{-- ══ FORM: Nuevo usuario ══ --}}
@if ($showAddForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 8px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:11px 18px; display:flex; align-items:center; justify-content:space-between;">
        <p style="font-size:13px; font-weight:700; color:#5B21B6; margin:0; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo usuario
        </p>
        <button wire:click="cancelAdd" style="background:transparent; border:none; cursor:pointer; color:#9CA3AF; display:flex; padding:3px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 18px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:12px; margin-bottom:14px;">
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Nombre Completo *</label>
                <input wire:model="newName" type="text" placeholder="Ej. María García"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box;">
                @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Usuario *</label>
                <input wire:model="newUsuario" type="text" placeholder="Ej. maria.garcia"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box; font-family:monospace;">
                @error('newUsuario') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Contraseña *</label>
                <input wire:model="newPassword" type="password" placeholder="Mín. 6 caracteres"
                       style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; box-sizing:border-box;">
                @error('newPassword') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Tipo *</label>
                <select wire:model="newTipo"
                        style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="administrativo">Administrativo</option>
                    <option value="vendedor">Vendedor</option>
                    <option value="cliente">Cliente</option>
                </select>
                @error('newTipo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Rol *</label>
                <select wire:model="newRole"
                        style="width:100%; border:1px solid #E5E7EB; border-radius:8px; padding:7px 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box;">
                    <option value="">— Seleccionar —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('newRole') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
        </div>
        <div style="display:flex; gap:8px; padding-top:12px; border-top:1px solid #F3F4F6;">
            <button wire:click="saveNew"
                    style="height:36px; padding:0 20px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; box-sizing:border-box;">
                Guardar nuevo
            </button>
            <button wire:click="cancelAdd"
                    style="height:36px; padding:0 16px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; box-sizing:border-box;">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ MOBILE: Cards ══ --}}
@php $iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div class="sm:hidden" style="display:flex; flex-direction:column; gap:10px;">
    @forelse ($users as $user)
    @php $roleName = $user->getRoleNames()->first() ?? '—'; @endphp

    @if($editingId === $user->id)
    {{-- CARD EDICIÓN --}}
    <div wire:key="card-edit-{{ $user->id }}"
         style="background:#FAFAFE; border-radius:14px; border:1px solid #EDE9FE; padding:14px 16px; box-shadow:0 1px 4px rgba(123,111,232,.1);">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                <input wire:model="editName" type="text" style="{{ $iM }}">
                @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Usuario *</label>
                <input wire:model="editUsuario" type="text" style="{{ $iM }} font-family:monospace;">
                @error('editUsuario')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:12px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Tipo</label>
                <select wire:model="editTipo" style="{{ $iM }} cursor:pointer;">
                    <option value="administrativo">Admin.</option>
                    <option value="vendedor">Vendedor</option>
                    <option value="cliente">Cliente</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Rol</label>
                <select wire:model="editRole" style="{{ $iM }} cursor:pointer;">
                    <option value="">— Rol —</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('editRole')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <button wire:click="saveEdit"
                    style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                Guardar
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>

    @else
    {{-- CARD NORMAL --}}
    <div wire:key="card-{{ $user->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        <div style="background:#F8F7FF; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #EDE9FE;">
            <p style="font-size:14px; font-weight:700; color:#111827; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; margin-right:10px;">{{ $user->name }}</p>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $user->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $user->active ? '#059669' : '#9CA3AF' }};">
                {{ $user->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div style="padding:10px 14px 12px;">
            <p style="font-size:12px; color:#7B6FE8; font-family:monospace; font-weight:600; margin:0 0 4px;">{{ $user->email }}</p>
            <p style="font-size:12px; color:#6B7280; margin:0;">{{ ucfirst($user->tipo ?? 'administrativo') }} · {{ ucfirst($roleName) }}</p>
        </div>

        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:7px;">
            <button wire:click="startEdit({{ $user->id }})"
                    style="flex:1; height:32px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer;">
                Editar
            </button>
            <button wire:click="openPasswordModal({{ $user->id }})"
                    style="flex:1; height:32px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer;">
                Contraseña
            </button>
            <button wire:click="toggleActive({{ $user->id }})"
                    style="flex:1; height:32px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;
                           border:1px solid {{ $user->active ? '#FEE2E2' : '#D1FAE5' }};
                           background:{{ $user->active ? '#FEF2F2' : '#ECFDF5' }};
                           color:{{ $user->active ? '#EF4444' : '#10B981' }};">
                {{ $user->active ? 'Desactivar' : 'Activar' }}
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay usuarios registrados.</p>
    @endforelse
    @if ($users->hasPages())
    <div style="padding-top:8px;">{{ $users->links() }}</div>
    @endif
</div>

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Usuarios registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $users->total() }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <button type="button" wire:click="$refresh"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualizar
            </button>
            <button type="button"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Importar
            </button>
            <button type="button"
                    style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        @if ($users->isEmpty())
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay usuarios registrados.</p>
        @else
        <table class="um-table" style="table-layout:fixed; width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:180px;">  {{-- nombre --}}
                <col style="width:180px;">  {{-- email --}}
                <col style="width:110px;">  {{-- tipo --}}
                <col style="width:100px;">  {{-- rol --}}
                <col style="width:95px;">   {{-- estado --}}
                <col style="width:95px;">   {{-- acciones --}}
            </colgroup>
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:100px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Nombre Completo</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:100px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Usuario</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Tipo</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Rol</span>
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>
                    <th style="padding:10px 16px; text-align:left; position:relative; user-select:none; overflow:hidden; min-width:70px;">
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
                @foreach ($users as $user)
                @php $roleName = $user->getRoleNames()->first() ?? '—'; @endphp

                {{-- Fila edición inline --}}
                @if ($editingId === $user->id)
                <tr wire:key="edit-{{ $user->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td style="padding:7px 10px; text-align:left;">
                        <input wire:model="editName" type="text" placeholder="Nombre completo"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                        @error('editName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; text-align:left;">
                        <input wire:model="editUsuario" type="text" placeholder="usuario"
                               style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff; font-family:monospace;">
                        @error('editUsuario') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px; text-align:left;">
                        <select wire:model="editTipo"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="administrativo">Administrativo</option>
                            <option value="vendedor">Vendedor</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </td>
                    <td style="padding:7px 10px; text-align:left;">
                        <select wire:model="editRole"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="">— Rol —</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('editRole') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:7px 10px;">
                        <select wire:model="editActive"
                                style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
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
                <tr wire:key="u-{{ $user->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    <td style="padding:10px 16px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $user->name }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $user->email }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucfirst($user->tipo ?? 'administrativo') }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; text-transform:capitalize;">{{ $roleName }}</span>
                    </td>

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;
                                     background:{{ $user->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $user->active ? '#059669' : '#9CA3AF' }};">
                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button wire:click="startEdit({{ $user->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button wire:click="openPasswordModal({{ $user->id }})" title="Cambiar contraseña"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #E5E7EB; background:#F9FAFB; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#F9FAFB'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleActive({{ $user->id }})" title="{{ $user->active ? 'Desactivar' : 'Activar' }}"
                                    style="width:28px; height:28px; border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center;
                                           border:1px solid {{ $user->active ? '#FEE2E2' : '#D1FAE5' }};
                                           background:{{ $user->active ? '#FEF2F2' : '#ECFDF5' }};
                                           color:{{ $user->active ? '#EF4444' : '#10B981' }};"
                                    @mouseenter="$el.style.opacity='.7'" @mouseleave="$el.style.opacity='1'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($users->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $users->links() }}</div>
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

</div>
