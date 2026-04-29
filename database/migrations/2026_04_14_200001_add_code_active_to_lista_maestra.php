<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->string('code', 30)->nullable()->after('id');
            $table->boolean('active')->default(true)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('lista_maestra', function (Blueprint $table) {
            $table->dropColumn(['code', 'active']);
        });
    }
};
