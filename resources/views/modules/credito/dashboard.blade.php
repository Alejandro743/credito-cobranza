<x-user-layout>
@section('page-title', 'Módulo Crédito')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-mint-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-mint-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-mint-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">Módulo Crédito</h2>
                <p class="text-xs text-gray-400">Gestión de créditos y cobranzas</p>
            </div>
        </div>
        <p class="text-sm text-gray-600">
            Bienvenido, <span class="font-semibold">{{ auth()->user()->name }}</span>.
        </p>
    </div>
</div>
</x-user-layout>
