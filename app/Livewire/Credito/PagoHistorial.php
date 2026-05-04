<?php

namespace App\Livewire\Credito;

use App\Models\Cuota;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PagoHistorial extends Component
{
    use WithPagination;

    public string $mode   = 'list';
    public string $search = '';
    public ?int   $pagoId = null;

    public bool   $confirmandoAnulacion  = false;
    public ?int   $confirmandoAnularId   = null;

    public function updatingSearch(): void { $this->resetPage(); }

    public function verPago(int $id): void
    {
        $this->pagoId               = $id;
        $this->confirmandoAnulacion = false;
        $this->confirmandoAnularId  = null;
        $this->mode                 = 'detalle';
    }

    public function iniciarAnulacion(int $id): void
    {
        $this->confirmandoAnularId = $id;
    }

    public function cancelarAnulacion(): void
    {
        $this->confirmandoAnularId  = null;
        $this->confirmandoAnulacion = false;
    }

    public function volver(): void
    {
        $this->pagoId               = null;
        $this->confirmandoAnulacion = false;
        $this->confirmandoAnularId  = null;
        $this->mode                 = 'list';
    }

    public function anularPago(?int $id = null): void
    {
        $id   = $id ?? $this->pagoId;
        $pago = Pago::with(['planPago', 'cuotas'])->find($id);

        if (!$pago || $pago->estado === 'anulado') return;
        if ($pago->planPago?->estado !== 'activo') return;

        DB::transaction(function () use ($pago) {
            foreach ($pago->cuotas as $cuota) {
                $cuota->update([
                    'estado'     => 'pendiente',
                    'fecha_pago' => null,
                    'pago_id'    => null,
                ]);
            }

            $pago->update([
                'estado'      => 'anulado',
                'anulado_por' => auth()->id(),
                'anulado_at'  => now(),
            ]);
        });

        session()->flash('success', 'Pago anulado. Las cuotas volvieron a estado pendiente.');

        if ($this->mode === 'detalle') {
            $this->volver();
        } else {
            $this->confirmandoAnularId = null;
        }
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
            $pagoDetalle = Pago::with(['pedido.cliente.usuario', 'planPago', 'cuotas', 'creadoPor', 'anuladoPor'])
                ->find($this->pagoId);
        }

        return view('livewire.credito.pago-historial', compact('pagos', 'pagoDetalle'));
    }
}
