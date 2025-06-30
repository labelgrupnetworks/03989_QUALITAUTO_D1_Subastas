<?php

namespace App\Http\Controllers\admin\usuario;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\libs\FormLib;
use App\Models\V5\FxCli;   // Clientes
use App\Models\V5\FsPaises;  // Paises
use App\Exports\ClientsExport;
use App\Http\Controllers\apilabel\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Requests\admin\ClienteRequest;
use App\Models\Newsletter;
use App\Models\V5\FgSg;
use App\Models\V5\FsIdioma;
use App\Models\V5\FsOrigen;
use App\Models\V5\FxCliOrigen;
use Illuminate\Http\Request;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\FxTcli;
use stdClass;
use App\Models\V5\FsParams;
use App\Models\V5\FxCliWeb;
use Illuminate\Support\Facades\Session;

class AdminClienteController extends Controller
{
	private $userSession;

	protected $availableColumns = [
		'cod_cli' => 'Código',
		'nom_cli' => 'Nombre',
		'rsoc_cli' => 'Razón Social',
		'email_cli' => 'Email',
		'falta_cliweb' => 'Fecha alta cliente web',
	];

	function __construct()
	{
		view()->share(['menu' => 'usuarios']);
	}


	public function index(Request $request)
	{
		$newslettersSelect = [];
		if (config('app.newsletterFamilies') && !config('app.newsletter_table')) {
			$newslettersSelect = collect(explode(',', config('app.newsletterFamilies', '')))->map(function ($item) {
				return "nllist" . trim($item) . "_cliweb";
			});
		}

		$clientes = self::clientsQueryBuilder()
			->select([
				"FXCLI.COD_CLI",
				"FXCLI.COD2_CLI",
				"FXCLI.TIPO_CLI",
				"FXCLI.RSOC_CLI",
				"FXCLI.EMAIL_CLI",
				"FXCLI.TEL1_CLI",
				"FXCLI.PAIS_CLI",
				"FXCLI.PRO_CLI",
				"fxcli.cp_cli",
				"fxcli.pob_cli",
				"fxcli.dir_cli",
				"fxcli.dir2_cli",
				"fxcli.sg_cli",
				"FXCLI.IDIOMA_CLI",
				"FXCLI.FISJUR_CLI",
				"FXCLI.BAJA_TMP_CLI",
				"FXCLI.F_MODI_CLI"
			])
			->addSelect("NVL(FXCLI.NOM_CLI, FXCLIWEB.NOM_CLIWEB) as NOM_CLI", "NVL(FXCLI.F_ALTA_CLI,FXCLIWEB.FECALTA_CLIWEB) as FECALTA_CLIWEB")
			->addSelect('FXCLID.EMAIL_CLID');


		$clientes = self::clientsQueryFilters($clientes, $request);

		//newsletters
		if (!empty($newslettersSelect)) {
			$clientes = $clientes->addSelect($newslettersSelect->implode(','));
			foreach ($newslettersSelect as $value) {
				$clientes = $clientes->when($request->{$value}, function ($query, $nllist_cliweb) use ($value) {
					return $query->where($value, $nllist_cliweb);
				});
			}
		}


		$clientes = $clientes->orderBy(request('order', 'cast(fxcli.cod_cli as int)'), request('order_dir', 'desc'));

		/* $test = DB::select(Str::replaceArray('?', $clientes->getBindings(), $clientes->toSql()));
			dd($test); */

		$clientes = $clientes->paginate(30);

		$fxcli = new FxCli();
		$bool = ['S' => 'Si', 'N' => 'No'];

		$newsletters = [];
		$newsletterTableParam = [];

		foreach ($newslettersSelect as $value) {
			$newsletters[$value] = FormLib::Select($value, 0, $request->{$value}, $bool, '', '', true);
			$newsletterTableParam[$value] = 0;
		}

		$catalogs = [];

		$formulario = (object)([
			'cod_cli' => FormLib::Text('cod_cli', 0, $request->cod_cli),
			'cod2_cli' => FormLib::Text('cod2_cli', 0, $request->cod2_cli),
			'tipo_cli' => FormLib::Select('tipo_cli', 0, $request->tipo_cli, FxTcli::pluck('des_tcli', 'cod_tcli')),
			'nom_cli' => FormLib::Text('nom_cli', 0, $request->nom_cli),
			'rsoc_cli' => FormLib::Text('rsoc_cli', 0, $request->rsoc_cli),
			'email_cli' => FormLib::Text('email_cli', 0, $request->email_cli),
			'tel1_cli' => FormLib::Text('tel1_cli', 0, $request->tel1_cli),
			'pais_cli' => FormLib::Text('pais_cli', 0, $request->pais_cli),
			'pro_cli' => FormLib::Text('pro_cli', 0, $request->pro_cli),
			'cp_cli' => FormLib::Text('cp_cli', 0, $request->cp_cli),
			'pob_cli' => FormLib::Text('pob_cli', 0, $request->pob_cli),
			'complete_direction' => FormLib::Text('complete_direction', 0, $request->complete_direction),
			'idioma_cli' => FormLib::Text('idioma_cli', 0, $request->idioma_cli),
			'fisjur_cli' => FormLib::Select('fisjur_cli', 0, $request->fisjur_cli, $fxcli->getTipoFisJurTypes()),
			'baja_tmp_cli' => FormLib::Select('baja_tmp_cli', 0, $request->baja_tmp_cli, $fxcli->getTipoBajaTmpTypes()),
			'fecalta_cliweb' => FormLib::Date('fecalta_cliweb', 0, $request->fecalta_cliweb),
			'f_modi_cli' => FormLib::Date('f_modi_cli', 0, $request->f_modi_cli),
			'envcat_cli2' => FormLib::Select("envcat_cli2", 0, $request->envcat_cli2, $bool, '', '', true),
			'email_clid' => FormLib::Text('email_clid', 0, $request->email_clid),
		] + $newsletters);

		$tableParams = [
			'cod_cli' => Config::get('external_id', 1),
			'cod2_cli' => Config::get('external_id', 0),
			'tipo_cli' => 1,
			'nom_cli' => 1,
			'rsoc_cli' => 0,
			'email_cli' => 1,
			'tel1_cli' => 0,
			'pais_cli' => 0,
			'pro_cli' => 0,
			'cp_cli' => 0,
			'pob_cli' => 0,
			'complete_direction' => 0,
			'idioma_cli' => 0,
			'fisjur_cli' => 1,
			'baja_tmp_cli' => 0,
			'fecalta_cliweb' => 1,
			'f_modi_cli' => 1,
			'envcat_cli2' => 1,
			'email_clid' => 0
		] + $newsletterTableParam;

		$availableColumns = $this->availableColumns;
		$visibleColumns = array_keys($this->availableColumns);

		return view('admin::pages.usuario.cliente_v2.index', compact('clientes', 'formulario', 'fxcli', 'tableParams', 'newslettersSelect', 'availableColumns', 'visibleColumns'));
	}

