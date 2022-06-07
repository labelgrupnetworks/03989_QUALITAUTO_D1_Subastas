<?php

namespace App\Http\Controllers\admin\usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\NewsletterClientExport;
use App\libs\FormLib;
use App\Models\V5\FxCliWeb;
use App\Providers\ToolsServiceProvider;
use Maatwebsite\Excel\Facades\Excel;

class AdminNewsletterClientController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'usuarios']);
	}

	public function index(Request $request)
	{
		$clients = FxCliWeb::select('COD_CLIWEB', 'COD2_CLIWEB', 'EMAIL_CLIWEB', 'NOM_CLIWEB', 'FECALTA_CLIWEB')
			->where('NLLIST1_CLIWEB', 'S')
			->when($request->cod_cliweb, function($query, $cod_cliweb){
				return $query->where('cod_cliweb', $cod_cliweb);
			})
			->when($request->cod2_cliweb, function($query, $cod2_cliweb){
				return $query->where('cod2_cliweb', $cod2_cliweb);
			})
			->when($request->email_cliweb, function($query, $email_cliweb){
				return $query->where('lower(email_cliweb)', 'like', "%".mb_strtolower($email_cliweb)."%");
			})
			->when($request->nom_cliweb, function($query, $nom_cliweb){
				return $query->where('lower(nom_cliweb)', 'like', "%".mb_strtolower($nom_cliweb)."%");
			})
			->when($request->fecalta_cliweb, function($query, $fecalta_cliweb){
				//dd($fecalta_cliweb);
				return $query->where('fecalta_cliweb', '>=', ToolsServiceProvider::getDateFormat($fecalta_cliweb, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->is_client_web, function($query, $is_client_web){
				if($is_client_web == 'S'){
					return $query->where('cod_cliweb', '!=', 0);
				}
				return $query->where('cod_cliweb', 0);
			})
			->orderBy($request->get('order', 'FECALTA_CLIWEB'), $request->get('order_dir', 'desc'))
			->paginate(20);


		$filters = (object)[
			'cod_cliweb' => FormLib::text('cod_cliweb', 0, $request->cod_cliweb),
			'cod2_cliweb' => FormLib::text("cod2_cliweb", 0, $request->cod2_cliweb),
			'email_cliweb' => FormLib::text("email_cliweb", 0, $request->email_cliweb),
			'nom_cliweb' => FormLib::text("nom_cliweb", 0, $request->nom_cliweb),
			'fecalta_cliweb' => FormLib::Date('fecalta_cliweb', 0, $request->fecalta_cliweb),
			'is_client_web' => FormLib::Select('is_client_web', 0, $request->is_client_web, ['S' => 'Si', 'N' => 'No']),
		];

		return view('admin::pages.usuario.newsletter.index', compact('clients', 'filters'));
	}

	public function destroy(Request $request, $email_cliweb)
	{
		FxCliWeb::where('email_cliweb', $email_cliweb)->update(['NLLIST1_CLIWEB' => 'N']);

		return redirect()->back()->with('success', array(trans('admin-app.title.deleted_ok')) );
	}

	public function export()
	{
		return Excel::download(new NewsletterClientExport, 'clients.xlsx');
	}
}
