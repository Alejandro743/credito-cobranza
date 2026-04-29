<x-user-layout>
@section('page-title', 'Módulo Vendedor')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-melocoton-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-melocoton-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-melocoton-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">Módulo Vendedor</h2>
                <p class="text-xs text-gray-400">Gestión de clientes y ventas</p>
            </div>
        </div>
        <p class="text-sm text-gray-600">
            Bienvenido, <span class="font-semibold">{{ auth()->user()->name }}</span>.
        </p>
    </div>
</div>
</x-user-layout>
