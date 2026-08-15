@php
    $busquedaActiva = strlen(trim($searchCliente)) >= 2;
    $listaActual    = $busquedaActiva ? $resultadosCliente : $clientesPropios;
@endphp

@if (!$busquedaActiva && count($clientesPropios) === 0)
<div style="padding:10px 12px; font-size:12px; color:#9CA3AF;">Escribí CI, nombre o apellido</div>

@elseif ($busquedaActiva && count($resultadosCliente) === 0)
<div style="padding:10px 12px; font-size:12px; color:#9CA3AF;">Sin resultados para "<span style="font-style:italic;">{{ $searchCliente }}</span>"</div>
<button wire:click="abrirRegistroCliente"
        @click="showSearch = false"
        style="width:100%; text-align:left; padding:10px 12px; border:none; border-top:1px solid #F3F4F6; background:#F8F7FF; font-size:12px; font-weight:700; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; gap:6px;"
        @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
    <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    ¿No existe? Registrarlo
</button>

@else
    @foreach ($listaActual as $c)
    <button wire:click="seleccionarCliente({{ $c['id'] }}, {{ $c['user_id'] }}, '{{ addslashes($c['nombre']) }}', '{{ addslashes($c['ci']) }}')"
            @click="showSearch = false"
            style="width:100%; text-align:left; padding:8px 12px; border:none; border-bottom:1px solid #F9FAFB; background:#fff; font-size:13px; color:#374151; cursor:pointer; display:block;"
            @mouseenter="$el.style.background='#F5F3FF'" @mouseleave="$el.style.background='#fff'">
        <span style="font-family:monospace; font-weight:700; color:#7B6FE8;">{{ $c['ci'] }}</span>
        <span> — {{ $c['nombre'] }}</span>
    </button>
    @endforeach
    @if ($busquedaActiva)
    <button wire:click="abrirRegistroCliente"
            @click="showSearch = false"
            style="width:100%; text-align:left; padding:10px 12px; border:none; border-top:1px solid #F3F4F6; background:#F8F7FF; font-size:12px; font-weight:700; color:#7B6FE8; cursor:pointer; display:flex; align-items:center; gap:6px;"
            @mouseenter="$el.style.background='#EDE9FE'" @mouseleave="$el.style.background='#F8F7FF'">
        <svg width="13" height="13" fill="none" stroke="#7B6FE8" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        ¿No existe? Registrarlo
    </button>
    @endif
@endif
