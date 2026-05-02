<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rango_calificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->decimal('min_a', 5, 2)->default(85.00);
            $table->decimal('min_b', 5, 2)->default(70.00);
            $table->decimal('min_c', 5, 2)->default(50.00);
            $table->decimal('min_d', 5, 2)->default(30.00);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rango_calificaciones');
    }
};
