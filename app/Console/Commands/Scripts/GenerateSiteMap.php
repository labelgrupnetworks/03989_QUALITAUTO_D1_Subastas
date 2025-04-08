<?php

namespace App\Console\Commands\Scripts;

use App\Jobs\GenerateSitemapJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class GenerateSiteMap extends Command
{
	protected $signature = 'label:generate-sitemap';
	protected $description = 'Genera el sitemap para el sitio web.';

	public function handle()
	{
		GenerateSitemapJob::dispatch()->onQueue(Config::get('app.queue_env'));
		$this->info('Job de generación de sitemap encolado.');
	}
}
