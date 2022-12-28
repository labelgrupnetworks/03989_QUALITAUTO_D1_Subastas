<?php

namespace App\Exports;

use App\Models\V5\FsIdioma;
use App\Models\V5\FxCliWeb;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MailChimpExport implements FromCollection, WithHeadings, ShouldAutoSize
{
	use Exportable;

	public function __construct()
	{
	}

	public function collection()
	{
		return $this->users();
	}

	public function users()
	{
		$languages = FsIdioma::pluck('des_idioma', 'cod_idioma');

		$formattingUser = function ($user) use ($languages) {
			$completName = array_map("trim", explode(",", $user->nom_cliweb));
			$nameHaveComa = count($completName) !== 1;

			$name = $nameHaveComa ? $completName[1] : $completName[0];
			$lastName = $nameHaveComa ? $completName[0] : "";
			$userLanguage = $languages[$user->idioma_cli] ?? $languages['ES'];

			return [
				mb_strtolower($user->email_cliweb),
				$name,
				$lastName,
				$user->pais_cli,
				$userLanguage,
			];
		};

		$userFormat = FxCliWeb::query()
			->joinCliCliweb()
			->select('email_cliweb', 'nom_cliweb', 'idioma_cli', 'pais_cli')
			->where('nllist1_cliweb', 'S')
			->get()
			->map($formattingUser);

		return $userFormat;
	}

	public function headings(): array
	{
		return [
			'Email Address',
			'First Name',
			'Last Name',
			'País',
			'Idioma'
		];
	}
}
