<?php

namespace Tests\Unit\Services;

use App\Services\web\user\AddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

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

	public function test_create_address(): void
	{
		Config::set('app.strtodefault_register', 0);

		$user = new \stdClass();
		$user->cod_cli = 1;
		$user->nom_cli = 'Test User';

		$address = [
			'codd_clid' => 1,
			'clid_cpostal' => '12345',
			'clid_direccion' => 'Test Address',
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
			'obs_clid' => 'Test Obs',
			'mater_clid' => 'Test Mater'
		];

		$addressService = new AddressService();
		$addressService->addAddres($user, $address);

		$this->assertDatabaseHas('fxclid', [
			'cli_clid' => 1,
			'codd_clid' => 1,
			'nomd_clid' => 'Test User',
			'tipo_clid' => 'E',
			'cp_clid' => '12345',
			'dir_clid' => 'Test Address',
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

}
