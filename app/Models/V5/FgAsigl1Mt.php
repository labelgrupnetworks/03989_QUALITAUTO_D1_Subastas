<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class FgAsigl1Mt extends Model
{
    protected $table = 'FGASIGL1MT';
    protected $primaryKey = 'ID_ASIGL1MT';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = [])
	{
        $this->attributes=[
            'emp_asigl1mt' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_asigl1mt', Config::get("app.emp"));
        });
    }

	/**
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeJoinAsigl1($query)
	{
        return $query->join('FgAsigl1', 'emp_asigl1 = emp_asigl1mt and sub_asigl1 = sub_asigl1mt and ref_asigl1 = ref_asigl1mt and lin_asigl1 = lin_asigl1mt');
	}

	public function getFullNameAttribute()
	{
		return "{$this->nom_asigl1mt} {$this->apellido_asigl1mt}";
	}

	public function getAmountRatio($amount)
	{
		if(empty($this->ratio_asigl1mt)){
			return 0;
		}
		return $amount * $this->ratio_asigl1mt / 100;
	}
}
