<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class FsOrigen extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FsOrigen';
	protected $primaryKey = 'id_origen';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	public function clientes()
	{
		return $this->belongsToMany(FxCli::class, 'fxcliorigen', 'origen_cliorigen', 'cli_cliorigen')->wherePivot('gemp_cliorigen', config('app.gemp'));
	}

}
