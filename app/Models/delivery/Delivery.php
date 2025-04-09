<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\delivery;
use Illuminate\Support\Facades\DB;
use App\Models\Enterprise;
use App\Services\Auction\LotDeliveryService;

/**
 * Description of delivery
 *
 * @author LABEL-RSANCHEZ
 */
class Delivery {
    //put your code here
    //proveedor que se usará para el envio, cada proveedor tiene su modelo propio
    protected $provider;
    protected $enterprise;
    protected $lot;
    protected $warehouse;
    protected $custom_dir;
    protected $carrier;
    protected $service;
    protected $imp_embalaje;
    protected $shipping_client_ref;

    function __construct(DeliveryService $provider){
        //cargar provider name desde web_config
		$this->provider = $provider;

        //cogemos el iva por defecto de la empresa
        $this->enterprise = new Enterprise();
        $parameters = $this->enterprise->getParameters();
        $iva = $this->enterprise->getIva($parameters->tiva_prmgt);
        if(!empty($iva)){
            $this->setTax(intval($iva->iva_iva));
        }else{
            $this->setTax(intval(0));
        }
        $this->imp_embalaje = 0;


    }

    //function getShipmentsRates($warehouse,  $destinationCountryCode, $destinationZipCode,$lot){

    function getShipmentsRates($emp, $cod_sub, $ref,   $destinationCountryCode, $destinationZipCode){

        $this->getInfo($emp, $cod_sub, $ref);


        $response = $this->provider->getShipmentsRates($this->warehouse,  $destinationCountryCode, $destinationZipCode, $this->lot);

        return $response;

    }

     function setTax($tax){
        $this->provider->setTax($tax);
    }



    function newShipment($emp,$cod_sub,$ref)
	{
        $this->getInfo($emp, $cod_sub, $ref);
        $this->getCsubeInfo($emp, $cod_sub, $ref);

        $this->SetRequestCsube($cod_sub,$ref,$emp, json_encode(array("custom_dir" => $this->custom_dir, "warehouse" => $this->warehouse,"carrier_code" =>  $this->carrier, "service_code" =>  $this->service, "lot" =>  $this->lot)));
        $response = $this->provider->newShipment($this->custom_dir, $this->warehouse, $this->carrier, $this->service, $this->lot,  $this->shipping_client_ref);

        if($this->getSuccess()){
            $res =  $response->getShippingDlvrRef();

        }else{
             $res=json_encode($response);
        }

        $this->SetResponseCsube($cod_sub,$ref,$emp,$res,$this->getSuccess());
        //devolvemos true o false dependiendo si se ha podido hacer la peticion o ha dado error
        return $this->getSuccess();
    }
    /*
    function getFinalPrice(){
        return $this->provider->getFinalPrice();
    }
    */
    function getCodesProvider(){
        return $this->provider->getCodesProvider();
    }
    //devuelve el iva del servicio
    function getTaxPrice(){
        $basePrice = $this->getBasePrice();
        $tax =  $this->getTax();
        $taxPrice = round(($basePrice * $tax) /100,2);
        return $taxPrice;
    }
    //devuelve el porcentage de iva que estamso aplicando
    function getTax(){
        return $this->provider->getTax();
    }
    //devuelve el precio base
    function getBasePrice(){
        $base_price_delivery = $this->provider->getBasePrice();

         return  ($base_price_delivery + $this->imp_embalaje);

    }

    function getSuccess(){
        return $this->provider->getSuccess();
    }

    function getError(){
        return $this->provider->getError();
    }

    //devolverá los tamaños del lote dado
    function getSizes($lote){

        //si falta campo de embalaje en hces1, lo ponemos por defecto
        if(!isset($lote->embalaje_hces1)){
            $lote->embalaje_hces1 = 0;
        }

        $embalaje = $this->enterprise->getEmbalaje($lote->embalaje_hces1);
        //el código cero de embalaje indica que se suma el ancho al paquete
        $lot = new \stdClass();



        if(!empty($embalaje)){
            $this->imp_embalaje = $embalaje->imp_embalajes;
            //de momento no pongo nada me han de decir como calcularlo.
            $this->tax_embalaje = 0;
            if($lote->embalaje_hces1 == 0 ){
                $lot->width = $lote->ancho_hces1 + $embalaje->ancho_embalajes;
                $lot->height = $lote->alto_hces1 + $embalaje->alto_embalajes;
                $lot->length = $lote->grueso_hces1 + $embalaje->grueso_embalajes;

            }else{
                $lot->width = $embalaje->ancho_embalajes;
                $lot->height = $embalaje->alto_embalajes;
                $lot->length =  $embalaje->grueso_embalajes;

            }
       }
       /* NO USAREMSO PESO
       if(!empty($lote->peso_hces1)){
             $lot->weight = $lote->peso_hces1;
        }else{
        *
        */
           // altoXanchoXlargo/5000 (En cm)
            $lot->weight = ($lot->width * $lot->height * $lot->length) / 5000;



        return $lot;
    }

