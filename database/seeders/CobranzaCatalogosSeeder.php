<?php

namespace Database\Seeders;

use App\Models\CobranzaCatalogo;
use Illuminate\Database\Seeder;

class CobranzaCatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $catalogs = [
            'tipo_contacto' => [
                ['codigo' => 'LLAMADA',   'nombre' => 'Llamada',          'sort_order' => 1],
                ['codigo' => 'WHATSAPP',  'nombre' => 'WhatsApp',         'sort_order' => 2],
                ['codigo' => 'SMS',       'nombre' => 'SMS',              'sort_order' => 3],
                ['codigo' => 'CORREO',    'nombre' => 'Correo',           'sort_order' => 4],
                ['codigo' => 'VISITA',    'nombre' => 'Visita',           'sort_order' => 5],
                ['codigo' => 'INTERNA',   'nombre' => 'Gestión interna',  'sort_order' => 6],
            ],
            'accion' => [
                ['codigo' => 'CONTACTAR',    'nombre' => 'Contactar cliente',       'sort_order' => 1],
                ['codigo' => 'RECORDAR',     'nombre' => 'Recordar vencimiento',    'sort_order' => 2],
                ['codigo' => 'CONFIRMAR',    'nombre' => 'Confirmar pago',          'sort_order' => 3],
                ['codigo' => 'SOLICITAR',    'nombre' => 'Solicitar documentación', 'sort_order' => 4],
                ['codigo' => 'VISITA',       'nombre' => 'Realizar visita',         'sort_order' => 5],
                ['codigo' => 'VERIFICAR',    'nombre' => 'Verificar compromiso',    'sort_order' => 6],
                ['codigo' => 'DERIVAR',      'nombre' => 'Derivar a supervisor',    'sort_order' => 7],
            ],
            'tipo_respuesta' => [
                ['codigo' => 'CONTESTO',     'nombre' => 'Contestó',               'sort_order' => 1],
                ['codigo' => 'NO_CONTESTO',  'nombre' => 'No contestó',            'sort_order' => 2],
                ['codigo' => 'NRO_INCORR',   'nombre' => 'Número incorrecto',      'sort_order' => 3],
                ['codigo' => 'PROMESA',      'nombre' => 'Promesa de pago',        'sort_order' => 4],
                ['codigo' => 'NUEVA_LLAM',   'nombre' => 'Solicita nueva llamada', 'sort_order' => 5],
                ['codigo' => 'REPROG',       'nombre' => 'Solicita reprogramación','sort_order' => 6],
                ['codigo' => 'PAGO_REAL',    'nombre' => 'Pago realizado',         'sort_order' => 7],
                ['codigo' => 'SIN_ACUERDO',  'nombre' => 'Sin acuerdo',            'sort_order' => 8],
            ],
            'motivo_cierre' => [
                ['codigo' => 'DEUDA_PAG',    'nombre' => 'Deuda pagada',                  'sort_order' => 1],
                ['codigo' => 'ACUERDO',      'nombre' => 'Acuerdo cumplido',              'sort_order' => 2],
                ['codigo' => 'DERIVADO',     'nombre' => 'Derivado a otra instancia',     'sort_order' => 3],
                ['codigo' => 'DUPLICADO',    'nombre' => 'Caso duplicado',                'sort_order' => 4],
                ['codigo' => 'ADMIN',        'nombre' => 'Cierre administrativo',         'sort_order' => 5],
                ['codigo' => 'NO_CORRESPON', 'nombre' => 'Caso no correspondía',          'sort_order' => 6],
                ['codigo' => 'OTRO',         'nombre' => 'Otro',                          'sort_order' => 7],
            ],
            'estado_caso' => [
                ['codigo' => 'SIN_ASIGNAR', 'nombre' => 'Sin Asignar', 'sort_order' => 1],
                ['codigo' => 'ASIGNADO',    'nombre' => 'Asignado',    'sort_order' => 2],
                ['codigo' => 'EN_GESTION',  'nombre' => 'En Gestión',  'sort_order' => 3],
                ['codigo' => 'CERRADO',     'nombre' => 'Cerrado',     'sort_order' => 4],
                ['codigo' => 'CANCELADO',   'nombre' => 'Cancelado',   'sort_order' => 5],
            ],
            'estado_actividad' => [
                ['codigo' => 'ABIERTA',     'nombre' => 'Abierta',     'sort_order' => 1],
                ['codigo' => 'EN_PROCESO',  'nombre' => 'En Proceso',  'sort_order' => 2],
                ['codigo' => 'CERRADA',     'nombre' => 'Cerrada',     'sort_order' => 3],
                ['codigo' => 'CANCELADA',   'nombre' => 'Cancelada',   'sort_order' => 4],
            ],
        ];

        foreach ($catalogs as $tipo => $items) {
            foreach ($items as $item) {
                CobranzaCatalogo::updateOrCreate(
                    ['tipo' => $tipo, 'codigo' => $item['codigo']],
                    array_merge($item, ['tipo' => $tipo, 'activo' => true])
                );
            }
        }
    }
}
