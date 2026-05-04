<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('estado', 20)->default('activo')->after('creado_por');
            $table->foreignId('anulado_por')->nullable()->constrained('users')->nullOnDelete()->after('estado');
            $table->timestamp('anulado_at')->nullable()->after('anulado_por');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['anulado_por']);
            $table->dropColumn(['estado', 'anulado_por', 'anulado_at']);
        });
    }
};
