<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;


class WebRedirectPages extends Model
{
    protected $table = 'web_redirect_pages';
    protected $primaryKey = 'id_web_redirect_pages';

    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = [])
    {
        $this->attributes = [
            'emp_web_redirect_pages' => Config::get("app.main_emp")
        ];

        parent::__construct($vars);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function (Builder $builder) {
            $builder->where('emp_web_redirect_pages', Config::get("app.main_emp"));
        });
    }
}
