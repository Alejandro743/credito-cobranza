<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lista_derivada_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_derivada_id')
                  ->constrained('lista_derivada')
                  ->cascadeOnDelete();
            $table->foreignId('lista_maestra_item_id')
                  ->constrained('lista_maestra_items')
                  ->cascadeOnDelete();
            $table->decimal('descuento', 8, 2)->default(0);
            $table->decimal('stock_asignado', 12, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['lista_derivada_id', 'lista_maestra_item_id'], 'ld_items_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('lista_derivada_items');
    }
};
