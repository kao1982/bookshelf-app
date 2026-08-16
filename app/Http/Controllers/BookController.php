<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Genre;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('genres')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->paginate(10);
        return view('books.index', compact('books'));
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
            'user_id' => 1,
        ]);

        // 選択されたジャンルを紐付け
        if ($request->genres) {
            $book->genres()->attach($request->genres);
        }

        return redirect()->route('books.index');
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
    $genres = Genre::all();

    return view('books.edit', compact('book', 'genres'));
    }

    public function update(BookRequest $request, string $id)
    {
    $book = Book::findOrFail($id);

    $book->update([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'published_date' => $request->published_date,
        'description' => $request->description,
        'image_url' => $request->image_url,
    ]);

    $book->genres()->sync($request->genres ?? []);

    return redirect()->route('books.show', $book);
    }

    public function destroy(string $id)
    {
    $book = Book::findOrFail($id);

    $book->genres()->detach();
    $book->delete();

    return redirect()->route('books.index');
    }
}
