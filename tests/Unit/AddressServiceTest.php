<?php

namespace Tests\Unit\Services;

use App\Services\User\UserAddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;


/**
 * Class AddressServiceTest
 * test file: php artisan test --filter AddressServiceTest
 * test method: php artisan test --filter test_create_address_to_strtodefault
 */
class AddressServiceTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		if (env('DB_CONNECTION') != 'sqlite') {
			$this->markTestSkipped('The test is only for sqlite');
		}

		parent::setUp();
	}

	public function test_create_address_to_strtodefault(): void
	{
		Config::set('app.strtodefault_register', 1);

		$user = new \stdClass();
		$user->cod_cli = 1;
		$user->nom_cli = 'Test User';

		$address = [
			'codd_clid' => 'W1',
			'clid_cpostal' => '12345',
			'clid_direccion' => 'TEST Address',
			'clid_direccion_2' => 'Test Address 2',
			'clid_poblacion' => 'Test City',
			'clid_pais' => 'Test Country',
			'clid_cod_pais' => 'TC',
			'clid_via' => 'Test Via',
			'clid_provincia' => 'Test Province',
			'clid_telf' => '1234567890',
			'cod2_clid' => '123',
			'email_clid' => 'test@add.es',
			'preftel_clid' => '1234567890',
			'rsoc2_clid' => 'Test Rsoc',
			'obs_clid' => 'Test Obs',
			'mater_clid' => 'Test Mater'
		];

		(new UserAddressService)->addAddress($address, $user->cod_cli, $user->nom_cli);

		$this->assertDatabaseHas('fxclid', [
			'cli_clid' => 1,
			'codd_clid' => 'W1',
			'nomd_clid' => 'Test User',
			'tipo_clid' => 'E',
			'cp_clid' => '12345',
			'dir_clid' => 'TEST Address',
			'dir2_clid' => 'Test Address 2',
			'pob_clid' => 'Test City',
			'pais_clid' => 'Test Country',
			'codpais_clid' => 'TC',
			'sg_clid' => 'Test Via',
			'pro_clid' => 'Test Province',
			'tel1_clid' => '1234567890',
			'rsoc_clid' => 'Test User'
		]);

		$this->assertDatabaseCount('fxclid', 1);

	}

	public function test_create_address_to_not_strtodefault(): void
	{
		Config::set('app.strtodefault_register', 0);

		$user = new \stdClass();
		$user->cod_cli = 1;
		$user->nom_cli = 'Test User';

		$address = [
			'codd_clid' => 'W1',
			'clid_cpostal' => '12345',
			'clid_direccion' => 'TEST Address',
			'clid_direccion_2' => 'Test Address 2',
			'clid_poblacion' => 'Test City',
			'clid_pais' => 'Test Country',
			'clid_cod_pais' => 'TC',
			'clid_via' => 'Test Via',
			'clid_provincia' => 'Test Province',
			'clid_telf' => '1234567890',
			'cod2_clid' => '123',
			'email_clid' => 'test@add.es',
			'preftel_clid' => '1234567890',
			'rsoc2_clid' => 'Test Rsoc',
			'obs_clid' => 'Test Obs',
			'mater_clid' => 'Test Mater'
		];

		(new UserAddressService)->addAddress($address, $user->cod_cli, $user->nom_cli);

		$this->assertDatabaseHas('fxclid', [
			'cli_clid' => 1,
			'codd_clid' => 'W1',
			'nomd_clid' => 'TEST USER',
			'tipo_clid' => 'E',
			'cp_clid' => '12345',
			'dir_clid' => 'TEST ADDRESS',
			'dir2_clid' => 'TEST ADDRESS 2',
			'pob_clid' => 'TEST CITY',
			'pais_clid' => 'TEST COUNTRY',
			'codpais_clid' => 'TC',
			'sg_clid' => 'TEST VIA',
			'pro_clid' => 'TEST PROVINCE',
			'tel1_clid' => '1234567890',
			'rsoc_clid' => 'TEST USER'
		]);

		$this->assertDatabaseCount('fxclid', 1);
	}
}
