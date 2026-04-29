<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lista_derivada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_maestra_id')
                  ->constrained('lista_maestra')
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('estado', 20)->default('borrador'); // borrador | activa | cerrada
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lista_derivada');
    }
};
