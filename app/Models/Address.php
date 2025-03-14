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
}
