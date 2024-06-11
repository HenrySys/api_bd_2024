<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoProductoController;
use App\Http\Controllers\ReservaController;


use App\Http\Middleware\ApiAuthMiddleware;


Route::prefix('barv1')->group(
    function(){
        Route::post('/user/login',[UserController::class,'login']);
        Route::post('/user/store',[UserController::class,'store']);
        Route::post('/user/update',[UserController::class,'update'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user/index',[UserController::class,'index']);
        Route::get('/user/show/{id}',[UserController::class,'show'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user/getidentity',[UserController::class,'getIdentity'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::post('/user/upload',[UserController::class,'uploadImage'])->middleware(ApiAuthMiddleware::class);//ruta para el metodo upload del controlador vehiculo
        Route::get('/user/getimage/{filename}',[UserController::class,'getImage'])->middleware(ApiAuthMiddleware::class);

        

        Route::resource('/user',UserController::class,['except'=>['create','edit','store']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/produto',ProductoController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/factura',FacturaController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/detallefactura',DetalleFacturaController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/mesa',MesaController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/inventario',InventarioController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/pedido',PedidoController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/pedidoproducto',PedidoProductoController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        Route::resource('/reserva',ReservaController::class,['except'=>['create','edit',]])->middleware(ApiAuthMiddleware::class);
        

        Route::post('/produto/upload',[ProductoController::class,'uploadImage'])->middleware(ApiAuthMiddleware::class);//ruta para el metodo upload del controlador vehiculo
        Route::get('/produto/getimage/{filename}',[ProductoController::class,'getImage'])->middleware(ApiAuthMiddleware::class);


    }
);