<?php

namespace App\Livewire\Admin\Definiciones;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\ConfiguracionCorrelativo;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CorrelativoManager extends Component
{
    use WithPagination, HasModuleColor;

    // ── Inline add ────────────────────────────────────────────────────────────
    public bool   $showAddForm       = false;
    public string $newPrefijo        = '';
    public string $newSiguienteNumero= '1';
    public string $newLongitud       = '6';
    public string $newDescripcion    = '';
    public bool   $newActivo         = true;

    // ── Inline edit ───────────────────────────────────────────────────────────
    public ?int   $editingId          = null;
    public string $editPrefijo        = '';
    public string $editSiguienteNumero= '';
    public string $editLongitud       = '';
    public string $editDescripcion    = '';
    public bool   $editActivo         = true;

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->initModuleColor();
    }

    // ── Inline add ────────────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm        = true;
        $this->editingId          = null;
        $this->newPrefijo         = '';
        $this->newSiguienteNumero = '1';
        $this->newLongitud        = '6';
        $this->newDescripcion     = '';
        $this->newActivo          = true;
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
    }

    public function saveNew(): void
    {
        $this->validate([
            'newPrefijo'         => ['required','string','max:10','regex:/^[A-Za-z0-9]+$/'],
            'newSiguienteNumero' => ['required','integer','min:1'],
            'newLongitud'        => ['required','integer','min:1','max:10'],
            'newDescripcion'     => ['nullable','string','max:200'],
        ]);

        ConfiguracionCorrelativo::create([
            'prefijo'           => strtoupper($this->newPrefijo),
            'siguiente_numero'  => (int) $this->newSiguienteNumero,
            'longitud'          => (int) $this->newLongitud,
            'descripcion'       => $this->newDescripcion ?: null,
            'activo'            => $this->newActivo,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Correlativo creado.');
    }

    // ── Inline edit ───────────────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $c = ConfiguracionCorrelativo::findOrFail($id);
        $this->editingId           = $id;
        $this->editPrefijo         = $c->prefijo;
        $this->editSiguienteNumero = (string) $c->siguiente_numero;
        $this->editLongitud        = (string) $c->longitud;
        $this->editDescripcion     = $c->descripcion ?? '';
        $this->editActivo          = $c->activo;
        $this->showAddForm         = false;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editPrefijo'         => ['required','string','max:10','regex:/^[A-Za-z0-9]+$/'],
            'editSiguienteNumero' => ['required','integer','min:1'],
            'editLongitud'        => ['required','integer','min:1','max:10'],
            'editDescripcion'     => ['nullable','string','max:200'],
        ]);

        ConfiguracionCorrelativo::findOrFail($this->editingId)->update([
            'prefijo'          => strtoupper($this->editPrefijo),
            'siguiente_numero' => (int) $this->editSiguienteNumero,
            'longitud'         => (int) $this->editLongitud,
            'descripcion'      => $this->editDescripcion ?: null,
            'activo'           => $this->editActivo,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Correlativo actualizado.');
    }

    // ── Toggle activo ─────────────────────────────────────────────────────────

    public function toggleActivo(int $id): void
    {
        $c = ConfiguracionCorrelativo::findOrFail($id);
        $c->update(['activo' => !$c->activo]);
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function delete(int $id): void
    {
        ConfiguracionCorrelativo::findOrFail($id)->delete();
        session()->flash('success', 'Correlativo eliminado.');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $correlativos = ConfiguracionCorrelativo::orderBy('id')->paginate(20);
        return view('livewire.admin.definiciones.correlativo-manager', compact('correlativos'));
    }
}
