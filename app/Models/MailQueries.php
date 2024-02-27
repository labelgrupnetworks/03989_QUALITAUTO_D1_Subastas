<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Amedida
 *
 * @author LABEL-RSANCHEZ
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailQueries {


    //seleccionamos los usuarios que tienen pujas en lotes que finalizan hoy o tiene esos lotes como favoritos
   public function lastCallLot($tipo_sub = 'P'){

       $where="WHERE SUB.EMP_SUB = :emp AND  SUB.TIPO_SUB = :tipo_sub AND SUB.SUBC_SUB = :subc_sub AND CERRADO_ASIGL0 = :cerrado AND TRUNC(FFIN_ASIGL0) = TRUNC(SYSDATE)";

       $sql="
            select ASIGL0.SUB_ASIGL0, ASIGL0.REF_ASIGL0, LICIT.CLI_LICIT AS COD_CLI,HFIN_ASIGL0  from FGSUB SUB
            JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 = EMP_SUB AND ASIGL0.SUB_ASIGL0 = SUB.COD_SUB
            JOIN FGASIGL1 ASIGL1 ON ASIGL1.EMP_ASIGL1 = EMP_SUB AND ASIGL1.SUB_ASIGL1 = SUB.COD_SUB AND ASIGL1.REF_ASIGL1 = ASIGL0.REF_ASIGL0
            JOIN FGLICIT LICIT ON LICIT.EMP_LICIT = EMP_SUB AND LICIT.SUB_LICIT = SUB.COD_SUB AND LICIT.COD_LICIT = ASIGL1.LICIT_ASIGL1
            $where

            UNION

            select ASIGL0.SUB_ASIGL0, ASIGL0.REF_ASIGL0, FAV.COD_CLI,HFIN_ASIGL0 from FGSUB SUB
            JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 = EMP_SUB AND ASIGL0.SUB_ASIGL0 = SUB.COD_SUB
            JOIN WEB_FAVORITES FAV ON   FAV.ID_EMP = SUB.EMP_SUB AND  FAV.ID_SUB = SUB.COD_SUB AND FAV.ID_REF = ASIGL0.REF_ASIGL0
            $where
            AND  COD_CLI IS NOT NULL
       ";

       $params = array(
                'emp'       =>  Config::get('app.emp'),
                'tipo_sub'  => $tipo_sub,
                'subc_sub'  => 'S',
                'cerrado'   => 'N'

                );

       $finish_lots = DB::select($sql, $params);
       $users = array();
       foreach($finish_lots as $lot){
           if(empty($users[$lot->cod_cli])){
               $users[$lot->cod_cli] = array();
           }
           if(empty($users[$lot->cod_cli][$lot->sub_asigl0])){
               $users[$lot->cod_cli][$lot->sub_asigl0] = array();
           }
           $users[$lot->cod_cli][$lot->sub_asigl0][$lot->ref_asigl0] = $lot->hfin_asigl0;
       }




       ksort($users);

       //insertar en setlastCAll
       foreach($users as $cod_cli=>$user){
           foreach ($user as $cod_sub =>$lote){
               foreach ($lote as $ref => $hora_fin ){
                   $this->setLastCall($cod_cli, $cod_sub, $ref, $hora_fin);
               }
           }
       }



   }

   public function setLastCall($cod_cli, $cod_sub, $ref, $hora_fin){


        $sql="MERGE INTO WEB_EMAIL_LAST_CALL lc
              USING (SELECT EMP_SUB, COD_SUB FROM FGSUB WHERE EMP_SUB = :emp and COD_SUB = :cod_sub) sub
              ON (lc.ID_EMP = sub.emp_sub and lc.COD_CLI = :cod_cli and lc.ID_SUB = sub.cod_sub and lc.ID_REF = :ref)
              WHEN NOT MATCHED THEN INSERT (ID_WEB_EMAIL_LAST_CALL, ID_EMP, COD_CLI, ID_SUB, ID_REF, HORA_FIN, FECHA_ENVIO, SENDED)
              VALUES (nvl((select max(ID_WEB_EMAIL_LAST_CALL)+1 from WEB_EMAIL_LAST_CALL),1)
               , :emp, :cod_cli, :cod_sub, :ref, :hora_fin, TRUNC(SYSDATE), 'N')
            ";

        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'cod_cli'  => $cod_cli,
                'cod_sub'  => $cod_sub,
                'ref'   => $ref,
                'hora_fin' => $hora_fin

                );

        DB::select($sql, $params);

   }

   public function getLastCall(){
        $sql = "select lc.id_web_email_last_call, cli.cod_cli, cli.nom_cli, cli.email_cli, cli.idioma_cli,  lc.hora_fin, asigl0.sub_asigl0, auc.\"name\", auc.\"id_auc_sessions\", asigl0.ref_asigl0, hces1.num_hces1, hces1.lin_hces1,
            NVL(hces1_lang.descweb_hces1_lang, hces1.descweb_hces1) descweb_hces1,   NVL(hces1_lang.WEBFRIEND_HCES1_LANG, hces1.WEBFRIEND_HCES1) WEBFRIEND_HCES1 ,NVL(hces1_lang.TITULO_HCES1_LANG,  hces1.TITULO_HCES1) TITULO_HCES1,hces1.implic_hces1,asigl0.impsalhces_asigl0
            from web_email_last_call lc
            join fxcli cli on cli.gemp_cli = :gemp and cli.cod_cli =  lc.cod_cli
            join \"auc_sessions\" auc on auc.\"company\" = lc.id_emp and auc.\"auction\" = lc.id_sub
            join fgasigl0 asigl0 on asigl0.emp_asigl0 = lc.id_emp and  asigl0.sub_asigl0 = lc.id_sub and asigl0.ref_asigl0 = lc.id_ref
            join fghces1 hces1 on hces1.emp_hces1 = lc.id_emp and hces1.num_hces1= asigl0.numhces_asigl0 and hces1.lin_hces1 = asigl0.linhces_asigl0
            left join fghces1_lang hces1_lang on hces1_lang.emp_hces1_lang = lc.id_emp and hces1_lang.num_hces1_lang= asigl0.numhces_asigl0 and hces1_lang.lin_hces1_lang = asigl0.linhces_asigl0 and SUBSTR(hces1_lang.lang_hces1_lang,0,2) = lower(cli.idioma_cli)
            where asigl0.cerrado_asigl0 = :cerrado and lc.sended = :sended and lc.id_emp = :emp
            order by lc.cod_cli, lc.hora_fin,lc.id_sub";


         $params = array(
                'emp'       =>  Config::get('app.emp'),
                'gemp'       =>  Config::get('app.gemp'),
                'cerrado'  => 'N',
                'sended'  => 'N',
                );
        $last_call = DB::select($sql, $params);
        $users = array();
        foreach($last_call as $lot){
           if(empty($users[$lot->cod_cli])){
               $users[$lot->cod_cli] = array();
           }
           $users[$lot->cod_cli][] = $lot;
        }

        return ($users);

   }

   public function sendedLastCall($ids_web_email_last_call,$sended = 'S'){
       $where = "( ";
       foreach($ids_web_email_last_call as $key => $id){
           $ids_web_email_last_call[$key] = " id_web_email_last_call = $id";
       }
        $where.= implode(" OR ", $ids_web_email_last_call);
        $where .= " )";
        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'new_sended' => $sended
                );
        $sql = "update web_email_last_call set sended = :new_sended where id_emp = :emp and sended = 'N' and $where ";

        DB::select($sql, $params);

   }

   public function getFirstAuction(){
        $sql="select ASIGL0.SUB_ASIGL0, ASIGL0.REF_ASIGL0 from FGSUB SUB
                JOIN FGASIGL0 ASIGL0 ON ASIGL0.EMP_ASIGL0 = SUB.EMP_SUB AND ASIGL0.SUB_ASIGL0 = SUB.COD_SUB
                JOIN FGHCES1 HCES1 ON HCES1.EMP_HCES1 = SUB.EMP_SUB AND HCES1.NUM_HCES1 = ASIGL0.NUMHCES_ASIGL0 AND HCES1.LIN_HCES1 = ASIGL0.LINHCES_ASIGL0
                WHERE SUB.EMP_SUB = :emp AND SUB.TIPO_SUB = :tipo_sub AND SUB.SUBC_SUB = :subc_sub AND CERRADO_ASIGL0 = :cerrado AND TRUNC(FINI_ASIGL0) = TRUNC(SYSDATE) AND HCES1.FAC_HCES1 != 'D' AND HCES1.FAC_HCES1 != 'R' ";

                 $params = array(
                'emp'       =>  Config::get('app.emp'),
                'subc_sub'  => 'S',
                'cerrado'   => 'N',
                'tipo_sub'  => 'P'

                );

       return DB::select($sql, $params);

   }

   /**
	* @deprecated No se esta utilizando, ahora mismo se insertan los datos desde ERP al añadir el
	* Lote a la primera subasta.
    */
    public function setFirstAuction( $cod_sub, $ref){
          //quieren que se puedan enviar emails aunque se repita el lote y la subasta
       /*
        $sql="MERGE INTO WEB_EMAIL_FIRST_AUCTION fa
              USING (SELECT EMP_SUB, COD_SUB FROM FGSUB WHERE EMP_SUB = :emp and COD_SUB = :cod_sub) sub
              ON (fa.ID_EMP = sub.emp_sub  and fa.ID_SUB = sub.cod_sub and fa.ID_REF = :ref)
              WHEN NOT MATCHED THEN
            ";
        */
        $sql=" INSERT INTO WEB_EMAIL_FIRST_AUCTION (ID_WEB_EMAIL_FIRST_AUCTION , ID_EMP, ID_SUB, ID_REF,  FECHA_ENVIO, SENDED)
              VALUES (nvl((select max(ID_WEB_EMAIL_FIRST_AUCTION )+1 from WEB_EMAIL_FIRST_AUCTION ),1)
               , :emp, :cod_sub, :ref, TRUNC(SYSDATE), 'N')";

        $params = array(
                'emp'       =>  Config::get('app.emp'),
                'cod_sub'  => $cod_sub,
                'ref'   => $ref
                );

        DB::select($sql, $params);

   }


	public function setEmailLogs($codtxt, $sub, $ref, $numhces, $linhces, $codcli, $email, $type, $sended = 'S')
	{
		// si existe la variable debug_email es la que manda, si no se usará APP_DEBUG
		$isDebugMode = Config::get('mail.debug_email') ?: Config::get('app.debug');
		if ($isDebugMode) {
			return false;
		}

		try {
			$sql = "INSERT INTO WEB_EMAIL_LOGS (ID_EMAIL_LOGS, EMP_EMAIL_LOGS, CODTXT_EMAIL_LOGS, SUB_EMAIL_LOGS, REF_EMAIL_LOGS, NUMHCES_EMAIL_LOGS, LINHCES_EMAIL_LOGS, CODCLI_EMAIL_LOGS, EMAIL_EMAIL_LOGS, TYPE_EMAIL_LOGS,DATE_EMAIL_LOGS,SENDED_EMAIL_LOGS)
                    VALUES ((select nvl(max(id_email_logs)+1,1) from web_email_logs), :emp, :codtxt, :sub, :ref, :numhces, :linhces, :codcli, :email, :type, sysdate, :sended)";

			if (is_string($email)) {
				$email = trim($email);
			}

			$params = array(
				'emp'       =>  Config::get('app.emp'),
				'codtxt' => $codtxt,
				'sub' => $sub,
				'ref' => $ref,
				'numhces' => $numhces,
				'linhces' => $linhces,
				'codcli' => $codcli,
				'email' => $email,
				'type' => $type,
				'sended' => $sended
			);

			DB::select($sql, $params);

			return true;

		} catch (\Exception $e) {
			Log::error('Error al insertar en WEB_EMAIL_LOGS: ', ['error' => $e->getMessage()]);
			return false;
		}
	}

   public function updateWebEmailCloslot($emp,$cod_sub,$ref,$type = 'S'){
        DB::table('WEB_EMAIL_CLOSLOT')
               ->where('ID_EMP',$emp)
               ->where('ID_SUB',$cod_sub)
               ->where('ID_REF',$ref)
               ->update(['SENDED' => $type]);
   }

   public function updateWebEmailCloseAuction($emp, $cod_sub, $cod_email, $sended){
	DB::table('WEB_EMAIL_CLOSAUCTION')
			->where([
				['ID_EMP', $emp],
				['ID_SUB', $cod_sub],
				['ID_EMAIL', $cod_email],
			])
		   ->update(['SENDED' => $sended]);
}

   public function updateSendEmailNoAdjudicado($emp,$cod_sub,$ref,$type = 'S'){
         DB::table('WEB_EMAIL_MOVELOT')
                                    ->where('ID_EMP',$emp)
                                    ->where('ID_SUB',$cod_sub)
                                    ->where('ID_REF',$ref)
                                    ->update(['SENDED' => $type]);
   }

   public function updateReSaleLot($id_emp,$id_sub,$id_ref,$id_last_sub,$id_last_ref,$type = 'S'){
         DB::table('WEB_EMAIL_RESALELOT')
                ->where('ID_EMP',$id_emp)
                ->where('ID_SUB',$id_sub)
                ->where('ID_REF',$id_ref)
                ->where('ID_LAST_SUB',$id_last_sub)
                ->where('ID_LAST_REF',$id_last_ref)
                ->update(['SENDED' => $type]);

   }

   public function updateWebEmailFirstAuction($emp,$cod_sub,$ref,$type = 'S'){
        DB::table('WEB_EMAIL_FIRST_AUCTION')
               ->where('ID_EMP',$emp)
               ->where('ID_SUB',$cod_sub)
               ->where('ID_REF',$ref)
               ->update(['SENDED' => $type]);
   }

   public function getEmailsLogs($date , $num_days = 7 ){


        $dates = '';

        $nuevafecha  = strtotime ( '-'.$num_days.' day' , strtotime ( $date ) ) ;
        $nuevafecha  = date ( 'Y/m/j' , $nuevafecha );
        $dates.= " trunc(DATE_EMAIL_LOGS) >= '$nuevafecha' AND  trunc(DATE_EMAIL_LOGS) <= '$date'";

       $sql = "Select CODTXT_EMAIL_LOGS, COUNT(CODTXT_EMAIL_LOGS) count_emails, TRUNC(DATE_EMAIL_LOGS) date_emails
        from WEB_EMAIL_LOGS
        WHERE
        $dates
        GROUP BY CODTXT_EMAIL_LOGS, TRUNC(DATE_EMAIL_LOGS) ORDER BY TRUNC(DATE_EMAIL_LOGS) desc";

      $value = DB::select($sql);

      return $value;
   }

   public function getTxtcod(){
       $sql = "Select cod_txtcod, des_txtcod from fstxtcod where cod_txtcod = 'W'";
       $params = array(
                );
       return DB::select($sql, $params);
   }

   public function redy_collect($date){

       $gemp = Config::get('app.gemp');

       $value = DB::Table('FGCSUB')
                ->select('FGCSUB.*')
                ->addSelect('FXCLI.nom_cli,FXCLI.email_cli,FXCLI.idioma_cli,FXCLI.cod_cli')
                ->addSelect('FXCOBRO1.VTO_COBRO1')
                ->Join('FXCLI', function ($join) use($gemp){
                    $join->on('FGCSUB.clifac_csub', '=', 'FXCLI.cod_cli')
                    ->on('FXCLI.gemp_cli', '=', $gemp);
                })
                ->Join('FGASIGL0',function($join){
                    $join->on('FGASIGL0.EMP_ASIGL0','=','FGCSUB.EMP_CSUB')
                    ->on('FGASIGL0.SUB_ASIGL0','=','FGCSUB.SUB_CSUB')
                    ->on('FGASIGL0.REF_ASIGL0','=','FGCSUB.REF_CSUB ');
                })
                ->Join('FGHCES1',function($join){
                    $join->on('FGHCES1.EMP_HCES1','=','FGCSUB.EMP_CSUB')
                    ->on('FGHCES1.SUB_HCES1','=','FGCSUB.SUB_CSUB')
                    ->on('FGHCES1.LIN_HCES1','=','FGASIGL0.LINHCES_ASIGL0')
                    ->on('FGHCES1.NUM_HCES1','=','FGASIGL0.NUMHCES_ASIGL0')
                    ->where('substr(FGHCES1.ALM_HCES1,0,1)','!=','9');
                })
                ->Join('FGCSUB0 FGC0',function($join){
                    $join->on('FGC0.EMP_CSUB0','=','FGCSUB.EMP_CSUB')
                    ->on('FGC0.APRE_CSUB0','=','FGCSUB.APRE_CSUB')
                    ->on('FGC0.NPRE_CSUB0','=','FGCSUB.NPRE_CSUB');
                })
                 ->Join('FXCOBRO1', function ($join) use($date) {
                    $join->on('FGCSUB.EMP_CSUB', '=', 'FXCOBRO1.EMP_COBRO1')
                    ->on('FGCSUB.AFRAL_CSUB', '=', 'FXCOBRO1.AFRA_COBRO1')
                    ->on('FGCSUB.NFRAL_CSUB', '=', 'FXCOBRO1.NFRA_COBRO1')
                    ->where('FGCSUB.FAC_CSUB','=','S')
                    ->where('FXCOBRO1.VTO_COBRO1','=',$date);
                })
                ->whereIn('FGCSUB.OPENV_CSUB',[1,2,3])
                ->where('EMP_CSUB',Config::get('app.emp'))
                ->whereIn('FGC0.ESTADO_CSUB0',['S','F'])
                ->get();
       return $value;

   }

   public function return_lot_cedente($date){
      $gemp = Config::get('app.gemp');
      return DB::Table('FGHCES1')
             ->select('FAC_HCES1,FECDEV_HCES1,PROP_HCES1,ALM_HCES1')
             ->addSelect('REF_HCES1,SUB_HCES1,NUM_HCES1,LIN_HCES1,PROP_HCES1')
              ->addSelect('FXCLI.NOM_CLI,FXCLI.EMAIL_CLI,FXCLI.IDIOMA_CLI')
              ->Join('FXCLI', function ($join) use($gemp){
                  $join->on('FGHCES1.prop_hces1', '=', 'FXCLI.cod_cli')
                  ->on('FXCLI.gemp_cli', '=', $gemp);
              })
             ->where('TRUNC(FECDEV_HCES1)',$date)
             ->where('FAC_HCES1','R')
             ->where('EMP_HCES1',Config::get('app.emp'))
             ->get();
   }

   public function pending_license_export(){
        $gemp = Config::get('app.gemp');
      return DB::Table('FGHCES1')
             ->select('FAC_HCES1,FECDEV_HCES1,PROP_HCES1,ALM_HCES1')
             ->addSelect('REF_HCES1,SUB_HCES1,NUM_HCES1,LIN_HCES1')
             ->where('SITUA_HCES1','LVPLE')
             ->where('EMP_HCES1',Config::get('app.emp'))
             ->get();
   }

   public function getUserDontBidder($date){

       return DB::table('FXCLIWEB')
              ->select('cod_cliweb,nom_cliweb,idioma_cliweb,email_cliweb,fecalta_cliweb')
              ->where('gemp_cliweb',Config::get('app.gemp'))
              ->where('emp_cliweb',Config::get('app.emp'))
              ->where('TRUNC(fecalta_cliweb)',$date)
              ->where('cod_cliweb','!=','0')
              ->whereNotNull('email_cliweb')
              ->get();
   }
}
