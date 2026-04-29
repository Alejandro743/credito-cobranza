<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('financial_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);

            // Cuota inicial
            $table->boolean('usa_cuota_inicial')->default(false);
            $table->string('tipo_cuota_inicial', 20)->nullable();   // porcentaje | monto_fijo
            $table->decimal('valor_cuota_inicial', 12, 2)->nullable();

            // Cuotas
            $table->unsignedSmallInteger('cantidad_cuotas')->default(1); // 1 = contado

            // Incremento
            $table->boolean('usa_incremento')->default(false);
            $table->string('tipo_incremento', 20)->nullable();   // porcentaje | monto_fijo
            $table->decimal('valor_incremento', 12, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('financial_matrices');
    }
};
