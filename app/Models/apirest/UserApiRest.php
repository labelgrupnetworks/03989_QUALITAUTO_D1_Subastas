<?php


namespace App\Models\apirest;
use Illuminate\Database\Eloquent\Model;
use Config;

use DB;


class UserApiRest extends ApiRest{
   
    public function getUserLogin($user,$password){

        
        $emp = Config::get('app.emp');

        return  DB::table('FSUSR')
                ->select('COD_USR,NOM_USR,PSW_USR')
                ->join('"sys_users"', function ($join){
                    $join->on('upper(cod_usr)', '=', 'upper("userName")');
                })
                ->where('BAJA_USR','N')
                ->where('upper(COD_USR)',strtoupper($user))
                ->where('PSW_USR',$password)
                ->first();
    }
    
    public function getAllusers($like){

        \Tools::linguisticSearch();
        
        $sql = DB::table('FXCLI')
        ->select('NOM_CLI,COD_CLI,COD_C_CLI,RSOC_CLI,SG_CLI,DIR_CLI,CP_CLI,POB_CLI,PRO_CLI ,CIF_CLI,TEL1_CLI,TEL2_CLI,F_ALTA_CLI,COMI_CLI,EMAIL_CLI,IDIOMA_CLI,TIPO_CLI','PAIS_CLI')
        ->where('GEMP_CLI',Config::get('app.gemp'));
        $sql = $this->generateFilter($sql,$like)
        ->orderBy('nom_cli')
        ->paginate(15);
        
        \Tools::normalSearch();
        return $sql;
    }
    
    public function getUser($nif = null,$cod_cli = null ,$baja = array('N','S')){

        $sql = DB::TABLE('FXCLI')
                ->where('GEMP_CLI',\Config::get('app.gemp'));
                if(!empty($nif)){
                    $sql->where('CIF_CLI',$nif);
                }
                if(!empty($cod_cli)){
                    $sql->where('COD_CLI',$cod_cli);
                }
   
                return $sql->whereIn('BAJA_TMP_CLI',$baja)
                ->first();

    }
    
    public function setNewUser($user){
            
            
        try {

            DB::select("INSERT INTO FXCLI 
                (GEMP_CLI, COD_CLI, COD_C_CLI, TIPO_CLI, RSOC_CLI, NOM_CLI,  DIR_CLI, DIR2_CLI, CP_CLI, POB_CLI, PRO_CLI, TEL1_CLI, BAJA_TMP_CLI, FPAG_CLI, EMAIL_CLI, CODPAIS_CLI, CIF_CLI, CNAE_CLI, PAIS_CLI, SEUDO_CLI, F_ALTA_CLI, SEXO_CLI, FECNAC_CLI,FISJUR_CLI, ENVCORR_CLI, IDIOMA_CLI,SG_CLI,TEL2_CLI,IVA_CLI,OBS_CLI,
                REPRES_CLI) 
                VALUES 
                (:gemp, :cod_cli, '4300', 'W', :rsoc, :usuario,  :direccion, :direccion2, :cpostal, :poblacion, :provincia, :telf, 'N', :forma_pago, :email, :pais, :dni, :trabajo, :nombrepais, :nombre_trabajo, :fecha_alta, :sexo_cli, :fecnac_cli, :pri_emp, :envcorr,:lang,:sg,:mobile,:ivacli,:obs,:represcli)",
                 array(
                    'gemp'          => Config::get('app.gemp'),
                    'email'         => $user->email_cli, 
                    'cod_cli'    => $user->cod_cli, 
                    'baja_tmp_cli'  => $user->baja_tmp_cli,                                       
                    'usuario'       => $user->nom_cli,
                    'rsoc'          => $user->rsoc_cli ,
                    'direccion'     => $user->nom_cli,
                    'direccion2'     => $user->nom_cli,
                    'cpostal'       => $user->cp_cli,
                    'poblacion'     => $user->pob_cli,
                    'provincia'     => $user->pro_cli,
                    'telf'          => $user->tel1_cli,
                    'mobile'          => $user->tel2cli,
                    'pais'          => $user->codpais_cli,
                    'dni'           => $user->cif_cli,
                    'trabajo'       => null,
                    'nombrepais'    => $user->pais_cli,
                    'nombre_trabajo'=> null,
                    'fecha_alta'    => date("Y-m-d H:i:s"),
                    'forma_pago'     => !empty(Config::get('app.fpag_default'))?Config::get('app.fpag_default'):0,
                    'sexo_cli' => $user->sexo_cli,
                    'fecnac_cli' => $user->fecnac_cli,
                    'pri_emp' =>$user->fisjur_cli,
                    'envcorr'       => $user->envecorr_cli,
                    'lang' => $user->idioma_cli,
                    'sg'  => $user->sg_cli,
                    'obs' => null,
                    'ivacli' => $user->iva_cli,
                    'represcli'=> $user->repres_cli
                    )
              );

            DB::select("INSERT INTO FXCLI2  (GEMP_CLI2, COD_CLI2, ENVCAT_CLI2)  VALUES  (:gemp, :cod_cli,'N')",
                     array('cod_cli'    => $user->cod_cli,
                           'gemp' => Config::get('app.gemp') )
                    );       
    
            
            return true;
        
        } catch (\Exception $e) {
            print_r($e);
            die();
            \Log::emergency('Insert cli APP'.print_r($e,true));
            return false;
        }
        
    }
    
