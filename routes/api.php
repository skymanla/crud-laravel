<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('board')->group(function () {
    Route::get('{boardId}', [\App\Http\Controllers\BoardController::class, 'getList'])->where(['boardId' => '[0-9]+']);
    Route::get('{boardIdx}/{contentIdx}', [\App\Http\Controllers\BoardController::class, 'readContents'])->where(['boardId' => '[0-9]+', '$contentIdx' => '[0-9]+']);
});
