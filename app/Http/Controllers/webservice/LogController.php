<?php
namespace App\Http\Controllers\webservice;
use App\libs\LogLib;
class LogController  extends WebServiceController
{

	# LOGS QUE SE OPTIENEN DE LA TABLA DE LOGS Y DE LA ORIGINAL

	public function logAuction($startDate,$endDate){
		return  LogLib::getLog("FgSub", "sub", $startDate, $endDate);

	}

	public function logSession($startDate,$endDate){
		return  LogLib::getLog("AucSessions", "sessions", $startDate, $endDate, true);
	}


   public function logLot($startDate,$endDate){
		return  LogLib::getLog("FgAsigl0", "asigl0", $startDate, $endDate);
   }

   public function logInfolot($startDate,$endDate){

		return  LogLib::getLog("FgHces1", "hces1", $startDate, $endDate);

	}

	public function logBid($startDate,$endDate){

		return  LogLib::getLog("FgAsigl1", "asigl1", $startDate, $endDate);

   }

   public function logOrder($startDate,$endDate){

		return  LogLib::getLog("FgOrlic", "orlic", $startDate, $endDate);

   }

   public function logUsr($startDate,$endDate){

		return  LogLib::getLog("FsUsr", "usr", $startDate, $endDate);

	}

	public function logCliweb($startDate,$endDate){

		return  LogLib::getLog("FxCliWeb", "cliweb", $startDate, $endDate);

	}

   # FIN LOGS QUE SE OPTIENEN DE LA TABLA DE LOGS Y DE LA ORIGINAL

}
