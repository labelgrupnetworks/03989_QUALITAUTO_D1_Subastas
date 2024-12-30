<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgSubInvites extends Model
{
	use HasFactory;

	// Nombre de la tabla
	protected $table = 'fgsubinvites';

	// Clave primaria (si se usa un campo distinto a "id", puedes especificarlo aquí)
	protected $primaryKey = false;

	// Desactivar la gestión automática de timestamps si no estás usando las columnas created_at y updated_at
	public $timestamps = false;
	public $incrementing = false;

	// Definir los campos que se pueden llenar masivamente
	protected $fillable = [
		'emp_subinvites',
		'owner_codcli_subinvites',
		'invited_codcli_subinvites',
		'invited_nom_subinvites',
		'invited_cif_subinvites',
		'invited_tel_subinvites',
		'codsub_subinvites',
		'notification_sent_subinvites',
		'invite_date_subinvites',
	];

	protected $cast = [
		'notification_sent_subinvites' => 'boolean',
	];

	// Definir si necesitas convertir la fecha de la invitación automáticamente
	protected $dates = ['invite_date_subinvites'];

	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'emp_subinvites' => Config::get("app.emp")
		];

		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('emp_subinvites', Config::get("app.emp"));
		});
	}

	public function owner()
	{
		return $this->belongsTo(FxCliWeb::class, 'owner_codcli_subinvites', 'cod_cliweb')
			->where('emp_cliweb', Config::get("app.emp"));
	}

	public function invited()
	{
		return $this->belongsTo(FxCliWeb::class, 'invited_codcli_subinvites', 'cod_cliweb')
			->JoinCliCliweb()
			->where('emp_cliweb', Config::get("app.emp"));
	}

	public function auction()
	{
	    return $this->belongsTo(FgSub::class, 'codsub_subinvites', 'cod_sub')
			->where('emp_sub', Config::get("app.emp"));
	}
}
