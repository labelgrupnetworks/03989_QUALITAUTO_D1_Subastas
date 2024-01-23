<?php

namespace App\Http\Controllers\admin\contenido;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\libs\FormLib;
use App\Models\V5\Web_Page;
use App\Providers\RoutingServiceProvider;
use Intervention\Image\Facades\Image;

class AdminPagesController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'contenido']);
	}

    public function index()
    {

		$locales = array_keys(array_change_key_case(config('app.locales'), CASE_UPPER));

        $pages = Web_Page::select('id_web_page', 'name_web_page', 'key_web_page', 'lang_web_page', 'webmetad_web_page', 'webmetat_web_page, webnoindex_web_page')
			->whereIn('lang_web_page', $locales)
			->where('manageable_web_page', '1')
			->orderBy(request('order', 'cast(id_web_page as int)'), request('order_dir', 'asc'))
			->get();

		$tableParams = [
			'id_web_page' => 1, 'name_web_page' => 1, 'key_web_page' => 1,
			'lang_web_page' => 1, 'webmetad_web_page' => 0, 'webmetat_web_page' => 0, 'webnoindex_web_page' => 1
		];

		return view('admin::pages.contenido.pages.index', compact('pages', 'tableParams'));
    }

    public function create()
    {
		$webPage = new Web_Page();
        $form = $this->formWebPage($webPage);

		$path = '/uploads/statics';

		$images = [];
		if (is_dir(getcwd() . $path)) {
			$images = array_slice(scandir(getcwd() . $path), 2);

			foreach ($images as $key => $value) {
				$images[$key] = $path . '/' . $value;
			}
		}

		return view('admin::pages.contenido.pages.create', compact('form', 'webPage', 'images'));
    }

    public function store(Request $request)
    {
		$maxId = Web_Page::withoutGlobalScope('emp')->max('id_web_page') + 1;

		$deleteText = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';

        Web_Page::create([
			'id_web_page' => $maxId,
			'name_web_page' => $request->name_web_page,
			'key_web_page' => $request->key_web_page,
			'lang_web_page' => $request->lang_web_page,
			'content_web_page' => str_replace($deleteText, '', $request->content_web_page),
			'webmetad_web_page' => $request->webmetad_web_page,
			'webmetat_web_page' => $request->webmetat_web_page,
			'webnoindex_web_page' => $request->webnoindex_web_page,
			'manageable_web_page' => 1
		]);

		return redirect()->back()->with(['success' => [0 => 'Creada correctamente']]);
    }

    public function show($id)
    {
		$webPage = Web_Page::select('content_web_page')->where('id_web_page', $id)->first();
		return response(['html' => $webPage->content_web_page]);
    }

    public function edit($id)
    {
        $webPage = Web_Page::where('id_web_page', $id)->first();
		$form = $this->formWebPage($webPage);

		$path = '/uploads/statics';

		$images = [];
		if (is_dir(getcwd() . $path)) {
			$images = array_slice(scandir(getcwd() . $path), 2);

			foreach ($images as $key => $value) {
				$images[$key] = $path . '/' . $value;
			}
		}

		return view('admin::pages.contenido.pages.edit', compact('form', 'webPage', 'images'));
    }

    public function update(Request $request, $id)
    {
		$webPage = Web_Page::where('id_web_page', $id)->first();
		if(!$webPage){
			abort(404);
		}

		$deleteText = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';

		Web_Page::where('id_web_page', $id)->update([
			'name_web_page' => $request->name_web_page,
			'key_web_page' => $request->key_web_page,
			'lang_web_page' => $request->lang_web_page,
			'content_web_page' => str_replace($deleteText, '', $request->content_web_page),
			'webmetad_web_page' => $request->webmetad_web_page,
			'webmetat_web_page' => $request->webmetat_web_page,
			'webnoindex_web_page' => $request->webnoindex_web_page,
		]);

		return redirect()->back()->with(['success' => [0 => 'Actualizado correctamente']]);
    }

    public function destroy($id)
    {
        $webPage = Web_Page::where('id_web_page', $id)->first();

		if(!$webPage){
			abort(404);
		}

		Web_Page::where('id_web_page', $id)->delete();

		return redirect(route('static-pages.index'))->with(['success' => array(trans('admin-app.title.deleted_ok'))]);
    }

	public function uploadImage(Request $request)
	{
		$request->validate([
			'files' => 'required'
		]);

		$images = $request->file('files');

		$name = now()->timestamp;

		$img = Image::make($images[0]->path());

		$img->save(getcwd() . "/uploads/statics/$name.png");

		return response(['data' => config('app.url') . "/uploads/statics/$name.png"]);
	}

	private function formWebPage(Web_Page $webPage)
	{
		$langs = array_change_key_case(config('app.locales'), CASE_UPPER);

		$form = [
			'name_web_page' => FormLib::Text('name_web_page', 1, old('name_web_page', $webPage->name_web_page ?? '')),
			'key_web_page' => FormLib::Text('key_web_page', 1, old('key_web_page', $webPage->key_web_page ?? '')),
			'lang_web_page' => FormLib::Select('lang_web_page', 1, old('lang_web_page', $webPage->lang_web_page ?? 'ES'), $langs, '', '', false),
			'webnoindex_web_page' => FormLib::Select('manageable_web_page', 1, old('webnoindex_web_page', $webPage->webnoindex_web_page ?? '0'), ['1' => 'Si', '0' => 'No'], '', '', false),
			'webmetat_web_page' => FormLib::Text('webmetat_web_page', 1, old('webmetat_web_page', $webPage->webmetat_web_page ?? '')),
			'webmetad_web_page' => FormLib::Textarea('webmetat_web_page', 1, old('webmetad_web_page', $webPage->webmetad_web_page ?? '')),
		];

		return $form;
	}
}
