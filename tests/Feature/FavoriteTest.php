<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_book_to_favorites(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999991',
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('favorites.toggle', $book));

        $response->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_user_can_remove_book_from_favorites(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999990',
            'user_id' => $user->id,
        ]);

        $user->favoriteBooks()->attach($book->id);

        $response = $this
            ->actingAs($user)
            ->post(route('favorites.toggle', $book));

        $response->assertRedirect();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_favorites_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'お気に入り書籍',
            'author' => 'テスト著者',
            'isbn' => '9999999989',
            'user_id' => $user->id,
        ]);

        $user->favoriteBooks()->attach($book->id);

        $response = $this
            ->actingAs($user)
            ->get(route('favorites.index'));

        $response->assertOk();
        $response->assertSee('お気に入り書籍');
    }
}
