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
{{-- ── ACCESOS DEL ROL ──────────────────────────────────────────────────── --}}
@php
    $accColorMap = [
        'lavanda'   => ['head_bg'=>'#F5F3FF','head_border'=>'#EDE9FE','hover'=>'#F0EEFF','icon'=>'#7B6FE8','dot'=>'#A78BFA','btn_bg'=>'#EDE9FE','btn_color'=>'#5B21B6'],
        'mint'      => ['head_bg'=>'#F0FDF4','head_border'=>'#D1FAE5','hover'=>'#E6FCF0','icon'=>'#059669','dot'=>'#6EE7B7','btn_bg'=>'#D1FAE5','btn_color'=>'#065F46'],
        'melocoton' => ['head_bg'=>'#FFF7ED','head_border'=>'#FED7AA','hover'=>'#FEF0D8','icon'=>'#EA580C','dot'=>'#FDBA74','btn_bg'=>'#FED7AA','btn_color'=>'#9A3412'],
        'celeste'   => ['head_bg'=>'#EFF6FF','head_border'=>'#BFDBFE','hover'=>'#E4F0FF','icon'=>'#2563EB','dot'=>'#93C5FD','btn_bg'=>'#BFDBFE','btn_color'=>'#1E40AF'],
    ];
@endphp

{{-- Cabecera --}}
<div style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:14px; overflow:hidden;">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="padding:13px 18px;">
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <button wire:click="backToList"
                    style="width:34px; height:34px; border-radius:9px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div style="min-width:0;">
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.6px; margin:0 0 2px;">Configurando accesos</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; text-transform:capitalize; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $permissionsRoleName }}</p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:6px;">
            <button wire:click="toggleTodos(true)" class="flex-1 sm:flex-none"
                    style="height:34px; padding:0 12px; border:1px solid #EDE9FE; border-radius:7px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px; white-space:nowrap; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Dar todo
            </button>
            <button wire:click="toggleTodos(false)" class="flex-1 sm:flex-none"
                    style="height:34px; padding:0 12px; border:1px solid #EDE9FE; border-radius:7px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:4px; white-space:nowrap; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Quitar todo
            </button>
        </div>
    </div>
</div>

