<?php

namespace App\Services\User;

use App\DataTransferObjects\User\AddressData;
use App\Models\V5\FsPaises;
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

	public function addAddress(AddressData $addressData, $userId)
	{
		if(!$addressData->des_pais) {
			$desPais = FsPaises::query()
				->where('cod_paises', $addressData->clid_pais)
				->value('des_paises');

			$addressData->setDesPais($desPais);
		}

		$dataToModel = [
			...$addressData->toEloquentArray(),
			'cli_clid' => $userId
		];

		$strToDefault = Config::get('app.strtodefault_register', 0);
		if (!$strToDefault) {
			$dataToModel = array_map('mb_strtoupper', $dataToModel);
		}

		FxClid::create($dataToModel);
	}

	public function editAddress(AddressData $addressData, string $userId)
	{
		if(!$addressData->des_pais) {
			$desPais = FsPaises::query()
				->where('cod_paises', $addressData->clid_pais)
				->value('des_paises');

			$addressData->setDesPais($desPais);
		}

		$dataToModel = $addressData->toEloquentArray();

		$strToDefault = Config::get('app.strtodefault_register', 0);
		if (!$strToDefault) {
			$dataToModel = array_map('mb_strtoupper', $dataToModel);
		}

		FxClid::query()
			->where([
				['cli_clid', $userId],
				['codd_clid', $addressData->codd_clid]
			])
			->update($dataToModel);
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
