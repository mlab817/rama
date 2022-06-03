<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login-via-credentials', [\App\Http\Controllers\Api\AuthController::class, 'loginViaCredentials'])->name('api.loginViaCredentials');
Route::post('/login-via-qr', [\App\Http\Controllers\Api\AuthController::class, 'loginViaQr'])->name('api.loginViaQr');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/scan-qr', \App\Http\Controllers\Api\ScanQRController::class)->name('api.scan-qr');
});
