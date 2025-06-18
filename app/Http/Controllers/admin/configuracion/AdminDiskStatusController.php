<?php

namespace App\Http\Controllers\admin\configuracion;

use App\Http\Controllers\Controller;
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

	public function index()
	{
		return view('admin::pages.configuracion.disk_status.index', $this->generateDiskUsageData());
	}

	public function getDirectoryInPath(Request $request)
	{
		$path = $request->input('path', '');
		$depth = $request->input('depth', 1);

		$data = $this->exploreDirectories($path, $depth);

		return view('admin::pages.configuracion.disk_status.node', $data);
	}

	/**
	 * Explorar directorios,
	 * $depth no se esta utilizando, pero nos sirve por si qusieramos explorar mas de una llamada.
	 *
	 * @param string $path
	 * @param int $depth Profundidad de exploración, no se esta utilizando por ahora pero nos sirve por si qusieramos explorar mas de un nivel
	 * @return array
	 */
	private function exploreDirectories($path, $depth)
	{
		if ($depth == 0) {
			return [];
		}

		$directories = [];
		$files = [];

		$items = $this->getDirsContent($path);

		$directoriesFilter = array_filter($items, function ($item) {
			return is_dir(public_path($item));
		});

		$filesFilter = array_filter($items, function ($item) {
			$isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $item);
			$isPdf = preg_match('/\.pdf$/i', $item);
			return !is_dir(public_path($item)) && ($isImage || $isPdf);
		});

		$directories = array_map(fn ($item) => $this->getDirectoriesData($item), $directoriesFilter);
		$files = array_map(fn ($item) => $this->getImageFileData($item), $filesFilter);

		return [
			'directories' => $directories,
			'files' => $files
		];
	}

	private function getDirectoriesData ($directoryPath, $depth = 1)
	{
		$publicPath = public_path($directoryPath);
		$name = basename($directoryPath);
		$size = $this->getDirectorySize($publicPath);
		// Recursivamente explorar subdirectorios si hay más profundidad
		$subDirectories = [];
		if ($depth > 1) {
			$subDirectories = $this->exploreDirectories($directoryPath, $depth - 1);
		}

		return [
			'path' => $directoryPath,
			'name' => $name,
			'size' => $size,
			'subdirectories' => $subDirectories
		];
	}

	private function getImageFileData ($imagePath)
	{
		$publicPath = public_path($imagePath);
		$name = basename($imagePath);

		$size = $this->unitConvert(filesize($publicPath));

		$lastModified = filemtime($publicPath);

		return [
			'path' => $imagePath,
			'name' => $name,
			'size' => $size,
			'lastModified' => date('d-m-Y H:i:s', $lastModified),
			'link' => $imagePath
		];
	}

	private function getDirsContent($path)
	{
		$items = scandir(public_path($path));

		//excluir reservados
		$exclude = ['.', '..', 'vendor'];
		$items = array_diff($items, $exclude);

		//add path to items
		$items = array_map(function ($item) use ($path) {
			return "$path/$item";
		}, $items);

		return $items;
	}

	private function generateDiskUsageData()
	{
		$fileSystem = PHP_OS_FAMILY === 'Windows' ? '/' : '/DATA';
		$freeSpaceInDisk = disk_free_space($fileSystem);
		$spaceInDisk = disk_total_space($fileSystem);

		return [
			'freeSpaceInDisk' => $this->unitConvert($freeSpaceInDisk),
			'spaceInDisk' => $this->unitConvert($spaceInDisk),
			'usedSpacePercent' => ($spaceInDisk <= 0) ? 0 : (($spaceInDisk - $freeSpaceInDisk) / $spaceInDisk) * 100
		];
	}

	private function getDirectorySize($path)
	{
		$bytestotal = 0;
		$path = realpath($path);
		if ($path !== false) {
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
				$bytestotal += $object->getSize();
			}
		}

		return $this->unitConvert($bytestotal);
	}

	private function unitConvert($size)
	{
		return match (true) {
			$size >= 1073741824 => number_format($size / 1073741824, 2) . ' GB',
			$size >= 1048576 => number_format($size / 1048576, 2) . ' MB',
			$size >= 1024 => number_format($size / 1024, 2) . ' KB',
			default => $size . ' bytes'
		};
	}

	private function generateDiskUsageForAllFileSystems()
	{
		$diskUsageData = [];
		foreach ($this->getListOfFileSystems() as $mounted_file_system) {

			$freeSpace = disk_free_space($mounted_file_system);
			$totalSpace = disk_total_space($mounted_file_system);
			$usedSpacePercentage =  ($totalSpace <= 0) ? 0 : (($totalSpace - $freeSpace) / $totalSpace) *  100;

			$diskUsageData[] = [
				'fs_name' => $mounted_file_system,
				'disk_free_space' => $freeSpace,
				'disk_total_space' => $totalSpace,
				'used_space_percent' => $usedSpacePercentage,
			];
		}

		return $diskUsageData;
	}

	private function getListOfFileSystems()
	{
		$mounted_file_systems = [];
		exec('findmnt -l -o TARGET', $mounted_file_systems);
		array_shift($mounted_file_systems); // remove output header
		sort($mounted_file_systems);

		return $mounted_file_systems;
	}
}
