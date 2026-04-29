<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ConfiguracionCorrelativo;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $roles = ['admin', 'vendedor', 'cliente'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // ── Usuario admin ─────────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@credito.test'],
            [
                'name'     => 'Administrador',
                'apellido' => '',
                'password' => Hash::make('password'),
                'tipo'     => 'administrativo',
                'active'   => true,
            ]
        );
        $admin->syncRoles(['admin']);

        // ── Usuario vendedor (prueba) ──────────────────────────────────────────
        $vendedor = User::updateOrCreate(
            ['email' => 'vendedor@credito.test'],
            [
                'name'     => 'Vendedor',
                'apellido' => 'Demo',
                'password' => Hash::make('password'),
                'tipo'     => 'vendedor',
                'active'   => true,
            ]
        );
        $vendedor->syncRoles(['vendedor']);

        // Crear registro en vendedores si no existe
        $vendedorRecord = Vendedor::firstOrCreate(
            ['user_id' => $vendedor->id],
            [
                'nombre'   => 'Vendedor',
                'apellido' => 'Demo',
                'telefono' => '70000001',
                'email'    => 'vendedor@credito.test',
                'activo'   => true,
            ]
        );

        // ── Usuario cliente (prueba) ───────────────────────────────────────────
        $clienteUser = User::updateOrCreate(
            ['email' => 'cliente@credito.test'],
            [
                'name'     => 'Cliente',
                'apellido' => 'Demo',
                'password' => Hash::make('password'),
                'tipo'     => 'cliente',
                'active'   => true,
            ]
        );
        $clienteUser->syncRoles(['cliente']);

        // Asegurar configuracion correlativo
        ConfiguracionCorrelativo::firstOrCreate(
            ['activo' => true],
            ['prefijo' => 'LN', 'siguiente_numero' => 1, 'longitud' => 6]
        );

        // Crear registro en clientes si no existe
        if (!Cliente::where('usuario_id', $clienteUser->id)->exists()) {
            Cliente::create([
                'usuario_id'  => $clienteUser->id,
                'vendedor_id' => $vendedor->id,
                'id_ln'       => ConfiguracionCorrelativo::generarIdLN(),
                'ci'          => '00000001',
                'nit'         => null,
                'telefono'    => '60000001',
                'ciudad'      => 'La Paz',
                'provincia'   => 'Murillo',
                'municipio'   => 'La Paz',
                'direccion'   => 'Dirección demo',
                'active'      => true,
            ]);
        }
    }
}
