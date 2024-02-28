<?php

namespace App\Http\Controllers\ClientTest;
use Tests\Feature\PagesTest;

class DemoTests extends PagesTest
{

	private $pagesTestInstance;

	/**
	 * Class constructor.
	 * @param PagesTest $pagesTest
	 * @return void
	 */
	public function __construct(PagesTest $pagesTest)
	{
		$this->pagesTestInstance = $pagesTest;
	}

	public function testHomePageIsSuccessful()
	{
		$response = $this->pagesTestInstance->get(route('home'));
		$response->assertSuccessful();
	}

}


