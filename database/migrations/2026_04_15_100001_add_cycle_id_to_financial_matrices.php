<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('financial_matrices', function (Blueprint $table) {
            $table->foreignId('cycle_id')
                  ->nullable()
                  ->after('code')
                  ->constrained('commercial_cycles')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('financial_matrices', function (Blueprint $table) {
            $table->dropForeign(['cycle_id']);
            $table->dropColumn('cycle_id');
        });
    }
};
