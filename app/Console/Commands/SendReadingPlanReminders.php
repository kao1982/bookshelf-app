<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminder;

class SendReadingPlanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reading-plan-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plans = ReadingPlan::where('status', \App\Enums\ReadingPlanStatus::Planned)
            ->whereDate('target_date',  now()->addDay())
            ->with('book')
            ->get();

        foreach ($plans as $plan) {
            $plan->user->notify(new ReadingPlanReminder($plan));
            }
        $this->info("リマインダー対象件数: {$plans->count()}");
    }
}
