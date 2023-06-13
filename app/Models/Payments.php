<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;
use App\Http\Controllers\PaymentsController;
use App\Models\V5\FgAsigl2;
use Illuminate\Support\Collection;

class Payments extends Model
{


    public function factura($anum, $num, $emp)
    {
        $data = DB::select("select f.cod_dvc0, f.rsoc_dvc0 as email,
            f.base_dvc0, f.total_dvc0, f.impiva_dvc0
            from
            fxdvc0 f
            INNER JOIN fxcli ON f.cod_dv0=fxcli.cod_cli
            where f.anum_dvc0=:ANUM and f.num_dvc0=:NUM and f.emp_dvc0=:EMP",
                array(
                        'ANUM'   => $anum,
                        'NUM'       =>$num,
                        'EMP' =>$emp,
                    )
                );




        return $data;
    }

    public function getFGCSUB0($emp,$num,$anum,$tk = null){

        if(!empty($tk)){
            $where_tk = " and fgcsub0.tk_csub0 = :tk";
                    $params =   array(
                        'ANUM'   => $anum,
                        'NUM'       =>$num,
                        'EMP' =>$emp,
                        'tk'=>$tk,
                    );
        }else{
                    $params =   array(
                        'ANUM'   => $anum,
                        'NUM'       =>$num,
                        'EMP' =>$emp,
                    );
                    $where_tk=' ';
        }

        $data =  "Select (fgcsub0.IMP_CSUB0+fgcsub0.IMPGAS_CSUB0 ) as IMP_CSUB0,fgcsub0.IMPCOB_CSUB0,fgcsub0.IMPGAS_CSUB0,fgcsub0.IMPEXTRA_CSUB0,
                    fgcsub0.TAX_CSUB0, fxcli.nom_cli,fgcsub0.cli_csub0, (fgcsub0.IMP_CSUB0+fgcsub0.IMPGAS_CSUB0+fgcsub0.TAX_CSUB0 + nvl(fgcsub0.EXP_CSUB0,0) + nvl(fgcsub0.IMPEXTRA_CSUB0,0)  ) IMPTOTAL
                    ,fgcsub0.estado_csub0, fxcli.email_cli, fxcli.cp_cli,fxcli.codpais_cli,fxcli.idioma_cli,fxcli.cod_cli,fac_csub,sub_csub
                    from fgcsub0
                    INNER JOIN fsempres ON fgcsub0.emp_csub0=fsempres.cod_emp
                    INNER JOIN fxcli ON fsempres.gemp_emp=fxcli.gemp_cli
                    INNER JOIN fgcsub ON fgcsub0.emp_csub0=fgcsub.emp_csub and fgcsub.apre_csub =fgcsub0.apre_csub0 and fgcsub.npre_csub =fgcsub0.npre_csub0
                    where fxcli.COD_CLI = fgcsub0.cli_csub0
                    and fgcsub0.apre_csub0 =:ANUM
                    and fgcsub0.npre_csub0=:NUM
					and fgcsub0.emp_csub0 =:EMP

                    $where_tk "
                ;

        $data = DB::select($data,$params);

        return $data;

    }

    public function updateTransaction($anum,$num,$emp,$ordenTrans){
       DB::table('fgcsub0')
          ->where('apre_csub0',$anum)
            ->where('npre_csub0',$num)
            ->where('emp_csub0',$emp)
          ->update(['idtrans_csub0' => $ordenTrans]);

    }

