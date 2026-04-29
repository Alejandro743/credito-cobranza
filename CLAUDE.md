# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Environment

- **PHP:** `/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe`
- **Composer:** `/c/laragon/bin/composer/composer.phar`
- **MySQL:** `/c/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysql.exe` (root, no password — requires Laragon running)
- **Dev URL:** `http://credito-cobranza.test` (via Laragon virtual host)

Use the full Laragon PHP binary for all `artisan` commands:
```bash
/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe artisan <command>
```

## Common Commands

```bash
# Reset DB and re-seed (standard dev workflow)
/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe artisan migrate:fresh --seed

# Run tests
composer test

# Run a single test file
/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe artisan test tests/Feature/ExampleTest.php

# Lint (Laravel Pint)
./vendor/bin/pint
```

## Stack

- **Laravel 13** + **PHP 8.3** on Laragon Windows
- **Breeze** (Blade + Tailwind CDN) for auth scaffolding
- **Livewire 4** for all reactive UI — full-page component pattern (no modals)
- **Alpine.js** for sidebar accordions and minor interactivity
- **Spatie Laravel Permission** for role management (`roles` table only — Spatie permissions are unused)
- **Custom submodule permission layer** (`rol_submodulo_permiso` table) on top of Spatie roles

## Architecture

### Permission System (two-layer)

1. **Spatie roles** (`roles` table): Coarse gating — `role:admin` middleware and `hasRole()` checks only. Spatie's `permissions` table is **not used**.

2. **Submodule permissions** (`rol_submodulo_permiso` table): Fine-grained access per leaf submodule. Single column: `puede_ver` (boolean). Groups (non-leaf submodules) never have permission rows.

**`PermisoService`** (`app/Services/PermisoService.php`):
- `check($user, $slug)` — checks `puede_ver` by submodule slug
- `checkByRoute($user, $routeName)` — checks by route name; routes not mapped in `submodulos` pass freely
- Cache is per-request (resets automatically via `spl_object_id(request())`)
- `admin` role always bypasses all checks

### Web Middleware Chain

Three middlewares in `bootstrap/app.php`, running on every request:
1. **`EnsureUserIsActivo`** — logs out users linked to an inactive `Vendedor` or `Cliente` record
2. **`EnsureRoleIsActivo`** — redirects to `/access/desactivado` if the user's Spatie role has `activo = false`; admin role is exempt

Route-level:
- **`role:admin`** — Spatie role check for `/admin/security/*`
- **`submodulo.permiso`** (`CheckSubmoduloPermiso`) — calls `PermisoService::checkByRoute`

### Route Structure

| Prefix | Middleware | Notes |
|--------|-----------|-------|
| `/admin/security/*` | `auth`, `role:admin` | Only admin |
| `/admin/catalogo/*` | `auth`, `submodulo.permiso` | Productos, Categorías, Unidades |
| `/admin/listas/*` | `auth`, `submodulo.permiso` | Lista Maestra, Listas Derivadas |
| `/admin/grupos/`, `/admin/reglas/` | `auth`, `submodulo.permiso` | |
| `/admin/ciclo/*` | `auth`, `submodulo.permiso` | Ciclos Comerciales, Puntos |
| `/admin/finance/` | `auth`, `submodulo.permiso` | Matrices Financieras |
| `/credito/*`, `/vendedor/*`, `/cliente/*` | `auth`, `submodulo.permiso` | Permission-gated |

`/dashboard` redirects: admin → `admin.dashboard`, others → first submodule with `puede_ver = true` ordered by `sort_order`.

### Modules / Submodules — 3-Level Nested Structure

`modulos` and `submodulos` tables with self-referential `parent_id` on `submodulos`:
- **Level 1**: Modulo (Administrativo, Crédito/Cobranza, Vendedor/EIE, Cliente)
- **Level 2**: Submodulo root — either a **group** (`route_name = null`) or a direct leaf
- **Level 3**: Children submodulos (leaves with `route_name`) under a group

`Submodulo::isGroup()` returns `true` when `route_name` is null. Only leaves have `puede_ver` permission rows and route mappings.

Sidebar (`admin-layout.blade.php`) loads `submodulos()->with('children')` and renders 2-level accordion using Alpine `x-data="{ open: false }"` and nested `x-data="{ subOpen: false }"`.

**Adding a new protected route**: add the route, add a `submodulos` row (with parent_id if nested), re-run seeders.

### Livewire Component Pattern

All admin components use `$mode = 'list' | 'form' | ...` — **no modals**. Pattern:
```php
public string $mode = 'list';
public function create(): void { $this->resetForm(); $this->mode = 'form'; }
public function edit(int $id): void { /* load, then */ $this->mode = 'form'; }
public function save(): void { /* validate, persist, */ $this->backToList(); }
public function backToList(): void { $this->resetForm(); $this->mode = 'list'; }
private function resetForm(): void { /* reset fields */ }
```

### Key Domain Models

```
CommercialCycle
  ├── listaMaestra()       HasOne → ListaMaestra        (one per cycle, unique)
  └── configuracionPuntos() HasOne → ConfiguracionPuntos (one per cycle)

ListaMaestra
  ├── items()              HasMany → ListaMaestraItem
  └── listaDerivadas()     HasMany → ListaDerivada

ListaMaestraItem
  └── ajustarStock(float $nuevoActual): void
      # diff = new - current; stock_inicial += diff; stock_actual = new; (consumido untouched)

ListaDerivada
  ├── items()              HasMany → ListaDerivadaItem
  └── groups()             BelongsToMany → Group via grupo_lista_precio

ListaDerivadaItem
  └── getPrecioFinalAttribute(): max(0, precio_base - descuento)
      # accessor: $item->precio_final

FinancialMatrix            (replaces old FinancialPlan)
  fields: code, name, description, active, usa_cuota_inicial, tipo_cuota_inicial,
          valor_cuota_inicial, cantidad_cuotas, usa_incremento, tipo_incremento, valor_incremento

Group
  ├── rules()              BelongsToMany → Rule
  ├── listaDerivadas()     BelongsToMany → ListaDerivada via grupo_lista_precio
  └── miembrosManual()     HasMany → GrupoMiembroManual

Product
  ├── categoria()          BelongsTo → Categoria         (has code, descripcion)
  └── unidad()             BelongsTo → Unidad            (has code, abreviatura — was "simbolo")
```

### Seeder Order (enforced in DatabaseSeeder)

1. `RolesAndUsersSeeder` — creates roles + test users
2. `ModulosSubmodulosSeeder` — creates 4-module, 3-level submodule tree
3. `RolesPermisosDefaultSeeder` — assigns `puede_ver` permissions; admin wildcard picks up all leaf submodulos automatically

### Tailwind Pastel Colors (CDN config)

Custom colors in Tailwind CDN config: `lavanda`, `mint`, `melocoton`, `celeste`. Each with shades 50–700. Used consistently for module/section color-coding.

## Test Users (password: `password`)

| Email | Role |
|-------|------|
| `admin@credito.test` | admin |
| `credito@credito.test` | credito |
| `vendedor@credito.test` | vendedor |
| `cliente@credito.test` | cliente |
