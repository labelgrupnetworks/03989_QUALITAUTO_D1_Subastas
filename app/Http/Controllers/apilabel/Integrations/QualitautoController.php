<?php

namespace App\Http\Controllers\apilabel\Integrations;

use Illuminate\Http\Request;

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
			return response()->json(['message' => 'No vehicle data found'], 400);
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
		];

		return response()->json($vehicleData);
	}
}
