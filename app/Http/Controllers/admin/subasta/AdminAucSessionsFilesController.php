<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\AucSessions;
use App\Models\V5\AucSessionsFiles;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AdminAucSessionsFilesController extends Controller
{

	/*index, create, show, edit, store, update, destroy*/

	/**
	 * Guardar con item
	 * */
	function store()
	{

		//obtener ficheros adjuntos
		$fichero = null;
		$imagen = '';

		if (request()->hasFile('ficheroAdjunto') && request('typefile') != 5) {
			$fichero = request()->file('ficheroAdjunto');
		} else if(request('typefile') != 5) {
			return redirect()->back()
				->with(['errors' => [0 => 'Necesita adjuntar un fichero']]);
		}

		if (request()->hasFile('imagenFile')) {
			$imagen = request()->file('imagenFile');
		}

		//datos de session
		if(request()->has('id_auc_sessions')){
			$where = [
				'"id_auc_sessions"' => request('id_auc_sessions')
			];
		}
		else{
			$where = [
				'"reference"' => request('reference'),
				'"auction"' => request('auction')
			];
		}


		$aucSession = AucSessions::where($where)->first();

		//guardamos datos de fichero en tabla
		$id = AucSessionsFiles::withoutGlobalScope('emp')->max('"id"');
		empty($id) ? $id = 1 : $id++;

		$pathFiles = getcwd() . '/files/' . Config::get("app.emp") . '/' . $aucSession->auction;
		$nameFile = $aucSession->reference . '_' . $id;

		if (request('typefile') != 5) {

			$nameFileWithExt = $nameFile . '.' . $fichero->extension();
			$pathFilesDb = '\\' . Config::get("app.emp") . '\\' . $aucSession->auction . '\\' . $nameFileWithExt;

			if (!is_dir(str_replace("\\", "/", $pathFiles))) {
				mkdir(str_replace("\\", "/", $pathFiles), 0775, true);
			}

			$newfile = str_replace("\\", "/", $pathFiles . '/' . $nameFileWithExt);

			//$_FILES['ficheroAdjunto']['tmp_name']
			//$fichero->getClientOriginalName()

			copy($fichero->getPathname(), $newfile);
		}

		if (!empty($imagen)) {
			if ($this->guardarImagen($imagen, $pathFiles, $nameFile)) {
				$imagen = '\\files\\' . Config::get("app.emp") . '\\' . $aucSession->auction . '\\img\\' . $nameFile . '.JPEG';
			}
		}

		$dataCreate = [
			'"id"' => $id,
			'"auction"' => $aucSession->auction,
			'"reference"' => $aucSession->reference,
			'"description"' =>  request('description'),
			'"path"' => $pathFilesDb ?? '',
			'"type"' => request('typefile'),
			'"order"' => request('order'),
			'"lang"' => request('lang'),
			'"img"' => $imagen,
			'"url"' => request('url', '')
		];

		AucSessionsFiles::create($dataCreate);

		if ('error' == 'ERROR') {
			return redirect()->back()
				->with(['errors' => [0 => 'Ha sucedido un error']]);
		}

		return redirect()->back()
			->with(['success' => [0 => 'Archivo añadido correctamente']]);
	}

	/**
	 * Eliminar item
	 * */
	function destroy()
	{

		$auction = request('auction');
		$reference = request('reference');
		$id = request('idFile');


		if (empty($auction) || empty($reference) || empty($id)) {
			return redirect()->back()
				->with(['errors' => [0 => 'Ha sucedido un error']]);
		}

		//Eliminar o no fichero...
		//unlink(str_replace("\\", "/", getcwd() . '/files/' . $info['subasta'] . '/' . $info['item']));

		AucSessionsFiles::where('"id"', $id)->where('"auction"', $auction)->where('"reference"', $reference)->delete();
		return redirect()->back()
			->with(['success' => [0 => 'Fichero eliminado']]);
	}

	public function guardarImagen(UploadedFile $fichero, $pathFile, $nameFile)
	{

		$pathInicial = $pathFile . "\\img";
		$path = str_replace("\\", "/", $pathInicial . "\\");

		if (!is_dir($path)) {
			mkdir($path, 0775, true);
		}

		if (!empty($fichero)) {

			$extension = $fichero->extension();

			$size = getimagesize($fichero->getPathname());
			if ($size[0] > 2000) {
				$w = 2000;
				$h = $size[1] * 2000 / $size[0];
			} else {
				$w = $size[0];
				$h = $size[1];
			}

			if ($extension == "png") {
				$src_image = imagecreatefrompng($fichero->getPathname());
			} elseif ($extension == "jpg" || $extension == "jpeg") {
				$src_image = imagecreatefromjpeg($fichero->getPathname());
			}

			$pathImage = $pathInicial . "\\" .  $nameFile . ".JPEG";

			$dst_image = imagecreatetruecolor($w, $h);
			$blanco = imagecolorallocate($src_image, 255, 255, 255);
			imagefill($dst_image, 0, 0, $blanco);
			imagecopy($dst_image, $src_image, 0, 0, 0, 0, $size[0], $size[1]);
			imagejpeg($dst_image, str_replace("\\", "/", $pathImage));

			return true;
		}
		return false;
	}

	public function addAucSessionsFilesForm($formulario, $aucSessions, $cod_sub)
	{

		$typeFiles = AucSessionsFiles::TYPE_FILES;


		$langsFile = array();
		foreach (Config::get("app.locales") as $lang => $langComplete) {
			$langsFile[Config::get("app.language_complete")[$lang]] = $langComplete;
		}

		$sessions = $aucSessions->pluck('reference', 'id_auc_sessions')->toArray();

		$formulario->archivos = [
			'auc_sessions_files' => FormLib::File("ficheroAdjunto", 0),
			'reference' => FormLib::Select('id_auc_sessions', 1, array_keys($sessions, '001')[0], $sessions, '', '', false),
			'description' => FormLib::Text('description', 0),
			'order' => FormLib::Int('order', 0, 1),
			'lang' => FormLib::Select('lang', 1, 'es-ES', $langsFile, '', '', false),
			'typeFile' => FormLib::Select('typefile', 1, 1, $typeFiles, '', '', false),
			'url' => FormLib::Text('url', 0),
			'img' => FormLib::File("img", 0),
		];
	}
}
