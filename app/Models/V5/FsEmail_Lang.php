<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class FsEmail_Lang extends Model
{

	// Variables propias de Eloquent para poder usar el ORM de forma correcta.

	protected $table = 'fsemail_lang';
	protected $primaryKey = 'codemail_lang';

	protected $attributes = false;
	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];
}
