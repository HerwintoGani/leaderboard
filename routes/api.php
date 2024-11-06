<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UserController;

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

Route::post('showUser',[UserController::class,'showUser'])->name('showUser');
Route::post('showLeaderboard',[LeaderboardController::class,'showLeaderboard'])->name('showLeaderboard');
Route::post('scoreInsert',[ScoreController::class,'scoreInsert'])->name('scoreInsert');
Route::post('scoreInsertBulk',[ScoreController::class,'scoreInsertBulk'])->name('scoreInsertBulk');
Route::post('callApi',[ScoreController::class,'callApi'])->name('callApi');