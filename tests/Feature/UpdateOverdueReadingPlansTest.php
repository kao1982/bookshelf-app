<?php

namespace Tests\Feature;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOverdueReadingPlansTest extends TestCase
{
    use RefreshDatabase;

    public function test_planned_reading_plan_with_past_target_date_becomes_overdue(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9876543210',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->subDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $this->artisan('app:update-overdue-reading-plans')
            ->assertExitCode(0);

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Overdue,
            $readingPlan->status
        );
    }
    public function test_today_planned_reading_plan_does_not_become_overdue(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9876543211',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => today(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $this->artisan('app:update-overdue-reading-plans')
            ->assertExitCode(0);

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Planned,
            $readingPlan->status
        );
    }
    public function test_completed_reading_plan_with_past_target_date_does_not_become_overdue(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9876543212',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->subDay(),
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        $this->artisan('app:update-overdue-reading-plans')
            ->assertExitCode(0);

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Completed,
            $readingPlan->status
        );
    }
    public function test_overdue_reading_plan_remains_overdue(): void
    {
        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '9876543213',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->subDay(),
            'status' => ReadingPlanStatus::Overdue,
            'completed_at' => null,
        ]);

        $this->artisan('app:update-overdue-reading-plans')
            ->assertExitCode(0);

        $readingPlan->refresh();

        $this->assertSame(
            ReadingPlanStatus::Overdue,
            $readingPlan->status
        );
    }
}