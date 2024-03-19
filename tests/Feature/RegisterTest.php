<?php

namespace Tests\Feature;

use App\Http\Controllers\apilabel\ClientController;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterTest extends TestCase
{

	private $requestUserMockPriempF = [
		'pri_emp' => 'F',
		'sexo' => 'M',
		'usuario' => 'SUPER_TEST',
		'contact' => null,
		'last_name' => 'FROM_LARAVEL_TESTS',
		'rsoc_cli' => null,
		'date' => '2001-01-01',
		'language' => 'ES',
		'nif' => '69426337D',
		'telefono' => '123456789',
		'pais' => 'ES',
		'cpostal' => '08840',
		'provincia' => 'Barcelona',
		'poblacion' => 'Viladecans',
		'codigoVia' => 'AV',
		'direccion' => 'del progrés 16',
		'divisa' => 'EUR',
		'obscli' => null,
		'shipping_address' => 'on',
		'clid' => '0',
		'clid_pais' => 'ES',
		'clid_cpostal' => '08840',
		'clid_provincia' => 'Barcelona',
		'clid_poblacion' => 'Viladecans',
		'clid_codigoVia' => 'AV',
		'clid_direccion' => 'del progrés 16',
		'email' => 'subastas9999@labelgrup.com',
		'confirm_email' => 'subastas9999@labelgrup.com',
		'password' => 'supertest1234',
		'confirm_password' => 'supertest1234',
		'condiciones' => 'on',
	];

	private $requestUserMockPriempJ = [
		"pri_emp" => "J",
		"sexo" => "H",
		"usuario" => null,
		"contact" => "Supertest",
		"last_name" => null,
		"rsoc_cli" => "prueba",
		"date" => "2000-01-01",
		"language" => "ES",
		"nif" => "R9476182B",
		"telefono" => "213654987",
		"pais" => "ES",
		"cpostal" => "08840",
		"provincia" => "Barcelona",
		"poblacion" => "Viladecans",
		"codigoVia" => "AV",
		"direccion" => "del progres 16",
		"divisa" => "EUR",
		"obscli" => null,
		"shipping_address" => "on",
		"clid" => "0",
		"clid_pais" => "ES",
		"clid_cpostal" => "08840",
		"clid_provincia" => "Barcelona",
		"clid_poblacion" => "Viladecans",
		"clid_codigoVia" => "AV",
		"clid_direccion" => "del progres 16",
		"email" => "subastas9999@labelgrup.com",
		"confirm_email" => "subastas9999@labelgrup.com",
		"password" => "supertest1234",
		"confirm_password" => "supertest1234",
		"condiciones" => "on"
	];
	
	#region Helper methods

	private function deleteCli()
	{
		$user = session()->get('user');

		if (!empty($user)) {
			$cliToDelete = FxCli::select("FXCLI.COD2_CLI")
				->with('tipoCli')->with('cli2:cod_cli2, envcat_cli2')
				->leftJoinCliWebCli()
				->leftJoinClid('W1')
				->where('cod_cli', '!=', 9999)
				->where('cod_cli', '=', $user['cod'])
				->first();

			$clientController = new ClientController();
			$clientController->eraseClient(['idorigincli' => $cliToDelete->cod2_cli]);
		}
	}

	#endregion

	#region _____________________________Particular register test_____________________________

	/**
	 * This is a test to check all register cirquit with valid data - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_all_data_is_valid_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not valid email - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_not_valid_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['email'] = 'MOCKINGMAIL';
		$this->requestUserMockPriempF['confirm_email'] = 'MOCKINGMAIL';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not same email - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_not_same_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['confirm_email'] = 'subastas9999@labelgroup.com';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with void password - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_void_password_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['password'] = '';
		$this->requestUserMockPriempF['confirm_password'] = '';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with snake case nif - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_snake_case_nif_is_succesful_and_not_save_underscores()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = '69426337_D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with kebab case nif - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_kebab_case_nif_is_succesful_and_not_save_dashes()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = '69426337-D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with spaced nif - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_spaced_nif_is_succesful_and_not_save_spaces()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = '69426337 D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with correct nie - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_correct_nie_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'X1063344P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with snake case nie - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_snake_case_nie_is_succesful_and_not_save_underscores()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'X_1063344_P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with kebab case nie - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_kebab_case_nie_is_succesful_and_not_save_dashes()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'X-1063344-P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with spaced nie - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_spaced_nie_is_succesful_and_not_save_spaces()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'X 1063344 P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with correct spain passport - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_correct_spain_passport_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'MOI695409Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with snake case spain passport - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_snake_case_passport_is_succesful_and_not_save_underscores()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'MOI_695409_Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with kebab case spain passport - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_kebab_case_passport_is_succesful_and_not_save_dashes()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'MOI-695409-Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with spaced spain passport - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_spaced_passport_is_succesful_and_not_save_spaces()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'MOI 695409 Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with cif - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_cif_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['nif'] = 'A97352611';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit in other country to check document ID - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_in_other_country_nif_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['pais'] = 'FR';
		$this->requestUserMockPriempF['nif'] = 'AAAAAAA111111AAAAAAA';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit in other languages (not latin languages) - particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_in_other_languages_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempF['_token'] = csrf_token();
		$this->requestUserMockPriempF['pais'] = 'CN';
		$this->requestUserMockPriempF['usuario'] = '超クールな名前';
		$this->requestUserMockPriempF['last_name'] = 'テスト姓';
		$this->requestUserMockPriempF['nif'] = 'ない';


		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempF);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	#endregion

	#region _____________________________Empresa register test________________________________

	/**
	 * This is a test to check all register cirquit with valid - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_all_data_is_valid_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);

			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not valid email - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_not_valid_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['email'] = 'MOCKINGMAIL';
		$this->requestUserMockPriempJ['confirm_email'] = 'MOCKINGMAIL';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);

			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not same email - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_not_same_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['confirm_email'] = 'subastas9999@labelgroup.com';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with void password - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_void_password_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['password'] = '';
		$this->requestUserMockPriempJ['confirm_password'] = '';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with snake case cif - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_snake_case_cif_is_succesful_and_not_save_underscores()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = 'R_9476182_B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with kebab case cif - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_kebab_case_cif_is_succesful_and_not_save_dashes()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = 'R-9476182-B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with spaced cif - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_spaced_cif_is_succesful_and_not_save_spaces()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = 'R 9476182 B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with nif - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_nif_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = '65024134Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with nie - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_nie_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = 'Z0352970V';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with passport - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_passport_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['nif'] = 'KKA190176U';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit in other country to check document ID - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_in_other_country_cif_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['pais'] = 'JP';
		$this->requestUserMockPriempJ['nif'] = '321654984321WWWoO';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit in other languages (not latin languages) - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_in_other_languages_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMockPriempJ['_token'] = csrf_token();
		$this->requestUserMockPriempJ['pais'] = 'RU';
		$this->requestUserMockPriempJ['contact'] = 'Суперкаллиграфический шрифт espiali doso';
		$this->requestUserMockPriempJ['rsoc_cli'] = 'доказательство того, что это круто';
		$this->requestUserMockPriempJ['nif'] = 'нет115658___________________------------------------                      ';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMockPriempJ);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	#endregion
}
