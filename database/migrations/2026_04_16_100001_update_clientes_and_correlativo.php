<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('ci');
            $table->string('correo')->nullable()->after('nit');
        });

        Schema::table('configuracion_correlativo', function (Blueprint $table) {
            $table->string('descripcion')->nullable()->after('longitud');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['apellido', 'correo']);
        });
        Schema::table('configuracion_correlativo', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
