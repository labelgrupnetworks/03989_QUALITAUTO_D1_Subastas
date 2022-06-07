<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;


class FsEmail extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'FsEmail';
	protected $primaryKey = 'EMP_EMAIL, COD_EMAIL';
	protected $dateFormat = 'U';
	protected $attributes = false;                  // Ej: ['delayed' => false]; Son valores por defecto para el modelo

	public $timestamps = false; 	// No usaremos campos de BBDD created_at y updated_at
	public $incrementing = false;

	protected $guarded = []; // Blacklist de variables que no queremos updatear de forma masiva


	# JOINS
    public function scopeJoinFsEmailLang($query){

        $query->leftJoin('FSEMAIL_LANG', function ($join) {

            $join   ->on("FSEMAIL_LANG.CODEMAIL_LANG", "=", "FSEMAIL.COD_EMAIL")
                    ->on("FSEMAIL_LANG.EMP_LANG", "=", "FSEMAIL.EMP_EMAIL");

        });
        return  $query;

    }
}
