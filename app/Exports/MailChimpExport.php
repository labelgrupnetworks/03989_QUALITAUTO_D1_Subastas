<?php

namespace App\Exports;

use App\Models\V5\FsIdioma;
use App\Models\V5\Fx_Newsletter_Suscription;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MailChimpExport implements FromCollection, WithHeadings, ShouldAutoSize
{
	use Exportable;

	private $onlyRegisterClients;

	public function __construct($onlyRegisterClients)
	{
		$this->onlyRegisterClients = $onlyRegisterClients;
	}

	public function collection()
	{
		return $this->users();
	}

	/**
	 * Adaptado a Tauler, en otros casos será necesario modificar los campos del grupo
	 */
	public function users()
	{
		$languages = FsIdioma::pluck('des_idioma', 'cod_idioma');
		$mailChimpLanguage = [
			'ES' => 'es_ES',
			'EN' => 'en'
		];

		$users = Fx_Newsletter_Suscription::query()
			->select('cod_cli, email_cli, nom_cli, pais_cli, dir_cli, dir2_cli, pob_cli, pro_cli, cp_cli, codpais_cli, tel1_cli, lang_newsletter_suscription')
			->selectRaw("LISTAGG(name_newsletter, ', ') WITHIN GROUP (ORDER BY id_newsletter_suscription) grupo")
			//->selectRaw("'Suscritos' as grupo")
			->joinNewsletter()
			->when($this->onlyRegisterClients, function($query){
				return $query->joinCli();
			}, function($query){
				return $query->leftJoinCli();
			})
			->groupBy('cod_cli, email_cli, nom_cli, pais_cli, dir_cli, dir2_cli, pob_cli, pro_cli, cp_cli, codpais_cli, tel1_cli, lang_newsletter_suscription')
			->get()
			->map(function ($user) use ($languages, $mailChimpLanguage) {
				$completName = array_map("trim", explode(",", $user->nom_cli));
				$nameHaveComa = count($completName) !== 1;

				$name = $nameHaveComa ? $completName[1] : $completName[0];
				$lastName = $nameHaveComa ? $completName[0] : "";

				$userLanguage = $languages[$user->lang_newsletter_suscription] ?? $languages['ES'];
				$shortLanguage = $mailChimpLanguage[$user->lang_newsletter_suscription] ?? 'es_ES';

				return [
					$user->email_cli,
					$name,
					$lastName,
					trim($user->pais_cli),
					$userLanguage,
					$user->grupo,
					$shortLanguage,
					$user->cod_cli,
					$user->tel1_cli,
					trim($user->dir_cli),
					trim($user->dir2_cli),
					trim($user->pob_cli),
					trim($user->pro_cli),
					trim($user->cp_cli),
					$user->codpais_cli,
					trim($user->pob_cli),
					trim($user->pro_cli),
				];
			});

		return $users;
	}

	public function headings(): array
	{
		return [
			'Email Address',
			'First Name',
			'Last Name',
			'País',
			'Idioma',
			'Grupo',
			'Language',
			'Código Cliente',
			'Phone Number',
			'Address - Street Address',
			'Address - Address Line 2',
			'Address - City',
			'Address - State',
			'Address - Zip',
			'Address - Country',
			'Ciudad',
			'Provincia'
		];
	}
}
