<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\libs;
use Config;
use File;
use Illuminate\Support\Str;
use \ForceUTF8\Encoding;
/**
 * Description of Str_lib
 *
 * @author LABEL-RSANCHEZ
 */
class StrLib {
    //put your code here
    public function CleanStr($string)
	{
		//vamos a probar la libreria ForceUTF8 y por eso no omitiremos los cambiso que haciamso hasta ahora
		$string = preg_replace('/\r?/', "", $string);
		$string = preg_replace('/\n?/', "", $string);
		$string = preg_replace('/\t?/', "", $string);
		$string =  str_replace('€', '&euro;', $string);
		$string =  str_replace("'", "&#39;", $string);

		$string =  str_replace("–", "-", $string);
		//Quitar problema de sala retiro
		$string =  str_replace("\\", "", $string);
		return Encoding::toUTF8($string);



     /*
        //$string = utf8_encode($string);
        //$string = iconv('UTF-8','ASCII//TRANSLIT',$string);
        //se ha quitado para que funcione correctamente el json
        //$string =  str_replace('"', '&quot;', $string);

        $string =  str_replace('€', '&euro;', $string);
        $string =  str_replace("'", "&#39;", $string);
        $string =  str_replace("–", "-", $string);
        $string= preg_replace('/\r\n?/', "\n", $string);

        //ahora vendrá con editor de texto por lo que los saltos los pondran en código HTML
       // $string =  nl2br($string);
        //$string=  str_replace("\n", "<br>",$string);

        //caracteres especiales
        $contra_barra = '\\\\';
        $guion="\-";
        $mas="\+";
        $barra = "\/";
        $cierra_corchete="\]";
        $letras_especiales ="áàäâªÁÀÂÄdoéèëêÉÈÊËreíìïîÍÌÏÎmióòöôÓÒÖÔfaúùüûÚÙÛÜsolñÑçÇ";

        $string = preg_replace("/[^a-zA-Z0-9.,[;:$contra_barra $barra  $guion $mas  $cierra_corchete $letras_especiales =}{ <>()º#@|!$%&?¿]/", " ", $string);
        return $string;
        */
    }

}
