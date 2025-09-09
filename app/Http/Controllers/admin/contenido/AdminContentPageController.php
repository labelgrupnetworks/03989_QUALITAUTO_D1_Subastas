<?php

namespace App\Http\Controllers\admin\contenido;

use App\Http\Controllers\Controller;
use App\Models\V5\Web_Blog;
use App\Models\V5\Web_Blog_Lang;
use App\Models\V5\Web_Content_Html;
use App\Models\V5\Web_Content_Page;
use App\Models\WebNewbannerItemModel;
use App\Models\WebNewbannerModel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class AdminContentPageController extends Controller
{
	public function store(Request $request, $id)
	{
		$webContentPage = Web_Content_Page::newContentPage($request->type_rel, $id, $request->type);

		return response()->json([
			'status' => 'success',
			'message' => 'Contenido creado correctamente',
			'data' => $webContentPage
		]);
	}

	public function update(Request $request, $id, $id_content)
	{
		$webContentPage = Web_Content_Page::query()
			->where([
				['table_rel_content_page', $request->type_rel],
				['rel_id_content_page', $id],
				['id_content_page', $id_content]
			])->first();

		$content = null;

		if (in_array($webContentPage->type_content_page, [Web_Content_Page::TYPE_CONTENT_PAGE_HTML, Web_Content_Page::TYPE_CONTENT_PAGE_TEXT])) {

			$content = $webContentPage->contentHtml()->updateOrCreate(
				['id_content' => $webContentPage->type_id_content_page],
				['html_content' => $request->html]
			);
		} elseif ($webContentPage->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME) {
			$content = $webContentPage->contentResource()->updateOrCreate(
				['id_content' => $webContentPage->type_id_content_page],
				['url_content' => $request->url_iframe]
			);
		} elseif ($webContentPage->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_YOUTUBE) {
			$content = $webContentPage->contentResource()->updateOrCreate(
				['id_content' => $webContentPage->type_id_content_page],
				['url_content' => "https://www.youtube.com/embed/$request->url_iframe"]
			);
		}

		$webContentPage->type_id_content_page = $content->id_content;
		if ($webContentPage->isDirty('type_id_content_page')) {
			$webContentPage->save();
		}

		return response()->json([
			'status' => 'success',
			'message' => 'Contenido actualizado correctamente'
		]);
	}

	public function order(Request $request, $id, $id_content)
	{
		$direction = $request->direction;
		$webContentPage = Web_Content_Page::where([
			['table_rel_content_page', $request->type_rel],
			['rel_id_content_page', $id],
			['id_content_page', $id_content]
		])->first();

		$maxOrder = Web_Content_Page::where([
			['table_rel_content_page', $request->type_rel],
			['rel_id_content_page', $id]
		])->max('order_content_page');

		if ($direction === 'up' && $webContentPage->order_content_page == 1) {
			return response()->json([
				'status' => 'error',
				'message' => 'El elemento ya esta en la parte superior'
			]);
		}

		if ($direction === 'down' && $webContentPage->order_content_page == $maxOrder) {
			return response()->json([
				'status' => 'error',
				'message' => 'El elemento ya esta en la parte inferior'
			]);
		}

		$webContentPage2 = Web_Content_Page::where([
			['table_rel_content_page', $request->type_rel],
			['rel_id_content_page', $id],
			['order_content_page', $direction === 'up' ? $webContentPage->order_content_page - 1 : $webContentPage->order_content_page + 1]
		])->first();

		$webContentPage->order_content_page = $direction === 'up' ? $webContentPage->order_content_page - 1 : $webContentPage->order_content_page + 1;
		$webContentPage2->order_content_page = $direction === 'up' ? $webContentPage2->order_content_page + 1 : $webContentPage2->order_content_page - 1;

		$webContentPage->save();
		$webContentPage2->save();


		return response()->json([
			'status' => 'success',
			'message' => 'Orden actualizado correctamente'
		]);
	}

	public function destroy(Request $request, $id, $id_content)
	{
		$webContentPage = Web_Content_Page::where('id_content_page', $id_content)->first();

		//change order to all content
		Web_Content_Page::where([
			['table_rel_content_page', $request->type_rel],
			['rel_id_content_page', $id],
			['order_content_page', '>', $webContentPage->order_content_page]
		])->decrement('order_content_page');

		$webContentPage->delete();

		//borrar elemento con el que estaba relacionado
		if ($webContentPage->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_HTML) {

			$webContentPage->contentHtml()->delete();
		} elseif ($webContentPage->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_BANNER && $webContentPage->type_id_content_page) {

			$this->destroyAllBannersContent($request->type_rel, $webContentPage->type_id_content_page);
		} elseif ($webContentPage->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE) {

			$webContentPage->deleteMediaResource();
		}

		return response()->json([
			'status' => 'success',
			'message' => 'Contenido eliminado correctamente'
		]);
	}

	private function destroyAllBannersContent($tableRel, $typeId)
	{
		$bannerController = new BannerController();
		$bannerController->borrar($typeId);

		//borar el resto de contenidos que apuntaran al mismo banner, por el momento presupongo que solo hay un idioma más
		//Cuando se implementen idiomas sera necesario cambiarlo
		$otherWebContentPage = Web_Content_Page::where([
			['table_rel_content_page', $tableRel],
			['type_content_page', Web_Content_Page::TYPE_CONTENT_PAGE_BANNER],
			['type_id_content_page', $typeId]
		])->first();

		if ($otherWebContentPage) {
			//reducir orden de bloques
			Web_Content_Page::where([
				['table_rel_content_page', $tableRel],
				['rel_id_content_page', $otherWebContentPage->rel_id_content_page],
				['order_content_page', '>', $otherWebContentPage->order_content_page]
			])->decrement('order_content_page');

			$otherWebContentPage->delete();
		}
	}

	public function setResource(Request $request, $id, $id_content)
	{
		$webContentPage = Web_Content_Page::query()
			->resourcesRelation()
			->when($request->get('type_rel', Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG) == Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG, function ($query) {
				$query->blogLangRelation();
			})
			->where([
				['table_rel_content_page', $request->type_rel],
				['rel_id_content_page', $id],
				['id_content_page', $id_content],
				['type_content_page', $request->type]
			])->first();

		$urlPath = $webContentPage->upsertMediaResouce($request->file('file'));

		$storeInOtherLanguages = Config::get('app.web_content_resource_multilanguage', true);
		if ($storeInOtherLanguages) {
			$this->cloneContentResourceToOtherLanguages($webContentPage);
		}

		return response()->json([
			'status' => 'success',
			'message' => 'Contenido actualizado correctamente',
			'data' => $urlPath
		]);
	}

	private function cloneContentResourceToOtherLanguages(Web_Content_Page $webContentPage)
	{
		//damos por hecho que solo tenemos español e ingles
		$otherWebBlogLang = $webContentPage->webBlogLang->otherLangs()->first();
		if(!$otherWebBlogLang){
			return;
		}

		//buscamos si existe el mismo tipo de contenido en el otro idioma
		$webContentPageLang = Web_Content_Page::query()
			->where([
				['table_rel_content_page', $webContentPage->table_rel_content_page],
				['rel_id_content_page', $otherWebBlogLang->id_web_blog_lang],
				['type_content_page', $webContentPage->type_content_page],
				['type_id_content_page', $webContentPage->type_id_content_page]
			])->first();

		//si no existe lo creamos identico al que ya tenemos pero con el id del otro idioma
		if (!$webContentPageLang) {

			$maxOrder = Web_Content_Page::where([
				['table_rel_content_page', $webContentPage->table_rel_content_page],
				['rel_id_content_page', $otherWebBlogLang->id_web_blog_lang]
			])->max('order_content_page');

			$webContentPageLang = Web_Content_Page::create([
				'table_rel_content_page' => $webContentPage->table_rel_content_page,
				'rel_id_content_page' => $otherWebBlogLang->id_web_blog_lang,
				'type_content_page' => $webContentPage->type_content_page,
				'type_id_content_page' => $webContentPage->type_id_content_page,
				'order_content_page' => $maxOrder + 1,
			]);
		}
	}

	public function uploadAsset(Request $request)
	{
		$request->validate([
			'files' => 'required'
		]);

		$webContentPage = Web_Content_Page::query()
			->where([
				['table_rel_content_page', $request->type_rel],
				['rel_id_content_page', $request->id]
			])->first();

		$files = $request->file('files');
		$paths = [];
		foreach ($files as $file) {
			$paths[] = config('app.url') . $webContentPage->uploadMediaWithoutPersist($file, true);
		}

		return response(['data' => $paths]);
	}

	public function importHtmlToWebBlogLang()
	{
		dump('Antes de comentar esta linea, mirar si el texto_web_blog_lang contiene un div con la clase container');
		dd('En caso de que no lo tenga dejar $addContainer a true');

		Web_Blog_Lang::query()
			//->where('texto_web_blog_lang', '!=', null)
			->join('web_blog', 'web_blog.id_web_blog', '=', 'web_blog_lang.idblog_web_blog_lang')
			->join('web_content_page', 'web_content_page.rel_id_content_page', '=', 'web_blog_lang.id_web_blog_lang', 'left outer')
			->whereNull('web_content_page.id_content_page')
			->whereNotIn('web_blog_lang.idblog_web_blog_lang', [519, 79697, 79699, 79700, 79701])
			->where('web_blog.emp_web_blog', config('app.main_emp'))
			->orderBy('web_blog_lang.idblog_web_blog_lang')
			->limit(4)
			->get()
			->each(function ($webBlogLang) {
				echo 'Id blog: ' . $webBlogLang->idblog_web_blog_lang . '/' . $webBlogLang->id_web_blog_lang . '<br>';

				if (!empty($webBlogLang->texto_web_blog_lang)) {
					$this->importHtmlToContent($webBlogLang->texto_web_blog_lang, $webBlogLang->id_web_blog_lang);
				}

				//only next pass if blog lang is ES language
				if ($webBlogLang->lang_web_blog_lang != 'ES') {
					return;
				}

				$pathImages = public_path('img/blog/' . $webBlogLang->idblog_web_blog_lang);
				if (!file_exists($pathImages)) {
					return;
				}

				echo 'Crear Banner<br>';
				$this->importImagesToContentBanner($pathImages, $webBlogLang->id_web_blog_lang, $webBlogLang->idblog_web_blog_lang);

				echo '---------------------------------<br>';
			});

		echo 'Finalizado';
	}

	private function importHtmlToContent($html, $tableRelationId)
	{
		$addContainer = true;
		$class = $addContainer ? 'container import-container' : 'import-container';
		$newHtml = "<div class=\"{$class}\">{$html}</div>";

		$webContentHtml = Web_Content_Html::create([
			'html_content' => $newHtml
		]);

		$webContentPage = Web_Content_Page::create([
			'table_rel_content_page' => Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG,
			'rel_id_content_page' => $tableRelationId,
			'type_content_page' => Web_Content_Page::TYPE_CONTENT_PAGE_HTML,
			'type_id_content_page' => $webContentHtml->id_content,
			'order_content_page' => 1
		]);

		echo 'Creado contenido html id: ' . $webContentHtml->id_content . '<br>';
		echo 'Creado contenido page id: ' . $webContentPage->id_content_page . '<br>';
		return;
	}

	private function importImagesToContentBanner($pathImages, $tableRelationId, $webBlogId)
	{
		//de alguna manera debo recuperar el id de web_blog y generar un web_content para cada idioma
		$webBlogsLang = Web_Blog_Lang::where('idblog_web_blog_lang', $webBlogId)->get();

		$webContentsPage = [];
		foreach ($webBlogsLang as $webBlogLang) {
			$webContentsPage[] = Web_Content_Page::create([
				'table_rel_content_page' => Web_Content_Page::TABLE_REL_CONTENT_PAGE_BLOG,
				'rel_id_content_page' => $webBlogLang->id_web_blog_lang,
				'type_content_page' => Web_Content_Page::TYPE_CONTENT_PAGE_BANNER,
				'order_content_page' => 2
			]);
		}

		$files = File::files($pathImages);
		$uploadFiles = array_map(function ($file) {
			return new UploadedFile($file, basename($file), null, null, true);
		}, $files);

		$maxBannerId = WebNewbannerModel::withoutGlobalScopes()->max('id');
		$dataToNewBanner = [
			'empresa' => config('app.main_emp'),
			'id' => $maxBannerId + 1,
			'key' => "bl_{$tableRelationId}_{$webContentsPage[0]->id_content_page}",
			'activo' => 1,
			'id_web_newbanner_tipo' => 2,
			'ubicacion' => WebNewbannerModel::UBICACION_BLOG,
		];

		$idNewBanner = WebNewbannerModel::insertGetId($dataToNewBanner);
		foreach ($webContentsPage as $webContentPage) {
			$webContentPage->type_id_content_page = $idNewBanner;
			$webContentPage->save();
		}

		//create items banner
		$bannerController = new BannerController();
		$theme = config('app.theme');
		$mainEmp = config('app.main_emp');

		$maxIdItem = WebNewbannerItemModel::max('id');
		foreach ($uploadFiles as $file) {
			$newItemId = $maxIdItem + 1;

			foreach (array_keys(Config::get("app.locales")) as $lang) {
				$language = strtoupper($lang);

				WebNewbannerItemModel::create([
					'id' => $newItemId,
					'id_web_newbanner' => $idNewBanner,
					'bloque' => 0,
					'lenguaje' => $language,
					'texto' => '',
					'url' => '',
					'ventana_nueva' => 0
				]);

				$directoryPath = str_replace("\\", "/", "/img/banner/$theme/$mainEmp/$idNewBanner/$newItemId");
				if (!is_dir(public_path($directoryPath))) {
					mkdir(public_path($directoryPath), 0775, true);
					chmod(public_path($directoryPath), 0775);
				}

				$bannerController->saveImage($file, $language, $directoryPath, false);
			}

			$maxIdItem++;
		}

		//echo 'Creado banner id: ' . $idNewBanner . '<br>';
		return;
	}
}
