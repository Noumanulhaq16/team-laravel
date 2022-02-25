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
//Universal API
Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');

//User API
Route::group(['prefix' => 'user'], function () {
    Route::post('register', [App\Http\Controllers\AuthController::class, 'registerUser'])->name('register.user');
});
//Agent API
Route::group(['prefix' => 'agent'], function () {
    Route::post('register', [App\Http\Controllers\AuthController::class, 'registerAgent'])->name('register.agent');
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
