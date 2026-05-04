<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('plan_pagos', function (Blueprint $table) {
                $table->dropForeign(['pedido_id']);
                $table->dropUnique('plan_pagos_pedido_id_unique');
                $table->foreign('pedido_id')->references('id')->on('pedidos')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('plan_pagos', function (Blueprint $table) {
                $table->dropForeign(['pedido_id']);
                $table->unique('pedido_id');
                $table->foreign('pedido_id')->references('id')->on('pedidos')->cascadeOnDelete();
            });
        }
    }
};
