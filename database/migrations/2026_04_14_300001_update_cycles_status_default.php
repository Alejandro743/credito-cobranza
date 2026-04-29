<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Convertir borrador existentes a abierto
        DB::table('commercial_cycles')
            ->where('status', 'borrador')
            ->update(['status' => 'abierto']);

        Schema::table('commercial_cycles', function (Blueprint $table) {
            $table->string('status', 20)->default('abierto')->change();
        });
    }

    public function down(): void
    {
        Schema::table('commercial_cycles', function (Blueprint $table) {
            $table->string('status', 20)->default('borrador')->change();
        });
    }
};
