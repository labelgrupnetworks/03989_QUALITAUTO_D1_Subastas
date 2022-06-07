<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class Web_Email_Logs extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'WEB_EMAIL_LOGS';
	protected $primaryKey = 'ID_EMAIL_LOGS';
	protected $dateFormat = 'U';
	protected $attributes = false;		// Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva

	protected $appends = ['typeEmailFormat'];


	public function __construct(array $vars = []){
        $this->attributes=[
            'emp_email_logs' => \Config::get("app.emp")
        ];
        parent::__construct($vars);
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('emp', function(Builder $builder) {
            $builder->where('emp_email_logs', \Config::get("app.emp"));
        });
	}


    public function getTypeEmailFormatAttribute(){
		return ['L' => 'Licitador', 'A' => 'Administrador'][$this->type_email_logs] ?? '';
	}











}
