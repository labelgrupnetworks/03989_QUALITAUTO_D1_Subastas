<?php

namespace App\Console\Commands\Scripts;

use App\Models\V5\Web_Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;


class UpdateCategoryInWebConfig extends Command
{
	protected $signature = 'label:update-webconfig-categories';
	protected $description = 'Actualizar las categorías en la tabla web_config según los archivos de config/web';

	public function handle()
	{
		$categories = Web_Config::getSections();

		$dataBaseConfigs = Web_Config::query()
			->withoutGlobalScopes()
			->whereNull('category')
			->get();

		foreach ($categories as $category) {
			$categoriesFile = Config::get('label.' . $category, []);
			$inCategories = $dataBaseConfigs->whereIn('key', array_keys($categoriesFile));

			$inCategories->each(function ($config) use ($category) {
				// Actualizar la categoría en la base de datos
				$config->category = $category;
				$config->updated_by = 'script';
				$config->save();

				$this->info("Configuración actualizada: {$config->key}");
			});
		}

		$this->info('Categorías actualizadas correctamente en web_config.');
		return 0;
	}
}
