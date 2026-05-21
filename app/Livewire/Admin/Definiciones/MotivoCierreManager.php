<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\MotivoCierre;
use Livewire\Component;

class MotivoCierreManager extends Component
{
    public bool   $showForm  = false;

    public ?int   $editId     = null;
    public string $nombre     = '';
    public bool   $afectaMora = false;
    public bool   $activo     = true;

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
        $this->afectaMora = $m->afecta_mora;
        $this->activo     = $m->activo;
        $this->showForm   = true;
    }

    public function save(): void
    {
        $this->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $data = [
            'nombre'      => trim($this->nombre),
            'afecta_mora' => $this->afectaMora,
            'activo'      => $this->activo,
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

    public function toggleActivo(int $id): void
    {
        $m = MotivoCierre::findOrFail($id);
        $m->update(['activo' => !$m->activo]);
    }

    public function delete(int $id): void
    {
        MotivoCierre::findOrFail($id)->delete();
    }

    private function resetForm(): void
    {
        $this->editId     = null;
        $this->nombre     = '';
        $this->afectaMora = false;
        $this->activo     = true;
    }

    public function render()
    {
        return view('livewire.admin.definiciones.motivo-cierre-manager', [
            'registros' => MotivoCierre::orderByDesc('activo')->orderBy('nombre')->get(),
        ]);
    }
}
