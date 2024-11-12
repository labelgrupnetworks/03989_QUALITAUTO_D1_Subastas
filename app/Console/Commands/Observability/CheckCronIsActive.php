<?php

namespace App\Console\Commands\Observability;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCronIsActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'label:check-cron-is-active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the cron is active';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		Log::debug("Cron is active!!!!");
    }
}
