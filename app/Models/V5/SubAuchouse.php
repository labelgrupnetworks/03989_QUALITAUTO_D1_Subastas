<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SubAuchouse extends Model
{
	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $connection = 'subalia';
	protected $table = 'SUB_AUCHOUSE';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo
	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;
	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function importAuctionUrl($codSub)
	{
		$subaliaUrl = Config::get("app.subalia_URL");
		$hash = $this->getHashBySub($codSub);

		return "{$subaliaUrl}/forceImportAuction?client={$this->cod_auchouse}&cod_sub={$codSub}&hash={$hash}";
	}

	public function hideAuctionUrl($codSub)
	{
		$subaliaUrl = Config::get("app.subalia_URL");
		$emp = Config::get("app.emp");
		$hash = $this->getHashBySub($codSub);

		return "{$subaliaUrl}/hideAuctionErp/{$this->cod_auchouse}-$emp-$codSub/$hash";
	}

	private function getHashBySub($codSub)
	{
		return hash_hmac("sha256", Config::get("app.emp") . " " . $codSub, $this->hash);
	}

	public static function getAuchouse()
	{
		$subaliaClient = Config::get('app.subalia_cli', false);
		$subaliaEmp = Config::get('app.APP_SUBALIA_EMP', '001');

		if (!$subaliaClient) {
			return null;
		}

		try {
			return self::query()
				->where([
					['emp_auchouse', $subaliaEmp],
					['cli_auchouse', $subaliaClient]
				])
				->first();
		} catch (\Throwable $th) {
			Log::error('Error al recuperar la casa de subastas de subalia', ['error' => $th->getMessage()]);
			Log::error($th);
			return null;
		}
	}
}
