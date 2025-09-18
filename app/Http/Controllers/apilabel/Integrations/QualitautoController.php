<?php

namespace App\Http\Controllers\apilabel\Integrations;

use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Value;
use Carbon\Carbon;
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

		$colors = collect(data_get($vehicle, 'LibroVin.Colores.Color', []));
		$fotos = data_get($vehicle, 'FotosCargadas.Foto', []);

		//filter colors where attribute 'tipo' contains 'exterior'
		$exteriorColors = $colors->filter(function ($item) {
			return str_contains($item['tipo'], 'exterior') && !empty($item['descripcion']);
		})
		->implode('descripcion', '/');

		$power1 = data_get($vehicle, 'Engine.EnginePowerHp', 0);
		$power2 = data_get($vehicle, 'Engine.EnginePowerKw', 0);
		$capacity = data_get($vehicle, 'Engine.Capacity', 0);
		$registrationDate = data_get($vehicle, 'InitialRegistration', null);

		$vehicleData = [
			'title' => data_get($vehicle, 'SalesDescription', null),
			'fabricante' => data_get($vehicle, 'ManufacturerName', null),
			'modelo' => data_get($vehicle, 'BaseModelName', null),
			'version' => data_get($vehicle, 'SubModelName', null),
			'variante' => data_get($vehicle, 'ContainerNameN', null),
			'tipo' => data_get($vehicle, 'VehicleData.StructureDescription', null),
			'puertas' => data_get($vehicle, 'TechInfo.VehicleDoors', null),
			'potencia_cilindrada' => "{$power1}CV / {$power2}Kw / {$capacity}cm3",
			'kilometros' => data_get($vehicle, 'MileageEstimated', null),
			'transmision' => data_get($vehicle, 'TechInfo.GearboxType', null),
			'num_velocidades' => data_get($vehicle, 'TechInfo.NrOfGears', null),
			'combustible' => data_get($vehicle, 'Engine.FuelMethod', null),
			'asientos' => data_get($vehicle, 'TechInfo.VehicleSeats', null),
			'bastidor' => data_get($vehicle, 'VehicleIdentNumber', null),
			'matricula' => data_get($vehicle, 'RegistrationData.LicenseNumber', null),
			'fecha_matriculacion' => $registrationDate ? Carbon::parse($registrationDate)->format('d/m/Y') : null,
			'tarjeta_emision' => data_get($vehicle, 'VehicleData.EmissionClass', null),
			'color_exterior' => $exteriorColors,
			'fotos' => is_array($fotos) ? $fotos : [$fotos],
		];

		$auctionId = 'ONLINE';
		$lotId = "{$auctionId}-{$vehicleData['bastidor']}";

		$lotObject = $this->createLotObject($auctionId, $lotId, $vehicleData);
		$upserted = $this->upsertLot($auctionId, $lotId, $lotObject);
		if (!$upserted) {
			return response()->json(['message' => 'Error creating or updating lot'], 500);
		}

		$imagesAdded = $this->addImages($lotId, $vehicleData['fotos']);
		if (!$imagesAdded) {
			return response()->json(['message' => 'Error adding images'], 500);
		}

		return response()->json(['message' => 'Lot created or updated'], 200);
	}

	private function upsertLot($auctionId, $lotId, $lotObject)
	{
		$existingLots = FgAsigl0::query()
			->joinFghces1Asigl0()
			->where('sub_asigl0', $auctionId)
			->pluck('idorigen_hces1', 'ref_asigl0');

		$lotControler = new LotController();
		if ($existingLots->contains($lotId)) {

			unset($lotObject['reflot']);
			$json = $lotControler->updateLot([$lotObject]);
		} else {
			$json = $lotControler->createLot([$lotObject]);
		}

		$result = json_decode($json);
		if ($result->status == 'ERROR') {
			return false;
		}
		return true;
	}

	private function addImages($lotId, $photos)
	{
		$imageController = new ImgController();
		$imageObject = $this->createImagesObject($lotId, $photos);
		$json = $imageController->createImg($imageObject);
		$result = json_decode($json);
		if ($result->status == 'ERROR') {
			return false;
		}
		return true;
	}

	private function createLotObject($idAuction, $id, $vehicleData)
	{
		$maxReference = FgAsigl0::where('sub_asigl0', $idAuction)->max('ref_asigl0') + 1;

		$lot = [
			'idorigin' => $id,
			'title' => "{$vehicleData['fabricante']} {$vehicleData['title']}",
			'description' => "{$vehicleData['fabricante']} {$vehicleData['title']}",
			'search' => "{$vehicleData['fabricante']} {$vehicleData['title']}",
			'idsubcategory' => Config::get('app.default_idsubcategory', 'VM'),
			'idauction' => $idAuction,
			'reflot' => $maxReference,
			'features' => $this->addFeatures($vehicleData),
			'startprice' => 0,
			'hidden' => 'S',
		];

		return $lot;
	}

	private function createImagesObject($lotId, $photos)
	{
		$images = [];
		foreach ($photos as $index => $photo) {
			$images[] = [
				'idoriginlot' => $lotId,
				'order' => $index,
				'img64' => $photo['Base64']
			];
		}
		return $images;
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
