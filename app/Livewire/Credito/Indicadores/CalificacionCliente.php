<?php

namespace App\Livewire\Credito\Indicadores;

use App\Models\Cliente;
use App\Models\PesoIndicador;
use App\Models\RangoCalificacion;
use App\Services\ClienteCalificacionService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class CalificacionCliente extends Component
{
    public string $mode               = 'list';
    public string $filtroCalificacion = 'todos';
    public string $ordenar            = 'puntaje_desc';
    public string $buscarCliente      = '';
    public ?int   $detalleId          = null;

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

    private function calcularClientes(PesoIndicador $pesos, RangoCalificacion $rangos): Collection
    {
        $service  = new ClienteCalificacionService();
        $clientes = Cliente::where('active', true)->get();

        return $clientes
            ->map(fn (Cliente $c) => $service->calcularParaCliente($c, $pesos, $rangos))
            ->filter()
            ->values();
    }

    private function calcularDetallePedidos(int $clienteId): Collection
    {
        return (new ClienteCalificacionService())->calcularDetallePedidos($clienteId);
    }

    public function render()
    {
        $hoy    = Carbon::today();
        $pesos  = PesoIndicador::vigente() ?? PesoIndicador::porDefecto();
        $rangos = RangoCalificacion::vigente() ?? RangoCalificacion::porDefecto();

        $todos = $this->calcularClientes($pesos, $rangos);

        $clienteDetalle = $this->detalleId ? $todos->firstWhere('id', $this->detalleId) : null;
        $detallePedidos = $this->detalleId ? $this->calcularDetallePedidos($this->detalleId) : collect();

        $clientes = $todos;
        if ($this->filtroCalificacion !== 'todos') {
            $clientes = $clientes->filter(fn($c) => $c['calificacion'] === $this->filtroCalificacion)->values();
        }
        if (strlen(trim($this->buscarCliente)) >= 2) {
            $q = mb_strtolower(trim($this->buscarCliente));
            $clientes = $clientes->filter(fn($c) => str_contains(mb_strtolower($c['nombre']), $q))->values();
        }
        $clientes = match($this->ordenar) {
            'puntaje_asc' => $clientes->sortBy('puntaje')->values(),
            'nombre'      => $clientes->sortBy('nombre')->values(),
            default       => $clientes->sortByDesc('puntaje')->values(),
        };

        $kpis = [
            'total' => $todos->count(),
            'a'     => $todos->where('calificacion', 'A')->count(),
            'b'     => $todos->where('calificacion', 'B')->count(),
            'c'     => $todos->where('calificacion', 'C')->count(),
            'db'    => $todos->whereIn('calificacion', ['D', 'BLOQUEADO'])->count(),
        ];

        return view('livewire.credito.indicadores.calificacion-cliente',
            compact('clientes', 'kpis', 'pesos', 'rangos', 'clienteDetalle', 'detallePedidos'));
    }
}
