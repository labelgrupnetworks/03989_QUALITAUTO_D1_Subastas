<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\Http\Services\b2b\UserB2BData;
use App\Http\Services\b2b\UserB2BService;
use App\Jobs\MailJob;
use App\Jobs\SendNotificationsJob;
use App\libs\EmailLib;
use App\libs\FormLib;
use App\Mail\AuctionInvitationMail;
use App\Models\V5\FgSub;
use App\Models\V5\FgSubInvites;
use App\Providers\ToolsServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AdminB2BUsersController extends Controller
{
	public function index()
	{
		$ownerCod = Session::get('user.cod');

		$users = FgSubInvites::query()
			->with('invited:cod_cliweb, cod2_cliweb, email_cliweb')
			->where('owner_codcli_subinvites', $ownerCod)
			->orderBy('invited_codcli_subinvites', 'desc')
			->paginate(40);

		$tableParams = [
			'nom_cliweb' => 1,
			'email_cliweb' => 1,
			'cif_cli' => 1,
			'tel1_cli' => 1,
			'notification_is_sent' => 1,
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

	public function notify(Request $request)
	{
		$ownerSession = Session::get('user');
		$ownerCod = $ownerSession['cod'];

		$users = FgSubInvites::query()
			->select('invited_codcli_subinvites', 'invited_nom_subinvites')
			->with('invited:cod_cliweb, email_cliweb, pwdwencrypt_cliweb')
			->where('owner_codcli_subinvites', $ownerCod)
			->when(!$request->input('force'), function ($query) {
				$query->where('notification_sent_subinvites', 0);
			})
			->get();

		$owner = [
			'company_name' => $ownerSession['rsoc'],
			'logo' => $this->getCompanyImageLink($ownerCod),
		];

		$auction = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->first();

		$auction->link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->des_sub, null);

		$delay = 0;
		foreach ($users as $user) {

			$userDataToEmail = [
				'name' => $user->invited_nom_subinvites,
				'email' => $user->invited->email_cliweb,
				'hasPassword' => $user->invited->hasPassword,
				'linkResetPassword' => $user->invited->recoveryLink,
			];

			$notification = new AuctionInvitationMail($owner, $auction->toArray(), $userDataToEmail);

			$emailLib = new EmailLib('AUTION_INVITE');
			if(!empty($emailLib->email)) {
				$emailLib->setHtmlBody($notification->render());
				$emailLib->setTo($userDataToEmail['email']);

				MailJob::dispatch($emailLib)
					->onQueue(Config::get('app.queue_env'))
					->delay(now()->addSeconds($delay));


				FgSubInvites::query()
					->where('owner_codcli_subinvites', $ownerCod)
					->where('invited_codcli_subinvites', $user->invited_codcli_subinvites)
					->update(['notification_sent_subinvites' => 1]);
			}


			/* SendNotificationsJob::dispatch($notification, $userDataToEmail['email'])
				->onQueue(Config::get('app.queue_env'))
				->delay(now()->addSeconds($delay)); */

			//office tiene un limite de 30 correos por minuto.
			//Con el delay evitaremos que se envien todos los correos a la vez.
			$delay += 5;
		}

		return response()->json(['success' => 'Notificaciones enviadas correctamente']);
	}

	public function notifySelection(Request $request)
	{
		$ownerCod = Session::get('user.cod');

		$users = FgSubInvites::query()
			->select('invited_codcli_subinvites', 'invited_nom_subinvites')
			->with('invited:cod_cliweb, email_cliweb, pwdwencrypt_cliweb')
			->where('owner_codcli_subinvites', $ownerCod)
			->whereIn('invited_codcli_subinvites', $request->input('ids'))
			->get();

		$owner = [
			'company_name' => Session::get('user.rsoc'),
			'logo' => $this->getCompanyImageLink($ownerCod),
		];

		$auction = FgSub::query()
			->where('agrsub_sub', $ownerCod)
			->first();

		$auction->link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->des_sub, null);

		$delay = 0;
		foreach ($users as $user) {

			$userDataToEmail = [
				'name' => $user->invited_nom_subinvites,
				'email' => $user->invited->email_cliweb,
				'hasPassword' => $user->invited->hasPassword,
				'linkResetPassword' => $user->invited->recoveryLink,
			];

			$notification = new AuctionInvitationMail($owner, $auction->toArray(), $userDataToEmail);

			$emailLib = new EmailLib('AUTION_INVITE');
			if(!empty($emailLib->email)) {
				$emailLib->setHtmlBody($notification->render());
				$emailLib->setTo($userDataToEmail['email']);

				MailJob::dispatch($emailLib)
					->onQueue(Config::get('app.queue_env'))
					->delay(now()->addSeconds($delay));

				FgSubInvites::query()
					->where('owner_codcli_subinvites', $ownerCod)
					->where('invited_codcli_subinvites', $user->invited_codcli_subinvites)
					->update(['notification_sent_subinvites' => 1]);
			}

			/* SendNotificationsJob::dispatch($notification, $userDataToEmail['email'])
				->onQueue(Config::get('app.queue_env'))
				->delay(now()->addSeconds($delay)); */

			$delay += 5;
		}

		return response()->json(['success' => 'Notificaciones enviadas correctamente']);
	}

	public function destroyAll()
	{
		$ownerCod = Session::get('user.cod');

		FgSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->delete();

		return response()->json(['success' => 'Clientes eliminados correctamente']);
	}

	public function destroySelection(Request $request)
	{
		$ownerCod = Session::get('user.cod');

		FgSubInvites::query()
			->where('owner_codcli_subinvites', $ownerCod)
			->whereIn('invited_codcli_subinvites', $request->input('ids'))
			->delete();

		return response()->json(['success' => 'Clientes eliminados correctamente']);
	}

	private function getCompanyImageLink($ownerCod)
	{
		if (!$ownerCod) {
			return false;
		}

		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');
		$path = "app/public/themes/$theme/owners/$emp/$ownerCod.png";

		if (!file_exists(storage_path($path))) {
			return asset("/themes/$theme/assets/img/logo.png");
		}

		return asset("storage/themes/$theme/owners/$emp/$ownerCod.png");
	}
}
