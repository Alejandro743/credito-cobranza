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
        Schema::table('lista_maestra_items', function (Blueprint $table) {
            $table->foreignId('maestro_articulo_id')
                  ->nullable()
                  ->after('product_id')
                  ->constrained('maestro_articulos')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra_items', function (Blueprint $table) {
            $table->dropForeign(['maestro_articulo_id']);
            $table->dropColumn('maestro_articulo_id');
        });
    }
};
