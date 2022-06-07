<?php

namespace App\Http\Controllers\apirest;

use Request;
use Config;
use Controller;
use App\Models\apirest\UserApiRest;
use App\Models\apirest\EnterpriseApiRest;
use App\Models\apirest\ContractApiRest;
use App\Models\apirest\AuctionApiRest;
use App\Models\apirest\LotApiRest;

class ContractApiRestController extends ApiRestController
{
    
    public function getContract($idcontract = null){

        $contract = new ContractApiRest();
        $auction = new AuctionApiRest();
        $lot_modal = new LotApiRest();
        $enterprise = new EnterpriseApiRest();
                
        $inf = array (
                    'status' => 'success'
                );
        
        if(!empty($idcontract)){
            $required = array('cod_cli');
            $this->validatorRequired($required);
            $codcli = Request::input('cod_cli');
            $inf['data']['contract'] = $contract->getContract($codcli,$idcontract);
        }
        $inf['data']['situa'] = $contract->getSitua();
        $type_sub = array('S','N','A');
        $inf['data']['auction'] = $auction->getSub($type_sub,null,null);
        $inf['data']['type'] = $enterprise->getTsec();
        $inf['data']['representative'] = $enterprise->getRepresentative();
        print_r($inf);
        die();

        $this->returnInf($inf);

    }
    
    //Generar contrato
    public function newContract(){
        $contract = new ContractApiRest();
        $inf = array (
                    'status' => 'error'
                );
        
        $required = array('cod_cli','sub_hces0','obs_hces0','situa_hces0','tsec_hces0');
        $this->validatorRequired($required);
        
        $user_modal = new UserApiRest();
        $cod_cli = Request::input('cod_cli');
        //Buscamos usuario del contrato
        $user = $user_modal->getUser(null,$cod_cli,array('N'));
        if(empty($user)){
            $inf["data"]["msg_error"] = "User don't exist";
            $this->returnInf($inf);
        }
        
        //Generamos objeto para el contrato
        $newcontract = new \stdClass();
        $newcontract->num_hces0 = $this->generateCodContract();
        $newcontract->rsoc_hces0 = $user->rsoc_cli;
        $newcontract->prop_hces0 = $user->cod_cli;
        $newcontract->representante_hces0 = Request::input('representante_hces0');
        $newcontract->obsdet_hces0 = Request::input('obsdet_hces0');
        $newcontract->comp_hces0 = !empty($user->comi_cli)?$user->comi_cli:0;
        $newcontract->situa_hces0 = Request::input('situa_hces0');
        $newcontract->tsec_hces0 = Request::input('tsec_hces0');
        $newcontract->sub_hces0 = Request::input('sub_hces0');
        $newcontract->usra_hces0 =  Request::input('user');
        $newcontract->fec_hces0 = date('d-m-Y');
        //Insertamos contrato

        if($contract->setContract($newcontract)){
            $inf = array (
                    'status' => 'success',
                    'data' => $newcontract
                );
        }else{
            $inf ["data"]["msg_error"] = "Error to generate contract.";
        }
       
        $this->returnInf($inf);
        
    }
    
    //Generar numero de contrato
    public function generateCodContract(){
        $enterprise = new EnterpriseApiRest();
        $contract = new ContractApiRest();
        $result = 0;
        $params_sub = $enterprise->getParamsSub();
        
        if(!empty($params_sub)){
            $dnumaut = $params_sub->dnumaut_prmsub;
            $hnumaut = $params_sub->hnumaut_prmsub;

            $num_contract = $contract->getNumContract($dnumaut,$hnumaut);
            if(!empty($num_contract)){
                if ($num_contract < $hnumaut)
                {
                    $num_contract++;
                    $result = $num_contract;
                }
            }else{
                $result = $dnumaut;
            }
        }
        
        return $result;

    }
    

        public function updateContract($idcontract = null){
        $contract = new ContractApiRest();
        $required = array('cod_cli','sub_hces0','obs_hces0','situa_hces0','tsec_hces0', 'num_hces0');
        $inf = array (
            'status' => 'error'
        );

        $this->validatorRequired($required);
        
        $user_modal = new UserApiRest();
        $cod_cli = Request::input('cod_cli');
        //Buscamos usuario del contrato
        
        $user = $user_modal->getUser(null,$cod_cli,array('N'));

        if(empty($user)){
            $inf["data"]["msg_error"] = "User don't exist";
            $this->returnInf($inf);
        }
        
        //Generamos objeto para el contrato
        $newcontract = new \stdClass();
        $newcontract->num_hces0 = Request::input('num_hces0');
        $newcontract->rsoc_hces0 = $user->rsoc_cli;
        $newcontract->prop_hces0 = $user->cod_cli;
        $newcontract->representante_hces0 = Request::input('representante_hces0');
        $newcontract->obs_hces0 = Request::input('obs_hces0');
        $newcontract->comp_hces0 = !empty($user->comi_cli)?$user->comi_cli:0;
        $newcontract->situa_hces0 = Request::input('situa_hces0');
        $newcontract->tsec_hces0 = Request::input('tsec_hces0');
        $newcontract->sub_hces0 = Request::input('sub_hces0');
        $newcontract->usra_hces0 =  Request::input('user');
        
        if($contract->updateContract($newcontract)){
            $inf = array (
                    'status' => 'success',
                    'data' => $newcontract
                );
        }else{
            $inf ["data"]["msg_error"] = "Error to generate contract.";
        }
       
        $this->returnInf($inf);

    }
}
