<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('factura', function (Blueprint $table) {
            $table->id();

            $table->string('id_factura')->nullable();
            $table->date('fecha')->nullable();
            $table->string('bodega')->nullable();
            $table->string('clase')->nullable();
            $table->string('identificacion_cliente')->nullable();
            $table->string('id_interno_cliente')->nullable();
            $table->string('num_pedido')->nullable();
            $table->string('fecha_creacion_factura')->nullable();
            $table->string('fecha_respuesta_dian')->nullable();

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
        Schema::dropIfExists('factura');
    }
}
