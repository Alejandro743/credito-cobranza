<?php

namespace App\Livewire\Admin\Definiciones;

use App\Models\MotivoCierre;
use Livewire\Component;

class MotivoCierreManager extends Component
{
    public string $mode = 'list';

    public ?int  $editId     = null;
    public string $nombre    = '';
    public bool  $afectaMora = false;
    public bool  $activo     = true;

    public function create(): void
    {
        $this->resetForm();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $m = MotivoCierre::findOrFail($id);
        $this->editId     = $m->id;
        $this->nombre     = $m->nombre;
        $this->afectaMora = $m->afecta_mora;
        $this->activo     = $m->activo;
        $this->mode       = 'form';
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
        $this->backToList();
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

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
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
            'registros' => MotivoCierre::orderBy('nombre')->get(),
        ]);
    }
}
