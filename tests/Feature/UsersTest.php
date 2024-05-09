<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Feature\PagesTest;
use Illuminate\Support\Facades\Config;

class UsersTest extends TestCase
{
    /**
	 * A test for the user registered page.
	 * @return void
	 */
	public function test_user_registered_is_successful()
	{
		$url = route('user.registered');

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}

	/**
	 * A test for the V5 register page.
	 * @return void
	 */
	public function test_register_is_succesful()
	{
		$url = route('register', ['lang' => Config::get('app.locale')]);

		PagesTest::setHTTP_HOST($url);

		$response = $this->get($url);

		$response->assertSuccessful();
	}
}
