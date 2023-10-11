<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\delivery;

use Config;
/**
 * Description of delivery
 *
 * @author LABEL-RSANCHEZ
 */
class Delivery_deliverea extends DeliveryService  {
    //put your code here

    private $deliverea ;
     //precio final con iva
    private $final_price;
    // calculo de la parte de iva sobre el producto
    private $tax_price;
    // porcentaje de iva, sera un número entero por ejemplo 21,
    private $tax;
    //precio base, sin IVA
    private $base_price;
    //precio que cobran a la casa de subastas
    private $provider_price;
    //objeto que contendra los códigos necesarios de cada proveedor
    private $codes_provider;
    //Indica si es posible hacer el envio, inicialmente esta a false hasta que se confirme un precio de envio
    private $success = false;

    private $error_message;

    private $insurance;

    function __construct() {

        $this->codes_provider = new \stdClass();


        $modeSandBox = Config::get('app.deliverea_sandbox');
        if(env('APP_DEBUG') ){
            $modeSandBox=1;
        }

        if ($modeSandBox == 1){

            $userapi = Config::get('app.deliverea_api_testuser');
            $passwordapi = Config::get('app.deliverea_api_testpass');
        }else{
            $userapi = Config::get('app.deliverea_api_realuser');
            $passwordapi = Config::get('app.deliverea_api_realpass');
        }
        $this->deliverea = new \Deliverea\Deliverea($userapi, $passwordapi);

        if ($modeSandBox == 1){
            $this->deliverea->setSandbox(true);
        }
    }


    function getShipmentsRates($warehouse,  $destinationCountryCode, $destinationZipCode, $lot){
        try {
            $this->prepare_dimensions($lot);

            if (!$this->validate_warehouse($warehouse)){
                $this->error_message =  trans(\Config::get('app.theme').'-app.msg_deliverea.no_validate_warehouse') ;
                $this->success = false;
                return $this->success;
            }
            if( !is_int($this->tax) ){

                $this->error_message =  trans(\Config::get('app.theme').'-app.msg_deliverea.tax_no_integer') ;
                $this->success = false;
                return $this->success;
            }

            $res = $this->deliverea->getShipmentsRates(
                new \Deliverea\Model\CountryCode($warehouse->codpais_alm),
                new \Deliverea\Model\ZipCode($warehouse->cp_alm),
                new \Deliverea\Model\CountryCode($destinationCountryCode),
                new \Deliverea\Model\ZipCode($destinationZipCode),
                new \Deliverea\Model\ParcelDimensions($lot->width, $lot->height, $lot->length, $lot->volume),
                new \Deliverea\Model\ParcelWeight($lot->weight)
            );


         if( !empty($res) ){
            $this->success = true;

            $best_price = $res->getBestServicePrice();



            if($best_price->getCommercialPrice() == "N/A" || $best_price->getCommercialPrice() == "ERR_PESO" || $best_price->getCommercialPrice() == "ERR_CONTACTO"){
                 $this->success = false;
                 if($best_price->getCommercialPrice() == "ERR_CONTACTO"){
                     $this->error_message = 'ERR_CONTACTO';
                 }else{
                    $this->error_message = 'ERR_PESO';// trans(\Config::get('app.theme').'-app.msg_deliverea.no_price_return') ;
                 }
                 return $this->success;
            }



             //precio para el clinete final, no están cargando el IVA en deliverea, por lo que el precio que pasan es el precio base
             $this->base_price = str_replace(',','',$best_price->getCommercialPrice());
             $this->provider_price =  str_replace(',','',$best_price->getPrice());
             $this->codes_provider->carrier_code =  $best_price->getCarrierCode();
             $this->codes_provider->carrier_name  = $best_price->getCarrierName();
             $this->codes_provider->service_code  =  $best_price->getServiceCode();
             $this->codes_provider->service_name  =  $best_price->getServiceName();

         } else{
              $this->success = false;
         }

            return $this->success;
        }catch (\Exception $e) {
            $this->success = false;
            //mostramos un código de error para la respuesta
            $this->error_message = 'ERR_PESO';// (string)$e ;
            return $this->success;
        }


    }
    //direccion usuario, almacen
    function newShipment($custom_dir,$warehouse,$carrier_code, $service_code, $lot, $shipping_client_ref){
        try {
            $this->prepare_dimensions($lot);
            $this->validate_warehouse($warehouse);
            $this->validate_customer_dir($custom_dir);

        // Create shipment

        //      numero paquetes,codigo referencia,fecha,servicio -siempre custom,

        $shipment = new \Deliverea\Model\Shipment(1, $shipping_client_ref, new \DateTime() , 'custom',
        $carrier_code,$service_code);
        $shipment->setParcelWeight($lot->weight);
        $shipment->setParcelHeight($lot->height);
        $shipment->setParcelWidth($lot->width);
        $shipment->setParcelLength($lot->length);
        $shipment->setParcelVolume($lot->volume);



        $shipment->insurance =  $this->insurance;

        //UPS requiere que pasemos este valor
        if($carrier_code == 'ups' ){
            $shipment->parcel_type = '02';
            $shipment->description = "samples";
            $shipment->commodities =  array();
            $shipment->commodities[]= array(
                "description" => "samples",
                "price" => "0.0000",
                "invoice_number" => "000000000",
                "country_manufacturer" => "ES"
            );
            if($custom_dir->codpais == 'US' || $custom_dir->codpais == 'CA'){
                $shipment->to_state_code = 'WV'; //dato de muestra para estados unidos y canada
            }


        }


        $fromAddress = new \Deliverea\Model\Address(
           substr( trans(\Config::get('app.theme').'-app.head.title_app'),0,40),
            $warehouse->dir_alm,
            $warehouse->pob_alm,
            $warehouse->cp_alm,
            $warehouse->codpais_alm,
            $warehouse->tel_alm
        );

        $fromAddress->setEmail($warehouse->email_alm);

        $toAddress = new \Deliverea\Model\Address(
            $custom_dir->nom,
            $custom_dir->dir,
            $custom_dir->pob,
            $custom_dir->cp,
            $custom_dir->codpais,
            $custom_dir->tel
        );

        $toAddress->setEmail($custom_dir->email);

        \Log::info(print_r($shipment,true).print_r($fromAddress,true).print_r($toAddress,true));

       /*
        $service=$this->deliverea->getServiceInfo($carrier_code, $service_code, $warehouse->codpais_alm, $warehouse->cp_alm, $custom_dir->codpais, $custom_dir->cp);
        print_r($service);
        die();
         */
        $shipment_res = $this->deliverea->newShipment($shipment, $fromAddress, $toAddress);
        $this->success = true;
        return $shipment_res;

        } catch (\Exception $e) {
            $this->success = false;
            $this->error_message = (string)$e ;

            return (string)$e;
        }
    }


