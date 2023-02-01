<?php

namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class Fx_Newsletter_Suscription extends Model
{
	protected $table = 'fx_newsletter_suscription';
	protected $primaryKey = 'id_newsletter_suscription';
	public $timestamps = false;

	//permitimos crear un elemento apartir de todos los campos
	protected $guarded = [];

	#definimos la variable emp para no tener que indicarla cada vez
	public function __construct(array $vars = [])
	{
		$this->attributes = [
			'gemp_newsletter_suscription' => Config::get("app.gemp"),
			'emp_newsletter_suscription' => Config::get('app.emp')
		];
		parent::__construct($vars);
	}

	protected static function boot()
	{
		parent::boot();
		static::addGlobalScope('gemp', function (Builder $builder) {
			$builder->where('gemp_newsletter_suscription', Config::get("app.gemp"));
			$builder->where('emp_newsletter_suscription', Config::get("app.emp"));
		});
	}

	static function insertWithDefaultValues(array $suscriptions)
	{
		foreach ($suscriptions as &$suscription) {
			$suscription = array_merge([
				'gemp_newsletter_suscription' => Config::get("app.gemp"),
				'emp_newsletter_suscription' => Config::get("app.emp"),
			], $suscription);
		}

		self::insert($suscriptions);
	}

	static function scopeGetSuscriptionsCli($query)
	{
		return $query
			->select('email_newsletter_suscription, lang_newsletter_suscription, cod_cli, nom_cli, pais_cli')
			->selectRaw("LISTAGG(name_newsletter, ',') WITHIN GROUP (ORDER BY name_newsletter) suscriptions")
			->leftJoinCli()
			->joinNewsletter()
			->groupBy('email_newsletter_suscription, lang_newsletter_suscription, cod_cli, nom_cli, pais_cli');
	}

	public function getUrlUnsuscribeAttribute()
	{
		$hash = md5($this->email_newsletter_suscription);
		return route('newsletter.unsuscribe', [
			'lang' => $this->lang_newsletter_suscription,
			'email' => $this->email_newsletter_suscription,
			'hash' => $hash
		]);
	}

	public function getCreateNewsletterSuscriptionAttribute()
	{
		return Carbon::create($this->attributes['create_newsletter_suscription'])->format("d/m/Y h:i:s");
	}

	public function scopeWhereEmail($query, $email)
	{
		$email = mb_strtolower(trim($email));
		return $query->where('lower(email_newsletter_suscription)', $email);
	}

	public function scopeWhereFilters($query, Request $request)
	{
		return $query
			->when($request->id_newsletter_suscription, function ($query, $id) {
				return $query->where('id_newsletter_suscription', $id);
			})
			->when($request->email_newsletter_suscription, function ($query, $email_newsletter_suscription) {
				return $query->where('lower(email_newsletter_suscription)', 'like', '%' . mb_strtolower($email_newsletter_suscription) . '%');
			})
			->when($request->cod_cli, function ($query, $cod_cli) {
				return $query->where('cod_cli', 'like', '%' . $cod_cli . '%');
			})
			->when($request->nom_cli, function ($query, $nom_cli) {
				return $query->where('lower(nom_cli)', 'like', '%' . mb_strtolower($nom_cli) . '%');
			})
			->when($request->pais_cli, function ($query, $pais_cli) {
				return $query->where('lower(pais_cli)', 'like', '%' . mb_strtolower($pais_cli) . '%');
			})
			->when($request->lang_newsletter_suscription, function ($query, $lang_newsletter_suscription) {
				return $query->where('lang_newsletter_suscription', $lang_newsletter_suscription);
			})
			->when($request->create_newsletter_suscription, function ($query, $create_newsletter_suscription) {
				return $query->where('create_newsletter_suscription', '>=', $create_newsletter_suscription);
			});
	}

	public function scopeLeftJoinCli($query)
	{
		return $query->leftJoin('fxcli', 'lower(fx_newsletter_suscription.email_newsletter_suscription) = lower(fxcli.email_cli) and fx_newsletter_suscription.gemp_newsletter_suscription = fxcli.gemp_cli');
	}

	public function scopeJoinCli($query)
	{
		return $query->join('fxcli', 'lower(fx_newsletter_suscription.email_newsletter_suscription) = lower(fxcli.email_cli) and fx_newsletter_suscription.gemp_newsletter_suscription = fxcli.gemp_cli');
	}

	public function scopeJoinNewsletter($query)
	{
		return $query->join('Fx_Newsletter', 'fx_newsletter_suscription.id_newsletter = fx_newsletter.id_newsletter and fx_newsletter_suscription.gemp_newsletter_suscription = fx_newsletter.gemp_newsletter and fx_newsletter_suscription.emp_newsletter_suscription = fx_newsletter.emp_newsletter')
			->where('lang_newsletter', mb_strtoupper(config('app.locale')));
	}

	public function name()
	{
		return $this->hasOne(Fx_Newsletter::class, 'id_newsletter', 'id_newsletter')->where('lower(lang_newsletter)', mb_strtolower(config('app.locale')));
	}
}
