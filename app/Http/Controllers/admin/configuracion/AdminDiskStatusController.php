<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use FilesystemIterator;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class AdminDiskStatusController extends Controller
{
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

	public function index(Request $request)
	{
		/* $depth = $request->input('depth', 3);
		$directories = $this->exploreDirectories('', $depth);

		dd($directories); */

		$data = [
			//'directories' => $directories,
			'freeSpaceInDisk' => $this->unitConvert(disk_free_space('/')),
			'spaceInDisk' => $this->unitConvert(disk_total_space('/'))
		];

		return view('admin::pages.configuracion.disk_status.index', $data);
	}

	public function getDirectoryInPath(Request $request)
	{
		$path = $request->input('path', '');
		$depth = $request->input('depth', 1);

		$directories = $this->exploreDirectories($path, $depth);

		return view('admin::pages.configuracion.disk_status.node', ['directories' => $directories]);
	}

	private function exploreDirectories($path, $depth)
	{
		if ($depth == 0) {
			return [];
		}

		$directories = [];
		$subDirectories = [];

		$items = $this->getDirs($path);

		/* if($path == ''){
			$items = array_diff($items, ['blog']);
		} */

		foreach ($items as $item) {

			$publicPath = public_path($item);

			//relaitve path:
			$name = basename($item);

			// Obtener el tamaño del directorio
			$size = $this->getDirectorySize($publicPath, 'KB');

			// Recursivamente explorar subdirectorios si hay más profundidad
			if ($depth > 1) {
				$subDirectories = $this->exploreDirectories($item, $depth - 1);
			}

			$directories[] = [
				'path' => $item,
				'name' => $name,
				'size' => $size,
				'subdirectories' => $subDirectories
			];
		}

		return $directories;
	}

	private function getDirs($path)
	{
		$items = scandir(public_path($path));

		// Eliminar los directorios "." y ".."
		$items = array_diff($items, array('.', '..'));

		//excluir reservados
		$exclude = ['vendor'];

		$items = array_diff($items, $exclude);

		//add path to items
		$items = array_map(function ($item) use ($path) {
			return "$path/$item";
		}, $items);

		// Filtrar solo los directorios y eliminar los links
		$items = array_filter($items, function ($item) {
			return is_dir(public_path("$item")) && !is_link(public_path($item));
		});

		return $items;
	}

	public function getDirectorySize($path, $unit = null)
	{
		$bytestotal = 0;
		$path = realpath($path);
		if ($path !== false) {
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
				$bytestotal += $object->getSize();
			}
		}

		return $this->unitConvert($bytestotal);

		/* return match ($unit) {
			'KB' => round($bytestotal / 1024, 2),
			'MB' => round($bytestotal / 1048576, 2),
			'GB' => round($bytestotal / 1073741824, 2),
			default => $bytestotal
		}; */
	}

	private function unitConvert($size)
	{
		if ($size >= 1073741824) {
			$size = number_format($size / 1073741824, 2) . ' GB';
		} elseif ($size >= 1048576) {
			$size = number_format($size / 1048576, 2) . ' MB';
		} elseif ($size >= 1024) {
			$size = number_format($size / 1024, 2) . ' KB';
		} else {
			$size = $size . ' bytes';
		}

		return $size;
	}
}