	function create()
	{
		$fxcli = new FxCli();
		$cliente = new stdClass();
		$cliente->idorigincli = FxCli::newCod2Cli();
		$save = 'clientesStore';
		$formulario = (object) $this->basicFormCreateFxCli($fxcli, $cliente, $save);

		return view('admin::pages.usuario.cliente_v2.create', compact('formulario', 'fxcli'));
	}

	public function store(ClienteRequest $request)
	{
		//Buscamos los paises para obtener su nombre
		$paises = FsPaises::selectBasicPaises()->select("des_paises")->where('cod_paises', 'like', $request->countryshipping)->first();

		//A parte de los campos validados añadimos los que no vienen por el formulario
		$cliente = $request->validated();
		$cliente += [
			'namecountryshipping' => $paises->des_paises ?? '',
			'createdate' => date("Y-m-d h:i:s"),
			'updatedate' => date("Y-m-d h:i:s"),
		];

		//Sobrescribimios campos
		//el cod2cli lo volvemos a generar por si acaso se registra alguien mientras guardamos un nuevo usuario
		$cliente['idorigincli'] = FxCli::newCod2Cli();
		$cliente['password'] = $this->passwordEncrypt($request->password);

		//Array para enviar a api
		$clientes[] = $cliente;

		$clientController = new ClientController();
		$json = $clientController->createClient($clientes);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
				->withErrors($json)->withInput();
		}

