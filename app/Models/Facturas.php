<?php

# Ubicacion del modelo
namespace App\Models;

use App\Models\V5\FgDvc1l;
use App\Models\V5\FxDvc02;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Request;

class Facturas extends Model
{
    public $cod_cli;
    public $anum;
    public $num;

    public $serie;
    public $numero;
    public $efec;

    public $imp;
    public $tk;


	/**
	 * Obtiene las pagos pendientes
	 * @param bool $all
	 * @param string $type Tipo de factura: P (propietario) o L (licitador)
	 */
    public function pending_bills($all = true, $type = '', $whereIntervalDates = [])
	{
		//añadir select tv_contav de fscontav
        $gemp = config('app.gemp');
        $sql = DB::table('FXPCOB')
                ->select('FXPCOB.*, FGSUB.COMPRAWEB_SUB, FGSUB.COD_SUB, FGSUB.DES_SUB, FXDVC0.fecha_dvc0', 'FXDVC0.tipo_dvc0')
				->selectRaw("(select tv_contav from FSCONTAV where SER_CONTAV = SUBSTR(FXPCOB.anum_pcob, 0, 1) and PER_CONTAV = SUBSTR(FXPCOB.anum_pcob, 2, 3) AND EMP_CONTAV = FXPCOB.EMP_PCOB) as tv_contav")
                ->Join('FXCLI',function($join) use($gemp){
                    $join->on('FXPCOB.COD_PCOB','=','FXCLI.COD_CLI')
                    ->where('GEMP_CLI','=',$gemp);
                })
                ->Join('FXDVC0',function($join){
                    $join->on('EMP_DVC0','=','EMP_PCOB')
                    ->on('ANUM_DVC0','=','ANUM_PCOB')
                    ->on('NUM_DVC0','=','NUM_PCOB');
                })
                ->leftJoin('FXDVC02',function($join){
                    $join->on('EMP_DVC02','=','EMP_PCOB')
                    ->on('ANUM_DVC02','=','ANUM_PCOB')
                    ->on('NUM_DVC02','=','NUM_PCOB');
                })
                ->leftJoin('FGSUB',function($join){
                    $join->on('EMP_DVC02','=','EMP_SUB')
                    ->on('SUB_DVC02','=','COD_SUB');
                })
                ->where('COD_PCOB', $this->cod_cli)
                ->where('EMP_PCOB', Config::get('app.emp'))
				->when(!empty($type), function ($query) use ($type) {
					return $query->where('FXDVC0.tipo_dvc0', $type);
				})
				->when(!empty($whereIntervalDates), function ($query) use ($whereIntervalDates) {
					$query->where(function($query) use ($whereIntervalDates){
						foreach ($whereIntervalDates as $interval) {
							$query->orWhereBetween('fecha_dvc0', $interval);
						}
					});
				});
                if(!empty($this->serie)){
                    $sql->where('anum_pcob',$this->serie);
                }
                if(!empty($this->numero)){
                    $sql->where('num_pcob',$this->numero);
                }
				$sql->orderBy('fecha_dvc0', 'desc');

                if($all){
                    $value = $sql->get();
                }else{
                    $value = $sql->first();
                }

        return $value;

    }

    public function cliFact(){
        $gemp = Config::get('app.gemp');
        return DB::table('FXDVC0')
                ->select('nom_cli','email_cli','idioma_cli')
                 ->Join('FXCLI',function($join) use($gemp){
                    $join->on('FXDVC0.COD_DVC0','=','FXCLI.COD_CLI')
                    ->where('GEMP_CLI','=',$gemp);
                })
                ->where('anum_dvc0',$this->serie)
                ->where('num_dvc0',$this->numero)
               ->first();
    }

