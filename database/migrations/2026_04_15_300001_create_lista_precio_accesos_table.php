<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lista_precio_accesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_maestra_id')
                  ->constrained('lista_maestra')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->enum('tipo', ['vendedor', 'cliente']);
            $table->enum('origen', ['manual', 'sql'])->default('manual');
            $table->timestamps();
            $table->unique(['lista_maestra_id', 'user_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_precio_accesos');
    }
};
