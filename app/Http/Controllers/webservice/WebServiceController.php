<?php

namespace App\Http\Controllers\webservice;

use App\Http\Controllers\apilabel\ApiLabelController;

use App\Http\Controllers\webservice\LogChangesController;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;



use Controller;
use Config;
use Request;

use DB;
use Exception;


use Illuminate\Database\QueryException;


class WebServiceController extends ApiLabelController
{



	public function index($function){
	    return $this->{$function}();
	}

	public function logChanges(){
		try {
			$logchanges = new LogChangesController();
			$request = request("request");
			$model = $request["model"];
			if(!empty($model)){

				return $logchanges->{"logChanges". ucfirst($model)}($request);
			}
		}catch(\Exception $e){
			return $this->exceptionApi($e);
		}
	}

	public function log(){
		try {
			$log = new LogController();
			$request = request("request");
			$model = $request["model"];
			if(!empty($model)){
				#hacemos aquí la validación ya que así no hay que hacerla dentro de cada función
				$rules = array('startDate' => "date",'endDate' => "date" );
				$this->validator($request, $rules);
				$startDate = $request['startDate']?? null;
				$endDate = $request['endDate']?? null;
				return $log->{"log". ucfirst($model)}($startDate, $endDate);
			}
		}catch(\Exception $e){
			return $this->exceptionApi($e);
		}
	}


}