    public  function insertFactura($anum,$num,$emp,$cobrado_web,$ordenTrans,$cod_dvc0,$rsoc_dvc0){

        $max_lincobweb = DB::table('FXCOBWEB')->max('LIN_COBWEB');
        $max_lincobweb++;

        DB::select("INSERT INTO FXCOBWEB (EMP_COBWEB, ANUM_COBWEB, NUM_COBWEB, LIN_COBWEB, CLI_COBWEB,RSOC_COBWEB,COBRADO_COBWEB,LIQUI_COBWEB,FECCOB_COBWEB,IDTRANS_COBWEB) VALUES (:emp,:anum, :num, :max_lincobweb,:cod_dvc0,:rsoc_dvc0,:cobrado_web,:liqui_cobweb,SYSDATE,:trans)",
                    array(
                        'anum'   => $anum,
                        'emp'       => $emp,
                        'num' => $num,
                        'cod_dvc0' => $cod_dvc0,
                        'max_lincobweb' => $max_lincobweb,
                        'rsoc_dvc0' => $rsoc_dvc0,
                        'cobrado_web' => '0',
                        'liqui_cobweb' => 'S',
                        'trans' => $ordenTrans

                        )
                );
    }

    /*public function okPrefact($trans){


        $objeto = json_decode($trans["objects"]);

        DB::select("Update fgcsub0 Set estado_csub0=:estado, impcob_csub0=:importe Where idtrans_csub0=:transaccion ",
                    array(
                        'estado'   => "C",
                        'importe'       => $objeto->order->total,
                        'transaccion' => $trans["merchant_order_id"]
                        )
                );

        $factura = explode("/",$objeto->order->items[0]->sku);
        $max_lincobweb = DB::table('fgcsub0h')->max('LIN_CSUB0H');
        $max_lincobweb++;

        //$empresa = DB::table('fgcsub0')->select('EMP_CSUB0')->where('idtrans_csub0',$trans["merchant_order_id"])->first();
        $empresa = DB::select("select EMP_CSUB0 from fgcsub0 where idtrans_csub0 = :id_trans",
                    array(
                        'id_trans' => $trans["merchant_order_id"]
                        )
                );

        \Log::info("Empresa ".print_r($trans,true));


        DB::select("INSERT INTO fgcsub0h (EMP_CSUB0H, APRE_CSUB0H, NPRE_CSUB0H, LIN_CSUB0H, IMP_CSUB0H,FECHA_CSUB0H,OBS_CSUB0H) "
                . "VALUES (:emp,:anum, :num, :max_lincobweb,:importe,SYSDATE,:observaciones)",
                    array(
                        'anum'   => $factura[0],
                        'emp'       => $empresa[0]->emp_csub0,
                        'num' => $factura[1],
                        'max_lincobweb' => $max_lincobweb,
                        'importe' => $objeto->order->total,
                        'observaciones' => json_encode ($trans)
                        )
                );
    }*/

    public function okPrefact($trans){

        $pay_cont = new PaymentsController();
        $objeto = json_decode($trans["objects"]);


        $max_lincobweb = DB::table('fgcsub0h')->max('LIN_CSUB0H');
        $max_lincobweb++;

        $fact = head(DB::select("select * from fgcsub0_ext where idtrans_csub0ext = :id_trans",
                    array(
                        'id_trans' => $trans["merchant_order_id"]
                        )
                ));
         DB::select("Update fgcsub0 Set estado_csub0=:estado, impcob_csub0=:importe, idtrans_csub0=:transaccion Where APRE_CSUB0=:apre and NPRE_CSUB0=:npre and EMP_CSUB0 = :emp ",
                    array(
                        'estado'   => "C",
                        'importe'       => $objeto->order->total,
                        'transaccion' => $trans["merchant_order_id"],
                        'apre'  => $fact->apre_csub0ext,
                        'npre'  => $fact->npre_csub0ext,
                        'emp'   => $fact->emp_csub0ext,
                        )
                );
        try {
            DB::select("INSERT INTO fgcsub0h (EMP_CSUB0H, APRE_CSUB0H, NPRE_CSUB0H, LIN_CSUB0H, IMP_CSUB0H,FECHA_CSUB0H,OBS_CSUB0H) "
                . "VALUES (:emp,:anum, :num, :max_lincobweb,:importe,SYSDATE,:observaciones)",
                    array(
                        'anum'   => $fact->apre_csub0ext,
                        'emp'       => $fact->emp_csub0ext,
                        'num' => $fact->npre_csub0ext,
                        'max_lincobweb' => $max_lincobweb,
                        'importe' => $objeto->order->total,
                        'observaciones' => json_encode ($trans)
                        )
                );
        } catch (\Exception $e) {
            $pay_cont->error_email($e);
        }

        return $fact;

    }

