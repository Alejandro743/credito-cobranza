<div>

{{-- Flash success --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
     style="position:fixed; bottom:20px; right:20px; z-index:50; background:#7B6FE8; color:#fff; font-size:13px; font-weight:600; padding:10px 20px; border-radius:12px; box-shadow:0 4px 16px rgba(123,111,232,.35); display:flex; align-items:center; gap:8px;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ══════ PANEL NUEVA MATRIZ ══════ --}}
@if($showForm)
<div style="background:#fff; border-radius:16px; border:1px solid #EDE9FE; box-shadow:0 2px 12px rgba(123,111,232,.12); margin-bottom:20px; overflow:hidden;">
    <div style="background:#F8F7FF; border-bottom:1px solid #EDE9FE; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
        <span style="font-size:14px; font-weight:800; color:#7B6FE8; display:flex; align-items:center; gap:7px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Nueva Matriz Financiera
        </span>
        <button wire:click="cancelar"
                style="width:30px; height:30px; border:1px solid #EDE9FE; background:#fff; color:#9CA3AF; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                @mouseenter="$el.style.background='#F3F4F6'" @mouseleave="$el.style.background='#fff'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div style="padding:16px 20px;">
        @php $iS = 'height:38px; border:1px solid #EDE9FE; border-radius:8px; padding:0 10px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;'; @endphp

        {{-- Fila 1: Código + Nombre + Cuotas + Estado --}}
        <div style="display:flex; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:12px;">
            <div style="min-width:100px; max-width:140px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Código *</label>
                <input wire:model="code" type="text" placeholder="Ej: MAT-01" style="width:100%; {{ $iS }}" maxlength="30">
                @error('code')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div style="flex:1; min-width:180px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Nombre *</label>
                <input wire:model="name" type="text" placeholder="Ej: Plan 12 cuotas" style="width:100%; {{ $iS }}">
                @error('name')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div style="min-width:100px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Cuotas *</label>
                <input wire:model="cantidadCuotas" type="number" min="1" max="120" style="width:100%; {{ $iS }}">
                @error('cantidadCuotas')<p style="font-size:11px; color:#EF4444; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div style="min-width:100px;">
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Estado</label>
                <select wire:model="active" style="{{ $iS }} width:100%; cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        {{-- Fila 2: Descripción --}}
        <div style="margin-bottom:12px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px;">Descripción</label>
            <input wire:model="description" type="text" placeholder="Descripción opcional" style="width:100%; {{ $iS }}">
        </div>

        {{-- Fila 3: Cuota Inicial --}}
        <div style="background:#FAFAFE; border-radius:10px; padding:12px 14px; border:1px solid #EDE9FE; margin-bottom:10px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-bottom:{{ $usaCuotaInicial ? '10px' : '0' }};">
                <input wire:model.live="usaCuotaInicial" type="checkbox" style="width:15px; height:15px; accent-color:#7B6FE8; cursor:pointer;">
                <span style="font-size:13px; font-weight:700; color:#374151;">Usa cuota inicial</span>
            </label>
            @if($usaCuotaInicial)
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="min-width:140px;">
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Tipo *</label>
                    <select wire:model="tipoCuotaInicial" style="{{ $iS }} width:100%; cursor:pointer;">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="monto_fijo">Monto fijo (Bs.)</option>
                    </select>
                    @error('tipoCuotaInicial')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div style="min-width:120px;">
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Valor *</label>
                    <input wire:model="valorCuotaInicial" type="number" min="0" step="0.01" style="{{ $iS }} width:100%;">
                    @error('valorCuotaInicial')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
            </div>
            @endif
        </div>

        {{-- Fila 4: Incremento --}}
        <div style="background:#FAFAFE; border-radius:10px; padding:12px 14px; border:1px solid #EDE9FE; margin-bottom:16px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; margin-bottom:{{ $usaIncremento ? '10px' : '0' }};">
                <input wire:model.live="usaIncremento" type="checkbox" style="width:15px; height:15px; accent-color:#7B6FE8; cursor:pointer;">
                <span style="font-size:13px; font-weight:700; color:#374151;">Usa incremento entre cuotas</span>
            </label>
            @if($usaIncremento)
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="min-width:140px;">
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Tipo *</label>
                    <select wire:model="tipoIncremento" style="{{ $iS }} width:100%; cursor:pointer;">
                        <option value="porcentaje">Porcentaje (%)</option>
                        <option value="monto_fijo">Monto fijo (Bs.)</option>
                    </select>
                    @error('tipoIncremento')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
                <div style="min-width:120px;">
                    <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">Valor *</label>
                    <input wire:model="valorIncremento" type="number" min="0" step="0.01" style="{{ $iS }} width:100%;">
                    @error('valorIncremento')<p style="font-size:11px; color:#EF4444; margin-top:3px;">{{ $message }}</p>@enderror
                </div>
            </div>
            @endif
        </div>

        {{-- Botones --}}
        <div style="display:flex; gap:8px;">
            <button wire:click="save" wire:loading.attr="disabled"
                    style="height:38px; padding:0 24px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                    @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
            <button wire:click="cancelar"
                    style="height:38px; padding:0 18px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;"
                    @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ══════ TOOLBAR ══════ --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <span style="font-size:12px; color:#9CA3AF; font-weight:500;">{{ $registros->count() }} matriz(ces)</span>
    @if(!$showForm && !$editId)
    <button wire:click="create"
            style="height:36px; padding:0 18px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px;"
            @mouseenter="$el.style.opacity='.88'" @mouseleave="$el.style.opacity='1'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Nueva Matriz
    </button>
    @endif
</div>

@php
$iRow = 'height:34px; border:1px solid #EDE9FE; border-radius:7px; padding:0 8px; font-size:13px; color:#374151; background:#fff; outline:none; box-sizing:border-box;';
$sortCols = ['Código'=>'code','Nombre'=>'name','Cuotas'=>'cantidad_cuotas'];
@endphp

{{-- ══════ TABLA ESCRITORIO ══════ --}}
<div class="hidden sm:block" style="background:#fff; border-radius:16px; border:1px solid #E5E7EB; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; max-height:calc(100vh - 180px);">

    <div style="padding:10px 18px; display:flex; align-items:center; gap:8px; border-bottom:1px solid #F3F4F6;">
        <span style="font-size:13px; font-weight:700; color:#111827;">Matrices Financieras</span>
        <span style="background:#F3F4F6; color:#6B7280; font-size:11px; font-weight:600; padding:2px 8px; border-radius:99px;">{{ $registros->count() }}</span>
    </div>

    <div style="overflow:auto; flex:1;">
    <table style="table-layout:fixed; width:100%; border-collapse:collapse; min-width:600px;">
        <colgroup>
            <col style="width:44px;">
            <col style="width:110px;">
            <col>
            <col style="width:70px;">
            <col style="width:160px;">
            <col style="width:160px;">
            <col style="width:90px;">
            <col style="width:80px;">
        </colgroup>
        <thead style="position:sticky; top:0; z-index:10;">
            <tr style="background:#F9F8FF; border-bottom:2px solid #EDE9FE;">
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:700; color:#C4B5FD; text-transform:uppercase; letter-spacing:.5px;">#</th>
                @foreach($sortCols as $label => $key)
                @php $isActive = $sortBy === $key; @endphp
                <th wire:click="toggleSort('{{ $key }}')"
                    style="padding:10px 14px; text-align:{{ in_array($label,['Cuotas','Estado']) ? 'center' : 'left' }}; user-select:none; cursor:pointer; {{ $isActive ? 'background:#EDE9FE;' : '' }}"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='{{ $isActive ? '#EDE9FE' : '' }}'">
                    <span style="font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; display:inline-flex; align-items:center; gap:5px;">
                        {{ $label }}
                        @if($isActive && $sortDir==='asc') <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
                        @elseif($isActive) <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#7B6FE8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                        @else <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 9l4-4 4 4M16 15l-4 4-4-4"/></svg>
                        @endif
                    </span>
                </th>
                @endforeach
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Cuota Inicial</th>
                <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Incremento</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Estado</th>
                <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($registros as $r)

        @if($r->id === $editId)
        {{-- ── FILA EDICIÓN INLINE ── --}}
        <tr wire:key="mf-edit-{{ $r->id }}" style="background:#FAFAFE; border-bottom:1px solid #EDE9FE;">
            <td colspan="8" style="padding:14px 18px;">

                {{-- Fila superior: Código + Nombre + Cuotas + Estado + Descripción --}}
                <div style="display:flex; align-items:flex-start; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                    <div style="min-width:100px; max-width:130px;">
                        <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Código *</label>
                        <input wire:model="code" type="text" maxlength="30" style="width:100%; {{ $iRow }}">
                        @error('code')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                    <div style="flex:1; min-width:160px;">
                        <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Nombre *</label>
                        <input wire:model="name" type="text" style="width:100%; {{ $iRow }}">
                        @error('name')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                    <div style="min-width:140px; flex:1;">
                        <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Descripción</label>
                        <input wire:model="description" type="text" style="width:100%; {{ $iRow }}">
                    </div>
                    <div style="min-width:80px;">
                        <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Cuotas *</label>
                        <input wire:model="cantidadCuotas" type="number" min="1" max="120" style="width:100%; {{ $iRow }}">
                        @error('cantidadCuotas')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                    <div style="min-width:90px;">
                        <label style="display:block; font-size:10px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Estado</label>
                        <select wire:model="active" style="{{ $iRow }} width:100%; cursor:pointer;">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                {{-- Fila inferior: Cuota inicial + Incremento --}}
                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 12px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer; white-space:nowrap;">
                            <input wire:model.live="usaCuotaInicial" type="checkbox" style="width:14px; height:14px; accent-color:#7B6FE8; cursor:pointer;">
                            <span style="font-size:12px; font-weight:700; color:#374151;">Cuota inicial</span>
                        </label>
                        @if($usaCuotaInicial)
                        <select wire:model="tipoCuotaInicial" style="{{ $iRow }} min-width:130px; cursor:pointer;">
                            <option value="porcentaje">Porcentaje (%)</option>
                            <option value="monto_fijo">Monto fijo (Bs.)</option>
                        </select>
                        <input wire:model="valorCuotaInicial" type="number" min="0" step="0.01" placeholder="Valor" style="{{ $iRow }} width:100px;">
                        @error('valorCuotaInicial')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                        @endif
                    </div>
                    <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:8px 12px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer; white-space:nowrap;">
                            <input wire:model.live="usaIncremento" type="checkbox" style="width:14px; height:14px; accent-color:#7B6FE8; cursor:pointer;">
                            <span style="font-size:12px; font-weight:700; color:#374151;">Incremento</span>
                        </label>
                        @if($usaIncremento)
                        <select wire:model="tipoIncremento" style="{{ $iRow }} min-width:130px; cursor:pointer;">
                            <option value="porcentaje">Porcentaje (%)</option>
                            <option value="monto_fijo">Monto fijo (Bs.)</option>
                        </select>
                        <input wire:model="valorIncremento" type="number" min="0" step="0.01" placeholder="Valor" style="{{ $iRow }} width:100px;">
                        @error('valorIncremento')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
                        @endif
                    </div>
                </div>

                {{-- Botones --}}
                <div style="display:flex; gap:6px;">
                    <button wire:click="save" wire:loading.attr="disabled"
                            style="height:34px; padding:0 20px; border-radius:7px; border:none; background:#7B6FE8; color:#fff; font-size:13px; font-weight:700; cursor:pointer; white-space:nowrap;"
                            @mouseenter="$el.style.opacity='.85'" @mouseleave="$el.style.opacity='1'">
                        <span wire:loading.remove wire:target="save">Guardar</span>
                        <span wire:loading wire:target="save">...</span>
                    </button>
                    <button wire:click="cancelar"
                            style="height:34px; padding:0 16px; border-radius:7px; border:1px solid #E5E7EB; background:#F3F4F6; color:#6B7280; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;"
                            @mouseenter="$el.style.background='#E5E7EB'" @mouseleave="$el.style.background='#F3F4F6'">
                        Cancelar
                    </button>
                </div>
            </td>
        </tr>

        @else
        {{-- ── FILA NORMAL ── --}}
        <tr wire:key="mf-{{ $r->id }}" style="border-bottom:1px solid #F9FAFB;"
            @mouseenter="$el.style.background='#FAFAFE'" @mouseleave="$el.style.background=''">
            <td class="col-row-num" style="padding:10px 8px; text-align:center; font-size:13px; color:#111827; white-space:nowrap;">{{ $loop->iteration }}</td>
            <td style="padding:11px 14px; font-size:13px; color:#111827; white-space:nowrap;">{{ $r->code }}</td>
            <td style="padding:11px 14px; font-size:13px; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $r->name }}</td>
            <td style="padding:11px 14px; text-align:center; font-size:13px; color:#111827;">
                {{ $r->cantidad_cuotas === 1 ? 'Contado' : $r->cantidad_cuotas }}
            </td>
            <td style="padding:11px 14px; font-size:13px; color:#111827;">
                @if($r->usa_cuota_inicial)
                <span style="display:inline-flex; align-items:center; gap:5px;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#f97316; flex-shrink:0;"></span>
                    {{ $r->tipo_cuota_inicial === 'porcentaje' ? number_format($r->valor_cuota_inicial, 2).'%' : 'Bs. '.number_format($r->valor_cuota_inicial, 2) }}
                </span>
                @else
                <span style="color:#111827; font-size:13px;">—</span>
                @endif
            </td>
            <td style="padding:11px 14px; font-size:13px; color:#111827;">
                @if($r->usa_incremento)
                <span style="display:inline-flex; align-items:center; gap:5px;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#7B6FE8; flex-shrink:0;"></span>
                    {{ $r->tipo_incremento === 'porcentaje' ? number_format($r->valor_incremento, 2).'%' : 'Bs. '.number_format($r->valor_incremento, 2) }}
                </span>
                @else
                <span style="color:#111827; font-size:13px;">—</span>
                @endif
            </td>
            <td style="padding:11px 14px; text-align:center;">
                <span style="font-size:12px; font-weight:700; padding:3px 10px; border-radius:6px;
                             background:{{ $r->active ? '#D1FAE5' : '#F3F4F6' }};
                             color:{{ $r->active ? '#059669' : '#9CA3AF' }};">
                    {{ $r->active ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td style="padding:11px 14px; text-align:center;">
                <button wire:click="edit({{ $r->id }})" title="Editar"
                        style="width:28px; height:28px; border-radius:7px; border:1px solid #EDE9FE; background:#F8F7FF; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0 auto;"
                        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
            </td>
        </tr>
        @endif

        @empty
        <tr><td colspan="8" style="padding:48px; text-align:center; color:#9CA3AF; font-size:13px;">Sin matrices configuradas. Creá la primera.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- ══════ CARDS MOBILE ══════ --}}
<div class="sm:hidden">
    @if($registros->isEmpty())
    <p style="text-align:center; color:#9CA3AF; font-size:13px; padding:48px 0;">Sin matrices configuradas. Creá la primera.</p>
    @endif

    @foreach($registros as $r)

    @if($r->id === $editId)
    {{-- CARD EDICIÓN MOBILE --}}
    <div wire:key="mf-edit-mobile-{{ $r->id }}"
         style="background:#FAFAFE; border-radius:14px; border:1px solid #EDE9FE; margin-bottom:10px; padding:14px 16px; box-shadow:0 1px 4px rgba(123,111,232,.1);">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Código *</label>
                <input wire:model="code" type="text" maxlength="30" style="width:100%; {{ $iRow }}">
                @error('code')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Cuotas *</label>
                <input wire:model="cantidadCuotas" type="number" min="1" max="120" style="width:100%; {{ $iRow }}">
                @error('cantidadCuotas')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div style="margin-bottom:10px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Nombre *</label>
            <input wire:model="name" type="text" style="width:100%; {{ $iRow }}">
            @error('name')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-bottom:10px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Descripción</label>
            <input wire:model="description" type="text" style="width:100%; {{ $iRow }}">
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
            <div>
                <label style="display:block; font-size:11px; font-weight:700; color:#7B6FE8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px;">Estado</label>
                <select wire:model="active" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        {{-- Cuota inicial --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:10px 12px; margin-bottom:8px;">
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin-bottom:{{ $usaCuotaInicial ? '8px' : '0' }};">
                <input wire:model.live="usaCuotaInicial" type="checkbox" style="width:14px; height:14px; accent-color:#7B6FE8; cursor:pointer;">
                <span style="font-size:12px; font-weight:700; color:#374151;">Cuota inicial</span>
            </label>
            @if($usaCuotaInicial)
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                <select wire:model="tipoCuotaInicial" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="porcentaje">% Porcentaje</option>
                    <option value="monto_fijo">Bs. Fijo</option>
                </select>
                <input wire:model="valorCuotaInicial" type="number" min="0" step="0.01" placeholder="Valor" style="width:100%; {{ $iRow }}">
            </div>
            @error('valorCuotaInicial')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            @endif
        </div>

        {{-- Incremento --}}
        <div style="background:#F8F7FF; border:1px solid #EDE9FE; border-radius:8px; padding:10px 12px; margin-bottom:12px;">
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin-bottom:{{ $usaIncremento ? '8px' : '0' }};">
                <input wire:model.live="usaIncremento" type="checkbox" style="width:14px; height:14px; accent-color:#7B6FE8; cursor:pointer;">
                <span style="font-size:12px; font-weight:700; color:#374151;">Incremento</span>
            </label>
            @if($usaIncremento)
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                <select wire:model="tipoIncremento" style="width:100%; {{ $iRow }} cursor:pointer;">
                    <option value="porcentaje">% Porcentaje</option>
                    <option value="monto_fijo">Bs. Fijo</option>
                </select>
                <input wire:model="valorIncremento" type="number" min="0" step="0.01" placeholder="Valor" style="width:100%; {{ $iRow }}">
            </div>
            @error('valorIncremento')<p style="font-size:10px; color:#EF4444; margin-top:2px;">{{ $message }}</p>@enderror
            @endif
        </div>

        <div style="display:flex; gap:8px;">
            <button wire:click="save" wire:loading.attr="disabled"
                    style="flex:1; height:36px; background:#7B6FE8; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:700; cursor:pointer;">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
            <button wire:click="cancelar"
                    style="flex:1; height:36px; background:#F3F4F6; color:#6B7280; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer;">
                Cancelar
            </button>
        </div>
    </div>

    @else
    {{-- CARD NORMAL --}}
    <div wire:key="mf-mobile-{{ $r->id }}"
         style="background:#fff; border-radius:14px; border:1px solid #E5E7EB; margin-bottom:10px; box-shadow:0 1px 4px rgba(0,0,0,.05); padding:12px 14px;">

        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
            <div style="width:30px; height:30px; border-radius:8px; background:#EDE9FE; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <span style="font-size:12px; font-weight:700; color:#7B6FE8;">{{ mb_substr($r->code, 0, 2) }}</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:14px; font-weight:700; color:#111827; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $r->name }}</p>
                <p style="font-size:11px; color:#7B6FE8; font-family:monospace; margin:2px 0 0;">{{ $r->code }}</p>
            </div>
            <span style="flex-shrink:0; padding:2px 9px; border-radius:99px; font-size:11px; font-weight:600;
                         background:{{ $r->active ? '#D1FAE5' : '#F3F4F6' }};
                         color:{{ $r->active ? '#059669' : '#9CA3AF' }};">
                {{ $r->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; border-top:1px solid #F3F4F6; padding-top:9px;">
            <span style="font-size:12px; color:#6B7280;">
                <span style="font-weight:700; color:#374151;">{{ $r->cantidad_cuotas === 1 ? 'Contado' : $r->cantidad_cuotas.' cuotas' }}</span>
            </span>
            @if($r->usa_cuota_inicial)
            <span style="font-size:11px; padding:2px 8px; border-radius:99px; background:#FFF7ED; color:#C2410C; font-weight:600;">
                CI: {{ $r->tipo_cuota_inicial === 'porcentaje' ? number_format($r->valor_cuota_inicial,2).'%' : 'Bs.'.number_format($r->valor_cuota_inicial,2) }}
            </span>
            @endif
            @if($r->usa_incremento)
            <span style="font-size:11px; padding:2px 8px; border-radius:99px; background:#F0EEFF; color:#5B21B6; font-weight:600;">
                Inc: {{ $r->tipo_incremento === 'porcentaje' ? number_format($r->valor_incremento,2).'%' : 'Bs.'.number_format($r->valor_incremento,2) }}
            </span>
            @endif
            <button wire:click="edit({{ $r->id }})"
                    style="margin-left:auto; height:32px; padding:0 14px; background:#F8F7FF; color:#7B6FE8; border:1px solid #EDE9FE; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:4px;"
                    @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Editar
            </button>
        </div>
    </div>
    @endif

    @endforeach
</div>

</div>
