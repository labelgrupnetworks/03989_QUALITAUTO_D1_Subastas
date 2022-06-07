<?php

namespace App\Http\Controllers\apirest;

use Request;
use Controller;

use App\Models\apirest\EnterpriseApiRest;

class EnterpriseApiRestController extends ApiRestController
{
    
    public function tracks(){
        $enterprise_inf = new EnterpriseApiRest();
        $tracks = $enterprise_inf->getVia();
        $inf = array(
            "status"=>"success",
            "data" => $tracks);
        
        $this->returnInf($inf);
    }
    
    public function countrys(){
        $enterprise_inf = new EnterpriseApiRest();
        $countris = $enterprise_inf->getCountries();
        $inf = array(
            "status"=>"success",
            "data" => $countris);
        
        $this->returnInf($inf);
    }
    
    public function returnTown(){
        
        $enterprise_inf = new EnterpriseApiRest();
        
        $inf = array(
            "status"=>"Error",
            "data" => "Don't exist town");
        
        $required = array('zip','country');
        $this->validatorRequired($required);
        
        $zip_code = Request::input('zip');
        $country = Request::input('country');

        $pob = $enterprise_inf->getTown($zip_code,$country);
        
        if(!empty($pob)){
           $inf['status'] =  'success';
           $inf['data'] =  $pob;
        }
        
        $this->returnInf($inf);

    }
    
    public function paramsAPP(){
        $enterprise = new EnterpriseApiRest();
        $params_sub = $enterprise->getParamsApp();
        
        $inf = array(
            "status"=>"success",
            "data" => $params_sub);
         
        $this->returnInf($inf);

    }


    public function getRepres(){

        $inf = array (
            'status' => 'success',
        );
        // $user_api = new UserApiRest();
        // $contract = new ContractApiRest();
        $enterprise = new EnterpriseApiRest(); 

        // if(!empty(Request::input('cod_cli'))){
        //     $codcli = Request::input('cod_cli');
        //     $inf['user'] = $user_api->getUser(null,$codcli);
        //     $inf['contracts'] = $contract->getContract($codcli,null);
        // }
        $inf['representative'] = $enterprise->getRepresentative();
        $this->returnInf($inf);

    }
    

}
