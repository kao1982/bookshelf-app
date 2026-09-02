<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReadingPlan;
use App\Models\User;
use App\Models\Book;
use App\Enums\ReadingPlanStatus;

class ReadingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'yamada@example.com')->first();

        $books = Book::whereIn('isbn', [
            '9784101010014',
            '9784422100524',
            '9784873115658',
        ])->get()->keyBy('isbn');

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $books['9784101010014']->id,
            'target_date' => now()->addDays(7),
            'status' => ReadingPlanStatus::Planned,
            'completed_at' => null,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $books['9784422100524']->id,
            'target_date' => now()->subDays(3),
            'status' => ReadingPlanStatus::Overdue,
            'completed_at' => null,
        ]);

        ReadingPlan::create([
            'user_id' => $user->id,
            'book_id' => $books['9784873115658']->id,
            'target_date' => now()->subDays(10),
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now()->subDays(2),
        ]);
    }
}
