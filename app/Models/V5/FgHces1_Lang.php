<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Config;
class FgHces1_Lang extends Model
{
    protected $table = 'fghces1_lang';
    protected $primaryKey = 'EMP_HCES1_LANG, NUM_HCES1_LANG, LIN_HCES1_LANG';

    public $timestamps = false;
    public $incrementing = false;

    //permitimos crear un elemento apartir de todos los campos
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_hces1_lang' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
	}

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_hces1_lang', \Config::get("app.emp"));
        });
    }




}
