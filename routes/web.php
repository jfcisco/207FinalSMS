<?php

use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WidgetController;

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

Route::get('/', function () {
    return redirect('/home');
})->middleware('auth');

Auth::routes();

//region Anonymous Routes
Route::get('/embed/{userId}/{widgetId}', [WidgetController::class, 'generateScript']);
//endregion

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);

    //region View Routes
    // TODO: Add View Routes here

    // Routes for Widget Management front-end
    Route::get('/widgets/create', [WidgetController::class, 'create'])->name('create-widget');
    Route::get('/widgets/{widgetId?}', [WidgetController::class, 'index'])->name('widget-details');
    //endregion

    //region Web API Endpoints
    Route::prefix('api')->group(function(){
        Route::resource('/users', UserController::class);
        Route::resource('/visitors', VisitorController::class);
        Route::resource('/chat-widgets', ChatWidgetController::class);
        Route::resource('/messages', MessageController::class);
        Route::resource('sessions', SessionController::class);
        Route::resource('/rooms', RoomController::class);

        Route::prefix('reporting')->group(function(){
            Route::prefix('chats')->group(function(){
                Route::get('/daily', [ReportingController::class, 'dailyChatsVolume']);
                Route::get('/answered', [ReportingController::class, 'answeredChatsCount']);
                Route::get('/missed', [ReportingController::class, 'missedChatsCount']);
            });

            Route::prefix('sessions')->group(function(){
                Route::get('/live-today', [ReportingController::class, 'todaysLiveSessionsCount']);
                Route::get('/hourly-today', [ReportingController::class, 'todaysHourlyLiveSessionsVolume']);
                Route::get('/daily', [ReportingController::class, 'dailySessionsVolume']);
            });
        });
    });
    //endregion
});




