<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('financial_matrices', function (Blueprint $table) {
            $table->unsignedSmallInteger('dias_entre_cuotas')->default(30)->after('cantidad_cuotas');
        });
    }

    public function down(): void
    {
        Schema::table('financial_matrices', function (Blueprint $table) {
            $table->dropColumn('dias_entre_cuotas');
        });
    }
};
