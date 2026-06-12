<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ciclo_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_cycle_id')->constrained('commercial_cycles')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('stock_total', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['commercial_cycle_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclo_productos');
    }
};