    public function koPrefact($trans){

        $objeto = json_decode($trans["objects"]);
        $factura = explode("/",$objeto->order->items[0]->sku);
        $max_lincobweb = DB::table('fgcsub0h')->max('LIN_CSUB0H');
        $max_lincobweb++;

         $empresa = DB::select("select EMP_CSUB0 from fgcsub0 where idtrans_csub0 = :id_trans",
                    array(
                        'id_trans' => $trans["merchant_order_id"]
                        )
                );

        DB::select("INSERT INTO fgcsub0h (EMP_CSUB0H, APRE_CSUB0H, NPRE_CSUB0H, LIN_CSUB0H, IMP_CSUB0H,FECHA_CSUB0H,OBS_CSUB0H) "
                . "VALUES (:emp,:anum, :num, :max_lincobweb,:importe,SYSDATE,:observaciones)",
                    array(
                        'anum'   => $factura[0],
                        'emp'       => $empresa[0]->emp_csub0,
                        'num' => $factura[1],
                        'max_lincobweb' => $max_lincobweb,
                        'importe' => $objeto->order->total,
                        'observaciones' => json_encode ($trans)
                        )
                );


    }


    public function okFact($trans){
           DB::table('fxcobweb')
           ->where('idtrans_cobweb',$trans["merchant_order_id"])
           ->update(['LIQUI_COBWEB' => 'N']);

    }

    public function koFact($trans){

    }


    public function getPrice($lot,$sub,$emp,$user_cod){

         $bindings = array(
            'emp'           => Config::get('app.emp'),
            'clifac'     => $user_cod,
            'lot'  =>$lot,
            'subasta'  => $sub
            );

        $sql="SELECT C.HIMP_CSUB,C.BASE_CSUB FROM    FGCSUB C
              WHERE C.EMP_CSUB =:emp and C.SUB_CSUB = :subasta and C.CLIFAC_CSUB =:clifac and C.REF_CSUB = :lot ";

        return DB::select($sql, $bindings);
    }

    public function updateApreNpre($lot,$sub,$apre,$npre,$emp){

        DB::table('fgcsub')
           ->where('sub_csub',$sub)
           ->where('ref_csub',$lot)
           ->where('emp_csub',$emp)
           ->update(['apre_csub' => $apre,'npre_csub' => $npre,'prefac_csub'=>'S']);
    }

    public function insertPreFactura($emp,$apre,$npre,$user_cod,$precio,$envio,$tax,$token,$jsonLot,$exp_csub0, $imp_extra = 0){
        DB::select("INSERT INTO fgcsub0 (EMP_CSUB0, APRE_CSUB0, NPRE_CSUB0,FECHA_CSUB0,USR_CSUB0,CLI_CSUB0,ESTADO_CSUB0,IMP_CSUB0,IMPGAS_CSUB0,TAX_CSUB0,TK_CSUB0,EXTRAINF_CSUB0,EXP_CSUB0, IMPEXTRA_CSUB0) "
                . "VALUES (:emp,:apre, :nepre,SYSDATE,:user_csub, :cli, :estado, :precio, :imp_gas, :tax,:tk,:inf,:exp_csub0, :imp_extra)",
                    array(
                        'emp'   => $emp,
                        'apre'       => $apre,
                        'nepre' => $npre,
                        'user_csub' => 'WEB',
                        'cli' => $user_cod,
                        'estado' => 'N',
                        'precio' => $precio,
                        'imp_gas' => $envio,
                        'tax' => $tax,
                        'tk'=>$token,
						'inf'=>$jsonLot,
						'exp_csub0' => $exp_csub0,
						'imp_extra' => round($imp_extra,2)
                        )
                );
    }

