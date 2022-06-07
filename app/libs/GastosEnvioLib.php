<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;
use App\Models\V5\WebGastosEnvio;
use App\Models\V5\WebZonasEnvio;
/**
 * Description of Str_lib
 *
 * @author LABEL-RSANCHEZ
 */
class GastosEnvioLib {

	public  function calculate($codCountry, $cp, $peso, $cmlineales){

		#sumamos cm y peso segun los valores que ya tenga el paquete
		if(\Config::get("app.suma_peso_web_gastos_envio")){
			$sumaPesos =json_decode( \Config::get("app.suma_peso_web_gastos_envio"));

			foreach($sumaPesos as $key => $values){
				if($peso <= floatval($key)){
					$cmlineales += $values[0];
					$peso += $values[1];
					#alimos del bucle
					break;
				}
			}
		}



		$codCp = $this->getCodCp($cp, $codCountry);


		if(empty($codCp)){
			#-1 SIGNIFICA QUE NO SE PUEDE ENTREGAR
			return -1;
		}

		$precio = $this->getPrecio($codCp , $peso, $cmlineales);
		return $precio;

	}

	public function getCodCp($cp, $codCountry){
		#SIEMPRE DEBE DAR RESULTADO PARA ESPAÑA,
		#si varios registros coinciden debe coger  el que coincida y tenga el código postal mas largo, gracias a estar ordenado por longitud desc
		$webZonasEnvio = new  WebZonasEnvio();
		$webZonasEnvio =  $webZonasEnvio->where("CODPAIS_ZENVIO",  $codCountry);

		$zonasEnvio = $webZonasEnvio->where(function ($query) use ($cp) {

									$length = strlen($cp);
									for($i=0;$i <$length; $i++){
										$query->orwhere("CP_ZENVIO", SUBSTR($cp, 0,$length -$i));
									}
									$query->orwhereRaw("CP_ZENVIO IS NULL");

									})#muy importante, la ordenación es la clave de que funcione, NO TOCAR,
									->orderByRaw(" LENGTH(CP_ZENVIO) DESC NULLS LAST ")
									->first();

		if(!empty($zonasEnvio)){

			return $zonasEnvio->cod_zenvio
			;
		}
		return null;
	}

	#
	public function getPrecio($codCp , $peso, $cmlineales){
		$gastosEnvio = webGastosEnvio::select("IMPORTE_GENVIO")
									->where("CODZONA_GENVIO", $codCp)
									->whereRaw("PESO_GENVIO >= ?", $peso)
									->whereRaw("CMSLINEALES_GENVIO >= ?",$cmlineales)
									->orderby("PESO_GENVIO","ASC")
									->orderby("CMSLINEALES_GENVIO","ASC")
									->first();

		if(!empty($gastosEnvio)){
			return $gastosEnvio->importe_genvio;
		}
		return -1;

	}

}
