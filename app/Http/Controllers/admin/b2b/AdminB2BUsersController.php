<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\Http\Services\b2b\UserB2BData;
use App\Http\Services\b2b\UserB2BService;
use App\Jobs\SendNotificationsJob;
use App\libs\FormLib;
use App\Mail\AuctionInvitationMail;
use App\Models\V5\FgSub;
use App\Models\V5\FxSubInvites;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AdminB2BUsersController extends Controller
{
	public function index()
	{
		$ownerCod = Session::get('user.cod');

		$users = FxSubInvites::query()
			->with('invited:cod_cliweb, cod2_cliweb, email_cliweb')
			->where('owner_codcli_subinvites', $ownerCod)
			->orderBy('invited_codcli_subinvites', 'desc')
			->paginate(40);

		$tableParams = [
			'nom_cliweb' => 1,
			'email_cliweb' => 1,
			'cif_cli' => 1,
			'tel1_cli' => 1,
		];

		return view('admin::pages.b2b.users.index', [
			'users' => $users,
			'tableParams' => $tableParams,
		]);
	}

	public function create()
	{
		$formulario = (object)[
			'name' => FormLib::Text('name', 0, old('name', ''), 'maxlength="60"'),
			'email' => FormLib::Text('email', 0, old('email', ''), 'maxlength="60"'),
			'idnumber' => FormLib::Text("idnumber", 0, old('idnumber', ''), 'maxlength="20"'),
			'phone' => FormLib::Text('phone', 0, old('phone', ''), 'maxlength="40"'),
		];

		return view('admin::pages.b2b.users.create', [
			'formulario' => $formulario,
		]);
	}

	public function store(Request $request, UserB2BService $userService)
	{

		try {
			$userService->createInvitation(Session::get('user.cod'), UserB2BData::fromArray($request->all()));
		} catch (\Throwable $th) {
			return redirect()->back()
				->withErrors([$th->getMessage()])->withInput();
		}

		return redirect(route('admin.b2b.users'))
			->with(['success' => [0 => 'Cliente creado correctamente']]);
	}

	public function import(Request $request, UserB2BService $userService)
	{
		$ownerCod = Session::get('user.cod');

		$request->validate([
			'file' => 'required|file|mimes:xlsx,xls',
		]);

		$userService->importFromExcel($ownerCod, $request->file('file'));

		return redirect(route('admin.b2b.users'))
			->with(['success' => [0 => 'Clientes importados correctamente']]);
	}

	public function notify()
	{
		$owner = Session::get('user');
		$ownerCod = $owner['cod'];

		$users = FxSubInvites::query()
			->with('invited:cod_cliweb, cod2_cliweb, nom_cliweb, email_cliweb, cif_cli, tel1_cli')
			->where('owner_codcli_subinvites', $ownerCod)
			->get();

		$owner = [
			'company_name' => $owner['rsoc'],
			'logo' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png'
		];

		$auction = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->first();

		$auction->link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->des_sub, null);

		$delay = 0;
		foreach ($users as $user) {
			$notification = new AuctionInvitationMail($owner, $auction->toArray(), $user->invited->toArray());

			SendNotificationsJob::dispatch($notification, $user->invited->email_cliweb)
				->onQueue(Config::get('app.queue_env'))
				->delay(now()->addSeconds($delay));

			//office tiene un limite de 30 correos por minuto.
			//Con el delay evitaremos que se envien todos los correos a la vez.
			$delay += 5;
		}

		return response()->json(['success' => 'Notificaciones enviadas correctamente']);
	}

	public function destroyAll()
	{
		$ownerCod = Session::get('user.cod');

		FxSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->delete();

		return response()->json(['success' => 'Clientes eliminados correctamente']);
	}
}
