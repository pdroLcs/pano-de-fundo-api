<?php

use App\Http\Controllers\Api\ClienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    Route::get('/clientes', [ClienteController::class, 'index']);

    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::post('/login', [ClienteController::class, 'login']);
});