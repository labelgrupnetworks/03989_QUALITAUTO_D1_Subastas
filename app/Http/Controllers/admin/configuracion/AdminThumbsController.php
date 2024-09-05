<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\libs\ImageGenerate;
use App\Models\V5\FgHces1;
use App\Models\V5\Web_Images_Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AdminThumbsController extends Controller
{
	const FILTER_ONLY_WITHOUT_THUMB = '1';
	const FILTER_WITH_MODIFIED_DATE = '2';

	public function __construct()
	{
		$this->middleware(function ($request, $next) {
			if (strtoupper(session('user.usrw')) != 'SUBASTAS@LABELGRUP.COM') {
				abort(403, 'No tienes permisos para acceder a esta página');
			}
			return $next($request);
		});

		view()->share(['menu' => 'configuracion_admin']);
	}

	public function index()
	{
		$sizes = Web_Images_Size::query()
			->select('size_web_images_size', 'name_web_images_size')
			->where('name_web_images_size', 'like', '%lote%')
			->orderby('size_web_images_size')
			->get();

		return view('admin::pages.configuracion.thumbs.index', ['sizes' => $sizes]);
	}

	public function getLots(Request $request)
	{
		//Como mínimo se tiene que seleccionar subasta o numhces
		if (!$request->input('auction') && !$request->input('numhces')) {
			return response()->json(['error' => 'No se han seleccionado lotes'], 400);
		}

		$lots = FgHces1::query()
			->select('num_hces1', 'lin_hces1')
			->when($request->input('auction'), function ($query, $auction) {
				return $query->where('sub_hces1', $auction);
			})
			->when($request->input('numhces'), function ($query, $numhces) {
				return $query->where('num_hces1', $numhces);
			})
			->when($request->input('linhces'), function ($query, $linhces) {
				return $query->where('lin_hces1', $linhces);
			})
			->when($request->input('ref'), function ($query, $ref) {
				return $query->where('ref_hces1', $ref);
			})
			->orderBy('num_hces1, lin_hces1')
			->get()
			->each(function ($lot) {
				$lot->images = (new ImageGenerate)->getlotImages($lot->num_hces1, $lot->lin_hces1);
			})
			->when($request->input('type'), function ($collection, $type) use ($request) {
				$size = $request->input('size');
				return match ($type) {
					self::FILTER_ONLY_WITHOUT_THUMB => $collection->filter(fn ($lot) => $this->filterNewThumbnails($lot, $size)),
					self::FILTER_WITH_MODIFIED_DATE => $collection->filter(fn ($lot) => $this->filterModifiedImages($lot, $size)),
					default => $collection
				};
			})
			->filter(fn ($lot) => !empty($lot->images));

		if ($lots->isEmpty()) {
			return response()->json(['error' => 'No se han encontrado lotes con las condiciones seleccionadas'], 404);
		}

		$data = [
			'count_lots' => $lots->count(),
			'count_images' => $lots->sum(fn ($lot) => count($lot->images)),
			'lots' => $lots->values()->all()
		];

		return response()->json($data);
	}

	public function generateThumbs(Request $request)
	{
		$numhces = $request->input('numhces');
		$linhces = $request->input('linhces');
		$size = $request->input('size');

		try {
			(new ImageGenerate)->imageLot($numhces, $linhces, null, $size);
		} catch (\Throwable $th) {
			$errorMessage = "Error al generar las miniaturas del lote número {$numhces} y línea {$linhces}";
			Log::error($errorMessage, ['error' => $th->getMessage()]);
			return response()->json(['error' => $errorMessage], 500);
		}

		$message = $size
			? "Miniaturas del lote número {$numhces} y línea {$linhces} generadas correctamente en el tamaño $size"
			: "Miniaturas del lote número {$numhces} y línea {$linhces} generadas correctamente";

		return response()->json(['message' => $message]);
	}

	private function getThumbsPath($numHces, $size)
	{
		$emp = Config::get('app.emp');
		return public_path("img/thumbs/$size/$emp/$numHces/");
	}

	private function getOriginalPath($numHces)
	{
		$emp = Config::get('app.emp');
		return public_path("img/$emp/$numHces/");
	}

	/**
	 * Comprueba si el lote tiene alguna imagen que no tenga la miniatura creada
	 */
	private function filterNewThumbnails($lot, $size)
	{
		foreach ($lot->images as $image) {
			if (!file_exists($this->getThumbsPath($lot->num_hces1, $size) . $image)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Comprueba si el lote tiene alguna imagen que la fecha de modificación de
	 * la original sea mayor que la de la miniatura
	 */
	function filterModifiedImages($lot, $size)
	{
		foreach ($lot->images as $image) {
			$thumbFilePath = $this->getThumbsPath($lot->num_hces1, $size) . $image;
			$thumbDate = file_exists($thumbFilePath) ? filemtime($thumbFilePath) : 0;
			$originalDate = filemtime($this->getOriginalPath($lot->num_hces1) . $image);

			if ($originalDate > $thumbDate) {
				return true;
			}
		}
		return false;
	}
}
