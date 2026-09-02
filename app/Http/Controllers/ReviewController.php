<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Book;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request, Book $book)
    {
        // すでにレビュー済みか確認
        $exists = Review::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'この書籍にはすでにレビューを投稿しています。');
        }

        // レビュー登録
        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'レビューを投稿しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
    * レビュー編集画面を表示
    */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        // 編集対象のレビューを取得して編集画面へ渡す
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
    * レビューを更新
    */
    public function update(ReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        // レビューを更新
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // 書籍詳細画面へ戻る
        return redirect()
            ->route('books.show', $review->book_id)
            ->with('success', 'レビューを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
    * レビューを削除
    */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        // レビューを削除
        $review->delete();

        // 書籍詳細画面へ戻る
        return redirect()
            ->route('books.show', $review->book_id)
            ->with('success', 'レビューを削除しました。');
    }
    public function like(Review $review)
    {
        $user = auth()->user();

        // 自分のレビューにはいいね不可
        if ($user->id === $review->user_id) {
            return back();
    }

    if ($user->likedReviews->contains($review->id)) {
        $user->likedReviews()->detach($review->id);
    } else {
        $user->likedReviews()->attach($review->id);
    }

    return back();
}
}