	/**
	 * Obtiene la cobros de facturas
	 * @param bool $showWhenPending
	 * @param string $type Tipo de factura: P (propietario) o L (licitador)
	 * @param array $whereIntervalDates Intervalos de fechas
	 * @param bool $group Agrupar resultados por factura, en caso contrario los resultados son por cobros
	 */
    public function paid_bill($showWhenPending = true, $type = '', $whereIntervalDates = [], $group = false){
        $sql = DB::TABLE('FXCOBRO1')
                ->select('afra_cobro1, nfra_cobro1, tv_contav, fec_cobro1')
                ->join('FSCONTAV',function($join){
                    $join->on('FSCONTAV.SER_CONTAV','=','SUBSTR(FXCOBRO1.afra_cobro1,0,1)')
                    ->on('FSCONTAV.PER_CONTAV','=','SUBSTR(FXCOBRO1.afra_cobro1,2)')
                    ->where('EMP_contav','=',Config::get('app.emp'));
                })
                ->where('EMP_COBRO1', Config::get('app.emp'))
                ->where('CLI_COBRO1',$this->cod_cli)
				->when(!$showWhenPending, function ($query) {
					return $query->leftjoin('FXPCOB', 'FXPCOB.ANUM_PCOB = FXCOBRO1.AFRA_COBRO1 AND FXPCOB.NUM_PCOB = FXCOBRO1.NFRA_COBRO1 AND FXPCOB.EMP_PCOB = FXCOBRO1.EMP_COBRO1')
						->whereNull('FXPCOB.ANUM_PCOB');
				})
				->when(!empty($type), function ($query) use ($type) {
					return $query->join('FXDVC0', 'FXDVC0.EMP_DVC0 = FXCOBRO1.EMP_COBRO1 and FXDVC0.ANUM_DVC0 = FXCOBRO1.AFRA_COBRO1 and FXDVC0.NUM_DVC0 = FXCOBRO1.NFRA_COBRO1')
						->where('FXDVC0.tipo_dvc0', $type);
				})
				->when(!empty($whereIntervalDates), function ($query) use ($whereIntervalDates) {
					$query->where(function($query) use ($whereIntervalDates){
						foreach ($whereIntervalDates as $interval) {
							$query->orWhereBetween('fec_cobro1', $interval);
						}
					});
				})
				->when($group, function ($query) {
					$query->groupBy('afra_cobro1', 'nfra_cobro1', 'tv_contav', 'fec_cobro1');
					$query->selectRaw('sum(imp_cobro1) as imp_cobro1');
				}, function ($query) {
					$query->addSelect('imp_cobro1');
				});
                if(!empty(Config::get('app.allBills'))){
                    $sql->whereIn('tv_contav',[Config::get('app.allBills')]);
                }
                if(Request::input('order') == 'lasted'){
                    $sql->orderBy('fec_cobro1','asc');
                }else{
                    $sql->orderBy('fec_cobro1','desc');
                }
                if(Config::get('app.pagination_bills')){
                    return $sql->paginate(Config::get('app.pagination_bills'));
                }else{
                    return $sql->get();
                }

    }

    public function bill_text_sub($letra,$num){
        return DB::table('FSCONTAV')
                ->select('TV_CONTAV')
                ->where('PER_CONTAV',$num)
                ->where('SER_CONTAV',$letra)
                ->where('EMP_contav',Config::get('app.emp'))
                ->first();
    }

