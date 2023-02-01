<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;

class SysAuctionTypes extends Model
{
	protected $table = '"sys_auction_types"';
	protected $primaryKey = '"id_sys_auction_types"';

	public $timestamps = false;
	public $incrementing = false;

	protected $guarded = [];

	public const ENABLED = 2;
	public const DISABLED = 1;

	public function scopeEnabledAuctions($query)
	{
		return $query->where('"status"', self::ENABLED);
	}
}
