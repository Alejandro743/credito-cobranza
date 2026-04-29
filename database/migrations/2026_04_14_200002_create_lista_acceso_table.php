<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lista_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_maestra_id')
                  ->constrained('lista_maestra')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('tipo', 20);    // cliente | vendedor
            $table->string('origen', 20)->default('manual'); // manual | sql
            $table->timestamps();

            $table->unique(['lista_maestra_id', 'user_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_acceso');
    }
};
