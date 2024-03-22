<?php

namespace Tests\Feature;

use App\Http\Controllers\apilabel\ClientController;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterParticularTest extends TestCase
{
	private $requestUserMock = [
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

	#region Helpers methods

	public static function deleteCli()
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

		$this->requestUserMock['_token'] = csrf_token();

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['email'] = 'MOCKINGMAIL';
		$this->requestUserMock['confirm_email'] = 'MOCKINGMAIL';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['confirm_email'] = 'subastas9999@labelgroup.com';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['password'] = '';
		$this->requestUserMock['confirm_password'] = '';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = '69426337_D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = '69426337-D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = '69426337 D';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'X1063344P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'X_1063344_P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'X-1063344-P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'X 1063344 P';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'MOI695409Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'MOI_695409_Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'MOI-695409-Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'MOI 695409 Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'A97352611';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['pais'] = 'FR';
		$this->requestUserMock['nif'] = 'AAAAAAA111111AAAAAAA';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['pais'] = 'CN';
		$this->requestUserMock['usuario'] = '超クールな名前';
		$this->requestUserMock['last_name'] = 'テスト姓';
		$this->requestUserMock['nif'] = 'ない';


		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	#endregion


}
