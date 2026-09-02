<?php

namespace Tests\Feature;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_their_reading_plan(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567890',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reading-plans.complete', $readingPlan));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('reading-plans.index'));

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Completed,
            $readingPlan->status
        );

        $this->assertNotNull($readingPlan->completed_at);
    }

    public function test_user_cannot_complete_another_users_reading_plan(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567891',
            'user_id' => $otherUser->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('reading-plans.complete', $readingPlan));

        $response->assertForbidden();

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Planned,
            $readingPlan->status
        );

        $this->assertNull($readingPlan->completed_at);
    }

    public function test_user_can_view_their_reading_plans(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567892',
        'user_id' => $user->id,
    ]);

    ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('reading-plans.index'));

    $response->assertOk();

    }
    public function test_user_can_create_a_reading_plan(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567893',
        'user_id' => $user->id,
    ]);

    $targetDate = now()->addDay()->toDateString();

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
            'book_id' => $book->id,
            'target_date' => $targetDate,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('reading-plans.index'))
        ->assertSessionHas('success', '読書計画を登録しました。');

    $this->assertDatabaseHas('reading_plans', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => $targetDate,
        'status' => ReadingPlanStatus::Planned->value,
        'completed_at' => null,
    ]);

    }
    public function test_user_can_update_their_reading_plan(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567894',
        'user_id' => $user->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $newTargetDate = now()->addDays(3)->toDateString();

    $response = $this
        ->actingAs($user)
        ->put(route('reading-plans.update', $readingPlan), [
            'book_id' => $book->id,
            'target_date' => $newTargetDate,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('reading-plans.index'))
        ->assertSessionHas('success', '読書計画を更新しました。');

    $this->assertDatabaseHas('reading_plans', [
        'id' => $readingPlan->id,
        'target_date' => $newTargetDate,
    ]);

    }
    public function test_user_can_delete_their_reading_plan(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567895',
        'user_id' => $user->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete(route('reading-plans.destroy', $readingPlan));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('reading-plans.index'))
        ->assertSessionHas('success', '読書計画を削除しました。');

    $this->assertDatabaseMissing('reading_plans', [
    'id' => $readingPlan->id,
    ]);

    }
    public function test_user_cannot_update_another_users_reading_plan(): void
    {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567896',
        'user_id' => $otherUser->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $otherUser->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
    '    completed_at' => null,
    ]);

    $newTargetDate = now()->addDays(3)->toDateString();

    $response = $this
        ->actingAs($user)
        ->put(route('reading-plans.update', $readingPlan), [
            'book_id' => $book->id,
            'target_date' => $newTargetDate,
        ]);

    $response->assertForbidden();

    $readingPlan->refresh();

    $this->assertNotSame(
        $newTargetDate,
        $readingPlan->target_date->toDateString()
    );

    }
    public function test_user_cannot_delete_another_users_reading_plan(): void
    {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567897',
        'user_id' => $otherUser->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $otherUser->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete(route('reading-plans.destroy', $readingPlan));

    $response->assertForbidden();

    $this->assertDatabaseHas('reading_plans', [
    'id' => $readingPlan->id,
    ]);

    }
    public function test_user_cannot_create_reading_plan_with_past_target_date(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567898',
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
        'book_id' => $book->id,
        'target_date' => now()->subDay()->toDateString(),
        ]);

    $response
        ->assertSessionHasErrors('target_date');

    $this->assertDatabaseMissing('reading_plans', [
        'user_id' => $user->id,
        'book_id' => $book->id,
    ]);

    }
    public function test_user_cannot_create_reading_plan_with_invalid_book_id(): void
    {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
        'book_id' => 99999,
        'target_date' => now()->addDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors('book_id');

    $this->assertDatabaseMissing('reading_plans', [
    'user_id' => $user->id,
    ]);

    }
    public function test_guest_cannot_view_reading_plans(): void
    {
    $response = $this->get(route('reading-plans.index'));

    $response->assertRedirect(route('login'));

    }
    public function test_guest_cannot_create_reading_plan(): void
    {
    $response = $this->post(route('reading-plans.store'), [
    'book_id' => 1,
    'target_date' => now()->addDay()  ->toDateString(),
    ]);

    $response->assertRedirect(route('login'));

    $this->assertDatabaseCount('reading_plans', 0);

    }
    public function test_guest_cannot_complete_reading_plan(): void
    {
    $response = $this->post(route('reading-plans.complete', 1));

    $response->assertRedirect(route('login'));

    }
    public function test_guest_cannot_edit_reading_plan(): void
    {
    $response = $this->get(route('reading-plans.edit', 1));

    $response->assertRedirect(route('login'));

    }
    public function test_guest_cannot_update_reading_plan(): void
    {
    $response = $this->put(route('reading-plans.update', 1), [
    'book_id' => 1,
    'target_date' => now()->addDay()->toDateString(),
    ]);

    $response->assertRedirect(route('login'));

    }
    public function test_guest_cannot_delete_reading_plan(): void
    {
    $response = $this->delete(route('reading-plans.destroy', 1));

    $response->assertRedirect(route('login'));

    }
    public function test_user_cannot_create_reading_plan_without_book_id(): void
    {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
            'target_date' => now()->addDay()->toDateString(),
        ]);

    $response->assertSessionHasErrors('book_id');

    $this->assertDatabaseMissing('reading_plans', [
    'user_id' => $user->id,
    ]);

    }
    public function test_user_cannot_create_reading_plan_without_target_date(): void
    {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
            'book_id' => 1,
        ]);

    $response->assertSessionHasErrors('target_date');

    $this->assertDatabaseMissing('reading_plans', [
    'user_id' => $user->id,
    ]);

    }
    public function test_user_cannot_create_reading_plan_with_invalid_target_date(): void
    {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('reading-plans.store'), [
            'book_id' => 1,
            'target_date' => 'invalid-date',
        ]);

    $response->assertSessionHasErrors('target_date');

    $this->assertDatabaseMissing('reading_plans', [
        'user_id' => $user->id,
    ]);

    }
    public function test_user_cannot_update_reading_plan_with_past_target_date(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567899',
        'user_id' => $user->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('reading-plans.update', $readingPlan), [
        'book_id' => $book->id,
        'target_date' => now()->subDay()->toDateString(),
        ]);

    $response->assertSessionHasErrors('target_date');

    $readingPlan->refresh();

    $this->assertNotSame(
        now()->subDay()->toDateString(),
        $readingPlan->target_date->toDateString()
    );

    }
    public function test_user_cannot_update_reading_plan_with_invalid_book_id(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567890',
        'user_id' => $user->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('reading-plans.update', $readingPlan), [
            'book_id' => 99999,
            'target_date' => now()->addDays(3)->toDateString(),
        ]);

    $response->assertSessionHasErrors('book_id');

    $readingPlan->refresh();

    $this->assertSame($book->id, $readingPlan->book_id);

    }
    public function test_user_cannot_update_reading_plan_without_book_id(): void
    {
    $user = User::factory()->create();

    $book = Book::create([
        'title' => 'テスト書籍',
        'author' => 'テスト著者',
        'isbn' => '1234567891',
        'user_id' => $user->id,
    ]);

    $readingPlan = ReadingPlan::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'target_date' => now()->addDay(),
        'status' => ReadingPlanStatus::Planned,
        'completed_at' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('reading-plans.update', $readingPlan), [
            'target_date' => now()->addDays(3)->toDateString(),
        ]);

    $response->assertSessionHasErrors('book_id');

    $readingPlan->refresh();

    $this->assertSame($book->id, $readingPlan->book_id);

    }
    public function test_user_cannot_update_reading_plan_without_target_date(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567892',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('reading-plans.update', $readingPlan), [
                'book_id' => $book->id,
            ]);

        $response->assertSessionHasErrors('target_date');

        $readingPlan->refresh();

        $this->assertSame(
            now()->addDay()->toDateString(),
            $readingPlan->target_date->toDateString()
        );
    }
    public function test_user_cannot_update_reading_plan_with_invalid_target_date(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567893',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('reading-plans.update', $readingPlan), [
                'book_id' => $book->id,
                'target_date' => 'invalid-date',
            ]);

        $response->assertSessionHasErrors('target_date');

        $readingPlan->refresh();

        $this->assertSame(
            now()->addDay()->toDateString(),
            $readingPlan->target_date->toDateString()
        );
    }
}

