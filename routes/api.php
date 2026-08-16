<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;

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

// 公開API：書籍一覧を取得
Route::get('/books', [BookController::class, 'index']);

// 公開API：書籍詳細を取得
Route::get('/books/{book}', [BookController::class, 'show']);

// 公開API：書籍を新規登録
Route::post('/books', [BookController::class, 'store']);