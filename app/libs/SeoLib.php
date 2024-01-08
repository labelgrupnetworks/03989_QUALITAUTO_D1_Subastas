<?php



namespace App\libs;
use Config;
use Request;

use App\Models\V5\Web_Keywords_Search;

class SeoLib {
    //put your code here
    static function KeywordsSearch()
	{

		if(Config::get("app.keywords_search")){
			$referer = Request::header('referer');
			if(!empty($referer)){
				$url = parse_url($referer);
				$host= $url["host"];
				$vars = explode("&", $url["query"]);
				$keywords=array();
				$host = str_replace("www.","",$host);
				$host = str_replace(".com","",$host);

				if($host == "google" || $host == "bing" || $host == "http://subastas.test" ){
					foreach($vars as $var){
						$posicion = strpos($var, "q=");
						if ($posicion !== false){
							$keywords = explode("+", str_replace("q=","",$var));

							foreach ($keywords as $index=>$keyword){
								#quitamos las palabras sin importancia que tengan 2 o menos caracteres
								if(strlen($keyword)>=3){
									Web_Keywords_Search::insert(
										["engine_keywords_search" => $host,
										"word_keywords_search" => $keyword,
										"date_keywords_search" => date("Y-m-d H:i:s"),
										"emp_keywords_search" => Config::get("app.main_emp")]
									);
								}
								break;
							}
						}
					}
				}
			}
		}

    }

}
