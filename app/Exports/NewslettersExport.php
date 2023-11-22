<?php

namespace App\Exports;

use App\Models\V5\Fx_Newsletter;
use App\Models\V5\Fx_Newsletter_Suscription;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NewslettersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
	use Exportable;
	public $newsletters;

	public function __construct()
	{
		$this->newsletters = Fx_Newsletter::WhereLang()->pluck('name_newsletter');
	}

	public function collection()
	{
		return $this->suscriptions();
	}

	/**
	 * Adaptado a Tauler, en otros casos será necesario modificar los campos del grupo
	 */
	public function suscriptions()
	{
		$newsletters = $this->newsletters;
		$suscriptions = Fx_Newsletter_Suscription::query()
			->getSuscriptionsCli()
			->orderby("create_newsletter_suscription", "desc")
			->get()
			->map(function($suscription) use ($newsletters) {
				$data = [
					$suscription->cod_cli,
					$suscription->cod2_cli,
					$suscription->nom_cli,
					$suscription->email_newsletter_suscription,
					$suscription->create_newsletter_suscription,
					$suscription->lang_newsletter_suscription,
					$suscription->pais_cli,
				];

				$values = explode(', ', $suscription->suscriptions);
				foreach($newsletters as $newsletter) {
					$data[] = in_array($newsletter, $values) ? "Si" : "No";
				}

				return $data;
			});

		return $suscriptions;
	}

	public function headings(): array
	{
		$heading = [
			'Código Cliente',
			'Id Origen',
			'Nombre Cliente',
			'Email',
			'Fecha Alta',
			'Idioma',
			'País'
		];

		foreach($this->newsletters as $newsletter) {
			$heading[] = $newsletter;
		}

		return $heading;
	}

	public function columnFormats(): array
    {
        return [
			'A' => NumberFormat::FORMAT_TEXT,
		];
	}
}