    public function getTAX($gemp,$user_cod){

        return $tax = DB::select( "select codpais_cli, iva_cli from fxcli where gemp_cli = :gemp and cod_cli = :cod_cli",
                    array(
                        'cod_cli'       => $user_cod,
                        'gemp'       => $gemp
                        )
                );
    }

    public function ErrorPaymentsSub($apre,$npre,$emp,$gemp){
        DB::table('fgcsub')
           ->where('apre_csub',$apre)
           ->where('npre_csub',$npre)
           ->where('emp_csub',$emp)
           ->update(['apre_csub' => NULL,'npre_csub' => NULL,'fac_csub'=>'N']);
    }

    public function  ErrorPaymentsSub0($apre,$npre,$emp,$gemp){
     DB::table('fgcsub0')
           ->where('apre_csub0',$apre)
           ->where('npre_csub0',$npre)
           ->where('emp_csub0',$emp)
           ->delete();
    }

    public function Client($id_trans){
        return DB::select( "Select FXCLI.NOM_CLI, FXCLI.DIR_CLI, FXCLI.CP_CLI, FXCLI.POB_CLI, FXCLI.PAIS_CLI, FXCLI.CODPAIS_CLI,FXCLI.COD_CLI, FXCLI.PRO_CLI from FGCSUB0"
                . " INNER JOIN FXCLI ON FGCSUB0.CLI_CSUB0=FXCLI.COD_CLI"
                . " where IDTRANS_CSUB0 = :id_trans and EMP_CSUB0 = :emp and ESTADO_CSUB0 = 'C'",
                    array(
                        'id_trans'       => $id_trans,
                        'emp'       => Config::get('app.emp')
                        )
                );
    }

    public function getIVA($date,$cod_iva){
         return DB::select( "select * from fsiva where dfec_iva <= :time and hfec_iva >= :time and cod_iva = :cod",
                    array(
                        'time'       => $date,
                        'cod'   => $cod_iva
                        )
                );
    }

        public function getIVACOD($cod_iva){
         return DB::select( "select * from fsiva where cod_iva = :cod",
                    array(
                        'cod'   => $cod_iva
                        )
                );
    }

     public function getPrmgt($emp){

         return DB::select( "select tiva_prmgt from fxprmgt where emp_prmgt = :gemp and cla_prmgt = :cla",
                    array(
                        'gemp'       => $emp,
                        'cla'   => '1'
                        )
                );
    }

    public function newCSUB0_EXT($emp,$anum,$num,$ordenTrans,$fechaactual){
        DB::table('FGCSUB0_EXT')->insert([
            ['EMP_CSUB0EXT' => $emp, 'APRE_CSUB0EXT' => $anum,'NPRE_CSUB0EXT'=>$num, 'IDTRANS_CSUB0EXT' => $ordenTrans, 'FECHA_CSUB0EXT' => $fechaactual]
        ]);
    }

    public function updateRequest($fields,$ordenTrans,$emp){
         DB::table('FGCSUB0_EXT')
           ->where('IDTRANS_CSUB0EXT',$ordenTrans)
           ->where('EMP_CSUB0EXT',$emp)
           ->update(['RESQUEST_CSUB0EXT' => $fields]);
    }

    public function updateReturn($return,$ordenTrans,$emp){
         DB::table('FGCSUB0_EXT')
           ->where('IDTRANS_CSUB0EXT',$ordenTrans)
           ->where('EMP_CSUB0EXT',$emp)
           ->update(['RETURN_CSUB0EXT' => $return]);
    }





   public function gastos_envio($tipo,$emp){

        return DB::select( "Select imp_gasimp FROM fxgasimp where emp_gasimp = :emp and tipo_gasimp = :tipo",
                    array(
                        'tipo'       => $tipo,
                        'emp'       => $emp
                        )
                );
   }

