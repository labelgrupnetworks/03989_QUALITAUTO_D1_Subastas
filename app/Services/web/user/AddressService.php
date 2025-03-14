<?php

namespace App\Services\web\user;

use App\Models\V5\FxClid;
use Illuminate\Support\Facades\Config;

class AddressService
{

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
