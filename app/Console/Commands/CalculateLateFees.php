<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\FeeService;

class CalculateLateFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:calculate-late';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and apply late fees for overdue payments';

    /**
     * Execute the console command.
     */
    public function handle(FeeService $feeService)
    {
        $this->info('Starting late fee calculation...');

        try {
            $count = $feeService->calculateLateFees();
            $this->info("Successfully processed late fees. Updated {$count} records.");
        } catch (\Exception $e) {
            $this->error("Error calculating late fees: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
