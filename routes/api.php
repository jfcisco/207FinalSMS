<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\VisitorController;
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

Route::resource('visitors', VisitorController::class);
Route::resource('icons', IconController::class);
Route::post('upload-file', [FileUploadController::class, 'uploadFile'])->name('upload-file');
