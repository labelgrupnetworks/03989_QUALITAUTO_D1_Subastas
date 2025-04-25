<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $bloques
 * @property int $completo
 * @property string $opciones
 * @property WebNewbannerModel $banner
 */
class WebNewbannerTipoModel extends Model
{
    protected $table = 'web_newbanner_tipo';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;
 //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

	public function banner()
    {
        return $this->hasOne(WebNewbannerModel::class, 'id_web_newbanner_tipo', 'id');
    }
}
