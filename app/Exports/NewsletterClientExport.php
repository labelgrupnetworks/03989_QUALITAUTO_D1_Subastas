<?php

namespace App\Exports;

use App\Models\V5\FxCliWeb;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NewsletterClientExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
		return FxCliWeb::select('COD_CLIWEB', 'COD2_CLIWEB', 'NOM_CLIWEB', 'EMAIL_CLIWEB', 'FECALTA_CLIWEB')
			->where('NLLIST1_CLIWEB', 'S')
			->orderBy('FECALTA_CLIWEB', 'desc')
			->get();
	}

	public function headings(): array
    {
        return [
			trans('admin-app.fields.user.id'),
			trans('admin-app.fields.user.id_origin'),
			trans('admin-app.fields.user.name'),
			trans('admin-app.fields.user.email'),
			trans('admin-app.fields.user.fecalta')
        ];
	}

}
