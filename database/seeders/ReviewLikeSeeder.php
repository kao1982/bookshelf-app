<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $likes = [
            1 => [2, 3],
            2 => [1, 4],
            3 => [2, 5],

            4 => [1, 3],
            5 => [2, 4],
            6 => [3],

            7 => [1, 5],
            8 => [2],
            9 => [4, 5],

            10 => [2, 3],
            11 => [4],
            12 => [1, 5],

            13 => [1, 2],
            14 => [3, 5],
            15 => [2],

            16 => [4, 5],
            17 => [1],
            18 => [2, 3],

            19 => [3, 5],
            20 => [1, 4],
            21 => [2],

            22 => [4],
            23 => [1, 3],
            24 => [2, 5],

            25 => [1],
            26 => [3, 4],
            27 => [2],

            28 => [4, 5],
            29 => [1, 2],
            30 => [3],

            31 => [2, 4],
            32 => [1, 5],
        ];

        foreach ($likes as $reviewId => $userIds) {
            $review = Review::find($reviewId);

            $review->likedByUsers()->syncWithoutDetaching($userIds);
        }
    }
}