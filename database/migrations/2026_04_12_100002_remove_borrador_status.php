<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ciclos: borrador → abierto
        DB::table('commercial_cycles')->where('status', 'borrador')->update(['status' => 'abierto']);
        Schema::table('commercial_cycles', function (Blueprint $table) {
            $table->string('status', 20)->default('abierto')->change();
        });

        // Lista Maestra: borrador → activa
        DB::table('lista_maestra')->where('estado', 'borrador')->update(['estado' => 'activa']);
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->string('estado', 20)->default('activa')->change();
        });

        // Lista Derivada: borrador → activa
        DB::table('lista_derivada')->where('estado', 'borrador')->update(['estado' => 'activa']);
        Schema::table('lista_derivada', function (Blueprint $table) {
            $table->string('estado', 20)->default('activa')->change();
        });
    }

    public function down(): void {}
};
