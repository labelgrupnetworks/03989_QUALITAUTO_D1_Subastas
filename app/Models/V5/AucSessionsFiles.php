<?php

# Ubicacion del modelo
namespace App\Models\V5;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
class AucSessionsFiles extends Model
{
    protected $table = '"auc_sessions_files"';
    protected $primaryKey = '"id", "company", "auction", "reference"';

    public $timestamps = false;
    public $incrementing = false;

	protected $guarded = [];

	const TYPE_FILES = [
		1 =>'Pdf',
		2 => 'Video',
		3 => 'Imagen',
		4 => 'Documento',
		5 => 'Enlace',
		6 => 'Bases de la subasta'
	];

	const PATH_ICONS = [
		1 => '/img/icons/pdf.png',
		2 => '/img/icons/video.png',
		3 => '/img/icons/image.png',
		4 => '/img/icons/document.png',
		5 => '/img/icons/video.png',
		6 => '/img/icons/document.png'
	];

	const TYPE_ENLACE = 5;
	const AUCTION_CONDITIONS = 6;


    #definimos la variable emp para no tener que indicarla cada vez
    public function __construct(array $vars = [])
	{
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

	public function scopeWhereAuction($query, $auction)
	{
		return $query->where('"auction"', $auction);
	}

	public function scopeWhereAuctionBases($query, $auction)
	{
		return $query->where([
			'"auction"' => $auction,
			'"type"' => self::AUCTION_CONDITIONS,
		]);
	}

	public function scopeIsLink($query)
	{
		return $query->where('"type"', self::TYPE_ENLACE);
	}

	public function getPublicFilePathAttribute()
	{
		return "\\files{$this->path}";
	}

	public function getTypeFileAttribute()
	{
		return self::TYPE_FILES[$this->type];
	}

	public function getPathIconAttribute()
	{
		return self::PATH_ICONS[$this->type];
	}

	public function getUrlFormatAttribute()
	{
		return ($this->type == self::TYPE_ENLACE) ? $this->url : "/files{$this->path}";
	}
}

