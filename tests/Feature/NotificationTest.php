<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use App\Enums\ReadingPlanStatus;
use App\Notifications\ReadingPlanReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reading_plan_reminder_is_stored_in_database(): void
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

        $user->notify(new ReadingPlanReminder($readingPlan));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => ReadingPlanReminder::class,
        ]);
    }
    public function test_reading_plan_reminder_contains_correct_data(): void
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

        $user->notify(new ReadingPlanReminder($readingPlan));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => ReadingPlanReminder::class,
            'data' => json_encode([
                'message' => '明日が読書予定日です。',
                'book_title' => 'テスト書籍',
                'target_date' => $readingPlan->target_date->format('Y-m-d'),
            ]),
        ]);
    }
    public function test_reading_plan_reminder_sends_mail(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567896',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $user->notify(new ReadingPlanReminder($readingPlan));

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }
    public function test_reading_plan_reminder_command_notifies_tomorrows_plan(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567897',
            'user_id' => $user->id,
        ]);

        $readingPlan = ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $this->artisan('app:send-reading-plan-reminders')
            ->assertExitCode(0);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            ReadingPlanReminder::class
        );
    }
    public function test_reading_plan_reminder_command_does_not_notify_other_dates(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567898',
            'user_id' => $user->id,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDays(3),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        $this->artisan('app:send-reading-plan-reminders')
            ->assertExitCode(0);

        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }
    public function test_reading_plan_reminder_command_does_not_notify_completed_plan(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567899',
            'user_id' => $user->id,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        $this->artisan('app:send-reading-plan-reminders')
            ->assertExitCode(0);

        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }
    public function test_reading_plan_reminder_command_does_not_notify_overdue_plan(): void
    {
        \Illuminate\Support\Facades\Notification::fake();

        $user = User::factory()->create();

        $book = Book::create([
            'title' => 'テスト書籍',
            'author' => 'テスト著者',
            'isbn' => '1234567800',
            'user_id' => $user->id,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDay(),
            'status' => ReadingPlanStatus::Overdue,
            'completed_at' => null,
        ]);

        $this->artisan('app:send-reading-plan-reminders')
            ->assertExitCode(0);

        \Illuminate\Support\Facades\Notification::assertNothingSent();
    }
}