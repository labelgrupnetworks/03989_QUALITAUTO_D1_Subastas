<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FgNft extends Model
{
    protected $table = 'FGNFT';
    protected $primaryKey = ' EMP_NFT, NUMHCES_NFT, LINHCES_NFT';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_nft' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_nft', \Config::get("app.emp"));
        });
	}


	public function scopeJoinFghces($query){
        return $query->join('FGHCES1', 'FGHCES1.EMP_HCES1 = FGNFT.EMP_NFT AND FGHCES1.NUM_HCES1 = FGNFT.NUMHCES_NFT AND FGHCES1.LIN_HCES1 = FGNFT.LINHCES_NFT');
    }

}

