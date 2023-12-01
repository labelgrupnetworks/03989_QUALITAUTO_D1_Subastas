<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

class FgHces1Files extends Model
{
    protected $table = 'FGHCES1_FILES';
    protected $primaryKey = 'id_hces1_files';
	public $incrementing = true;


    public $timestamps = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	const ROOT_DIRECTORY = 'files';
	const PERMISSION_EMPTY = 'N';
	const PERMISSION_USER = 'U';
	const PERMISSION_DEPOSIT = 'D';

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []) {
        $this->attributes = [
            'emp_hces1_files' => Config::get("app.emp")
        ];
        parent::__construct($vars);
	}

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_hces1_files', Config::get("app.emp"));
        });
    }

	/***
	 * Atributos
	 */
	public function getStoragePathAttribute()
	{
		$path = str_replace("\\", "/", $this->attributes['path_hces1_files']);

		if(config('app.storage_path_active', false)){
			return storage_path('app/' . self::ROOT_DIRECTORY . $path);
		}

		return public_path('/'. self::ROOT_DIRECTORY . $path);
	}

	public function getDownloadPathAttribute()
	{
		return route('lot_file_download', ['lang' => config('app.locale'),'file' => $this->id_hces1_files, 'numhces' => $this->numhces_hces1_files, 'linhces' => $this->linhces_hces1_files]);
	}

	public static function getRelativeStoragePath($num_hces1, $lin_hces1)
	{
		$emp = Config::get('app.emp');
		$path = "/$emp/$num_hces1/$lin_hces1/files/";

		return $path;
	}

	public static function uploadFile(UploadedFile $file, FgHces1Files $fgHces1File) : FgHces1Files
	{
		$relativePath = self::getRelativeStoragePath($fgHces1File->numhces_hces1_files, $fgHces1File->linhces_hces1_files);

		$storagePath = public_path('/'. self::ROOT_DIRECTORY . $relativePath);
		if(config('app.storage_path_active', false)){
			$storagePath = storage_path('app/' . self::ROOT_DIRECTORY . $relativePath);
		}

		if (!is_dir(str_replace("\\", "/", $storagePath))) {
			mkdir(str_replace("\\", "/", $storagePath), 0775, true);
		}

		$newfile = str_replace("\\", "/", $storagePath . '/' . $file->getClientOriginalName());

		copy($file->getPathname(), $newfile);

		$fgHces1File->path_hces1_files = $relativePath . $file->getClientOriginalName();
		$fgHces1File->save();

		return $fgHces1File;
	}

	public static function deleteFile(FgHces1Files $fgHces1File) : bool
	{
		$storagePath = public_path('/'. self::ROOT_DIRECTORY . $fgHces1File->path_hces1_files);
		if(config('app.storage_path_active', false)){
			$storagePath = storage_path('app/' . self::ROOT_DIRECTORY . $fgHces1File->path_hces1_files);
		}

		$file = str_replace("\\", "/", $storagePath);

		if (file_exists($file)) {
			unlink($file);
		}

		return true;
	}

	/***
	 * Recursos
	 */
	public static function getAllFilesByLot($num_hces1, $lin_hces1): Collection
	{
		$withTable = Config::get('app.use_table_files', false);
		return $withTable
			? self::withNumhcesAndLinhces($num_hces1, $lin_hces1)
					->orderBy('order_hces1_files')
					->get()
			: self::getOldFiles($num_hces1, $lin_hces1);
	}

	public static function getAllFilesByLotCanViewUser($userSession, $num_hces1, $lin_hces1, $validDeposit = false)
	{
		return self::withNumhcesAndLinhces($num_hces1, $lin_hces1)
					->withPermissions($userSession, $validDeposit)
					->active()
					->orderBy('order_hces1_files')
					->get();
	}

	public static function getFileByIdCanViewUser($userSession, $idFile, $validDeposit = false)
	{
		return self::where('id_hces1_files', $idFile)
					->withPermissions($userSession, $validDeposit)
					->active()
					->first();
	}

	public static function getPermissions()
	{
		return [
			self::PERMISSION_EMPTY => trans('admin-app.values.empty_permission'),
			self::PERMISSION_USER => trans('admin-app.values.user_permission'),
			self::PERMISSION_DEPOSIT => trans('admin-app.values.deposit_permission')
		];
	}

	private static function getOldFiles(string $num_hces1, string $lin_hces1): Collection
	{
		$emp = Config::get('app.emp');
		$path = "/files/$emp/$num_hces1/$lin_hces1/files/";
		$files = [];
		if (is_dir(getcwd() . $path)) {
			$files = array_diff(scandir(getcwd() . $path), ['.', '..']);
		}

		return self::mapperFiles($files, $num_hces1, $lin_hces1);
	}

	/**
	 * @return \Illuminate\Support\Collection<TKey, FgHces1Files>
	 * @template TKey of array-key
	 * */
	private static function mapperFiles(array $files, string $num_hces1, string $lin_hces1): Collection
	{
		$emp = Config::get('app.emp');
		$filesInstances = [];
		foreach ($files as $file) {
			$nameFile = explode('.', $file)[0];
			$nameFile = str_replace('-', ' ', $nameFile);

			$routeFile = "\\$emp\\$num_hces1\\$lin_hces1\\files\\$file";

			$filesInstances[] = new self([
				'id_hces1_files' => '0',
				'numhces_hces1_files' => $num_hces1,
				'linhces_hces1_files' => $lin_hces1,
				'lang_hces1_files' => null,
				'path_hces1_files' => $routeFile,
				'external_url_hces1_files' => null,
				'name_hces1_files' => $nameFile,
				'description_hces1_files' => null,
				'order_hces1_files' => 1,
				'image_hces1_files' => null,
				'is_active_hces1_files' => 'S',
				'permission_hces1_files' => 'N',
			]);
		}

		return collect($filesInstances);
	}


	/***
	 * Scopes y where's
	 */
	public function scopeWithPermissions($query, $userSession, $validDeposit)
	{
		$permissions = [self::PERMISSION_EMPTY];

		//si no es usuario se obtienen los que no tienen permisos
		if(!$userSession){
			return $query->where('permission_hces1_files', self::PERMISSION_EMPTY);
		}

		//si el usuario es un administrador, no se filtra por permisos (se obtienen todos)
		if(array_key_exists('admin', $userSession)){
			return $query;
		}

		//si el usuario no tiene un depostio valido añadimos solo los de usuario
		if(!$validDeposit){
			array_push($permissions, self::PERMISSION_USER);
		}
		//si el usuario si tiene un depostio valido añadimos los de usuario
		else{
			array_push($permissions, self::PERMISSION_USER, self::PERMISSION_DEPOSIT);
		}

		return $query->whereIn('permission_hces1_files', $permissions);
	}


	public function scopeWithNumhcesAndLinhces($query, $num_hces1, $lin_hces1)
	{
		return $query->where('numhces_hces1_files', $num_hces1)
					->where('linhces_hces1_files', $lin_hces1);
	}

	public function scopeActive($query)
	{
		return $query->where('is_active_hces1_files', 'S');
	}

	private function whereNotUser($query)
	{
		return $query->where('permission_hces1_files', 'N');
	}

	private function whereIsUser($query)
	{
		return $query->whereIn('permission_hces1_files', ['U', 'N']);
	}

	public function actulizarTablaFgHces1_FilesConArchivosDelServidor()
	{
		$empresa = config('app.emp');
		$rootPath = "app/files/$empresa";

		$numDirectories = $this->pathWithouDot(scandir(storage_path($rootPath)));

		foreach ($numDirectories as $num) {

			$linDirectories = $this->pathWithouDot(scandir(storage_path("$rootPath/$num")));

			foreach ($linDirectories as $lin) {

				if(!is_dir(storage_path("$rootPath/$num/$lin"))){
					continue;
				}

				$files = $this->pathWithouDot(scandir(storage_path("$rootPath/$num/$lin/files")));

				foreach ($files as $file) {


					$nameFile = explode('.', $file)[0];
					$nameFile = str_replace('-', ' ', $nameFile);

					$routeFile = "\\$empresa\\$num\\$lin\\files\\$file";

					self::create([
						'numhces_hces1_files' => $num,
						'linhces_hces1_files' => $lin,
						'lang_hces1_files' => null,
						'path_hces1_files' => $routeFile,
						'external_url_hces1_files' => null,
						'name_hces1_files' => $nameFile,
						'description_hces1_files' => null,
						'order_hces1_files' => 1,
						'image_hces1_files' => null,
						'is_active_hces1_files' => 'S',
						'permission_hces1_files' => 'N',
					]);

				}

			}

		}
	}

	private function pathWithouDot($path)
	{
		return array_diff($path, ['.', '..']);
	}

}
