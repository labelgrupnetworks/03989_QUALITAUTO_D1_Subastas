<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\libs\FormLib;
use App\Models\V5\FgHces1Files;

class AdminLotFilesController extends Controller
{
	function show(FgHces1Files $fgHces1File)
	{
		return response()->file($fgHces1File->storage_path);
	}

	function create(Request $request, $num_hces1, $lin_hces1)
	{
		$formulario = $this->getFilesForm($request, new FgHces1Files(), true);
		return view('admin::pages.subasta.lot_files._create', ['formulario' => $formulario, 'num_hces1' => $num_hces1, 'lin_hces1' => $lin_hces1])->render();
	}

	function store(Request $request, $num_hces1, $lin_hces1)
	{
		//@todo validaciones

		$files = $request->file('file_hces1_files');
		$order = FgHces1Files::withNumhcesAndLinhces($num_hces1, $lin_hces1)->max('order_hces1_files');

		foreach ($files as $file) {
			$nameFile = count($files) > 1 || empty($request->input('name_hces1_files', null))
				? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
				: $request->input('name_hces1_files');

			$fgHces1File = new FgHces1Files([
				'numhces_hces1_files' => $num_hces1,
				'linhces_hces1_files' => $lin_hces1,
				'lang_hces1_files' => null,
				'path_hces1_files' => null,
				'external_url_hces1_files' => null,
				'name_hces1_files' => $nameFile,
				'description_hces1_files' => null,
				'order_hces1_files' => ++$order,
				'image_hces1_files' => null,
				'is_active_hces1_files' => $request->input('is_active_hces1_files', 'N'),
				'permission_hces1_files' => $request->input('permission_hces1_files', 'N')
			]);

			FgHces1Files::uploadFile($file, $fgHces1File);
		}

		$files = FgHces1Files::getAllFilesByLot($num_hces1, $lin_hces1);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	function edit(FgHces1Files $fgHces1File)
	{
		$formulario = $this->getFilesForm(new Request(), $fgHces1File, false);
		return view('admin::pages.subasta.lot_files._edit', ['formulario' => $formulario, 'id' => $fgHces1File->id_hces1_files])->render();
	}

	function update(Request $request, FgHces1Files $fgHces1File)
	{
		//@todo validaciones
		$file = $request->file('file_hces1_files');

		$nameFile = $file ? $file->getClientOriginalName() : $fgHces1File->name_hces1_files;
		$newName = $request->input('name_hces1_files', $nameFile);

		$fgHces1File->name_hces1_files = $newName;
		$fgHces1File->is_active_hces1_files = $request->input('is_active_hces1_files', 'N');
		$fgHces1File->permission_hces1_files = $request->input('permission_hces1_files', FgHces1Files::PERMISSION_EMPTY);

		if ($fgHces1File->isDirty()) {
			$fgHces1File->save();
		}

		//si se ha subido un archivo, se elimina el anterior y se sube el nuevo
		if ($file) {
			FgHces1Files::deleteFile($fgHces1File);
			FgHces1Files::uploadFile($file, $fgHces1File);
		}

		$files = FgHces1Files::getAllFilesByLot($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	function updateSelection(Request $request)
	{
		$ids = $request->input('ids', []);

		foreach ($ids as $id) {
			$fgHces1File = FgHces1Files::find($id);

			$fgHces1File->is_active_hces1_files = $request->input('is_active_hces1_files', 'N');
			$fgHces1File->permission_hces1_files = $request->input('permission_hces1_files', FgHces1Files::PERMISSION_EMPTY);

			if ($fgHces1File->isDirty()) {
				$fgHces1File->save();
			}
		}

		$files = FgHces1Files::getAllFilesByLot($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	function updateOrder(Request $request)
	{
		$files = $request->input('order', []);
		foreach ($files as $order => $id) {
			FgHces1Files::where('id_hces1_files', $id)->update(['order_hces1_files' => $order + 1]);
		}

		return response()->json(['success' => true]);
	}

	function destroy(FgHces1Files $fgHces1File)
	{
		FgHces1Files::deleteFile($fgHces1File);
		$fgHces1File->delete();

		$files = FgHces1Files::getAllFilesByLot($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	function deleteSelection(Request $request)
	{
		$ids = $request->input('ids', []);
		foreach ($ids as $id) {
			$fgHces1File = FgHces1Files::find($id);
			FgHces1Files::deleteFile($fgHces1File);
			$fgHces1File->delete();
		}

		$files = FgHces1Files::getAllFilesByLot($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	function status(FgHces1Files $fgHces1File)
	{
		$fgHces1File->is_active_hces1_files = $fgHces1File->is_active_hces1_files == 'S' ? 'N' : 'S';
		$fgHces1File->save();

		$files = FgHces1Files::getAllFilesByLot($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);
		return view('admin::pages.subasta.lot_files._table_rows', ['files' => $files])->render();
	}

	private function getFilesForm(Request $request, FgHces1Files $file, $isMultiple)
	{
		$multiple = $isMultiple ? 'multiple="true"' : '';
		$formulario = [
			'file_hces1_files' => FormLib::File('file_hces1_files[]', 1, $multiple),
			'name_hces1_files' => FormLib::Text('name_hces1_files', 1, old('name_hces1_files', $file->name_hces1_files)),
			'is_active_hces1_files' => FormLib::Select('is_active_hces1_files', true, old('is_active_hces1_files', $file->is_active_hces1_files), ['S' => 'Si', 'N' => 'No'], '', '', false),
			'permission_hces1_files' => FormLib::Select('permission_hces1_files', true, old('permission_hces1_files', $file->permission_hces1_files), FgHces1Files::getPermissions(), '', '', false)
		];

		return $formulario;
	}
}
