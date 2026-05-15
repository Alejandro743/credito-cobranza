@php
    $user = auth()->user();
    $rol  = $user->getRoleNames()->first() ?? '—';
@endphp

<x-user-layout title="Mi Perfil">

<div class="max-w-md mx-auto" style="padding-top:8px;">

    {{-- Tarjeta principal --}}
    <div style="background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07);">

        {{-- Cabecera morada --}}
        <div style="background:linear-gradient(135deg,#7B6FE8,#9B8FF5); padding:32px 24px 48px; position:relative;">
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:rgba(255,255,255,.7); margin-bottom:4px;">Mi cuenta</p>
            <p style="font-size:22px; font-weight:800; color:#fff;">{{ $user->name }}</p>
        </div>

        {{-- Avatar centrado sobre la cabecera --}}
        <div style="display:flex; justify-content:center; margin-top:-36px; margin-bottom:16px;">
            <div style="width:72px; height:72px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.15); border:3px solid #fff;">
                <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; color:#fff;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>
        </div>

        {{-- Datos --}}
        <div style="padding:0 24px 28px;">

            {{-- Rol --}}
            <div style="text-align:center; margin-bottom:24px;">
                <span style="display:inline-block; padding:4px 14px; background:rgba(123,111,232,.12); color:#7B6FE8; border-radius:20px; font-size:12px; font-weight:700; text-transform:capitalize;">
                    {{ $rol }}
                </span>
            </div>

            {{-- Filas de datos --}}
            @foreach([
                ['label' => 'Nombre completo', 'value' => $user->name,       'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label' => 'Correo',          'value' => $user->email,      'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['label' => 'Miembro desde',   'value' => $user->created_at->format('d M Y'), 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ] as $row)
            <div style="display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #F3F4F6;">
                <div style="width:36px; height:36px; border-radius:10px; background:#F4F5F9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="{{ $row['icon'] }}"/>
                    </svg>
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:1px;">{{ $row['label'] }}</p>
                    <p style="font-size:14px; font-weight:600; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $row['value'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- Botón editar perfil --}}
    <a href="{{ route('profile.edit') }}" wire:navigate
       style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:12px; padding:14px; background:#fff; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,.06); text-decoration:none; color:#374151; font-size:13px; font-weight:600;">
        <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Editar información
    </a>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">
        @csrf
        <button type="submit"
                style="width:100%; padding:14px; background:#FEF2F2; border:none; border-radius:14px; color:#EF4444; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
            </svg>
            Cerrar sesión
        </button>
    </form>

</div>

</x-user-layout>
