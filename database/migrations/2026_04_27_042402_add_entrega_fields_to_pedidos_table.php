<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('entrega_ciudad',    150)->nullable()->after('notas');
            $table->string('entrega_provincia', 150)->nullable()->after('entrega_ciudad');
            $table->string('entrega_municipio', 150)->nullable()->after('entrega_provincia');
            $table->string('entrega_direccion', 500)->nullable()->after('entrega_municipio');
            $table->string('entrega_referencia',500)->nullable()->after('entrega_direccion');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('direccion_entrega');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->text('direccion_entrega')->nullable()->after('notas');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn([
                'entrega_ciudad', 'entrega_provincia', 'entrega_municipio',
                'entrega_direccion', 'entrega_referencia',
            ]);
        });
    }
};