    public function getFactSubasta()
	{
        $bindings = array(
            'emp'       => Config::get('app.emp'),
            'anum'       =>$this->serie,
            'num'   => $this->numero,
            'lang'      => \Tools::getLanguageComplete(Config::get('app.locale'))
        );

        $sql = "SELECT FGDVC1L.*,
            NVL(HCES1_LANG.DESCWEB_HCES1_LANG,  HCES1.DESCWEB_HCES1) DESCWEB_HCES1,
            NVL(HCES1_LANG.DESC_HCES1_LANG,  HCES1.DESC_HCES1) DESC_HCES1,
            NVL(HCES1_LANG.TITULO_HCES1_LANG,  HCES1.TITULO_HCES1) TITULO_HCES1
            FROM FGDVC1L
            LEFT JOIN FGHCES1 HCES1 ON tl_dvc1l = 'P' and HCES1.emp_hces1 = EMP_DVC1L and HCES1.num_hces1 = numhces_dvc1l and HCES1.lin_hces1 = linhces_dvc1l
            LEFT JOIN FGHCES1_LANG HCES1_LANG ON (HCES1_LANG.EMP_HCES1_LANG = HCES1.EMP_HCES1 AND HCES1_LANG.NUM_HCES1_LANG =  HCES1.NUM_HCES1 AND HCES1_LANG.LIN_HCES1_LANG = HCES1.LIN_HCES1 AND HCES1_LANG.LANG_HCES1_LANG = :lang)
            WHERE EMP_DVC1L = :emp AND NUM_DVC1L = :num AND ANUM_DVC1L = :anum";

        return DB::select($sql,$bindings);
    }

	/**
	 * Copia del metodo @see getFactSubasta()
	 * pero permitiendo obtener varias facturas a la vez.
	 */
	public function getFacturaLotsByMultipleSheets(array $seriesAndLines)
	{
		if(empty($seriesAndLines)){
			return collect();
		}

		$invoiceLots = FgDvc1l::query()
			->select('FGDVC1L.*')
			->withBuyerLotsInfo()
			->whereMultiplesSeriesAndLines($seriesAndLines)
			->get();

		return $invoiceLots;
	}

