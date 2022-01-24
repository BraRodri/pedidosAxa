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

            $table->string('tipo');
            $table->decimal('horaspro', 12, 2);
            $table->date('fecha');
            $table->string('fecaprov');
            $table->text('quien_cae_venta');
            $table->string('estado');
            $table->string('axlp_estado_aprobacion');
            $table->text('bodega');
            $table->string('metodo_pago');
            $table->string('numdoc');
            $table->decimal('total', 1000, 2);
            $table->text('cliente');
            $table->string('controlw');
            $table->string('boddestino')->nullable();

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
