<?php

namespace App\Livewire\Credito;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;

class PagoManual extends Component
{
    use WithFileUploads;

    public string $mode   = 'list';
    public string $search = '';
    public string $filtro = 'todos';

    public ?int  $pedidoId            = null;
    public array $cuotasSeleccionadas = [];

    public $archivo           = null;
    public array $resultadosOk    = [];
    public array $resultadosError = [];

    public function seleccionarPedido(int $id): void
    {
        $this->pedidoId            = $id;
        $this->cuotasSeleccionadas = [];
        $this->mode                = 'detalle';
    }

    public function volver(): void
    {
        $this->pedidoId            = null;
        $this->cuotasSeleccionadas = [];
        $this->archivo             = null;
        $this->resultadosOk        = [];
        $this->resultadosError     = [];
        $this->mode                = 'list';
    }

    public function irUpload(): void
    {
        $this->archivo         = null;
        $this->resultadosOk    = [];
        $this->resultadosError = [];
        $this->mode            = 'upload';
    }

    public function toggleCuota(int $cuotaId): void
    {
        $pedido = Pedido::with('planPago.cuotas')->find($this->pedidoId);
        $plan   = $pedido?->planPago;
        if (!$plan) return;

        $cuotas = $plan->cuotas->where('numero', '>', 0)->sortBy('numero')->values();
        $cuota  = $cuotas->firstWhere('id', $cuotaId);
        if (!$cuota || $cuota->estado === 'pagado') return;

        if (in_array($cuotaId, $this->cuotasSeleccionadas)) {
            // Al deseleccionar, también quitar todas las de número mayor (cascada)
            $idsToRemove = $cuotas->where('numero', '>=', $cuota->numero)->pluck('id')->toArray();
            $this->cuotasSeleccionadas = array_values(
                array_filter($this->cuotasSeleccionadas, fn($id) => !in_array($id, $idsToRemove))
            );
        } else {
            // Solo permitir si todas las cuotas anteriores no pagadas ya están seleccionadas
            $lowerUnpaid = $cuotas->where('numero', '<', $cuota->numero)->where('estado', '!=', 'pagado');
            if ($lowerUnpaid->every(fn($x) => in_array($x->id, $this->cuotasSeleccionadas))) {
                $this->cuotasSeleccionadas[] = $cuotaId;
            }
        }
    }

    public function registrarPago(): void
    {
        if (empty($this->cuotasSeleccionadas)) return;

        $pedido = Pedido::with('planPago.cuotas')->findOrFail($this->pedidoId);
        $plan   = $pedido->planPago;
        if (!$plan) return;

        DB::transaction(function () use ($pedido, $plan) {
            $cuotas = $plan->cuotas()
                ->whereIn('id', $this->cuotasSeleccionadas)
                ->where('estado', '!=', 'pagado')
                ->get();

            if ($cuotas->isEmpty()) return;

            $pago = Pago::create([
                'numero'          => Pago::generarNumero(),
                'pedido_id'       => $pedido->id,
                'plan_pago_id'    => $plan->id,
                'monto_total'     => $cuotas->sum('monto'),
                'cantidad_cuotas' => $cuotas->count(),
                'creado_por'      => auth()->id(),
            ]);

            $plan->cuotas()
                ->whereIn('id', $this->cuotasSeleccionadas)
                ->where('estado', '!=', 'pagado')
                ->update([
                    'estado'     => 'pagado',
                    'fecha_pago' => now()->toDateString(),
                    'pago_id'    => $pago->id,
                ]);
        });

        session()->flash('success', count($this->cuotasSeleccionadas) . ' cuota(s) registrada(s) como pagadas.');
        $this->cuotasSeleccionadas = [];
    }

