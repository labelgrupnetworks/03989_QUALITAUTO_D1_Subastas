<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Feature\PagesTest;

class AuctionsTest extends TestCase
{

	/**
	 * A test for the actual auction page.
	 * @return void
	 */
	public function test_subasta_actual_is_succesful()
	{
		$url = route('subasta.actual');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the actual online auctions page.
	 * @return void
	 */
	public function test_subasta_actual_online_is_succesful()
	{
		$url = route('subasta.actual-online');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		if ($response->baseResponse->getStatusCode() == 200) {
			$response->assertSuccessful();
		} else {
			$response->assertRedirect($url);
		}
	}

	/**
	 * A test for the presencial auctions page.
	 * @return void
	 */
	public function test_subastas_presenciales_is_succesful()
	{
		$url = route('subastas.presenciales');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the historic auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_is_succesful()
	{
		$url = route('subastas.historicas');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the historic presencial auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_presenciales_is_succesful()
	{
		$url = route('subastas.historicas_presenciales');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the historic online auctions page.
	 * @return void
	 */
	public function test_subastas_historicas_online_is_succesful()
	{
		$url = route('subastas.historicas_online');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the online auctions page.
	 * @return void
	 */
	public function test_subastas_online_is_succesful()
	{
		$url = route('subastas.online');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the permanent auctions page.
	 * @return void
	 */
	public function test_subastas_permanentes_is_succesful()
	{
		$url = route('subastas.permanentes');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the direct sale auctions page.
	 * @return void
	 */
	public function test_subastas_venta_directa_is_succesful()
	{
		$url = route('subastas.venta_directa');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for all auctions page.
	 * @return void
	 */
	public function test_subastas_todas_is_succesful()
	{
		$url = route('subastas.all');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the active auctions page.
	 * @return void
	 */
	public function test_subastas_activas_is_succesful()
	{
		$url = route('subastas.activas');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the special auctions page.
	 * @return void
	 */
	public function test_subastas_especiales_is_succesful()
	{
		$url = route('subastas.especiales');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for "make a bid" auctions page.
	 * @return void
	 */
	public function test_subastas_haz_oferta_is_succesful()
	{
		$url = route('subastas.haz_oferta');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the reverse auctions page.
	 * @return void
	 */
	public function test_subastas_inversas_is_succesful()
	{
		$url = route('subastas.subasta_inversa');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}
}
