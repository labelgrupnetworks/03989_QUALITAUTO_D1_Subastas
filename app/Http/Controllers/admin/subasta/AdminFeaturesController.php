<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgCaracteristicas_Value;
use App\Models\V5\FgCaracteristicasLang;
use App\Support\Localization;
use Illuminate\Http\Request;

class AdminFeaturesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$features = FgCaracteristicas::with('allLanguages')
			->orderBy('orden_caracteristicas')
			->orderBy('id_caracteristicas')
			->get();

		return view('admin::pages.subasta.features.index', ['features' => $features]);
	}

	public function getFeatureValues($id)
	{
		$featureValues = FgCaracteristicas_Value::query()
			->where('idcar_caracteristicas_value', $id)
			->get();

		return view('admin::pages.subasta.features.values', ['featureValues' => $featureValues]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$formulario = [
			'name_caracteristicas' => FormLib::Text('name_caracteristicas', 1, '', 'required'),
			'orden_caracteristicas' => FormLib::Text('orden_caracteristicas', 1, '', 'required'),
		];
		foreach (Localization::getAvailableLocales() as $locale) {
			$formulario['feature_name_' . $locale] = FormLib::Text('feature_name_' . $locale, 1, '', 'required');
		}

		return view('admin::pages.subasta.features.create', ['formulario' => $formulario]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$feature = FgCaracteristicas::with('allLanguages')
			->where('id_caracteristicas', $id)
			->firstOrFail();

		$formulario = [
			'orden_caracteristicas' => FormLib::Text('orden_caracteristicas', 1, $feature->orden_caracteristicas, 'required'),
		];
		foreach (Localization::getAvailableLocales() as $locale) {
			$formulario['feature_name_' . $locale] = FormLib::Text('feature_name_' . $locale, 1, $feature->allLanguages->where('lang_caracteristicas_lang', Localization::getLanguageComplete($locale))->first()->name_caracteristicas_lang ?? '', 'required');
		}

		return view('admin::pages.subasta.features.edit', ['formulario' => $formulario, 'id' => $id]);
	}

	public function store(Request $request)
	{
		$request->validate([
			'name_caracteristicas' => 'required|string|max:255',
			'orden_caracteristicas' => 'required|integer',
		]);

		$newId = FgCaracteristicas::max('id_caracteristicas') + 1;

		FgCaracteristicas::create([
			'id_caracteristicas' => $newId,
			'name_caracteristicas' => $request->input('name_caracteristicas'),
			'orden_caracteristicas' => $request->input('orden_caracteristicas'),
			'filtro_caracteristicas' => 'N', // Default value, can be changed later
			'value_caracteristicas' => 'N' // Default value, can be changed later
		]);

		$dataToStore = [];
		foreach (Localization::getAvailableLocales() as $locale) {
			$dataToStore[] = [
				'id_caracteristicas_lang' => $newId,
				'emp_caracteristicas_lang' => config('app.emp'),
				'lang_caracteristicas_lang' => Localization::getLanguageComplete($locale),
				'name_caracteristicas_lang' => $request->input('feature_name_' . $locale, '')
			];
		}

		FgCaracteristicasLang::insert($dataToStore);

		return redirect()->route('admin.features.index')->with('success', 'Característica creada con éxito.');
	}

	/**
	 * Por el momento solo se permitira actualizar los idiomas de las caracteristicas
	 */
	public function update(Request $request, $id)
	{
		FgCaracteristicasLang::where('id_caracteristicas_lang', $id)
			->delete();

		$dataToStore = [];
		foreach (Localization::getAvailableLocales() as $locale) {
			$dataToStore[] = [
				'id_caracteristicas_lang' => $id,
				'emp_caracteristicas_lang' => config('app.emp'),
				'lang_caracteristicas_lang' => Localization::getLanguageComplete($locale),
				'name_caracteristicas_lang' => $request->input('feature_name_' . $locale, '')
			];
		}

		FgCaracteristicasLang::insert($dataToStore);

		return redirect()->route('admin.features.index')->with('success', 'Característica actualizada con éxito.');
	}
}
