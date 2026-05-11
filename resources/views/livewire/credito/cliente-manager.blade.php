<div>

<div class="ds-section-header">
    <div style="width:38px;height:38px;background:#E8F0F7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="#6D8196" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
    </div>
    <div style="flex:1;">
        <h2>Gestión de Clientes</h2>
        <p>Registros de clientes del sistema</p>
    </div>
</div>

<div class="ds-table-card">

    {{-- ── Toolbar ── --}}
    <div class="ds-table-toolbar" style="flex-wrap:wrap;gap:8px;">
        <div style="position:relative;flex:1;min-width:200px;max-width:320px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="#CBCBCB" stroke-width="2" stroke-linecap="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Buscar por CI, ID_LN, nombre o apellido…"
                   style="padding-left:32px;width:100%;">
        </div>
        <select wire:model.live="filterCiudad" style="border:1px solid #CBCBCB;border-radius:6px;padding:6px 10px;font-size:12px;background:#FFFFE3;color:#4A4A4A;outline:none;">
            <option value="">Todas las ciudades</option>
            @foreach ($ciudades as $ciu)
                <option value="{{ $ciu }}">{{ $ciu }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterActivo" style="border:1px solid #CBCBCB;border-radius:6px;padding:6px 10px;font-size:12px;background:#FFFFE3;color:#4A4A4A;outline:none;">
            <option value="">Todos los estados</option>
            <option value="1">Activos</option>
            <option value="0">Inactivos</option>
        </select>
        @if (!$showAddForm && !$editingId)
        <button wire:click="showAdd" class="ds-btn ds-btn-primary ds-btn-sm">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo cliente
        </button>
        @endif
    </div>

    {{-- ── Fila agregar ── --}}
    @if ($showAddForm)
    <div class="ds-confirm-panel neutral" style="margin:0;border-radius:0;border-left:0;border-right:0;border-top:0;">
        <p class="ds-confirm-panel-title">Nuevo cliente</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-bottom:12px;">
            <div>
                <label class="ds-form-label">CI * <span style="color:#CBCBCB;font-weight:400;">(usuario de acceso)</span></label>
                <input wire:model="newCi" type="text" maxlength="20" placeholder="12345678" style="width:100%;">
                @error('newCi') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Nombre *</label>
                <input wire:model="newNombre" type="text" maxlength="120" style="width:100%;">
                @error('newNombre') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Apellido *</label>
                <input wire:model="newApellido" type="text" maxlength="120" style="width:100%;">
                @error('newApellido') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Teléfono * <span style="color:#CBCBCB;font-weight:400;">(contraseña inicial)</span></label>
                <input wire:model="newTelefono" type="text" maxlength="30" style="width:100%;">
                @error('newTelefono') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">NIT</label>
                <input wire:model="newNit" type="text" maxlength="30" style="width:100%;">
            </div>
            <div>
                <label class="ds-form-label">Correo</label>
                <input wire:model="newCorreo" type="email" maxlength="191" style="width:100%;">
                @error('newCorreo') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Ciudad *</label>
                <select wire:model.live="newCiudad" style="width:100%;">
                    <option value="">-- Seleccionar --</option>
                    @foreach($ciudadesAll as $c)
                    <option value="{{ $c->nombre }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('newCiudad') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Provincia *</label>
                <select wire:model.live="newProvincia" style="width:100%;" @disabled(!$newCiudad)>
                    <option value="">-- Seleccionar --</option>
                    @foreach($newProvincias as $p)
                    <option value="{{ $p->nombre }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('newProvincia') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Municipio *</label>
                <select wire:model.live="newMunicipio" style="width:100%;" @disabled(!$newProvincia)>
                    <option value="">-- Seleccionar --</option>
                    @foreach($newMunicipios as $m)
                    <option value="{{ $m->nombre }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
                @error('newMunicipio') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Dirección *</label>
                <input wire:model="newDireccion" type="text" maxlength="255" style="width:100%;">
                @error('newDireccion') <p class="ds-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ds-form-label">Vendedor</label>
                <select wire:model="newVendedorId" style="width:100%;">
                    <option value="">Sin asignar</option>
                    @foreach ($vendedores as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="ds-form-label">Activo</label>
                <div style="display:flex;align-items:center;height:34px;">
                    <input wire:model="newActive" type="checkbox" style="width:16px;height:16px;border-radius:4px;accent-color:#6D8196;">
                </div>
            </div>
        </div>
        <div class="ds-confirm-panel-actions">
            <button wire:click="cancelAdd" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
            <button wire:click="saveNew" class="ds-btn ds-btn-primary ds-btn-sm">Guardar</button>
        </div>
    </div>
    @endif

    {{-- ── Tabla ── --}}
    @if ($clientes->isEmpty() && !$showAddForm)
    <div class="ds-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
        <p>No hay clientes registrados</p>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table style="min-width:700px;">
            <thead>
                <tr>
                    <th>ID_LN</th>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Vendedor</th>
                    <th style="text-align:center;">Estado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)

                {{-- ── Fila edición inline ── --}}
                @if ($editingId === $c->id)
                <tr wire:key="edit-{{ $c->id }}">
                    <td colspan="9" style="padding:0;">
                        <div class="ds-confirm-panel neutral" style="margin:0;border-radius:0;border-left:0;border-right:0;">
                            <p class="ds-confirm-panel-title">Editar cliente: {{ $c->usuario->name ?? $c->ci }}</p>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-bottom:12px;">
                                <div>
                                    <label class="ds-form-label">CI * <span style="color:#CBCBCB;font-weight:400;">(actualiza usuario de acceso)</span></label>
                                    <input wire:model="editCi" type="text" maxlength="20" style="width:100%;">
                                    @error('editCi') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Nombre *</label>
                                    <input wire:model="editNombre" type="text" maxlength="120" style="width:100%;">
                                    @error('editNombre') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Apellido *</label>
                                    <input wire:model="editApellido" type="text" maxlength="120" style="width:100%;">
                                    @error('editApellido') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Teléfono *</label>
                                    <input wire:model="editTelefono" type="text" maxlength="30" style="width:100%;">
                                    @error('editTelefono') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">NIT</label>
                                    <input wire:model="editNit" type="text" maxlength="30" style="width:100%;">
                                </div>
                                <div>
                                    <label class="ds-form-label">Correo</label>
                                    <input wire:model="editCorreo" type="email" maxlength="191" style="width:100%;">
                                    @error('editCorreo') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Ciudad *</label>
                                    <select wire:model.live="editCiudad" style="width:100%;">
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($ciudadesAll as $ciudad)
                                        <option value="{{ $ciudad->nombre }}">{{ $ciudad->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editCiudad') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Provincia *</label>
                                    <select wire:model.live="editProvincia" style="width:100%;" @disabled(!$editCiudad)>
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($editProvincias as $prov)
                                        <option value="{{ $prov->nombre }}">{{ $prov->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editProvincia') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Municipio *</label>
                                    <select wire:model.live="editMunicipio" style="width:100%;" @disabled(!$editProvincia)>
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($editMunicipios as $mun)
                                        <option value="{{ $mun->nombre }}">{{ $mun->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('editMunicipio') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Dirección *</label>
                                    <input wire:model="editDireccion" type="text" maxlength="255" style="width:100%;">
                                    @error('editDireccion') <p class="ds-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="ds-form-label">Vendedor</label>
                                    <select wire:model="editVendedorId" style="width:100%;">
                                        <option value="">Sin asignar</option>
                                        @foreach ($vendedores as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="ds-form-label">Activo</label>
                                    <div style="display:flex;align-items:center;height:34px;">
                                        <input wire:model="editActive" type="checkbox" style="width:16px;height:16px;border-radius:4px;accent-color:#6D8196;">
                                    </div>
                                </div>
                            </div>
                            <div class="ds-confirm-panel-actions">
                                <button wire:click="cancelEdit" class="ds-btn ds-btn-secondary ds-btn-sm">Cancelar</button>
                                <button wire:click="saveEdit" class="ds-btn ds-btn-primary ds-btn-sm">Guardar</button>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ── Fila normal ── --}}
                @else
                <tr wire:key="row-{{ $c->id }}" x-data="{ modal: false }">
                    <td data-label="ID_LN" style="font-family:monospace;font-size:11px;color:#6D8196;">{{ $c->id_ln ?? '—' }}</td>
                    <td data-label="CI" style="font-family:monospace;font-size:12px;color:#4A4A4A;">{{ $c->ci }}</td>
                    <td data-label="Nombre" style="font-weight:600;color:#4A4A4A;">{{ $c->usuario->name ?? '—' }}</td>
                    <td data-label="Apellido" style="color:#6D8196;">{{ $c->apellido ?? '—' }}</td>
                    <td data-label="Teléfono" style="color:#6D8196;">{{ $c->telefono }}</td>
                    <td data-label="Ciudad" style="color:#CBCBCB;font-size:12px;">{{ $c->ciudad }}</td>
                    <td data-label="Vendedor" style="color:#CBCBCB;font-size:12px;">{{ $c->vendedorUsuario->name ?? '—' }}</td>
                    <td data-label="Estado" style="text-align:center;">
                        @if ($c->active)
                            <span class="ds-badge ds-badge-active">Activo</span>
                        @else
                            <span class="ds-badge ds-badge-inactive">Inactivo</span>
                        @endif
                    </td>
                    <td data-label="" style="text-align:right;">
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                            <button @click="modal = true" title="Ver cliente" class="ds-btn ds-btn-ghost ds-btn-sm">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleActivo({{ $c->id }})" title="{{ $c->active ? 'Desactivar' : 'Activar' }}"
                                    class="ds-btn ds-btn-ghost ds-btn-sm" style="{{ !$c->active ? 'color:#CBCBCB;' : '' }}">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="{{ $c->active ? 'M5.636 18.364a9 9 0 010-12.728M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' : 'M18.364 5.636a9 9 0 010 12.728M12 21v-1m0-16V3m-9 9h1m16 0h1M5.636 5.636l.707.707M18.364 18.364l.707.707m0-13.435l-.707.707M5.636 18.364l-.707.707M8 12a4 4 0 118 0 4 4 0 01-8 0z' }}"/>
                                </svg>
                            </button>
                            <button wire:click="startEdit({{ $c->id }})" title="Editar" class="ds-btn ds-btn-ghost ds-btn-sm">
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
                                        <div><p style="{{ $lClr }}">Correo</p><p style="{{ $vClr }}" style="word-break:break-all;">{{ $c->correo ?: '—' }}</p></div>
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
                                        <div><p style="{{ $lClr }}">Dirección</p><p style="{{ $vClr }}" style="word-break:break-word;">{{ $c->direccion ?: '—' }}</p></div>
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
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:10px 16px;border-top:1px solid #CBCBCB;">
        {{ $clientes->links() }}
    </div>
    @endif
</div>

</div>
