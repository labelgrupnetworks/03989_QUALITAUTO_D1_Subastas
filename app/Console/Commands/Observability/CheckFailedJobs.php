<?php

namespace App\Console\Commands\Observability;

use App\Actions\Observability\CheckFailedJobsAction;
use Illuminate\Console\Command;

class CheckFailedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'label:check-failed-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check failed jobs and send notifications to the web team';

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
		$checkFailedJobsAction = new CheckFailedJobsAction();
		$checkFailedJobsAction();
    }
}
