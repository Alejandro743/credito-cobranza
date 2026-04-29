<x-user-layout :no-header="true" :no-padding="true">
<div>
    <div class="px-3 py-3 flex items-center justify-between" style="background:#FAEEDA;">
        <button @click="$dispatch('open-sidebar')" onclick="window.dispatchEvent(new CustomEvent('open-sidebar'))"
                class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg mr-2 flex-shrink-0"
                style="background:rgba(99,56,6,0.12);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#633806;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="font-bold text-base flex-1" style="color:#633806;">Pagos y Saldos</h1>
        <span class="text-sm font-medium" style="color:#633806;">{{ now()->format('d/m/Y') }}</span>
    </div>
    <div class="p-6 text-center text-gray-400 mt-12">
        <svg class="w-12 h-12 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        <p class="font-semibold text-gray-500">Pagos y Saldos</p>
        <p class="text-sm mt-1">Próximamente disponible</p>
    </div>
</div>
</x-user-layout>