   public function gastos_imp($tipo,$emp,$cantidad){

       $value = DB::select("SELECT MAX(dimp_gasimp) dimp FROM fxgasimp WHERE emp_gasimp = :emp and tipo_gasimp = :tipo "
                . "and dimp_gasimp <= :cantidad",
               array(
                        'tipo'       => $tipo,
                        'emp'       => $emp,
                        'cantidad'  => $cantidad,
                        ));

       if(!empty($value)){
           $value =  head($value)->dimp;
       }else{
           $value =  0;
       }

        return DB::select( "Select imp_gasimp FROM fxgasimp where emp_gasimp = :emp and tipo_gasimp = :tipo "
                . "and dimp_gasimp = :value",
                    array(
                        'tipo'       => $tipo,
                        'emp'       => $emp,
                        'value' => $value,
                        )
                );
   }

   public function maxLin(){
        $max_lincobweb = DB::table('fgcsub0h')->max('LIN_CSUB0H');
        $max_lincobweb++;
        return $max_lincobweb;
   }

   public function getInfTransExt($trans){
       $fact = head(DB::select("select * from fgcsub0_ext where idtrans_csub0ext = :id_trans",
                    array(
                        'id_trans' => $trans
                        )
                ));
       return $fact;
   }

   public function updateTrans($amount,$customerid,$fact){

       DB::select("Update fgcsub0 Set estado_csub0=:estado, impcob_csub0=:importe, idtrans_csub0=:transaccion Where APRE_CSUB0=:apre and NPRE_CSUB0=:npre and EMP_CSUB0 = :emp ",
                    array(
                        'estado'   => "C",
                        'importe'       => $amount,
                        'transaccion' => $customerid,
                        'apre'  => $fact->apre_csub0ext,
                        'npre'  => $fact->npre_csub0ext,
                        'emp'   => $fact->emp_csub0ext,
                        )
                );
   }

   public function insertHistTrans($amount,$fact,$post,$max_lin){

       DB::select("INSERT INTO fgcsub0h (EMP_CSUB0H, APRE_CSUB0H, NPRE_CSUB0H, LIN_CSUB0H, IMP_CSUB0H,FECHA_CSUB0H,OBS_CSUB0H) "
                . "VALUES (:emp,:anum, :num, :max_lincobweb,:importe,SYSDATE,:observaciones)",
                    array(
                        'anum'   => $fact->apre_csub0ext,
                        'emp'       => $fact->emp_csub0ext,
                        'num' => $fact->npre_csub0ext,
                        'max_lincobweb' => $max_lin,
                        'importe' => $amount,
                        'observaciones' => json_encode ($post)
                        )
                );
   }

   public function existGasimp($tipo_iva){
       return DB::table('fxgasimp')
               ->select('imp_gasimp')
               ->where('emp_gasimp',Config::get('app.emp'))
			   //puede existir un valor D (default)
			   ->where([
					['tipo_gasimp', '=', $tipo_iva->tipo],
					['tipo_gasimp', '=', 'D', 'or']
				])
               ->where('pais_gasimp',$tipo_iva->pais)
               ->first();

   }

   public function getGastoEnvio($emp, $imp, $tipoIva, $codPais, $cp, $min = false) {

        $emp = is_null($emp)? '' : $emp;
        $imp = is_null($imp)? '' : $imp;
        $tipoIva = is_null($tipoIva)? '' : $tipoIva;
        $codPais = is_null($codPais)? '' : $codPais;
        $cp = is_null($cp)? '' : $cp;
		#existen dos funciones, la que se usaba hasta ahora y la min
		if($min){
			$function = "CALCULAR_GASTOS_ENVIO_MIN";
		}else{
			$function = "CALCULAR_GASTOS_ENVIO";
		}

        $a=DB::select("select $function(:empresa,:imp,:tipoIva,:codPais,:cp ) as imp_gasimp from dual",
        array(
            'empresa'    => $emp,
            'imp'        => $imp,
            'tipoIva'    => $tipoIva,
            'codPais'    => $codPais,
            'cp'         => $cp
            )
        );
       return $a;
    }

