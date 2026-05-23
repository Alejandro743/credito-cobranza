<div>

{{-- Flash --}}
@if (session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-5 right-5 z-50"
     style="background:#10B981; color:#fff; font-size:13px; font-weight:600; padding:12px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif

{{-- ══ TOOLBAR ══ --}}
@if(!$showAddForm && !$editingId)
<div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 sm:gap-2.5 mb-5">

    <div class="relative w-full sm:flex-1" style="min-width:0; max-width:100%;">
        <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por CI, ID_LN, nombre o apellido…"
               style="width:100%; height:36px; padding:0 12px 0 30px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; outline:none; box-sizing:border-box; background:#fff;">
    </div>

    <select wire:model.live="filterCiudad" class="w-full sm:flex-1"
            style="height:36px; padding:0 12px; border:1px solid #E5E7EB; border-radius:9px; font-size:13px; background:#fff; outline:none; color:#374151; cursor:pointer; box-sizing:border-box;">
        <option value="">Todas las ciudades</option>
        @foreach ($ciudades as $ciu)
            <option value="{{ $ciu }}">{{ $ciu }}</option>
        @endforeach
    </select>

    <select wire:model.live="filterActivo" class="w-full sm:flex-1"
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
        Nuevo cliente
    </button>
</div>
@endif

{{-- ══ FORM: Nuevo cliente ══ --}}
@if ($showAddForm)
@php $iF = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;'; @endphp
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo cliente
        </span>
        <button wire:click="cancelAdd"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; margin-bottom:14px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">CI * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(usuario de acceso)</span></label>
                <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678" style="{{ $iF }}">
                @error('newCi') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="newNombre" type="text" maxlength="120" style="{{ $iF }}">
                @error('newNombre') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Apellido *</label>
                <input wire:model="newApellido" type="text" maxlength="120" style="{{ $iF }}">
                @error('newApellido') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Teléfono * <span style="color:#CBCBCB; font-weight:400; text-transform:none;">(contraseña inicial)</span></label>
                <input wire:model="newTelefono" type="text" maxlength="30" style="{{ $iF }}">
                @error('newTelefono') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">NIT</label>
                <input wire:model="newNit" type="text" maxlength="30" style="{{ $iF }}">
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Correo</label>
                <input wire:model="newCorreo" type="email" maxlength="191" style="{{ $iF }}">
                @error('newCorreo') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Ciudad *</label>
                <select wire:model.live="newCiudad" style="{{ $iF }} cursor:pointer;">
                    <option value="">— Seleccionar —</option>
                    @foreach($ciudadesAll as $c)
                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('newCiudad') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Provincia *</label>
                <select wire:model.live="newProvincia" style="{{ $iF }} cursor:pointer;" @disabled(!$newCiudad)>
                    <option value="">— Seleccionar —</option>
                    @foreach($newProvincias as $p)
                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('newProvincia') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Municipio *</label>
                <select wire:model.live="newMunicipio" style="{{ $iF }} cursor:pointer;" @disabled(!$newProvincia)>
                    <option value="">— Seleccionar —</option>
                    @foreach($newMunicipios as $m)
                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
                @error('newMunicipio') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Dirección *</label>
                <input wire:model="newDireccion" type="text" maxlength="255" style="{{ $iF }}">
                @error('newDireccion') <p style="color:#EF4444; font-size:11px; margin-top:3px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Vendedor</label>
                <select wire:model="newVendedorId" style="{{ $iF }} cursor:pointer;">
                    <option value="">Sin asignar</option>
                    @foreach ($vendedores as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
                <select wire:model="newActive" style="{{ $iF }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>
        <div style="display:flex; gap:8px; padding-top:12px; border-top:1px solid #F3F4F6;">
            <button wire:click="saveNew"
                    style="height:38px; padding:0 28px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                Guardar
            </button>
            <button wire:click="cancelAdd"
                    style="height:38px; padding:0 20px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══ MOBILE: Cards ══ --}}
@php
$iM = 'height:36px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box; width:100%;';
@endphp
<div class="sm:hidden" style="display:flex; flex-direction:column; gap:10px;">
    @forelse ($clientes as $c)

    @if($editingId === $c->id)
    {{-- Card edición --}}
    <div wire:key="card-edit-{{ $c->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #EDE9FE; border-left:3px solid #7B6FE8; box-shadow:0 2px 8px rgba(123,111,232,.1); overflow:hidden;">
        <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:10px 14px;">
            <p style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0;">Editando: {{ $c->usuario->name ?? $c->ci }}</p>
        </div>
        <div style="padding:14px; display:flex; flex-direction:column; gap:10px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">CI *</label>
                    <input wire:model="editCi" type="text" maxlength="20" style="{{ $iM }}">
                    @error('editCi')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Nombre *</label>
                    <input wire:model="editNombre" type="text" style="{{ $iM }}">
                    @error('editNombre')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Apellido *</label>
                    <input wire:model="editApellido" type="text" style="{{ $iM }}">
                    @error('editApellido')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Teléfono *</label>
                    <input wire:model="editTelefono" type="text" style="{{ $iM }}">
                    @error('editTelefono')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">NIT</label>
                    <input wire:model="editNit" type="text" style="{{ $iM }}">
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Correo</label>
                    <input wire:model="editCorreo" type="email" style="{{ $iM }}">
                    @error('editCorreo')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Ciudad *</label>
                    <select wire:model.live="editCiudad" style="{{ $iM }} cursor:pointer;">
                        <option value="">— Seleccionar —</option>
                        @foreach($ciudadesAll as $ciudad)
                        <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editCiudad')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Provincia *</label>
                    <select wire:model.live="editProvincia" style="{{ $iM }} cursor:pointer;" @disabled(!$editCiudad)>
                        <option value="">— Seleccionar —</option>
                        @foreach($editProvincias as $prov)
                        <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editProvincia')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Municipio *</label>
                    <select wire:model.live="editMunicipio" style="{{ $iM }} cursor:pointer;" @disabled(!$editProvincia)>
                        <option value="">— Seleccionar —</option>
                        @foreach($editMunicipios as $mun)
                        <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                        @endforeach
                    </select>
                    @error('editMunicipio')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Estado</label>
                    <select wire:model="editActive" style="{{ $iM }} cursor:pointer;">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Dirección *</label>
                <input wire:model="editDireccion" type="text" maxlength="255" style="{{ $iM }}">
                @error('editDireccion')<p style="font-size:11px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#7B6FE8; margin-bottom:4px;">Vendedor</label>
                <select wire:model="editVendedorId" style="{{ $iM }} cursor:pointer;">
                    <option value="">Sin asignar</option>
                    @foreach ($vendedores as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:8px; padding-top:4px; border-top:1px solid #F3F4F6;">
                <button wire:click="saveEdit" style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">Guardar</button>
                <button wire:click="cancelEdit" style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            </div>
        </div>
    </div>

    @else
    {{-- Card normal --}}
    <div wire:key="card-{{ $c->id }}" x-data="{ modal: false }"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden;">

        {{-- Header --}}
        <div style="padding:12px 14px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #F3F4F6;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ strtoupper(mb_substr($c->usuario->name ?? $c->ci, 0, 1)) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $c->usuario->name ?? '—' }} {{ $c->apellido }}</p>
                <p style="font-size:12px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $c->ci }}</p>
            </div>
            <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; flex-shrink:0;
                         background:{{ $c->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $c->active ? '#059669' : '#9CA3AF' }};">
                {{ $c->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Info --}}
        <div style="padding:10px 14px; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <span style="font-size:11px; font-weight:700; color:#7B6FE8; font-family:monospace;">{{ $c->id_ln ?? '—' }}</span>
            <span style="font-size:11px; color:#D1D5DB;">|</span>
            <span style="font-size:12px; color:#6B7280;">{{ $c->ciudad }}</span>
            @if($c->vendedorUsuario)
            <span style="font-size:11px; color:#D1D5DB;">|</span>
            <span style="font-size:12px; color:#6B7280;">{{ $c->vendedorUsuario->name }}</span>
            @endif
        </div>

        {{-- Acciones --}}
        <div style="padding:10px 14px; border-top:1px solid #F3F4F6; display:flex; gap:8px;">
            <button @click="modal = true"
                    style="flex:1; height:34px; border:1px solid #E5E7EB; border-radius:8px; background:#F9FAFB; color:#6B7280; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Ver
            </button>
            <button wire:click="startEdit({{ $c->id }})"
                    style="flex:1; height:34px; border:1px solid #EDE9FE; border-radius:8px; background:#F8F7FF; color:#7B6FE8; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:5px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>

        {{-- Modal Ver Cliente --}}
        <template x-teleport="body">
        <div x-show="modal"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(20,10,40,0.4);"
             @click.self="modal = false">
            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 style="background:#FFFFE3;border:1px solid #CBCBCB;border-radius:8px;width:100%;max-width:440px;overflow:hidden;position:relative;max-height:90vh;overflow-y:auto;">
                <button @click="modal = false"
                        style="position:absolute;top:12px;right:12px;width:26px;height:26px;border-radius:6px;background:#fff;border:1px solid #CBCBCB;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:1;">
                    <svg width="11" height="11" fill="none" stroke="#6D8196" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div style="padding:18px 16px 16px;">
                    @php
                    $fSty = 'background:#fff;border:1px solid #CBCBCB;border-radius:6px;padding:8px 10px;display:flex;align-items:center;gap:8px;';
                    $iBox = 'width:30px;height:30px;border-radius:6px;background:#E8F0F7;display:flex;align-items:center;justify-content:center;flex-shrink:0;';
                    $vClr = 'font-size:11px;font-weight:700;color:#4A4A4A;margin:0;';
                    $lClr = 'font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#CBCBCB;margin-bottom:1px;';
                    @endphp
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;padding-right:36px;">
                        <span style="font-size:13px;font-weight:700;color:#4A4A4A;">Datos del Cliente</span>
                        <div style="flex:1;height:1px;background:#CBCBCB;"></div>
                        <span class="ds-badge {{ $c->active ? 'ds-badge-active' : 'ds-badge-inactive' }}">{{ $c->active ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                            <div style="min-width:0;"><p style="{{ $lClr }}">Nombre</p><p style="{{ $vClr }}">{{ $c->usuario->name ?? '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div>
                            <div><p style="{{ $lClr }}">CI</p><p style="{{ $vClr }} font-family:monospace;">{{ $c->ci }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                            <div><p style="{{ $lClr }}">Apellido</p><p style="{{ $vClr }}">{{ $c->apellido ?: '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                            <div><p style="{{ $lClr }}">Teléfono</p><p style="{{ $vClr }}">{{ $c->telefono ?: '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                            <div><p style="{{ $lClr }}">Correo</p><p style="{{ $vClr }}">{{ $c->correo ?: '—' }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                            <div><p style="{{ $lClr }}">NIT</p><p style="{{ $vClr }}">{{ $c->nit ?: '—' }}</p></div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;margin:8px 0 6px;">
                        <span style="font-size:11px;font-weight:700;color:#6D8196;white-space:nowrap;">Dirección</span>
                        <div style="flex:1;height:1px;background:#CBCBCB;"></div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <div><p style="{{ $lClr }}">Ciudad</p><p style="{{ $vClr }}">{{ strtoupper($c->ciudad ?: '—') }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <div><p style="{{ $lClr }}">Provincia</p><p style="{{ $vClr }}">{{ strtoupper($c->provincia ?: '—') }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                            <div><p style="{{ $lClr }}">Municipio</p><p style="{{ $vClr }}">{{ strtoupper($c->municipio ?: '—') }}</p></div>
                        </div>
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                            <div><p style="{{ $lClr }}">Dirección</p><p style="{{ $vClr }}">{{ $c->direccion ?: '—' }}</p></div>
                        </div>
                    </div>
                    @if($c->vendedorUsuario)
                    <div style="margin-top:6px;">
                        <div style="{{ $fSty }}">
                            <div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                            <div><p style="{{ $lClr }}">Vendedor</p><p style="{{ $vClr }}">{{ $c->vendedorUsuario->name }}</p></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        </template>
    </div>
    @endif

    @empty
    <p style="text-align:center; padding:48px; color:#9CA3AF; font-size:13px;">No hay clientes registrados.</p>
    @endforelse
    @if ($clientes->hasPages())
    <div style="padding-top:8px;">{{ $clientes->links() }}</div>
    @endif
</div>

{{-- ══ DESKTOP: Tabla ══ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden;">

    {{-- Barra --}}
    <div style="padding:10px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #F3F4F6;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; font-weight:700; color:#111827;">Clientes registrados</span>
            <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $clientes->total() }}</span>
        </div>
        <button type="button" wire:click="$refresh"
                style="height:30px; padding:0 10px; border:1px solid #E5E7EB; border-radius:7px; background:#fff; color:#6B7280; cursor:pointer; display:flex; align-items:center; gap:4px; font-size:12px; font-weight:500; box-sizing:border-box;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Actualizar
        </button>
    </div>

    <div style="overflow-x:auto;">
        @if ($clientes->isEmpty() && !$showAddForm)
        <p style="text-align:center; padding:64px; color:#9CA3AF; font-size:13px;">No hay clientes registrados.</p>
        @else
        <table style="table-layout:fixed; width:100%; min-width:900px; border-collapse:collapse; font-size:13px;">
            <colgroup>
                <col style="width:90px;">   {{-- ID_LN --}}
                <col style="width:110px;">  {{-- CI --}}
                <col style="width:150px;">  {{-- Nombre --}}
                <col style="width:130px;">  {{-- Apellido --}}
                <col style="width:110px;">  {{-- Teléfono --}}
                <col style="width:110px;">  {{-- Ciudad --}}
                <col style="width:140px;">  {{-- Vendedor --}}
                <col style="width:90px;">   {{-- Estado --}}
                <col style="width:110px;">  {{-- Acciones --}}
            </colgroup>
            <thead>
                <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                    @foreach(['ID_LN','CI','Nombre','Apellido','Teléfono','Ciudad','Vendedor','Estado','Acciones'] as $col)
                    <th style="padding:10px 16px; text-align:{{ $col === 'Acciones' ? 'center' : 'left' }}; position:relative; user-select:none; overflow:hidden; min-width:60px;">
                        <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">{{ $col }}</span>
                        @if($col !== 'Acciones')
                        <div x-data="colResize()" @mousedown="start($event)"
                             style="position:absolute; right:0; top:0; bottom:0; width:4px; cursor:col-resize;"
                             @mouseenter="$el.style.background='rgba(123,111,232,.3)'" @mouseleave="$el.style.background='transparent'"></div>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)

                {{-- Fila edición inline --}}
                @if ($editingId === $c->id)
                @php $iE = 'width:100%; height:30px; border:1px solid #D8D3F8; border-radius:7px; padding:0 8px; font-size:12px; outline:none; box-sizing:border-box; background:#fff;'; @endphp
                <tr wire:key="edit-{{ $c->id }}" style="background:#F8F7FF; border-bottom:1px solid #EDE9FE;">
                    <td colspan="9" style="padding:0;">
                        <div style="padding:14px 18px;">
                            <p style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin:0 0 12px;">Editando: {{ $c->usuario->name ?? $c->ci }}</p>
                            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px; margin-bottom:12px;">
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">CI *</label>
                                    <input wire:model="editCi" type="text" maxlength="20" style="{{ $iE }}">
                                    @error('editCi') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Nombre *</label>
                                    <input wire:model="editNombre" type="text" style="{{ $iE }}">
                                    @error('editNombre') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Apellido *</label>
                                    <input wire:model="editApellido" type="text" style="{{ $iE }}">
                                    @error('editApellido') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Teléfono *</label>
                                    <input wire:model="editTelefono" type="text" style="{{ $iE }}">
                                    @error('editTelefono') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">NIT</label>
                                    <input wire:model="editNit" type="text" style="{{ $iE }}">
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Correo</label>
                                    <input wire:model="editCorreo" type="email" style="{{ $iE }}">
                                    @error('editCorreo') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Ciudad *</label>
                                    <select wire:model.live="editCiudad" style="{{ $iE }} cursor:pointer; height:30px; padding:0 8px;">
                                        <option value="">— Ciudad —</option>
                                        @foreach($ciudadesAll as $ciudad)
                                        <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editCiudad') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Provincia *</label>
                                    <select wire:model.live="editProvincia" style="{{ $iE }} cursor:pointer; height:30px; padding:0 8px;" @disabled(!$editCiudad)>
                                        <option value="">— Provincia —</option>
                                        @foreach($editProvincias as $prov)
                                        <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editProvincia') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Municipio *</label>
                                    <select wire:model.live="editMunicipio" style="{{ $iE }} cursor:pointer; height:30px; padding:0 8px;" @disabled(!$editProvincia)>
                                        <option value="">— Municipio —</option>
                                        @foreach($editMunicipios as $mun)
                                        <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editMunicipio') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Dirección *</label>
                                    <input wire:model="editDireccion" type="text" maxlength="255" style="{{ $iE }}">
                                    @error('editDireccion') <p style="color:#EF4444; font-size:10px; margin-top:2px;">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Vendedor</label>
                                    <select wire:model="editVendedorId" style="{{ $iE }} cursor:pointer; height:30px; padding:0 8px;">
                                        <option value="">Sin asignar</option>
                                        @foreach ($vendedores as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Estado</label>
                                    <select wire:model="editActive" style="{{ $iE }} cursor:pointer; height:30px; padding:0 8px;">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div style="display:flex; gap:8px; padding-top:10px; border-top:1px solid #EDE9FE;">
                                <button wire:click="saveEdit"
                                        style="height:32px; padding:0 20px; background:#7B6FE8; color:#fff; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">
                                    Guardar
                                </button>
                                <button wire:click="cancelEdit"
                                        style="height:32px; padding:0 14px; background:#F3F4F6; color:#6B7280; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer;">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- Fila normal --}}
                @else
                <tr wire:key="c-{{ $c->id }}" x-data="{ modal: false }"
                    style="border-bottom:1px solid #F9FAFB; transition:background .1s;"
                    @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">

                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:11px; font-family:monospace; font-weight:700; color:#7B6FE8; white-space:nowrap;">{{ $c->id_ln ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; font-family:monospace; color:#374151; white-space:nowrap;">{{ $c->ci }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; font-weight:500; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->usuario->name ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:13px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->apellido ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->telefono }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap;">{{ $c->ciudad }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="font-size:12px; color:#6B7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $c->vendedorUsuario->name ?? '—' }}</span>
                    </td>
                    <td style="padding:10px 16px; overflow:hidden;">
                        <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; white-space:nowrap;
                                     background:{{ $c->active ? '#D1FAE5' : '#F3F4F6' }};
                                     color:{{ $c->active ? '#059669' : '#9CA3AF' }};">
                            {{ $c->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="padding:10px 16px; text-align:center;">
                        <div style="display:inline-flex; align-items:center; justify-content:center; gap:4px;">
                            <button @click="modal = true" title="Ver detalle"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #E5E7EB; background:#F9FAFB; color:#6B7280; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#F9FAFB'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleActivo({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #E5E7EB; background:#F9FAFB; color:{{ $c->active ? '#6B7280' : '#D1D5DB' }}; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#F9FAFB'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="{{ $c->active ? 'M5.636 18.364a9 9 0 010-12.728M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M18.364 5.636a9 9 0 010 12.728M12 21v-1m0-16V3m-9 9h1m16 0h1M5.636 5.636l.707.707M18.364 18.364l.707.707m0-13.435l-.707.707M5.636 18.364l-.707.707M8 12a4 4 0 118 0 4 4 0 01-8 0z' }}"/>
                                </svg>
                            </button>
                            <button wire:click="startEdit({{ $c->id }})" title="Editar"
                                    style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </td>

                    {{-- Modal Ver Cliente --}}
                    <template x-teleport="body">
                    <div x-show="modal"
                         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         style="position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(20,10,40,0.4);"
                         @click.self="modal = false">
                        <div x-show="modal"
                             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             style="background:#FFFFE3;border:1px solid #CBCBCB;border-radius:8px;width:100%;max-width:440px;overflow:hidden;position:relative;max-height:90vh;overflow-y:auto;">
                            <button @click="modal = false"
                                    style="position:absolute;top:12px;right:12px;width:26px;height:26px;border-radius:6px;background:#fff;border:1px solid #CBCBCB;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:1;">
                                <svg width="11" height="11" fill="none" stroke="#6D8196" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div style="padding:18px 16px 16px;">
                                @php
                                $fSty = 'background:#fff;border:1px solid #CBCBCB;border-radius:6px;padding:8px 10px;display:flex;align-items:center;gap:8px;';
                                $iBox = 'width:30px;height:30px;border-radius:6px;background:#E8F0F7;display:flex;align-items:center;justify-content:center;flex-shrink:0;';
                                $vClr = 'font-size:11px;font-weight:700;color:#4A4A4A;margin:0;';
                                $lClr = 'font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#CBCBCB;margin-bottom:1px;';
                                @endphp
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;padding-right:36px;">
                                    <span style="font-size:13px;font-weight:700;color:#4A4A4A;">Datos del Cliente</span>
                                    <div style="flex:1;height:1px;background:#CBCBCB;"></div>
                                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;background:{{ $c->active ? '#D1FAE5' : '#F3F4F6' }};color:{{ $c->active ? '#059669' : '#9CA3AF' }};">{{ $c->active ? 'Activo' : 'Inactivo' }}</span>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div style="min-width:0;"><p style="{{ $lClr }}">Nombre</p><p style="{{ $vClr }}">{{ $c->usuario->name ?? '—' }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg></div><div><p style="{{ $lClr }}">CI</p><p style="{{ $vClr }} font-family:monospace;">{{ $c->ci }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div><p style="{{ $lClr }}">Apellido</p><p style="{{ $vClr }}">{{ $c->apellido ?: '—' }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div><div><p style="{{ $lClr }}">Teléfono</p><p style="{{ $vClr }}">{{ $c->telefono ?: '—' }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><div><p style="{{ $lClr }}">Correo</p><p style="{{ $vClr }}">{{ $c->correo ?: '—' }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div><p style="{{ $lClr }}">NIT</p><p style="{{ $vClr }}">{{ $c->nit ?: '—' }}</p></div></div>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;margin:8px 0 6px;"><span style="font-size:11px;font-weight:700;color:#6D8196;white-space:nowrap;">Dirección</span><div style="flex:1;height:1px;background:#CBCBCB;"></div></div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div><p style="{{ $lClr }}">Ciudad</p><p style="{{ $vClr }}">{{ strtoupper($c->ciudad ?: '—') }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div><p style="{{ $lClr }}">Provincia</p><p style="{{ $vClr }}">{{ strtoupper($c->provincia ?: '—') }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><div><p style="{{ $lClr }}">Municipio</p><p style="{{ $vClr }}">{{ strtoupper($c->municipio ?: '—') }}</p></div></div>
                                    <div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div><div><p style="{{ $lClr }}">Dirección</p><p style="{{ $vClr }}">{{ $c->direccion ?: '—' }}</p></div></div>
                                </div>
                                @if($c->vendedorUsuario)
                                <div style="margin-top:6px;"><div style="{{ $fSty }}"><div style="{{ $iBox }}"><svg width="13" height="13" fill="none" stroke="#6D8196" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div><div><p style="{{ $lClr }}">Vendedor</p><p style="{{ $vClr }}">{{ $c->vendedorUsuario->name }}</p></div></div></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    </template>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if ($clientes->hasPages())
    <div style="padding:10px 18px; border-top:1px solid #F3F4F6;">{{ $clientes->links() }}</div>
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
