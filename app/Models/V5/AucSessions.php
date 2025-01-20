<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use App\Providers\ToolsServiceProvider;
//use App\Override\RelationCollection;
class AucSessions extends Model
{
	protected $table = '"auc_sessions"';
	protected $primaryKey = '"id_auc_sessions"';

	public $timestamps = false;
	public $incrementing = true;

	protected $guarded = [];

	protected $appends = [
		'start_format',
		'end_format'
	];


	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'"company"' => Config::get("app.emp")
		];
		parent::__construct($vars);
	}


	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('emp', function (Builder $builder) {
			$builder->where('"company"', Config::get("app.emp"));
		});
	}

	/* public function newCollection(array $models = [])
	{
		return new RelationCollection($models);
	} */

	public function scopeWhereUpdateApi($query, $item)
	{
		return $query->where('"auction"', $item['"auction"'])
			->where('"reference"', $item['"reference"']);
	}

	public function getStartFormatAttribute()
	{
		return ToolsServiceProvider::getDateFormat($this->start, 'Y-m-d H:i:s', 'd/m/Y H:i');
	}

	public function getEndFormatAttribute()
	{
		return ToolsServiceProvider::getDateFormat($this->end, 'Y-m-d H:i:s', 'd/m/Y H:i');
	}

	public function getUrlSessionAttribute()
	{
		return ToolsServiceProvider::url_auction($this->auction, $this->name, $this->id_auc_sessions, $this->reference);
	}

	public function getUrlIndiceAttribute()
	{
		return ToolsServiceProvider::url_indice_auction($this->auction, $this->name, $this->id_auc_sessions);
	}

	public function scopelog($query)
	{
		return $query->joinUsr()->select('FSUSR.NOM_USR, "auc_sessions".*');
	}

	public function scopeJoinFgSub($query)
	{
		return $query->join('fgsub', '"company" = emp_sub AND "auction" = cod_sub');
	}

	public function scopeJoinLocaleFgSub($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		$query->join('fgsub', '"company" = emp_sub AND "auction" = cod_sub')
			->leftjoin('fgsub_lang', function ($join) use ($lang) {
				$join->on('fgsub.emp_sub', '=', 'fgsub_lang.emp_sub_lang')
					->on('fgsub.cod_sub', '=', 'fgsub_lang.cod_sub_lang')
					->where('fgsub_lang.lang_sub_lang', $lang);
			});
	}

	public function scopeJoinUsr($query)
	{
		return $query->leftjoin("FSUSR", 'FSUSR.COD_USR = "auc_sessions"."usr_update_sessions"');
	}

	public function scopeJoinLang($query)
	{
		$lang = ToolsServiceProvider::getLanguageComplete(Config::get('app.locale'));

		return $query->leftJoin('"auc_sessions_lang"', '  "id_auc_session_lang" = "id_auc_sessions"   AND "company_lang" = "company"   AND "auction_lang" = "auction"  AND "lang_auc_sessions_lang" = \'' . $lang . '\'');
	}

	public function scopeLeftJoinWebSubastas($query)
	{
		return $query->leftJoin('WEB_SUBASTAS', 'WEB_SUBASTAS.ID_EMP = "auc_sessions"."company" AND WEB_SUBASTAS.ID_SUB = "auc_sessions"."auction" AND WEB_SUBASTAS.SESSION_REFERENCE = "auc_sessions"."reference"');
	}

	public function scopeWhereAuction($query, $auction)
	{
		return $query->where('"auction"', $auction);
	}

	public static function previousReference($auction, $reference)
	{
		return self::whereAuction($auction)
			->where('"reference"', '<', $reference)
			->select('"reference"', '"id_auc_sessions"', '"auction"', '"name"')
			->orderBy('"reference"', 'desc')
			->first();
	}

	public static function nextReference($auction, $reference)
	{
		return self::whereAuction($auction)
			->where('"reference"', '>', $reference)
			->select('"reference"', '"id_auc_sessions"', '"auction"', '"name"')
			->orderBy('"reference"')
			->first();
	}
}
