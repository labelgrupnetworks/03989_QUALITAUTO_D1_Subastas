<?php

namespace App\Console\Commands\Observability;

use App\Actions\Observability\HasAuctionAction;
use Illuminate\Console\Command;

class HasAuction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'label:has-auction {--when=day}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there is an auction today or in a week';

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
		$when = $this->option('when');
		$hasAuctionAction = new HasAuctionAction();
		$hasAuctionAction($when);
    }
}
