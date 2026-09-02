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
        public function test_user_can_create_review(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999997',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => 5,
                'comment' => 'とても良い本でした。',
            ]);

        $response->assertRedirect(route('books.show', $book));

        $response->assertSessionHas('success', 'レビューを投稿しました。');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'とても良い本でした。',
        ]);
    }
        public function test_user_cannot_create_duplicate_review(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999996',
            'user_id' => $user->id,
        ]);

        Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 4,
            'comment' => '最初のレビューです。',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reviews.store', $book), [
                'rating' => 5,
                'comment' => '2回目のレビューです。',
            ]);

        $response->assertSessionHas('error', 'この書籍にはすでにレビューを投稿しています。');

        $this->assertDatabaseCount('reviews', 1);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 4,
            'comment' => '最初のレビューです。',
        ]);
    }
        public function test_user_can_update_own_review(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999995',
            'user_id' => $user->id,
        ]);

        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 3,
            'comment' => '変更前のレビューです。',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch(route('reviews.update', $review), [
                'rating' => 5,
                'comment' => '変更後のレビューです。',
            ]);

        $response->assertRedirect(route('books.show', $book));

        $response->assertSessionHas('success', 'レビューを更新しました。');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
            'comment' => '変更後のレビューです。',
        ]);
    }
        public function test_user_can_delete_own_review(): void
        {
            $user = User::factory()->create();

            $book = Book::create([
                'title' => 'テスト書籍',
                'author' => 'テスト著者',
                'isbn' => '9999999994',
                'user_id' => $user->id,
            ]);

            $review = Review::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => 4,
                'comment' => '削除するレビューです。',
            ]);

            $response = $this
                ->actingAs($user)
                ->delete(route('reviews.destroy', $review));

            $response->assertRedirect(route('books.show', $book));

            $response->assertSessionHas('success', 'レビューを削除しました。');

            $this->assertDatabaseMissing('reviews', [
                'id' => $review->id,
            ]);
        }
            public function test_user_can_like_another_users_review(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999993',
            'user_id' => $otherUser->id,
        ]);

        $review = Review::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'いいねされるレビューです。',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reviews.like', $review));

        $response->assertRedirect();

        $this->assertDatabaseHas('review_likes', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
    }
        public function test_user_can_unlike_another_users_review(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999992',
            'user_id' => $otherUser->id,
        ]);

        $review = Review::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'いいね解除するレビューです。',
        ]);

        $this->actingAs($user)
            ->post(route('reviews.like', $review));

        $this->assertDatabaseHas('review_likes', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
        $user->refresh();

        $response = $this
            ->actingAs($user)
            ->post(route('reviews.like', $review));

        $response->assertRedirect();

        $this->assertDatabaseMissing('review_likes', [
            'user_id' => $user->id,
            'review_id' => $review->id,
        ]);
    }

}
