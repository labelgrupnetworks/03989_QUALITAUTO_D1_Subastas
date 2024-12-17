<?php

namespace App\Http\Controllers\admin\b2b;

use App\Http\Controllers\admin\subasta\AdminSubastaGenericController;
use App\Http\Controllers\apilabel\ClientController;
use App\Http\Controllers\Controller;
use App\libs\EmailLib;
use App\libs\FormLib;
use App\Models\V5\AucSessions;
use App\Models\V5\FgSub;
use App\Models\V5\FxCli;
use App\Models\V5\FxCliWeb;
use App\Providers\ToolsServiceProvider;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class AdminB2BCompaniesController extends Controller
{
	public function index(Request $request)
	{
		$users = FxCli::query()
			->where('tipo_cli', FxCli::TIPO_CLI_CEDENTE)
			->orderBy($request->input('order', 'cod_cli'), $request->input('order_dir', 'desc'))
			->paginate(40);


		$tableParams = [
			'cod_cli' => 1,
			'nom_cli' => 1,
			'rsoc_cli' => 1,
			'email_cli' => 1,
			'cif_cli' => 1,
			'tel1_cli' => 1,
			'baja_tmp_cli' => 1,
		];

		return view('admin::pages.b2b.companies.index', [
			'users' => $users,
			'tableParams' => $tableParams,
		]);
	}

	public function create()
	{
		$auction = new FgSub();

		$formulario = (object) [
			'company_image' => ['imagen_cli' => FormLib::File("imagen_cli", 0)],
			'info' => [
				'name' => FormLib::Text('name', 1, old('name', ''), 'maxlength="60"'),
				'registeredname' => FormLib::Text('registeredname', 1, old('registeredname', ''), 'maxlength="60"'),
				'email' => FormLib::Text('email', 1, old('email', ''), 'maxlength="60"'),
				'idnumber' => FormLib::Text("idnumber", 0, old('idnumber', ''), 'maxlength="20"'),
				'phone' => FormLib::Text('phone', 0, old('phone', ''), 'maxlength="40"'),
				'temporaryblock' => FormLib::Select('temporaryblock', 0, old('temporaryblock', FxCli::TIPO_BAJA_TMP_NO), (new FxCli())->getTipoBajaTmpTypes(), '', '', false),
			],
			'auction_image' => ['imagen_sub' => FormLib::File("imagen_sub", 0)],
			'auction' => [
				'cod_sub' => FormLib::Text('cod_sub', 1, old('cod_sub', $auction->cod_sub), 'maxlength="8"'),
				'des_sub' => FormLib::Text('des_sub', 1, old('des_sub', $auction->des_sub ?? '&nbsp'), 'maxlength="255"'),
				'descdet_sub' => FormLib::TextAreaTiny('descdet_sub', 0, old('descdet_sub', $auction->descdet_sub))
			],
			'submit' => FormLib::Submit('Guardar', 'companyStore')
		];

		return view('admin::pages.b2b.companies.create', [
			'formulario' => $formulario,
		]);
	}

	public function edit(Request $request, $idCli)
	{
		$user = FxCli::query()
			->select('cod_cli', 'cod2_cli', 'nom_cli', 'rsoc_cli', 'email_cli', 'cif_cli', 'tel1_cli', 'baja_tmp_cli')
			->where('cod_cli', $idCli)
			->first();

		if (!$user) {
			return redirect(route('admin.b2b.companies'))
				->withErrors(['Cliente no encontrado']);
		}

		$user->image = $this->getCompanyImageLink($user);

		$auction = FgSub::query()
			->select('cod_sub', 'des_sub', 'descdet_sub')
			->where('agrsub_sub', $user->cod_cli)
			->first();

		$auction->image = $this->getAuctionImage($auction->cod_sub);

		$formulario = (object) [
			'company_image' => ['imagen_cli' => FormLib::File("imagen_cli", 0)],
			'info' => [
				'name' => FormLib::Text('name', 1, old('name', $user->nom_cli), 'maxlength="60"'),
				'registeredname' => FormLib::Text('registeredname', 1, old('registeredname', $user->rsoc_cli), 'maxlength="60"'),
				'email' => FormLib::Text('email', 1, old('email', $user->email_cli), 'maxlength="60"'),
				'idnumber' => FormLib::Text("idnumber", 0, old('idnumber', $user->cif_cli), 'maxlength="20"'),
				'phone' => FormLib::Text('phone', 0, old('phone', $user->tel1_cli), 'maxlength="40"'),
				'temporaryblock' => FormLib::Select('temporaryblock', 0, old('temporaryblock', $user->baja_tmp_cli ?? FxCli::TIPO_BAJA_TMP_NO), (new FxCli())->getTipoBajaTmpTypes(), '', '', false),
			],
			'auction_image' => ['imagen_sub' => FormLib::File("imagen_sub", 0)],
			'auction' => [
				'cod_sub' => FormLib::Text('cod_sub', 1, old('cod_sub', $auction->cod_sub), 'maxlength="8"'),
				'des_sub' => FormLib::Text('des_sub', 1, old('des_sub', $auction->des_sub ?? '&nbsp'), 'maxlength="255"'),
				'descdet_sub' => FormLib::TextAreaTiny('descdet_sub', 0, old('descdet_sub', $auction->descdet_sub))
			],
			'submit' => FormLib::Submit('Guardar', 'companyStore')
		];

		return view('admin::pages.b2b.companies.edit', [
			'formulario' => $formulario,
			'user' => $user,
			'auction' => $auction
		]);
	}

	public function store(Request $request)
	{
		//Crear usuario.
		$userData = [
			'name' => $request->input('name'),
			'registeredname' => $request->input('registeredname'),
			'email' => $request->input('email'),
			'idnumber' => $request->input('idnumber'),
			'phone' => $request->input('phone'),
			'temporaryblock' => $request->input('temporaryblock'),
			'legalentity' => FxCli::TIPO_FISJUR_JURIDICA,
			'source' => FxCli::TIPO_CLI_CEDENTE,
			'idorigincli' => FxCli::newCod2Cli(),
			'createdate' => date("Y-m-d h:i:s"),
			'updatedate' => date("Y-m-d h:i:s")
		];

		DB::beginTransaction();

		try {
			$user = $this->createCliWithApi($userData);
			$this->addAdminAccess($user);

			//Guardar imagen de empresa.
			if ($request->has('imagen_cli') && $request->file('imagen_cli')->isValid()) {
				$this->addCompanyImage($request->file('imagen_cli'), $user);
			}

			$auctionData = [
				'cod_sub' => $request->input('cod_sub'),
				'des_sub' => $request->input('des_sub'),
				'descdet_sub' => $request->input('descdet_sub'),
				'agrsub_sub' => $user->cod_cliweb,
				"tipo_sub" => FgSub::TIPO_SUB_ONLINE,
				"subc_sub" => FgSub::SUBC_SUB_ACTIVO,
				"dfec_sub" => '2024-01-01',
				"dhora_sub" => '00:00:00',
				"hfec_sub" => '2099-01-01',
				"hhora_sub" => '23:59:59',
			];

			$this->createAuction($auctionData);

			if ($request->has('imagen_sub')) {
				$this->addAuctionImage($request->file('imagen_sub'), $auctionData['cod_sub']);
			}

			//Enviar email de invitación.
			$email = new EmailLib('NEW_OWNER_USER');
			if (!empty($email->email)) {
				$link = $user->recoveryLink;
				$email->setUserByCod($user->cod_cliweb, true);
				$email->setLink_pssw($link);
				$email->send_email();
			}

			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();

			return redirect()->back()
				->withErrors([$e->getMessage()])->withInput();
		}

		return redirect(route('admin.b2b.companies'))
			->with(['success' => [0 => 'Cliente creado correctamente']]);
	}

	public function update(Request $request, $idCli)
	{
		$user = FxCli::query()
			->where('cod_cli', $idCli)
			->first();

		if (!$user) {
			return redirect(route('admin.b2b.companies'))
				->withErrors(['Cliente no encontrado']);
		}

		$userData = [
			'idorigincli' => $user->cod2_cli,
			'name' => $request->input('name'),
			'registeredname' => $request->input('registeredname'),
			'email' => $request->input('email'),
			'idnumber' => $request->input('idnumber'),
			'phone' => $request->input('phone'),
			'temporaryblock' => $request->input('temporaryblock'),
			'legalentity' => FxCli::TIPO_FISJUR_JURIDICA,
			'source' => FxCli::TIPO_CLI_CEDENTE,
			'updatedate' => date("Y-m-d h:i:s")
		];

		if (empty($userData->idorigincli)) {
			$userData['idorigincli'] = FxCli::newCod2Cli($user->cod_cli);
			$userData['setidorigincli'] = 'S';
		}

		DB::beginTransaction();

		try {
			$this->updateCliWithApi($userData);
			if ($request->has('imagen_cli') && $request->file('imagen_cli')->isValid()) {
				$this->addCompanyImage($request->file('imagen_cli'), $user);
			}

			$auctionData = [
				'cod_sub' => $request->input('cod_sub'),
				'des_sub' => $request->input('des_sub'),
				'descdet_sub' => $request->input('descdet_sub'),
			];

			$this->updateAuction($auctionData);

			if ($request->has('imagen_sub')) {
				$this->addAuctionImage($request->file('imagen_sub'), $auctionData['cod_sub']);
			}

			DB::commit();
		} catch (Exception $e) {

			DB::rollBack();
			Log::error('Error updating companie', ['error' => $e]);
			return redirect()->back()
				->withErrors([$e->getMessage()])->withInput();
		}

		return redirect(route('admin.b2b.companies'))
			->with(['success' => [0 => 'Cliente actualizado correctamente']]);
	}

	public function destroy(Request $request, $codCli)
	{
		return response()->json(['success' => 'Clientes eliminados correctamente']);
	}


	private function createCliWithApi($apiUser): FxCliWeb
	{
		$apiRequest = [$apiUser];
		$clientController = new ClientController();
		$json = $clientController->createClient($apiRequest);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			Log::error('Error creating user with API', ['error' => $result]);
			throw new Exception('Error creating user with API');
		}

		return FxCliWeb::query()
			->joinCliCliweb()
			->where('cod2_cliweb', $apiUser['idorigincli'])
			->first();
	}

	private function updateCliWithApi($apiUser)
	{
		$apiRequest = [$apiUser];
		$clientController = new ClientController();
		$json = $clientController->updateClient($apiRequest);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			Log::error('Error updating user with API', ['error' => $result]);
			throw new Exception('Error updating user with API');
		}
	}

	private function addAdminAccess($user)
	{
		FxCliWeb::query()
			->where('cod_cliweb', $user['cod_cliweb'])
			->update(['tipacceso_cliweb' => 'S']);
	}

	private function addCompanyImage($image, $user)
	{
		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');

		$ownerCod = $user->cod_cli ?? $user->cod_cliweb;
		if (!$ownerCod) {
			throw new Exception('No owner cod found');
		}

		//create direcotry if not exists
		$path = "app/public/themes/$theme/owners/$emp";
		if (!file_exists(storage_path($path))) {
			mkdir(storage_path($path), 0777, true);
		}

		Image::make($image)->save(storage_path("$path/$ownerCod.png"));
	}

	private function getCompanyImageLink($user)
	{
		$theme = Config::get('app.theme');
		$emp = Config::get('app.emp');

		$ownerCod = $user->cod_cli ?? $user->cod_cliweb;
		if (!$ownerCod) {
			throw new Exception('No owner cod found');
		}

		$path = "app/public/themes/$theme/owners/$emp/$ownerCod.png";
		if (!file_exists(storage_path($path))) {
			return null;
		}

		return asset("storage/themes/$theme/owners/$emp/$ownerCod.png");
	}

	private function createAuction($auctionSubData)
	{
		FgSub::create($auctionSubData);
		$id_auc_session = AucSessions::withoutGlobalScopes()->max('"id_auc_sessions"') + 1;

		$auc_session_attributes = [
			'"auction"' => $auctionSubData['cod_sub'],
			'"id_auc_sessions"' => $id_auc_session,
			'"reference"' => '001',
			'"start"' => new DateTime('2024-01-01 00:00:00'),
			'"end"' => new DateTime('2099-01-01 23:59:59'),
			'"init_lot"' => 1,
			'"end_lot"' => 99999,
			'"name"' => $auctionSubData['des_sub'],
			'"description"' => mb_substr($auctionSubData['descdet_sub'], 0, 1000, 'UTF-8')
		];

		AucSessions::create($auc_session_attributes);
	}

	private function updateAuction($auctionSubData)
	{
		FgSub::query()
			->where('cod_sub', $auctionSubData['cod_sub'])
			->update($auctionSubData);

		AucSessions::query()
			->where('"auction"', $auctionSubData['cod_sub'])
			->update([
				'"name"' => $auctionSubData['des_sub'],
				'"description"' => mb_substr($auctionSubData['descdet_sub'], 0, 1000, 'UTF-8')
			]);
	}

	private function addAuctionImage($image, $codSub)
	{
		(new AdminSubastaGenericController)->saveFgSubImage($image, $codSub, true, true);
	}

	private function getAuctionImage($codSub)
	{
		return ToolsServiceProvider::auctionImage($codSub, 'real', '001');
	}
}
