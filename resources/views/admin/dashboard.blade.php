<x-admin-layout>
@section('page-title', 'Dashboard')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    @php
    $cards = [
        ['label'=>'Usuarios','value'=> \App\Models\User::count(), 'color'=>'lavanda', 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label'=>'Productos','value'=> \App\Models\Product::count(), 'color'=>'mint', 'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['label'=>'Ciclos Activos','value'=> \App\Models\CommercialCycle::where('status','abierto')->count(), 'color'=>'melocoton', 'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>'Matrices Financieras','value'=> \App\Models\FinancialMatrix::where('active',true)->count(), 'color'=>'celeste', 'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-medium text-gray-500">{{ $card['label'] }}</span>
            <div class="w-9 h-9 rounded-xl bg-{{ $card['color'] }}-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-{{ $card['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h3 class="font-semibold text-gray-700 mb-4">Ciclos Comerciales Recientes</h3>
        @forelse(\App\Models\CommercialCycle::latest()->take(5)->get() as $c)
        <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
            <span class="text-sm text-gray-700">{{ $c->name }}</span>
            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                {{ $c->status === 'abierto' ? 'bg-mint-100 text-mint-700' : ($c->status === 'cerrado' ? 'bg-celeste-100 text-celeste-700' : 'bg-gray-100 text-gray-500') }}">
                {{ ucfirst($c->status) }}
            </span>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-4">Sin ciclos aún</p>
        @endforelse
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h3 class="font-semibold text-gray-700 mb-4">Usuarios por Rol</h3>
        @php $totalUsers = \App\Models\User::count() ?: 1; @endphp
        @foreach(\Spatie\Permission\Models\Role::withCount('users')->get() as $role)
        <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
            <span class="text-sm text-gray-700 flex-1 capitalize">{{ $role->name }}</span>
            <span class="text-sm font-semibold text-gray-800 w-8 text-right">{{ $role->users_count }}</span>
            <div class="w-24 bg-gray-100 rounded-full h-1.5">
                <div class="bg-lavanda-400 h-1.5 rounded-full" style="width: {{ min(100, ($role->users_count / $totalUsers) * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</x-admin-layout>
