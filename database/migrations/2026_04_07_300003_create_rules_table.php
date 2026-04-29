<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50)->default('segmento'); // segmento, geografica, comercial, personalizado
            $table->text('condicion')->nullable();            // expresión SQL o descripción de la condición
            $table->text('description')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('group_rule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rule_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['group_id', 'rule_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('group_rule');
        Schema::dropIfExists('rules');
    }
};
