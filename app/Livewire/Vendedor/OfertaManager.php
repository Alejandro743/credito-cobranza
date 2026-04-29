<?php

namespace App\Livewire\Vendedor;

use App\Livewire\Concerns\HasModuleColor;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\Municipio;
use App\Models\Provincia;
use App\Models\Cuota;
use App\Models\ListaAcceso;
use App\Models\ListaMaestra;
use App\Models\ListaMaestraItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\PlanPago;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class OfertaManager extends Component
{
    use HasModuleColor, WithFileUploads;

    // ── Flujo ─────────────────────────────────────────────────────────────────
    public string $step = 'cliente'; // cliente | oferta | resumen | entrega

    // ── Acceso ────────────────────────────────────────────────────────────────
    public bool $sinListasActivas = false;

    // ── Cliente ───────────────────────────────────────────────────────────────
    public string $searchCliente     = '';
    public array  $resultadosCliente = [];
    public ?int   $clienteId         = null;
    public ?int   $clienteUserId     = null;
    public string $clienteNombre     = '';
    public string $clienteCI         = '';
    public bool   $sinListasComunes  = false;

    // ── Oferta ────────────────────────────────────────────────────────────────
    public array  $oferta         = [];
    public array  $listasInfo     = [];
    public string $searchProducto = '';
    public string $filterLista    = '';

    // ── Carrito ───────────────────────────────────────────────────────────────
    public array $carrito = [];

    // ── Plan de pagos (en resumen) ────────────────────────────────────────────
    public ?array $simulacion = null;

    // ── Documentos ───────────────────────────────────────────────────────────
    public $docAnversoCi  = null;
    public $docReversoCi  = null;
    public $docAnversoDoc = null;
    public $docReversoDoc = null;
    public $docAvisoLuz   = null;

    // ── Entrega ───────────────────────────────────────────────────────────────
    public string $tipoEntrega = 'domicilio'; // 'domicilio' | 'nuevo'
    public string $entregaReferencia = '';
    public string $entregaClienteCiudad    = '';
    public string $entregaClienteProvincia = '';
    public string $entregaClienteMunicipio = '';
    public string $entregaClienteDireccion = '';
    public string $entregaNuevoCiudad    = '';
    public string $entregaNuevaProvincia = '';
    public string $entregaNuevoMunicipio = '';
    public string $entregaNuevaDireccion = '';
    public string $pedidoNotas = '';

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->initModuleColor();

        $this->sinListasActivas = !ListaMaestra::where('active', true)
            ->where(function ($q) {
                $q->whereDoesntHave('accesosVendedores')
                  ->orWhereHas('accesosVendedores', fn($a) => $a->where('user_id', auth()->id()));
            })->exists();
    }

    // ── Búsqueda de cliente ───────────────────────────────────────────────────

    public function updatedSearchCliente(): void
    {
        if ($this->clienteId && trim($this->searchCliente) !== '') {
            $this->reset([
                'clienteId','clienteUserId','clienteNombre','clienteCI',
                'oferta','listasInfo','carrito',
                'sinListasComunes','resultadosCliente',
                'simulacion','pedidoNotas',
                'tipoEntrega','entregaReferencia',
                'entregaClienteCiudad','entregaClienteProvincia',
                'entregaClienteMunicipio','entregaClienteDireccion',
                'entregaNuevoCiudad','entregaNuevaProvincia',
                'entregaNuevoMunicipio','entregaNuevaDireccion',
            ]);
            $this->step = 'cliente';
        }

        $q = trim($this->searchCliente);
        if (strlen($q) < 2) { $this->resultadosCliente = []; return; }

        $this->resultadosCliente = Cliente::where('active', true)
            ->where(fn($query) =>
                $query->where('ci', 'like', "%{$q}%")
                      ->orWhere('apellido', 'like', "%{$q}%")
                      ->orWhereHas('usuario', fn($u) =>
                          $u->where('name', 'like', "%{$q}%")
                      )
            )
            ->with('usuario')
            ->limit(8)
            ->get()
            ->map(fn($c) => [
                'id'      => $c->id,
                'user_id' => $c->usuario_id,
                'nombre'  => trim(($c->usuario->name ?? '') . ' ' . ($c->apellido ?? '')),
                'ci'      => $c->ci ?? '',
            ])->toArray();
    }

    public function seleccionarCliente(int $id, int $userId, string $nombre, string $ci = ''): void
    {
        $this->clienteId     = $id;
        $this->clienteUserId = $userId;
        $this->clienteNombre = $nombre;
        $this->clienteCI     = $ci;
        $this->searchCliente     = '';
        $this->resultadosCliente = [];
        $this->sinListasComunes  = false;

        $cliente = Cliente::find($id);
        if ($cliente) {
            $this->entregaClienteCiudad    = $cliente->ciudad    ?? '';
            $this->entregaClienteProvincia = $cliente->provincia ?? '';
            $this->entregaClienteMunicipio = $cliente->municipio ?? '';
            $this->entregaClienteDireccion = $cliente->direccion ?? '';
        }

        $this->cargarOferta();
    }

    public function cambiarCliente(): void
    {
        $this->reset([
            'clienteId','clienteUserId','clienteNombre','clienteCI',
            'oferta','listasInfo','carrito',
            'sinListasComunes','searchCliente','resultadosCliente',
            'simulacion','pedidoNotas',
            'tipoEntrega','entregaReferencia',
            'entregaClienteCiudad','entregaClienteProvincia',
            'entregaClienteMunicipio','entregaClienteDireccion',
            'entregaNuevoCiudad','entregaNuevaProvincia',
            'entregaNuevoMunicipio','entregaNuevaDireccion',
        ]);
        $this->step = 'cliente';
    }

    // ── Oferta ────────────────────────────────────────────────────────────────

    private function cargarOferta(): void
    {
        $todasIds = ListaMaestra::where('active', true)->pluck('id');

        if ($todasIds->isEmpty()) {
            $this->oferta = []; $this->listasInfo = [];
            $this->sinListasComunes = true;
            $this->step = 'oferta';
            return;
        }

        $listasConVendedores = ListaAcceso::where('tipo', 'vendedor')
            ->whereIn('lista_maestra_id', $todasIds)
            ->pluck('lista_maestra_id')->unique();

        $listasVendedorExplicito = ListaAcceso::where('tipo', 'vendedor')
            ->whereIn('lista_maestra_id', $todasIds)
            ->where('user_id', auth()->id())
            ->pluck('lista_maestra_id');

        $accesoVendedor = $listasVendedorExplicito
            ->merge($todasIds->diff($listasConVendedores))
            ->unique();

        $listasConClientes = ListaAcceso::where('tipo', 'cliente')
            ->whereIn('lista_maestra_id', $todasIds)
            ->pluck('lista_maestra_id')->unique();

        $listasClienteExplicito = ListaAcceso::where('tipo', 'cliente')
            ->whereIn('lista_maestra_id', $todasIds)
            ->where('user_id', $this->clienteUserId)
            ->pluck('lista_maestra_id');

        $accesoCliente = $listasClienteExplicito
            ->merge($todasIds->diff($listasConClientes))
            ->unique();

        $comunes = $accesoVendedor->intersect($accesoCliente);

        if ($comunes->isEmpty()) {
            $this->oferta = []; $this->listasInfo = [];
            $this->sinListasComunes = true;
            $this->step = 'oferta';
            return;
        }

        $listas = ListaMaestra::whereIn('id', $comunes)
            ->where('active', true)
            ->with(['items' => fn($q) => $q
                ->where('active', true)
                ->where('stock_actual', '>', 0)
                ->with(['product' => fn($p) => $p->where('active', true)])
            ])->get();

        $this->listasInfo = $listas->mapWithKeys(fn($l) => [
            (string)$l->id => ['nombre' => $l->name, 'code' => $l->code]
        ])->toArray();

        $oferta = [];
        foreach ($listas as $lista) {
            foreach ($lista->items as $item) {
                if (!$item->product) continue;
                $pid         = (string)$item->product_id;
                $precioFinal = (float)$item->precio_final;
                if (!isset($oferta[$pid]) || $precioFinal < $oferta[$pid]['precio']) {
                    $oferta[$pid] = [
                        'item_id'           => $item->id,
                        'product_id'        => $item->product_id,
                        'code'              => $item->product->code ?? '',
                        'nombre'            => $item->product->name,
                        'image'             => $item->product->image ? Storage::url($item->product->image) : null,
                        'precio_base'       => (float)$item->precio_base,
                        'tipo_incremento'   => $item->tipo_incremento,
                        'factor_incremento' => (float)$item->factor_incremento,
                        'monto_incremento'  => (float)$item->monto_incremento,
                        'precio'            => $precioFinal,
                        'puntos'            => (int)$item->puntos,
                        'stock'             => (float)$item->stock_actual,
                        'lista_id'          => (string)$lista->id,
                        'lista_nombre'      => $lista->name,
                        'lista_code'        => $lista->code,
                    ];
                }
            }
        }

        $this->oferta = $oferta;
        $this->step   = 'oferta';
    }

    // ── Carrito ───────────────────────────────────────────────────────────────

    public function agregar(int $productId, int $qty = 1): void
    {
        $pid = (string)$productId;
        if (!isset($this->oferta[$pid])) return;
        $existing = $this->carrito[$pid]['cantidad'] ?? 0;
        $newQty   = min($existing + max(1, $qty), (int)$this->oferta[$pid]['stock']);
        $this->carrito[$pid] = array_merge($this->oferta[$pid], ['cantidad' => $newQty]);
        $this->dispatch('producto-agregado', nombre: $this->oferta[$pid]['nombre']);
    }

    public function incrementar(int $productId): void
    {
        $pid = (string)$productId;
        if (isset($this->carrito[$pid]) &&
            $this->carrito[$pid]['cantidad'] < ($this->oferta[$pid]['stock'] ?? PHP_INT_MAX)) {
            $this->carrito[$pid]['cantidad']++;
        }
    }

    public function decrementar(int $productId): void
    {
        $pid = (string)$productId;
        if (!isset($this->carrito[$pid])) return;
        if ($this->carrito[$pid]['cantidad'] <= 1) unset($this->carrito[$pid]);
        else $this->carrito[$pid]['cantidad']--;
    }

    public function quitar(int $productId): void { unset($this->carrito[(string)$productId]); }

    public function vaciar(): void
    {
        $this->carrito = [];
        $this->dispatch('carrito-vaciado');
    }

    // ── Resumen ───────────────────────────────────────────────────────────────

    public function irResumen(): void
    {
        if (empty($this->carrito)) return;
        $lista = $this->getPrimaryLista();
        $this->simulacion = $lista ? $this->buildSimulacionFromLista($lista) : null;
        $this->step = 'resumen';
    }

    public function volverOferta(): void { $this->step = 'oferta'; }

    public function updatedEntregaNuevoCiudad(): void   { $this->entregaNuevaProvincia = ''; $this->entregaNuevoMunicipio = ''; }
    public function updatedEntregaNuevaProvincia(): void { $this->entregaNuevoMunicipio = ''; }

    // ── Entrega ───────────────────────────────────────────────────────────────

    public function irEntrega(): void
    {
        if (empty($this->carrito)) return;
        if (!$this->simulacion) {
            $lista = $this->getPrimaryLista();
            $this->simulacion = $lista ? $this->buildSimulacionFromLista($lista) : null;
        }
        $this->step = 'entrega';
    }

    public function volverResumen(): void { $this->step = 'resumen'; }

    // ── Confirmar pedido ──────────────────────────────────────────────────────

    public function confirmarPedido(): void
    {
        if (empty($this->carrito) || !$this->clienteId) return;

        $this->validate([
            'docAnversoCi'  => 'required',
            'docReversoCi'  => 'required',
            'docAnversoDoc' => 'required',
            'docReversoDoc' => 'required',
            'docAvisoLuz'   => 'required',
        ], [
            'docAnversoCi.required'  => 'Anverso del CI',
            'docReversoCi.required'  => 'Reverso del CI',
            'docAnversoDoc.required' => 'Anverso del documento',
            'docReversoDoc.required' => 'Reverso del documento',
            'docAvisoLuz.required'   => 'Aviso de Luz',
        ]);

        if ($this->tipoEntrega === 'domicilio') {
            $this->validate([
                'entregaClienteDireccion' => 'required|string|min:3',
            ], [
                'entregaClienteDireccion.required' => 'El domicilio del cliente no tiene dirección registrada.',
                'entregaClienteDireccion.min'      => 'La dirección debe tener al menos 3 caracteres.',
            ]);
        } else {
            $this->validate([
                'entregaNuevaDireccion' => 'required|string|min:3',
                'entregaNuevoCiudad'    => 'required|string|min:2',
            ], [
                'entregaNuevaDireccion.required' => 'Ingresá la dirección.',
                'entregaNuevaDireccion.min'      => 'La dirección debe tener al menos 3 caracteres.',
                'entregaNuevoCiudad.required'    => 'Ingresá la ciudad.',
            ]);
        }

        $lista = $this->getPrimaryLista();
        $sim   = $this->simulacion ?? ($lista ? $this->buildSimulacionFromLista($lista) : null);

        if (!$sim) {
            $t   = $this->calcTotal();
            $sim = [
                'cuota_inicial'   => 0.0,
                'saldo_financiar' => $t,
                'incremento'      => 0.0,
                'monto_cuota'     => $t,
                'cantidad_cuotas' => 1,
                'total_pagar'     => $t,
                'cuotas_preview'  => [[
                    'numero'            => 1,
                    'tipo'              => 'regular',
                    'monto'             => $t,
                    'fecha'             => Carbon::today()->format('d/m/Y'),
                    'fecha_vencimiento' => Carbon::today(),
                ]],
            ];
        }

        $pedidoId = null;

        try {
        DB::transaction(function () use ($lista, $sim, &$pedidoId) {
            $vendedor = Vendedor::where('user_id', auth()->id())->first();
            if (!$vendedor) {
                throw new \RuntimeException('No existe un perfil de vendedor para este usuario.');
            }
            $total    = $this->calcTotal();

            if ($this->tipoEntrega === 'domicilio') {
                $entregaCiudad    = $this->entregaClienteCiudad;
                $entregaProvincia = $this->entregaClienteProvincia;
                $entregaMunicipio = $this->entregaClienteMunicipio;
                $entregaDireccion = $this->entregaClienteDireccion;
            } else {
                $entregaCiudad    = $this->entregaNuevoCiudad;
                $entregaProvincia = $this->entregaNuevaProvincia;
                $entregaMunicipio = $this->entregaNuevoMunicipio;
                $entregaDireccion = $this->entregaNuevaDireccion;
            }

            $pedido = Pedido::create([
                'numero'              => Pedido::generarNumero(),
                'cliente_id'          => $this->clienteId,
                'vendedor_id'         => $vendedor->id,
                'financial_matrix_id' => null,
                'matriz_snapshot'     => $lista ? [
                    'source'              => 'lista_maestra',
                    'id'                  => $lista->id,
                    'code'                => $lista->code,
                    'name'                => $lista->name,
                    'cantidad_cuotas'     => $lista->cantidad_cuotas,
                    'dias_entre_cuotas'   => $lista->dias_entre_cuotas,
                    'tipo_cuota_inicial'  => $lista->tipo_cuota_inicial,
                    'valor_cuota_inicial' => (float) $lista->valor_cuota_inicial,
                    'tipo_incremento'     => $lista->tipo_incremento,
                    'valor_incremento'    => (float) $lista->valor_incremento,
                ] : null,
                'estado'              => 'en_espera',
                'notas'               => $this->pedidoNotas ?: null,
                'entrega_ciudad'      => $entregaCiudad    ?: null,
                'entrega_provincia'   => $entregaProvincia ?: null,
                'entrega_municipio'   => $entregaMunicipio ?: null,
                'entrega_direccion'   => $entregaDireccion ?: null,
                'entrega_referencia'  => $this->entregaReferencia ?: null,
                'total'             => $total,
                'total_pagar'       => $sim['total_pagar'],
                'cuota_inicial'     => $sim['cuota_inicial'],
            ]);

            $pedidoId = $pedido->id;

            foreach ($this->carrito as $item) {
                PedidoItem::create([
                    'pedido_id'             => $pedido->id,
                    'lista_maestra_item_id' => $item['item_id'],
                    'product_id'            => $item['product_id'],
                    'cantidad'              => $item['cantidad'],
                    'precio_unitario'       => $item['precio'],
                    'puntos'                => $item['puntos'],
                    'subtotal'              => round($item['precio'] * $item['cantidad'], 2),
                ]);
            }

            foreach ($this->carrito as $item) {
                $lmi = ListaMaestraItem::find($item['item_id']);
                if ($lmi) {
                    $lmi->stock_consumido = (float)$lmi->stock_consumido + $item['cantidad'];
                    $lmi->stock_actual    = max(0, (float)$lmi->stock_actual - $item['cantidad']);
                    $lmi->save();
                }
            }

            $planPago = PlanPago::create([
                'pedido_id'       => $pedido->id,
                'matriz_nombre'   => $lista?->name ?? 'Sin plan',
                'cantidad_cuotas' => $sim['cantidad_cuotas'],
                'cuota_inicial'   => $sim['cuota_inicial'],
                'saldo_financiar' => $sim['saldo_financiar'],
                'incremento'      => $sim['incremento'],
                'monto_cuota'     => $sim['monto_cuota'],
                'total_pagar'     => $sim['total_pagar'],
            ]);

            foreach ($sim['cuotas_preview'] as $cuota) {
                Cuota::create([
                    'plan_pago_id'      => $planPago->id,
                    'numero'            => $cuota['numero'],
                    'monto'             => $cuota['monto'],
                    'estado'            => 'pendiente',
                    'fecha_vencimiento' => $cuota['fecha_vencimiento'] ?? null,
                ]);
            }
        });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('confirmarPedido error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->addError('pedido', $e->getMessage());
            return;
        }

        if ($pedidoId) {
            $docs = [];
            if ($this->docAnversoCi)  $docs['doc_anverso_ci']  = $this->docAnversoCi->store('pedidos/docs',  'public');
            if ($this->docReversoCi)  $docs['doc_reverso_ci']  = $this->docReversoCi->store('pedidos/docs',  'public');
            if ($this->docAnversoDoc) $docs['doc_anverso_doc'] = $this->docAnversoDoc->store('pedidos/docs', 'public');
            if ($this->docReversoDoc) $docs['doc_reverso_doc'] = $this->docReversoDoc->store('pedidos/docs', 'public');
            if ($this->docAvisoLuz)   $docs['doc_aviso_luz']   = $this->docAvisoLuz->store('pedidos/docs',   'public');
            if ($docs) DB::table('pedidos')->where('id', $pedidoId)->update($docs);
        }

        session()->flash('success', 'Pedido creado exitosamente');
        $this->redirect(route('vendedor.pedidos'), navigate: false);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function getPrimaryLista(): ?ListaMaestra
    {
        if (empty($this->carrito)) return null;
        $counts = array_count_values(array_column($this->carrito, 'lista_id'));
        arsort($counts);
        $listaId = (int) array_key_first($counts);
        return $listaId ? ListaMaestra::find($listaId) : null;
    }

    private function buildSimulacionFromLista(ListaMaestra $lista): ?array
    {
        $total = $this->calcTotal();
        if ($total <= 0 || !$lista->cantidad_cuotas) return null;

        $cuotaInicial = 0.0;
        $saldo = $total;
        $tipoCuotaIni = $lista->tipo_cuota_inicial;

        if ($tipoCuotaIni && $tipoCuotaIni !== 'ninguna' && (float)$lista->valor_cuota_inicial > 0) {
            $cuotaInicial = $tipoCuotaIni === 'porcentaje'
                ? round($total * (float)$lista->valor_cuota_inicial / 100, 2)
                : (float)$lista->valor_cuota_inicial;
            $saldo = max(0.0, $total - $cuotaInicial);
        }

        $cuotas = max(1, (int)$lista->cantidad_cuotas);
        $dias   = max(1, (int)($lista->dias_entre_cuotas ?? 30));

        $montoCuota  = $cuotas > 1 ? round($saldo / $cuotas, 2) : $saldo;
        $ultimaCuota = round($saldo - ($montoCuota * ($cuotas - 1)), 2);

        $hoy = Carbon::today();
        $preview = [];

        if ($cuotaInicial > 0) {
            $preview[] = [
                'numero'            => 0,
                'tipo'              => 'inicial',
                'monto'             => $cuotaInicial,
                'fecha'             => $hoy->format('d/m/Y'),
                'fecha_vencimiento' => $hoy->copy(),
            ];
        }

        for ($i = 1; $i <= $cuotas; $i++) {
            $fecha = $hoy->copy()->addDays($dias * $i);
            $preview[] = [
                'numero'            => $i,
                'tipo'              => 'regular',
                'monto'             => ($i === $cuotas) ? $ultimaCuota : $montoCuota,
                'fecha'             => $fecha->format('d/m/Y'),
                'fecha_vencimiento' => $fecha,
            ];
        }

        return [
            'cuota_inicial'     => $cuotaInicial,
            'saldo_financiar'   => $saldo,
            'incremento'        => 0.0,
            'monto_cuota'       => $montoCuota,
            'ultima_cuota'      => $ultimaCuota,
            'cantidad_cuotas'   => $cuotas,
            'total_pagar'       => round($cuotaInicial + $saldo, 2),
            'es_contado'        => $cuotas === 1 && $cuotaInicial === 0.0,
            'cuotas_preview'    => $preview,
            'dias_entre_cuotas' => $dias,
            'lista_name'        => $lista->name,
            'lista_code'        => $lista->code,
        ];
    }

    public function calcTotal(): float
    {
        return round(array_sum(array_map(
            fn($i) => $i['precio'] * $i['cantidad'], $this->carrito
        )), 2);
    }

    public function calcPuntos(): int
    {
        return (int)array_sum(array_map(
            fn($i) => $i['puntos'] * $i['cantidad'], $this->carrito
        ));
    }

    public function calcCantidad(): int
    {
        return (int)array_sum(array_column($this->carrito, 'cantidad'));
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $filtrada = collect($this->oferta);

        if ($this->searchProducto) {
            $q = strtolower(trim($this->searchProducto));
            $filtrada = $filtrada->filter(fn($p) => str_contains(strtolower($p['nombre']), $q));
        }
        if ($this->filterLista) {
            $filtrada = $filtrada->filter(fn($p) => $p['lista_id'] == $this->filterLista);
        }

        $ciudadesAll       = Ciudad::orderBy('nombre')->get();
        $entregaCiudadObj  = Ciudad::where('nombre', $this->entregaNuevoCiudad)->first();
        $entregaProvincias = $entregaCiudadObj ? Provincia::where('ciudad_id', $entregaCiudadObj->id)->orderBy('nombre')->get() : collect();
        $entregaProvObj    = Provincia::where('nombre', $this->entregaNuevaProvincia)->where('ciudad_id', $entregaCiudadObj?->id)->first();
        $entregaMunicipios = $entregaProvObj ? Municipio::where('provincia_id', $entregaProvObj->id)->orderBy('nombre')->get() : collect();

        return view('livewire.vendedor.oferta-manager', [
            'ofertaPorLista'    => $filtrada->groupBy('lista_id'),
            'total'             => $this->calcTotal(),
            'puntos'            => $this->calcPuntos(),
            'cantidad'          => $this->calcCantidad(),
            'ciudadesAll'       => $ciudadesAll,
            'entregaProvincias' => $entregaProvincias,
            'entregaMunicipios' => $entregaMunicipios,
        ]);
    }
}
