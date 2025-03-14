<?php

# Ubicacion del modelo
namespace App\Models;

use App\Models\V5\FxClid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class Address extends Model
{

	public $cod_cli;

	public function __construct($cod_cli = null)
	{
		$this->cod_cli = $cod_cli;
	}

	public function editDirEnvio($envio, $num)
	{

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);

		DB::select(
			"UPDATE FXCLID
        SET CP_CLID = :cpostal, DIR_CLID = :direccion,DIR2_CLID = :direccion2, POB_CLID = :poblacion, PAIS_CLID = :pais, CODPAIS_CLID = :cod_pais, SG_CLID = :via,
        PRO_CLID = :provincia, NOMD_CLID = :name, TEL1_CLID = :telf, RSOC_CLID = :rsoc, EMAIL_CLID  = :email, PREFTEL_CLID = :preftel_clid, RSOC2_CLID = :rsoc2_clid
        WHERE GEMP_CLID = :gemp and CLI_CLID = :num and CODD_CLID = :codd_clid",
			array(
				'gemp' => Config::get('app.gemp'),
				'num' => $num,
				'direccion'     => $strToDefault ? $envio['clid_direccion'] : strtoupper($envio['clid_direccion']),
				'direccion2'     => $strToDefault ? $envio['clid_direccion_2'] : strtoupper($envio['clid_direccion_2']),
				'cpostal'       => strtoupper($envio['clid_cpostal']),
				'poblacion'     => $strToDefault ? $envio['clid_poblacion'] : strtoupper($envio['clid_poblacion']),
				'pais'          => strtoupper($envio['clid_pais']),
				'cod_pais'   => $envio['clid_cod_pais'],
				'codd_clid' =>  $envio['codd_clid'],
				'via'   => strtoupper($envio['clid_via']),
				'provincia'   => $strToDefault ? $envio['clid_provincia'] : strtoupper($envio['clid_provincia']),
				'name'   => $strToDefault ? $envio['clid_name'] : strtoupper($envio['clid_name']),
				'telf'   => $envio['clid_telf'],
				'rsoc'	=> $strToDefault ? $envio['clid_rsoc'] : strtoupper($envio['clid_rsoc']),
				'email' => $strToDefault ? $envio['email_clid'] : strtoupper($envio['email_clid']),
				'preftel_clid' => $envio['preftel_clid'],
				'rsoc2_clid' => $envio['rsoc2_clid'] ?? null
			)
		);
	}

	public function getUserShippingAddress($codd_clid = false)
	{

		$bindings = array(
			'cod_cli'   => $this->cod_cli,
			'gemp'      => Config::get('app.gemp'),
			'tipoclid'  => 'E'
		);
		$where = '';

		if (!empty($codd_clid)) {
			$where = ' AND CODD_CLID = :codd_clid';
			$bindings['codd_clid'] = $codd_clid;
		}

		$sql = "SELECT * FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid  $where
               Order by codd_clid desc";


		return DB::select($sql, $bindings);
	}

	public function getMaxShippingAddress()
	{
		$bindings = array(
			'cod_cli'   => $this->cod_cli,
			'gemp'      => Config::get('app.gemp'),
			'tipoclid'  => 'E'
		);
		$sql = "SELECT CODD_CLID max_codd FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid AND FXCLID.CODD_CLID <> 'W1' ORDER BY CODD_CLID desc";


		return DB::select($sql, $bindings);
	}

	public function deleteAddres($codd_clid)
	{
		$bindings = array(
			'cod_cli'   => $this->cod_cli,
			'gemp'      => Config::get('app.gemp'),
			'tipoclid'  => 'E',
			'codd_clid' => $codd_clid
		);
		$sql = "DELETE FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid AND CODD_CLID = :codd_clid";


		return head(DB::select($sql, $bindings));
	}

	public function changeFavoriteAddres($cod_clid, $new_cod_clid)
	{

		DB::select(
			"UPDATE FXCLID
        SET CODD_CLID = :new_cod_clid
        WHERE GEMP_CLID = :gemp and CLI_CLID = :num and CODD_CLID = :codd_clid",
			array(
				'gemp' => Config::get('app.gemp'),
				'num' => $this->cod_cli,
				'codd_clid' => $cod_clid,
				'new_cod_clid'  => $new_cod_clid
			)
		);
	}
}
