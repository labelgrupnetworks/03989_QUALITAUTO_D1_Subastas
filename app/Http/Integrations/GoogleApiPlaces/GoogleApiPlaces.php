<?php

namespace App\Http\Controllers\Integrations\GoogleApiPlaces;

use DateTime;
use GuzzleHttp;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class GoogleApiPlaces
{
	public $apiKey;
	public $placeId;
	public $lang;
	public $path;
	public $fields;

	function __construct()
	{
		$this->apiKey = Config::get('app.google_api_key');
		$this->placeId = Config::get('app.google_place_id');
		$this->lang = Config::get('app.locale');
		$this->fields = "reviews";
		$this->path = public_path() . "/files/" .  Config::get('app.theme') . "_reviews_" . Config::get('app.locale') . ".txt";
	}

	function getReviews($daysToReload)
	{
		$responseJson = json_decode($this->readData($daysToReload), true);

		if (!empty($responseJson['error_message'])) {
			Log::error("Error de datos recuperados de google", $responseJson);
			return false;
		}

		return [
			'rating' => $responseJson["result"]["rating"],
			'reviews' => $responseJson["result"]["reviews"],
			'user_rating_total' => $responseJson["result"]["user_ratings_total"],
			'url_write_review' => "https://search.google.com/local/writereview?placeid=$this->placeId"
		];
	}

	function getResults($daysToReload)
	{
		$responseJson = json_decode($this->readData($daysToReload), true);
		return $responseJson["result"];
	}


	private function readData($daysToReload)
	{
		try {
			$linesFile = file($this->path);
		} catch (\Throwable $th) { //archivo no existe o no tiene permisos

			if (!$this->createOrUpdateFile()) {  //si no existe tenemos que crearlo y seguir, si no permiso salir y error
				return false;
			};
			$linesFile = file($this->path);
		}

		$date = preg_replace("/[\r\n|\n|\r]+/", "", $linesFile[0]);
		$dateobj = DateTime::createFromFormat("d/m/Y", $date);
		$interval = $dateobj->diff(new DateTime("now"));

		//comparar fecha y si diferencia superior a dias marcados, recargar datos
		if ($interval->days > $daysToReload) {
			$this->createOrUpdateFile();
			$linesFile = file($this->path);
		}

		//leer datos
		return $linesFile[1];
	}

	private function createOrUpdateFile()
	{
		$reloadData = $this->reloadData();
		//$reloadData = $this->testData();

		if (!$reloadData) {
			return false;
		}

		$this->saveData($reloadData, $this->path);
		return true;
	}

	private function reloadData()
	{

		$client = new GuzzleHttp\Client();
		//$res = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&language=$lang&fields=$this->fields&key=$apiKey");

		try {
			$res = $client->request('GET', "https://maps.googleapis.com/maps/api/place/details/json?place_id=$this->placeId&language=$this->lang&key=$this->apiKey");
		} catch (\Throwable $th) {
			Log::error("error al conectar con api google");
			Log::error($th);
			return false;
		}


		if ($res->getStatusCode() != 200) {
			Log::error("error al conectar con api google");

			return false;
		}

		return $res->getBody();
	}

	private function saveData($data = null, $path = null)
	{

		$data = $data ?? $this->testData();

		try {
			$file = fopen($path, "w");
			//vamos añadiendo el contenido
			fwrite($file, date("d/m/Y") . PHP_EOL);
			fwrite($file, json_encode(json_decode($data, true)) . PHP_EOL);
			fclose($file);
			return true;
		} catch (\Throwable $th) {
			Log::error("error al guardar archivo reviews");
			return false;
		}
	}

	function testData()
	{
		return '{ "html_attributions" : [], "result" : { "reviews" : [ { "author_name" : "domingo sanz rausell", "author_url" : "https://www.google.com/maps/contrib/109547260683051532328/reviews", "language" : "es", "profile_photo_url" : "https://lh5.ggpht.com/-ziFfeF5wJoU/AAAAAAAAAAI/AAAAAAAAAAA/UHfUqJvtK1s/s128-c0x00000000-cc-rp-mo/photo.jpg", "rating" : 5, "relative_time_description" : "Hace 4 meses", "text" : "EXCELENTE CASA DE SUBASTAS, SERIA, CON MUCHA CLASE Y CON PLENAS GARANTIAS AL 100 %.", "time" : 1587466807 }, { "author_name" : "Carlos Limón", "author_url" : "https://www.google.com/maps/contrib/117244254878005575717/reviews", "language" : "es", "profile_photo_url" : "https://lh6.ggpht.com/-rX16vaz3HOQ/AAAAAAAAAAI/AAAAAAAAAAA/5wNEkWDuYK0/s128-c0x00000000-cc-rp-mo/photo.jpg", "rating" : 5, "relative_time_description" : "Hace 7 meses", "text" : "Gran casa de subastas que mejoran día a día. Trato muy humano y personal. Muy competentes. Atienden cualquier problema, dando soluciones siempre satisfactorias. Ya podían fijarse en ella otra de las casas de subastas con mas \"prestigio\" de Madrid que solo se preocupa de ganar dinero. Sois fenómenos.", "time" : 1578138740 }, { "author_name" : "Numismatica Jesús Adame", "author_url" : "https://www.google.com/maps/contrib/105097597826367759854/reviews", "language" : "es", "profile_photo_url" : "https://lh3.ggpht.com/-H6WEF0iGtEs/AAAAAAAAAAI/AAAAAAAAAAA/DdPjYYXm8xs/s128-c0x00000000-cc-rp-mo/photo.jpg", "rating" : 5, "relative_time_description" : "Hace 6 meses", "text" : "Sin duda alguna, una de las mejores casas de subasta. Buen trato, buenas subastas, cercanía... Excelente.", "time" : 1582027763 }, { "author_name" : "H. I.D. Ultzama", "author_url" : "https://www.google.com/maps/contrib/110073366765781079320/reviews", "language" : "es", "profile_photo_url" : "https://lh4.ggpht.com/-L86AFvvsSoQ/AAAAAAAAAAI/AAAAAAAAAAA/6IcY7hImU6A/s128-c0x00000000-cc-rp-mo/photo.jpg", "rating" : 5, "relative_time_description" : "Hace 7 meses", "text" : "La casa de subastas más innovadora con diferencia en el panorama nacional.\nSiempre buscando opciones para seguir creciendo, ánimo y a seguir así.\nEl trato personal que he tenido con ellos espectacular, grandes profesionales, mejores personas !", "time" : 1579272641 }, { "author_name" : "Miguel Juste", "author_url" : "https://www.google.com/maps/contrib/102811480608303066434/reviews", "language" : "es", "profile_photo_url" : "https://lh6.ggpht.com/-me37b7JIT5I/AAAAAAAAAAI/AAAAAAAAAAA/opUGv2h4bCg/s128-c0x00000000-cc-rp-mo/photo.jpg", "rating" : 5, "relative_time_description" : "Hace 10 meses", "text" : "Un gran equipo. Se esmeran diariamente por seguir creciendo.\nInnovadores y con muchas ganas de hacer las cosas bien.\nTodo esfuerzo tiene una recompensa.\nA medio plazo se vera reflejado.\n\nMiguel Juste.", "time" : 1570560736 } ] }, "status" : "OK" }';
	}
}
