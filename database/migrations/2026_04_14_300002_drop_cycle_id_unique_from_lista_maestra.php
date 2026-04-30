<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropForeign(['cycle_id']);
            $table->dropUnique('lista_maestra_cycle_id_unique');
            $table->foreign('cycle_id')->references('id')->on('commercial_cycles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropForeign(['cycle_id']);
            $table->unique('cycle_id');
            $table->foreign('cycle_id')->references('id')->on('commercial_cycles')->cascadeOnDelete();
        });
    }
};
