<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{

	/**
	 * A test searching a succesful login circuit.
	 * This test redirects back the page last visited.
	 * @return void
	 */
	public function test_make_login_is_succesful()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$this->post($url, [
			"_token" => csrf_token(),
			"email" => "subastas@labelgrup.com",
			"password" => "Magno22"
		]);

		$user = [];
		$user = session()->get('user');
		$userLoged = $user ? count($user) > 0 : false;

		$this->assertTrue($userLoged); # Probbably the user 'subastas@labelgrup.com' not exists.
	}

	/**
	 * A test searching a failed login circuit introducing a inexistent email.
	 * This test redirects to the home page.
	 * @return void
	 */
	public function test_make_login_with_inexistent_email_is_failed()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"email" => "mockingdata@mockingdata.com",
			"password" => "MOCKINGDATA"
		]);

		$response->assertRedirect('/');
	}

	/**
	 * A test searching a failed login circuit introducing a inexistent email.
	 * This test redirects to the home page.
	 * @return void
	 */
	public function test_make_login_with_incorrect_email_is_failed()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"email" => "mockingdata",
			"password" => "MOCKINGDATA"
		]);

		$response->assertRedirect(\Routing::slug('login'));
	}

	/**
	 * A test searching a failed login circuit introducing a incorrect password.
	 * This test redirects to the home page.
	 * @return void
	 */
	public function test_make_login_with_incorrect_password_is_failed()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"email" => "subastas@labelgrup.com",
			"password" => "MOCKINGDATA"
		]);

		$response->assertRedirect('/');
	}

	/**
	 * A test searching a failed login circuit introducing a void email.
	 * This test redirects to the login page.
	 * @return void
	 */
	public function test_make_login_with_void_email_is_failed()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"email" => "",
			"password" => "MOCKINGDATA"
		]);

		$response->assertRedirect(\Routing::slug('login'));
	}

	/**
	 * A test searching a failed login circuit introducing a void password.
	 * This test redirects to the login page.
	 * @return void
	 */
	public function test_make_login_with_void_password_is_failed()
	{
		$url = route('post_login');

		PagesTest::setHTTP_HOST($url);

		$response = $this->post($url, [
			"_token" => csrf_token(),
			"email" => "jhondoe@labelgrup.com",
			"password" => ""
		]);

		$response->assertRedirect(\Routing::slug('login'));
	}
}