    function getInfo($emp,$cod_sub,$ref)
	{
         $res = DB::table('FGASIGL0')
                ->select('ANCHO_HCES1, ALTO_HCES1, PESO_HCES1, GRUESO_HCES1, ALM_HCES1, EMBALAJE_HCES1')
                ->join('FGHCES1', function ($join) {
                            $join->on('FGHCES1.EMP_HCES1', '=', 'FGASIGL0.EMP_ASIGL0')
                                 ->on('FGHCES1.NUM_HCES1', '=', 'FGASIGL0.NUMHCES_ASIGL0')
                                 ->on('FGHCES1.LIN_HCES1', '=', 'FGASIGL0.LINHCES_ASIGL0');
                        })
                 ->where('EMP_ASIGL0',$emp)
                 ->where('SUB_ASIGL0',$cod_sub)
                 ->where('REF_ASIGL0',$ref)
                ->first();

        if(!empty($res)){
            $this->lot =  $this->getSizes($res) ;
            $this->warehouse = (new LotDeliveryService)->getWarehouseById($res->alm_hces1);
        }
    }

    function getCsubeInfo($emp,$cod_sub, $ref){
        $csub= $this->getCsube($emp,$cod_sub, $ref);

        $this->carrier = $csub->carrier_csube;
        $this->service = $csub->service_csube;

        $this->custom_dir = new \stdClass();
        $this->custom_dir->nom = $csub->clifac_csub. " - ". $csub->nom_csube;
        $this->custom_dir->dir = $csub->dir_csube;
        $this->custom_dir->pob = $csub->pob_csube;
        $this->custom_dir->cp = $csub->cp_csube;
        $this->custom_dir->codpais = $csub->codpais_csube;
        $this->custom_dir->tel = $csub->tel_csube;
        $this->custom_dir->email = $csub->email_csube;
        $this->shipping_client_ref = substr($csub->ref_csube."-".$csub->sub_csube."-".md5(strtotime('now')),0,14);
        $this->provider->setInsurance($csub->imp_seguro);


    }


    public function setCsube($cod_sub, $ref, $custom_dir, $carrier_code, $service_code,  $imp, $imp_iva){
       try {
        DB::select(
                "MERGE INTO FGCSUBE csube
                USING( SELECT :emp EMP, :sub sub , :ref ref FROM dual) src
                    ON( csube.emp_csube = src.emp and csube.sub_csube = src.sub and csube.ref_csube = src.ref)
                WHEN MATCHED THEN
                    UPDATE SET CARRIER_CSUBE = :carrier,SERVICE_CSUBE = :service,NOM_CSUBE = :nom,
                    DIR_CSUBE = :dir,POB_CSUBE = :pob,CP_CSUBE = :cp,CODPAIS_CSUBE = :cod_pais,TEL_CSUBE = :tel,EMAIL_CSUBE = :email,
                    IMP_CSUBE = :imp, IMPIVA_CSUBE = :imp_iva
                WHEN NOT MATCHED THEN
                    INSERT (EMP_CSUBE,SUB_CSUBE,REF_CSUBE,CARRIER_CSUBE,SERVICE_CSUBE,NOM_CSUBE,DIR_CSUBE,POB_CSUBE,CP_CSUBE,CODPAIS_CSUBE,TEL_CSUBE,EMAIL_CSUBE,
                    IMP_CSUBE,IMPIVA_CSUBE)
                    VALUES
                    (:emp,:sub,:ref,:carrier,:service,:nom,:dir,:pob,:cp,:cod_pais,:tel,:email,:imp,:imp_iva)"
                    ,array(
                        'emp' => \Config::get('app.emp'),
                        'sub' => $cod_sub,
                        'ref' => $ref,
                        'carrier'       => $carrier_code,
                        'service'     => $service_code,
                        'nom'     =>   $custom_dir->nomd_clid,
                        'dir'       =>  $custom_dir->dir_clid,
                        'pob'     => $custom_dir->pob_clid,
                        'cp'          =>  $custom_dir->cp_clid,
                        'cod_pais'   =>  $custom_dir->codpais_clid,
                        'tel'   => $custom_dir->tel1_clid,
                        'email'   => $custom_dir->email_clid,
                        'imp' => $imp,
                        'imp_iva'   => $imp_iva,
                        )
                    );
         return true;
      }catch (\Exception $e) {
           return false;
      }
    }

