<?php

namespace App\Http\Controllers\admin\subasta;

use Illuminate\Support\Facades\Config;
use App\libs\FormLib;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Hces1;
use App\Models\V5\FgCaracteristicas_Hces1_Lang;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FxCli;
use App\Models\V5\FxSec;
use App\Models\V5\FgHces1;
use App\Models\V5\FgHces1_Lang;
use App\Models\V5\FgHces1Files;
use App\Support\ArrayHelper;

class AdminLoteConcursalController extends AdminLotController
{
	public function __construct($isRender = false)
	{
		parent::__construct($isRender);
		$this->resource_name = 'lotes_concursales';
		$this->parent_name = 'subastas_concursales';
	}

	public function edit($cod_sub, $ref_asigl0)
	{
		$render = request('render', false);

		$fgAsigl0 = FgAsigl0::joinFghces1Asigl0()
			->where([['ref_asigl0', $ref_asigl0], ['sub_asigl0', $cod_sub]])
			->first();

		if (!$fgAsigl0) {
			abort(404);
		}

		//Todos los lotes necesitan un idorigen para poder ser actualizados, así forzamos a que los tengan
		if(!$fgAsigl0->idorigen_asigl0){
			$newIdOrigen = "$cod_sub-$fgAsigl0->ref_asigl0";

			FgAsigl0::where([
				['ref_asigl0', $ref_asigl0],
				['sub_asigl0', $cod_sub]
			])->update(
				['idorigen_asigl0' => $newIdOrigen]
			);

			FgHces1::where([
				['num_hces1', $fgAsigl0->numhces_asigl0],
				['lin_hces1', $fgAsigl0->linhces_asigl0],
				['sub_hces1', $cod_sub],
			])->update(
				['idorigen_hces1' => $newIdOrigen]
			);
		}

		$images = $fgAsigl0->getImages();
		$files = FgHces1Files::getAllFilesByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);

		$lotes = FgAsigl0::select('ref_asigl0')
				->where('sub_asigl0', $cod_sub)
				->orderBy('ref_asigl0')
				->pluck('ref_asigl0')->toArray();

		$anterior = ArrayHelper::getAdjacentElementValue($lotes, $ref_asigl0, ArrayHelper::PREVIOUS);
		$siguiente = ArrayHelper::getAdjacentElementValue($lotes, $ref_asigl0, ArrayHelper::NEXT);

		$formulario = (object) $this->basicFormCreateFgAsigl0($fgAsigl0, $cod_sub);
		$formulario->id['reflot'] = FormLib::TextReadOnly('reflot', 0, $fgAsigl0->ref_asigl0);
		$formulario->id['idorigin'] = FormLib::TextReadOnly('idorigin', 0, old('idorigin', $fgAsigl0->idorigen_asigl0 ?? "$cod_sub-$fgAsigl0->ref_asigl0"));
		$formulario->files['files'] = FormLib::File('files[]', 0, 'multiple="true"');

		$lotTranslates = FgHces1_Lang::where([
			['num_hces1_lang', $fgAsigl0->numhces_asigl0],
			['lin_hces1_lang', $fgAsigl0->linhces_asigl0]
		])->get();

		$this->addTranslationsForm($formulario, $lotTranslates);

		$formulario->submit = FormLib::Submit('Actualizar', 'loteUpdate');

