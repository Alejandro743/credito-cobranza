<x-user-layout>
@section('page-title', 'Mi Panel')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-celeste-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-celeste-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-celeste-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-800">Mi Panel</h2>
                <p class="text-xs text-gray-400">Consulta el estado de tu cuenta</p>
            </div>
        </div>
        <p class="text-sm text-gray-600">
            Bienvenido, <span class="font-semibold">{{ auth()->user()->name }}</span>.
        </p>
    </div>
</div>
</x-user-layout>
