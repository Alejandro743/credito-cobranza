<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('name', 100)->unique()->change();
            $table->string('code', 30)->nullable()->change();
        });

        Schema::table('unidades', function (Blueprint $table) {
            $table->string('name', 100)->unique()->change();
            $table->string('code', 30)->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('code', 30)->nullable(false)->change();
        });
        Schema::table('unidades', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('code', 30)->nullable(false)->change();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('code', 50)->nullable(false)->change();
        });
    }
};
