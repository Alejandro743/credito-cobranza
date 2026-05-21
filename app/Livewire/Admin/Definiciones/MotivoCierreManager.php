<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\MotivoCierre;
use Livewire\Component;

class MotivoCierreManager extends Component
{
    public bool $showForm = false;  // panel nuevo registro

    public ?int   $editId     = null;
    public string $nombre     = '';
    public int    $afectaMora = 0;
    public int    $activo     = 1;

    public function create(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $m = MotivoCierre::findOrFail($id);
        $this->resetForm();
        $this->resetErrorBag();
        $this->editId     = $m->id;
        $this->nombre     = $m->nombre;
        $this->afectaMora = $m->afecta_mora ? 1 : 0;
        $this->activo     = $m->activo     ? 1 : 0;
        $this->showForm   = false;
    }

    public function save(): void
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $data = [
            'nombre'      => trim($this->nombre),
            'afecta_mora' => (bool) $this->afectaMora,
            'activo'      => (bool) $this->activo,
        ];

        if ($this->editId) {
            MotivoCierre::findOrFail($this->editId)->update($data);
        } else {
            MotivoCierre::create($data);
        }

        session()->flash('success', 'Motivo guardado.');
        $this->cancelar();
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $m = MotivoCierre::findOrFail($id);

        if ($m->cierres()->exists()) {
            session()->flash('error', 'No se puede eliminar: el motivo ya fue utilizado en cierres de pedidos.');
            return;
        }

        $m->delete();
    }

    private function resetForm(): void
    {
        $this->editId     = null;
        $this->nombre     = '';
        $this->afectaMora = 0;
        $this->activo     = 1;
    }

    public function render()
    {
        return view('livewire.admin.definiciones.motivo-cierre-manager', [
            'registros' => MotivoCierre::orderByDesc('activo')->orderBy('nombre')->get(),
        ]);
    }
}
