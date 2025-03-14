<?php

namespace Tests\Unit\Services;

use App\DataTransferObjects\User\AddressData;
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

		$userId = 1;
		$address = AddressData::fromArray([
			'clid_direccion' => 'TEST Address',
			'clid_poblacion' => 'Test City',
			'clid_cpostal' => '12345',
			'clid_pais' => 'TC',
			'des_pais' => 'Test Country',
			'clid_codigoVia' => 'TV',
			'clid_provincia' => 'Test Province',
			'usuario' => 'Test User',
			'telefono' => '1234567890',
			'rsoc' => 'Test Rsoc',
			'codd_clid' => 'W1',
			'cod2_clid' => '123',
			'preftel_clid' => '1234',
			'mater_clid' => 'N',
		]);

		(new UserAddressService)->addAddress($address, $userId);

		$this->assertDatabaseHas('fxclid', [
			'cli_clid' => 1,
			'codd_clid' => 'W1',
			'nomd_clid' => 'Test User',
			'tipo_clid' => 'E',
			'cp_clid' => '12345',
			'dir_clid' => 'TEST Address',
			'pob_clid' => 'Test City',
			'pais_clid' => 'Test Country',
			'codpais_clid' => 'TC',
			'sg_clid' => 'TV',
			'pro_clid' => 'Test Province',
			'tel1_clid' => '1234567890'
		]);

		$this->assertDatabaseCount('fxclid', 1);

	}

	public function test_create_address_to_not_strtodefault(): void
	{
		Config::set('app.strtodefault_register', 0);

		$userId = 1;
		$address = AddressData::fromArray([
			'clid_direccion' => 'TEST Address',
			'clid_poblacion' => 'Test City',
			'clid_cpostal' => '12345',
			'clid_pais' => 'TC',
			'des_pais' => 'Test Country',
			'clid_codigoVia' => 'TV',
			'clid_provincia' => 'Test Province',
			'usuario' => 'Test User',
			'telefono' => '1234567890',
			'rsoc' => 'Test Rsoc',
			'codd_clid' => 'W1',
			'cod2_clid' => '123',
			'preftel_clid' => '1234',
			'mater_clid' => 'N',
		]);

		(new UserAddressService)->addAddress($address, $userId);

		$this->assertDatabaseHas('fxclid', [
			'cli_clid' => 1,
			'codd_clid' => 'W1',
			'nomd_clid' => 'TEST USER',
			'tipo_clid' => 'E',
			'cp_clid' => '12345',
			'dir_clid' => 'TEST ADDRESS',
			'pob_clid' => 'TEST CITY',
			'pais_clid' => 'TEST COUNTRY',
			'codpais_clid' => 'TC',
			'sg_clid' => 'TV',
			'pro_clid' => 'TEST PROVINCE',
			'tel1_clid' => '1234567890'
		]);

		$this->assertDatabaseCount('fxclid', 1);
	}
}
