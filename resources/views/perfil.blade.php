@php
    $user = auth()->user();
    $rol  = $user->getRoleNames()->first() ?? '—';
@endphp

<x-user-layout headerTitle="Perfil del usuario">

<div class="max-w-sm mx-auto" style="padding-top:8px;">

    <div style="background:#fff; border-radius:20px; padding:32px 24px 28px; box-shadow:0 2px 12px rgba(0,0,0,.07);">

        {{-- Avatar --}}
        <div style="display:flex; justify-content:center; margin-bottom:20px;">
            <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#7B6FE8,#9B8FF5); display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:800; color:#fff; flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
        </div>

        {{-- Nombre --}}
        <p style="text-align:center; font-size:20px; font-weight:800; color:#111827; line-height:1.3; margin-bottom:8px;">{{ $user->name }}</p>

        {{-- Rol badge --}}
        <div style="text-align:center; margin-bottom:24px;">
            <span style="display:inline-block; padding:4px 14px; background:rgba(123,111,232,.12); color:#7B6FE8; border-radius:20px; font-size:12px; font-weight:700; text-transform:capitalize;">
                {{ $rol }}
            </span>
        </div>

        {{-- Email --}}
        <div style="display:flex; align-items:center; gap:14px; padding:14px 0; border-top:1px solid #F3F4F6;">
            <div style="width:36px; height:36px; border-radius:10px; background:#F4F5F9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#7B6FE8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div style="flex:1; min-width:0;">
                <p style="font-size:10px; font-weight:600; color:#9CA3AF; text-transform:uppercase; letter-spacing:.5px; margin-bottom:1px;">Correo</p>
                <p style="font-size:14px; font-weight:600; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</p>
            </div>
        </div>

    </div>

</div>

</x-user-layout>
