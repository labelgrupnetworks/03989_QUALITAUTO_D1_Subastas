<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
class FgAsigl2 extends Model
{
    protected $table = 'FGASIGL2';

    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_asigl2' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_asigl2', Config::get("app.emp"));
        });
    }

    protected $casts = [
        'ref_asigl2' => 'float',
        'imp_asigl2' => 'float',
        'impiva_asigl2' => 'float',
    ];

	public static function getBuilderForAuctions(Collection $auctionsLots) : Builder
	{
		return self::where(function($query) use ($auctionsLots) {
			return $auctionsLots->map(function($references, $auction) use ($query) {
				return $query->orWhere('sub_asigl2', $auction)->whereIn('ref_asigl2', $references);
			});
		});
	}
}
