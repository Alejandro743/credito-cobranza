<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso desactivado — Crédito y Cobranza</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-melocoton-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-xl border border-red-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-red-50 px-8 py-8 text-center border-b border-red-100">
                <div class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Acceso desactivado</h1>
            </div>

            {{-- Body --}}
            <div class="px-8 py-8 text-center">
                <p class="text-gray-600 leading-relaxed">
                    Tu rol de acceso al sistema ha sido desactivado.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Contactá al administrador para restablecer el acceso.
                </p>

                <div class="mt-8 p-4 bg-gray-50 rounded-2xl text-left">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sesión actual</p>
                    <p class="text-sm text-gray-700 font-medium">{{ auth()->user()->name ?? '' }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->email ?? '' }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit"
                            class="w-full px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl transition-colors">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Si creés que esto es un error, comunicate con el soporte.
        </p>
    </div>
</body>
</html>
