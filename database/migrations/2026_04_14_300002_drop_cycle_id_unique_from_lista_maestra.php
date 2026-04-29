<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropUnique('lista_maestra_cycle_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->unique('cycle_id');
        });
    }
};
