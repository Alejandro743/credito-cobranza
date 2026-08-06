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


{{-- ══ FORM: Nuevo usuario ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo usuario
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div style="flex:1; min-width:150px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre Completo *</label>
            <input wire:model="newName" type="text" placeholder="Ej. María García"
                   style="width:100%; {{ $iS }}">
            @error('newName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:140px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Usuario *</label>
            <input wire:model="newUsuario" type="text" placeholder="Ej. maria.garcia"
                   style="width:100%; {{ $iS }} font-family:monospace;">
            @error('newUsuario') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="flex:1; min-width:140px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Contraseña *</label>
            <input wire:model="newPassword" type="password" placeholder="Mín. 6 caracteres"
                   style="width:100%; {{ $iS }}">
            @error('newPassword') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Tipo *</label>
            <select wire:model="newTipo" style="width:100%; {{ $iS }} cursor:pointer;">
                <option value="administrativo">Administrativo</option>
                <option value="vendedor">Vendedor</option>
                <option value="cliente">Cliente</option>
            </select>
            @error('newTipo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div style="min-width:130px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Rol *</label>
            <select wire:model="newRole" style="width:100%; {{ $iS }} cursor:pointer;">
                <option value="">— Seleccionar —</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            @error('newRole') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; margin-bottom:5px;">·</label>
            <div style="display:flex; gap:8px;">
                <button wire:click="saveNew"
                        style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                    Guardar
                </button>
                <button wire:click="cancelAdd"
                        style="height:38px; padding:0 18px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;"
                        @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ MOBILE: Cards ══ --}}
@php
$iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;';
$tipoBadgeMobile = fn($tipo) => match($tipo ?? 'administrativo') {
    'vendedor' => ['bg'=>'rgba(249,115,22,.1)','color'=>'#EA580C'],
    'cliente'  => ['bg'=>'rgba(59,130,246,.1)','color'=>'#2563EB'],
    default    => ['bg'=>'rgba(123,111,232,.1)','color'=>'#7B6FE8'],
};
@endphp
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($users as $user)
    @php $roleName = $user->getRoleNames()->first() ?? '—'; $tb = $tipoBadgeMobile($user->tipo); @endphp

    @if($editingId === $user->id)
    {{-- Card edición --}}
    <div wire:key="card-edit-{{ $user->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px;">
            <p style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0;">Editando usuario</p>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editName" type="text" style="{{ $iM }}">
                    @error('editName')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Usuario *</label>
                    <input wire:model="editUsuario" type="text" style="{{ $iM }} font-family:monospace;">
                    @error('editUsuario')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Tipo</label>
                    <select wire:model="editTipo" style="{{ $iM }} cursor:pointer;">
                        <option value="administrativo">Admin.</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="cliente">Cliente</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Rol</label>
                    <select wire:model="editRole" style="{{ $iM }} cursor:pointer;">
                        <option value="">— Rol —</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('editRole')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                    <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
                <button wire:click="saveEdit" style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">Guardar</button>
                <button wire:click="cancelEdit" style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            </div>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $user->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Header --}}
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->name }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $user->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $user->active ? '#059669' : '#9CA3AF' }};">
                {{ $user->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Info: tipo + rol --}}
        <div style="padding:10px 14px; display:flex; gap:8px; align-items:center;">
            <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; background:{{ $tb['bg'] }}; color:{{ $tb['color'] }};">
                {{ ucfirst($user->tipo ?? 'administrativo') }}
            </span>
            <span style="font-size:11px; color:#D1D5DB;">|</span>
            <span style="font-size:12px; color:#6B7280; text-transform:capitalize; font-weight:500;">{{ $roleName }}</span>
        </div>

        {{-- Acciones --}}
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button wire:click="startEdit({{ $user->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
            <button wire:click="openPasswordModal({{ $user->id }})"
                    style="flex:1; height:34px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Contraseña
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
<div class="hidden sm:block">
@if(!$showAddForm)
<div style="display:flex; justify-content:flex-start; margin-bottom:8px;">
    <button wire:click="showAdd"
            style="height:32px; padding:0 14px; display:inline-flex; align-items:center; gap:5px; border:none; border-radius:8px; background:#7B6FE8; font-size:12px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo usuario
    </button>
