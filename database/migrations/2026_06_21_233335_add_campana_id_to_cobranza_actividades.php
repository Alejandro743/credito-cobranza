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
        Schema::table('cobranza_actividades', function (Blueprint $table) {
            $table->unsignedBigInteger('campana_id')->nullable()->after('caso_id');
            $table->foreign('campana_id')->references('id')->on('campanas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cobranza_actividades', function (Blueprint $table) {
            $table->dropForeign(['campana_id']);
            $table->dropColumn('campana_id');
        });
    }
};