		$cod_cli = FxCli::select('cod_cli')->where('cod2_cli', $cliente['idorigincli'])->first()->cod_cli ?? null;

		//Origen de datos los guardamos directamente en su tabla
		if ($request->provenance && FsOrigen::first()) {
			$this->addOrigenes($request, $cod_cli);
		}

		(new Newsletter())->subscribeToExternalService($cliente['email']);

		return redirect(route('clientes.index'))
			->with(['success' => [0 => 'Cliente creado correctamente']]);
	}

	function edit($cod_cli)
	{
		//Datos del cliente
		$clienteFxCli = Fxcli::with('cli2:cod_cli2, envcat_cli2')->leftJoinCliWebCli()->select('*') //->select('cod2_cli', 'cod_cli', 'tipacceso_cliweb')
			->with('origenes')
			->where('cod_cli', $cod_cli)->first();

		//Si no existe, error
		if (!$clienteFxCli) {
			abort(419);
		}

		//Buscamos los datos en la api
		$clientController = new ClientController();
		$json = $clientController->showClient(['codcli' => $clienteFxCli->cod_cli]);
		$result = json_decode($json);

		#No todos los clientes utilizan idorigin, mirar de realizar otro proceso fuera de la api para estos
		if ($result->status == 'ERROR') {
			abort(419);
		}

		$cliente = $result->items[0];

		//formulario
		$save = 'clientesUpdate';
		$formulario = (object) $this->basicFormCreateFxCli($clienteFxCli, $cliente, $save);

		//sobresctibimos campos no editables
		$formulario->identificacion['codcli'] = FormLib::TextReadOnly('codcli', 0, $cliente->codcli);

		//archivos de cliente
		$files = (new AdminClienteFilesController())->getClientFiles($cliente->codcli);

		//dnis
		$dnisPaths = (new UserController)->getCIFImages($cliente->codcli);
		$dnis = array_map(function ($dni) {
			return [
				'path' => $dni,
				'mime' => mime_content_type($dni),
				'filename' => basename($dni),
				'base64' => base64_encode(file_get_contents($dni)),

			];
		}, $dnisPaths);

		return view('admin::pages.usuario.cliente_v2.edit', compact('formulario', 'clienteFxCli', 'cliente', 'files', 'dnis'));
	}

	public function update(ClienteRequest $request)
	{
		$paises = FsPaises::selectBasicPaises()->select("des_paises")->where('cod_paises', 'like', $request->countryshipping)->first();

		$cliente = $request->validated();
		$cliente += [
			'namecountryshipping' => $paises->des_paises ?? '',
			'updatedate' => date("Y-m-d h:i:s"),
		];

		/**
		 * Ojo, no se puede editar el email hasta que el cliente no tenga idorigincli
		 * */
		//Si no tiene idorigincli, debemos enviar setidorigincli para poder añadirlo
		if (empty($request->idorigincli)) {
			$cliente['idorigincli'] = FxCli::newCod2Cli($request->codcli);
			$cliente['setidorigincli'] = 'S';
		}

		//Si no viene nuevo password eliminamos el campo vacio para que no lo actualice
		unset($cliente['password']);
		if (!empty($request->password)) {
			$cliente['password'] = $this->passwordEncrypt($request->password);
		}

		$clientes[] = $cliente;

		$clientController = new ClientController();
		$json = $clientController->updateClient($clientes);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return redirect()->back()
				->withErrors($json)->withInput();
		}

		$this->modifyUserUpdate([$cliente['idorigincli']]);

		//Origen de datos los guardamos directamente en su tabla
		if (FsOrigen::first()) {
			$this->addOrigenes($request, $request->codcli);
		}

		if (config('app.newsletter_table')) {

			$families = array_filter($request->input('families', []), function ($family) {
				return $family === 'S';
			});
			(new Newsletter())
				->setAttributes($request->input('language'), $request->input('email'), $families)
				->suscribe(true, 'admin');
		}

		//(new Newsletter())->subscribeToExternalService($cliente['email']);

		return redirect(route('clientes.index'))
			->with(['success' => [0 => 'Cliente actualizado correctamente']]);
	}

	public function destroy($id)
	{
		if (empty($id)) {
			return response('Error al eliminar el cliente');
		}

		$idCli = [
			'idorigincli' => $id,
		];
		$clientController = new ClientController();
		$json = $clientController->eraseClient($idCli);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response('Error al eliminar el cliente');
		}

		return response('Cliente eliminado correctamente');
	}

	function export(Request $request)
	{
		return (new ClientsExport($request))->download("clientes" . "_" . date("Ymd") . ".xlsx");
	}

	#usamos esta función para poder llamar al web service desde el admin
	function send_ws(Request $request)
	{
		#por seguridad solo podrá ejecutar este código el usuari ode subastas
		if (Config::get('app.WebServiceClient') && (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')) {
			$theme  = Config::get('app.theme');
			$rutaClientcontroller = "App\Http\Controllers\\externalws\\$theme\ClientController";

			$clientController = new $rutaClientcontroller();
			$clientController->createClient($request->codcli);
		}
	}

	function modificarBajaTemporal(Request $request)
	{
		if (!$request->filled('id_cli') && !$request->filled('baja_tmp')) {
			return response('error faltan dades', 400);
		}

		FxCli::where("cod_cli", $request->id_cli)->update(['baja_tmp_cli' => $request->baja_tmp]);
		return response('success');
	}

	protected function basicFormCreateFxCli(FxCli $fxcli, $cliente, $save)
	{
		$countries = DB::table("FsPaises")->orderby("des_paises")->pluck('des_paises', 'cod_paises');
		$country_selected = 'ES';

		$typesCli = FxTcli::pluck('des_tcli', 'cod_tcli');

		$isNotCliWeb = empty($fxcli->cod_cliweb) ? 'S' : 'N';

		$booleanSelectOptions = ['S' => 'Si', 'N' => 'No'];

		$form = [
			'identificacion' => [
				'idorigincli' => FormLib::TextReadOnly('idorigincli', 0, $cliente->idorigincli),
				//'codcli' => FormLib::Text('codcli', 0, old('codcli', $cliente->codcli ?? ''), 'maxlength="8"'),
				'email' => FormLib::Text('email', 0, old('email', $cliente->email ?? ''), 'maxlength="60"'),
				'source' => FormLib::Select('source', 0, old('source', $cliente->source ?? ''), $typesCli, ''),
				'temporaryblock' => FormLib::Select('temporaryblock', 0, old('temporaryblock', $cliente->temporaryblock ?? FxCli::TIPO_BAJA_TMP_NO), $fxcli->getTipoBajaTmpTypes(), '', '', false),
			],
			'clienteweb' => [
				//Al ser una logica inversa, es más facil de entender al reves (Crear usuario web -> S / N)
				'notwebuser' => FormLib::Select('notwebuser', 1, old('notwebuser', $isNotCliWeb), ['S' => 'No', 'N' => 'Si']),
				'password' => FormLib::Password("password", 0, "", 0, "Mín. 6 caracteres."),
			],
			'datosPersonales' => [
				'legalentity' => FormLib::Select('legalentity', 0, old('legalentity', $cliente->legalentity ?? ''), $fxcli->getTipoFisJurTypes(), ''),
				'registeredname' => FormLib::Text('registeredname', 0, old('registeredname', $cliente->registeredname ?? ''), 'maxlength="60"'),
				'name' => FormLib::Text('name', 0, old('name', $cliente->name ?? ''), 'maxlength="60"'),
				'language' => FormLib::Select('language', 0, old('language', $cliente->language ?? ''), FsIdioma::getArrayValues(), '', '', false),
				'documenttype' => FormLib::Select('documenttype', 0, old('documenttype', $cliente->documenttype ?? ''), $fxcli->getTipoDocumento(), ''),
				'idnumber' => FormLib::Text("idnumber", 0, old('idnumber', $cliente->idnumber ?? ''), 'maxlength="20"'),
				'typerepresentative' => FormLib::Select('typerepresentative', 0, old('typerepresentative', $cliente->typerepresentative ?? ''), $fxcli->getTipoRep(), ''),
				'docrepresentative' => FormLib::Text('docrepresentative', 0, old('docrepresentative', $cliente->docrepresentative ?? ''), 'maxlength="20"'),
				'notes' => FormLib::Textarea('notes', 0, old('notes', $cliente->notes ?? ''), 'maxlength="200"'),

			],
			'direccionFacturacion' => [
				'country' => FormLib::Select("country", 0, old('country', $cliente->country ?? $country_selected), $countries),
				'zipcode' => FormLib::Text('zipcode', 0, old('zipcode', $cliente->zipcode ?? ''), 'maxlength="10"'),
				'province' => FormLib::Text('province', 0, old('province', $cliente->province ?? ''), 'maxlength="30"'),
				'city' => FormLib::Text('city', 0, old('city', $cliente->city ?? ''), 'maxlength="30"'),
				'track' => FormLib::Select('track', 0, old('track', $fxcli->sg_cli ?? null), FgSg::getList(), '', '', false),
				'address' => FormLib::Text('address', 0, old('address', $cliente->address ?? ''), 'maxlength="60"'),
				'prefix' => FormLib::Text('prefix', 0, old('prefix', $cliente->prefix ?? ''), 'maxlength="4"'),
				'phone' => FormLib::Text('phone', 0, old('phone', $cliente->phone ?? ''), 'maxlength="40"'),
				'mobile' => FormLib::Text('mobile', 0, old('mobile', $cliente->mobile ?? ''), 'maxlength="40"'),
				'fax' => FormLib::Text('fax', 0, old('fax', $cliente->fax ?? ''), 'maxlength="40"'),
			],
			'direccionEnvio' => [
				'countryshipping' => FormLib::Select("countryshipping", 0,  old('countryshipping', $cliente->countryshipping ?? $country_selected), $countries), //
				'zipcodeshipping' => FormLib::Text('zipcodeshipping', 0, old('zipcodeshipping', $cliente->zipcodeshipping ?? ''), 'maxlength="10"'),
				'provinceshipping' => FormLib::Text('provinceshipping', 0, old('provinceshipping', $cliente->provinceshipping ?? ''), 'maxlength="30"'),
				'cityshipping' => FormLib::Text('cityshipping', 0, old('cityshipping', $cliente->cityshipping ?? ''), 'maxlength="30"'),
				'addressshipping' => FormLib::Text('addressshipping', 0, old('addressshipping', $cliente->addressshipping ?? ''), 'maxlength="60"'),
				'emailshipping' => FormLib::Text('emailshipping', 0, old('email', $cliente->emailshipping ?? ''), 'maxlength="60"'),
				'phoneshipping' => FormLib::Text('phoneshipping', 0, old('phoneshipping', $cliente->phoneshipping ?? ''), 'maxlength="40"'),
				'mobileshipping' => FormLib::Text('mobileshipping', 0, old('mobileshipping', $cliente->mobileshipping ?? ''), 'maxlength="40"'),
			],
			'newsletters' => $this->newsletterForm($fxcli, $cliente),
			'additional' => [
				'enviocatalogo' =>  FormLib::Select('enviocatalogo', 0, old('enviocatalogo', $fxcli->cli2->envcat_cli2 ?? 'N'), $booleanSelectOptions, '', '', false),
			],
			'submit' => FormLib::Submit('Guardar', $save)
		];

		//De manera provisional, en ansorena galeria, necesitamos guardar en newslleter20 la suscripcion a catalogo
		if (config('app.catalogo_newsletter')) {
			$position = config('app.catalogo_newsletter');
			$form['additional']["newsletter$position"] = FormLib::select("newsletter$position", 0, old("newsletter$position", $cliente->{"newsletter$position"} ?? 'N'), $booleanSelectOptions, '', '', false);
		}

		$origenes = FsOrigen::pluck('des_origen', 'id_origen')->toArray();
		if (!empty($origenes)) {
			$form['additional']['provenance'] = FormLib::Select2WithArray('provenance', false, old('provenance', $fxcli->origenes->pluck('id_origen')->toArray()), $origenes, false, true);
		}

		return $form;
	}

	private function newsletterForm($fxCli, $apiCli)
	{
		$newsletters = [];
		$booleanSelectOptions = ['S' => 'Si', 'N' => 'No'];

		if (config('app.newsletter_table')) {
			$newsletterRepo = new Newsletter();
			$subsriptions = $newsletterRepo->getSuscriptionsWithNamesByEmail($fxCli->email_cliweb ?? '');
			$newslettersNames = $newsletterRepo->getNewslettersNames(true);

			foreach ($newslettersNames as $id => $name) {
				$suscription = $subsriptions->where('id_newsletter', $id)->first();
				$newsletters[$name] = FormLib::Select("families[$id]", 0, old("families[$id]", (bool)$suscription ? 'S' : 'N'), $booleanSelectOptions, '', '', false);
			}
		} else {
			$newslettersFamilies = explode(',', config('app.newsletterFamilies', ''));

			for ($i = 1; $i <= 20; $i++) {
				if (in_array($i, $newslettersFamilies)) {
					$newsletters[$i] = FormLib::select("newsletter$i", 0, old("newsletter$i", $apiCli->{"newsletter$i"} ?? 'N'), $booleanSelectOptions, '', '', false);
				}
			}
		}

		return $newsletters;
	}

	public function passwordEncrypt($passwd)
	{
		if (!empty(Config::get('app.multi_key_pass'))) {
			$newKey = md5(time());
			$password_encrypt =  md5($newKey . $passwd) . ":" . $newKey;
		} elseif (!empty(Config::get('app.password_MD5'))) {

			$password_encrypt =  md5(Config::get('app.password_MD5') . $passwd);
		}

		return $password_encrypt;
	}

	private function addOrigenes(Request $request, $cod_cli)
	{
		FxCliOrigen::where('cli_cliorigen', $cod_cli)->delete();

		$origenes = collect($request->provenance)->map(function ($provenace) use ($cod_cli) {
			return [
				'origen_cliorigen' => $provenace,
				'cli_cliorigen' => $cod_cli,
				'gemp_cliorigen' => config('app.gemp')
			];
		});

		FxCliOrigen::insert($origenes->toArray());
	}

	protected function clientsQueryBuilder()
	{
		return FxCli::query()
			->with('cli2:cod_cli2, envcat_cli2', 'tipoCli')
			->leftJoinCliWebCli()
			->leftJoinClid('W1')
			->where('cod_cli', '!=', 9999);
	}

	protected function clientsQueryFilters($clientes, Request $request)
	{
		//para relacion con cli2
		$clientes->when($request->envcat_cli2, function ($query, $envcat_cli2) {
			return $query->whereHas('cli2', function ($query) use ($envcat_cli2) {
				return $query->where('envcat_cli2', $envcat_cli2);
			});
		})

			->when($request->cod_cli, function ($query, $cod_cli) {
				return $query->where('cod_cli', 'like', "%" . $cod_cli . "%");
			})
			->when($request->cod2_cli, function ($query, $cod2_cli) {
				return $query->where('upper(cod2_cli)', 'like', "%" . mb_strtoupper($cod2_cli) . "%");
			})
			->when($request->tipo_cli, function ($query, $tipo_cli) {
				return $query->where('tipo_cli', $tipo_cli);
			})
			->when($request->nom_cli, function ($query, $nom_cli) {
				return $query->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($nom_cli) . "%");
			})
			->when($request->rsoc_cli, function ($query, $rsoc_cli) {
				return $query->where('upper(rsoc_cli)', 'like', "%" . mb_strtoupper($rsoc_cli) . "%");
			})
			->when($request->email_cli, function ($query, $email_cli) {
				return $query->where('upper(email_cli)', 'like', "%" . mb_strtoupper($email_cli) . "%");
			})
			->when($request->tel1_cli, function ($query, $tel1_cli) {
				return $query->where('upper(tel1_cli)', 'like', "%" . mb_strtoupper($tel1_cli) . "%");
			})
			->when($request->pais_cli, function ($query, $pais_cli) {
				return $query->where('upper(pais_cli)', 'like', "%" . mb_strtoupper($pais_cli) . "%");
			})
			->when($request->pro_cli, function ($query, $pro_cli) {
				return $query->where('upper(email_cli)', 'like', "%" . mb_strtoupper($pro_cli) . "%");
			})
			->when($request->idioma_cli, function ($query, $idioma_cli) {
				return $query->where('upper(idioma_cli)', 'like', "%" . mb_strtoupper($idioma_cli) . "%");
			})
			->when($request->fisjur_cli, function ($query, $fisjur_cli) {
				return $query->where('fisjur_cli', $fisjur_cli);
			})
			->when($request->fecalta_cliweb, function ($query, $fecalta_cliweb) {
				return $query->where('fecalta_cliweb', '>=', ToolsServiceProvider::getDateFormat($fecalta_cliweb, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->f_modi_cli, function ($query, $f_modi_cli) {
				return $query->where('f_modi_cli', '>=', ToolsServiceProvider::getDateFormat($f_modi_cli, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($request->baja_tmp_cli, function ($query, $baja_tmp_cli) {
				return $query->where('baja_tmp_cli', $baja_tmp_cli);
			})
			->when($request->email_clid, function ($query, $email_clid) {
				return $query->where('upper(email_clid)', 'like', "%" . mb_strtoupper($email_clid) . "%");
			})
			->when($request->cp_cli, function ($query, $cp_cli) {
				return $query->where('cp_cli', 'like', "%" . $cp_cli . "%");
			})
			->when($request->pob_cli, function ($query, $pob_cli) {
				return $query->where('upper(pob_cli)', 'like', "%" . mb_strtoupper($pob_cli) . "%");
			})
			->when($request->complete_direction, function ($query, $complete_direction) {
				return $query->whereRaw('upper(dir_cli || dir2_cli || sg_cli) like ?', "%" . mb_strtoupper($complete_direction) . "%");
			});

		return $clientes;
	}

	private function validateEmptySelectionFields($fields)
	{
		$empty = true;
		foreach ($fields as $key => $value) {
			if (preg_match('/_select$/', $key) && !empty($value)) {
				$empty = false;
				return $empty;
			}
		}
		return $empty;
	}

	public function updateSelections(Request $request)
	{
		if (self::validateEmptySelectionFields($request->toArray())) {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_data_form")], 500);
		}

		$ids = $request->input('ids', []);

		$clientes = [];
		foreach ($ids as $id) {
			$clientes[] =  $this->formattingDataForUpdate($request, $id);
		}

		$clientController = new ClientController();
		$json = $clientController->updateClient($clientes);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_update_data")], 500);
		}

		$this->modifyUserUpdate($ids);

		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_cli")], 200);
	}

	public function updateWithFilters(Request $request)
	{
		if (self::validateEmptySelectionFields($request->toArray())) {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_data_form")], 500);
		}

		$clientes = $this->clientsQueryBuilder()->select("FXCLI.COD2_CLI");
		$clientes = self::clientsQueryFilters($clientes, $request);
		$clientes = $clientes->get();

		$clientesForUpdate = [];
		foreach ($clientes as $cliente) {
			$clientesForUpdate[] = $this->formattingDataForUpdate($request, $cliente->cod2_cli);
		}

		$clientController = new ClientController();
		$json = $clientController->updateClient($clientesForUpdate);
		$result = json_decode($json);

		if ($result->status == 'ERROR') {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.no_update_data")], 500);
		}

		$this->modifyUserUpdate($clientes->pluck('cod2_cli')->toArray());

		return response()->json(['success' => true, 'message' => trans("admin-app.success.update_mass_cli")], 200);
	}

	private function formattingDataForUpdate(Request $request, $cod2_cli)
	{
		$update = [];
		$update['idorigincli'] = $cod2_cli;
		if ($request->input('tipo_select', '') != '') {
			$update['source'] = $request->input('tipo_select', '');
		}
		if ($request->input('bloq_temporal_select', '') != '') {
			$update['temporaryblock'] = $request->input('bloq_temporal_select', '');
		}
		if ($request->input('envio_catalogo_select', '') != '') {
			$update['enviocatalogo'] = $request->input('envio_catalogo_select', '');
		}
		return $update;
	}

	private function modifyUserUpdate(array $cod2_cli)
	{
		$cods_cliweb = FxCli::select('cod_cliweb')->whereIn('cod2_cli', $cod2_cli)->joinCliWebCli()->pluck('cod_cliweb');

		$this->userSession = Session::get('user');

		$update = [
			'USR_UPDATE_CLIWEB' => $this->userSession['usrw'],
			'DATE_UPDATE_CLIWEB' => date("Y-m-d h:i:s"),
		];

		FxCliWeb::whereIn('cod_cliweb', $cods_cliweb)->update($update);
	}

	public function destroySelections(Request $request)
	{
		$ids = $request->input('ids', []);

		$json = $this->destroySelectedClients($ids);

		if (json_decode($json)->status == 'ERROR') {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.erase_mass_cli")], 500);
		}

		return response()->json(['success' => true, 'message' => trans("admin-app.success.erase_mass_cli")], 200);
	}

	public function destroyWithFilters(Request $request)
	{
		$clientes = $this->clientsQueryBuilder()->select("FXCLI.COD2_CLI");
		$clientes = self::clientsQueryFilters($clientes, $request);
		$clientes = $clientes->get();

		$cod2_clients = $clientes->pluck('cod2_cli')->toArray();

		$json = $this->destroySelectedClients($cod2_clients);

		if (json_decode($json)->status == 'ERROR') {
			return response()->json(['success' => false, 'message' => trans("admin-app.error.erase_mass_cli")], 500);
		}

		return response()->json(['success' => true, 'message' => trans("admin-app.success.erase_mass_cli")], 200);
	}

	private function destroySelectedClients(array $ids)
	{
		$clientController = new ClientController();
		foreach ($ids as $id) {
			$json = $clientController->eraseClient(['idorigincli' => $id]);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		return $json;
	}

	public function data(Request $request)
	{
		// Recoger filtros y columnas
		$filters        = $request->input('filters', []);
		if (is_string($filters)) {
			$filters = json_decode($filters, true);
		}

		//$visibleColumns = json_decode($request->input('columns', array_keys($this->availableColumns)), true);
		$visibleColumns = array_keys($this->availableColumns);
		$availableColumns = $this->availableColumns;

		$query = self::clientsQueryBuilder();

		foreach ($filters as $f) {
			switch ($f['operator']) {
				case 'contains':
					$query->where($f['field'], 'like', "%{$f['value']}%");
					break;
				case 'equals':
					$query->where($f['field'], $f['value']);
					break;
				case 'starts':
					$query->where($f['field'], 'like', "{$f['value']}%");
					break;
			}
		}

		$clientes = $query
			//falla la query con select, revisar.
			//->select($visibleColumns)
			->paginate(10);


		// Renderizamos solo la tabla. necesitamos recuperar clases html de la original
		$html = view('admin::pages.usuario.cliente_v2.table', compact('clientes', 'availableColumns', 'visibleColumns'))->render();


		return response()->json([
			'table'      => $html,
			'pagination' => view('admin::pages.usuario.cliente_v2.pagination', compact('clientes'))->render(),
		]);
	}
}