    /*

    OLD DE ESTA FUNCION - 12-09-2019

    public function getGastoEnvio($pais,$tipo,$base_himp){

       $sql = "SELECT imp_gasimp FROM fxgasimp
            WHERE emp_gasimp = :emp
            AND tipo_gasimp = :tipo
            AND pais_gasimp = :pais
            AND dimp_gasimp = (SELECT MAX(dimp_gasimp) FROM fxgasimp
            WHERE emp_gasimp = :emp
            AND tipo_gasimp = :tipo
            AND dimp_gasimp <= :cantidad
            AND pais_gasimp = :pais)";


       $binding =  array(
                        'tipo'   => $tipo,
                        'pais'  => $pais,
                        'cantidad'       => $base_himp,
                        'emp' => Config::get('app.emp')
                        );
       $result = DB::Select($sql,$binding);
        return $result;


   }
*/

    public function infEnvExp($inf_env_lic){

         DB::select("Update FGCSUB Set openv_csub = :openv, infoenv_csub = :infenv, licexp_csub = :liceexp, tasas_csub = :tasas,fecharec_csub = :daterec "
                 . "Where sub_csub = :sub and ref_csub = :ref and emp_csub = :emp ",
             array(
                 'openv'   => $inf_env_lic->openv,
                 'infenv'       => $inf_env_lic->infenv,
                 'liceexp' =>  $inf_env_lic->liceexp,
                 'tasas' => $inf_env_lic->tasas,
                 'daterec' => $inf_env_lic->fecharec,
                 'sub'  => $inf_env_lic->sub,
                 'ref'  => $inf_env_lic->ref,
                 'emp'   => $inf_env_lic->emp,
                 )
         );
    }

    public function deleteAsigl2($ref,$sub,$emp){

            DB::table('FGASIGL2')
                 ->where('EMP_ASIGL2',$emp)
                 ->where('SUB_ASIGL2',$sub)
                 ->where('REF_ASIGL2',$ref)
                 ->where('ORIGEN_ASIGL2','W')
                 ->where('ESTADO_ASIGL2','P')
                 ->delete();

             return true;


    }

    public function getIncrementGastosExtras($ref,$sub,$emp){
         return DB::table('FGASIGL2')
                ->where('EMP_ASIGL2',$emp)
                ->where('SUB_ASIGL2',$sub)
                ->where('REF_ASIGL2',$ref)
                ->max('LIN_ASIGL2');
    }

    public function insertGastosExtrasLot($value_extra){

            DB::table('FGASIGL2')->insert([
               ['EMP_ASIGL2' => $value_extra->EMP_ASIGL2, 'SUB_ASIGL2' => $value_extra->SUB_ASIGL2,'REF_ASIGL2'=>$value_extra->REF_ASIGL2,
                   'LIN_ASIGL2' => $value_extra->LIN_ASIGL2, 'DESC_ASIGL2' => $value_extra->DESC_ASIGL2, 'IMP_ASIGL2' => $value_extra->IMP_ASIGL2,
                   'IVA_ASIGL2'=>$value_extra->IVA_ASIGL2,'IMPIVA_ASIGL2'=>$value_extra->IMPIVA_ASIGL2,'ORIGEN_ASIGL2'=>$value_extra->ORIGEN_ASIGL2,
                   'ESTADO_ASIGL2'=>$value_extra->ESTADO_ASIGL2,'TIPO_ASIGL2'=>$value_extra->TIPO_ASIGL2,'SEC_ASIGL2'=>$value_extra->SEC_ASIGL2]
           ]);
           return true;

    }

    public function getGastosExtrasLot($sub,$ref,$origen = null,$estado = 'P',$tipo = null){
        $extra = DB::table('FGASIGL2')
                ->select('IMP_ASIGL2','IMPIVA_ASIGL2','DESC_ASIGL2','TIPO_ASIGL2')
                ->when($origen, function($q) use($origen){
                    return $q->where('ORIGEN_ASIGL2',$origen);
                })
                ->when($tipo, function($q) use($tipo){
                    return $q->where('TIPO_ASIGL2',$tipo);
                })
                ->where('EMP_ASIGL2',Config::get('app.emp'))
                ->where('SUB_ASIGL2',$sub)
                ->where('REF_ASIGL2',$ref)
                ->where('ESTADO_ASIGL2',$estado)
                ->orderby('TIPO_ASIGL2','ASC')
                ->get();

        foreach($extra as $key_exta => $value_extra){
            $extra[$key_exta]->imp_asigl2 = floatval($value_extra->imp_asigl2);
            $extra[$key_exta]->impiva_asigl2 = floatval($value_extra->impiva_asigl2);
        }
        return $extra;
    }

