<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\FaleConoscoController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {

    Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function() {
        Route::resource('clientes', UserController::class)->only('index', 'show');
        Route::resource('categorias', CategoriaController::class)->except('create', 'edit');
        Route::resource('produtos', ProdutoController::class)->except('create', 'edit');
        Route::resource('fale-conosco', FaleConoscoController::class)->except('create', 'edit');
    });

    Route::middleware(['auth:sanctum', 'ability:cliente'])->group(function() {
        Route::post('/comprar/{produto}', [CompraController::class, 'comprar']);
        Route::resource('fale-conosco', FaleConoscoController::class)->only('store');
        Route::resource('clientes', UserController::class)->only('update', 'destroy');
    });

    Route::resource('produtos', ProdutoController::class)->only('index', 'show');

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

    // Route::post('/login', [ClienteController::class, 'login']);



    // Route::post('/fale-conosco', [FaleConoscoController::class, 'store']);
    // Route::put('/fale-conosco/{mensagem}', [FaleConoscoController::class, 'update']);
    // Route::delete('/fale-conosco/{mensagem}', [FaleConoscoController::class, 'destroy']);

    // Route::get('/produtos', [ProdutoController::class, 'index']);
    // Route::get('/produtos/{produto}', [ProdutoController::class, 'show']);
    // Route::post('/produtos', [ProdutoController::class, 'store']);
    // Route::put('/produtos/{produto}', [ProdutoController::class, 'update']);
    // Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy']);


    // Route::get('/categorias', [CategoriaController::class, 'index']);
    // Route::get('/categorias/{categoria}', [CategoriaController::class, 'show']);
    // Route::post('/categorias', [CategoriaController::class, 'store']);
    // Route::put('/categorias/{categoria}', [CategoriaController::class, 'update']);
    // Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy']);
});