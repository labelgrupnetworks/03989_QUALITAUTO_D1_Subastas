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
use DB;
use Session;
use \pdo;
use yajra\Oci8\Connectors\OracleConnector;
/**
 * Description of Currency
 *
 * @author LABEL-JPALAU
 */
class Currency {
    var $price;
    var $symbol;
    var $cod;
    var $exchange;
    var $position;


    //carga el valor pasado por price y por la divisa indicada
    public function currency($price,$cod,$divori){

        $this->setDivisa($cod, $divori);
        $this->price =  $price;

    }

    public function setDivisa($cod, $divori = "EUR"){
        $currency = DB::table('FSDIV')
                    ->select('cod_div','des_div','impd_div','symbolhtml_div','pos_div')
                    ->where('cod_div',$cod)
                    ->where('divori_div',$divori)
                    ->get();

        if(!empty($currency->toArray())){
            $this->exchange = $currency[0]->impd_div ;
            $this->cod = $currency[0]->cod_div;
            $this->symbol = $currency[0]->symbolhtml_div;
            $this->position = $currency[0]->pos_div;
        }else{
            $this->exchange = 0;
            $this->cod = "";
            $this->symbol = "";
            $this->position = 'R';
        }
    }

    //devuelve todos los cambios de divisas en relación a una divisa
    public function getAllCurrencies($divori = "EUR"){
        $currency_tmp = DB::table('FSDIV')
                    ->select('cod_div','des_div','impd_div','symbolhtml_div','pos_div')
                    ->where('divori_div',$divori)
                    ->orderby('cod_div')
                    ->get();
        $currencies = array();
        foreach($currency_tmp as $currency){
            $currencies[$currency->cod_div]=$currency;
        }

        return $currencies;
    }



    public function getPrice($decimal = 2, $price = NULL){
        if(!is_null($price)){
            $this->price = $price;
        }
         return \Tools::moneyFormat($this->price * $this->exchange ,FALSE,$decimal,$this->position);
    }

    public function getPriceSymbol($decimal = 2, $price = NULL){

        //debemos permitir precio a 0, por eso n ousamso el empty si no el is_null
        if(!is_null($price)){
            $this->price = $price;
        }
        //pongo un espacio en el momento de pasar la moneda
         return \Tools::moneyFormat($this->price * $this->exchange," $this->symbol",$decimal,$this->position);
    }

    public function getPriceCod($decimal = 2, $price = NULL){
        if(!is_null($price)){
            $this->price = $price;
        }
        //pongo un espacio en el momento de pasar la moneda
         return \Tools::moneyFormat($this->price * $this->exchange," $this->cod",$decimal,$this->position);
    }

    public function getExchange(){

        return $this->exchange;
    }

    public function getSymbol(){

        return $this->symbol;
    }

     public function getCod(){

        return $this->cod;
    }

}
