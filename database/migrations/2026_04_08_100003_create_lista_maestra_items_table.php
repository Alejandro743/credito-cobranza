<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lista_maestra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_maestra_id')
                  ->constrained('lista_maestra')
                  ->cascadeOnDelete();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->decimal('precio_base', 12, 2)->default(0);
            $table->unsignedInteger('puntos')->default(0);
            // Stock: el usuario edita stock_actual; el sistema ajusta stock_inicial
            $table->decimal('stock_inicial', 12, 2)->default(0);
            $table->decimal('stock_consumido', 12, 2)->default(0);
            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('descuento', 8, 2)->default(0); // siempre 0 en maestra
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['lista_maestra_id', 'product_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('lista_maestra_items');
    }
};
