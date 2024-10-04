<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\articles\FgArt0;
use Illuminate\Http\Request;

class AdminArticlesController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'articulos']);
	}

	public function index(Request $request)
	{
		$emp = config('app.emp');
		$gemp = config('app.gemp');

		$articles = FgArt0::select('id_art0', 'des_sec as sec_art0', 'title_art0', 'des_art0')
			->when($request->id_art0, function ($query, $id_art0) {
				return $query->where('id_art0', 'like', "%{$id_art0}%");
			})
			->when($request->sec_art0, function ($query, $sec_art0) {
				return $query->where('upper(des_sec)', 'like', "%" . mb_strtoupper($sec_art0) . "%");
			})
			->when($request->title_art0, function ($query, $title_art0) {
				return $query->where('upper(title_art0)', 'like', "%" . mb_strtoupper($title_art0) . "%");
			})
			->when($request->des_art0, function ($query, $des_art0) {
				return $query->where('des_art0', 'like', "%{$des_art0}%");
			})
			->joinArt()->joinSec()->artActivo()->orderBy($request->filled('orden') ? $request->order : 'id_art0', $request->filled('orden_art0') ? $request->orden_art0 : 'asc')
			->paginate(30);

		$tableParams = ['id_art0' => 1, 'sec_art0' => 1, 'title_art0' => 1, 'des_art0' => 1];

		$formulario = (object)[
			'id_art0' => FormLib::Text('id_art0', 0, $request->id_art0),
			'sec_art0' => FormLib::Text('sec_art0', 0, $request->sec_art0),
			'title_art0' => FormLib::Text('title_art0', 0, $request->title_art0),
			'des_art0' => FormLib::Text('des_art0', 0, $request->des_art0)
		];

		return view('admin::pages.articles.index', compact('articles', 'tableParams', 'formulario'));
	}

	public function getOrder()
	{
		$gemp = config('app.gemp');
		$sections = FgArt0::select('cod_sec', 'des_sec', 'orden_ortsec1')->distinct()
			->joinArt()->joinSec()->joinOrtsec1()->artActivo()->orderBy('orden_ortsec1', 'asc')->get();

		foreach ($sections as $section) {
			$articles = FgArt0::select('id_art0', 'sec_art0', 'title_art0', 'des_art0', 'orden_art0')
				->joinArt()->joinSec()->artActivo()->where('sec_art0', $section->cod_sec)
				->orderby('orden_art0')->orderby('id_art0')->orderby('sec_art0')->get();
			$section->articles = $articles;
		}

		return view('admin::pages.articles.order', compact('sections'));
	}

	public function saveOrder(Request $request)
	{
		$articles = FgArt0::select('id_art0', 'sec_art0', 'des_sec', 'title_art0', 'des_art0', 'orden_art0')
			->joinArt()->joinSec()->artActivo()->where('sec_art0', collect($request->sec))->orderby('orden_art0')->orderby('id_art0')->orderby('sec_art0')->get();
		$order = collect($request->ref)->flip();

		foreach ($articles as $article) {

			$article->orden_art0 = $order[$article->id_art0 . '-' . $article->sec_art0] + 1;

			//Comprueba si el modelo a sido modificado para no lanzar más actualizaciones de las necesarias
			if ($article->isDirty()) {
				FgArt0::where([
					['id_art0', $article->id_art0],
					['sec_art0', $article->sec_art0]
				])->update([
					'orden_art0' => $article->orden_art0
				]);
			}
		}
	}
}
