<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favorites = [
            1 => [1, 2, 3, 5],
            2 => [2, 4, 6],
            3 => [3, 7, 8, 10],
            4 => [1, 6, 9, 11],
            5 => [4, 5, 10],
        ];

        foreach ($favorites as $userId => $bookIds) {
            $user = User::find($userId);

            $user->favoriteBooks()->syncWithoutDetaching($bookIds);
        }
    }
}