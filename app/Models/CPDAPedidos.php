<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPDAPedidos extends Model
{
    use HasFactory;

    protected $table = 'cpda_pedidos';

    protected $fillable = [
        'tipo_venta',
        'horas_apro',
        'fecha',
        'fecaprov',
        'fecha_fulfill',
        'quien_cae_venta',
        'estado',
        'axlp_estado_aprobacion',
        'bodega',
        'metodo_pago',
        'numdoc',
        'total',
        'cliente',
        'control_wms',
        'bodega_destino',
        'rol',
        'origen',
        'estado_combinado',
        'estado_combinado_url',
        'clase'
    ];

    public function factura(){
        return $this->belongsTo(Factura::class, 'num_pedido');
    }

}
