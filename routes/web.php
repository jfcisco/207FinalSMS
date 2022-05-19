<?php

use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
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
    // Route::get('/widget/create', [WidgetController::class, 'create']);
    Route::get('/widgets/{widgetId?}', [WidgetController::class, 'index'])->name('widget-details');
    //endregion

    //region Web API Endpoints
    // TODO: Add Web API Endpoints here
    //endregion
});

// TODO: Move inside middleware
Route::prefix('api')->group(function(){
    Route::resource('/chat-widgets', ChatWidgetController::class);
    Route::resource('/messages', MessageController::class);
    Route::resource('sessions', SessionController::class);
    Route::resource('/users', UserController::class);
});

