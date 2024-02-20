<?php



namespace App\libs;
use Config;

use Session;
use App\Models\V5\Web_Keywords_Search;
use App\Models\V5\Web_Seo_Events;
use App\Models\V5\Web_Seo_Visits;
use App\Models\V5\FgOrtsec1;
use App\Http\Controllers\UserController;
class SeoLib {



	static function sessionsVars(){
		$UTM_SOURCE = "";
		$UTM_MEDIUM = "";
		$UTM_CAMPAIGN ="";
		$UTM_TYPE = "";
		$referer = "";

		if(Session::has('UTM')){
			$UTM_SOURCE = Session::get("UTM.source");
			$UTM_MEDIUM = Session::get("UTM.medium");
			$UTM_CAMPAIGN = Session::get("UTM.campaign");

			$referer = Session::get("UTM.referer");
		}

		#tipo de trafico
		if(!empty(Session::get("UTM.type"))){
			if(Session::get("UTM.type") == "R" || Session::get("UTM.type") == "r"){
				$UTM_TYPE = "REFERRAL";
			}else{
				#Si el tipo no es referral es que es de pago, ya que solo llevaria UTM si es de pago o referral
				$UTM_TYPE = "PAID";
			}
		}else{
			if(empty($UTM_SOURCE) && empty($UTM_MEDIUM) && empty($UTM_CAMPAIGN) ){
				if(empty($referer)){
					$UTM_TYPE = "DIRECT";
				}else{
					$UTM_TYPE = "ORGANIC";
				}

			}else{
				#si hemos llegado a este punto es que han pasado parametros UTM pero no el type por lo que es de pago
				$UTM_TYPE = "PAID";
			}
		}
		$codUser = null;
		if (Session::has('user')){
			$codUser =Session::get('user.cod');
		}
		return compact("UTM_SOURCE", "UTM_MEDIUM", "UTM_CAMPAIGN", "UTM_TYPE", "referer", "codUser" );
	}

	static function saveVisit($sub = null, $category = null, $section = null, $ref = null){
		if(\Config::get("app.seoVisit")){
			$vars = SeoLib::sessionsVars();
			try{
				$userController = new UserController();
				$ip = $userController->getUserIP();


					if( empty($category) && !empty($section)){

						$ortsec = FgOrtsec1::select("lin_ortsec1")->where("SEC_ORTSEC1", $section)->first();
						if(!empty($ortsec) ){
							$category = $ortsec->lin_ortsec1;
						}
					}
					$insertData=[
						"EMP_SEO_VISITS" =>  Config::get("app.emp"),
						"USER_SEO_VISITS" => $vars["codUser"],
						"SUB_SEO_VISITS" => $sub,
						"FAMILY_SEO_VISITS" => $category,
						"SUBFAMILY_SEO_VISITS" => $section,
						"REF_SEO_VISITS" => $ref,
						"TYPE_SEO_VISITS" => substr($vars["UTM_TYPE"],0,20),
						"REFERER_SEO_VISITS" => substr($vars["referer"],0,255),
						"UTM_SOURCE_SEO_VISITS" => substr($vars["UTM_SOURCE"],0,255),
						"UTM_MEDIUM_SEO_VISITS" => substr($vars["UTM_MEDIUM"],0,255),
						"UTM_CAMPAIGN_SEO_VISITS" => substr($vars["UTM_CAMPAIGN"],0,255),
						"IP_SEO_VISITS" => substr($ip,0,255),
						"DATE_SEO_VISITS" => date("Y-m-d")
					];
					Web_Seo_Visits::updateOrInsert([
						"EMP_SEO_VISITS" =>  Config::get("app.emp"),
						"USER_SEO_VISITS" => $vars["codUser"],
						"SUB_SEO_VISITS" => $sub,
						"FAMILY_SEO_VISITS" => $category,
						"SUBFAMILY_SEO_VISITS" => $section,
						"REF_SEO_VISITS" => $ref,
						"IP_SEO_VISITS" => substr($ip,0,255),
						"DATE_SEO_VISITS" => date("Y-m-d")
					],$insertData);

			}catch(\Illuminate\Database\QueryException $e){
				\Log::error($e);
			}

		}


	}

	static function saveEvent($event){
		$vars = SeoLib::sessionsVars();


		try{
			Web_Seo_Events::insert([
				"EMP_SEO_EVENTS" =>  Config::get("app.emp"),
				"USER_SEO_EVENTS" => $vars["codUser"],
				"EVENT_SEO_EVENTS" => substr($event,0,20),
				"TYPE_SEO_EVENTS" => substr($vars["UTM_TYPE"],0,20),
				"REFERER_SEO_EVENTS" => substr($vars["referer"],0,255),
				"UTM_SOURCE_SEO_EVENTS" => substr($vars["UTM_SOURCE"],0,255),
				"UTM_MEDIUM_SEO_EVENTS" => substr($vars["UTM_MEDIUM"],0,255),
				"UTM_CAMPAIGN_SEO_EVENTS" => substr($vars["UTM_CAMPAIGN"],0,255),
				"DATE_SEO_EVENTS" => date("Y-m-d H:i:s")
			]);

		}catch(\Illuminate\Database\QueryException $e){
			\Log::error($e);
		}



	}



}
