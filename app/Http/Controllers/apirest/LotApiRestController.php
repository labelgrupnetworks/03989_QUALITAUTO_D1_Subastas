<?php

namespace App\Http\Controllers\apirest;

use Request;
use Config;
use Controller;
use File;

use App\Models\apirest\UserApiRest;
use App\Models\apirest\EnterpriseApiRest;
use App\Models\apirest\ContractApiRest;
use App\Models\apirest\AuctionApiRest;
use App\Models\apirest\LotApiRest;


class LotApiRestController extends ApiRestController
{
    
  public function setLot(){
      $lotModal = new LotApiRest();
      
      $inf = array (
        'status' => 'success',
      );
       
      $required = array('num_hces1','sec_hces1','titulo_hces1','alm_hces1','nobj_hces1','tipoobj_hces1','impsal_hces1','imptas_hces1','imptash_hces1','sub_hces1');
      $this->validatorRequired($required);
      
      $num_hces1 = Request::input('num_hces1');
      
      //buscar numero de linia del lote
      if(empty(Request::input('lin_hces1'))){
        $lin_hces1 = $lotModal->generateLin($num_hces1);
      }else{
        $lin_hces1 = Request::input('lin_hces1');
      }
      
      $sec = Request::input('sec_hces1');
        
      if(!empty($sec)){
          $comisionL_temp = $lotModal->getSeccions($sec);
      }

      if(!empty($comisionL_temp)){
         
          $comisionL = $comisionL_temp[0]->comi_sec;
      }else{
          $comisionL = 0;
      }
      
      $lot = new \stdClass();
      $lot->num_hces1 = $num_hces1;
      $lot->lin_hces1 = $lin_hces1;
      $lot->sub_hces1 = Request::input('sub_hces1');
      $lot->sec_hces1 = $sec;
      $lot->titulo_hces1 = Request::input('titulo_hces1');
      $lot->alm_hces1 = Request::input('alm_hces1');
      $lot->nobj_hces1 = Request::input('nobj_hces1');
      $lot->tipoobj_hces1 = Request::input('tipoobj_hces1');
      $lot->impsal_hces1 = Request::input('impsal_hces1');
      $lot->impres_hces1 = Request::input('impsal_hces1');
      $lot->imptas_hces1 = Request::input('imptas_hces1');
      $lot->imptash_hces1 = Request::input('imptash_hces1');
      $lot->desc_hces1 = Request::input('desc_hces1');
      $lot->prop_hces1 = Request::input('prop_hces1');
      $lot->id_hces1 = ($num_hces1*10000) + $lin_hces1;
      $lot->pc_hces1 = !empty(Request::input('pc_hces1'))?Request::input('pc_hces1'):0;
      $lot->comp_hces1 = !empty(Request::input('comp_hces1'))?Request::input('comp_hces1'):0;
      $lot->coml_hces1 = $comisionL;

      $lotModal->insertLot($lot);
      
      if(!empty(Request::input('file'))){
        $this->saveImageLot( Request::input('file'),$num_hces1,$lin_hces1);
      }
      
      $inf = array (
        'status' => 'success',
        'data' => array(
            'lot' => $lot
        )
      );
      
      $this->returnInf($inf);
            
  }
  
  public function getLot(){
      
      $lotsmodal = new LotApiRest();
      $auctionsmodal = new AuctionApiRest();
      
      $almacen = $lotsmodal->getWarehouse();
      $seccions = $lotsmodal->getSeccions();
      $objecttype = $lotsmodal->getObjectTypes();
      $auction = $auctionsmodal->getSub(null,null,null);
      $inf = array (
        'status' => 'success',
        'data' => array(
            'almacen' => $almacen,
            'seccions' => $seccions,
            'objectType' =>$objecttype,
            'auction'   => $auction
        )
      );
      
      $this->returnInf($inf);

  }
  
  public function saveImageLot($img_temp,$num_hces1,$lin_hces1){
      
     
    $dest_path =  'img/'.Config::get('app.emp').'/'.$num_hces1.'/';
    $img = json_decode($img_temp);
    foreach($img as $key => $imgSource){
        $id_image = $key + 1;
        $imageName = Config::get('app.emp').'-'.$num_hces1.'-'.$lin_hces1.'-NV'.$id_image.'.jpg';

        $this->saveImage($dest_path,$imageName,$imgSource);
    }
     
   
  }
    
}
