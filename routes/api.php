<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthController;

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

Route::post('/v1/login', [AuthController::class, 'login']);

// 公開API：書籍一覧を取得
Route::get('/v1/books', [BookController::class, 'index']);

// 公開API：書籍詳細を取得
Route::get('/v1/books/{book}', [BookController::class, 'show']);

// 公開API：書籍を新規登録
Route::middleware('auth:sanctum')->post('/v1/books', [BookController::class, 'store']);

// 公開API：書籍を更新
Route::middleware('auth:sanctum')->put('/v1/books/{book}', [BookController::class, 'update']);

// 公開API：書籍を削除
Route::middleware('auth:sanctum')->delete('/v1/books/{book}', [BookController::class, 'destroy']);