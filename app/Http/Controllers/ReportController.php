<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReportController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ログインユーザーのレビュー
        $reviews = Review::where('user_id', $userId);

        // 基本統計
        $totalReviews = (clone $reviews)->count();

        // レビューしたユニーク書籍数
        $booksRead = (clone $reviews)
            ->distinct('book_id')
            ->count('book_id');

        // 平均評価
        $averageRating = (clone $reviews)->avg('rating') ?? 0;
        // 評価分布（★1～★5）
        $ratingDistribution = (clone $reviews)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // ★1～★5を必ず用意する
        $ratingDistribution = collect(range(1, 5)) ->mapWithKeys(function ($rating) use ($ratingDistribution) {
        return [$rating => $ratingDistribution->get($rating, 0)];
    });

        // 高評価書籍TOP5（平均評価4以上）
        $topRatedBooks = Review::where('user_id', $userId)
            ->where('rating', '>=', 4)
            ->with('book')
            ->get()
            ->groupBy('book_id')
            ->map(function ($reviews) {
                $book = $reviews->first()->book;

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'rating' => round($reviews->avg('rating')),
                ];
            })
            ->sortByDesc('rating')
            ->take(5)
            ->values();
                // ジャンル別平均評価＋件数TOP5
                $genreRatings = Review::where('user_id', $userId)
                    ->with('book.genres')
                    ->get()
                    ->flatMap(function ($review) {
                        return $review->book->genres->map(function ($genre) use ($review) {
                            return [
                                'genre_id' => $genre->id,
                                'genre_name' => $genre->name,
                                'rating' => $review->rating,
                            ];
                        });
                    })
                    ->groupBy('genre_id')
                    ->map(function ($reviews) {
                        return [
                            'id' => $reviews->first()['genre_id'],
                            'name' => $reviews->first()['genre_name'],
                            'count' => $reviews->count(),
                            'average_rating' => round($reviews->avg('rating'), 1),
                        ];
                    })
                    ->sortByDesc('average_rating')
                    ->take(5)
                    ->values();
        $stats = [
            'summary' => [
                'total_reviews' => $totalReviews,
                'books_read' => $booksRead,
                'average_rating' => $averageRating,
            ],
            'rating_distribution' => $ratingDistribution,
            'top_rated_books' => $topRatedBooks,
            'genre_ratings' => $genreRatings,
        ];

        return view('reports.index', compact('stats'));
    }
}