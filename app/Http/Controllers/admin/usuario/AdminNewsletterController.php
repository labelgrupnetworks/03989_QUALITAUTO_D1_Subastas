<?php

namespace App\Http\Controllers\admin\usuario;

use App\Exports\MailChimpExport;
use App\Exports\NewslettersExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\V5\Fx_Newsletter;

class AdminNewsletterController extends Controller
{
	public $newsletterModel;

	public function __construct()
	{
		view()->share(['menu' => 'usuarios']);
		$this->newsletterModel = new Newsletter();
	}

	public function index()
	{
		$newsletters = Fx_Newsletter::whereLang()->withCount('suscriptors')->get();
		return view('admin::pages.usuario.newsletter.new_index', ['newsletters' => $newsletters]);
	}

	public function edit($id)
	{
		$newsletter = Fx_Newsletter::when(isMultilanguage(), function($query) {
			return $query->with('languages');
		})
		->findOrFail($id);
		return response()->json(['newsletter' => $newsletter]);
	}

	public function store(Request $request)
	{
		$names = $request->input("newsletter");
		$locale = mb_strtoupper(config('app.locale'));
		$id_newsletter = Fx_Newsletter::max('id_newsletter') + 1;

		Fx_Newsletter::create([
			'id_newsletter' => $id_newsletter,
			'name_newsletter' => $names[$locale],
			'lang_newsletter' => $locale
		]);

		unset($names[$locale]);

		collect($names)->filter()->each(function($name, $lang) use ($id_newsletter) {
			Fx_Newsletter::Create([
				'id_newsletter' => $id_newsletter,
				'name_newsletter' => $name,
				'lang_newsletter' => $lang
			]);
		});

		$response['success'][] = trans('admin-app.title.created_ok');
		return back()->with($response);
	}

	public function update(Request $request, $id)
	{
		$names = $request->input("newsletter");
		$newsletterLocale = Fx_Newsletter::findOrFail($id);

		$locale = mb_strtoupper(config('app.locale'));
		$newsletterLocale->name_newsletter = $names[$locale];
		$newsletterLocale->save();

		unset($names[$locale]);

		collect($names)->filter()->each(function($name, $lang) use ($newsletterLocale) {
			Fx_Newsletter::updateOrCreate(
				['id_newsletter'=> $newsletterLocale->id_newsletter, 'lang_newsletter' => $lang],
				['name_newsletter' => $name]
			);
		});

		$response['success'][] = trans('admin-app.title.updated_ok');
		return back()->with($response);
	}

	public function destroy($id_newsletter)
	{
		Fx_Newsletter::where('id_newsletter', $id_newsletter)->delete();
		$response['success'][] = trans('admin-app.title.deleted_ok');
		return back()->with($response);
	}

	public function export(Request $request)
	{
		$typesExport = [
			'csv' => \Maatwebsite\Excel\Excel::CSV,
			'xlsx' => \Maatwebsite\Excel\Excel::XLSX
		];
		//NewslettersExport
		$format = $request->input('format');
		$service = $request->input('service', null);
		$date = now()->format('Y_m_d\TH_i_s');

		if($service == 'mailchimp') {
			return (new MailChimpExport(false))->download("export_{$date}.$format", $typesExport[$format]);
		}

		return (new NewslettersExport())->download("export_{$date}.$format", $typesExport[$format]);
	}
}
