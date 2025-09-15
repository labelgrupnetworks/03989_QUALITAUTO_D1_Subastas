<?php

namespace App\Http\Controllers\apilabel\Integrations;

use App\Http\Controllers\apilabel\LotController;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class QualitautoController
{
	public function store(Request $request)
	{
		$xml_data = $request->getContent();
		$xml = simplexml_load_string($xml_data);
		$json = json_encode($xml);
		$array = json_decode($json, true);

		$vehicle = data_get($array, 'Dossier.Vehicle', []);
		if (empty($vehicle)) {
			//docs/HttpFiles/example_data.txt
			$file = file(base_path('docs/HttpFiles/example_data.txt'));
			$xml_data = implode("", $file);
			$xml = simplexml_load_string($xml_data);
			$json = json_encode($xml);
			$array = json_decode($json, true);
			$vehicle = data_get($array, 'Dossier.Vehicle', []);

			//return response()->json(['message' => 'No vehicle data found'], 400);
		}


		$colores = data_get($vehicle, 'LibroVin.Colores.color', []);
		$fotos = data_get($vehicle, 'FotosCargadas.Foto', []);

		$vehicleData = [
			'fabricante' => data_get($vehicle, 'ManufacturerName', null),
			'modelo' => data_get($vehicle, 'SalesDescription', null),
			'tipo' => data_get($vehicle, 'VehicleTypeNameN', null),
			'potencia' => data_get($vehicle, 'Engine.EnginePowerKw', null),
			'kilometros' => data_get($vehicle, 'MileageEstimated', null),
			'transmision' => data_get($vehicle, 'TechInfo.GearboxType', null),
			'combustible' => data_get($vehicle, 'Engine.FuelMethod', null),
			'asientos' => data_get($vehicle, 'TechInfo.VehicleSeats', null),
			'bastidor' => data_get($vehicle, 'VehicleIdentNumber', null), //usar como id.
			'matricula' => data_get($vehicle, 'RegistrationData.LicenseNumber', null),
			'fecha_matriculacion' => data_get($vehicle, 'InitialRegistration', null),
			'tarjeta_emision' => data_get($vehicle, 'VehicleData.EmissionClass', null),
			'colores' => is_array($colores) ? $colores : [$colores],
			'fotos' => is_array($fotos) ? $fotos : [$fotos],
		];

		$auctionId = 'LABELO';
		$existingLots = FgAsigl0::query()
			->joinFghces1Asigl0()
			->where('sub_asigl0', $auctionId)
			->pluck('idorigen_hces1', 'ref_asigl0');

		$lotId = "{$auctionId}-{$vehicleData['bastidor']}";

		//create lot object
		$lotObject = $this->createLotObject($lotId, $vehicleData);

		$lotControler = new LotController();
		$json = $lotControler->createLot([$lotObject]);
		$result = json_decode($json);




		// if ($existingLots->contains($lotId)) {
		// 	//update
		// }

		//create

		//action code -> LABELO

		return response()->json($result);
	}

	private function createLotObject($id, $vehicleData)
	{
		$idAuction = 'LABELO';
		$maxReference = FgAsigl0::where('sub_asigl0', $idAuction)->max('ref_asigl0') + 1;

		$lot = [
			'idorigin' => $id,
			'title' => "{$vehicleData['fabricante']} {$vehicleData['modelo']}",
			'description' => "{$vehicleData['fabricante']} {$vehicleData['modelo']}",
			'search' => "{$vehicleData['fabricante']} {$vehicleData['modelo']}",
			'idsubcategory' => Config::get('app.default_idsubcategory', 'VM'),
			'idauction' => $idAuction,
			'reflot' => $maxReference,
			'features' => $this->addFeatures($vehicleData),
			'startprice' => 0,
			'hidden' => 'S',
		];

		return $lot;
	}

	private function addFeatures($vehicleData)
	{
		$fgCaracteristicas = FgCaracteristicas::query()
			->select('name_caracteristicas', 'id_caracteristicas', 'filtro_caracteristicas', 'value_caracteristicas')
			->get();

		$features = [];

		foreach ($vehicleData as $featureName => $featureValue) {

			//filtramos las caracteristicas que coinciden con las creadas en la base de datos
			$caracteristica = $fgCaracteristicas->filter(function ($item) use ($featureName) {
				return mb_strtolower($item->name_caracteristicas) == mb_strtolower($featureName);
			})->first();

			if ($caracteristica) {

				if ($caracteristica->value_caracteristicas == 'N') {
					$idvaluefeature = null;
					$newValue = $featureValue;
				} else {
					$newFeature = FgCaracteristicas_Value::addFeature($caracteristica->id_caracteristicas, $featureValue);
					$idvaluefeature = $newFeature['idFeatureValue'];
					$newValue = null;
				}

				$feature['idfeature'] = $caracteristica->id_caracteristicas;
				$feature['idvaluefeature'] = $idvaluefeature;
				$feature['value'] = $newValue;

				$features[$caracteristica->name_caracteristicas] = $feature;
			}
		}

		return $features;
	}
}
