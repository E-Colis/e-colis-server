<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthApiController;


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

// authentication
Route::controller(AuthApiController::class)->group(function () {
    Route::Post('/register', 'register');
    Route::Post('/login', 'login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::Post('/logout', 'logout');
        Route::Post('/logout-all', 'logoutAll');
    });
});
