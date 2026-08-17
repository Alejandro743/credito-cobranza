<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE pedido_items MODIFY product_id BIGINT UNSIGNED NULL');
        } else {
            // SQLite no soporta ALTER COLUMN sin doctrine/dbal: recreamos la tabla.
            Schema::disableForeignKeyConstraints();
            DB::statement('
                CREATE TABLE pedido_items_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    pedido_id INTEGER NOT NULL,
                    lista_maestra_item_id INTEGER NOT NULL,
                    product_id INTEGER NULL,
                    cantidad INTEGER NOT NULL,
                    precio_unitario NUMERIC NOT NULL,
                    puntos INTEGER NOT NULL DEFAULT 0,
                    subtotal NUMERIC NOT NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
                    FOREIGN KEY (lista_maestra_item_id) REFERENCES lista_maestra_items(id),
                    FOREIGN KEY (product_id) REFERENCES products(id)
                )
            ');
            DB::statement('INSERT INTO pedido_items_new SELECT * FROM pedido_items');
            DB::statement('DROP TABLE pedido_items');
            DB::statement('ALTER TABLE pedido_items_new RENAME TO pedido_items');
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE pedido_items MODIFY product_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
