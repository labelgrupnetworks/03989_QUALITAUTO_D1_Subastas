<?php

namespace App\Http\Controllers;

use App\Models\V5\FgOrtsec0;
use App\Services\Content\SpecialistService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class EnterpriseController extends Controller
{

	function __construct() {}

	public function index()
	{
		$specialits = (new SpecialistService)->infEspecialistas();
		$data['especialista'] = $specialits->groupBy('lin_especial1');

		return View::make('front::pages.specialists', array('data' => $data));
	}

	public function department($text)
	{
		$fgOrtsec = new FgOrtsec0();
		$ortsec = $fgOrtsec->getInfoWithKeyOrtsec($text);

		if (empty($ortsec)) {
			exit(View::make('front::errors.404'));
		}

		$especialistas = (new SpecialistService)->getSpecialistsByOrtsec($ortsec->lin_ortsec0);
		$data['seo'] = new \stdClass();
		$data['seo']->meta_title = $ortsec->meta_titulo_ortsec0 ?? trans(Config::get('app.theme') . '-app.head.title_app');
		$data['seo']->meta_description = $ortsec->meta_description_ortsec0 ?? trans(Config::get('app.theme') . '-app.head.meta_description');

		return View::make('front::pages.department', compact('ortsec', 'especialistas', 'data'));
	}

	public function team()
	{
		$specialists = (new SpecialistService)->infEspecialistas();
		$specialties = $specialists->where('lin_especial1', '!=', 1)->pluck('titulo_especial0')->unique();

		$data = [
			'specialists' => $specialists,
			'specialties' => $specialties,
		];

		return view('front::pages.team', ['data' => $data]);
	}

	public function aboutUsPage()
	{
		if (!View::exists('front::pages.about_us')) {
			abort(404);
		}

		$specialists = (new SpecialistService)->getAllSpecialists();

		return view('front::pages.about_us', ['specialists' => $specialists]);
	}
}
