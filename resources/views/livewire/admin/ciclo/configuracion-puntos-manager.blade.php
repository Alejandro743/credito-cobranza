<div>

@php
$iRow = 'height:34px; font-size:13px; border:1px solid #D1D5DB; border-radius:6px; padding:0 8px; background:#fff; width:100%; box-sizing:border-box;';
@endphp

{{-- Toast success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#10B981;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.15);">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     style="position:fixed;bottom:20px;right:20px;z-index:50;background:#EF4444;color:#fff;font-size:13px;font-weight:600;padding:10px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.15);">
    {{ session('error') }}
</div>
@endif

{{-- Toolbar --}}
<div style="display:flex;gap:10px;margin-bottom:16px;align-items:center;">
    <div style="position:relative;flex:1;">
        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:#9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o código de ciclo..."
               style="width:100%;height:36px;font-size:13px;border:1px solid #D1D5DB;border-radius:8px;padding:0 12px 0 34px;box-sizing:border-box;outline:none;">
    </div>
    <select wire:model.live="filterStatus"
            style="height:36px;font-size:13px;border:1px solid #D1D5DB;border-radius:8px;padding:0 10px;background:#fff;outline:none;">
        <option value="">Todos los estados</option>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select>
    @if($ciclosDisponibles->isNotEmpty() && !$showAddForm)
    <button wire:click="showAdd"
            style="height:36px;font-size:13px;font-weight:600;background:#7C3AED;color:#fff;border:none;border-radius:8px;padding:0 16px;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;">
        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Config.
    </button>
    @endif
</div>

{{-- Panel nuevo --}}
@if($showAddForm)

{{-- Desktop --}}
<div class="hidden sm:block" style="background:#F5F3FF;border:1px solid #DDD6FE;border-radius:12px;padding:16px;margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <span style="font-size:13px;font-weight:700;color:#6D28D9;">Nueva Configuración de Puntos</span>
        <button wire:click="cancelAdd" style="background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:20px;line-height:1;">&times;</button>
    </div>
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Ciclo *</label>
            <select wire:model="newCycleId" style="{{ $iRow }} width:260px;">
                <option value="">— Seleccionar —</option>
                @foreach($ciclosDisponibles as $ciclo)
                <option value="{{ $ciclo->id }}">{{ $ciclo->code }} — {{ $ciclo->name }}</option>
                @endforeach
            </select>
            @error('newCycleId') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Valor / Punto *</label>
            <div style="position:relative;">
                <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;font-weight:500;">Bs</span>
                <input wire:model="newValorPunto" type="number" step="0.01" min="0.01" placeholder="1.00"
                       style="{{ $iRow }} width:110px;padding-left:26px;">
            </div>
            @error('newValorPunto') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Descripción</label>
            <input wire:model="newDescription" type="text" placeholder="Observación opcional"
                   style="{{ $iRow }} width:200px;">
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Estado</label>
            <select wire:model="newActive" style="{{ $iRow }} width:110px;">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:11px;color:transparent;margin-bottom:4px;">·</label>
            <div style="display:flex;gap:6px;">
                <button wire:click="saveNew"
                        style="height:34px;font-size:13px;font-weight:600;background:#7C3AED;color:#fff;border:none;border-radius:6px;padding:0 14px;cursor:pointer;">
                    Guardar
                </button>
                <button wire:click="cancelAdd"
                        style="height:34px;font-size:13px;font-weight:500;background:#fff;color:#6B7280;border:1px solid #D1D5DB;border-radius:6px;padding:0 14px;cursor:pointer;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Móvil --}}
<div class="sm:hidden" style="background:#F5F3FF;border:1px solid #DDD6FE;border-radius:12px;padding:14px;margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <span style="font-size:13px;font-weight:700;color:#6D28D9;">Nueva Config. de Puntos</span>
        <button wire:click="cancelAdd" style="background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:20px;line-height:1;">&times;</button>
    </div>
    <div style="margin-bottom:10px;">
        <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Ciclo *</label>
        <select wire:model="newCycleId" style="{{ $iRow }}">
            <option value="">— Seleccionar —</option>
            @foreach($ciclosDisponibles as $ciclo)
            <option value="{{ $ciclo->id }}">{{ $ciclo->code }} — {{ $ciclo->name }}</option>
            @endforeach
        </select>
        @error('newCycleId') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Valor / Punto *</label>
            <div style="position:relative;">
                <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;">Bs</span>
                <input wire:model="newValorPunto" type="number" step="0.01" min="0.01" placeholder="1.00"
                       style="{{ $iRow }} padding-left:26px;">
            </div>
            @error('newValorPunto') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Estado</label>
            <select wire:model="newActive" style="{{ $iRow }}">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
    <div style="margin-bottom:10px;">
        <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Descripción</label>
        <input wire:model="newDescription" type="text" placeholder="Observación opcional" style="{{ $iRow }}">
    </div>
    <div style="display:flex;gap:8px;">
        <button wire:click="saveNew"
                style="flex:1;height:36px;font-size:13px;font-weight:600;background:#7C3AED;color:#fff;border:none;border-radius:8px;cursor:pointer;">
            Guardar
        </button>
        <button wire:click="cancelAdd"
                style="flex:1;height:36px;font-size:13px;font-weight:500;background:#fff;color:#6B7280;border:1px solid #D1D5DB;border-radius:8px;cursor:pointer;">
            Cancelar
        </button>
    </div>
</div>

@endif

{{-- Tabla escritorio --}}
<div class="hidden sm:block" style="background:#fff;border-radius:12px;border:1px solid #E5E7EB;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#EDE9FE;">
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;width:190px;">Ciclo</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;width:160px;">Período</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;width:120px;">Valor / Pto</th>
                    <th style="padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;">Descripción</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;width:100px;">Estado</th>
                    <th style="padding:10px 14px;text-align:center;font-size:11px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.5px;width:160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($puntos as $punto)
                @if($editingId === $punto->id)
                {{-- Fila edición inline --}}
                <tr wire:key="edit-{{ $punto->id }}" style="background:#F5F3FF;border-top:1px solid #E5E7EB;">
                    <td style="padding:8px 10px;" colspan="2">
                        <p style="font-family:monospace;font-size:12px;font-weight:700;color:#6D28D9;margin:0;">{{ $punto->cycle->code }}</p>
                        <p style="font-size:12px;color:#374151;margin:2px 0 0;">{{ $punto->cycle->name }}</p>
                        <p style="font-size:11px;color:#9CA3AF;margin:2px 0 0;">{{ $punto->cycle->start_date->format('d/m/Y') }} — {{ $punto->cycle->end_date->format('d/m/Y') }}</p>
                    </td>
                    <td style="padding:8px 10px;">
                        <div style="position:relative;">
                            <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;font-weight:500;">Bs</span>
                            <input wire:model="editValorPunto" type="number" step="0.01" min="0.01"
                                   style="{{ $iRow }} padding-left:26px;">
                        </div>
                        @error('editValorPunto') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
                    </td>
                    <td style="padding:8px 10px;">
                        <input wire:model="editDescription" type="text" placeholder="Descripción"
                               style="{{ $iRow }}">
                    </td>
                    <td style="padding:8px 10px;text-align:center;">
                        <select wire:model="editActive" style="{{ $iRow }} width:90px;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </td>
                    <td style="padding:8px 10px;text-align:center;">
                        <div style="display:flex;gap:4px;justify-content:center;">
                            <button wire:click="saveEdit"
                                    style="height:34px;font-size:12px;font-weight:600;background:#7C3AED;color:#fff;border:none;border-radius:6px;padding:0 10px;cursor:pointer;white-space:nowrap;">
                                Guardar
                            </button>
                            <button wire:click="cancelEdit"
                                    style="height:34px;font-size:12px;font-weight:500;background:#fff;color:#6B7280;border:1px solid #D1D5DB;border-radius:6px;padding:0 10px;cursor:pointer;white-space:nowrap;">
                                Cerrar
                            </button>
                        </div>
                    </td>
                </tr>
                @else
                {{-- Fila normal --}}
                <tr wire:key="p-{{ $punto->id }}" style="border-top:1px solid #F3F4F6;"
                    onmouseover="this.style.background='#FAFAFF'" onmouseout="this.style.background=''">
                    <td style="padding:10px 14px;">
                        <p style="font-family:monospace;font-size:12px;font-weight:700;color:#6D28D9;margin:0;">{{ $punto->cycle->code }}</p>
                        <p style="font-size:12px;color:#374151;margin:2px 0 0;">{{ $punto->cycle->name }}</p>
                    </td>
                    <td style="padding:10px 14px;font-size:12px;color:#6B7280;">
                        {{ $punto->cycle->start_date->format('d/m/Y') }} — {{ $punto->cycle->end_date->format('d/m/Y') }}
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        <span style="font-size:13px;font-weight:700;color:#6D28D9;">Bs {{ number_format((float)$punto->valor_punto, 2) }}</span>
                        <span style="font-size:11px;color:#9CA3AF;"> / pto</span>
                    </td>
                    <td style="padding:10px 14px;font-size:12px;color:#6B7280;">
                        {{ $punto->description ?? '—' }}
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        @if($punto->active)
                        <span style="background:#D1FAE5;color:#065F46;font-size:11px;font-weight:600;padding:2px 10px;border-radius:20px;">Activo</span>
                        @else
                        <span style="background:#F3F4F6;color:#6B7280;font-size:11px;font-weight:600;padding:2px 10px;border-radius:20px;">Inactivo</span>
                        @endif
                    </td>
                    <td style="padding:10px 14px;text-align:center;">
                        <button wire:click="startEdit({{ $punto->id }})"
                                style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border:none;background:transparent;border-radius:6px;cursor:pointer;color:#9CA3AF;"
                                onmouseover="this.style.background='#EDE9FE';this.style.color='#6D28D9'"
                                onmouseout="this.style.background='transparent';this.style.color='#9CA3AF'">
                            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" style="padding:40px;text-align:center;font-size:13px;color:#9CA3AF;">
                        No hay configuraciones de puntos registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($puntos->hasPages())
    <div style="padding:12px 16px;border-top:1px solid #F3F4F6;">
        {{ $puntos->links() }}
    </div>
    @endif
</div>

{{-- Tarjetas móvil --}}
<div class="sm:hidden">
    @forelse($puntos as $punto)
    @if($editingId === $punto->id)
    <div wire:key="card-edit-{{ $punto->id }}" style="background:#F5F3FF;border:1px solid #DDD6FE;border-radius:10px;padding:14px;margin-bottom:10px;">
        <p style="font-family:monospace;font-size:12px;font-weight:700;color:#6D28D9;margin:0 0 10px;">{{ $punto->cycle->code }} — {{ $punto->cycle->name }}</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
            <div>
                <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Valor / Punto *</label>
                <div style="position:relative;">
                    <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:12px;color:#9CA3AF;">Bs</span>
                    <input wire:model="editValorPunto" type="number" step="0.01" min="0.01"
                           style="{{ $iRow }} padding-left:26px;">
                </div>
                @error('editValorPunto') <p style="color:#EF4444;font-size:11px;margin-top:2px;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Estado</label>
                <select wire:model="editActive" style="{{ $iRow }}">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>
        <div style="margin-bottom:10px;">
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;margin-bottom:4px;">Descripción</label>
            <input wire:model="editDescription" type="text" placeholder="Descripción" style="{{ $iRow }}">
        </div>
        <div style="display:flex;gap:8px;">
            <button wire:click="saveEdit"
                    style="flex:1;height:36px;font-size:13px;font-weight:600;background:#7C3AED;color:#fff;border:none;border-radius:8px;cursor:pointer;">
                Guardar
            </button>
            <button wire:click="cancelEdit"
                    style="flex:1;height:36px;font-size:13px;font-weight:500;background:#fff;color:#6B7280;border:1px solid #D1D5DB;border-radius:8px;cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>
    @else
    <div wire:key="card-{{ $punto->id }}" style="background:#fff;border-radius:10px;border:1px solid #E5E7EB;margin-bottom:10px;overflow:hidden;">
        <div style="background:#F8F7FF;padding:10px 14px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #EDE9FE;">
            <span style="font-family:monospace;font-size:13px;font-weight:700;color:#6D28D9;">{{ $punto->cycle->code }}</span>
            @if($punto->active)
            <span style="background:#D1FAE5;color:#065F46;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;">Activo</span>
            @else
            <span style="background:#F3F4F6;color:#6B7280;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;">Inactivo</span>
            @endif
        </div>
        <div style="padding:10px 14px;">
            <p style="font-size:13px;font-weight:600;color:#374151;margin:0 0 4px;">{{ $punto->cycle->name }}</p>
            <p style="font-size:12px;color:#6B7280;margin:0 0 6px;">{{ $punto->cycle->start_date->format('d/m/Y') }} — {{ $punto->cycle->end_date->format('d/m/Y') }}</p>
            <p style="font-size:14px;font-weight:700;color:#6D28D9;margin:0;">
                Bs {{ number_format((float)$punto->valor_punto, 2) }}
                <span style="font-size:11px;font-weight:400;color:#9CA3AF;">/ punto</span>
            </p>
            @if($punto->description)
            <p style="font-size:12px;color:#9CA3AF;margin:4px 0 0;">{{ $punto->description }}</p>
            @endif
        </div>
        <div style="padding:8px 14px;border-top:1px solid #F3F4F6;">
            <button wire:click="startEdit({{ $punto->id }})"
                    style="width:100%;height:34px;font-size:13px;font-weight:600;background:#EDE9FE;color:#6D28D9;border:none;border-radius:6px;cursor:pointer;">
                Editar
            </button>
        </div>
    </div>
    @endif
    @empty
    <p style="text-align:center;font-size:13px;color:#9CA3AF;padding:40px 0;">No hay configuraciones registradas.</p>
    @endforelse
    @if($puntos->hasPages())
    <div style="padding:12px 0;">{{ $puntos->links() }}</div>
    @endif
</div>

</div>
