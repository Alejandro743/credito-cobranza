<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'              => RoleMiddleware::class,
            'permission'        => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'submodulo.permiso' => \App\Http\Middleware\CheckSubmoduloPermiso::class,
        ]);

        // Bloquea usuarios vinculados a vendedor/cliente inactivo
        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureUserIsActivo::class);
        // Bloquea acceso si el rol del usuario está desactivado
        $middleware->appendToGroup('web', \App\Http\Middleware\EnsureRoleIsActivo::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