{{-- Módulos --}}
<div style="display:flex; flex-direction:column; gap:10px; margin-bottom:14px;">
    @forelse ($modulosArbol as $modulo)
    @php $cc = $accColorMap[$modulo->color] ?? $accColorMap['lavanda']; @endphp
    <div wire:key="m-{{ $modulo->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 3px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Cabecera módulo --}}
        <div style="padding:10px 16px; background:{{ $cc['head_bg'] }}; border-bottom:1px solid {{ $cc['head_border'] }}; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:30px; height:30px; border-radius:8px; background:{{ $cc['head_border'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="14" height="14" fill="none" stroke="{{ $cc['icon'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="{{ $modulo->icon }}"/>
                    </svg>
                </div>
                <span style="font-size:13px; font-weight:700; color:#111827;">{{ $modulo->name }}</span>
            </div>
            <div style="display:flex; gap:5px;">
                <button wire:click="toggleModulo({{ $modulo->id }}, true)"
                        style="height:26px; padding:0 10px; border-radius:6px; border:none; background:{{ $cc['btn_bg'] }}; color:{{ $cc['btn_color'] }}; font-size:11px; font-weight:700; cursor:pointer;">
                    Todos
                </button>
                <button wire:click="toggleModulo({{ $modulo->id }}, false)"
                        style="height:26px; padding:0 10px; border-radius:6px; border:1px solid #E5E7EB; background:#fff; color:#6B7280; font-size:11px; font-weight:700; cursor:pointer;">
                    Ninguno
                </button>
            </div>
        </div>

        {{-- Submodulos --}}
        @foreach ($modulo->submodulos as $sub)
        @if ($sub->isGroup())
        <div style="padding:5px 16px; background:#F9FAFB; border-bottom:1px solid #F3F4F6;">
            <span style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.7px;">{{ $sub->name }}</span>
        </div>
        @foreach ($sub->children as $leaf)
        @php $key = (string) $leaf->id; @endphp
        <div wire:key="s-{{ $leaf->id }}"
             style="padding:10px 16px 10px 28px; border-bottom:1px solid #F9FAFB; display:flex; align-items:center; justify-content:space-between;"
             @mouseenter="$el.style.background='{{ $cc['hover'] }}'" @mouseleave="$el.style.background=''">
            <div style="display:flex; align-items:center; gap:7px;">
                <span style="width:5px; height:5px; border-radius:50%; background:{{ $cc['dot'] }}; flex-shrink:0; display:block;"></span>
                <span style="font-size:13px; color:#374151;">{{ $leaf->name }}</span>
            </div>
            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; user-select:none;">
                <span class="hidden sm:inline" style="font-size:11px; font-weight:500; min-width:62px; text-align:right; color:{{ ($permissions[$key]['puede_ver'] ?? false) ? '#7B6FE8' : '#D1D5DB' }};">
                    {{ ($permissions[$key]['puede_ver'] ?? false) ? 'Con acceso' : 'Sin acceso' }}
                </span>
                <div style="position:relative; width:36px; height:20px; flex-shrink:0;">
                    <input type="checkbox" wire:model.live="permissions.{{ $key }}.puede_ver" class="peer"
                           style="position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; margin:0; z-index:1;">
                    <div class="peer-checked:bg-lavanda-500 bg-gray-200"
                         style="position:absolute; inset:0; border-radius:10px; transition:background .15s; pointer-events:none;"></div>
                    <div class="peer-checked:translate-x-4 translate-x-0"
                         style="position:absolute; top:2px; left:2px; width:16px; height:16px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,.2); transition:transform .15s; pointer-events:none;"></div>
                </div>
            </label>
        </div>
        @endforeach

        @else
        @php $key = (string) $sub->id; @endphp
        <div wire:key="s-{{ $sub->id }}"
             style="padding:10px 16px; border-bottom:1px solid #F9FAFB; display:flex; align-items:center; justify-content:space-between;"
             @mouseenter="$el.style.background='{{ $cc['hover'] }}'" @mouseleave="$el.style.background=''">
            <div style="display:flex; align-items:center; gap:7px;">
                <span style="width:5px; height:5px; border-radius:50%; background:{{ $cc['dot'] }}; flex-shrink:0; display:block;"></span>
                <span style="font-size:13px; color:#374151;">{{ $sub->name }}</span>
            </div>
            <label style="display:flex; align-items:center; gap:7px; cursor:pointer; user-select:none;">
                <span class="hidden sm:inline" style="font-size:11px; font-weight:500; min-width:62px; text-align:right; color:{{ ($permissions[$key]['puede_ver'] ?? false) ? '#7B6FE8' : '#D1D5DB' }};">
                    {{ ($permissions[$key]['puede_ver'] ?? false) ? 'Con acceso' : 'Sin acceso' }}
                </span>
                <div style="position:relative; width:36px; height:20px; flex-shrink:0;">
                    <input type="checkbox" wire:model.live="permissions.{{ $key }}.puede_ver" class="peer"
                           style="position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; margin:0; z-index:1;">
                    <div class="peer-checked:bg-lavanda-500 bg-gray-200"
                         style="position:absolute; inset:0; border-radius:10px; transition:background .15s; pointer-events:none;"></div>
                    <div class="peer-checked:translate-x-4 translate-x-0"
                         style="position:absolute; top:2px; left:2px; width:16px; height:16px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,.2); transition:transform .15s; pointer-events:none;"></div>
                </div>
            </label>
        </div>
        @endif
        @endforeach

    </div>
    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay módulos en BD.</p>
    @endforelse
</div>

{{-- Footer --}}
<div style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; padding:13px 18px; display:flex; align-items:center; justify-content:flex-end; gap:8px;">
    <button wire:click="savePermissions" wire:loading.attr="disabled"
            style="height:36px; padding:0 22px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; box-sizing:border-box; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <span wire:loading.remove wire:target="savePermissions">Guardar</span>
        <span wire:loading wire:target="savePermissions">Guardando...</span>
    </button>
    <button wire:click="backToList"
            style="height:36px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; box-sizing:border-box; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        Cerrar
    </button>
</div>

@else
{{-- ── LIST ──────────────────────────────────────────────────────────────── --}}


{{-- ══ FORM: Nuevo rol ══ --}}
@if ($showAddForm)
@php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#7B6FE8;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Nuevo Rol
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px; display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap;">
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre del Rol *</label>
            <input wire:model="newRoleName" type="text" placeholder="Nombre del rol"
                   style="width:100%; {{ $iS }}">
            @error('newRoleName') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:700; color:transparent; margin-bottom:5px;">·</label>
            <div style="display:flex; gap:8px;">
                <button wire:click="saveNew"
                        style="height:38px; padding:0 24px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    Guardar
                </button>
                <button wire:click="cancelAdd"
                        style="height:38px; padding:0 18px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
                        onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ MOBILE: Cards ══ --}}
<div class="sm:hidden flex flex-col" style="gap:10px;">
    @forelse ($roles as $role)

    @if ($editingId === $role->id)
    {{-- Card edición --}}
    <div wire:key="card-edit-{{ $role->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px;">
            <p style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0;">Editando rol</p>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            @if ($role->name !== 'admin')
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Nombre</label>
                <input wire:model="editRoleName" type="text"
                       style="width:100%; height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
                @error('editRoleName')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                <select wire:model="editActivo"
                        style="width:100%; height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; outline:none; background:#fff; box-sizing:border-box; cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            @else
            <p style="font-size:12px; color:#9CA3AF; margin:0; font-style:italic;">El rol admin no se puede modificar.</p>
            @endif
            <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
                @if ($role->name !== 'admin')
                <button wire:click="saveEdit" style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">Guardar</button>
                @endif
                <button wire:click="cancelEdit" style="flex:1; height:36px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            </div>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $role->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Header --}}
        <div style="padding:12px 14px; display:flex; align-items:center; gap:12px; border-bottom:1px solid #F3F4F6;">
            <div style="width:38px; height:38px; border-radius:10px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; text-transform:capitalize;">{{ $role->name }}</p>
                <p style="font-size:12px; color:#9CA3AF; margin:2px 0 0;">{{ $role->users_count }} usuario{{ $role->users_count !== 1 ? 's' : '' }}</p>
            </div>
            @if ($role->name === 'admin')
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#EDE9FE; color:#7B6FE8; flex-shrink:0;">Siempre activo</span>
            @elseif ($role->activo ?? true)
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#D1FAE5; color:#059669; flex-shrink:0;">Activo</span>
            @else
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; background:#FEE2E2; color:#EF4444; flex-shrink:0;">Inactivo</span>
            @endif
        </div>

        {{-- Acciones --}}
        <div style="padding:10px 14px; display:flex; gap:8px;">
            <button wire:click="openPermissions({{ $role->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Accesos
            </button>
            <button wire:click="openView({{ $role->id }})"
                    style="width:34px; height:34px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
                    title="Ver accesos">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
            <button wire:click="startEdit({{ $role->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay roles registrados.</p>
    @endforelse
    @if ($roles->hasPages())
    <div style="padding-top:8px;">{{ $roles->links() }}</div>
    @endif
</div>

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block">
@if(!$showAddForm)
<div style="display:flex; justify-content:flex-start; margin-bottom:8px;">
    <button wire:click="showAdd"
            style="height:32px; padding:0 14px; display:inline-flex; align-items:center; gap:5px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; font-size:12px; font-weight:700; color:#7B6FE8; cursor:pointer; white-space:nowrap; transition:background .15s, color .15s;"
            onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nuevo Rol
    </button>
</div>
@endif
</div>
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6; flex-wrap:wrap;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Roles registrados</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $roles->total() }}</span>
        @if($selectedRoleId)
        @php $btnH = 'height:28px; padding:0 10px; border:none; border-radius:7px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;'; @endphp
        <div style="display:flex; align-items:center; gap:5px; margin-left:4px; padding-left:10px; border-left:1px solid #E5E7EB;">
            @if($editingId === $selectedRoleId)
                <button wire:click="saveEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Guardar
                </button>
                <button wire:click="cancelEdit" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancelar
                </button>
            @else
                <button wire:click="openPermissions({{ $selectedRoleId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Accesos
                </button>
                <button wire:click="startEdit({{ $selectedRoleId }})" style="{{ $btnH }} border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; transition:background .15s, color .15s;" onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Editar
                </button>
            @endif
        </div>
        @endif
    </div>

    <div style="overflow:auto; flex:1;">
        @php
            $fI  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 6px 0 22px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:left;';
            $fS  = 'height:28px; font-size:11px; border:1px solid #DDD8FA; border-radius:5px; padding:0 4px; width:100%; outline:none; box-sizing:border-box; background:#fff; color:#9CA3AF; font-weight:700; text-align:center; text-indent:16px; cursor:pointer;';
            $fW  = 'position:relative; margin-top:4px;' . ($editingId ? ' opacity:0.45;' : '');
            $fIc = 'position:absolute; left:6px; top:50%; transform:translateY(-50%); width:11px; height:11px; pointer-events:none;';
            $fSvg = '<svg style="'.$fIc.'" fill="none" stroke="#9CA3AF" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35" stroke-linecap="round"/></svg>';
            $thC = 'font-size:11px; font-weight:700; color:#7B6FE8; padding:8px 10px 6px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; user-select:none; vertical-align:top; position:relative; overflow:hidden;';
        @endphp
        <table style="width:100%; min-width:500px; border-collapse:collapse; font-size:13px;">
            <thead style="position:sticky; top:0; z-index:10;">
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE; {{ $editingId ? 'pointer-events:none;' : '' }}">

                    {{-- # --}}
                    <th style="width:50px; padding:8px 8px 6px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; vertical-align:top; position:sticky; left:0; z-index:11; background:#F9F8FF;">
                        #
                        <div style="margin-top:4px; height:28px; display:flex; align-items:center; justify-content:center;">
                            <input type="checkbox"
                                   :checked="$wire.selectedRoleId !== null"
                                   :disabled="$wire.selectedRoleId === null"
                                   @click.prevent="$wire.selectedRoleId !== null && $wire.set('selectedRoleId', null)"
                                   :style="$wire.selectedRoleId !== null ? 'accent-color:#7B6FE8; width:15px; height:15px; cursor:pointer;' : 'accent-color:#7B6FE8; width:15px; height:15px; cursor:default; opacity:0.35;'">
                        </div>
                    </th>

                    {{-- Nombre --}}
                    @php $isA = $sortBy === 'name'; @endphp
                    <th wire:click="toggleSort('name')" style="{{ $thC }} text-align:left; cursor:pointer; min-width:160px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
                        @mouseenter="!{{ $isA?'true':'false' }} && ($el.style.background='#F5F3FF')" @mouseleave="!{{ $isA?'true':'false' }} && ($el.style.background='')">
                        <div style="display:flex; align-items:center; gap:4px;">Nombre
                            <span style="display:inline-flex; flex-direction:column; gap:1px; line-height:1;">
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='asc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 0l5 6H0z"/></svg>
                                <svg width="7" height="7" viewBox="0 0 10 6" fill="{{ $isA && $sortDir==='desc' ? '#7B6FE8':'#C4B5FD' }}"><path d="M5 6l5-6H0z"/></svg>
                            </span>
                        </div>
                        <div style="{{ $fW }}" @click.stop>{!! $fSvg !!}<input wire:model.live.debounce.300ms="colFilterNombre" @click.stop type="text" style="{{ $fI }}"></div>
                        <div x-data="colResize()" @mousedown="start($event)" style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;" @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                    </th>

                    {{-- Estado --}}
                    @php $isA = $sortBy === 'activo'; @endphp
                    <th wire:click="toggleSort('activo')" style="{{ $thC }} text-align:center; cursor:pointer; min-width:110px; {{ $isA ? 'background:#EDE9FE;' : '' }}"
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
                @forelse ($roles as $role)

                {{-- Fila edición inline --}}
                @if ($editingId === $role->id)
                <tr wire:key="edit-{{ $role->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td class="col-row-num" style="padding:6px 6px; text-align:center; white-space:nowrap; position:sticky; left:0; z-index:2; background:#F8F7FF;">
                        <span style="font-size:13px; color:#111827;">{{ $roles->firstItem() + $loop->index }}</span>
                    </td>
                    <td style="padding:7px 10px; text-align:left;">
                        @if ($role->name === 'admin')
                            <span style="font-size:13px; color:#111827; text-transform:capitalize;">admin</span>
                        @else
                            <input wire:model="editRoleName" type="text" placeholder="Nombre del rol"
                                   style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;">
                            @error('editRoleName') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                        @endif
                    </td>
                    <td style="padding:7px 10px; text-align:center;">
                        @if ($role->name === 'admin')
                            <span style="font-size:12px; color:#7B6FE8; font-weight:500;">Siempre activo</span>
                        @else
                            <select wire:model="editActivo"
                                    style="width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; background:#fff; box-sizing:border-box;">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        @endif
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                @php $selR = $selectedRoleId === $role->id; @endphp
                <tr wire:key="role-{{ $role->id }}"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s; background:{{ $selR ? '#F5F3FF' : '' }}; {{ $selR ? 'border-left:3px solid #7B6FE8;' : '' }}"
                    @mouseenter="$el.style.background='{{ $selR ? '#F5F3FF' : '#FAFAFE' }}'" @mouseleave="$el.style.background='{{ $selR ? '#F5F3FF' : '' }}'">

                    <td class="col-row-num" style="padding:6px 6px; text-align:center; position:sticky; left:0; z-index:2; background:{{ $selR ? '#F5F3FF' : '#fff' }};">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                            <input type="checkbox"
                                   :checked="$wire.selectedRoleId === {{ $role->id }}"
                                   @click="$wire.selectedRoleId === {{ $role->id }} ? $wire.set('selectedRoleId', null) : $wire.selectRole({{ $role->id }})"
                                   :disabled="{{ $editingId && $editingId !== $role->id ? 'true' : 'false' }}"
                                   style="accent-color:#7B6FE8; width:13px; height:13px; {{ $editingId && $editingId !== $role->id ? 'cursor:not-allowed; opacity:0.35;' : 'cursor:pointer;' }}">
                            <span style="font-size:13px; color:#111827;">{{ $roles->firstItem() + $loop->index }}</span>
                        </div>
                    </td>

                    <td style="padding:10px 14px; overflow:hidden; text-align:left;">
                        <span style="font-size:13px; color:#111827; text-transform:capitalize; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $role->name }}</span>
                    </td>

                    <td style="padding:10px 14px; text-align:center;">
                        @if ($role->name === 'admin')
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#EDE9FE; color:#7B6FE8;">Siempre activo</span>
                        @elseif ($role->activo ?? true)
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#D1FAE5; color:#059669;">Activo</span>
                        @else
                        <span style="padding:3px 10px; border-radius:6px; font-size:12px; font-weight:700; background:#FEE2E2; color:#EF4444;">Inactivo</span>
                        @endif
                    </td>

                </tr>
                @endif

                @empty
                <tr><td colspan="3" style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">{{ $colFilterNombre || $colFilterEstado ? 'Sin resultados para los filtros aplicados.' : 'No hay roles registrados.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($roles->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6; flex-shrink:0;">{{ $roles->links() }}</div>
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

{{-- ══ MODAL: Ver accesos ══ --}}
@if ($showViewModal)
@php
$vmColorMap = [
    'lavanda'   => ['head_bg'=>'#F5F3FF','head_border'=>'#EDE9FE','icon'=>'#7B6FE8'],
    'mint'      => ['head_bg'=>'#F0FDF4','head_border'=>'#D1FAE5','icon'=>'#059669'],
    'melocoton' => ['head_bg'=>'#FFF7ED','head_border'=>'#FED7AA','icon'=>'#EA580C'],
    'celeste'   => ['head_bg'=>'#EFF6FF','head_border'=>'#BFDBFE','icon'=>'#2563EB'],
];
@endphp
<div style="position:fixed; inset:0; z-index:60; display:flex; align-items:center; justify-content:center; padding:24px; background:rgba(0,0,0,.45);"
     wire:click.self="closeView">
    <div style="background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(0,0,0,.18); width:100%; max-width:580px; height:80vh; display:flex; flex-direction:column;">

        {{-- Header --}}
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <p style="font-size:10px; color:#9CA3AF; font-weight:600; text-transform:uppercase; letter-spacing:.7px; margin:0 0 2px;">Resumen de accesos</p>
                <p style="font-size:16px; font-weight:800; color:#7B6FE8; margin:0; text-transform:capitalize;">{{ $viewRoleName }}</p>
            </div>
            <button wire:click="closeView"
                    style="width:32px; height:32px; border-radius:9px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div style="height:calc(80vh - 130px); overflow-y:auto; padding:16px;">
            @forelse ($viewData as $mod)
            @php $vmc = $vmColorMap[$mod['color']] ?? $vmColorMap['lavanda']; @endphp
            <div style="border-radius:12px; border:1px solid {{ $vmc['head_border'] }}; overflow:hidden; margin-bottom:10px;">

                {{-- Cabecera módulo --}}
                <div style="background:{{ $vmc['head_bg'] }}; padding:9px 14px; display:flex; align-items:center; gap:8px;">
                    <svg width="13" height="13" fill="none" stroke="{{ $vmc['icon'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="{{ $mod['icon'] }}"/>
                    </svg>
                    <span style="font-size:12px; font-weight:700; color:#111827; text-transform:uppercase; letter-spacing:.4px;">{{ $mod['name'] }}</span>
                </div>

                {{-- Solo accesos habilitados --}}
                <div>
                    @foreach ($mod['submodulos'] as $sub)
                    @if ($sub['tipo'] === 'group')
                    <div style="padding:8px 14px 6px; background:#FAFAFA; border-top:1px solid #F3F4F6;">
                        <p style="font-size:10px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.6px; margin:0 0 5px;">{{ $sub['name'] }}</p>
                        @foreach ($sub['children'] as $child)
                        <div style="display:flex; align-items:center; gap:7px; padding:4px 0 4px 8px;">
                            <svg width="11" height="11" fill="none" stroke="#10B981" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span style="font-size:12px; color:#374151;">{{ $child['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div style="display:flex; align-items:center; gap:7px; padding:8px 14px; border-top:1px solid #F9FAFB;">
                        <svg width="11" height="11" fill="none" stroke="#10B981" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span style="font-size:12px; color:#374151;">{{ $sub['name'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @empty
            <p style="text-align:center; padding:32px; color:#9CA3AF; font-size:13px; font-style:italic;">Este rol no tiene accesos habilitados.</p>
            @endforelse
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px; border-top:1px solid #F3F4F6; flex-shrink:0; display:flex; justify-content:flex-end;">
            <button wire:click="closeView"
                    style="height:36px; padding:0 24px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; transition:background .15s, color .15s;"
                    onmouseenter="this.style.background='#7B6FE8'; this.style.color='#fff';" onmouseleave="this.style.background='#F8F7FF'; this.style.color='#7B6FE8';">
                Cerrar
            </button>
        </div>
    </div>
</div>
@endif

@if (session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
     class="fixed bottom-20 sm:bottom-5 right-5 z-50"
     style="background:#EF4444; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-20 sm:bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

</div>
