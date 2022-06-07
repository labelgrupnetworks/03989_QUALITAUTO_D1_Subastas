<?php

# Ubicacion del modelo
namespace App\Models\V5\Log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use App\Providers\ToolsServiceProvider;
//use App\Override\RelationCollection;
class AucSessions_Log extends Model
{
    protected $table = '"auc_sessions_log"';
    protected $primaryKey = '"company", "auction", "reference"';

    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];




    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = []){
        $this->attributes=[
            '"company"' => Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('"company"', Config::get("app.emp"));
        });
	}



}