	/**
	 * Obtener los gastos extra de un conjunto de lotes
	 *
	 * @param Collection<string, array> $auctionsLots ['cod_sub' => [ref1, ref2, ...]]
	 * @param string $estado
	 * @param string $origen
	 * @param string $tipo
	 * @return Collection
	 */
	public function getGastosExtra(Collection $auctionsLots, string $estado = 'P', string $origen = null, string $tipo = null) : Collection
	{
		return FgAsigl2::getBuilderForAuctions($auctionsLots)
			->select('imp_asigl2', 'impiva_asigl2', 'desc_asigl2', 'tipo_asigl2', 'sub_asigl2', 'ref_asigl2')
			->when($origen, function($q) use ($origen){
				return $q->where('origen_asigl2', $origen);
			})
			->when($tipo, function($q) use ($tipo){
				return $q->where('tipo_asigl2', $tipo);
			})
			->where('estado_asigl2', $estado)
			->orderBy('tipo_asigl2', 'asc')
			->get();
	}

    public function updateGastosExtrasLot($lot){
        $sql = " UPDATE  FGASIGL2 SET ESTADO_ASIGL2 = :estado_c
                WHERE REF_ASIGL2 = :ref
                AND SUB_ASIGL2 = :sub
                AND EMP_ASIGL2 = :emp
                AND ESTADO_ASIGL2 = :estado";

        $bindings =  array(
            'sub'=> $lot->sub_csub,
            'ref'  => $lot->ref_csub,
            'estado' => 'P',
            'estado_c'  => 'C',
            'emp'          =>  $lot->emp_csub,
        );
        DB::select($sql, $bindings);

    }

    public function getLotsFact($apre,$npre){
        $sql = " SELECT * FROM FGCSUB
                WHERE APRE_CSUB = :apre
                AND NPRE_CSUB = :npre
                AND EMP_CSUB = :emp";

        $bindings =  array(
            'apre'=> $apre,
            'npre'  => $npre,
            'emp'          => Config::get('app.emp'),
        );
        return DB::select($sql, $bindings);
    }

    public function getEXTRAINF($apre,$npre){

        $sql = "SELECT FGCSUB0.EXTRAINF_CSUB0 FROM FGCSUB0
                WHERE APRE_CSUB0 = :apre
                AND NPRE_CSUB0 = :npre
                AND EMP_CSUB0 = :emp";

        $bindings =  array(
            'apre'=> $apre,
            'npre'  => $npre,
            'emp'          => Config::get('app.emp'),
        );
        return head(DB::select($sql, $bindings));
    }

    public function licExportacion($any,$sec,$imp = null){
         $query = DB::TABLE('FGCERTEXP')
                ->where('EMP_CERTEXP',Config::get('app.emp'))
                ->where('ANOS_CERTEXP','<',$any)
                ->where(function($q) use ($sec) {
                    $q->where('SEC_CERTEXP',$sec)
                    ->orwhere('SEC_CERTEXP','*');
                });

        if(!empty($imp)){
           $query->where('MINIMP_CERTEXP','<',$imp);
        }
        return $query->get();

    }

