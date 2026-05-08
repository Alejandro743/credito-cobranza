<?php

namespace App\Livewire\Vendedor;

use App\Models\Pago;
use App\Models\Vendedor;
use Livewire\Component;
use Livewire\WithPagination;

class PagoHistorial extends Component
{
    use WithPagination;

    public string $mode   = 'list';
    public string $search = '';
    public ?int   $pagoId = null;

    public ?int $vendedorId = null;

    public function mount(): void
    {
        $v = Vendedor::delUsuario();
        $this->vendedorId = $v?->id;
    }

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
                ->whereHas('pedido', fn($q) => $q->where('vendedor_id', $this->vendedorId))
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

        $pagoDetalle   = null;
        $cuotasDetalle = collect();

        if ($this->mode === 'detalle' && $this->pagoId) {
            $pagoDetalle = Pago::with(['pedido.cliente.usuario', 'planPago', 'cuotas', 'creadoPor', 'anuladoPor'])
                ->whereHas('pedido', fn($q) => $q->where('vendedor_id', $this->vendedorId))
                ->find($this->pagoId);

            if ($pagoDetalle) {
                $cuotasDetalle = $pagoDetalle->cuotas->where('numero', '>', 0)->sortBy('numero');

                if ($cuotasDetalle->isEmpty() && !empty($pagoDetalle->cuota_ids)) {
                    $cuotasDetalle = \App\Models\Cuota::whereIn('id', $pagoDetalle->cuota_ids)
                        ->where('numero', '>', 0)
                        ->orderBy('numero')
                        ->get();
                }
            }
        }

        return view('livewire.vendedor.pago-historial', compact('pagos', 'pagoDetalle', 'cuotasDetalle'));
    }
}
