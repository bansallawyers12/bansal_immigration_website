<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run cron job';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Cron job executed successfully.');
        return 0;
    }
}
