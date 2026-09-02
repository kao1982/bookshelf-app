<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::withCount('books')->get();

        // 取得したジャンル一覧を画面に渡す
        return view('genres.index', compact('genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ジャンル登録画面を表示する
        return view('genres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request)
    {
        // バリデーション済みのジャンル名を取得する
        $validated = $request->validated();

        // genresテーブルにジャンルを登録する
        Genre::create($validated);

       // ジャンル一覧画面へ戻る
        return redirect()->route('genres.index')->with('success', 'ジャンルを登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 指定されたジャンルを取得する
        $genre = Genre::findOrFail($id);

        // そのジャンルに紐づく書籍を取得する
        $books = $genre->books()->with('genres')->paginate(10);

        // ジャンル別の書籍一覧画面にデータを渡す
        return view('genres.show', compact('genre', 'books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // 編集するジャンルをIDから取得する
        $genre = Genre::findOrFail($id);

        // ジャンル編集画面にジャンル情報を渡す
        return view('genres.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, string $id)
    {
        // 編集するジャンルを取得する
        $genre = Genre::findOrFail($id);

        // バリデーション済みのデータを取得する
        $validated = $request->validated();

        // ジャンル名を更新する
        $genre->update($validated);

        // ジャンル一覧画面へ戻る
        return redirect()->route('genres.index')->with('success', 'ジャンルを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 削除するジャンルを取得する
        $genre = Genre::findOrFail($id);

        // ジャンルを削除する
        $genre->delete();

        // ジャンル一覧画面へ戻る
        return redirect()->route('genres.index')->with('success', 'ジャンルを削除しました');
    }
}
