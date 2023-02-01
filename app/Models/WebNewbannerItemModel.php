<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class WebNewbannerItemModel extends Model
{
	protected $table = 'web_newbanner_item';
	protected $primaryKey = 'ID';
	public $timestamps = false;
	public $incrementing = false;
	//   public  $keyType = string;
	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	public function banner()
	{
		return $this->belongsTo(WebNewbannerModel::class, 'id', 'id_web_newbanner');
	}

	public function scopeActive($query)
	{
		return $query->where('activo', 1);
	}

	public function scopeOrderItems($query)
	{
		return $query->orderBy('bloque')
			->orderBy('orden')
			->orderBy('id');
	}

	public function scopeForLanguage($query)
	{
		$query->where('lenguaje', strtoupper(Config::get("app.locale")));
	}
}
