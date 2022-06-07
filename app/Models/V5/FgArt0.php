<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FgArt0 extends Model
{
    protected $table = 'FGART0';
    protected $primaryKey = 'ID_ART0, EMP_ART0, REFASIN_ART0';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            'emp_art0' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_art0', \Config::get("app.emp"));
        });
	}

	public function scopeJoinArtArt0($query){
        return $query->join('FGART', 'FGART.EMP_ART = FGART0.EMP_ART0 AND FGART.IDART0_ART = FGART0.ID_ART0');
    }

}

