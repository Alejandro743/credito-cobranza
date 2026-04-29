<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // SQLite: deshabilitar FKs temporalmente para poder recrear la tabla
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::dropIfExists('clientes');

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('vendedor_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('id_ln')->unique()->nullable();
            $table->string('ci')->unique();
            $table->string('nit')->nullable();
            $table->string('telefono');
            $table->string('ciudad');
            $table->string('provincia');
            $table->string('municipio');
            $table->string('direccion');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
