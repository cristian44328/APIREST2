<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/registrase', [AuthController::class, 'crear']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::get('proByAlm', [ProductoController::class, 'ProductosbyAlmacen']);
    Route::get('todosProd', [ProductoController::class, 'todosLosProd']);
    Route::resource('almacenes', AlmacenController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::get('auth/logout', [AuthController::class, 'logout']);
});

