<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class FgNftNetwork extends Model
{
	protected $table = 'FgNft_Network';
	protected $primaryKey = 'id_nft_networkt';
	protected $attributes = false;

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];
}
