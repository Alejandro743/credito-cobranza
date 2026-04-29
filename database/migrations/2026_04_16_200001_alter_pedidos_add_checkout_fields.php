<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('financial_matrix_id')
                  ->nullable()
                  ->after('vendedor_id')
                  ->constrained('financial_matrices')
                  ->nullOnDelete();
            $table->json('matriz_snapshot')->nullable()->after('financial_matrix_id');
            $table->text('direccion_entrega')->nullable()->after('notas');
            $table->decimal('total_pagar', 12, 2)->default(0)->after('total');
            $table->decimal('cuota_inicial', 10, 2)->default(0)->after('total_pagar');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['financial_matrix_id']);
            $table->dropColumn(['financial_matrix_id', 'matriz_snapshot', 'direccion_entrega', 'total_pagar', 'cuota_inicial']);
        });
    }
};
