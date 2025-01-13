<?php

namespace App\Services\admin\lot;

use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\apilabel\LotController;
use App\Models\V5\FgAsigl0;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class AdminLotService
{

	public function createLotWithApi(array $lotData)
	{
		$json = (new LotController())->createLot([$lotData]);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			Log::error('Error creating lot with API', ['error' => $result]);
			throw new Exception("Error creating lot: $json");
		}
	}

	public function updateLotWithApi(array $lotData)
	{
		$json = (new LotController())->updateLot([$lotData]);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			Log::error('Error updating lot with API', ['error' => $result]);
			throw new Exception("Error updating lot: $json");
		}

		$this->updateUpdatedAt($lotData['idauction'], [$lotData['reflot']]);
	}

	public function uploadImagesWithApi(array $images, string $lotOriginId, bool $isEncode = false): void
	{
		$itemImages = [];

		foreach ($images as $key => $image) {
			$item = ($isEncode)
				? ['img64' => $image]
				: ['img64' => base64_encode(Image::make($image->path())->encode()->encoded)];

			$itemImages[] = [
				'idoriginlot' => $lotOriginId,
				'order' => $key,
			] + $item;
		}

		if (empty($itemImages)) {
			return;
		}

		$json = (new ImgController())->createImg($itemImages);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			throw new Exception("Error uploading images: $json");
		}

		return;
	}

	/**
	 * @param array<UploadedFile> $files
	 */
	public function uploadFilesByLot($lot, array $files)
	{
		$numHces = $lot->num_hces1;
		$linHces = $lot->lin_hces1;
		$this->uploadFilesByHces($numHces, $linHces, $files);
	}

	/**
	 * @param string $numHces
	 * @param string $linHces
	 * @param array<UploadedFile> $files
	 */
	public function uploadFilesByHces(string $numHces, string $linHces, array $files)
	{
		$emp = Config::get('app.emp');
		$relativePath = "/$emp/$numHces/$linHces/files/";
		$path = getcwd() . "/files/$relativePath";

		if (!is_dir(str_replace("\\", "/", $path))) {
			mkdir(str_replace("\\", "/", $path), 0775, true);
		}

		foreach ($files as $file) {
			$newfile = str_replace("\\", "/", $path . '/' . $file->getClientOriginalName());
			copy($file->getPathname(), $newfile);
		}
	}

	/**
	 * @param string $cod_sub
	 * @param array $refLots
	 */
	private function updateUpdatedAt(string $codSud, array $refLots)
	{
		if(empty($refLots)) {
			return;
		}

		$userSession = Session::get('user');

		$update = [
			'usr_update_asigl0' => strval($userSession['usrw']),
			'date_update_asigl0' => date('Y-m-d H:i:s'),
		];

		FgAsigl0::where('sub_asigl0', $codSud)
			->whereIn('ref_asigl0', $refLots)
			->update($update);
	}
}
