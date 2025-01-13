<?php

namespace App\Services\b2b;

use Illuminate\Support\Facades\Config;

class OwnerB2BData
{
	public $logo;

	public function __construct(
		public readonly string $id,
		public readonly string $name,
		public readonly string $rsoc,
	) {
		$this->logo = $this->getImageLink();
	}

	public static function fromArray(array $data): self
	{
		return new static(
			$data['cod'],
			$data['name'],
			$data['rsoc'],
		);
	}

	private function getImageLink()
	{
		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');

		$path = "themes/$theme/owners/$emp/{$this->id}.png";
		$publicPath = "app/public/$path";

		if (!file_exists(storage_path($publicPath))) {
			return asset("/themes/$theme/assets/img/logo.png");
		}

		return asset("storage/$path");
	}
}
