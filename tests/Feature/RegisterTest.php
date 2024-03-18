<?php

namespace Tests\Feature;

use App\Http\Controllers\apilabel\ClientController;
use App\Models\V5\FxCli;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterTest extends TestCase
{
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

	#region Particular register test

	/**
	 * This is a test to check all register cirquit with valid particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_all_data_is_valid_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				'_token' => csrf_token(),
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
				'email' => 'subastas1000@labelgrup.com',
				'confirm_email' => 'subastas1000@labelgrup.com',
				'password' => 'Magno22',
				'confirm_password' => 'Magno22',
				'condiciones' => 'on',
			]);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not valid email particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_not_valid_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				'_token' => csrf_token(),
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
				'email' => 'MOCKINGMAIL',
				'confirm_email' => 'MOCKINGMAIL',
				'password' => 'Magno22',
				'confirm_password' => 'Magno22',
				'condiciones' => 'on',
			]);


			$response->assertJson([
				'err' => 1,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not same email particular user data.
	 *
	 * @return void
	 */
	public function test_register_particular_with_not_same_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				'_token' => csrf_token(),
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
				'email' => 'subastas1000@labelgrup.com',
				'confirm_email' => 'subastas1000@labelgroup.com',
				'password' => 'Magno22',
				'confirm_password' => 'Magno22',
				'condiciones' => 'on',
			]);


			$response->assertJson([
				'err' => 1,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	#endregion

	#region Empresa register test

	/**
	 * This is a test to check all register cirquit with valid empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_all_data_is_valid_is_succesful()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				"_token" => csrf_token(),
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
				"email" => "subastas1000@labelgrup.com",
				"confirm_email" => "subastas1000@labelgrup.com",
				"password" => "Magno22",
				"confirm_password" => "Magno22",
				"condiciones" => "on"
			]);


			$response->assertJson([
				'err' => 0,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not valid email empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_not_valid_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				"_token" => csrf_token(),
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
				"email" => "MOCKINGMAIL",
				"confirm_email" => "MOCKINGMAIL",
				"password" => "Magno22",
				"confirm_password" => "Magno22",
				"condiciones" => "on"
			]);


			$response->assertJson([
				'err' => 1,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	/**
	 * This is a test to check all register cirquit with not same email empresa user data.
	 *
	 * @return void
	 */
	public function test_register_empresa_with_not_same_email_is_failed()
	{
		PagesTest::disbleRecaptcha();
		PagesTest::setHTTP_HOST(route('send_register'));

		DB::transaction(function () {
			$response = $this->post(route('send_register'), [
				"_token" => csrf_token(),
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
				"email" => "subastas1000@labelgrup.com",
				"confirm_email" => "subastas1000@labelgroup.com",
				"password" => "Magno22",
				"confirm_password" => "Magno22",
				"condiciones" => "on"
			]);


			$response->assertJson([
				'err' => 1,
			]);

			self::deleteCli();
		});

		DB::rollBack();
	}

	#endregion
}
