<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peso_indicadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->decimal('peso_puntualidad',   5, 2)->default(25.00);
            $table->decimal('peso_mora',           5, 2)->default(25.00);
            $table->decimal('peso_riesgo',         5, 2)->default(20.00);
            $table->decimal('peso_recuperacion',   5, 2)->default(20.00);
            $table->decimal('peso_reprogramacion', 5, 2)->default(10.00);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peso_indicadores');
    }
};
