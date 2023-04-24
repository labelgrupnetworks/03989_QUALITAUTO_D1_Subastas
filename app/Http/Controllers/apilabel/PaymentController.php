<?php

namespace App\Http\Controllers\apilabel;



use Controller;
use Config;
use Request;

use App\Models\V5\FxPcob;
use App\Models\V5\FxDvc0;
use App\Models\V5\FxDvc1;
use App\Models\V5\FxDvc02;
use App\Models\V5\FxDvc2t;
use App\Models\V5\FsContav;
use App\Models\V5\FxCli;
use App\Models\V5\FxCobro1;
use App\libs\EmailLib;
use DB;

#IMPORTANTE  es necesario que exista un registro en la tabla FSCONTAV y los pagos los asociaremos siempre a este , este registro debe tener fecha de finalización lo mas lejso en el tiempo que se pueda.
/*
REGISTRO NECESARIO PARA GENERAR PAGOS
Insert into FSCONTAV (EMP_CONTAV,CLA_CONTAV,SER_CONTAV,PER_CONTAV,DFEC_CONTAV,HFEC_CONTAV,ALC_CONTAV,FCC_CONTAV,TV_CONTAV,LOGO_CONTAV,DESC_CONTAV)
values ('001','1','T','20',to_date('18/06/2020 08:03:39','DD/MM/RR HH24:MI:SS'),to_date('18/06/2040 08:03:41','DD/MM/RR HH24:MI:SS'),'0','0','T',null,null);

El serial que deberan enviar deberá ser siempre SER_CONTAV concatenado con PER_CONTAV, en este ejemplo T20, y el number será el que decida la casa de subastas
*/
class PaymentController extends ApiLabelController
{


    protected  $fxpcobRename = array( "cod_cli" => "cod_pcob","user_name" => "rsoc_pcob", "serial"=>"anum_pcob","number"=>"num_pcob", "amount"=>"imp_pcob","date"=>"fec_pcob", "pending_amount"=>"pendiente_pcob" );
	protected  $fxcobro1Rename = array( "cod_cli" => "cli_cobro1","user_name" => "rsoc_cobro1", "serial"=>"afra_cobro1","number"=>"nfra_cobro1", "amount"=>"imp_cobro1","date"=>"fec_cobro1", "num_cobro" => "num_cobro1" );
   	protected  $dvc0Rename = array( "cod_cli" => "cod_dvc0","user_name" => "rsoc_dvc0", "serial"=>"anum_dvc0","number"=>"num_dvc0", "amount"=>"total_dvc0","date"=>"fecha_dvc0" );
	protected  $dvc1Rename = array( "cod_cli" => "cod_dvc1", "serial"=>"anum_dvc1","number"=>"num_dvc1", "amount"=>"total_dvc1" );
	protected  $dvc02Rename = array(  "serial"=>"anum_dvc02","number"=>"num_dvc02", "path_pdf" =>"fich_dvc02" );
	protected  $dvc2tRename = array(  "serial"=>"anum_dvc2t","number"=>"num_dvc2t", "description" =>"des_dvc2t" );

	protected  $rules = array('cod_cli' => "required|alpha_num|max:10",  "serial"=> "required|alpha_num|max:3", "number"=> "required|numeric|max:99999999", 'amount'   => "required|numeric|max:999999", "date" => "required|date_format:Y-m-d H:i:s","description"=> "max:60"  );


    #si se amplia esto ampliar el where del get, las reglas de busqueda son diferentes a las reglas normales ya que si no, habria campos requeridos y no nos permitiria hacer busquedas por todo
    protected $searchRules = array( "serial"=> "required|alpha_num|max:3", "number"=> "required|numeric|max:99999999");

    public function postPayment(){
        $items =  request("items");
       return $this->createPayment( $items );
    }


