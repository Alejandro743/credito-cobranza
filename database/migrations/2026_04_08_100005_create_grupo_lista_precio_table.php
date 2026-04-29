<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grupo_lista_precio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                  ->constrained('groups')
                  ->cascadeOnDelete();
            $table->foreignId('lista_derivada_id')
                  ->constrained('lista_derivada')
                  ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['group_id', 'lista_derivada_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('grupo_lista_precio');
    }
};
