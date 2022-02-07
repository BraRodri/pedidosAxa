<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('cpda_pedidos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_venta');
            $table->decimal('horas_apro', 12, 2);
            $table->date('fecha');
            $table->string('fecaprov');
            $table->string('fecha_fulfill');
            $table->text('quien_cae_venta');
            $table->string('estado');
            $table->string('axlp_estado_aprobacion');
            $table->text('bodega');
            $table->string('metodo_pago');
            $table->string('numdoc');
            $table->string('total');
            $table->text('cliente');
            $table->string('control_wms');
            $table->string('bodega_destino')->nullable();
            $table->string('rol');
            $table->string('origen');
            $table->text('estado_combinado');
            $table->text('estado_combinado_url');
            $table->string('clase')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpda_pedidos');
    }
}
