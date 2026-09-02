<?php

namespace App\Http\Controllers;

use App\Models\Book;

class RankingController extends Controller
{
    /**
     * 評価ランキングを表示する
     */
    public function index()
    {
        $rankedBooks = Book::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(10)
            ->get();

        return view('ranking.index', compact('rankedBooks'));
    }
}