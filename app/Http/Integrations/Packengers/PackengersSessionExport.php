<?php

namespace App\Http\Integrations\Packengers;

use App\Models\V5\FgAsigl0;
use App\Providers\ToolsServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackengersSessionExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithCustomCsvSettings
{
	use Exportable;

	private $idAucSession;
	private $theme;

	public function __construct($idAucSession)
	{
		$this->idAucSession = $idAucSession;
		$this->theme = Config::get('app.theme');
	}

	public function query()
	{
		return FgAsigl0::query()
			->select([
				'ref_asigl0',
				'num_hces1',
				'lin_hces1',
				'impsalhces_asigl0',
				"desc_hces1 as description",
				'webfriend_hces1',
				'ancho_hces1',
				'alto_hces1',
				'grueso_hces1',
				'des_umed',
				'dir_alm',
				'codpais_alm',
				'pob_alm',
				'cp_alm',
				'des_sub',
				'cod_sub',
				'"name"',
				'"id_auc_sessions"',
				'"start"',
			])
			->joinFghces1Asigl0()
			->leftJoinAlm()
			->leftJoinUmed()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->where('"id_auc_sessions"', $this->idAucSession)
			->orderBy('ref_asigl0', 'asc');
	}

	public function map($lot): array
	{
		return [
			"$lot->cod_sub-$lot->ref_asigl0",
			$lot->ref_asigl0,
			$lot->ancho_hces1,
			$lot->grueso_hces1,
			$lot->alto_hces1,
			$lot->des_umed,
			$lot->impsalhces_asigl0,
			'EUR',
			$this->cleanDescription($lot->description),
			ToolsServiceProvider::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1),
			ToolsServiceProvider::url_lot($lot->cod_sub, $lot->id_auc_sessions, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->description),
			$this->ownerName(),
			$lot->cod_sub,
			$lot->des_sub,
			(new Carbon($lot->start))->format('Y-m-d'),
			$lot->dir_alm,
			$lot->codpais_alm,
			$lot->pob_alm,
			$lot->cp_alm,
		];
	}

	public function headings(): array
	{
		return [
			'id',
			'lot_number',
			'length',
			'depth',
			'height',
			'metrics_unit',
			'value',
			'currency',
			'description',
			'photo_url',
			'lot_url',
			'owner_name',
			'catalog_reference',
			'catalog_name',
			'catalog_date',
			'picking_address',
			'picking_country',
			'picking_city',
			'picking_zipcode',
		];
	}

	private function ownerName()
	{
		return match ($this->theme) {
			'ansorena' => 'ansorena',
			'salaretiro' => 'sala-retiro',
			default => 'demo',
		};
	}

	private function cleanDescription($description)
	{
		$descriptionHtmlWithSpaces = str_replace('<', ' <', $description);
		$descriptionString = strip_tags($descriptionHtmlWithSpaces);
		return str_replace('  ', ' ', $descriptionString);
	}

	public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'use_bom' => true,
            'output_encoding' => 'UTF-8',
        ];
    }
}