    public function updateNewUser($user){
       try {
           
         $sql = "UPDATE FXCLI 
                SET RSOC_CLI = :rsoc, NOM_CLI = :usuario,DIR_CLI = :direccion, DIR2_CLI = :direccion2, CP_CLI = :cpostal,
                POB_CLI = :poblacion , PRO_CLI = :provincia, TEL1_CLI = :telf, TEL2_CLI = :mobile, FPAG_CLI = :forma_pago, EMAIL_CLI = :email,
                CODPAIS_CLI = :pais, CNAE_CLI = :trabajo, PAIS_CLI = :nombrepais, SEUDO_CLI = :nombre_trabajo, F_MODI_CLI = :fecha_modi, SEXO_CLI = :sexo_cli,
                FECNAC_CLI = :fecnac_cli, FISJUR_CLI = :pri_emp,  ENVCORR_CLI = :envcorr,  IDIOMA_CLI = :lang, SG_CLI = :sg, IVA_CLI = :ivacli, CIF_CLI = :dni,
                repres_cli = :represcli
                WHERE GEMP_CLI = :gemp AND COD_CLI = :cod_cli";
         
        $bindings =   array(
                    'gemp'          => Config::get('app.gemp'),
                    'email'         => $user->email_cli, 
                    'cod_cli'    => $user->cod_cli, 
                    'baja_tmp_cli'  => $user->baja_tmp_cli,                                       
                    'usuario'       => $user->nom_cli,
                    'rsoc'          => $user->rsoc_cli ,
                    'direccion'     => $user->nom_cli,
                    'direccion2'     => $user->nom_cli,
                    'cpostal'       => $user->cp_cli,
                    'poblacion'     => $user->pob_cli,
                    'provincia'     => $user->pro_cli,
                    'telf'          => $user->tel1_cli,
                    'mobile'          => $user->tel2cli,
                    'pais'          => $user->codpais_cli,
                    'dni'           => $user->cif_cli,
                    'trabajo'       => null,
                    'nombrepais'    => $user->pais_cli,
                    'nombre_trabajo'=> null,
                    'fecha_alta'    => date("Y-m-d H:i:s"),
                    'forma_pago'     => !empty(Config::get('app.fpag_default'))?Config::get('app.fpag_default'):0,
                    'sexo_cli' => $user->sexo_cli,
                    'fecnac_cli' => $user->fecnac_cli,
                    'pri_emp' =>$user->fisjur_cli,
                    'envcorr'       => $user->envecorr_cli,
                    'lang' => $user->idioma_cli,
                    'sg'  => $user->sg_cli,
                    'obs' => null,
                    'ivacli' => $user->iva_cli,
                    'represcli'=> $user->repres_cli
              );


         DB::select($sql, $bindings);
        } catch (\Exception $e) {
            \Log::emergency('Insert cli APP'.print_r($e,true));
            return false;
        }
    }
    
    public function getCodNewUser($longitud){
        
        $res = DB::select("SELECT NVL(MAX(CAST(COD_CLI AS Int)) + 1, 1) AS numero FROM FXCLI WHERE TRANSLATE(cod_cli, 'T 0123456789', 'T') IS NULL AND cod_cli IS NOT NULL and FXCLI.GEMP_CLI ='". Config::get('app.gemp')."' ");
        $num = $res['0']->numero;
        $num = str_pad($num, $longitud, 0, STR_PAD_LEFT);
        
        return $num;
    }
       
}
