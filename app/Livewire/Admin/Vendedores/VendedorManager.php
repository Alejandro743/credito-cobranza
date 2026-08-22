<?php

namespace App\Livewire\Admin\Vendedores;

use App\Models\Ciudad;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class VendedorManager extends Component
{
    use WithPagination, HasModuleColor, WithFileUploads;

    public string $sortBy  = 'apellido';
    public string $sortDir = 'asc';

    public string $colFilterCodigoUsuario = '';
    public string $colFilterNombre        = '';
    public string $colFilterApellido      = '';
    public string $colFilterCi            = '';
    public string $colFilterTelefono      = '';
    public string $colFilterEmail         = '';
    public string $colFilterCiudad        = '';
    public string $colFilterEstado        = '';
    public string $colFilterAcceso        = '';
    public string $colFilterRol           = '';

    public ?int $selectedVendedorId = null;

    // ── Import CSV ──────────────────────────────────────────────────────────
    public bool  $showImportModal       = false;
    public       $importFile            = null;
    public bool  $showImportResultModal = false;
    public array $importResult          = [];

    public function selectVendedor(int $id): void
    {
        $this->selectedVendedorId = $this->selectedVendedorId === $id ? null : $id;
    }

    public function toggleSort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function updatingColFilterCodigoUsuario(): void { $this->resetPage(); }
    public function updatingColFilterNombre():        void { $this->resetPage(); }
    public function updatingColFilterApellido():      void { $this->resetPage(); }
    public function updatingColFilterCi():            void { $this->resetPage(); }
    public function updatingColFilterTelefono():      void { $this->resetPage(); }
    public function updatingColFilterEmail():         void { $this->resetPage(); }
    public function updatingColFilterCiudad():        void { $this->resetPage(); }
    public function updatingColFilterEstado():        void { $this->resetPage(); }
    public function updatingColFilterAcceso():        void { $this->resetPage(); }
    public function updatingColFilterRol():           void { $this->resetPage(); }

    public function refrescarGrilla(): void {}

    // ── Panel alta ───────────────────────────────────────────────────────────
    public bool   $showAddForm      = false;
    public string $newCodigoUsuario = '';
    public string $newNombre        = '';
    public string $newApellido      = '';
    public string $newCi            = '';
    public string $newTelefono      = '';
    public string $newEmail         = '';
    public string $newCiudadId      = '';
    public int    $newActivo        = 1;
    public bool   $newTieneAcceso   = false;
    public string $newUserPassword  = '';
    public string $newUserRol       = '';

    // ── Fila edición inline ──────────────────────────────────────────────────
    public ?int   $editingId          = null;
    public string $editCodigoUsuario  = '';
    public string $editNombre         = '';
    public string $editApellido       = '';
    public string $editCi             = '';
    public string $editTelefono       = '';
    public string $editEmail          = '';
    public string $editCiudadId       = '';
    public int    $editActivo         = 1;
    public bool   $editTieneAcceso    = false;
    public ?int   $editUserIdActual   = null;
    public string $editUserPassword   = '';
    public string $editUserRol        = '';

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Alta ─────────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm     = true;
        $this->editingId       = null;
        $this->newCodigoUsuario = '';
        $this->newNombre       = '';
        $this->newApellido     = '';
        $this->newCi           = '';
        $this->newTelefono     = '';
        $this->newEmail        = '';
        $this->newCiudadId     = '';
        $this->newActivo       = 1;
        $this->newTieneAcceso  = false;
        $this->newUserPassword = '';
        $this->newUserRol      = '';
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $codigoRules = ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z0-9._@-]+$/', Rule::unique('vendedores', 'codigo_usuario')];
        if ($this->newTieneAcceso) {
            $codigoRules[] = Rule::unique('users', 'email');
        }

        $this->validate([
            'newCodigoUsuario' => $codigoRules,
            'newNombre'        => 'required|string|min:2|max:100',
            'newApellido'      => 'required|string|min:2|max:100',
            'newCi'            => ['required', 'string', 'max:20', Rule::unique('vendedores', 'ci')],
            'newTelefono'      => 'nullable|string|max:30',
            'newEmail'         => 'nullable|email|max:150',
            'newCiudadId'      => 'nullable|exists:ciudades,id',
            'newUserPassword'  => $this->newTieneAcceso ? 'required|min:6' : 'nullable|min:6',
            'newUserRol'       => $this->newTieneAcceso ? 'required|exists:roles,name' : 'nullable',
        ], [
            'newCodigoUsuario.unique' => 'Ese código de usuario ya está en uso.',
            'newCodigoUsuario.regex'  => 'Solo letras, números, punto, guión, guión bajo o @.',
            'newCi.unique'            => 'Ese CI ya está registrado en otro vendedor.',
        ], [
            'newCodigoUsuario' => 'código de usuario',
            'newNombre'        => 'nombre',
            'newApellido'      => 'apellido',
            'newCi'            => 'CI',
            'newTelefono'      => 'teléfono',
            'newEmail'         => 'email',
            'newCiudadId'      => 'ciudad',
            'newUserPassword'  => 'contraseña',
            'newUserRol'       => 'rol',
        ]);

        $userId = null;
        if ($this->newTieneAcceso) {
            $user = User::create([
                'name'     => "{$this->newNombre} {$this->newApellido}",
                'email'    => $this->newCodigoUsuario,
                'password' => Hash::make($this->newUserPassword),
                'tipo'     => 'vendedor',
            ]);
            $user->assignRole($this->newUserRol);
            $userId = $user->id;
        }

        Vendedor::create([
            'codigo_usuario' => $this->newCodigoUsuario,
            'nombre'         => $this->newNombre,
            'apellido'       => $this->newApellido,
            'ci'             => $this->newCi       ?: null,
            'telefono'       => $this->newTelefono ?: null,
            'email'          => $this->newEmail    ?: null,
            'ciudad_id'      => $this->newCiudadId ?: null,
            'user_id'        => $userId,
            'activo'         => $this->newActivo,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Vendedor creado.');
    }

    // ── Edición inline ───────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $v = Vendedor::with('user')->findOrFail($id);

        $this->editingId        = $id;
        $this->editCodigoUsuario = $v->codigo_usuario ?? '';
        $this->editNombre       = $v->nombre;
        $this->editApellido     = $v->apellido;
        $this->editCi           = $v->ci ?? '';
        $this->editTelefono     = $v->telefono ?? '';
        $this->editEmail        = $v->email    ?? '';
        $this->editCiudadId     = (string) ($v->ciudad_id ?? '');
        $this->editActivo       = $v->activo ? 1 : 0;

        if ($v->user) {
            $this->editTieneAcceso  = true;
            $this->editUserIdActual = $v->user->id;
            $this->editUserPassword = '';
            $this->editUserRol      = $v->user->getRoleNames()->first() ?? '';
        } else {
            $this->editTieneAcceso  = false;
            $this->editUserIdActual = null;
            $this->editUserPassword = '';
            $this->editUserRol      = '';
        }

        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $codigoRules = ['required', 'string', 'min:3', 'max:80', 'regex:/^[a-zA-Z0-9._@-]+$/', Rule::unique('vendedores', 'codigo_usuario')->ignore($this->editingId)];
        if ($this->editTieneAcceso) {
            $codigoRules[] = $this->editUserIdActual
                ? Rule::unique('users', 'email')->ignore($this->editUserIdActual)
                : Rule::unique('users', 'email');
        }

        $this->validate([
            'editCodigoUsuario' => $codigoRules,
            'editNombre'        => 'required|string|min:2|max:100',
            'editApellido'      => 'required|string|min:2|max:100',
            'editCi'            => ['required', 'string', 'max:20', Rule::unique('vendedores', 'ci')->ignore($this->editingId)],
            'editTelefono'      => 'nullable|string|max:30',
            'editEmail'         => 'nullable|email|max:150',
            'editCiudadId'      => 'nullable|exists:ciudades,id',
            'editUserPassword'  => $this->editTieneAcceso && !$this->editUserIdActual ? 'required|min:6' : 'nullable|min:6',
            'editUserRol'       => $this->editTieneAcceso ? 'required|exists:roles,name' : 'nullable',
        ], [
            'editCodigoUsuario.unique' => 'Ese código de usuario ya está en uso.',
            'editCodigoUsuario.regex'  => 'Solo letras, números, punto, guión, guión bajo o @.',
            'editCi.unique'            => 'Ese CI ya está registrado en otro vendedor.',
        ], [
            'editCodigoUsuario' => 'código de usuario',
            'editNombre'        => 'nombre',
            'editApellido'      => 'apellido',
            'editCi'            => 'CI',
            'editTelefono'      => 'teléfono',
            'editEmail'         => 'email',
            'editCiudadId'      => 'ciudad',
            'editUserPassword'  => 'contraseña',
            'editUserRol'       => 'rol',
        ]);

        $userId = null;
        if ($this->editTieneAcceso) {
            if ($this->editUserIdActual) {
                $user = User::findOrFail($this->editUserIdActual);
                $user->email = $this->editCodigoUsuario;
                $user->tipo  = 'vendedor';
                if ($this->editUserPassword) {
                    $user->password = Hash::make($this->editUserPassword);
                }
                $user->save();
                $user->syncRoles([$this->editUserRol]);
                $userId = $user->id;
            } else {
                $user = User::create([
                    'name'     => "{$this->editNombre} {$this->editApellido}",
                    'email'    => $this->editCodigoUsuario,
                    'password' => Hash::make($this->editUserPassword),
                    'tipo'     => 'vendedor',
                ]);
                $user->assignRole($this->editUserRol);
                $userId = $user->id;
            }
        }

        Vendedor::findOrFail($this->editingId)->update([
            'codigo_usuario' => $this->editCodigoUsuario,
            'nombre'         => $this->editNombre,
            'apellido'       => $this->editApellido,
            'ci'             => $this->editCi       ?: null,
            'telefono'       => $this->editTelefono ?: null,
            'email'          => $this->editEmail    ?: null,
            'ciudad_id'      => $this->editCiudadId ?: null,
            'user_id'        => $userId,
            'activo'         => $this->editActivo,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Vendedor actualizado.');
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function filteredQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Vendedor::with(['user.roles', 'ciudad'])
            ->when($this->colFilterCodigoUsuario, fn($q) => $q->where('codigo_usuario', 'like', "%{$this->colFilterCodigoUsuario}%"))
            ->when($this->colFilterNombre,   fn($q) => $q->where('nombre',   'like', "%{$this->colFilterNombre}%"))
            ->when($this->colFilterApellido, fn($q) => $q->where('apellido', 'like', "%{$this->colFilterApellido}%"))
            ->when($this->colFilterCi,       fn($q) => $q->where('ci',       'like', "%{$this->colFilterCi}%"))
            ->when($this->colFilterTelefono, fn($q) => $q->where('telefono', 'like', "%{$this->colFilterTelefono}%"))
            ->when($this->colFilterEmail,    fn($q) => $q->where('email',    'like', "%{$this->colFilterEmail}%"))
            ->when($this->colFilterCiudad,   fn($q) => $q->whereHas('ciudad',
                fn($qq) => $qq->where('nombre', 'like', "%{$this->colFilterCiudad}%")))
            ->when($this->colFilterEstado !== '', fn($q) => $q->where('activo', $this->colFilterEstado === '1'))
            ->when($this->colFilterAcceso !== '', fn($q) =>
                $this->colFilterAcceso === '1' ? $q->whereNotNull('user_id') : $q->whereNull('user_id'))
            ->when($this->colFilterRol, fn($q) => $q->whereHas('user.roles',
                fn($qq) => $qq->where('name', $this->colFilterRol)));
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $vendedores = $this->filteredQuery()->orderBy($this->sortBy, $this->sortDir)->get();
        $filename   = 'vendedores-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($vendedores) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['Código Usuario', 'CI', 'Nombre', 'Apellido', 'Teléfono', 'Email', 'Ciudad', 'Estado', 'Acceso', 'Rol'], ';');

            foreach ($vendedores as $v) {
                fputcsv($out, [
                    $v->codigo_usuario ?? '',
                    $v->ci ?? '',
                    strtoupper($v->nombre),
                    strtoupper($v->apellido),
                    $v->telefono ?? '',
                    $v->email ?? '',
                    $v->ciudad->nombre ?? '',
                    $v->activo ? 'Activo' : 'Inactivo',
                    $v->user ? 'Si' : 'No',
                    $v->user && $v->user->roles->first() ? ucfirst($v->user->roles->first()->name) : '',
                ], ';');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ── Import CSV ───────────────────────────────────────────────────────────

    public function downloadImportTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $roles      = Role::orderBy('name')->get();
        $rolEjemplo = $roles->firstWhere('name', 'vendedor')?->name ?? $roles->first()?->name ?? 'vendedor';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Plantilla');
        $sheet1->fromArray(
            ['Código Usuario', 'CI', 'Nombre', 'Apellido', 'Teléfono', 'Email', 'Ciudad', 'Estado', 'Acceso', 'Rol'],
            null, 'A1'
        );
        $sheet1->fromArray(
            ['jperez', '1234567', 'Juan', 'Pérez', '70000000', 'juan@example.com', 'SANTA CRUZ', '1', '1', $rolEjemplo],
            null, 'A2'
        );
        $sheet1->getStyle('A1:J1')->getFont()->setBold(true);
        foreach (range('A', 'J') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Roles válidos');
        $sheet2->fromArray(['Código', 'Rol'], null, 'A1');
        $sheet2->getStyle('A1:B1')->getFont()->setBold(true);
        $fila = 2;
        foreach ($roles as $r) {
            $sheet2->setCellValue("A{$fila}", $r->name);
            $sheet2->setCellValue("B{$fila}", ucfirst($r->name));
            $fila++;
        }
        foreach (['A', 'B'] as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Estado válidos');
        $sheet3->fromArray(['Código', 'Estado'], null, 'A1');
        $sheet3->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet3->fromArray([['1', 'Activo'], ['0', 'Inactivo']], null, 'A2');
        foreach (['A', 'B'] as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Acceso válidos');
        $sheet4->fromArray(['Código', 'Acceso'], null, 'A1');
        $sheet4->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet4->fromArray([['1', 'Sí'], ['0', 'No']], null, 'A2');
        foreach (['A', 'B'] as $col) {
            $sheet4->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'formato-importacion-vendedores.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importCsv(): void
    {
        $this->validate(['importFile' => 'required|file|mimes:csv,txt|max:4096']);

        $handle = fopen($this->importFile->getRealPath(), 'r');

        $firstLine = fgets($handle);
        rewind($handle);
        $delim = substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';

        fgetcsv($handle, 0, $delim);

        $rolesValidos = Role::pluck('name')->all();

        $actualizados = 0;
        $creados      = 0;
        $errores      = [];

        while (($row = fgetcsv($handle, 0, $delim)) !== false) {
            if (count($row) < 4 || empty(trim($row[0] ?? ''))) continue;

            $codigoUsuario = trim($row[0]);
            $ci            = trim($row[1] ?? '');
            $nombre        = trim($row[2] ?? '');
            $apellido      = trim($row[3] ?? '');

            if ($ci === '' || $nombre === '' || $apellido === '') {
                $errores[] = "{$codigoUsuario} (falta CI/nombre/apellido)";
                continue;
            }

            $telefono     = trim($row[4] ?? '') ?: null;
            $email        = trim($row[5] ?? '') ?: null;
            $ciudadNombre = trim($row[6] ?? '');
            $estadoRaw    = strtolower(trim($row[7] ?? '1'));
            $activo       = in_array($estadoRaw, ['1', 'si', 'sí', 'activo', 'true', 'yes']);
            $accesoRaw    = strtolower(trim($row[8] ?? '0'));
            $quiereAcceso = in_array($accesoRaw, ['1', 'si', 'sí', 'true', 'yes']);
            $rolCsv       = strtolower(trim($row[9] ?? ''));

            $ciudadId = $ciudadNombre !== ''
                ? Ciudad::whereRaw('UPPER(nombre) = ?', [strtoupper($ciudadNombre)])->value('id')
                : null;

            $vendedor = Vendedor::where('ci', $ci)->first();

            // El código de usuario debe ser único; si ya lo usa OTRO vendedor, se salta la fila.
            $codigoEnUso = Vendedor::where('codigo_usuario', $codigoUsuario)
                ->when($vendedor, fn($q) => $q->where('id', '!=', $vendedor->id))
                ->exists();
            if ($codigoEnUso) {
                $errores[] = "{$codigoUsuario} (código de usuario ya usado por otro vendedor)";
                continue;
            }

            $data = [
                'codigo_usuario' => $codigoUsuario,
                'nombre'         => $nombre,
                'apellido'       => $apellido,
                'telefono'       => $telefono,
                'email'          => $email,
                'ciudad_id'      => $ciudadId,
                'activo'         => $activo,
            ];

            // Acceso al sistema: usuario = código de usuario, contraseña = CI.
            if ($quiereAcceso) {
                if (!in_array($rolCsv, $rolesValidos)) {
                    $errores[] = "{$codigoUsuario} (rol \"{$rolCsv}\" inválido, no se activó acceso)";
                } else {
                    $userIdExistente = $vendedor?->user_id;
                    $emailEnUso = User::where('email', $codigoUsuario)
                        ->when($userIdExistente, fn($q) => $q->where('id', '!=', $userIdExistente))
                        ->exists();

                    if ($emailEnUso) {
                        $errores[] = "{$codigoUsuario} (usuario de login ya está en uso, no se activó acceso)";
                    } elseif ($userIdExistente) {
                        $user = User::find($userIdExistente);
                        $user->email    = $codigoUsuario;
                        $user->password = Hash::make($ci);
                        $user->tipo     = 'vendedor';
                        $user->save();
                        $user->syncRoles([$rolCsv]);
                        $data['user_id'] = $user->id;
                    } else {
                        $user = User::create([
                            'name'     => "{$nombre} {$apellido}",
                            'email'    => $codigoUsuario,
                            'password' => Hash::make($ci),
                            'tipo'     => 'vendedor',
                        ]);
                        $user->assignRole($rolCsv);
                        $data['user_id'] = $user->id;
                    }
                }
            }

            if ($vendedor) {
                $vendedor->update($data);
                $actualizados++;
            } else {
                Vendedor::create(array_merge($data, ['ci' => $ci]));
                $creados++;
            }
        }

        fclose($handle);

        $this->importFile            = null;
        $this->showImportModal       = false;
        $this->importResult          = [
            'actualizados' => $actualizados,
            'creados'      => $creados,
            'errores'      => $errores,
        ];
        $this->showImportResultModal = true;
    }

    public function render()
    {
        $vendedores = $this->filteredQuery()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(15);

        $roles    = Role::orderBy('name')->get();
        $ciudades = Ciudad::orderBy('nombre')->get();

        return view('livewire.admin.vendedores.vendedor-manager',
            compact('vendedores', 'roles', 'ciudades'));
    }
}
