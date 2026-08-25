<?php

namespace App\Livewire\Credito\Indicadores;

use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use App\Models\Vendedor;
use App\Services\VendedorCalificacionService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalificacionVendedor extends Component
{
    public string $mode                = 'list';
    public string $filtroCalificacion  = 'todos';
    public string $ordenar             = 'puntaje_desc';
    public string $buscarVendedor      = '';
    public ?int   $detalleId           = null;

    public function verDetalle(int $id): void
    {
        $this->detalleId = $id;
        $this->mode      = 'detalle';
    }

    public function backToList(): void
    {
        $this->detalleId = null;
        $this->mode      = 'list';
    }

    private function calcularVendedores(PesoIndicador $pesos, RangoCalificacion $rangos): Collection
    {
        $service    = new VendedorCalificacionService();
        $vendedores = Vendedor::where('activo', true)->get();

        return $vendedores
            ->map(fn (Vendedor $v) => $service->calcularParaVendedor($v, $pesos, $rangos))
            ->filter()
            ->values();
    }

    private function calcularDetallePedidos(int $vendedorId): Collection
    {
        return (new VendedorCalificacionService())->calcularDetallePedidos($vendedorId);
    }

    public function render()
    {
        $hoy    = Carbon::today();
        $pesos  = PesoIndicador::vigente() ?? PesoIndicador::porDefecto();
        $rangos = RangoCalificacion::vigente() ?? RangoCalificacion::porDefecto();

        $todos = $this->calcularVendedores($pesos, $rangos);

        $vendedorDetalle = $this->detalleId ? $todos->firstWhere('id', $this->detalleId) : null;
        $detallePedidos  = $this->detalleId ? $this->calcularDetallePedidos($this->detalleId) : collect();

        // Filtros y orden solo para la lista
        $vendedores = $todos;
        if ($this->filtroCalificacion !== 'todos') {
            $vendedores = $vendedores->filter(fn($v) => $v['calificacion'] === $this->filtroCalificacion)->values();
        }
        if (strlen(trim($this->buscarVendedor)) >= 2) {
            $q = mb_strtolower(trim($this->buscarVendedor));
            $vendedores = $vendedores->filter(fn($v) => str_contains(mb_strtolower($v['nombre']), $q))->values();
        }
        $vendedores = match($this->ordenar) {
            'puntaje_asc' => $vendedores->sortBy('puntaje')->values(),
            'nombre'      => $vendedores->sortBy('nombre')->values(),
            default       => $vendedores->sortByDesc('puntaje')->values(),
        };

        $kpis = [
            'total' => $todos->count(),
            'a'     => $todos->where('calificacion', 'A')->count(),
            'b'     => $todos->where('calificacion', 'B')->count(),
            'c'     => $todos->where('calificacion', 'C')->count(),
            'db'    => $todos->whereIn('calificacion', ['D', 'BLOQUEADO'])->count(),
        ];

        return view('livewire.credito.indicadores.calificacion-vendedor',
            compact('vendedores', 'kpis', 'pesos', 'rangos', 'vendedorDetalle', 'detallePedidos'));
    }
}