</div>
@endif
</div>
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-wrap:wrap;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Usuarios registrados</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $users->total() }}</span>
        @if($selectedUserId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedUserId)
                <button wire:click="saveEdit" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} background:#E5E7EB; color:#374151;">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="startEdit({{ $selectedUserId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
                <button wire:click="openPasswordModal({{ $selectedUserId }})" style="{{ $btnH }} background:#7B6FE8; color:#fff;">
                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                    Cambiar Contraseña
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        <table class="um-table" style="width:100%; min-width:700px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                @php
                    $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
                    $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
                    $fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
                    $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
                    $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
                    $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
                @endphp
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'pointer-events:none;' : '' }}">

                    {{-- # --}}
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedUserId !== null"
                                   :disabled="$wire.selectedUserId === null"
                                   @click.prevent="$wire.selectedUserId !== null && $wire.set('selectedUserId', null)"
                                   :style="$wire.selectedUserId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Usuario --}}
                    @php $isA = $sortBy === 'name'; @endphp
                    <th wire:click="toggleSort('name')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:160px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Usuario
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Email --}}
                    @php $isA = $sortBy === 'email'; @endphp
                    <th wire:click="toggleSort('email')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:160px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Email
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterUsuario" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Tipo --}}
                    @php $isA = $sortBy === 'tipo'; @endphp
                    <th wire:click="toggleSort('tipo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:120px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Tipo
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterTipo" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="administrativo">Administrativo</option>
                                <option value="vendedor">Vendedor</option>
                                <option value="cliente">Cliente</option>
                            </select>
                        </div>
                    </th>

                    {{-- Rol (sin sort) --}}
                    <th style="{{ $thC }} text-align:left; min-width:100px;">
                        Rol
                        <div style="{{ $fW }}">
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterRol" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'active'; @endphp
                    <th wire:click="toggleSort('active')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px;">Estado
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>
                            {!! $fSvg !!}
                            <select wire:model.live="colFilterEstado" @click.stop style="{{ $fS }}">
                                <option value="">Todos</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                @php $roleName = $user->getRoleNames()->first() ?? '—'; @endphp

                {{-- Fila edición inline --}}
                @if ($editingId === $user->id)
                <tr wire:key="edit-{{ $user->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:13px; color:#111827; white-space:nowrap;">{{ $users->firstItem() + $loop->index }}</td>
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
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selU = $selectedUserId === $user->id; @endphp
                <tr wire:key="u-{{ $user->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s; background:{{ $selU ? '#F5F3FF' : '' }}; {{ $selU ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selU ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selU ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selU ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedUserId === {{ $user->id }}"
                                   @click="$wire.selectedUserId === {{ $user->id }} ? $wire.set('selectedUserId', null) : $wire.selectUser({{ $user->id }})"
                                   :disabled="{{ $editingId && $editingId !== $user->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $user->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:13px; color:#111827;">{{ $users->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucwords(strtolower($user->name)) }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $user->email }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ ucfirst($user->tipo ?? 'administrativo') }}</span>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; text-transform:capitalize;">{{ $roleName }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; white-space:nowrap;
                                     background:{{ $user->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $user->active ? '#059669' : '#9CA3AF' }};">
                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>

                </tr>
                @endif

                @endforeach
                @if ($users->isEmpty())
                <tr><td colspan="6" style="padding:48px;"><p style="text-align:center; color:#9CA3AF; font-size:13px; margin:0;">{{ $colFilterNombre || $colFilterUsuario || $colFilterTipo || $colFilterRol || $colFilterEstado ? 'Sin resultados para los filtros aplicados.' : 'No hay usuarios registrados.' }}</p></td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $users->links() }}</div>
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
