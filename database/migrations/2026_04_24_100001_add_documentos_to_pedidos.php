<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('doc_anverso_ci',  500)->nullable()->after('direccion_entrega');
            $table->string('doc_reverso_ci',  500)->nullable()->after('doc_anverso_ci');
            $table->string('doc_anverso_doc', 500)->nullable()->after('doc_reverso_ci');
            $table->string('doc_reverso_doc', 500)->nullable()->after('doc_anverso_doc');
            $table->string('doc_aviso_luz',   500)->nullable()->after('doc_reverso_doc');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'doc_anverso_ci','doc_reverso_ci',
                'doc_anverso_doc','doc_reverso_doc','doc_aviso_luz',
            ]);
        });
    }
};
