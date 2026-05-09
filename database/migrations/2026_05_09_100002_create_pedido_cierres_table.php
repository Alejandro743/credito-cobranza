<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_cierres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('plan_pago_id')->constrained('plan_pagos')->cascadeOnDelete();
            $table->foreignId('motivo_cierre_id')->constrained('motivo_cierres');
            $table->text('observacion')->nullable();
            $table->foreignId('cerrado_por')->constrained('users');
            // Reversión
            $table->timestamp('revertido_at')->nullable();
            $table->foreignId('revertido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('motivo_reversion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_cierres');
    }
};
