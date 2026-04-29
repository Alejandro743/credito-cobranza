<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracion_correlativo', function (Blueprint $table) {
            $table->id();
            $table->string('prefijo', 10)->default('LN');
            $table->unsignedInteger('siguiente_numero')->default(1);
            $table->unsignedTinyInteger('longitud')->default(6);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Fila inicial por defecto
        DB::table('configuracion_correlativo')->insert([
            'prefijo'           => 'LN',
            'siguiente_numero'  => 1,
            'longitud'          => 6,
            'activo'            => true,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_correlativo');
    }
};
