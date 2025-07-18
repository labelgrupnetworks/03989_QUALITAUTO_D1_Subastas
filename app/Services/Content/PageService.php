<?php

namespace App\Services\Content;

use App\Models\V5\Web_Page;
use App\Support\Localization;
use Illuminate\Support\Facades\Config;

class PageService
{
	/**
	 * Obtiene una página por su clave
	 *
	 * @param string $key
	 * @return Web_Page|null
	 */
	public function getPage($key) : ?Web_Page
	{
		$page = Web_Page::query()
			->where([
				'key_web_page' => $key,
				'lang_web_page' => Localization::getUpperLocale(),
			])
			->first();

		if(!$page) {
			return null;
		}

		ManagementService::add('page', [
			'name' => $page->name_web_page,
			'url' => route('content.page', ['id' => $page->id_web_page]),
		]);

		return $page;
	}

	public function getPageWithoutGemp($key)
	{
		$emp = Config::get('app.emp');
		return Web_Page::query()
			->withoutGlobalScope('emp')
			->where([
				['key_web_page', $key],
				['emp_web_page', $emp],
				['lang_web_page', Localization::getUpperLocale()]
			])
			->first();
	}
}
