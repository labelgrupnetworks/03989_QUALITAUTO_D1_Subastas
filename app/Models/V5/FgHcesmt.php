<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgHcesmt extends Model
{
    protected $table = 'FGHCESMT';
    protected $primaryKey = 'EMP_HCESMT, NUM_HCESMT, CLI_HCESMT, LIN_HCESMT';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_hcesmt' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_hcesmt', Config::get("app.emp"));
        });
	}


}

