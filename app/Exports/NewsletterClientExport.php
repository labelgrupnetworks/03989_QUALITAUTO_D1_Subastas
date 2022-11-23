<?php

namespace App\Exports;

use App\Models\V5\FxCliWeb;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NewsletterClientExport implements FromCollection, WithHeadings, ShouldAutoSize
{

	public $newsletters;

	public function __construct()
	{
		$this->newsletters = [];
		if (config('app.newsletterFamilies')) {
			$this->newsletters = collect(explode(',', config('app.newsletterFamilies', '')))->map(function ($item) {
				return "nllist" . trim($item) . "_cliweb";
			});
		}
	}

	public function collection()
	{


		return FxCliWeb::select('COD_CLIWEB', 'COD2_CLIWEB', 'NOM_CLIWEB', 'EMAIL_CLIWEB', 'FECALTA_CLIWEB')

			->when(!empty($this->newsletters), function ($query) {
				$query->addSelect($this->newsletters->implode(','));

				$query->where(function ($query) {
					foreach ($this->newsletters as $newsletter) {
						$query->orWhere($newsletter, 'S');
					}
				});
			}, function ($query) {
				$query->where('NLLIST1_CLIWEB', 'S');
			})
			->orderBy('FECALTA_CLIWEB', 'desc')
			->get();
	}

	public function headings(): array
	{
		$newslettersTranslates = [];
		foreach ($this->newsletters as $value) {
			$newslettersTranslates[] = trans("admin-app.fields.$value");
		}

		$heading = array_merge([
			trans('admin-app.fields.user.id'),
			trans('admin-app.fields.user.id_origin'),
			trans('admin-app.fields.user.name'),
			trans('admin-app.fields.user.email'),
			trans('admin-app.fields.user.fecalta')
		], $newslettersTranslates);

		return $heading;
	}
}
