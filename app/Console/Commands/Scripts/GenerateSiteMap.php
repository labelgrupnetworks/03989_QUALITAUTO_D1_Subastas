<?php

namespace App\Console\Commands\Scripts;

use App\Jobs\GenerateSitemapJob;
use Illuminate\Console\Command;

class GenerateSiteMap extends Command
{
	protected $signature = 'label:generate-sitemap';
	protected $description = 'Genera el sitemap para el sitio web.';

	public function handle()
	{
		GenerateSitemapJob::dispatch();
		$this->info('Job de generación de sitemap encolado.');
	}
}
