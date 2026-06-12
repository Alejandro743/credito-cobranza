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
            $table->decimal('stock_comprometido', 12, 2)->default(0)->after('stock_consumido');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra_items', function (Blueprint $table) {
            $table->dropColumn('stock_comprometido');
        });
    }
};