    public function getCsube($emp, $cod_sub, $ref ){
        $res = DB::table('FGCSUBE')
                ->select('FGCSUBE.*,FGCSUB.clifac_csub,nvl(FGASIGL2.IMP_ASIGL2,0) + nvl(FGASIGL2.IMPIVA_ASIGL2,0) imp_seguro')
                ->leftJoin('FGCSUB', function ($join) {
                    $join->on('EMP_CSUB', '=', 'EMP_CSUBE')
                    ->on('SUB_CSUB', '=', 'SUB_CSUBE')
                    ->on('REF_CSUB', '=', 'REF_CSUBE');
                })
                //GASTOS DE SEGURO
                ->leftJoin('FGASIGL2', function ($join) {
                    $join->on('EMP_ASIGL2', '=', 'EMP_CSUBE')
                    ->on('SUB_ASIGL2', '=', 'SUB_CSUBE')
                    ->on('REF_ASIGL2', '=', 'REF_CSUBE')
                    ->on('TIPO_ASIGL2', '=', "'SE'");
                })
                 ->where('EMP_CSUBE',$emp)
                 ->where('SUB_CSUBE',$cod_sub)
                 ->where('REF_CSUBE',$ref)
                ->first();


         return $res;
    }

    public function deleteCsube($emp, $cod_sub, $ref ){
         DB::table('FGCSUBE')
                 ->where('EMP_CSUBE',$emp)
                 ->where('SUB_CSUBE',$cod_sub)
                 ->where('REF_CSUBE',$ref)
                ->delete();
    }
     public function getCustomDir( $emp,$cod_sub, $ref){
        $res = DB::table('FGCSUBE')
                ->select('CARRIER_CSUBE,SERVICE_CSUBE,NOM_CSUBE,DIR_CSUBE,POB_CSUBE,CP_CSUBE,CODPAIS_CSUBE,TEL_CSUBE,EMAIL_CSUBE')
                 ->where('EMP_CSUBE',$emp)
                 ->where('SUB_CSUBE',$cod_sub)
                 ->where('REF_CSUBE',$ref)
                ->first();


         return $res;


    }
    public function SetRequestCsube($cod_sub, $ref, $emp, $request){
        $res = DB::table('FGCSUBE')
                ->where('EMP_CSUBE',$emp)
                ->where('SUB_CSUBE',$cod_sub)
                ->where('REF_CSUBE',$ref)
                ->update(['REQUEST_CSUBE' => $request]);


         return $res;


    }
    public function SetResponseCsube($cod_sub, $ref, $emp, $response, $success){
        $res = DB::table('FGCSUBE')
                ->where('EMP_CSUBE',$emp)
                ->where('SUB_CSUBE',$cod_sub)
                ->where('REF_CSUBE',$ref)
                ->update(['RESPONSE_CSUBE' => $response, 'SUCCESS_CSUBE' => $success]);


         return $res;


    }
    /*
     public function SetImpCsube($cod_sub, $ref, $emp, $imp){
        $imp = str_replace(',', '.', $imp);
        $res = DB::table('FGCSUBE')
                ->where('EMP_CSUBE',$emp)
                ->where('SUB_CSUBE',$cod_sub)
                ->where('REF_CSUBE',$ref)
                ->update(['IMP_CSUBE' => $imp]);


         return $res;


    }
    */



    /*
    function prueba__(){
        echo "prueba";
        $a = new Delivery_deliverea();
        return $a->prueba();

    }

    function prueba($clase){
        $clase="Delivery_deliverea";

        echo "prueba";
        $a = new Delivery_deliverea();
        $a = new $clase();
        return $a->prueba();

    }
     *
     */
}