    public function createPayment($items){
        try {
			DB::beginTransaction();
			if(empty($items) || empty($items[0])){
				throw new ApiLabelException(trans('apilabel-app.errors.no_items'));
			}

			#separar pagos en dos grupos, los pagados y los pendientes de pago
			$pendiente_pago =array();
			$pagados =array();
			#obtenemos el numero actual de cobro
			$numcobro1 = FxCobro1::select("max(num_cobro1) as maxnum")->first()->maxnum;
			foreach($items as $key =>$item){
				#se deben rellenar dos campos de importe, el segundo lo fuerzo
				$items[$key]["pending_amount"]=$items[$key]["amount"];

				if(!empty($item["pdf"]) || !empty($item["pdf64"]) ){
					$namefile = $item["serial"]."_".$item["number"].'_'.time();
					$items[$key]["path_pdf"] = $namefile;
				}
				#validamos que venga el idorigincli
				$this->validatorArray($items, ["idorigincli" => "required|alpha_num|max:10"]);

				# coger datos de usuario, si no existe dará fallo el primer create ya que es obligatorio

				if(!empty($item["idorigincli"])){
					$fxcli = 	FxCli::select("COD_CLI, NOM_CLI")->where("cod2_cli", $item["idorigincli"])->first();

					if(empty($fxcli)){
						throw new ApiLabelException(trans('apilabel-app.errors.no_exist_client'));
					}

					$items[$key]["cod_cli"] = $fxcli->cod_cli;
					$items[$key]["user_name"] = $fxcli->nom_cli;
				}

				#mirar si existe contav
				$this->searchContav($item["serial"]);


				if(!empty($item["paid"]) && $item["paid"]=="S"){
					$numcobro1 ++;
					$items[$key]["num_cobro"] = $numcobro1;
					//poner valor anum y num
					$pagados[]=$items[$key];
				}else{
					$pendiente_pago[]=$items[$key];
				}
			}

			#solo pagados
			#ponemos el valor C20 siempre, el valor num_cobro1 será
			$fxcobro1defaultValues = array( "anum_cobro1" => "C20", "efec_cobro1" => 1, "cla_cobro1" => 1, "lin_cobro1" => 1);
			$this->create($pagados, $this->rules, $this->fxcobro1Rename, new FxCobro1(), $fxcobro1defaultValues);

			#solo pendietne de pago
			$fxcobdefaultValues = array("efec_pcob" => 1, 'cla_pcob' => 1);
			$this->create($pendiente_pago, $this->rules, $this->fxpcobRename, new FxPcob(), $fxcobdefaultValues);

			#todos
			$this->create($items, $this->rules, $this->dvc0Rename, new FxDvc0());
			$fxdvc1defaultValues = array("lin_dvc1" => 1);
			$this->create($items, $this->rules, $this->dvc1Rename, new FxDvc1(), $fxdvc1defaultValues);
			$this->create($items, $this->rules, $this->dvc02Rename, new FxDvc02());
			$dvc2tdefaultValues = array("lin_dvc2t" => 1, "lin2_dvc2t" => 1);
			$this->create($items, $this->rules, $this->dvc2tRename, new FxDvc2t(), $dvc2tdefaultValues);

			$this->uploadPDF($items);


			#enviamos los emails al final, para asegurarnos de que todo ha ido bien
			foreach($items as $key =>$item){
				$email = new EmailLib('PAY_IN_WEB');
				if (!empty($email->email)) {
					$email->setUserByCod($item["cod_cli"]);
					$email->send_email();
				}
			}

			DB::commit();
            return  $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }
    }


    #
    public function getPayment(){
        return $this->showPayment(request("parameters"));
    }

    public function showPayment($whereVars){
		$dvc0 =  New Fxdvc0();
        $dvc0 = $dvc0->join("fxdvc02","emp_dvc02 = emp_dvc0 and anum_dvc0 = anum_dvc02 and  num_dvc02 = num_dvc0 " );
        $dvc0 = $dvc0->join("fxdvc2t","emp_dvc2t = emp_dvc0 and anum_dvc0 = anum_dvc2t and num_dvc2t = num_dvc0 " );
		$dvc0 = $dvc0->join("fxcli","gemp_cli = ".Config::get('app.emp')." and cod_cli = cod_dvc0" );
        $dvc0 = $dvc0->leftjoin("fxcobro1","emp_cobro1 = emp_dvc0 and afra_cobro1 = anum_dvc0  and nfra_cobro1 = num_dvc0 " );
		$dvc0 = $dvc0->select("FXDVC0.*, FXDVC2T.DES_DVC2T, FXCLI.COD2_CLI");
		#generamos la url del pdf si este existe
		$url_pdf= Config::get("app.url"). '/bills/'.Config::get('app.emp').'/';
		$dvc0 = $dvc0->selectRaw( " CASE  WHEN FXDVC02.FICH_DVC02 IS NULL THEN '' ELSE concat( ? ,concat(FXDVC02.FICH_DVC02,'.PDF') ) END as FICH_DVC02 ", [$url_pdf]);
		$dvc0 = $dvc0->selectRaw( " CASE  WHEN FXCOBRO1.ANUM_COBRO1 IS NOT NULL THEN 'S' ELSE 'N'  END as PAID ");



		$varAPI = array_flip($this->dvc0Rename);
		unset($varAPI["cod_dvc0"]);
		$varAPI["cod2_cli"] = "idorigincli";
		$varAPI["des_dvc2t"] = "description";
		$varAPI["fich_dvc02"] = "url_pdf";
		$varAPI["paid"] = "paid";
        $wheredvc0Rename = $this->getItems($this->dvc0Rename , array("serial","number"));
		return $this->show($whereVars, $this->searchRules, $wheredvc0Rename, $dvc0,  $varAPI);

    }


