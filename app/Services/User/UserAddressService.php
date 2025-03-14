<?php

namespace App\Services\User;

use App\Models\V5\FxClid;
use Illuminate\Support\Facades\Config;

class UserAddressService
{
	public static function shouldSaveDeliveryAddressInRegister($hasShippingAddress)
	{
		// En Soler y CSM, si la dirección de envío es la misma que la de facturación, no se guarda la dirección de envío.
		// Por defecto, al seleccionar la misma dirección de envío que la de facturación,
		// se rellena la dirección de envío con la dirección de facturación.
		return Config::get('app.delivery_address', false)
			&& (Config::get('app.save_address_when_empty', true) || !$hasShippingAddress);
	}

	public function addAddress($envio, $num, $user)
	{
		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);
		if (!$strToDefault) {
			$envio = array_map('mb_strtoupper', $envio);
			$user = mb_strtoupper($user);
		}

		$rsoc = $envio['clid_rsoc'] ?? $user;

		//'codd_clid' => 'CONT', se usaba para carlandia tener datos de contacto del usuario
		$address = [
			'cli_clid' => $num,
			'codd_clid' => $envio['codd_clid'],
			'nomd_clid' => $user ?? '',
			'tipo_clid' => 'E',
			'cp_clid' => $envio['clid_cpostal'] ?? '',
			'dir_clid' => $envio['clid_direccion'] ?? '',
			'dir2_clid' => $envio['clid_direccion_2'] ?? '',
			'pob_clid' => $envio['clid_poblacion'] ?? '',
			'pais_clid' => $envio['clid_pais'] ?? '',
			'codpais_clid' => $envio['clid_cod_pais'] ?? '',
			'sg_clid' => $envio['clid_via'] ?? '',
			'pro_clid' => mb_substr($envio['clid_provincia'] ?? '', 0, 30, 'UTF-8'),
			'tel1_clid' => $envio['clid_telf'] ?? '',
			'rsoc_clid' => $rsoc ?? '',
			'cli2_clid' => $envio['cod2_clid'] ?? null,
			'email_clid' => $envio['email_clid'] ?? '',
			'preftel_clid' => $envio['preftel_clid'] ?? '',
			'rsoc2_clid' => $envio['rsoc2_clid'] ?? '',
			'mater_clid' => $envio['mater_clid'] ?? 'N'
		];

		FxClid::create($address);
	}

	public function editAddress($envio, $num)
	{
		$addressId = $envio['codd_clid'];

		$strToDefault = Config::get('app.strtodefault_register', 0);
		if (!$strToDefault) {
			$envio = array_map('mb_strtoupper', $envio);
		}

		$addressData = [
			'dir_clid' => $envio['clid_direccion'] ?? '',
			'dir2_clid' => $envio['clid_direccion_2'] ?? '',
			'cp_clid' => $envio['clid_cpostal'] ?? '',
			'pob_clid' => $envio['clid_poblacion'] ?? '',
			'pais_clid' => $envio['clid_pais'] ?? '',
			'codpais_clid' => $envio['clid_cod_pais'] ?? '',
			'sg_clid' => $envio['clid_via'] ?? '',
			'pro_clid' => mb_substr($envio['clid_provincia'] ?? '', 0, 30, 'UTF-8'),
			'nomd_clid' => $envio['clid_name'] ?? '',
			'tel1_clid' => $envio['clid_telf'] ?? '',
			'rsoc_clid' => $envio['clid_rsoc'] ?? '',
			'email_clid' => $envio['email_clid'] ?? '',
			'preftel_clid' => $envio['preftel_clid'] ?? '',
			'rsoc2_clid' => $envio['rsoc2_clid'] ?? '',
			'mater_clid' => $envio['mater_clid'] ?? 'N'
		];

		FxClid::query()
			->where([
				['cli_clid', $num],
				['codd_clid', $addressId]
			])
			->update($addressData);
	}

	public function getUserAddressById($codCli, $addressId)
	{
		return FxClid::query()
			->where([
				['cli_clid', $codCli],
				['tipo_clid', 'E'],
				['codd_clid', $addressId]
			])
			->first();
	}
}
