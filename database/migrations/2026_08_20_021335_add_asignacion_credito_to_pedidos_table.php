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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('asignado_por_id')->nullable()->after('revisado_por')->constrained('users')->nullOnDelete();
            $table->foreignId('asignado_a_id')->nullable()->after('asignado_por_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asignado_por_id');
            $table->dropConstrainedForeignId('asignado_a_id');
        });
    }
};
