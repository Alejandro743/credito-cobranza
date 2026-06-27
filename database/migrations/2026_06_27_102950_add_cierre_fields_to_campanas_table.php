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
        Schema::table('campanas', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_respuesta_id')->nullable()->after('observacion');
            $table->unsignedBigInteger('tipo_cancelacion_id')->nullable()->after('tipo_respuesta_id');
            $table->text('observacion_cierre')->nullable()->after('tipo_cancelacion_id');

            $table->foreign('tipo_respuesta_id')->references('id')->on('cobranza_catalogos')->nullOnDelete();
            $table->foreign('tipo_cancelacion_id')->references('id')->on('cobranza_catalogos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('campanas', function (Blueprint $table) {
            $table->dropForeign(['tipo_respuesta_id']);
            $table->dropForeign(['tipo_cancelacion_id']);
            $table->dropColumn(['tipo_respuesta_id', 'tipo_cancelacion_id', 'observacion_cierre']);
        });
    }
};
