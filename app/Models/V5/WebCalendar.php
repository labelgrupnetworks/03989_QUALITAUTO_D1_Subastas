<?php

# Ubicacion del modelo

namespace App\Models\V5;
use Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebCalendar extends Model {

    // Variables propias de Eloquent para poder usar el ORM de forma correcta.
    protected $table = 'WEB_CALENDAR';
    protected $primaryKey = 'ID_CALENDAR';
    //protected $connection = 'SUBALIA';
    public $timestamps = false;
    public $incrementing = false;
    //   public  $keyType = string;
    //permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];



	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'EMP_CALENDAR' =>  Config::get('app.main_emp')
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('EMP_CALENDAR', \Config::get("app.main_emp"));
		});
	}

	public function scopeJoinEvent($query){
        return $query->join('WEB_CALENDAR_EVENT', 'EMP_CALENDAR_EVENT = EMP_CALENDAR AND COD_CALENDAR_EVENT = CODEVENT_CALENDAR');
    }



}
