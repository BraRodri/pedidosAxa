<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPDAPedidos extends Model
{
    use HasFactory;

    protected $table = 'cpda_pedidos';

    protected $fillable = [
        'tipo', 
        'horaspro', 
        'fecha', 
        'fecaprov', 
        'quien_cae_venta',
        'estado',
        'axlp_estado_aprobacion',
        'bodega',
        'metodo_pago',
        'numdoc',
        'total',
        'cliente',
        'controlw',
        'boddestino'
    ];

}
