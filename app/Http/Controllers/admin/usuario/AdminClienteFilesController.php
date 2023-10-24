<?php

namespace App\Http\Controllers\admin\usuario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminClienteFilesController extends Controller
{
	/**
	 * Returns an array of files in the client directory.
	 * The array contains the following information for each file: name, size in KB, last modified date in dd/mm/yyyy hh:mm:ss format, and extension.
	 * Returns an empty array if the client directory does not exist.
	 */
	public function getClientFiles($codCli)
	{
		$storage = Storage::disk('client');
		$files = $storage->files("$codCli/files");

		//get url and methadata of files
		$files = array_map(function ($file) use ($storage) {
			return (object) $this->getMetadataToStorageFile($storage, $file);
		}, $files);

		return $files;
	}

	private function getMetadataToStorageFile($storage, $file)
	{
		$cod_cli = explode('/', $file)[0];
		$fileName = basename($file);
		return [
			'link' => $storage->url($file),
			'unlink' => route('clientes.files.destroy', ['cod_cli' => $cod_cli , 'name' => $fileName]),
			'name' => $fileName,
			'size_kb' => round($storage->size($file) / 1024, 2) . ' KB',
			'last_modified_human' => Carbon::createFromTimestamp($storage->lastModified($file))->format('d/m/Y H:i:s'),
			'extension' => pathinfo($file, PATHINFO_EXTENSION),
		];
	}

	public function show($cod_cli, $name)
	{
		$storage = Storage::disk('client');

		if (!$storage->exists("$cod_cli/files/$name")) {
			return abort(404);
		}

		$file = $storage->get("$cod_cli/files/$name");

		return response($file, 200)->header('Content-Type', $storage->mimeType("$cod_cli/files/$name"));
	}

	public function storeDni(Request $request, $cod_cli)
	{
		$files = $request->file();
		if(empty($files)){
			return response()->json(['status' => 'error', 'message' => 'No se han seleccionado ficheros válidos']);
		}

		//files is array, get first key name
		$nameFile = array_keys($files)[0];
		$userController = new UserController;
		$userController->saveDni($request, $cod_cli, $nameFile);

		$dnisPaths = $userController->getCIFImages($cod_cli);
		$dnis = array_map(function($dni){
			return [
				'path' => $dni,
				'filename' => basename($dni),
				'base64' => base64_encode(file_get_contents($dni)),

			];
		}, $dnisPaths);

		return response()->json(['dnis' => $dnis, 'status' => 'success']);
	}

	public function store(Request $request, $cod_cli)
	{
		$files = ToolsServiceProvider::validFiles($request->file('files'));

		if(!(new User)->storeFiles($files, $cod_cli)){
			return response()->json(['status' => 'error', 'message' => 'No se han seleccionado ficheros válidos']);
		}

		//return getMetadata in json
		$newFiles = $this->getClientFiles($cod_cli);

		return response()->json(['files' => $newFiles, 'status' => 'success']);
	}

	public function destroy($cod_cli, $name)
	{
		$storage = Storage::disk('client');

		if (!$storage->exists("$cod_cli/files/$name")) {
			return response()->json(['status' => 'error', 'message' => 'No existe el fichero']);
		}

		$storage->delete("$cod_cli/files/$name");

		$newFiles = $this->getClientFiles($cod_cli);
		return response()->json(['files' => $newFiles, 'status' => 'success']);
	}
}
