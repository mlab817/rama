<?php

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

Route::get('/clear', function() {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return 'Cleared';
});

/*For AWS health check problem*/
Route::get('/healthcheck', function() {
    config()->set('session.driver', 'array');
    return response('OK', 200)
        ->header('Content-Type', 'text/plain');
});

Route::get('/androidlogin',[App\Admin\Controllers\AndroidLogin::class, 'login']);

Route::get('/test', function () {
    $puvDetails = (new \App\Services\GenerateTripsService())->execute();

    return response()->json($puvDetails);
});

Route::redirect('/', '/auth');
