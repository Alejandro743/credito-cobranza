<?php

namespace App\Livewire\Credito;

use App\Models\Pago;
use Livewire\Component;
use Livewire\WithPagination;

class PagoHistorial extends Component
{
    use WithPagination;

    public string $mode   = 'list';
    public string $search = '';
    public ?int   $pagoId = null;

    public function updatingSearch(): void { $this->resetPage(); }

    public function verPago(int $id): void
    {
        $this->pagoId = $id;
        $this->mode   = 'detalle';
    }

    public function volver(): void
    {
        $this->pagoId = null;
        $this->mode   = 'list';
    }

    public function render()
    {
        $pagos = collect();

        if ($this->mode === 'list') {
            $query = Pago::with(['pedido.cliente.usuario', 'creadoPor'])
                ->orderByDesc('created_at');

            if (strlen(trim($this->search)) >= 2) {
                $query->where(fn($q) => $q
                    ->where('numero', 'like', "%{$this->search}%")
                    ->orWhereHas('pedido', fn($p) => $p->where('numero', 'like', "%{$this->search}%"))
                    ->orWhereHas('pedido.cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                    ->orWhereHas('pedido.cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                );
            }

            $pagos = $query->paginate(15);
        }

        $pagoDetalle = null;
        if ($this->mode === 'detalle' && $this->pagoId) {
            $pagoDetalle = Pago::with(['pedido.cliente.usuario', 'planPago', 'cuotas', 'creadoPor'])
                ->find($this->pagoId);
        }

        return view('livewire.credito.pago-historial', compact('pagos', 'pagoDetalle'));
    }
}
