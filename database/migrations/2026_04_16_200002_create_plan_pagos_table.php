<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnDelete();
            $table->string('matriz_nombre');
            $table->integer('cantidad_cuotas');
            $table->decimal('cuota_inicial',   10, 2)->default(0);
            $table->decimal('saldo_financiar', 12, 2)->default(0);
            $table->decimal('incremento',      10, 2)->default(0);
            $table->decimal('monto_cuota',     10, 2)->default(0);
            $table->decimal('total_pagar',     12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_pagos');
    }
};
