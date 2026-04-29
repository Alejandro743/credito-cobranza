<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pedidos')->where('estado', 'borrador')->update(['estado' => 'en_espera']);
        DB::table('pedidos')->where('estado', 'operativo')->update(['estado' => 'en_espera']);
        DB::table('pedidos')->where('estado', 'enviado')->update(['estado' => 'revision']);
    }

    public function down(): void
    {
        DB::table('pedidos')->where('estado', 'en_espera')->update(['estado' => 'borrador']);
        DB::table('pedidos')->where('estado', 'revision')->update(['estado' => 'enviado']);
    }
};
