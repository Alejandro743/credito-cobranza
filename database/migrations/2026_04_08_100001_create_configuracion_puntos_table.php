<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('configuracion_puntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')
                  ->unique()
                  ->constrained('commercial_cycles')
                  ->cascadeOnDelete();
            $table->decimal('valor_punto', 10, 2)->default(1.00); // Bs por punto
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('configuracion_puntos');
    }
};
