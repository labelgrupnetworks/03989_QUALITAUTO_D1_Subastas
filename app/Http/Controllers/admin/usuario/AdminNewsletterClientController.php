<?php

namespace App\Http\Controllers\admin\usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\NewsletterClientExport;
use App\libs\FormLib;
use App\Models\Newsletter;
use App\Models\V5\Fx_Newsletter;
use App\Models\V5\FxCliWeb;
use App\Providers\ToolsServiceProvider;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class AdminNewsletterClientController extends Controller
{
	public $newsletterModel;

	public function __construct()
	{
		view()->share(['menu' => 'usuarios']);
		$this->newsletterModel = new Newsletter();
	}

	public function index(Request $request)
	{
		$newslettersSelect = [];
		if (config('app.newsletterFamilies')) {
			$newslettersSelect = collect(explode(',', config('app.newsletterFamilies', '')))->map(function ($item) {
				return "nllist" . trim($item) . "_cliweb";
			});
		}

		$clients = FxCliWeb::select('COD_CLIWEB', 'COD2_CLIWEB', 'EMAIL_CLIWEB', 'NOM_CLIWEB', 'FECALTA_CLIWEB')
			->when($request->cod_cliweb, function ($query, $cod_cliweb) {
				return $query->where('cod_cliweb', $cod_cliweb);
			})
			->when($request->cod2_cliweb, function ($query, $cod2_cliweb) {
				return $query->where('cod2_cliweb', $cod2_cliweb);
			})
			->when($request->email_cliweb, function ($query, $email_cliweb) {
				return $query->where('lower(email_cliweb)', 'like', "%" . mb_strtolower($email_cliweb) . "%");
			})
			->when($request->nom_cliweb, function ($query, $nom_cliweb) {
				return $query->where('lower(nom_cliweb)', 'like', "%" . mb_strtolower($nom_cliweb) . "%");
			})
			->when($request->fecalta_cliweb, function ($query, $fecalta_cliweb) {
				//dd($fecalta_cliweb);
				return $query->where('fecalta_cliweb', '>=', ToolsServiceProvider::getDateFormat($fecalta_cliweb, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->is_client_web, function ($query, $is_client_web) {
				if ($is_client_web == 'S') {
					return $query->where('cod_cliweb', '!=', 0);
				}
				return $query->where('cod_cliweb', 0);
			})
			->when(!empty($newslettersSelect), function ($query) use ($newslettersSelect, $request) {
				$query->addSelect($newslettersSelect->implode(','));
				foreach ($newslettersSelect as $value) {
					$query->when($request->{$value}, function ($query, $nllist_cliweb) use ($value) {
						return $query->where($value, $nllist_cliweb);
					})
						->orWhere($value, 'S');
				}
			}, function ($query) {
				return $query->where('NLLIST1_CLIWEB', 'S');
			})
			->orderBy($request->get('order', 'FECALTA_CLIWEB'), $request->get('order_dir', 'desc'))
			->paginate(20);

		$bool = ['S' => 'Si', 'N' => 'No'];
		$newsletters = [];
		$newsletterTableParam = [];

		foreach ($newslettersSelect as $value) {
			$newsletters[$value] = FormLib::Select($value, 0, $request->{$value}, $bool, '', '', true);
			$newsletterTableParam[$value] = 0;
		}

		$filters = (object)([
			'cod_cliweb' => FormLib::text('cod_cliweb', 0, $request->cod_cliweb),
			'cod2_cliweb' => FormLib::text("cod2_cliweb", 0, $request->cod2_cliweb),
			'email_cliweb' => FormLib::text("email_cliweb", 0, $request->email_cliweb),
			'nom_cliweb' => FormLib::text("nom_cliweb", 0, $request->nom_cliweb),
			'fecalta_cliweb' => FormLib::Date('fecalta_cliweb', 0, $request->fecalta_cliweb),
			'is_client_web' => FormLib::Select('is_client_web', 0, $request->is_client_web, $bool),
		] + $newsletters);

		return view('admin::pages.usuario.newsletter.index', compact('clients', 'filters', 'newsletterTableParam'));
	}

	public function show(Request $request, $newsletter)
	{
		$newsletterName = Fx_Newsletter::where([
			['id_newsletter', $newsletter],
			['lang_newsletter', 'ES']
		])->value('name_newsletter');

		$suscriptions = $this->newsletterModel
			->getSuscriptionsQueryWithCliInfoById($newsletter, false)
			->whereFilters($request)
			->orderBy($request->input('order', 'id_newsletter_suscription'), $request->get('order_dir', 'desc'))
			->paginate(40);

		$filters = ([
			'id_newsletter_suscription' => FormLib::text('id_newsletter_suscription', 0, $request->id_newsletter_suscription),
			'email_newsletter_suscription' => FormLib::text('email_newsletter_suscription', 0, $request->email_newsletter_suscription),
			'cod_cli' => FormLib::text("cod_cli", 0, $request->cod_cli),
			'nom_cli' => FormLib::text("nom_cli", 0, $request->nom_cli),
			'pais_cli' => FormLib::text("pais_cli", 0, $request->pais_cli),
			'lang_newsletter_suscription' => FormLib::Select("lang_newsletter_suscription", 0, $request->lang_newsletter_suscription, ['ES' => 'ES', 'EN' => 'EN']),
			'create_newsletter_suscription' => FormLib::Date('create_newsletter_suscription', 0, $request->create_newsletter_suscription),
		]);

		return view('admin::pages.usuario.newsletter.show', ['suscriptions' => $suscriptions, 'filters' => $filters, 'newsletterName' => $newsletterName, 'newsletterId' => $newsletter]);
	}

	public function destroy(Request $request, $email_cliweb)
	{
		if(Config::get('app.newsletter_table', false)){

		}
		else {
			FxCliWeb::where('email_cliweb', $email_cliweb)->update(['NLLIST1_CLIWEB' => 'N']);
		}

		return redirect()->back()->with('success', array(trans('admin-app.title.deleted_ok')));
	}

	public function export()
	{
		return Excel::download(new NewsletterClientExport, 'clients.xlsx');
	}
}
