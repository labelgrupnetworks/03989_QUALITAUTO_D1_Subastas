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
			\Log::info("keywords_search ACTIVO");
			//$referer = Request::header('referer');


			if(!empty($_SERVER['HTTP_REFERER'])){
				\Log::info("referr: ". $_SERVER['HTTP_REFERER']);
				$url = parse_url($_SERVER['HTTP_REFERER']);

				$host= $url["host"];
				\Log::info("url: ". $host);
				if(!empty($url["query"])){
					\Log::info("query: ". $url["query"]);
					$vars = explode("&", $url["query"]);
					$keywords=array();
					$host = str_replace("www.","",$host);
					#cogemos el dominio sin extension
					$explodeHost = explode(".",$host);
					if(count($explodeHost)>0){
						$host = $explodeHost[0];
					}
					\Log::info("host: ". $host);
					if($host == "google" || $host == "bing" ){

						foreach($vars as $var){
							\Log::info("var: ". $var);
							$posicion = strpos($var, "q=");
							if ($posicion !== false){
								$keywords = explode("+", str_replace("q=","",$var));

								foreach ($keywords as $keyword){
									\Log::info("keyword: ". $keyword);
									#quitamos las palabras sin importancia que tengan 2 o menos caracteres
									if(strlen($keyword)>=3){
										Web_Keywords_Search::insert(
											["engine_keywords_search" => $host,
											"word_keywords_search" => $keyword,
											"date_keywords_search" => date("Y-m-d H:i:s"),
											"emp_keywords_search" => Config::get("app.emp")]
										);
									}

								}
								#solo nos interesa la variable "q"
								break;
							}

						}
					}
				}
			}
		}

    }

}
