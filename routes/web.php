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
    return redirect('home');
})->middleware('auth');

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');

});

// TODO: Move inside middleware
Route::resource('chat-widgets', ChatWidgetController::class);
Route::resource('messages', MessageController::class);
Route::resource('sessions', SessionController::class);
Route::resource('users', UserController::class);
Route::resource('visitor', VisitorController::class);
