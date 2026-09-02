<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Genre;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('genres')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // キーワード検索（タイトル・著者）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('author', 'like', "%{$keyword}%");
            });
        }

        // ジャンル絞り込み
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }

        // 並び順
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('published_date', 'asc');
                break;

            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;

            case 'title':
                $query->orderBy('title');
                break;

            case 'newest':
            default:
                $query->orderByDesc('published_date');
                break;
        }

        $books = $query->paginate(10)->withQueryString();

        $genres = Genre::all();

        return view('books.index', compact('books', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::all();

        $bookGenreIds = [];

        return view('books.create', compact('genres', 'bookGenreIds'));
    }
    public function isbn($isbn)
    {
        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
        'q' => 'isbn:' . $isbn,
        'key' => config('services.google_books.api_key'),
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Google Books APIから書籍情報を取得できませんでした。'
            ], 500);
        }

        $data = $response->json();

        if (empty($data['items'])) {
            return response()->json([
                'message' => '該当する書籍が見つかりませんでした。'
            ], 404);
        }

        $volumeInfo = $data['items'][0]['volumeInfo'];

        return response()->json([
            'title' => $volumeInfo['title'] ?? '',
            'author' => $volumeInfo['authors'][0] ?? '',
            'isbn' => $isbn,
            'description' => $volumeInfo['description'] ?? '',
            'image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? '',
            'published_date' => $volumeInfo['publishedDate'] ?? '',
        ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_date' => $request->published_date,
            'description' => $request->description,
            'image_url' => $request->image_url,
            'user_id' => auth()->id(),
        ]);

        // 選択されたジャンルを紐付け
        if ($request->genres) {
            $book->genres()->attach($request->genres);
        }

        return redirect()->route('books.index')->with('success', '書籍を登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load([
            'genres',
            'reviews.user',
            'reviews.likedByUsers',
        ]);

    return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $book = Book::findOrFail($id);
    $this->authorize('update', $book);
    $genres = Genre::all();

    return view('books.edit', compact('book', 'genres'));
    }

    public function update(BookRequest $request, string $id)
    {
    $book = Book::findOrFail($id);
    $this->authorize('update', $book);

    $book->update([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'published_date' => $request->published_date,
        'description' => $request->description,
        'image_url' => $request->image_url,
    ]);

    $book->genres()->sync($request->genres ?? []);

    return redirect()->route('books.show', $book)->with('success', '書籍を更新しました');
    }

    public function destroy(string $id)
    {
    $book = Book::findOrFail($id);
    $this->authorize('delete', $book);

    $book->genres()->detach();
    $book->delete();

    return redirect()->route('books.index')->with('success', '書籍を削除しました');
    }
}
