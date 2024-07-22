<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use App\libs\CacheLib;
use App\Models\V5\FgHces1;
use App\Models\V5\Web_Images_Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

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
				$lot->images = $this->getlotImages($lot->num_hces1, $lot->lin_hces1);
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
			$this->imageLot($numhces, $linhces, null, $size);
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


	/**
	 * TODO EL CÓDIGO A PARTIR DE AQUÍ YA ESTA CREADO EN EL ImageGenerate de la rama de tauler
	 * Cuando se únifique esa rama, utilizar ese código y elimianr este
	 */

	public function imageLot($numHces, $linHces = null, $imagePosition = null, $imageSize = null)
	{
		$emp = Config::get('app.emp');
		$path = "img/$emp/$numHces/";

		$images = $this->getlotImages($numHces, $linHces, $imagePosition);

		$sizes = $imageSize
			? [$imageSize]
			: CacheLib::rememberCache('image_sizes', 1200, function () {
				return Web_Images_Size::query()
					->where('name_web_images_size', 'like', '%lote%')
					->pluck('size_web_images_size');
			});

		foreach ($images as $image) {
			[$imageName, $extension] = explode(".", $image);
			$imagePath = public_path($path . $image);

			foreach ($sizes as $size) {
				set_time_limit(60);
				$directoryThumb = "img/thumbs/$size/$emp/$numHces";
				if (!is_dir(public_path($directoryThumb))) {
					mkdir(public_path($directoryThumb), 0775, true);
					chmod(public_path($directoryThumb), 0775);
				}

				$imageThumb = public_path("$directoryThumb/$imageName.jpg");

				$imageMake = Image::make($imagePath);

				$imageMake->resize($size, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});

				$imageMake->save($imageThumb, 75, 'jpg');
			}
		}
	}

	private function getlotImages($numHces, $linHces = null, $imagePosition = null)
	{
		$emp = Config::get('app.emp');
		$path = "img/$emp/$numHces/";

		if (!is_dir(public_path($path))) {
			return [];
		}

		$images = array_diff(scandir($path), ['.', '..']);

		if (!empty($linHces)) {
			$images = array_filter($images, fn ($image) => $this->isSameLineAndPosition($image, $linHces, $imagePosition));
		}

		return array_values($images);
	}

	/**
	 * ejemplos de nombre
	 * 001-50-1.jpg
	 * 001-50-1_01.jpg
	 * [emp-numHces-linHces]_[imagePosition].[extension]
	 *
	 * si $imagePosition es 0, obtenemos la que concida con el linHces y que no tenga imagePosition
	 * si $imagePosition es null, obtenemos todas las que concidan con el linHces
	 * si $imagePosition no es null, obtenemos la que concida con el linHces y la imagePosition
	 */
	private function isSameLineAndPosition($image, $linHces, $imagePosition)
	{
		[$imageName, $extension] = explode(".", $image);

		$imageParams = explode("-", $imageName);
		[$imgEmp, $imgNum, $imgLin] = $imageParams;

		if ($imagePosition === 0) {
			return $imgLin == $linHces && strpos($imgLin, "_") === false;
		}

		if ($imagePosition === null) {
			return $imgLin == $linHces || (strpos($imgLin, "_") !== false && explode("_", $imgLin)[0] == $linHces);
		}

		$imagePosition = str_pad($imagePosition, 2, "0", STR_PAD_LEFT);
		$imgPos = explode("_", $imgLin)[1] ?? null;
		$imgLin = explode("_", $imgLin)[0] ?? $imgLin;

		return $imgLin == $linHces && $imgPos == $imagePosition;
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
