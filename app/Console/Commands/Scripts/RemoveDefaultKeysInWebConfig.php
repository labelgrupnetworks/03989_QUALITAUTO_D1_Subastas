<?php

namespace App\Console\Commands\Scripts;

use App\Models\V5\Web_Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;


class RemoveDefaultKeysInWebConfig extends Command
{
	protected $signature = 'label:remove-default-webconfig-keys';
	protected $description = 'Eliminar claves que tienen el valor por defecto';

	public function handle()
	{
		$defaultValues = Config::get('label.app', []);
		$dbValues = Web_Config::query()
		 	->pluck('value', 'key')
			->toArray();


		$sameValues = [];
		foreach ($dbValues as $key => $value) {
			if (isset($defaultValues[$key]) && ($defaultValues[$key] == $value) || (empty($defaultValues[$key]) && empty($value))) {
				$sameValues[$key] = $value;
			}
		}

		$number = Web_Config::query()
			->whereIn('key', array_keys($sameValues))
			->count();

		$this->table(['Clave', 'Valor'], array_map(null, array_keys($sameValues), $sameValues));

		$result = $this->ask("Se eliminarán $number claves de configuración que tienen el valor por defecto. ¿Está seguro de continuar? (s/n)");

		if (strtolower($result) !== 's') {
			$this->info('Operación cancelada.');
			return 0;
		}

		$number = Web_Config::query()
			->whereIn('key', array_keys($sameValues))
			->delete();

		$this->info("Se eliminaron $number claves de configuración.");
		return 0;
	}
}
