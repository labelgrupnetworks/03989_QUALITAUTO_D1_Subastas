<?php

namespace App\Http\Controllers\apirest;

use Request;
use Config;
use Controller;
use App\Models\apirest\UserApiRest;
use App\Models\apirest\ContractApiRest;
use App\Models\apirest\EnterpriseApiRest;
use App\Providers\ToolsServiceProvider;
use App\Support\Localization;

class UserApiRestController extends ApiRestController
{

    public function index(){

        $inf_user = $this->existUser(true);
        $inf = array(
            "status"=>"success",
            "data" => $inf_user);
        $this->returnInf($inf);

    }


    public function allUsers(){

        $user_api = new UserApiRest();
        $search = array();
        $like = array();

try {
    if(!empty(Request::input('search'))){
        $search = json_decode(Request::input('search'));
        $filter = array("tipo_cli","nom_cli","rsoc_cli","cif_cli","tel1_cli","tel2_cli","email_cli","pob_cli","repres_cli","cod_cli","pro_cli");
        $like = $this->generateFilters($filter,$search);
    }

    $users = $user_api->getAllusers($like);
    $inf = array(
        "status"=>"success",
        "data" => $users);
    $this->returnInf($inf);
    } catch (Exception $e) {
        $inf = array(
            "status"=>"error",
            "error" => $e);
        $this->returnInf($inf);
    }

}

    public function setUser(){
    //    try{

        $enterprise_inf = new EnterpriseApiRest();
            $user_api = new UserApiRest();
            $required = array('cif_cli','pais_cli','nom_cli','dir_cli','cp_cli','pob_cli');
            $this->validatorRequired($required);
            $inf_user = '';
            $cif_cli = Request::input('cif_cli');

            if(empty(Request::input('cod_cli'))){
                $params = $enterprise_inf->getParams();
                $codcli = $user_api->getCodNewUser($params->tcli_params);
                $userexist = $user_api->getUser($cif_cli,null,array('N','S'));
                print_r($userexist);
                die();
                if(!empty($userexist)){
                    $inf = array (
                        'status' => 'error',
                        'data' => array('msg_error' => "This user exist.",'cod_cli'=>$userexist->cod_cli)
                    );
                    $this->returnInf($inf);
                }

            }else{

                $codcli = Request::input('cod_cli');
                $inf_user = $user_api->getUser(null,$codcli,array('N'));

            }


            if(!empty(Config::get('app.envcorr')) && Config::get('app.envcorr') == 'S'){
                $envcorr ='S';
            }else{
                $envcorr ='N';
            }


            $nombre_pais = '';
            $pais = $enterprise_inf->getCountries(Request::input('pais_cli'));

            if(!empty($pais)){
                $nombre_pais = $pais->des_paises;
            }



            $user = new \stdClass;
            $user->cod_cli = $codcli;
            $user->cif_cli = $cif_cli;
            $user->nom_cli = strtoupper(Request::input('nom_cli'));
            $user->rsoc_cli = strtoupper(!empty(Request::input('rsoc_cli'))?Request::input('rsoc_cli'):Request::input('nom_cli'));
            $user->email_cli = Request::input('email_cli');
            $user->idioma_cli = Request::input('idioma_cli');
            $user->tel1_cli = Request::input('tel1_cli');
            $user->tel2cli = Request::input('tel2_cli');
            $user->pais_cli = $enterprise_inf->getCountries(Request::input('pais_cli'))->des_paises;
            $user->codpais_cli = Request::input('pais_cli');

            $user->sg_cli = Request::input('sg_cli');
            $user->dir_cli = strtoupper(mb_substr(Request::input('dir_cli'),0,30,'UTF-8'));
            $user->dir2_cli =  strtoupper(mb_substr(Request::input('dir_cli'),30,30,'UTF-8'));
            $user->cp_cli = Request::input('cp_cli');
            $user->pro_cli = !empty(Request::input('pro_cli'))?strtoupper(Request::input('pro_cli')):null;
            $user->pob_cli = strtoupper(Request::input('pob_cli'));
            $user->fpag_cli = !empty(Config::get('app.fpag_default'))?Config::get('app.fpag_default'):null;
            $user->sexo_cli = strtoupper(Request::input('sexo_cli'));
            $user->envecorr_cli = strtoupper($envcorr);
            $user->iva_cli = $this->cliente_tax(Request::input('pais_cli'),Request::input('cp_cli'));
            $user->fecnac_cli = !empty(Request::input('fecnac_cli'))?Request::input('fecnac_cli'):null;
            $user->fisjur_cli = !empty(Request::input('fisjur_cli'))?Request::input('fisjur_cli'):null;
            $user->baja_tmp_cli = !empty(Request::input('baja_tmp_cli'))?Request::input('baja_tmp_cli'):'N';
            $user->repres_cli = Request::input('repres_cli');


            if(empty($inf_user)){
                $newuser = $user_api->setNewUser($user);
                if(!empty(Request::input('file'))){
                    $this->saveImageDNI( Request::input('file'),$codcli);
                }
            }else{
                $newuser = $user_api->updateNewUser($user);
            }

            if($newuser){
                 $inf = array (
                        'status' => 'success',
                        'data' => array('msg_error' => "Client save.","cod_cli" => $codcli)
                    );
            }else{
                 $inf = array (
                        'status' => 'error',
                        'data' => array('msg_error' => "Problem save client.")
                    );
            }


        // } catch (\Exception $e) {

        //     $inf = array (
        //             'status' => 'error',
        //             'data' => array('msg_error' => "Problem save client.")
        //         );
        //     pritn_r($e);
        //    \Log::info('Erro set user'.$e);
        // }

        $this->returnInf($inf);
    }

    public function getUser(){

        $inf = array (
            'status' => 'success',
        );

        $user_api = new UserApiRest();
        $contract = new ContractApiRest();
        $enterprise = new EnterpriseApiRest();

        if(!empty(Request::input('cod_cli'))){
            $codcli = Request::input('cod_cli');
            $inf['user'] = $user_api->getUser(null,$codcli);
            $inf['contracts'] = $contract->getContract($codcli,null);
        }
        $inf['representative'] = $enterprise->getRepresentative();

        $this->returnInf($inf);

    }


    public function cliente_tax($pais,$cpostal){
       $iva_cli = 1;

       if(!empty(Config::get('app.all_tax_clients'))){
           return Config::get('app.all_tax_clients');
       }

       $paises = Localization::europeanUnionCountriesCodes();

       $canarias = array('38','35');
       $mel_ceu = array('51','52');

       if(($pais == 'AD') || ($pais == 'ES' && in_array(substr($cpostal,0,2),$canarias))){
          $iva_cli = 0;
       }elseif($pais == 'ES' && in_array(substr($cpostal,0,2),$mel_ceu)){
          $iva_cli = 6;
       }elseif(!in_array($pais, $paises)){
          $iva_cli = 4;
       }

       return $iva_cli;
    }

    public function saveImageDNI($img_temp,$codcli){
        $dest_path =   storage_path('cli/'.Config::get('app.gemp').'/DNI/');
        $img = json_decode($img_temp);
        foreach($img as $key => $imgSource){

            if($key == 0){
                $dniimg = 'DNIA';
            }else{
                $dniimg = 'DNIR';
            }
            $imageName = Config::get('app.gemp').$codcli.$dniimg.'.jpg';

            $this->saveImage($dest_path,$imageName,$imgSource);
        }
    }



}
