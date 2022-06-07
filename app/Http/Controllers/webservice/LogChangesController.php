<?php
namespace App\Http\Controllers\webservice;
use App\libs\LogLib;
class LogChangesController  extends WebServiceController
{


	public function logChangesAuction($request){

			$rules = array('codAuction' => "required|alpha_num|max:8" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("FgSub","FgSub", "sub", ["emp_sub" => $this->emp, "cod_sub" => $request["codAuction"]]);
			return $res;

	}

	public function logChangesSession($request){

			$rules = array('codAuction' => "required|alpha_num|max:8", "refSession" => "required|alpha_num|max:3" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("AucSessions", '"auc_sessions"', "sessions", ['"company"' => $this->emp, '"auction"' =>  $request["codAuction"], '"reference"' => $request["refSession"]],true );
			return $res;
	}


   public function logChangesLot($request){

			$rules = array('codAuction' => "required|alpha_num|max:8", 'refLot'   => "required|numeric" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("FgAsigl0","FgAsigl0", "asigl0", ["emp_asigl0" => $this->emp, "sub_asigl0" => $request["codAuction"], "ref_asigl0" => $request["refLot"]]);
			return $res;
   }

   public function logChangesInfolot($request){


			$rules = array('assigNum' => "required|numeric", 'assigLin'   => "required|numeric" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("FgHces1","FgHces1", "hces1", ["emp_hces1" => $this->emp, "num_hces1" => $request["assigNum"], "lin_hces1" => $request["assigLin"]]);
			return $res;

	}

	public function logChangesBid($request){

			$rules = array('codAuction' => "required|alpha_num|max:8", 'refLot'   => "required|numeric" , 'linBid'   => "required|numeric" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("FgAsigl1","FgAsigl1", "asigl1", ["emp_asigl1" => $this->emp, "sub_asigl1" => $request["codAuction"], "ref_asigl1" => $request["refLot"],"lin_asigl1" =>  $request["linBid"]]);
			return $res;

   }

   public function logChangesOrder(){

		    $request = request("request");
			$rules = array('codAuction' => "required|alpha_num|max:8", 'refLot'   => "required|numeric", 'linOrder'   => "required|numeric" );
			$this->validator($request, $rules);
			$res = LogLib::getLogChanges("FgOrlic","FgORLIC", "orlic", ["emp_orlic" => $this->emp, "sub_orlic" =>  $request["codAuction"], "ref_orlic" => $request["refLot"], "lin_orlic" =>$request["linOrder"]]);

			return $res;
   }

}
