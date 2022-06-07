<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class FxCli2 extends Model
{

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.

    protected $table = 'FxCli2';
    protected $primaryKey = 'GEMP_CLI2, COD_CLI2';
    protected $dateFormat = 'U';
    protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

    public $timestamps = false;     // No usaremos campos de BBDD created_at y updated_at
    public $incrementing = false;

    protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	public function __construct(array $vars = []){
        $this->attributes=[
            'gemp_cli2' => \Config::get("app.gemp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('gemp_cli2', \Config::get("app.gemp"));
        });
	}



	public function scopeWhereUpdateApi($query, $item){
		return $query->where('cod2_cli2', $item["cod2_cli2"]);
	}

	#Relaciones
	public function cli()
	{
		return $this->belongsTo(FxCli::class, 'cod_cli2', 'cod_cli');
	}


}