    public function putPayment(){
        $items =  request("items");
        return $this->updatePayment( $items );

    }

    public function updatePayment($items){
        try {
			DB::beginTransaction();

			$updateRules = $this->cleanRequired($this->rules ,["serial", "number", "cod_cli"] );
			$deleteRules = $this->cleanRequired($this->rules ,["serial", "number"] );
			#separar pagos en dos grupos, los pagados y los pendientes de pago
			$createPagados =array();
			$createPendientesPago =array();

			#obtenemos el numero actual de cobro
			$numcobro1 = FxCobro1::select("max(num_cobro1) as maxnum")->first()->maxnum;
			foreach($items as $key =>$item){

				if(!empty($items[$key]["amount"])){
					#se deben rellenar dos campos de importe, el segundo lo fuerzo
					$items[$key]["pending_amount"] = $items[$key]["amount"];
				}

				if(!empty($item["pdf"]) || !empty($item["pdf64"])){
					$dxDvc02 = FxDvc02::select("FICH_DVC02")->WHERE("ANUM_DVC02", $item["serial"])->WHERE("NUM_DVC02", $item["number"] );
					if(!empty($dxDvc02) && !empty($dxDvc02->fich_dvc02)){
						$namefile = $dxDvc02->fich_dvc02;
					}else{
						#si no tiene archivo le generamos un nombre
						$namefile = $item["serial"]."_".$item["number"].'_'.time();
					}
					$items[$key]["path_pdf"] = $namefile;
				}

				# coger datos de usuario, si no existe dará fallo el primer create ya que es obligatorio
				if(!empty($item["idorigincli"])){
					$fxcli = 	FxCli::select("COD_CLI, NOM_CLI")->where("cod2_cli", $item["idorigincli"])->first();
					if(empty($fxcli)){
						throw new ApiLabelException(trans('apilabel-app.errors.no_exist_client'));
					}
					$items[$key]["cod_cli"] = $fxcli->cod_cli;
					$items[$key]["user_name"] = $fxcli->nom_cli;
				}

				#mirar si existe contav
				$this->searchContav($item["serial"]);


				# pueden pasar un pago a pagado o viceversa
				if(!empty($item["paid"]) && $item["paid"]=="S"){
					$exist = FxCobro1::select("count(num_cobro1) as num")->where("AFRA_COBRO1", $item["serial"])->where("NFRA_COBRO1", $item["number"])->first();
					#si NO existe el pago se crea, si existe es que ya está en el estado de pagado
					if( $exist->num == 0){
						$numcobro1 ++;
						$items[$key]["num_cobro"] = $numcobro1;
						#ponemso importe si no lo han puesto
						if(empty($item["amount"])){
							$fxdvc0 = FxDvc0::select("TOTAL_DVC0")->WHERE("ANUM_DVC0", $item["serial"])->WHERE("NUM_DVC0", $item["number"] )->first();
							if(!empty($fxdvc0)){
								$items[$key]["amount"]= $fxdvc0->total_dvc0;
							}
						}
						#ponemos fecha si no lo ahn puesto
						if(empty($item["date"])){
							$items[$key]["date"]=date('Y-m-d H:i:s');
						}


						$createPagados[]=$items[$key];

						#borramso el pago pendiente,  solo debemos quedarnos con el afra y nfra ya que si no el where se hará con todos los valores enviados
						$itemdelete =  $this->getItems( $items[$key], array("serial", "number") );
						$this->erase($itemdelete, $deleteRules, $this->fxpcobRename, new FxPcob(), false);

					}
				}elseif(!empty($item["paid"]) && $item["paid"]=="N"){
					#si existe el pago se borra, si no es que ya está en el estado de no pagado
					$exist = FxCobro1::select("count(num_cobro1) as num")->where("AFRA_COBRO1", $item["serial"])->where("NFRA_COBRO1", $item["number"])->first();
					if( $exist->num == 1){
						$createPendientesPago[] = $items[$key];
						#borramso el cobro1,  solo debemos quedarnos con el afra y nfra ya que si no el where se hará con todos los valores enviados
						$itemdelete =  $this->getItems( $items[$key], array("serial", "number") );
						$this->erase($itemdelete, $deleteRules, $this->fxcobro1Rename, new FxCobro1());

					}

				}


			}

			#ponemos el valor C20 siempre, el valor num_cobro1 será
			$fxcobro1defaultValues = array( "anum_cobro1" => "C20", "efec_cobro1" => 1, "cla_cobro1" => 1, "lin_cobro1" => 1);
			#creamos

			$this->create($createPagados, $this->rules, $this->fxcobro1Rename, new FxCobro1(), $fxcobro1defaultValues);

			#solo pendietne de pago
			$fxcobdefaultValues = array("efec_pcob" => 1, 'cla_pcob' => 1);
			#creamos
			$this->create($createPendientesPago, $this->rules, $this->fxpcobRename, new FxPcob(), $fxcobdefaultValues);


			$this->update($items, $updateRules, $this->fxpcobRename, new FxPcob());
			$this->update($items, $updateRules, $this->dvc0Rename, new FxDvc0());

			$this->update($items, $updateRules, $this->dvc1Rename, new FxDvc1());
			$this->update($items, $updateRules, $this->dvc02Rename, new FxDvc02());

			$this->update($items, $updateRules, $this->dvc2tRename, new FxDvc2t());

			$this->uploadPDF($items);

            //     $this->update($items, $this->rules, $this->ortsecRename, new FgPcob());
            DB::commit();
            return $this->responseSuccsess();

        } catch(\Exception $e){
            DB::rollBack();
           return $this->exceptionApi($e);
        }

    }


