<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 500)->nullable();          // SVG path data
            $table->string('color', 30)->default('lavanda');  // pastel color key
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('submodulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();   // sub-grupo (ej. Catálogo)
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('route_name', 150)->nullable();         // null en subgrupos
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['modulo_id', 'slug']);
            $table->foreign('parent_id')->references('id')->on('submodulos')->nullOnDelete();
        });

        Schema::create('rol_submodulo_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('submodulo_id')->constrained('submodulos')->cascadeOnDelete();
            $table->boolean('puede_ver')->default(false);
            $table->timestamps();

            $table->unique(['role_id', 'submodulo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_submodulo_permiso');
        Schema::dropIfExists('submodulos');
        Schema::dropIfExists('modulos');
    }
};
