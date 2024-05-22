<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\apilabel\AuctionController;
use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\Controller;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgAsigl1;
use App\Models\V5\FgCsub;
use App\Models\V5\FgHces1;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgSub;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminTestAuctions extends Controller
{
	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			if (strtoupper(session('user.usrw')) != 'SUBASTAS@LABELGRUP.COM') {
				abort(403, 'No tienes permisos para acceder a esta página');
			}
			return $next($request);
		});

		view()->share(['menu' => 'configuracion_admin']);
	}

	public function index()
	{
		$defaultAuctions = $this->getDefaultAuctions();
		$idAuctions = $defaultAuctions->pluck('idauction');
		$auctions = FgSub::whereIn('cod_sub', $idAuctions)->pluck('cod_sub');

		$firstLots = FgAsigl0::whereIn('sub_asigl0', $idAuctions)->get()->groupBy('sub_asigl0')->map(function ($lots) {
			return $lots->min('ref_asigl0');
		});

		$defaultAuctions->transform(function (array $defaultAuction) use ($auctions, $firstLots) {
			$defaultAuction['isCreated'] = $auctions->contains($defaultAuction['idauction']);
			$defaultAuction['isFirstLotCreated'] = $firstLots->has($defaultAuction['idauction']);
			return $defaultAuction;
		});

		return view('admin::pages.configuracion.init_auctions.index', ['auctions' => $defaultAuctions]);
	}

	public function createAuction($idauction)
	{
		$auction = $this->getDefaultAuctions()->where('idauction', $idauction)->first();
		if(!$auction) {
			return redirect()->back()->with('errors', ['Subasta no encontrada']);
		}

		DB::beginTransaction();
		$images = $this->getFakeImage();

		$isCreated = $this->createApiAuction($auction);
		if (!$isCreated) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al crear la subasta']);
		}

		try {
			$this->addImageToAuction($auction['idauction'], $images[0]->download_url);
		} catch (\Throwable $th) {
			Log::debug("Error al guardar la imagen", ["error" => $th->getMessage()]);
		}

		$isCreated = $this->createApiLots($auction);
		if (!$isCreated) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al crear los lotes']);
		}

		$isCreated = $this->addLotImages($auction, $images);
		if (!$isCreated) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al añadir las imágenes']);
		}

		DB::commit();
		return redirect()->back()->with('success', ['Subasta creada correctamente']);
	}

	public function resetAuction($idauction)
	{
		$auction = $this->getDefaultAuctions()->where('idauction', $idauction)->first();
		if(!$auction) {
			return redirect()->back()->with('errors', ['Subasta no encontrada']);
		}

		DB::beginTransaction();
		$images = $this->getFakeImage();

		$isReset = $this->resetApiAuction($auction);
		if (!$isReset) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al resetear la subasta']);
		}

		$this->resetLive($auction);

		try {
			$this->addImageToAuction($auction['idauction'], $images[0]->download_url);
		} catch (\Throwable $th) {
			Log::debug("Error al guardar la imagen", ["error" => $th->getMessage()]);
		}

		$isReset = $this->resetLotsStates($auction);
		if (!$isReset) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al resetear los lotes']);
		}

		$isReset = $this->updateApiLots($auction);
		if (!$isReset) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al actualizar los lotes']);
		}

		$isCreated = $this->addLotImages($auction, $images);
		if (!$isCreated) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al añadir las imágenes']);
		}

		DB::commit();
		return redirect()->back()->with('success', ['Subasta reseteada correctamente']);
	}

	public function createLots($idauction)
	{
		$auction = $this->getDefaultAuctions()->where('idauction', $idauction)->first();
		if(!$auction) {
			return redirect()->back()->with('errors', ['Subasta no encontrada']);
		}

		$isCreated = $this->createApiLots($auction);

		if (!$isCreated) {
			return redirect()->back()->with('errors', ['Error al crear los lotes']);
		}

		return redirect()->back()->with('success', ['Lotes creados correctamente']);
	}

	public function resetLots($idauction)
	{
		$auction = $this->getDefaultAuctions()->where('idauction', $idauction)->first();
		if(!$auction) {
			return redirect()->back()->with('errors', ['Subasta no encontrada']);
		}

		$isCreated = $this->resetLotsStates($auction);

		DB::beginTransaction();
		if (!$isCreated) {
			DB::rollBack();
			return redirect()->back()->with('errors', ['Error al resetear los lotes']);
		}

		DB::commit();
		return redirect()->back()->with('success', ['Lotes reseteados correctamente']);
	}

	public function createApiAuction($auction)
	{
		$response = (new AuctionController)->createAuction([$auction]);
		$responseJson = json_decode($response);

		return $responseJson->status != 'ERROR';
	}

	private function resetApiAuction($auction)
	{
		//Se eliminan los campos que no se pueden actualizar
		unset($auction['type']);
		unset($auction['visiblebids']);

		$response = (new AuctionController)->updateAuction([$auction]);
		$responseJson = json_decode($response);

		return $responseJson->status != 'ERROR';
	}

	private function resetLive($auction)
	{
		if($auction['type'] != Fgsub::TIPO_SUB_PRESENCIAL){
			return;
		}

		DB::table('WEB_SUBASTAS')->where('id_sub', $auction['idauction'])->delete();
	}

	private function createApiLots($auction)
	{
		$lots = [];
		foreach (range(1, 10) as $ref) {
			$lots[] = $this->lotObject($auction, $ref);
		}

		$response = (new LotController)->createLot($lots);
		$responseJson = json_decode($response);

		return $responseJson->status != 'ERROR';
	}

	private function updateApiLots($auction)
	{
		$lots = FgAsigl0::where('sub_asigl0', $auction['idauction'])->get();

		$lotsToApi = [];
		foreach ($lots as $lot) {
			$lotsToApi[] = $this->lotObject($auction, $lot->ref_asigl0);
		}

		$response = (new LotController)->updateLot($lotsToApi);
		$responseJson = json_decode($response);

		return $responseJson->status != 'ERROR';
	}

	private function addLotImages($auction, $images)
	{
		$lots = FgAsigl0::where('sub_asigl0', $auction['idauction'])->get();

		$lotImges = [];
		foreach ($lots as $key => $lot) {
			$lotImges[] = [
				'idoriginlot' => "{$auction['idauction']}-{$lot->ref_asigl0}",
				'order' => 0,
				'img' => $images[$key]->download_url,
			];
		}

		$response = (new ImgController)->createImg($lotImges);
		$responseJson = json_decode($response);

		return $responseJson->status != 'ERROR';

	}

	private function resetLotsStates($auction)
	{
		try {
			$this->deleteOrders($auction['idauction']);
			$this->deleteBids($auction['idauction']);
			$this->opernLotAndDeleteAwardsWhenNotInvoiced($auction['idauction']);
		} catch (\Throwable $th) {
			Log::debug("reset lot", ["error" => $th->getMessage(), "idauction" => $auction['idauction']]);
			return false;
		}

		return true;
	}

	private function lotObject($auction, $ref = 1)
	{
		//En caso de ser una subasta online se añade tiempo extra a cada lote
		$dateEnd = $auction['finishauction'];
		if($auction['type'] == Fgsub::TIPO_SUB_ONLINE){
			$addTime = Config::get('app.increment_endlot_online', 60) * $ref;
			$dateEnd = date('Y-m-d H:i:s', strtotime($dateEnd . " + {$addTime} seconds"));
		}

		return [
			'idorigin' => "{$auction['idauction']}-{$ref}",
			'idauction' => $auction['idauction'],
			'reflot' => $ref,
			'idsubcategory' => 'AB',
			'title' => "Lote {$ref}",
			'description' => "Descripción del lote {$ref}",
			'extrainfo' => '',
			'search' => '',
			'startprice' => $starPrice = $this->getRandomNumber(),
			'lowprice' => $lowPrice = $this->getRandomNumber($starPrice),
			'highprice' => $this->getRandomNumber($lowPrice),
			'reserveprice' => $this->getRandomNumber($starPrice),
			'close' => 'N',
			'buyoption' => 'S',
			'startdate' => date('Y-m-d', strtotime($auction['startauction'])),
			'enddate' => date('Y-m-d', strtotime($auction['finishauction'])),
			'starthour' => date('H:i:s', strtotime($auction['startauction'])),
			'endhour' => date('H:i:s', strtotime($auction['finishauction'])),
		];
	}

	private function getRandomNumber($min = 10, $max = 1000)
	{
		$random = rand($min, $max);
		$random -= $random % 10;
		return $random;
	}

	private function deleteOrders($cod_sub)
	{
		FgOrlic::where('sub_orlic', $cod_sub)->delete();
	}

	private function deleteBids($cod_sub)
	{
		FgAsigl1::where('sub_asigl1', $cod_sub)->delete();
		$this->resetPrice($cod_sub);
	}

	private function resetPrice($cod_sub)
	{
		FgHces1::where('sub_hces1', $cod_sub)->update([
			'implic_hces1' => 0
		]);
	}

	private function opernLotAndDeleteAwardsWhenNotInvoiced($cod_sub)
	{
		FgAsigl0::where('sub_asigl0', $cod_sub)->update([
			'cerrado_asigl0' => 'N'
		]);

		FgCsub::where([
			['sub_csub', '=', $cod_sub],
			['fac_csub', '=', 'N']
		])->delete();
	}

	private function getFakeImage()
	{
		$url = "https://picsum.photos/v2/list?limit=10&page=1";
		$contents = file_get_contents($url);
		$contents = mb_convert_encoding($contents, 'UTF-8');
    	return json_decode($contents);
	}

	private function addImageToAuction($idauction, $url)
	{
		#SIMULO UN ENCABEZADO POR SI TIENEN CAPADOS LOS SCRIPTS, así se piensa que entramos desde un navegador
		$context = stream_context_create(
			[
				"ssl" => [
					"verify_peer"      => false,
					"verify_peer_name" => false
				],
				"http" => [
					"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
				]
			]
		);

		$fileContent = file_get_contents($url, false, $context);
		$img = imagecreatefromstring($fileContent);

		$emp = Config::get('app.emp', '001');
		$destinationPath = public_path("img/AUCTION_{$emp}_{$idauction}.JPEG");

		imagejpeg($img, $destinationPath);

		$sessionPath = public_path("img/SESSION_{$emp}_{$idauction}_001.JPEG");
		imagejpeg($img, $sessionPath);
	}

	private function getDefaultAuctions()
	{
		return collect([
			$this->defaultAuctionState([
				'idauction' => 'APRES',
				'name' => 'Subasta Presencial',
			], [['name' => 'Subasta Presencial - Sesión 1']]),
			$this->defaultAuctionState([
				'idauction' => 'APRESAP',
				'name' => 'Subasta Presencial Abierta Pujas',
				'visiblebids' => Fgsub::SUBABIERTA_SUB_PUJAS,
			], [['name' => 'Presencial Abierta Pujas - Sesión 1']]),
			$this->defaultAuctionState([
				'idauction' => 'APRESAO',
				'name' => 'Subasta Presencial Abierta Ordenes',
				'visiblebids' => Fgsub::SUBABIERTA_SUB_ORDENES,
			], [['name' => 'Presencial Abierta Ordenes - Sesión 1']]),
			$this->defaultAuctionState([
				'idauction' => 'AONLINE',
				'name' => 'Subasta Online',
				'type' => Fgsub::TIPO_SUB_ONLINE,
			], [['name' => 'Subasta Online']]),
			$this->defaultAuctionState([
				'idauction' => 'AVD',
				'name' => 'Venta Directa',
				'type' => Fgsub::TIPO_SUB_VENTA_DIRECTA,
			], [['name' => 'Venta Directa']]),
		]);
	}

	private function defaultAuctionState($mergeAuctionParams = [], $mergeSessionParams = [])
	{
		$auction = [
			"idauction" => '',
			"name" => 'name',
			"type" => Fgsub::TIPO_SUB_PRESENCIAL,
			"status" => FgSub::SUBC_SUB_ADMINISITRADOR,
			"description" => 'description',
			"visiblebids" => Fgsub::SUBABIERTA_SUB_NO,
			"startauction" => date('Y-m-d H:i:s'),
			"finishauction" => date('Y-m-d H:i:s', strtotime('+2 months')),
			"startorders" => date('Y-m-d H:i:s'),
			"finishorders" => date('Y-m-d H:i:s', strtotime('+2 months')),
			"sessions" => [
				[
					'name' => 'name',
					'reference' => '001',
					'description' => 'description',
					'start' => date('Y-m-d H:i:s', strtotime('+1 month')),
					'finish' => date('Y-m-d H:i:s', strtotime('+2 months')),
					'startorders' => date('Y-m-d H:i:s'),
					'finishorders' => date('Y-m-d H:i:s', strtotime('+2 months')),
					'firstlot' => 1,
					'lastlot' => 999999,
				]
			]
		];

		foreach ($mergeSessionParams as $key => $mergeSessionParam) {
			$auction['sessions'][$key] = array_merge($auction['sessions'][$key], $mergeSessionParam);
		}

		$auction = array_merge($auction, $mergeAuctionParams);

		return $auction;
	}
}
