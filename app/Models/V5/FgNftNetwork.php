<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FgNftNetwork extends Model
{
	protected $table = 'FgNft_Network';
	protected $primaryKey = 'id_nft_networkt';
	protected $attributes = false;

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];



    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_nft_network' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_nft_network', \Config::get("app.emp"));
        });
	}

}
