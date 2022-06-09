<?php

use App\Admin\Controllers\OperatorController;
use App\Admin\Controllers\TripController;
use App\Admin\Controllers\WeeklyReportBatchController;
use App\Admin\Controllers\WeeklyReportController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    //
    $router->get('/', 'HomeController@index')->name('home');
});

Route::group([
    'prefix' => 'auth',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->resource('operators', OperatorController::class);
//    $router->get('/report-batches/generate', 'WeeklyReportBatchController@generate');
    $router->resource('report-batches', WeeklyReportBatchController::class);

    $router->resource('weekly-reports', WeeklyReportController::class);

    $router->resource('trips', TripController::class);
});
