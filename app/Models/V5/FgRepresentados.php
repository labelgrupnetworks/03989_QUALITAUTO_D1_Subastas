<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

/**
 * Class FgRepresentados
 *
 * @package App\Models\V5
 * @property int $id
 * @property string $emp_representados
 * @property string $cli_representados
 * @property string $nom_representados
 * @property string $cif_representados
 * @property string $alias_representados
 * @property bool $activo_representados
 * @property bool $eliminado_representados
 * @property \Illuminate\Support\Carbon $falta_representados
 * @property \Illuminate\Support\Carbon|null $fmodi_representados
 */
class FgRepresentados extends Model
{
	// Variables propias de Eloquent para poder usar el ORM de forma correcta.
	protected $table = 'fgrepresentados';
	protected $primaryKey = 'id';
	protected $dateFormat = 'Y-m-d H:i:s';

	const CREATED_AT = 'falta_representados';
	const UPDATED_AT = 'fmodi_representados';

	protected $guarded = [];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_representados' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_representados', Config::get("app.emp"));
		});
	}

	public function getActivoRepresentadosAttribute($value)
	{
		return $value == 'S';
	}

	public function setActivoRepresentadosAttribute($value)
	{
		if (is_string($value)) {
			$this->attributes['activo_representados'] = $value;
			return;
		}

		$this->attributes['activo_representados'] = $value ? 'S' : 'N';
	}

	public function getEliminadoRepresentadosAttribute($value)
	{
		return $value == 'S';
	}

	public function setEliminadoRepresentadosAttribute($value)
	{
		$this->attributes['eliminado_representados'] = $value ? 'S' : 'N';
	}

	/**
	 * @param $codCli
	 * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Builder|FgRepresentados>
	 */
	public static function getRepresentedCollectionByClient($codCli)
	{
		return self::query()
			->select('id', 'nom_representados', 'cif_representados', 'alias_representados', 'activo_representados')
			->isNotDeleted()
			->whereClient($codCli)
			->orderBy('alias_representados')
			->get();
	}

	/**
	 * @param $codCli
	 * @return array
	 */
	public static function getRepresentedToSelect($codCli)
	{
		return self::query()
			->select('id', 'alias_representados')
			->isNotDeleted()
			->isActive()
			->whereClient($codCli)
			->orderBy('alias_representados')
			->pluck('alias_representados', 'id')
			->toArray();
	}

	public static function insertFromArray(string $codCli, array $representedArray)
	{
		if(empty($codCli)) {
			return;
		}

		$cleanRepresentedArray = array_filter($representedArray, function($represented){
			return !empty($represented['name']) || !empty($represented['cif']);
		});

		$newRepresentedArray = array_map(function($represented) use ($codCli){
			return [
				'cli_representados' => $codCli,
				'nom_representados' => $represented['name'],
				'cif_representados' => $represented['cif'],
				'alias_representados' => $represented['alias'] ?? $represented['name'],
				'emp_representados' => Config::get('app.emp'),
				'falta_representados' => date('Y-m-d H:i:s'),
				'fmodi_representados' => date('Y-m-d H:i:s'),
			];
		}, $cleanRepresentedArray);

		self::insert($newRepresentedArray);
	}

	public function scopeIsNotDeleted($query)
	{
		return $query->where('eliminado_representados', 'N');
	}

	public function scopeIsActive($query)
	{
		return $query->where('activo_representados', 'S');
	}

	public function scopeWhereClient($query, $codCli)
	{
		return $query->where('cli_representados', $codCli);
	}

	public function toEmailString()
	{
		$representedToSting = 'Representado a: ' . $this->nom_representados;
		$representedToSting .= "<br>CIF: " . $this->cif_representados;
		$representedToSting .= "<br>ID: " . $this->id;
		return $representedToSting;
	}
}
