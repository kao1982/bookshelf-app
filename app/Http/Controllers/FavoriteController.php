<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録・解除
     */
    public function toggle(Book $book)
    {
        $user = auth()->user();

        // すでにお気に入り登録済みなら削除
        if ($user->favoriteBooks->contains($book->id)) {
            $user->favoriteBooks()->detach($book->id);
            session()->flash('success', 'お気に入りから削除しました');
        } else {
            // 未登録なら追加
            $user->favoriteBooks()->attach($book->id);
            session()->flash('success', 'お気に入りに登録しました');
        }

        return back();
    }
    /**
     * お気に入り一覧表示
     */
    public function index()
    {
        $books = auth()->user()
            ->favoriteBooks()
            ->paginate(10);

        return view('favorites.index', compact('books'));
    }
}