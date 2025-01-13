<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\Controller;
use App\Services\b2b\OwnerB2BData;
use App\Services\b2b\UserB2BData;
use App\Services\b2b\UserB2BService;
use App\libs\FormLib;
use App\Models\V5\FgSub;
use App\Models\V5\FgSubInvites;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
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
			'rsoc' => FormLib::Text('name', 1, old('name', ''), 'maxlength="60"'),
			'email' => FormLib::Text('email', 1, old('email', ''), 'maxlength="60"'),
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

	public function edit(UserB2BService $userService, $id)
	{
		$ownerCod = Session::get('user.cod');

		$user = $userService->getInvitationsByOwnerAndInvited($ownerCod, $id);

		abort_if(!$user, 404);

		$formulario = (object)[
			'rsoc' => FormLib::Text('name', 1, $user->name, 'maxlength="60"'),
			'email' => FormLib::Text('email', 0, $user->email, 'maxlength="60" readonly '),
			'idnumber' => FormLib::Text("idnumber", 0, $user->idnumber, 'maxlength="20"'),
			'phone' => FormLib::Text('phone', 0, $user->phone, 'maxlength="40"'),
		];

		return view('admin::pages.b2b.users.edit', [
			'formulario' => $formulario,
			'user' => $user,
		]);
	}

	public function update(Request $request, UserB2BService $userService, $id)
	{
		$ownerCod = Session::get('user.cod');

		try {
			$userService->updateInvitation($ownerCod, $id, UserB2BData::fromArray($request->all()));
		} catch (\Throwable $th) {
			return redirect()->back()
				->withErrors([$th->getMessage()])->withInput();
		}

		return redirect(route('admin.b2b.users'))
			->with(['success' => [0 => 'Cliente actualizado correctamente']]);
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

	public function notify(Request $request, UserB2BService $userService)
	{
		$owner = OwnerB2BData::fromArray(Session::get('user'));

		$users = FgSubInvites::query()
			->with('invited:cod_cliweb, email_cliweb, pwdwencrypt_cliweb')
			->where('owner_codcli_subinvites', $owner->id)
			->when(!$request->input('force'), function ($query) {
				$query->where('notification_sent_subinvites', 0);
			})
			->get()
			->map(fn($user) => UserB2BData::fromInvitationWithInvited($user));

		$auction = FgSub::query()
			->where('agrsub_sub', $owner->id)
			->first();

		$auction->link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->des_sub, null);

		$delay = 0;
		foreach ($users as $user) {

			$userService->sendInvitationEmail($owner, $auction, $user, $delay);
			//office tiene un limite de 30 correos por minuto.
			//Con el delay evitaremos que se envien todos los correos a la vez.
			$delay += 5;
		}

		return response()->json(['success' => 'Notificaciones enviadas correctamente']);
	}

	public function notifySelection(Request $request, UserB2BService $userService)
	{
		$owner = OwnerB2BData::fromArray(Session::get('user'));

		$users = FgSubInvites::query()
			->with('invited:cod_cliweb, email_cliweb, pwdwencrypt_cliweb')
			->where('owner_codcli_subinvites', $owner->id)
			->whereIn('invited_codcli_subinvites', $request->input('ids'))
			->get()
			->map(fn($user) => UserB2BData::fromInvitationWithInvited($user));

		$auction = FgSub::query()
			->where('agrsub_sub', $owner->id)
			->first();

		$auction->link = ToolsServiceProvider::url_auction($auction->cod_sub, $auction->des_sub, null);

		$delay = 0;
		foreach ($users as $user) {
			$userService->sendInvitationEmail($owner, $auction, $user, $delay);
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
}
