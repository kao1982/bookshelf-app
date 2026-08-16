<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookIndexRequest;
use App\Http\Requests\Api\BookStoreRequest;
use App\Models\Book;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    public function index(BookIndexRequest $request)
    {
        // 書籍を取得する
        $books = Book::with('genres')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')

            // キーワードが指定されている場合
            // タイトルまたは著者にキーワードが含まれる書籍を検索
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                        ->orWhere('author', 'like', '%' . $request->keyword . '%');
                });
            })

            // ジャンルIDが指定されている場合
            // そのジャンルに紐づいている書籍だけを検索
            ->when($request->genre_id, function ($query) use ($request) {
                $query->whereHas('genres', function ($query) use ($request) {
                    $query->where('genres.id', $request->genre_id);
                });
            })

            // 新しい書籍から順番に並べる
            ->latest()

            // 1ページあたりの件数を指定
            ->paginate(
            $request->input('per_page', 10)
            );

        // JSON形式で返す
        return BookResource::collection($books);
    }

        // 書籍詳細を取得
    public function show(Book $book)
    {
        // ジャンルとレビューを一緒に取得する
        $book->load([
            'genres',
            'reviews.user',
        ]);
         // レビューの平均評価と件数を取得する
        $book->loadAvg('reviews', 'rating');
        $book->loadCount('reviews');

        // 書籍情報をJSON形式で返す
        return new BookResource($book);
    }
        // 書籍を登録
    public function store(BookStoreRequest $request)
    {
        // バリデーション済みのデータを取得
        $validated = $request->validated();

        // 書籍を登録
        $book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'],
            'published_date' => $validated['published_date'],
            'description' => $validated['description'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'user_id' => $validated['user_id'],
        ]);

        // genre_idを中間テーブルに紐付け
        $book->genres()->attach($validated['genre_id']);

        // 登録した書籍をJSON形式で返す
        return response()->json(
            new BookResource($book->load('genres')),
            201
        );
    }
}