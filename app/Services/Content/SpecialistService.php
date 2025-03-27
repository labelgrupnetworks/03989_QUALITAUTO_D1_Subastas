<?php

namespace App\Services\Content;

use App\Models\V5\FgEspecial1;
use Illuminate\Support\Facades\Config;

class SpecialistService
{
	public function infEspecialistas()
	{
		return FgEspecial1::getSpecialistsWithJoins();
	}

	public function getAllSpecialists()
	{
		return FgEspecial1::getSpecialists();
	}

	public function getSpecialist($per_especial1)
	{
		return FgEspecial1::getSpecialist($per_especial1);
	}

	public function getSpecialistsByOrtsec($lin_ortsec0)
	{
		if (Config::get('app.specialists_model', false)) {
			return FgEspecial1::getSpecialistsByOrtsec($lin_ortsec0);
		}
		return $this->getSpecialistsWithoutModel($lin_ortsec0);
	}

	/**
	 * @deprecated
	 * Se utiliza por soler. No se debe utilizar en nuevos desarrollos
	 */
	private function getSpecialistsWithoutModel($lin_ortsec0)
	{
		$specialistsToModel = FgEspecial1::getSpecialistsByOrtsec($lin_ortsec0);

		return $specialistsToModel->map(function ($specialist) {
			if ($specialist->relationLoaded('specialty')) {
				$specialistTemp = array_merge($specialist->toArray(), $specialist->specialty->toArray());
				unset($specialistTemp['specialty']);
				return (object) $specialistTemp;
			}
			return (object) $specialist->toArray();
		})->toArray();
	}
}
