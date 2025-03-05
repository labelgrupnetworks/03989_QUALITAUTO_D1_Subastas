<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\View\View;
use App\Models\V5\FgSub;
use App\libs\FormLib;
use App\Http\Requests\admin\UpdateSubastasPut;
use App\Models\V5\AucSessions;
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgPujasSub;
use App\Models\V5\FgSub_lang;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminSubastaConcursalController extends AdminSubastaGenericController
{
	public function __construct()
	{
		parent::__construct();
        $this->isGeneric = false;
		$this->resource_name = 'subastas_concursales';
    }

	public function create(): View
	{
		$fgSub = new FgSub();
		$formulario = (object) $this->basicFormCreateFgSub($fgSub);
		return view('admin::pages.subasta.subastas_concursales.create', compact('formulario', 'fgSub'));
	}

	public function edit($cod_sub)
	{
		$fgSub = FgSub::where('cod_sub', $cod_sub)->first();

		if (!$fgSub) {

			return redirect()->back()->withErrors('not exist')
				->withInput();
		}

		//sessiones
		$aucSessions = AucSessions::select('"id_auc_sessions"', '"reference"', '"name"', '"init_lot"', '"end_lot"')->where('"auction"', $cod_sub)->get();

		$formulario = (object) $this->basicFormCreateFgSub($fgSub);
		$formulario->textos['cod_sub'] = FormLib::TextReadOnly('cod_sub', 0, $fgSub->cod_sub);
		$formulario->submit = FormLib::Submit('Actualizar', 'subastaUpdate');

		//formulario de idiomas
		$fgSub_lang = FgSub_lang::select('lang_sub_lang', 'des_sub_lang', 'descdet_sub_lang', 'webmetat_sub_lang', 'webmetad_sub_lang', 'webfriend_sub_lang')->where('COD_SUB_LANG', $cod_sub)->get();
		$this->addFgSub_lanfForm($formulario, $fgSub_lang);

		//formulario escalados
		$fgPujasSubs = FgPujasSub::where('SUB_PUJASSUB', $cod_sub)->orderBy('LIN_PUJASSUB')->get();
		$this->addFgPujasSubForm($formulario, $fgPujasSubs);

		//Archivos de sesiones
		$aucSessionsFilesController = new AdminAucSessionsFilesController();
		$aucSessionsFilesController->addAucSessionsFilesForm($formulario, $aucSessions, $cod_sub);
		$aucSessionsFiles = AucSessionsFiles::where('"auction"', $cod_sub)->get();

		return view('admin::pages.subasta.subastas_concursales.edit', compact('fgSub', 'aucSessions', 'formulario', 'aucSessionsFiles'));
	}

	public function update(UpdateSubastasPut $request, $cod_sub)
	{

		$fgSub = FgSub::where('cod_sub', $cod_sub)->first();
		if (!$fgSub) {
			return redirect()->back()->withErrors('not exist')
				->withInput();
		}

		try {

			DB::beginTransaction();

			//subasta
			//FgSub::where('cod_sub', $cod_sub)->update($request->except(['_method', '_token', 'cod_sub', 'image_sub']));

			$dataToUpdate = [
				'des_sub' => $request->des_sub,
				'descdet_sub' => $request->descdet_sub,
				'dfec_sub' => $request->dfec_sub,
				'dhora_sub' => $request->dhora_sub,
				'hfec_sub' => $request->hfec_sub,
				'hhora_sub' => $request->hhora_sub,
				'tipo_sub' => $request->tipo_sub,
				'subc_sub' => $request->subc_sub,
				'subabierta_sub' => $request->subabierta_sub,
				'opcioncar_sub' => $request->opcioncar_sub,
				'webmetat_sub' => $request->webmetat_sub,
				'webmetad_sub' => $request->webmetad_sub,
				'webfriend_sub' => $request->webfriend_sub,
			];

			if(Config::get('app.use_panel_sub')){
				$dataToUpdate['panel_sub'] = $request->panel_sub;
			}

			FgSub::where('cod_sub', $cod_sub)->update($dataToUpdate);

			//session
			AucSessions::where([
				['"auction"', $cod_sub],
				['"reference"', '001'],
			])->update([
				'"start"' => new DateTime($request->dfec_sub . ' ' . $request->dhora_sub), //new DateTime(request("start")),
				'"end"' => new DateTime($request->hfec_sub . ' ' . $request->hhora_sub),
				'"name"' => $request->des_sub,
				'"description"' => mb_substr($request->descdet_sub, 0, 1000,'UTF-8')
			]);

			//Actualizar o crear idiomas
			$languages = array_diff(Config::get('app.locales'), ['es' => 'Español']);
			if (!empty($languages)) {
				$this->createOrSaveFgSub_lang($request, $cod_sub, $languages);
			}

			//archivos de sesion
			if (request()->hasFile('ficheroAdjunto')) {
				$adminAucSessionsFilesController = new AdminAucSessionsFilesController();
				$adminAucSessionsFilesController->store($request);
			}

			$this->createFgPujasSub($request, $cod_sub);

			DB::commit();

			return redirect(route("$this->resource_name.edit", $cod_sub))->with(['success' => array(trans('admin-app.title.updated_ok'))]);
		} catch (\Throwable $th) {
			DB::rollBack();
			Log::error($th);
			return back()->withErrors(['errors' => [$th->getMessage()]])->withInput();
		}
	}

	public function updateImage(Request $request)
	{
		$fgSub = FgSub::where('COD_SUB', $request->cod_sub)->first();

		if (!$fgSub) {
			return redirect()
				->back()
				->withErrors('not exist')
				->withInput();
		}

		return $this->saveFgSubImage($request->file('imagen_sub'), $request->cod_sub, true, true);
	}



}
