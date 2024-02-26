<?php



namespace App\libs;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\V5\Web_Keywords_Search;
use App\Models\V5\Web_Seo_Events;
use App\Models\V5\Web_Seo_Visits;
use App\Models\V5\FgOrtsec1;
use App\Http\Controllers\UserController;

class SeoLib
{



	static function sessionsVars()
	{
		$UTM_SOURCE = "";
		$UTM_MEDIUM = "";
		$UTM_CAMPAIGN = "";
		$UTM_TYPE = "";
		$referer = "";

		if (Session::has('UTM')) {
			$UTM_SOURCE = Session::get("UTM.source");
			$UTM_MEDIUM = Session::get("UTM.medium");
			$UTM_CAMPAIGN = Session::get("UTM.campaign");

			$referer = Session::get("UTM.referer");
		}

		#tipo de trafico
		if (!empty(Session::get("UTM.type"))) {
			if (Session::get("UTM.type") == "R" || Session::get("UTM.type") == "r") {
				$UTM_TYPE = "REFERRAL";
			} else {
				#Si el tipo no es referral es que es de pago, ya que solo llevaria UTM si es de pago o referral
				$UTM_TYPE = "PAID";
			}
		} else {
			if (empty($UTM_SOURCE) && empty($UTM_MEDIUM) && empty($UTM_CAMPAIGN)) {
				if (empty($referer)) {
					$UTM_TYPE = "DIRECT";
				} else {
					$UTM_TYPE = "ORGANIC";
				}
			} else {
				#si hemos llegado a este punto es que han pasado parametros UTM pero no el type por lo que es de pago
				$UTM_TYPE = "PAID";
			}
		}
		$codUser = null;
		if (Session::has('user')) {
			$codUser = Session::get('user.cod');
		}
		return compact("UTM_SOURCE", "UTM_MEDIUM", "UTM_CAMPAIGN", "UTM_TYPE", "referer", "codUser");
	}

	static function saveVisit($sub = null, $category = null, $section = null, $ref = null)
	{

		$userAgent = request()->userAgent();

		if (Config::get('app.env') == "testing" || !$userAgent) {
			return;
		}

		#Si solo queremos guardar visitas únicas, solo guardaremos si el resto de campos está nulo
		if (Config::get("app.seoVisit") || (Config::get("app.seoUniqueVisit") && empty($sub) && empty($category) && empty($section) && empty($ref))) {

			if (!SeoLib::isRobotAgent($userAgent)) {

				$vars = SeoLib::sessionsVars();
				try {

					$userController = new UserController();
					$ip = $userController->getUserIP();

					#guardamos el user agent para analizarlo y ver is hay que bloquear mas
					if (Config::get("app.logUserAgent")) {
						\Log::info("User Agent: " . $userAgent. " ip: " . $ip);
					}


					if (empty($category) && !empty($section)) {

						$ortsec = FgOrtsec1::select("lin_ortsec1")->where("SEC_ORTSEC1", $section)->first();
						if (!empty($ortsec)) {
							$category = $ortsec->lin_ortsec1;
						}
					}
					$insertData = [
						"EMP_SEO_VISITS" =>  Config::get("app.emp"),
						"USER_SEO_VISITS" => $vars["codUser"],
						"SUB_SEO_VISITS" => $sub,
						"FAMILY_SEO_VISITS" => $category,
						"SUBFAMILY_SEO_VISITS" => $section,
						"REF_SEO_VISITS" => $ref,
						"TYPE_SEO_VISITS" => substr($vars["UTM_TYPE"], 0, 20),
						"REFERER_SEO_VISITS" => substr($vars["referer"], 0, 255),
						"UTM_SOURCE_SEO_VISITS" => substr($vars["UTM_SOURCE"], 0, 255),
						"UTM_MEDIUM_SEO_VISITS" => substr($vars["UTM_MEDIUM"], 0, 255),
						"UTM_CAMPAIGN_SEO_VISITS" => substr($vars["UTM_CAMPAIGN"], 0, 255),
						"IP_SEO_VISITS" => substr($ip, 0, 255),
						"DATE_SEO_VISITS" => date("Y-m-d")
					];
					Web_Seo_Visits::updateOrInsert([
						"EMP_SEO_VISITS" =>  Config::get("app.emp"),
						"USER_SEO_VISITS" => $vars["codUser"],
						"SUB_SEO_VISITS" => $sub,
						"FAMILY_SEO_VISITS" => $category,
						"SUBFAMILY_SEO_VISITS" => $section,
						"REF_SEO_VISITS" => $ref,
						"IP_SEO_VISITS" => substr($ip, 0, 255),
						"DATE_SEO_VISITS" => date("Y-m-d")
					], $insertData);
				} catch (\Illuminate\Database\QueryException $e) {
					Log::error($e);
				}
			}
		}
	}