    //lotes pendientes de pagar
    public function lots_pending_pay($date){
        $gemp = Config::get('app.gemp');

        $pending_lots = array();
        //Todos los lotes que se adjudicaron ese dia
        $pay = DB::Table('FGCSUB')
                ->select('FGCSUB.*')
                ->addSelect('FXCLI.nom_cli,FXCLI.email_cli,FXCLI.idioma_cli,FXCLI.cod_cli')
                ->Join('FXCLI', function ($join) use($gemp){
                    $join->on('FGCSUB.clifac_csub', '=', 'FXCLI.cod_cli')
                    ->on('FXCLI.gemp_cli', '=', $gemp);
                })
                ->where('trunc(FECHA_CSUB)',$date)
                ->where('EMP_CSUB',Config::get('app.emp'))
                ->get();

        if(empty($pay)){
           return;
        }

        foreach($pay as $value_pay){
            $pending_lots[$value_pay->sub_csub][$value_pay->ref_csub] = $value_pay;
        }

        //Todos los lotes que se pagaron por prefact
        $pay_prefact = DB::Table('FGCSUB')
                ->select('FGCSUB.*,FGCSUB0.*')
                ->Join('FGCSUB0', function ($join) {
                    $join->on('FGCSUB.EMP_CSUB', '=', 'FGCSUB0.EMP_CSUB0')
                    ->on('FGCSUB.APRE_CSUB', '=', 'FGCSUB0.APRE_CSUB0')
                    ->on('FGCSUB.NPRE_CSUB', '=', 'FGCSUB0.NPRE_CSUB0')
                    ->where('FGCSUB0.ESTADO_CSUB0','!=','N');
                })
                ->where('trunc(FECHA_CSUB)',$date)
                ->where('EMP_CSUB',Config::get('app.emp'))
                ->get();

        //Elimina los lotes pagados por prefact
        foreach($pay_prefact as $fact){
            if(!empty($pending_lots[$fact->sub_csub][$fact->ref_csub])){
                 unset($pending_lots[$fact->sub_csub][$fact->ref_csub]);
            }
        }

        //Lotes que estan facturados
        $pay_fact = DB::Table('FGCSUB')
                ->select('FGCSUB.*')
                ->Join('FXCOBRO1', function ($join) {
                    $join->on('FGCSUB.EMP_CSUB', '=', 'FXCOBRO1.EMP_COBRO1')
                    ->on('FGCSUB.AFRAL_CSUB', '=', 'FXCOBRO1.AFRA_COBRO1')
                    ->on('FGCSUB.NFRAL_CSUB', '=', 'FXCOBRO1.NFRA_COBRO1')
                    ->where('FGCSUB.FAC_CSUB','=','S');
                })
                ->where('trunc(FECHA_CSUB)',$date)
                ->where('EMP_CSUB',Config::get('app.emp'))
                ->get();

        //eliminamos los que estan facturados


        foreach($pay_fact as $fact){
            if(!empty($pending_lots[$fact->sub_csub][$fact->ref_csub])){
                 unset($pending_lots[$fact->sub_csub][$fact->ref_csub]);
            }
        }

        $users_pending = array();

        foreach($pending_lots as $pending_sub){
            foreach($pending_sub as $pending_lot){
                $users_pending[$pending_lot->cod_cli][]=$pending_lot;
            }
        }

        return $users_pending;
    }

    public function getPrefacturaGenerated ($sub,$lot){

        return DB::Table('FGCSUB')
                ->select('FGCSUB.*,FGCSUB0.*')
                ->Join('FGCSUB0', function ($join) {
                    $join->on('FGCSUB.EMP_CSUB', '=', 'FGCSUB0.EMP_CSUB0')
                    ->on('FGCSUB.APRE_CSUB', '=', 'FGCSUB0.APRE_CSUB0')
                    ->on('FGCSUB.NPRE_CSUB', '=', 'FGCSUB0.NPRE_CSUB0')
                    ->where('FGCSUB0.ESTADO_CSUB0','=','N');
                })
                ->where('SUB_CSUB',$sub)
                ->where('REF_CSUB',$lot)
                ->where('EMP_CSUB',Config::get('app.emp'))
                ->first();
    }

    public function updatePreFactB($npre,$apre){
        DB::table('FGCSUB0')
           ->where('apre_csub0',$apre)
           ->where('npre_csub0',$npre)
           ->where('EMP_CSUB0',Config::get('app.emp'))
           ->update(['ESTADO_CSUB0' => 'B']);
    }




}