    public function procesarArchivo(): void
    {
        $this->validate(['archivo' => 'required|file|max:10240']);

        $path = $this->archivo->getRealPath();
        $ext  = strtolower($this->archivo->getClientOriginalExtension());

        try {
            if ($ext === 'csv' || $ext === 'txt') {
                $reader = new CsvReader();
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
                $spreadsheet = $reader->load($path);
            } else {
                $spreadsheet = IOFactory::load($path);
            }
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            $this->addError('archivo', 'No se pudo leer el archivo: ' . $e->getMessage());
            return;
        }

        if (count($rows) < 2) {
            $this->addError('archivo', 'El archivo está vacío o solo tiene encabezados.');
            return;
        }

        $rawHeaders = array_map(fn($h) => $this->normalizeHeader((string)($h ?? '')), $rows[0]);
        $colMap     = $this->mapColumns($rawHeaders);

        if ($colMap === null) {
            $this->addError('archivo', 'Columnas requeridas no encontradas. Verificá que el archivo tenga: transaccion, fecha_de_pago, numero_de_pedido, numero_de_cuota.');
            return;
        }

        $validas = [];
        $errores = [];

        foreach (array_slice($rows, 1) as $i => $row) {
            $fila        = $i + 2;
            $transaccion = trim((string)($row[$colMap['transaccion']] ?? ''));
            $fechaStr    = trim((string)($row[$colMap['fecha']]       ?? ''));
            $pedidoNum   = trim((string)($row[$colMap['pedido']]      ?? ''));
            $cuotaNum    = trim((string)($row[$colMap['cuota']]       ?? ''));

            if (!$transaccion && !$pedidoNum && !$cuotaNum) continue;

            try {
                $fecha = Carbon::parse($fechaStr)->toDateString();
            } catch (\Throwable) {
                $fecha = now()->toDateString();
            }

            $pedido = Pedido::where('numero', $pedidoNum)->with('planPago.cuotas')->first();
            if (!$pedido) {
                $errores[] = compact('fila', 'transaccion', 'pedidoNum', 'cuotaNum') + ['motivo' => 'Pedido no encontrado'];
                continue;
            }

            $plan = $pedido->planPago;
            if (!$plan) {
                $errores[] = compact('fila', 'transaccion', 'pedidoNum', 'cuotaNum') + ['motivo' => 'Sin plan de pagos'];
                continue;
            }

            $cuota = $plan->cuotas->where('numero', (int)$cuotaNum)->first();
            if (!$cuota) {
                $errores[] = compact('fila', 'transaccion', 'pedidoNum', 'cuotaNum') + ['motivo' => 'Cuota no encontrada'];
                continue;
            }

            if ($cuota->estado === 'pagado') {
                $errores[] = compact('fila', 'transaccion', 'pedidoNum', 'cuotaNum') + ['motivo' => 'Cuota ya pagada'];
                continue;
            }

            $validas[] = [
                'transaccion' => $transaccion,
                'fecha'       => $fecha,
                'cuota_id'    => $cuota->id,
                'monto'       => (float)$cuota->monto,
                'pedido_id'   => $pedido->id,
                'plan_id'     => $plan->id,
                'pedidoNum'   => $pedidoNum,
                'cuotaNum'    => $cuotaNum,
            ];
        }

        $aplicados = [];

        if (!empty($validas)) {
            $grupos = [];
            foreach ($validas as $v) {
                $grupos[$v['transaccion'] . '||' . $v['pedido_id']][] = $v;
            }

            DB::transaction(function () use ($grupos, &$aplicados) {
                foreach ($grupos as $items) {
                    $cuotaIds = array_column($items, 'cuota_id');

                    $pago = Pago::create([
                        'numero'          => Pago::generarNumero(),
                        'pedido_id'       => $items[0]['pedido_id'],
                        'plan_pago_id'    => $items[0]['plan_id'],
                        'monto_total'     => array_sum(array_column($items, 'monto')),
                        'cantidad_cuotas' => count($cuotaIds),
                        'creado_por'      => auth()->id(),
                    ]);

                    Cuota::whereIn('id', $cuotaIds)
                        ->where('estado', '!=', 'pagado')
                        ->update([
                            'estado'     => 'pagado',
                            'fecha_pago' => $items[0]['fecha'],
                            'pago_id'    => $pago->id,
                        ]);

                    foreach ($items as $item) {
                        $aplicados[] = [
                            'transaccion' => $item['transaccion'],
                            'pedido'      => $item['pedidoNum'],
                            'cuota'       => $item['cuotaNum'],
                            'fecha'       => $item['fecha'],
                            'monto'       => $item['monto'],
                        ];
                    }
                }
            });
        }

        $this->resultadosOk    = $aplicados;
        $this->resultadosError = $errores;
        $this->archivo         = null;
        $this->mode            = 'upload-resultado';
    }

    private function normalizeHeader(string $h): string
    {
        $h = mb_strtolower(trim($h));
        $h = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $h) ?: $h;
        return preg_replace('/[\s\-]+/', '_', $h);
    }

    private function mapColumns(array $headers): ?array
    {
        $find = function (array $keywords) use ($headers): ?int {
            foreach ($headers as $i => $h) {
                foreach ($keywords as $k) {
                    if (str_contains($h, $k)) return $i;
                }
            }
            return null;
        };

        $cols = [
            'transaccion' => $find(['transaccion', 'transaction', 'tx', 'referencia']),
            'fecha'       => $find(['fecha_de_pago', 'fecha_pago', 'fecha', 'date', 'payment']),
            'pedido'      => $find(['numero_de_pedido', 'nro_pedido', 'num_pedido', 'pedido', 'order']),
            'cuota'       => $find(['numero_de_cuota', 'nro_cuota', 'num_cuota', 'cuota', 'quota']),
        ];

        return in_array(null, $cols, true) ? null : $cols;
    }

    public function render()
    {
        $query = Pedido::with(['cliente.usuario', 'planPago.cuotas'])
            ->where('estado', 'aprobado')
            ->whereHas('planPago', fn($q) => $q->where('estado', 'activo'))
            ->whereHas('planPago.cuotas', fn($q) => $q->where('estado', '!=', 'pagado')->where('numero', '>', 0));

        if (strlen(trim($this->search)) >= 2) {
            $query->where(fn($q) => $q
                ->whereHas('cliente', fn($c) => $c->where('ci', 'like', "%{$this->search}%"))
                ->orWhereHas('cliente.usuario', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                ->orWhere('numero', 'like', "%{$this->search}%")
            );
        }

        $pedidos = $query->orderByDesc('created_at')->get();

        if ($this->filtro !== 'todos') {
            $pedidos = $pedidos->filter(
                fn($p) => $p->planPago?->estadoFinanciero === $this->filtro
            )->values();
        }

        $pedidoDetalle = null;
        if ($this->mode === 'detalle' && $this->pedidoId) {
            $pedidoDetalle = Pedido::with(['cliente.usuario', 'planPago.cuotas'])
                ->find($this->pedidoId);
        }

        return view('livewire.credito.pago-manual', compact('pedidos', 'pedidoDetalle'));
    }
}