    public function deletePayment(){
        return $this->erasePayment(request("parameters"));
    }

    public function erasePayment($whereVars){
        try
        {

            DB::beginTransaction();
            $rules = $this->getItems($this->rules, array("serial", "number"));
			#pasamos false para que no genere error, yq que puede no existir ese registro si ya ha sido pagado
            $this->erase($whereVars, $rules, $this->fxpcobRename, new FxPcob(), false);
            $this->erase($whereVars, $rules, $this->fxcobro1Rename, new FxCobro1(), false);
            $this->erase($whereVars, $rules, $this->dvc0Rename, new FxDvc0());
            $this->erase($whereVars, $rules, $this->dvc1Rename, new FxDvc1());
			$this->erase($whereVars, $rules, $this->dvc02Rename, new FxDvc02());
			$this->erase($whereVars, $rules, $this->dvc2tRename, new FxDvc2t());

            DB::commit();
            return $this->responseSuccsess();

        }catch (\Exception $e){
            DB::rollBack();
            return $this->exceptionApi($e);
        }


    }



public function searchContav($serial){
	#buscamos el contav en la fecha actual
	$sercontav = substr($serial, 0, 1);
	$percontav = substr($serial, 1);

	$contav = FsContav::WhereActiveDate(date('Y-m-d H:i:s'))->WHERE("CLA_CONTAV", 1)->WHERE("SER_CONTAV",$sercontav)->WHERE("PER_CONTAV",$percontav)->first();
	if(empty($contav)){

		throw new ApiLabelException(trans('apilabel-app.errors.no_exist_serial'));
		#no se puede crear ya que lo mejor es crear un Fscontav permanente
		/*
		$year = date('y');
		FsContav::create([
						"cla_contav" => 1,
						"ser_contav" => $sercontav,
						"per_contav" => $percontav,
						"tv_contav" => "T", # es l oque marcará que es factura de texto
						"dfec_contav" =>$year."-01-01 00:00:00",
						"hfec_contav" =>$year."-12-31 23:59:59"
		]);
		*/
	}
}


private function uploadPDF($items){
	#creamos las pdfs  al final, para que solo se creen si está todo bien
	foreach($items as $key =>$item){

		if(!empty($item["path_pdf"])){
			$ruta ='bills/'.Config::get('app.emp').'/'. $item["path_pdf"] .'.PDF';
			if(!empty($item["pdf64"])){
				$contentPDF  = base64_decode($item["pdf64"]);
			}elseif(!empty($item["pdf"])){
				//copy($item["pdf"], $ruta );
				$contentPDF = file_get_contents($item["pdf"]);
			}

			$file = fopen($ruta, "w");
			fwrite($file, $contentPDF);
			fclose($file);
		}
	}
}






}
