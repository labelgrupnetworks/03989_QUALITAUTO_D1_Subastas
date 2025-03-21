<?php

namespace App\Http\Controllers\admin;

use \Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\V5\Web_Block;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

/**
 * Class BloqueConfigController
 * Administraction de la tabla web_block
 * @todo 21/03/2025
 * Tal y como esta pensada, no creo que nadie administre estas consultas desde aquí.
 * Solamente se utiliza para lotes destacados y recomentados, puede que se pueda eliminar
 * si se modifica la forma en como mostrarlos.
 */
class BloqueConfigController extends Controller
{
	//Ver todos los Bloques que hay
	public function index()
	{
		$data = Web_Block::orderBy('title', 'desc')->get();
		return View::make('admin::pages.bloque', array('data' => $data));
	}

	//Ver la informacion del bloque si no existe todo vacio
	public function SeeBloque($id = NULL)
	{
		$bloque = Web_Block::where('id_web_block', $id)->first();
		return View::make('admin::pages.editBloque', array('bloque' => $bloque));
	}

	//Editar el bloque
	public function EditBloque()
	{
		$type = Request::input('type');
		$title = Request::input('title');
		$consulta = Request::input('consulta');
		$enabled_temp = Request::input('enabled');
		$id = Request::input('id');
		$key_name = Request::input('key_name');
		$cache = Request::input('cache');

		//Comprobamos que no haya injection sql
		$val_injection = $this->injectionSQL($consulta);
		//Si no hay update o hace un insert dependiendo de si existe

		if (!$val_injection) {

			$enabled = $enabled_temp == 'on' ? 1 : 0;

			if ($id < 1) {
				$maxId = Web_Block::max('id_web_block') + 1;
				Web_Block::create([
					'id_web_block' => $maxId,
					'key_name' => $key_name,
					'title' => $title,
					'type' => $type,
					'products' => $consulta,
					'enabled' => $enabled,
					'time_cache' => $cache,
				]);
			} else {
				Web_Block::where('id_web_block', $id)
					->update([
						'key_name' => $key_name,
						'title' => $title,
						'type' => $type,
						'products' => $consulta,
						'enabled' => $enabled,
						'time_cache' => $cache,
					]);
			}

			$claves_temp = $this->sqlClaves($consulta);

			if (!$claves_temp) {
				$value = DB::select($consulta);
				if ($cache >= 1) {
					$expiresAt = Carbon::now()->addMinutes($cache);
					Cache::put($key_name, $value, $expiresAt);
				}
				$num_resultados = count($value);
				return array($num_resultados, $id);
			} else {
				return ("claves");
			}
		} else {
			return ("injection");
		}
	}

	function injectionSQL($consulta)
	{
		$injection['in'] =  array('delete', 'insert', 'created', 'drop', 'alter', 'update');
		$val_injection = false;


		foreach ($injection['in'] as $valor) {
			$consulta_temp = stripos($consulta, $valor);
			if ($consulta_temp !== false) {
				$val_injection = true;
				break;
			}
		}
		return  $val_injection;
	}

	function sqlClaves($consulta)
	{
		$injection['claves'] =  array('{', '}');
		$val_injection = false;
		foreach ($injection['claves'] as $valor) {
			$consulta_temp = stripos($consulta, $valor);
			if ($consulta_temp !== false) {
				$val_injection = true;
				break;
			}
		}
		return  $val_injection;
	}
}
