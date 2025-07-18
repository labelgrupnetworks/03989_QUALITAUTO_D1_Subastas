<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ContentController extends Controller
{
	public function index()
	{
		$pages = Web_Page::query()
			->whereNull('manageable_web_page')
			->orderBy('name_web_page', 'asc')
			->get();

		return View::make('admin::pages.page_content', ['data' => $pages]);
	}

	public function getPage($id)
	{
		$page = Web_Page::query()
			->where('id_web_page', $id)
			->first();

		return View::make('admin::pages.editPage_content', ['content' => $page]);
	}

	public function savedPage(Request $request)
	{
		$id = $request->input('id');

		$dataToUpdate = [
			'name_web_page' => $request->input('name_web_page'),
			'content_web_page' => $request->input('html'),
			'webmetat_web_page' => $request->input('webmetat_web_page'),
			'webmetad_web_page' => $request->input('webmetad_web_page'),
			'webnoindex_web_page' => empty($request->input('webnoindex_web_page')) ? 0 : 1,
		];

		Web_Page::query()
			->where('id_web_page', $id)
			->update($dataToUpdate);
	}
}
