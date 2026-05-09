<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear rol credito si no existe
        $roleId = DB::table('roles')->where('name', 'credito')->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name'       => 'credito',
                'guard_name' => 'web',
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Asignar tipo=administrativo y rol credito al usuario con email 'credito'
        $user = DB::table('users')->where('email', 'credito')->first();
        if ($user) {
            DB::table('users')->where('id', $user->id)->update(['tipo' => 'administrativo']);

            $tieneRol = DB::table('model_has_roles')
                ->where('role_id', $roleId)
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $user->id)
                ->exists();

            if (!$tieneRol) {
                DB::table('model_has_roles')
                    ->where('model_type', 'App\\Models\\User')
                    ->where('model_id', $user->id)
                    ->delete();

                DB::table('model_has_roles')->insert([
                    'role_id'    => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id'   => $user->id,
                ]);
            }
        }

        // 3. Asignar permisos puede_ver al rol credito
        $slugs = [
            'credito-clientes', 'credito-cobranza', 'credito-espera', 'credito-revision',
            'credito-aprobado', 'credito-pagos-pasarela', 'credito-pagos-manuales',
            'credito-reprog-nueva', 'credito-reprog-historial', 'credito-pagos-historial',
            'credito-indicadores-calificacion', 'credito-indicadores-calificacion-clientes',
        ];

        foreach ($slugs as $slug) {
            $subId = DB::table('submodulos')->where('slug', $slug)->value('id');
            if (!$subId) continue;

            DB::table('rol_submodulo_permiso')->updateOrInsert(
                ['role_id' => $roleId, 'submodulo_id' => $subId],
                ['puede_ver' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Limpiar caché de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void {}
};
