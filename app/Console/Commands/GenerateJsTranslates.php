<?php

namespace App\Console\Commands;

use App\libs\TradLib;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class GenerateJsTranslates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:jstranslates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera las traducciones para javascript';

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
		$tradLib = new TradLib();
		foreach (Config::get('app.locales') as $key => $value) {
			$tradLib->createTranslatesJs($key);
		}
		echo "translates creates";
    }
}
