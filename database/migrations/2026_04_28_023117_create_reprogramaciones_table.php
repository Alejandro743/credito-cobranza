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
        Schema::create('reprogramaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_viejo_id')->nullable()->constrained('plan_pagos')->nullOnDelete();
            $table->foreignId('plan_nuevo_id')->constrained('plan_pagos')->cascadeOnDelete();
            $table->unsignedSmallInteger('version_anterior')->default(1);
            $table->unsignedSmallInteger('version_nueva');
            $table->decimal('saldo_reprogramado', 12, 2)->default(0);
            $table->unsignedSmallInteger('cuotas_pagadas')->default(0);
            $table->text('motivo');
            $table->foreignId('creado_por')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reprogramaciones');
    }
};
