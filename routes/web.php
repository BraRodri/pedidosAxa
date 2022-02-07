<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WmsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\FacturaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//login
Route::post('/validar-login', [LoginController::class, 'login'])->name('validar_login');

Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {

    //inicio
    Route::match(['get', 'post'], '/', [HomeController::class, 'index'])->name('home');
    Route::post('/consulta-pedido', [HomeController::class, 'consultarPedido'])->name('consultarPedido');

    //consultar informacion netsuite - wms
    Route::get('/consulta/{id}', [HomeController::class, 'consultar'])->name('consultar');

    //ver pedidos por estado
    Route::post('/pedidos/ver', [HomeController::class, 'verPedidos'])->name('verPedidos');

    //consulta WMS
    Route::match(['get', 'post'], '/control-wms', [WmsController::class, 'index'])->name('wms');

    //estadisticas
    Route::controller(EstadisticasController::class)
    ->group(function () {
        Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas');
        Route::post('/estadisticas/por-clases', [EstadisticasController::class, 'getPedidosClases'])->name('estadisticas.por.clases');
        Route::post('/estadisticas/por-clase-y-horas', [EstadisticasController::class, 'getPedidosClasePorHoras'])->name('estadisticas.por.clase.por.horas');
        Route::post('/estadisticas/get-pedidos-fechas', [EstadisticasController::class, 'getPedidosPorFechas'])->name('estadisticas.getPedidosPorFechas');
        Route::post('/estadisticas/get-pedidos-bodega', [EstadisticasController::class, 'getPedidosPorBodegas'])->name('estadisticas.getPedidosPorBodegas');
    });

    //control factura
    Route::match(['get', 'post'], '/control-factura', [FacturaController::class, 'index'])->name('factura');

});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