	static function saveEvent($event)
	{
		$vars = SeoLib::sessionsVars();


		try {
			Web_Seo_Events::insert([
				"EMP_SEO_EVENTS" =>  Config::get("app.emp"),
				"USER_SEO_EVENTS" => $vars["codUser"],
				"EVENT_SEO_EVENTS" => substr($event, 0, 20),
				"TYPE_SEO_EVENTS" => substr($vars["UTM_TYPE"], 0, 20),
				"REFERER_SEO_EVENTS" => substr($vars["referer"], 0, 255),
				"UTM_SOURCE_SEO_EVENTS" => substr($vars["UTM_SOURCE"], 0, 255),
				"UTM_MEDIUM_SEO_EVENTS" => substr($vars["UTM_MEDIUM"], 0, 255),
				"UTM_CAMPAIGN_SEO_EVENTS" => substr($vars["UTM_CAMPAIGN"], 0, 255),
				"DATE_SEO_EVENTS" => date("Y-m-d H:i:s")
			]);
		} catch (\Illuminate\Database\QueryException $e) {
			Log::error($e);
		}
	}


	static function isRobotAgent($agent)
	{
		$lista_robots = array("Zabbix","ImagesiftBot","ABCdatos BotLink", "Acme.Spider", "Ahoy! The Homepage Finder", "Alkaline", "Anthill", "Walhello appie", "Arachnophilia", "Arale", "Araneo", "AraybOt", "ArchitextSpider", "Aretha", "ARIADNE", "arks", "ASpider (Associative Spider)", "ATN Worldwide", "Atomz.com Search Robot", "AURESYS", "BackRub", "bayspider", "BBot", "Big Brother", "Bjaaland", "BlackWidow", "Die Blinde Kuh", "Bloodhound", "Borg-Bot", "BoxSeaBot", "bright.net caching robot", "BSpider", "CACTVS Chemistry Spider", "Calif", "Cassandra", "Digimarc Marcspider/CGI", "Checkbot", "ChristCrawler.com", "churl", "cIeNcIaFiCcIoN.nEt", "CMC/0.01", "Collective", "Combine System", "Conceptbot", "ConfuzzledBot", "CoolBot", "Web Core / Roots", "XYLEME Robot", "Internet Cruiser Robot", "Cusco", "CyberSpyder Link Test", "CydralSpider", "Desert Realm Spider", "DeWeb(c) Katalog/Index", "DienstSpider", "Digger", "Digital Integrity Robot", "Direct Hit Grabber", "DNAbot", "DownLoad Express", "DragonBot", "DWCP (Dridus' Web Cataloging Project)", "e-collector", "EbiNess", "EIT Link Verifier Robot", "ELFINBOT", "Emacs-w3 Search Engine", "ananzi", "esculapio", "Esther", "Evliya Celebi", "nzexplorer", "FastCrawler", "Fluid Dynamics Search Engine robot", "Felix IDE", "Wild Ferret Web Hopper #1, #2, #3", "FetchRover", "fido", "H�m�h�kki", "KIT-Fireball", "Fish search", "Fouineur", "Robot Francoroute", "Freecrawl", "FunnelWeb", "gammaSpider, FocusedCrawler", "gazz", "GCreep", "GetBot", "GetURL", "Golem", "Googlebot", "Grapnel/0.01 Experiment", "Griffon", "Gromit", "Northern Light Gulliver", "Gulper Bot", "HamBot", "Harvest", "havIndex", "HI (HTML Index) Search", "Hometown Spider Pro", "Wired Digital", "ht", "HTMLgobble", "Hyper-Decontextualizer", "iajaBot", "IBM_Planetwide", "Popular Iconoclast", "Ingrid", "Imagelock", "IncyWincy", "Informant", "InfoSeek Robot 1.0", "Infoseek Sidewinder", "InfoSpiders", "Inspector Web", "IntelliAgent", "I, Robot", "Iron33", "Israeli-search", "JavaBee", "JBot Java Web Robot", "JCrawler", "AskJeeves", "JoBo Java Web Robot", "Jobot", "JoeBot", "The Jubii Indexing Robot", "JumpStation", "image.kapsi.net", "Katipo", "KDD-Explorer", "Kilroy", "KO_Yappo_Robot", "LabelGrabber", "larbin", "legs", "Link Validator", "LinkScan", "LinkWalker", "Lockon", "logo.gif Crawler", "Lycos", "Mac WWWWorm", "Magpie", "marvin/infoseek", "Mattie", "MediaFox", "MerzScope", "NEC-MeshExplorer", "MindCrawler", "mnoGoSearch search engine software", "moget", "MOMspider", "Monster", "Motor", "MSNBot", "Muncher", "Muninn", "Muscat Ferret", "Mwd.Search", "Internet Shinchakubin", "NDSpider", "NetCarta WebMap Engine", "NetMechanic", "NetScoop", "newscan-online", "NHSE Web Forager", "Nomad", "The NorthStar Robot", "ObjectsSearch", "Occam", "HKU WWW Octopus", "OntoSpider", "Openfind data gatherer", "Orb Search", "Pack Rat", "PageBoy", "ParaSite", "Patric", "pegasus", "The Peregrinator", "PerlCrawler 1.0", "Phantom", "PhpDig", "PiltdownMan", "Pimptrain.com's robot", "Pioneer", "html_analyzer", "Portal Juice Spider", "PGP Key Agent", "PlumtreeWebAccessor", "Poppi", "PortalB Spider", "psbot", "GetterroboPlus Puu", "The Python Robot", "Raven Search", "RBSE Spider", "Resume Robot", "RoadHouse Crawling System", "RixBot", "Road Runner", "Robbie the Robot", "ComputingSite Robi/1.0", "RoboCrawl Spider", "RoboFox", "Robozilla", "Roverbot", "RuLeS", "SafetyNet Robot", "Scooter", "Search.Aus-AU.COM", "Sleek", "SearchProcess", "Senrigan", "SG-Scout", "ShagSeeker", "Shai'Hulud", "Sift", "Simmany Robot Ver1.0", "Site Valet", "Open Text Index Robot", "SiteTech-Rover", "Skymob.com", "SLCrawler", "Inktomi Slurp", "Smart Spider", "Snooper", "Solbot", "Spanner", "Speedy Spider", "spider_monkey", "SpiderBot", "Spiderline Crawler", "SpiderMan", "SpiderView(tm)", "Spry Wizard Robot", "Site Searcher", "Suke", "suntek search engine", "Sven", "Sygol", "TACH Black Widow", "Tarantula", "tarspider", "Tcl W3 Robot", "TechBOT", "Templeton", "TeomaTechnologies", "TitIn", "TITAN", "The TkWWW Robot", "TLSpider", "UCSD Crawl", "UdmSearch", "UptimeBot", "URL Check", "URL Spider Pro", "Valkyrie", "Verticrawl", "Victoria", "vision-search", "void-bot", "Voyager", "VWbot", "The NWI Robot", "W3M2", "WallPaper (alias crawlpaper)", "the World Wide Web Wanderer", "w@pSpider by wap4.com", "WebBandit Web Spider", "WebCatcher", "WebCopy", "webfetcher", "The Webfoot Robot", "Webinator", "weblayers", "WebLinker", "WebMirror", "The Web Moose", "WebQuest", "Digimarc MarcSpider", "WebReaper", "webs", "Websnarf", "WebSpider", "WebVac", "webwalk", "WebWalker", "WebWatch", "Wget", "whatUseek Winona", "WhoWhere Robot", "Weblog Monitor", "w3mir", "WebStolperer", "The Web Wombat", "The World Wide Web Worm", "WWWC Ver 0.2.5", "WebZinger", "XGET", "Nederland.zoek");


		//Determina si la cadena que llega es un robot
		foreach ($lista_robots as $robot) {
			if (strpos($agent, $robot)  !== false) {
				return true;
			}
		}
		return false;
	}
}