		$features = FgCaracteristicas::getAllFeatures();
		$featuresValues = FgCaracteristicas_Value::SelectAllForInput();
		$featuresHces1 = FgCaracteristicas_Hces1::getByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);
		$featuresHces1Lang = FgCaracteristicas_Hces1_Lang::getByLot($fgAsigl0->numhces_asigl0, $fgAsigl0->linhces_asigl0);

		$data = compact('formulario', 'fgAsigl0', 'cod_sub', 'images', 'files', 'anterior', 'siguiente', 'render', 'features', 'featuresValues', 'featuresHces1', 'featuresHces1Lang');

		return view('admin::pages.subasta.lotes_concursales.edit', $data);
	}

	protected function basicFormCreateFgAsigl0(FgAsigl0 $fgAsigl0, $cod_sub)
	{
		$propietario = null;
		if(!empty($fgAsigl0->prop_hces1)){
			$propietario = FxCli::select('RSOC_CLI')->where('COD_CLI', $fgAsigl0->prop_hces1)->first();
		}

		$form =  [
			'hiddens' => [
				'idauction' => FormLib::Hidden('idauction', 1, $cod_sub),
			],
			'id' => [
				'reflot' => FormLib::TextReadOnly('reflot', 1, old('reflot', $fgAsigl0->ref_asigl0), 'maxlength="999999999"'),
				'idorigin' => FormLib::TextReadOnly('idorigin', 1, old('idorigin', $fgAsigl0->idorigen_asigl0 ?? "$cod_sub-$fgAsigl0->ref_asigl0"), 'maxlength="30"'),
				'other_id' => FormLib::Text('other_id', 0, old('other_id', $fgAsigl0->loteaparte_hces1 ?? '')),
			],
			'imagen' => [
				'image' => FormLib::File('images[]', 0, 'multiple="true" accept=".jpg, .jpeg, .png"'),
			],
			'info' => [
				'owner' => FormLib::Select2WithAjax('owner', 0, old('owner', $fgAsigl0->prop_hces1), (!empty($propietario)) ? $propietario->rsoc_cli : '', route('client.list'), trans('admin-app.placeholder.owner')),
				'idsubcategory' => FormLib::select("idsubcategory", 1, $fgAsigl0->sec_hces1, FxSec::GetActiveFxSec()),
				'title' => FormLib::Text('title', 1, old('title', strip_tags($fgAsigl0->descweb_hces1))),
				'description' => FormLib::TextAreaTiny('description', 0, old('description', $fgAsigl0->desc_hces1))
			],
			'estados' => [
				'highlight' => FormLib::Select('highlight', 1, old('highlight', $fgAsigl0->destacado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'retired' => FormLib::Select('retired', 1, old('retired', $fgAsigl0->retirado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'close' => FormLib::Select('close', 1, old('close', $fgAsigl0->cerrado_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
				'soldprice' => FormLib::Select('soldprice', 1, old('soldprice', $fgAsigl0->remate_asigl0 ?? 'N'), ['N' => 'No', 'S' => 'Si'], '', '', false),
			],
			'fechas' => [
				'startdate' => FormLib::Date("startdate", 1, old('startdate', $fgAsigl0->fini_asigl0)),
				'starthour' => FormLib::Hour("starthour", 1, old('starthour', $fgAsigl0->hini_asigl0), 'step="1"'),
				'enddate' => FormLib::Date("enddate", 1, old('enddate', $fgAsigl0->ffin_asigl0)),
				'endhour' => FormLib::Hour("endhour", 1, old('endhour', $fgAsigl0->hfin_asigl0), 'step="1"')
			],
			'precios' => [
				'startprice' => FormLib::Int('startprice', 1, old('startprice', $fgAsigl0->impsalhces_asigl0 ?? 0)),
				'lowprice' => FormLib::Int('lowprice', 0, old('lowprice', $fgAsigl0->imptas_asigl0 ?? 0)),
				'highprice' => FormLib::Int('highprice', 0, old('highprice', $fgAsigl0->imptash_asigl0 ?? 0)),
				'reserveprice' => FormLib::Int('reserveprice', 0, old('reserveprice', $fgAsigl0->impres_asigl0 ?? 0)),
				'biddercommission' => FormLib::Int('biddercommission', 0, old('biddercommission', $fgAsigl0->comlhces_asigl0 ?? 0)),
				'ownercommission' => FormLib::Int('ownercommission', 0, old('ownercommission', $fgAsigl0->comphces_asigl0 ?? 0)),
			],
			'submit' => FormLib::Submit('Guardar', 'loteStore')
		];

		if($this->hasShowOption('extrai')) {
			$form['info']['extrainfo'] = FormLib::TextAreaTiny('extrainfo', 0, old('extrainfo', $fgAsigl0->descdet_hces1));
		}

		return $form;
	}


	private function hasShowOption($option) {
		$showOptions = Config::get('app.ShowEditLotOptions');
		$arrayOptions = explode(',', $showOptions);

		$arrayOptions = array_map(function($value) {
			return mb_strtolower(trim($value));
		}, $arrayOptions);

		return in_array(mb_strtolower($option), $arrayOptions);
	}
}
