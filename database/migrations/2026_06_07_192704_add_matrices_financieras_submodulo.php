<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $definiciones = DB::table('submodulos')->where('slug', 'definiciones')->first();
        if (!$definiciones) return;

        $existe = DB::table('submodulos')->where('slug', 'def-matrices-financieras')->exists();
        if ($existe) {
            DB::table('submodulos')->where('slug', 'def-matrices-financieras')
                ->update(['sort_order' => 2]);
        } else {
            DB::table('submodulos')->insert([
                'modulo_id'  => $definiciones->modulo_id,
                'parent_id'  => $definiciones->id,
                'name'       => 'Matrices Financieras',
                'slug'       => 'def-matrices-financieras',
                'route_name' => 'admin.definiciones.matrices-financieras',
                'sort_order' => 2,
                'active'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (['def-peso-indicadores' => 3, 'def-rango-calificacion' => 4, 'def-motivo-cierre' => 5] as $slug => $order) {
            DB::table('submodulos')->where('slug', $slug)->update(['sort_order' => $order]);
        }

        $sub       = DB::table('submodulos')->where('slug', 'def-matrices-financieras')->first();
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        if ($sub && $adminRole) {
            DB::table('rol_submodulo_permiso')->updateOrInsert(
                ['role_id' => $adminRole->id, 'submodulo_id' => $sub->id],
                ['puede_ver' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        $sub = DB::table('submodulos')->where('slug', 'def-matrices-financieras')->first();
        if ($sub) {
            DB::table('rol_submodulo_permiso')->where('submodulo_id', $sub->id)->delete();
            DB::table('submodulos')->where('id', $sub->id)->delete();
        }
        foreach (['def-peso-indicadores' => 2, 'def-rango-calificacion' => 3, 'def-motivo-cierre' => 4] as $slug => $order) {
            DB::table('submodulos')->where('slug', $slug)->update(['sort_order' => $order]);
        }
    }
};
