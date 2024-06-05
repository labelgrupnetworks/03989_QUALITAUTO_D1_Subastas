<?php

namespace App\Console\Commands\Observability;

use App\Actions\Observability\CheckCertificateAction;
use Illuminate\Console\Command;

class CheckCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'label:check-certificate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the certificate of the website';

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
		$checkCertificateAction = new CheckCertificateAction();
		$checkCertificateAction();
    }
}
