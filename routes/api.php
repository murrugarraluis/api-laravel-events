<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('categories', [CategoryController::class, 'store'])
            ->middleware('permission:create categories');
        Route::put('categories/{category}', [CategoryController::class, 'update'])
            ->middleware('permission:update categories');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:destroy categories');

        Route::post('events', [EventController::class, 'store'])
            ->middleware('permission:create events');
        Route::put('events/{event}', [EventController::class, 'update'])
            ->middleware('permission:update events');
        Route::delete('events/{event}', [EventController::class, 'destroy'])
            ->middleware('permission:destroy events');
    });
});
