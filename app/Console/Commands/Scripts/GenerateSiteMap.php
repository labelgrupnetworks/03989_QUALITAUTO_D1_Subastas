<?php

namespace App\Console\Commands\Scripts;

use App\Http\Controllers\CronController;

class GenerateSiteMap extends \Illuminate\Console\Command
{
	protected $signature = 'label:generate-sitemap';
	protected $description = 'Genera el sitemap para el sitio web.';

	public function handle()
	{
		$this->info('Generando sitemap...');

		(new CronController)->newXmlUrl();
		// Aquí puedes agregar la lógica para generar el sitemap.
		// Por ejemplo, podrías usar un paquete como spatie/laravel-sitemap.
		// O simplemente crear un archivo XML con las URLs de tu sitio.

		$this->info('Sitemap generado correctamente.');
	}
}
