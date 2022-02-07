<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table = 'factura';

    protected $fillable = [
        'id_factura',
        'fecha',
        'bodega',
        'clase',
        'identificacion_cliente',
        'id_interno_cliente',
        'num_pedido',
        'fecha_creacion_factura',
        'fecha_respuesta_dian'
    ];
}
