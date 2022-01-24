<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::get('/validar', [HomeController::class, 'consultarDatosNetsuit'])->name('validar');
Route::get('/consulta/{id}', [HomeController::class, 'consultar'])->name('consultar');
