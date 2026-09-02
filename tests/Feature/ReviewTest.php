<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_edit_another_users_review(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999999',
            'user_id' => $otherUser->id,
        ]);

        $review = Review::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'テストレビュー',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('reviews.edit', $review));

        $response->assertForbidden();
    }
    
    public function test_user_cannot_delete_another_users_review(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999998',
            'user_id' => $otherUser->id,
        ]);

        $review = Review::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'テストレビュー',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('reviews.destroy', $review));

        $response->assertForbidden();

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
        ]);
    }

}
