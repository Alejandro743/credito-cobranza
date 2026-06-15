<?php

namespace App\Livewire\Credito;

use App\Models\CobranzaCatalogo;
use Livewire\Component;
use Livewire\WithPagination;

class DefinicionManager extends Component
{
    use WithPagination;

    public string $tipo   = '';
    public string $titulo = '';
    public string $mode   = 'list';

    // Filtros
    public string $search      = '';
    public string $filtroActivo = '';

    // Formulario
    public int    $editId      = 0;
    public string $codigo      = '';
    public string $nombre      = '';
    public string $descripcion = '';
    public bool   $activo      = true;
    public int    $sortOrder   = 0;

    protected function rules(): array
    {
        $uniqueRule = $this->editId
            ? "unique:cobranza_catalogos,codigo,{$this->editId},id,tipo,{$this->tipo}"
            : "unique:cobranza_catalogos,codigo,NULL,id,tipo,{$this->tipo}";

        return [
            'codigo' => ['required', 'max:30', $uniqueRule],
            'nombre' => ['required', 'max:120'],
        ];
    }

    protected $messages = [
        'codigo.required' => 'El código es requerido.',
        'codigo.max'      => 'El código no puede superar 30 caracteres.',
        'codigo.unique'   => 'Ya existe un registro con ese código.',
        'nombre.required' => 'El nombre es requerido.',
        'nombre.max'      => 'El nombre no puede superar 120 caracteres.',
    ];

    public function updatingSearch(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->resetForm();
        $this->mode = 'form';
    }

    public function edit(int $id): void
    {
        $cat = CobranzaCatalogo::where('tipo', $this->tipo)->findOrFail($id);
        $this->editId      = $cat->id;
        $this->codigo      = $cat->codigo;
        $this->nombre      = $cat->nombre;
        $this->descripcion = $cat->descripcion ?? '';
        $this->activo      = $cat->activo;
        $this->sortOrder   = $cat->sort_order;
        $this->mode        = 'form';
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'tipo'        => $this->tipo,
            'codigo'      => strtoupper(trim($this->codigo)),
            'nombre'      => trim($this->nombre),
            'descripcion' => trim($this->descripcion) ?: null,
            'activo'      => $this->activo,
            'sort_order'  => $this->sortOrder,
            'updated_by'  => auth()->id(),
        ];

        if ($this->editId) {
            CobranzaCatalogo::where('tipo', $this->tipo)->findOrFail($this->editId)->update($data);
            session()->flash('success', 'Registro actualizado correctamente.');
        } else {
            $data['created_by'] = auth()->id();
            CobranzaCatalogo::create($data);
            session()->flash('success', 'Registro creado correctamente.');
        }

        $this->backToList();
    }

    public function toggleActivo(int $id): void
    {
        $cat = CobranzaCatalogo::where('tipo', $this->tipo)->findOrFail($id);
        $cat->update(['activo' => !$cat->activo, 'updated_by' => auth()->id()]);
    }

    public function backToList(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    private function resetForm(): void
    {
        $this->editId      = 0;
        $this->codigo      = '';
        $this->nombre      = '';
        $this->descripcion = '';
        $this->activo      = true;
        $this->sortOrder   = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $items = CobranzaCatalogo::where('tipo', $this->tipo)
            ->when($this->search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('nombre', 'like', "%{$this->search}%")
                       ->orWhere('codigo', 'like', "%{$this->search}%")
                )
            )
            ->when($this->filtroActivo !== '', fn($q) => $q->where('activo', $this->filtroActivo === '1'))
            ->orderBy('sort_order')
            ->orderBy('nombre')
            ->paginate(20);

        return view('livewire.credito.definicion-manager', compact('items'));
    }
}
