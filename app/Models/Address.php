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

    public function addDirEnvio($envio,$num,$user){

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);
		$pais = null;
		$rsoc = '';

		if(!empty($envio['clid_pais'])){
			$pais = $strToDefault ? $envio['clid_pais'][0]->des_paises : strtoupper($envio['clid_pais'][0]->des_paises);
		}

		if(!empty($envio['clid_rsoc'])){
			$rsoc = $strToDefault ? $envio['clid_rsoc'] : strtoupper($envio['clid_rsoc']);
		}
		else{
			$rsoc = $strToDefault ? $user : strtoupper($user);
		}

        DB::select("INSERT INTO FXCLID
                (GEMP_CLID, CLI_CLID, CODD_CLID, NOMD_CLID,TIPO_CLID,CP_CLID,DIR_CLID,DIR2_CLID,POB_CLID,PAIS_CLID,CODPAIS_CLID,SG_CLID,PRO_CLID,TEL1_CLID,RSOC_CLID, CLI2_CLID, EMAIL_CLID, PREFTEL_CLID, OBS_CLID)
                VALUES
                (:emp,:num,:max_direcc,:usuario,'E',:cpostal,:direccion,:direccion2,:poblacion,:pais,:cod_pais,:via,:provincia,:telf,:rsoc, :cod2, :email, :preftel_clid, :obs_clid)",
                 array(
                    'emp' => Config::get('app.gemp'),
                     'num' => $num,
                    'max_direcc' => $envio['codd_clid'],
                    'usuario'       => $strToDefault ? $user : strtoupper($user),
                    'direccion'     => $strToDefault ? $envio['clid_direccion'] : strtoupper($envio['clid_direccion']),
                    'direccion2'     => $strToDefault ? $envio['clid_direccion_2'] : strtoupper($envio['clid_direccion_2']),
                    'cpostal'       => strtoupper($envio['clid_cpostal']),
                    'poblacion'     => $strToDefault ? $envio['clid_poblacion'] : strtoupper($envio['clid_poblacion']),
                    'pais'          => !empty($envio['clid_pais'])?strtoupper($envio['clid_pais'][0]->des_paises):null,
                    'cod_pais'   => $envio['clid_cod_pais'],
                    'via'   => strtoupper($envio['clid_via']),
                    'rsoc'   => $rsoc,
                    'provincia'   => $strToDefault ? mb_substr($envio['clid_provincia'],0,30,'UTF-8') : strtoupper(mb_substr($envio['clid_provincia'],0,30,'UTF-8')),
                    'telf'   => $envio['clid_telf'],
                    'cod2'   => !empty($envio['cod2_clid']) ? strtoupper($envio['cod2_clid']) : null,
					'email' => $strToDefault ? $envio['email_clid'] ?? '' : strtoupper($envio['email_clid'] ?? ''),
					'preftel_clid' => $envio['preftel_clid'],
					'obs_clid' => $envio['obs_clid'] ?? ''
                    )
      );
    }

	public function addContacto(Request $request, $cod_cli)
	{
		$strToDefault = config('app.strtodefault_register', 0);
		$pais = '';

		//filtramos solo los campos de direccion, y los convertimos a mayusculas si config activado
		$contactParams =  collect($request->all())->filter(function($param, $key){
			return  strpos($key, 'clid') !== false;
		})->map(function($param) use ($strToDefault){
			return $strToDefault ? $param : mb_strtoupper($param);
		});

		if(!empty($contactParams['clid_pais'])){
			$pais = DB::select("SELECT des_paises FROM FSPAISES WHERE cod_paises = :codPais", array("codPais" => $contactParams['clid_pais']));
		}

		FxClid::create([
			'cli_clid' => $cod_cli,
			'codd_clid' => 'CONT',
			'nomd_clid' => $contactParams['clid_usuario'] ?? '',
			'sg_clid' => $contactParams['clid_via'] ?? '',
			'dir_clid' => mb_substr($contactParams['clid_direccion'] ?? '', 0, 30, 'UTF-8'),
			'dir2_clid' => mb_substr($contactParams['clid_direccion'] ?? '', 30, 30, 'UTF-8'),
			'cp_clid' => $contactParams['clid_cpostal'] ?? '',
			'pob_clid' => mb_substr($contactParams['clid_poblacion'] ?? '', 0, 30, 'UTF-8'),
			'pais_clid' => $pais[0]->des_paises ?? '',
			'codpais_clid' => $contactParams['clid_pais'] ?? '',
			'rsoc_clid' => $contactParams['clid_usuario'] ?? '',
			'pro_clid' => mb_substr($contactParams['clid_provincia'] ?? '', 0, 30, 'UTF-8'),
			'tel1_clid' => $contactParams['clid_telf'] ?? '',
			'cli2_clid' => $contactParams['cod2_clid'] ?? '',
			'email_clid' => $contactParams['email_clid'] ?? '',
			'preftel_clid' => $contactParams['preftel_clid'] ?? '',
		]);
	}

    public function editDirEnvio($envio,$num){

		//Textos por defecto o toUpper
		$strToDefault = Config::get('app.strtodefault_register', 0);

         DB::select("UPDATE FXCLID
        SET CP_CLID = :cpostal, DIR_CLID = :direccion,DIR2_CLID = :direccion2, POB_CLID = :poblacion, PAIS_CLID = :pais, CODPAIS_CLID = :cod_pais, SG_CLID = :via,
        PRO_CLID = :provincia, NOMD_CLID = :name, TEL1_CLID = :telf, RSOC_CLID = :rsoc, EMAIL_CLID  = :email, PREFTEL_CLID = :preftel_clid, OBS_CLID = :obs_clid
        WHERE GEMP_CLID = :gemp and CLI_CLID = :num and CODD_CLID = :codd_clid",
             array(
                    'gemp' => Config::get('app.gemp'),
                    'num' => $num,
                    'direccion'     => $strToDefault ? $envio['clid_direccion'] : strtoupper($envio['clid_direccion']),
                    'direccion2'     => $strToDefault ? $envio['clid_direccion_2'] : strtoupper($envio['clid_direccion_2']),
                    'cpostal'       => strtoupper($envio['clid_cpostal']),
                    'poblacion'     => $strToDefault ? $envio['clid_poblacion'] : strtoupper($envio['clid_poblacion']),
                    'pais'          => strtoupper($envio['clid_pais'][0]->des_paises),
                    'cod_pais'   => $envio['clid_cod_pais'],
                    'codd_clid' =>  $envio['codd_clid'],
                    'via'   => strtoupper($envio['clid_via']),
                    'provincia'   => $strToDefault ? $envio['clid_provincia'] : strtoupper($envio['clid_provincia']),
                    'name'   => $strToDefault ? $envio['clid_name'] : strtoupper($envio['clid_name']),
                    'telf'   => $envio['clid_telf'],
					'rsoc'	=> $strToDefault ? $envio['clid_rsoc'] : strtoupper($envio['clid_rsoc']),
					'email' => $strToDefault ? $envio['email_clid'] : strtoupper($envio['email_clid']),
					'preftel_clid' => $envio['preftel_clid'],
					'obs_clid' => $envio['obs_clid'] ?? null
                    )
       );

    }

    public function getUserShippingAddress($codd_clid = false){

          $bindings = array('cod_cli'   => $this->cod_cli,
                            'gemp'      => Config::get('app.gemp'),
                            'tipoclid'  => 'E'
                            );
        $where = '';

        if(!empty($codd_clid)){
            $where = ' AND CODD_CLID = :codd_clid';
            $bindings['codd_clid'] = $codd_clid;
        }

        $sql = "SELECT * FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid  $where
               Order by codd_clid desc";


         return DB::select($sql,$bindings);

    }

    public function getMaxShippingAddress(){
         $bindings = array('cod_cli'   => $this->cod_cli,
                            'gemp'      => Config::get('app.gemp'),
                            'tipoclid'  => 'E'
                            );
        $sql = "SELECT CODD_CLID max_codd FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid AND FXCLID.CODD_CLID <> 'W1' ORDER BY CODD_CLID desc";


         return DB::select($sql,$bindings);
    }

    public function deleteAddres($codd_clid){
        $bindings = array('cod_cli'   => $this->cod_cli,
                            'gemp'      => Config::get('app.gemp'),
                            'tipoclid'  => 'E',
                            'codd_clid' => $codd_clid
                            );
        $sql = "DELETE FROM FXCLID
               WHERE FXCLID.CLI_CLID = :cod_cli AND FXCLID.GEMP_CLID = :gemp AND FXCLID.TIPO_CLID = :tipoclid AND CODD_CLID = :codd_clid";


         return head(DB::select($sql,$bindings));
    }

    public function changeFavoriteAddres($cod_clid, $new_cod_clid){

         DB::select("UPDATE FXCLID
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
