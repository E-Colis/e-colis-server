<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\AnnounceController;

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

if (App::environment('production')) {
    URL::forceScheme('https');
}

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


// public routes
Route::apiResource('announces', AnnounceController::class)->only([
    'index', 'show'
]);


// protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('announces', AnnounceController::class)->only([
        'store', 'update', 'destroy'
    ])->middleware('can:create-announce');
});

 
