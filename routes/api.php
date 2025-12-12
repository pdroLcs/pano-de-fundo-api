<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\FaleConoscoController;
use App\Http\Controllers\Api\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    Route::get('/clientes', [ClienteController::class, 'index']);

    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::post('/login', [ClienteController::class, 'login']);

    Route::resource('categorias', CategoriaController::class)->except('create', 'edit');

    Route::resource('produtos', ProdutoController::class)->except('create', 'edit');
    Route::post('/comprar/{produto}', [CompraController::class, 'comprar']);

    Route::resource('fale-conosco', FaleConoscoController::class)->except('create', 'edit');

    // Route::get('/fale-conosco/mensagens', [FaleConoscoController::class, 'index']);
    // Route::get('/fale-conosco/{mensagem}', [FaleConoscoController::class, 'show']);
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