<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReadingPlan;

class UpdateOverdueReadingPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-overdue-reading-plans';

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
        $updated = ReadingPlan::where('status', \App\Enums\ReadingPlanStatus::Planned)
            ->whereDate('target_date', '<', today())
        ->update([
            'status' => \App\Enums\ReadingPlanStatus::Overdue,
        ]);

        $this->info("期限切れに変更した件数: {$updated}");
    }
}
