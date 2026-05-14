<x-guest-layout>

    <p style="font-size:30px; font-weight:800; color:#111827; margin-bottom:6px; letter-spacing:-0.5px;">Bienvenido</p>
    <p style="font-size:13px; color:#9CA3AF; margin-bottom:28px; font-weight:400;">Ingrese sus datos para continuar</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#6B7280; letter-spacing:.5px; text-transform:uppercase; margin-bottom:6px;">
                Correo electrónico
            </label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <input type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="form-input" placeholder="usuario@essen.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Contraseña --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#6B7280; letter-spacing:.5px; text-transform:uppercase; margin-bottom:6px;">
                Contraseña
            </label>
            <div class="input-wrap">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input type="password" id="pwd-input" name="password" required autocomplete="current-password"
                       class="form-input" placeholder="••••••••" style="padding-right:42px;">
                <button type="button" class="input-eye" onclick="togglePwd(this)">
                    <svg id="eye-show" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg id="eye-hide" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Recordarme --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:24px;">
            <input type="checkbox" name="remember" id="remember_me"
                   style="accent-color:#8B7CF6; width:15px; height:15px; cursor:pointer; border-radius:4px;">
            <label for="remember_me" style="font-size:13px; color:#6B7280; cursor:pointer;">
                Recordarme en este equipo
            </label>
        </div>

        <button type="submit" class="btn-login">
            Ingresar al sistema
        </button>
    </form>

    <script>
    function togglePwd(btn) {
        var inp = document.getElementById('pwd-input');
        var show = document.getElementById('eye-show');
        var hide = document.getElementById('eye-hide');
        if (inp.type === 'password') {
            inp.type = 'text';
            show.style.display = 'none';
            hide.style.display = '';
        } else {
            inp.type = 'password';
            show.style.display = '';
            hide.style.display = 'none';
        }
    }
    </script>

</x-guest-layout>