    public function getFactTexto(){

        $bindings = array(
            'emp'       => Config::get('app.emp'),
            'anum'       =>$this->serie,
            'num'   => $this->numero,
        );

        $sql = "Select anum_dvc1,num_dvc1,lin_dvc1,total_dvc1,imp_dvc1,total_dvc1,iva_dvc1,des_dvc2t,lin2_dvc2t
                from FXDVC1
                LEFT JOIN FXDVC2T on EMP_DVC2T = EMP_DVC1 and anum_dvc1 = anum_dvc2t and  num_dvc1 = num_dvc2t and lin_dvc1 = lin2_dvc2t
                where EMP_DVC1 = :emp
                and anum_dvc1 = :anum
                and num_dvc1 = :num
                order by anum_dvc1,num_dvc1,lin_dvc2t,lin2_dvc2t";
        $value = DB::select($sql,$bindings);

        $factura = array();
        $fact = array();
        foreach($value as $val){
            if(empty($fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t])){
                $fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t] = $val;
            }else{
                $fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t]->des_dvc2t .= $val->des_dvc2t;
            }

            $fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t]->des_dvc2t = str_replace('\b',' ',$fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t]->des_dvc2t);
            $fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t]->des_dvc2t = str_replace('\n','<br>',$fact[$val->anum_dvc1][$val->num_dvc1][$val->lin2_dvc2t]->des_dvc2t);
        }

        foreach($fact as $anum_fact){
            foreach($anum_fact as $num_fact){
                foreach($num_fact as $lin_fact){
                    $factura[] = $lin_fact;
                }
            }
        }

        return $factura;

    }



    public function mergeFXPCOB1(){

              $sql ="MERGE INTO FXPCOB1 pcob1
                    USING( SELECT :emp emp, :serie serie, :numero numero, :cod_cli cod FROM dual) src
                    ON( pcob1.emp_pcob1 = src.emp and pcob1.SERIE_PCOB1 = src.serie  and pcob1.NUMERO_PCOB1 = src.numero and pcob1.cod_pcob1 = src.cod)
                    WHEN MATCHED THEN
                    UPDATE SET ANUM_PCOB1 = :anum , NUM_PCOB1 = :num
                    where EMP_PCOB1 =:emp and SERIE_PCOB1 = :serie and NUMERO_PCOB1 = :numero and COD_PCOB1 = :cod_cli and EFECTO_PCOB1 = :efec
                    WHEN NOT MATCHED THEN
                    INSERT (EMP_PCOB1,ANUM_PCOB1,NUM_PCOB1,COD_PCOB1,SERIE_PCOB1,NUMERO_PCOB1,EFECTO_PCOB1)
                    VALUES(:emp,:anum,:num,:cod_cli,:serie,:numero,:efec)";
              $bindings = array(
                        'emp'   => \Config::get('app.emp'),
                        'anum' =>$this->anum,
                        'num'=>$this->num,
                        'numero'=>$this->numero,
                        'serie'=>$this->serie,
                        'efec'  => $this->efec,
                        'cod_cli' => $this->cod_cli
                    );
               DB::select($sql,$bindings);
            return true;

    }

    public function insertFact(){
        DB::table('FXPCOB0')->insert([
            ['EMP_PCOB0' => \Config::get('app.emp'), 'anum_pcob0' => $this->anum,'num_pcob0'=>$this->num, 'COD_PCOB0' => $this->cod_cli, 'IMP_PCOB0' => $this->imp, 'ESTADO_PCOB0' => 'N','TK_PCOB0' => $this->tk]
        ]);
        return true;
    }

    public function getFXPCOB0($emp,$num,$anum,$tk){
        $gemp = \Config::get('app.gemp');
        return DB::TABLE('FXPCOB0')
        ->Join('FXCLI',function($join) use($gemp){
           $join->on('FXPCOB0.COD_PCOB0','=','FXCLI.COD_CLI')
           ->where('GEMP_CLI','=',$gemp);
       })
        ->where('EMP_PCOB0',$emp)
        ->where('ANUM_PCOB0',$anum)
        ->where('NUM_PCOB0',$num)
        ->where('TK_PCOB0',$tk)
        ->first();
    }


     public function updateFact($anum,$num,$emp,$ordenTrans){
       DB::table('FXPCOB0')
        ->where('ANUM_PCOB0',$anum)
        ->where('NUM_PCOB0',$num)
        ->where('EMP_PCOB0',$emp)
        ->update(['IDTRANS_PCOB0' => $ordenTrans]);

    }


    public function getInfFactExt($trans){
       return DB::table('FXPCOB0_EXT')
        ->WHERE('EMP_PCOB0_EXT',\Config::get('app.emp'))
        ->WHERE('IDTRANS_PCOB0_EXT',$trans)
        ->first();
    }

    public function newPCOB0_EXT($emp,$anum,$num,$ordenTrans,$fechaactual){
        DB::table('FXPCOB0_EXT')->insert([
            ['EMP_PCOB0_EXT' => $emp, 'ANUM_PCOB0_EXT' => $anum,'NUM_PCOB0_EXT'=>$num, 'IDTRANS_PCOB0_EXT' => $ordenTrans, 'FECHA_PCOB0_EXT' => $fechaactual]
        ]);
    }

    public function updateRequest($fields,$ordenTrans,$emp){
         DB::table('FXPCOB0_EXT')
           ->where('IDTRANS_PCOB0_EXT',$ordenTrans)
           ->where('EMP_PCOB0_EXT',$emp)
           ->update(['REQUEST_PCOB0_EXT' => $fields]);
    }

    public function updateReturn($return,$ordenTrans,$emp){
         DB::table('FXPCOB0_EXT')
           ->where('IDTRANS_PCOB0_EXT',$ordenTrans)
           ->where('EMP_PCOB0_EXT',$emp)
           ->update(['RETURN_PCOB0_EXT' => $return]);
    }


     public function insertHistTrans($amount,$fact,$post){

       DB::select("INSERT INTO FXPCOB0H (EMP_PCOB0H, APRE_PCOB0H, NPRE_PCOB0H,IMP_PCOB0H,FECHA_PCOB0H,OBS_PCOB0H) "
                . "VALUES (:emp,:anum, :num,:importe,SYSDATE,:observaciones)",
                    array(
                        'anum'   => $fact->anum_pcob0_ext,
                        'emp'       => $fact->emp_pcob0_ext,
                        'num' => $fact->num_pcob0_ext,
                        'importe' => $amount,
                        'observaciones' => json_encode ($post)
                        )
                );
   }

   public function updateTransCob($amount,$customerid,$factura){


       DB::select("Update FXPCOB0 Set estado_PCOB0=:estado, IMPCOB_PCOB0 = :importe, idtrans_PCOB0 = :transaccion Where ANUM_PCOB0=:anum and NUM_PCOB0=:num and EMP_PCOB0 = :emp ",
                    array(
                        'estado'   => "C",
                        'importe'       => $amount,
                        'transaccion' => $customerid,
                        'anum'  => $factura->anum_pcob0_ext,
                        'num'  => $factura->num_pcob0_ext,
                        'emp'   => $factura->emp_pcob0_ext,
                        )
                );
   }

    public function getFactCob(){


        $bindings = array(
            'emp'       => Config::get('app.emp'),
            'anum'       =>$this->anum,
            'num'   => $this->num,
        );

        $sql = "Select *
                from FXPCOB1
                JOIN FXPCOB on SERIE_PCOB1 = ANUM_PCOB and NUMERO_PCOB1 = NUM_PCOB and  EFECTO_PCOB1 = EFEC_PCOB and EMP_PCOB1 = EMP_PCOB
                where EMP_PCOB1 = :emp
                and ANUM_PCOB1 = :anum
                and NUM_PCOB1 = :num";
        return DB::select($sql,$bindings);

    }

    public function insertCOBRO0($anum,$num,$params,$date){

         DB::table('FXCOBRO0')->insert([
            'EMP_COBRO0' => \Config::get('app.emp'), 'ANUM_COBRO0' => $anum,'NUM_COBRO0'=>$num, 'CLI_COBRO0' => $this->cod_cli, 'FECI_COBRO0' => $date,
                'USR_COBRO0'=>'WEB','ASENT_COBRO0'=>'N','CONC1_COBRO0'=>'COBRO GLOBAL1','CONC2_COBRO0'=>'COBRO GLOBAL2','CONC3_COBRO0'=>'COBRO GLOBAL3'
                ,'INGRESADO_COBRO0'=>'N','TOTAL1_COBRO0'=>'0','TOTAL2_COBRO0'=>'0','TOTAL3_COBRO0'=>'0'
                ,'CTAIC_COBRO0'=>$params->cob1_param1, 'CTAIT_COBRO0'=>$params->cob2_param1, 'CTAIB_COBRO0'=>$params->cob3web_param1
        ]);
    }

    public function  maxCOBRO1($anum,$num){
        return DB::TABLE('FXCOBRO1')
                ->where('emp_cobro1',\Config::get('app.emp'))
                ->where('anum_cobro1',$anum)
                ->where('emp_cobro1',$num)
                ->max('lin_cobro1');
    }

     public function insertCOBRO1($anum,$num,$bill,$params,$date,$max_lin){

       $conc_cobro1 = substr(\Config::get('app.emp').'-'.$bill->anum_pcob.'/'.$bill->num_pcob.' - '.$bill->cod_pcob,0,19);

       DB::table('FXCOBRO1')->insert(['EMP_COBRO1'=>\Config::get('app.emp'),'ANUM_COBRO1'=>$anum,'NUM_COBRO1'=>$num,'lin_cobro1'=>$max_lin,'TIPO_COBRO1'=>'3',
           'TCOB_COBRO1'=>$bill->tcob_pcob,'cla_cobro1'=>$bill->cla_pcob,'afra_cobro1'=>$bill->anum_pcob,'nfra_cobro1'=>$bill->num_pcob,
           'efec_cobro1'=>$bill->efec_pcob,'cli_cobro1'=>$bill->cod_pcob,'rsoc_cobro1'=>$bill->rsoc_pcob,'fec_cobro1'=>$bill->fec_pcob,'vto_cobro1'=>$bill->vto_pcob,
           'imp_cobro1'=>$bill->imp_pcob,'cta_cobro1'=>$bill->cta_pcob,'banco_cobro1'=>$bill->banco_pcob,'dirb_cobro1'=>$bill->dirb_pcob,'entb_cobro1'=>$bill->entb_pcob,
           'ofib_cobro1'=>$bill->ofib_pcob,'dcb_cobro1'=>$bill->dcb_pcob,'ctab_cobro1'=>$bill->ctab_pcob,'impag_cobro1'=>$bill->impag_pcob,'pendiente_cobro1'=>$bill->pendiente_pcob,
           'act_cobro1'=>$bill->act_pcob,'obs_cobro1'=>$bill->obs_pcob,'tdoc_cobro1'=>$bill->tdoc_pcob,'fpag_cobro1'=>$bill->fpag_pcob,
           'liqui_cobro1'=>$bill->liqui_pcob,'acta_cobro1'=>$bill->acta_pcob,'ctag_cobro1'=>$bill->ctag_pcob,'impg_cobro1'=>$bill->impg_pcob,
           'usr_cobro1'=>'WEB','conc_cobro1'=>$conc_cobro1,'conf_cobro1'=>'N',
           'asi_cobro1'=>null,'ctaiban_cobro1'=>$bill->ctaiban_pcob,'bic_cobro1'=>$bill->bic_pcob,'asidev_cobro1'=>$bill->asi_pcob,
           'ctai_cobro1'=>$params->cob3_param1,'feci_cobro1'=>$date]);

    }

    public function deletePCOB($bill){

        $sql = DB::table('FXPCOB')
        ->where('emp_pcob',\Config::get('app.emp'))
        ->where('cla_pcob','1')
        ->where('anum_pcob',$bill->anum_pcob)
        ->where('num_pcob',$bill->num_pcob)
        ->where('efec_pcob',$bill->efecto_pcob1)
        ->where('cod_pcob',$bill->cod_pcob1)
        ->delete();

    }

    public function updateCOBRO0($anum_cob,$num_cob){

        DB::table('fxcobro0')
        ->where('emp_cobro0',\Config::get('app.emp'))
        ->where('anum_cobro0',$anum_cob)
        ->where('num_cobro0',$num_cob)
        ->update(['total3_cobro0' => DB::TABLE('FXCOBRO1')
                                        ->where('emp_cobro1',\Config::get('app.emp'))
                                        ->where('anum_cobro1',$anum_cob)
                                        ->where('num_cobro1',$num_cob)
                                        ->where('tipo_cobro1','3')
                                        ->sum('pendiente_cobro1')
        ]);

    }

    public function closeCobro($anum_cob,$num_cob){

        $sql = "Select CERRARCOBRO(:emp ,:gemp ,:anum,:num) from dual";
        $binding = array(
            'emp' => \Config::get('app.emp'),
            'gemp' => \Config::get('app.gemp'),
            'anum' => $anum_cob,
            'num' => $num_cob
        );
        DB::select($sql,$binding);
    }

	public function getBillsFilesFromMultipleSheets(array $seriesAndLines)
	{
		if (empty($seriesAndLines)) {
			return collect();
		}

		$bills = FxDvc02::query()
			->joinDvc0()
			->whereUser($this->cod_cli)
			->whereMultiplesSeriesAndLines($seriesAndLines)
			->whereNotNull('fich_dvc02')
			->select('anum_dvc02', 'num_dvc02', 'fich_dvc02', 'fecha_dvc0')
			->get();

		$billsData = $bills->map(function ($bill) {
			$emp = Config::get('app.emp');
			$fileName = "bills/{$emp}/{$bill->fich_dvc02}.PDF";
			$date = date('d-m-Y', strtotime($bill->fecha_dvc0));

			return [
				'filname' => $fileName,
				'date' => $date,
				'anum_dvc02' => $bill->anum_dvc02,
				'num_dvc02' => $bill->num_dvc02,
			];
		});

		return $billsData;
	}

}
