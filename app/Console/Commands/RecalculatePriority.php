<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PriorityCalculator;

class RecalculatePriority extends Command
{
    protected $signature = 'priority:recalculate';
    protected $description = 'Recalculate priority scores for all eligible order items';

    public function handle()
    {
        $this->info('Starting priority recalculation...');

        $count = PriorityCalculator::recalculateAll('scheduled_update');

        $this->info("Priority recalculation completed. {$count} items updated.");

        return 0;
    }
}
