<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_pago_id')->constrained('plan_pagos')->cascadeOnDelete();
            $table->decimal('monto_total', 12, 2);
            $table->unsignedTinyInteger('cantidad_cuotas');
            $table->foreignId('creado_por')->constrained('users');
            $table->timestamps();
        });

        Schema::table('cuotas', function (Blueprint $table) {
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->nullOnDelete()->after('plan_pago_id');
        });
    }

    public function down(): void
    {
        Schema::table('cuotas', function (Blueprint $table) {
            $table->dropForeign(['pago_id']);
            $table->dropColumn('pago_id');
        });
        Schema::dropIfExists('pagos');
    }
};
