<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ApiWaliBerandaController;

Route::post('login', [LoginController::class, 'loginApi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('beranda', [ApiWaliBerandaController::class, 'index']);
});
