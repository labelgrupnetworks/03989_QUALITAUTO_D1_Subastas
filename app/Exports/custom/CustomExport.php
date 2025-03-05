<?php

namespace App\Exports\custom;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class CustomExport
{
	protected $theme;

	public function __construct()
	{
		$this->theme = Config::get('app.theme');
	}

	/**
	 * Obtener los nombres de las clases de exportación
	 * @return Collection
	 */
	public function getExportsNames()
	{
		$exportsCollection = $this->getExportsCollection();
		return $exportsCollection->pluck('name');
	}

	/**
	 * Obtener la clase de exportación por su nombre
	 * @param string $name
	 * @return BaseCustomExport
	 */
	public function getExport($name) :BaseCustomExport
	{
		$exportsCollection = $this->getExportsCollection();
		$export = $exportsCollection->where('name', $name)->first();
		return new $export['className'];
	}

	/**
	 * Obtener todas las clases de exportación de la carpeta custom
	 * @return Collection
	 */
	private function getExportsCollection() :Collection
	{
		$path = app_path('Exports/custom' . DIRECTORY_SEPARATOR . $this->theme);
		if (!file_exists($path)) {
			return collect([]);
		}

		// Get all the files in the custom folder
		$files = scandir($path);
		$exports = [];
		foreach ($files as $file) {
			if($file == '.' || $file == '..') {
				continue;
			}

			$exportRouteFile = 'App\Exports\custom\\' . $this->theme . '\\' . $file;
			$exportNameSpace = 'App\Exports\custom\\' . $this->theme . '\\' . str_replace('.php', '', $file);
			$exportName = (new $exportNameSpace)->getName();

			$exports[] = [
				'route' => $exportRouteFile,
				'className' => $exportNameSpace,
				'name' => $exportName
			];
		}

		return collect($exports);
	}
}