    function parseFloat( $var){
      //  if(!is_float($var) && is_int($var)){
        if(!is_float($var)){
                return   floatval($var);
        }else{
            return $var;
        }

    }

    function setTax($tax){
        $this->tax = $tax;
    }

    function setInsurance($insurance){
        if(!empty($insurance)){
            $this->insurance = str_replace('.', ',',(string)$insurance);
        }
        else{
            $this->insurance = (string)"0,00";
        }

    }
     /*
    function getFinalPrice(){
        return \Tools::moneyFormat($this->final_price,false,2);
    }
    */
    function getCodesProvider(){
        return $this->codes_provider;
    }
    /*
    function getTaxPrice(){
        return $this->tax_price;
    }
    */
     function getTax(){
        return $this->tax;
    }

    function getBasePrice(){
        return $this->base_price;
    }

    function getSuccess(){
        return $this->success;
    }

    function getError(){
        return $this->error_message;
    }


     //funcion que deja los valores de medidas preparado para que n oden error, poniendo el tip ode datos que toca y el mínimo
    function prepare_dimensions($lot){
        // en cm y kg
        //pasamos a float los atributos del objeto
        foreach($lot as $attr => $value ){
           $lot->{$attr} = $this->parseFloat($lot->{$attr});

           if($lot->{$attr} < 0.1){
               $lot->{$attr} = 0.1;
           }
        }
        //hay que redondear a un decimal por que deliverea si no da error
        $lot->volume =  round($lot->width * $lot->height * $lot->length/1000000,1);
        $lot->weight =  round($lot->weight ,2);
        if($lot->volume < 0.1){
            $lot->volume =0.1;
        }

    }
     //funcion para comprobar que se dispone de valores para hacer la petición
    function validate_warehouse($warehouse){

       if(empty($warehouse->dir_alm)){
           $this->error_message = "No address in warehouse";
           return false;
       }
        $warehouse->dir_alm = substr($warehouse->dir_alm,0, 50);

       if(empty($warehouse->pob_alm)){
           $this->error_message = "No city in warehouse";
           return false;
       }
        $warehouse->pob_alm = substr($warehouse->pob_alm,0, 50);

       if(empty($warehouse->cp_alm)){
           $this->error_message = "No postal code in warehouse";
           return false;
       }
        $warehouse->cp_alm = substr($warehouse->cp_alm,0, 8);

       if(empty($warehouse->codpais_alm)){
           $this->error_message = "No country code in warehouse";
           return false;
       }
        $warehouse->codpais_alm = substr($warehouse->codpais_alm,0, 2);

       if(empty($warehouse->tel_alm)){
           $this->error_message = "No telephone in warehouse";
           return false;
       }

        $warehouse->tel_alm = substr($warehouse->tel_alm,0, 15);
       return true;

    }

     //funcion para comprobar que se dispone de valores para hacer la petición
    //PARA QUE NO DE ERROR LA PETICIÓN LA HACEMOS AUNQUE LOS VALORES ESTEN VACIOS, YA QUE SI NO DA ERRROR Y NO SE TRAMITA
    function validate_customer_dir($custom_dir){

       if(empty($custom_dir->nom )){
           $custom_dir->nom = "-";

       }
       $custom_dir->nom = substr(trim($custom_dir->nom),0, 40);

       if(empty($custom_dir->tel)){
            $custom_dir->tel = "-";
       }
       $custom_dir->tel = substr(trim($custom_dir->tel),0, 15);

       if(empty($custom_dir->dir)){
            $custom_dir->dir = "-";
        //   $this->error_message = "No address in customer";
        //   return false;
       }
       $custom_dir->dir = substr(trim($custom_dir->dir),0, 50);

       if(empty($custom_dir->pob)){
           $custom_dir->pob = "-";
        //   $this->error_message = "No city in customer";
        //   return false;
       }
       $custom_dir->pob = substr(trim($custom_dir->pob),0, 50);

       if(empty($custom_dir->cp)){
           $custom_dir->cp = "-";
          // $this->error_message = "No postal code in customer";
         //  return false;
       }
       $custom_dir->cp = substr(trim($custom_dir->cp),0, 8);

       if(empty($custom_dir->codpais)){
           $custom_dir->codpais = "-";
          // $this->error_message = "No country code in customer";
         //  return false;
       }
       $custom_dir->codpais = substr($custom_dir->codpais,0, 2);

       if(empty($custom_dir->email)){
           $custom_dir->email = "-";
        //   $this->error_message = "No email code in customer";
         //  return false;
       }
       $custom_dir->email = substr(trim($custom_dir->email),0, 50);

    }
}
