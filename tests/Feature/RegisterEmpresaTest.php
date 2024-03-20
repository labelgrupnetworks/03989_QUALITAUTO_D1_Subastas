<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterEmpresaTest extends TestCase
{
	private $requestUserMock = [
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

		$this->requestUserMock['_token'] = csrf_token();

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['email'] = 'MOCKINGMAIL';
		$this->requestUserMock['confirm_email'] = 'MOCKINGMAIL';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);

			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['confirm_email'] = 'subastas9999@labelgroup.com';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['password'] = '';
		$this->requestUserMock['confirm_password'] = '';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'R_9476182_B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'R-9476182-B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'R 9476182 B';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = '65024134Y';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'Z0352970V';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['nif'] = 'KKA190176U';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_nif'
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['pais'] = 'JP';
		$this->requestUserMock['nif'] = '321654984321WWWoO';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
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

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['pais'] = 'RU';
		$this->requestUserMock['contact'] = 'Суперкаллиграфический шрифт espiali doso';
		$this->requestUserMock['rsoc_cli'] = 'доказательство того, что это круто';
		$this->requestUserMock['nif'] = 'нет115658___________________------------------------                      ';

		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 0,
			]);

			RegisterParticularTest::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with exagerated and irreal lower dates - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_irreal_lower_dates_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['date'] = '10-10-1000';


		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
		});

		DB::rollBack();
	}

	#endregion

	/**
	 * This is a test to check all register cirquit with exagerated and irreal higher dates - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_irreal_higher_dates_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['date'] = '10-10-3000';


		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with void user data - empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_void_user_data_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		$this->requestUserMock['_token'] = csrf_token();
		$this->requestUserMock['contact'] = '';
		$this->requestUserMock['rsoc_cli'] = '';


		DB::transaction(function () {
			$response = $this->post(route('send_register'), $this->requestUserMock);


			$response->assertJson([
				'err' => 1,
				'msg' => 'error_register'
			]);

			RegisterParticularTest::deleteCli();
		});

		DB::rollBack();
	}

	#endregion


}
