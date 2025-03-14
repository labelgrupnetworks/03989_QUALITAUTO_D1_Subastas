<?php

namespace App\Services\web\user;

use App\Models\V5\FxClid;
use Illuminate\Support\Facades\Config;

class AddressService
{
	public function editAddress($user, $envio)
	{
		$strToDefault = Config::get('app.strtodefault_register', 0);
		$userId = $user->cod_cli;
		$addressId = $envio['codd_clid'];

		$addressData = [
			'clid_direccion' => $envio['clid_direccion'],
			'clid_direccion_2' => $envio['clid_direccion_2'],
			'clid_cpostal' => $envio['clid_cpostal'],
			'clid_poblacion' => $envio['clid_poblacion'],
			'clid_pais' => $envio['clid_pais'][0]->des_paises,
			'clid_cod_pais' => $envio['clid_cod_pais'],
			'clid_via' => $envio['clid_via'],
			'clid_provincia' => $envio['clid_provincia'],
			'clid_name' => $envio['clid_name'],
			'clid_telf' => $envio['clid_telf'],
			'clid_rsoc' => $envio['clid_rsoc'],
			'email_clid' => $envio['email_clid'],
			'preftel_clid' => $envio['preftel_clid'],
			'rsoc2_clid' => $envio['rsoc2_clid'] ?? null
		];

		if ($strToDefault) {
			$addressData = array_map(function ($value) {
				return mb_strtoupper($value);
			}, $addressData);
		}

		FxClid::query()
			->where([
				['cli_clid', $userId],
				['codd_clid', $addressId]
			])
			->update($addressData);
	}

	public function getAddresses($user, $addressId = null)
	{
		$userId = $user->cod_cli;

		return FxClid::query()
			->where([
				['cli_clid', $userId],
				['tipo_clid', 'E']
			])
			->when($addressId, function ($query) use ($addressId) {
				$query->where('codd_clid', $addressId);
			})
			->get();
	}

	/**
	 * Se utiliza solamente en metodo de AddressController
	 * Se puede refactorizar junto a ese metodo.
	 * Ahora mismo espera retornar un array, al refactorizar se puede retornar un valor.
	 */
	public function getMaxAddressId($user)
	{
		$userId = $user->cod_cli;
		return FxClid::query()
			->select('codd_clid as max_codd')
			->where([
				['cli_clid', $userId],
				['tipo_clid', 'E'],
				['codd_clid', '!=', 'W1']
			])
			->orderBy('codd_clid', 'desc')
			->get();
	}

	public function deleteAddress($user, $codd_clid)
	{
		$userId = $user->cod_cli;

		FxClid::query()
			->where([
				['cli_clid', $userId],
				['tipo_clid', 'E'],
				['codd_clid', $codd_clid]
			])
			->delete();

		return true;
	}

	public function changeFavoriteAddress($user, $codd_clid, $new_cod_clid)
	{
		$userId = $user->cod_cli;

		FxClid::query()
			->where([
				['cli_clid', $userId],
				['codd_clid', $codd_clid]
			])
			->update(['codd_clid' => $new_cod_clid]);

		return true;
	}
}
