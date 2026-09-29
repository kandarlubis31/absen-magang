<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ApiController::class, 'user']);
    Route::post('/check-in', [ApiController::class, 'checkIn']);
    Route::post('/check-out', [ApiController::class, 'checkOut']);
    Route::post('/logout', [ApiController::class, 'logout']);

    Route::get('/absensi/status-hari-ini', [ApiController::class, 'statusHariIni']);
});
