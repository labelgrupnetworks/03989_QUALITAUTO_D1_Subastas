<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Mobile\Resources\CategoryResource;
use App\Models\V5\FgOrtsec0;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MobileCategoriesController extends Controller
{
	public function index(Request $request)
	{
		$lang = $request->user()?->idioma_cliweb ?? 'ES';
		App::setLocale($lang);

		$categories = FgOrtsec0::getCategoriesWhereAuctionsQuery()
			->select('lin_ortsec0', 'des_ortsec0', 'orden_ortsec0', 'key_ortsec0', 'meta_description_ortsec0', 'meta_titulo_ortsec0', 'meta_contenido_ortsec0')
			->when($lang != 'ES', function ($query) {
				$query->joinLangFgOrtsec0()
					->addSelect('des_ortsec0_lang', 'key_ortsec0_lang', 'meta_description_ortsec0_lang', 'meta_titulo_ortsec0_lang', 'meta_contenido_ortsec0_lang');
			})
			->orderBy('lin_ortsec0')
			->get();

		return CategoryResource::collection($categories);
	}
}
