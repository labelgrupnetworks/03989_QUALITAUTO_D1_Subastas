<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Address extends Model
{

	public $cod_cli;

	public function __construct($cod_cli = null)
	{
		$this->cod_cli = $cod_cli;
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
