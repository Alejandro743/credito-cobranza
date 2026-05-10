<x-guest-layout>

    <p style="font-size:24px; font-weight:700; color:#1C1917; margin-bottom:5px;">Bienvenida</p>
    <p style="font-size:13px; color:#A8A29E; margin-bottom:28px;">Ingresá tus datos para continuar</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#78716C; letter-spacing:.5px; text-transform:uppercase; margin-bottom:6px;">
                Correo electrónico
            </label>
            <input type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="form-input" placeholder="usuario@essen.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Contraseña --}}
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:11px; font-weight:700; color:#78716C; letter-spacing:.5px; text-transform:uppercase; margin-bottom:6px;">
                Contraseña
            </label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="form-input" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Recordarme --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:22px;">
            <input type="checkbox" name="remember" id="remember_me"
                   style="accent-color:#BE185D; width:15px; height:15px; cursor:pointer;">
            <label for="remember_me" style="font-size:13px; color:#78716C; cursor:pointer;">
                Recordarme en este equipo
            </label>
        </div>

        <button type="submit" class="btn-login">
            Ingresar al sistema
        </button>
    </form>

</x-guest-layout>
