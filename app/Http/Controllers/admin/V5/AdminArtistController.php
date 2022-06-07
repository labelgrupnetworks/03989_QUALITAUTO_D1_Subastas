<?php

namespace App\Http\Controllers\admin\V5;

use Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\V5\LotListController;
use App\Http\Requests\admin\UpdateArtistPut;
use App\Models\V5\Web_Artist;
use App\Models\V5\Web_Artist_Article;


use App\Models\V5\FgCaracteristicas_Value;
use App\libs\FormLib;
use App\Models\V5\Web_Artist_Lang;


class AdminArtistController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	var $numElements = 40;
	var $folderPath = "";

	public function __construct(){
		$this->folderPath = "/img/autores/";
	}

	public function index(Request $request)
	{
		$webArtist = Web_Artist::query();

		if ($request->id_artist) {
			$webArtist->where('id_artist', '=', $request->id_artist);
		}

		if ($request->name_artist) {
			$lotlist = new LotListController();
			$description = $lotlist->clearWords($request->name_artist, \Tools::getLanguageComplete(Config::get("app.locale")));
			$words = explode(" ",$description);
			$search="";
			$and="";
			foreach($words as $key => $word ){
				#ponemos el comodin de busqueda % para que busque cualquier texto despues de la palabra y dolar $ para que busque por stem (raiz, origen de una palabra)
				$search .=$and. " $".$word."% ";
				$and=" AND ";
			}
			#Es necesario poner las dos pipes || para concatenar la variable si no da error  número/nombre de variable no válid
			$webArtist  =  $webArtist->whereraw(" CATSEARCH(name_artist,'<query><textquery grammar=\"context\">' || ? || '</textquery></query>',null) >0", [ $search]);


		}

		$webArtist = $webArtist->select('id_artist,name_artist, active_artist')
			->orderBy("name_artist", "asc")
			->paginate($this->numElements);



		$formulario = (object)[
			'id_artist' => FormLib::Text('id_artist', 0, $request->id_artist, '', trans("admin-app.fields.artist.id_artist")),
			'name_artist' => FormLib::Text('name_artist', 0, $request->name_artist, '', trans("admin-app.fields.artist.name_artist")),

		];

		return view('admin::pages.contenido.artist.index', compact('webArtist', 'formulario'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

		$artistNewFields = \Config::get("app.camposNuevosArtista");

		$webArtist = new Web_Artist();
		$webArtist->id_artist = old('id_artist', '');
		$webArtist->name_artist = old('name_artist', '');
		$webArtist->info_artist = old('info_artist', '');

		if (\Str::contains($artistNewFields,'phone')) {
			#si existe phone en el config se crean datos en el campo
			$webArtist->phone_artist = old('phone_artist', '');
		}
		if (\Str::contains($artistNewFields,'email')) {
			#si existe email en el config se crean datos en el campo
			$webArtist->email_artist = old('email_artist', '');
		}
		if (\Str::contains($artistNewFields,'idexternal')) {
			#si existe idexternal en el config se crean datos en el campo
			$webArtist->idexternal_artist = old('idexternal_artist', '');
		}

		$webArtist->biography_artist = old('biography_artist', '');
		$webArtist->extra_artist = old('extra_artist', '');

		# Este condicional hace que se muestre o no se muestre el selector de FgCaracteristicas_Value
		if (!\Config::get('app.artistNameToFeature')) {
			#	Si tiene valor nulo ejecutará el selector en el formulario artista
			$fgcarcateriticas = FgCaracteristicas_Value::where("IDCAR_CARACTERISTICAS_VALUE", \Config::get("app.ArtistCode"))
								->leftjoin("WEB_ARTIST","EMP_ARTIST = EMP_CARACTERISTICAS_VALUE AND ID_ARTIST = ID_CARACTERISTICAS_VALUE")
								->where("ID_ARTIST")
								->orderby("value_caracteristicas_value")->SelectInput();

			# En el selector se añade el elemento required
			$artistasCaracteristicas = ['id_artist' => FormLib::Select('id_artist', 0, '', $fgcarcateriticas, 'required', '')];
			$formweb = array_merge($artistasCaracteristicas,  $this->formWeb_Artist($webArtist));
		} else {
			#	Si no tiene valor nulo se creará la caracteristica en conjunto con el artista
			$formweb = $this->formWeb_Artist($webArtist);
		}

		#usamos el web config que identifica que id de categoria corresponde a Autores
		$formulario = (object) $formweb;

		if(isMultilanguage()){
			$formulario = $this->addTranslationsForm($formulario, null);
		}

		return view('admin::pages.contenido.artist.create', compact('formulario', 'webArtist'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UpdateArtistPut $request)
	{
		$vars = $request->all();

		# Si el Config no es nulo creará el los campos del Artista en FgCaracteristicas_Value
		if (\Config::get('app.artistNameToFeature')) {
			FgCaracteristicas_Value::insert([
				'EMP_CARACTERISTICAS_VALUE' => \Config::get('app.emp'),
				'IDCAR_CARACTERISTICAS_VALUE' => \Config::get("app.ArtistCode"),
				'VALUE_CARACTERISTICAS_VALUE' => $vars['name_artist']
			]);

			$id_artist = FgCaracteristicas_Value::select('ID_CARACTERISTICAS_VALUE')
			->where('VALUE_CARACTERISTICAS_VALUE', $vars['name_artist'])->first();

			$vars['id_artist'] = $id_artist->id_caracteristicas_value;
		}

		$this->createImage($request->file('img_artist'), $vars['id_artist']);
		unset($vars['img_artist']);
		unset($vars['info_artist_lang']);
		unset($vars['biography_artist_lang']);
		unset($vars['extra_artist_lang']);
		$test = Web_Artist::create($vars);


		if(isMultilanguage()){
			$this->saveLangFields($request, $vars['id_artist']);
		}

		return redirect( route('artist.edit', $vars['id_artist']))->with('success', ['Artista creado correctamente']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $cod_artist
	 * @return \Illuminate\Http\Response
	 */
	public function edit($cod_artist)
	{
		$artistNewFields = \Config::get("app.camposNuevosArtista");
		$webArtist = Web_Artist::where('id_artist', $cod_artist)->firstOrFail();

		//$webArtist->id_artist = old('id_artist', $webArtist->id_artist);
		$webArtist->name_artist = old('name_artist', $webArtist->name_artist);
		$webArtist->info_artist = old('info_artist', $webArtist->info_artist);

		if (\Str::contains($artistNewFields,'phone')) {
			#si existe phone en el config se edita el campo
			$webArtist->phone_artist = old('phone_artist', $webArtist->phone_artist);
		}
		if (\Str::contains($artistNewFields,'email')) {
			#si existe email en el config se edita el campo
			$webArtist->email_artist = old('email_artist', $webArtist->email_artist);
		}
		if (\Str::contains($artistNewFields,'idexternal')) {
			#si existe idexternal en el config se edita el campo
			$webArtist->idexternal_artist = old('idexternal_artist', $webArtist->idexternal_artist);
		}

		$webArtist->biography_artist = old('biography_artist',  $webArtist->biography_artist);
		$webArtist->extra_artist = old('extra_artist',  $webArtist->extra_artist);
		$id_artist = ['id_artist' => FormLib::hidden('id_artist', 1, '', $webArtist->id_artist, '', '')];

		$formulario = (object) $this->formWeb_Artist($webArtist) ;

		if(isMultilanguage()){
			$formulario = $this->addTranslationsForm($formulario, $cod_artist);
		}

		$imgPath = $this->folderPath. $webArtist->id_artist.".jpg";
		return view('admin::pages.contenido.artist.edit', compact('formulario', 'webArtist','imgPath', 'articles'));

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateArtistPut $request, $id_artist)
	{
		$webArtist = Web_Artist::where('id_artist', $id_artist)->firstOrFail();
		$vars = $request->validated();

		$vars["active_artist"] = $request->active_artist?? 0;

		Web_Artist::where('id_artist', $id_artist)->update($vars);

		$this->createImage($request->file('img_artist'), $id_artist );

		# Si se edita el nombre del artista se edita también en la tabla FgCaracteristicas_Value
		if (\Config::get('app.artistNameToFeature')) {
			FgCaracteristicas_Value::where('ID_CARACTERISTICAS_VALUE', $vars['id_artist'])->update([
				'VALUE_CARACTERISTICAS_VALUE' => $vars['name_artist']
			]);
		}

		if(isMultilanguage()){
			$this->saveLangFields($request, $id_artist);
		}

		return redirect(route('artist.index'))
				->with(['success' =>array(trans('admin-app.title.updated_ok')) ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id_artist
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id_artist)
	{
		Web_Artist_Article::where("IDARTIST_ARTIST_ARTICLE", $id_artist)->delete();
		Web_Artist::where('id_artist', $id_artist)->delete();
		# Si el Config no es nulo lo que hará es borrar la característica con el Artista
		if (\Config::get('app.artistNameToFeature')) {
			FgCaracteristicas_Value::where('ID_CARACTERISTICAS_VALUE', $id_artist)->delete();
		}
	}


	private function formWeb_Artist(Web_Artist $webArtist)
	{
		$artistNewFields = \Config::get("app.camposNuevosArtista");
		$fields = [
			'name_artist' => FormLib::Text('name_artist', 1, $webArtist->name_artist, '', ''),
			'info_artist' => FormLib::Text('info_artist', 0, $webArtist->info_artist, '', ''),
		];

		if (\Str::contains($artistNewFields,'phone')) {
			#si existe phone en el config se añade el campo al array
			$fields['phone_artist'] =  FormLib::Text('phone_artist', 0, $webArtist->phone_artist, '', '');
		}
		if (\Str::contains($artistNewFields,'email')) {
			#si existe email en el config se añade el campo al array
			$fields['email_artist'] = FormLib::Text('email_artist', 0, $webArtist->email_artist, '', '');
		}
		if (\Str::contains($artistNewFields,'idexternal')) {
			#si existe idexternal en el config se añade el campo al array
			$fields['idexternal_artist'] = FormLib::Text('idexternal_artist', 0, $webArtist->idexternal_artist, '', '');
		}

		$fields =array_merge($fields, [
			'biography_artist' => FormLib::TextAreaTiny('biography_artist', 0, $webArtist->biography_artist, '', ''),
			'extra_artist' => FormLib::TextAreaTiny('extra_artist', 0, $webArtist->extra_artist, '', ''),
			'active_artist' => FormLib::Bool('active_artist', 0, $webArtist->active_artist, '1', '')
		]);

		return $fields;
	}

	private function createImage($image,  $id_artist){
		\Log::info("id_artist $id_artist");

		if(!empty($image)){
			$public_path= public_path($this->folderPath);
			if (!file_exists($public_path))
            {
				mkdir($public_path, 0775, true);
				chmod($public_path,0775);
				chgrp($public_path,"www-data");
			}
			$destinationPath = $public_path . $id_artist.".jpg";
			$img = \Image::make($image->path());

			if($img->width() > 2000){
				$img->resize(2000, null, function ($constraint) {
					$constraint->aspectRatio();
				});
			}
			$img->save($destinationPath);
		}else{
			\Log::info("no hay imagen");
		}
	}

	public function createArticle(){
		Web_Artist_Article::create(["idartist_artist_article" => request("id_artist")]);
	}

	public function deleteArticle(){
		Web_Artist_Article::where("id_artist_article", request("id_article"))->delete();
	}


	public function updateArticles(Request $request){

		$vars = $request->all();


		foreach($vars["url_article"] as $key=>$url){

			$update=array("url_artist_article" => $url, "title_artist_article" => $vars["title_article"][$key]);

			Web_Artist_Article::where('id_artist_article', $key)->update($update);

		}

		return array("status" => "success");

	}

	public function createArticles(Request $request){
		$articles = Web_Artist_Article::where('idartist_artist_article',  $request->id_artist)->get();

		return view('admin::pages.contenido.artist.articles', compact('articles'));
	}



	public function loadArticles(Request $request){
		$articles = Web_Artist_Article::where('idartist_artist_article',  $request->id_artist)->orderby("id_artist_article")->get();

		return view('admin::pages.contenido.artist.articles', compact('articles'));
	}

	public function activar(Request $request)
	{

		$id = $request->input('id', '0');
		$activo = $request->input('activo', '0');

		Web_Artist::where('EMP_ARTIST', Config::get("app.emp"))
			->where('ID_ARTIST', $id)
			->update([
				'ACTIVE_ARTIST' => $activo
			]);
	}

	protected function addTranslationsForm($formulario, $cod_artist = null)
	{
		$languages = array_keys(config('app.locales'));

		$formulario->translates = [];
		$artist_langs = collect([]);

		if($cod_artist){
			$artist_langs = Web_Artist_Lang::where('id_artist_lang', $cod_artist)->get();
		}

		foreach ($languages as $lang) {

			if ($lang == 'es') {
				continue;
			}

			$language_complete = config("app.language_complete.$lang");

			if (!$artist_langs->isEmpty()) {
				$artist_lang = $artist_langs->where('lang_artist_lang', $language_complete)->first();
			} else {
				$artist_lang = new Web_Artist_Lang();
			}

			$formulario->translates[$lang] = [
				'info_artist_lang' => FormLib::Text("info_artist_lang[$language_complete]", 0, old("info_artist_lang[$language_complete]", $artist_lang->info_artist_lang ?? ''), '', ''),
				'biography_artist_lang' => FormLib::TextAreaTiny("biography_artist_lang[$language_complete]", 0, old("biography_artist_lang[$language_complete]", $artist_lang->biography_artist_lang ?? ''), '', '', 300, true),
				'extra_artist_lang' => FormLib::TextAreaTiny("extra_artist_lang[$language_complete]", 0, old("extra_artist_lang[$language_complete]", $artist_lang->extra_artist_lang ?? '') , '', '', 300, true)
			];
		}

		return $formulario;
	}

	private function saveLangFields($request, $cod_artist)
	{

		$artist_langs = Web_Artist_Lang::where('id_artist_lang', $cod_artist)->get();
		$languages = array_keys(config('app.locales'));

		foreach ($languages as $keyLang) {

			$language_complete = config("app.language_complete.$keyLang");

			if ($keyLang == 'es') {
				continue;
			}

			$requestForArtistsLang = ($request->biography_artist_lang[$language_complete] || $request->extra_artist_lang[$language_complete] || $request->info_artist_lang[$language_complete]);

			if(!$requestForArtistsLang){
				continue;
			}

			if($artist_langs->where('lang_artist_lang', $language_complete)->count()) {

				Web_Artist_Lang::where([
					['id_artist_lang', $cod_artist],
					['lang_artist_lang', $language_complete]
				])->update([
					'info_artist_lang' => $request->info_artist_lang[$language_complete],
					'biography_artist_lang' => $request->biography_artist_lang[$language_complete],
					'extra_artist_lang' => $request->extra_artist_lang[$language_complete],
				]);

			} else {

				Web_Artist_Lang::create([
					'id_artist_lang' => $cod_artist,
					'info_artist_lang' => $request->info_artist_lang[$language_complete],
					'biography_artist_lang' => $request->biography_artist_lang[$language_complete],
					'extra_artist_lang' => $request->extra_artist_lang[$language_complete],
					'lang_artist_lang' => $language_complete
				]);

			}
		}

	}
}
