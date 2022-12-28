<?php

namespace App\Http\View\Composers;

use App\Models\V5\FgSub;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class GlobalComposer
{

	static $subastas;

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{

		if(static::$subastas){
			return $view->with('global', ['subastas' => static::$subastas]);
		}

		$subastasQuery = FgSub::query()
			//No he encontrado un caso en el que lo necesite y en caso
			//de necesitarlo saldra a cuenta envolverlo en un condicional.
			//->joinLangSub()
			->joinSessionSub()
			->addSelect('subc_sub')
			->where('subc_sub', '!=', 'N');

		if (!\Session::get('user.admin')) {
			$subastasQuery->where('subc_sub', '!=', 'A');
		}


		/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
		if(\Config::get("app.restrictVisibility")){
			$subastasQuery =	$subastasQuery->Visibilidadsubastas(\Session::get('user.cod'));
		}


		$subastasQuery = $subastasQuery->orderBy('session_start', 'asc')->get();


		$subastas = $subastasQuery
			->groupBy([
				//Si se es admin, las subastas subc_sub A las unificamos con las S
				function ($item, $key) {
					if (\Session::has('user') && \Session::get('user.admin') && $item['subc_sub'] == 'A') {
						return 'S';
					} else {
						return $item['subc_sub'];
					}
				},
				'tipo_sub', 'cod_sub'
			], $preserveKeys = false);

		static::$subastas = $subastas;

		$view->with('global', compact('subastas'));
	}
}
