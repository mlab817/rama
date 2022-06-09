<?php

use App\Jobs\GenerateWeeklyReportJob;
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

Route::post('/notifications', \App\Http\Controllers\ReadNotificationController::class)
    ->name('notifications.read');

Route::get('/sync-trips', function () {
    \Illuminate\Support\Facades\Artisan::call('trips:sync');

    return 'done syncing';
});

Route::get('/schedule-run', function () {
    \Illuminate\Support\Facades\Artisan::call('schedule:run');

    return 'schedule ran';
});

Route::get('/queue-work', function () {
    \Illuminate\Support\Facades\Artisan::call('queue:work');

    return 'queue ran';
});

Route::get('/test', function () {
    $weeklyReport = \App\Models\WeeklyReport::find(1);

    $result = GenerateWeeklyReportJob::dispatch($weeklyReport, auth()->id());
});
