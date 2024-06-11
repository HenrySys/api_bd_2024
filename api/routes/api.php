<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;


Route::prefix('barv1')->group(
    function(){
        Route::post('/user/login',[UserController::class,'login']);
        Route::post('/user/store',[UserController::class,'store']);
        Route::post('/user/update',[UserController::class,'update'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user/index',[UserController::class,'index'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user/show/{id}',[UserController::class,'show'])->middleware(ApiAuthMiddleware::class);
        Route::get('/user/getidentity',[UserController::class,'getIdentity'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::resource('/user',UserController::class,['except'=>['create','edit','store']])->middleware(ApiAuthMiddleware::class);

        

    }
);