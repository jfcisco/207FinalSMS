<?php

use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect('/home');
})->middleware('auth');

Auth::routes();

//region Anonymous Routes
Route::get('/embed/{userId}/{widgetId}', [WidgetController::class, 'generateScript']);
//endregion

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/reports', [ReportsController::class, 'index']);

    //region View Routes
    // TODO: Add View Routes here

    // Routes for Widget Management front-end
    Route::get('/widgets/create', [WidgetController::class, 'create'])->name('create-widget');
    Route::get('/widgets/{widgetId?}', [WidgetController::class, 'index'])->name('widget-details');
    Route::post('/widgets/{widgetId}/update', [WidgetController::class, 'update']);
    //endregion

    //region Web API Endpoints
    Route::prefix('api')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('chat-widgets', ChatWidgetController::class);
        Route::resource('messages', MessageController::class);
        Route::resource('sessions', SessionController::class);
        Route::resource('rooms', RoomController::class);
        Route::put('join-conversation/{conversationId}', [ConversationController::class, 'joinConversation']);

        Route::prefix('reports')->group(function () {
            Route::prefix('chats')->group(function () {
                Route::post('daily', [ReportsController::class, 'dailyChats']);
                Route::get('todays-hourly', [ReportsController::class, 'todaysHourlyChats']);
                Route::get('todays-answered', [ReportsController::class, 'todaysAnsweredChatsCount']);
                Route::get('answered', [ReportsController::class, 'answeredChatsCount']);
                Route::get('todays-missed', [ReportsController::class, 'todaysMissedChatsCount']);
                Route::get('missed', [ReportsController::class, 'missedChatsCount']);
            });

            Route::prefix('sessions')->group(function () {
                Route::post('daily', [ReportsController::class, 'dailySessions']);
                Route::get('todays-hourly', [ReportsController::class, 'todaysHourlySessions']);
                Route::get('todays-live', [ReportsController::class, 'todaysLiveSessions']);
                Route::get('live-visitors', [ReportsController::class, 'liveVisitorSessions']);
            });

            Route::post('past-conversations', [ReportsController::class, 'pastConversations']);
        });
    });
    //endregion
});







