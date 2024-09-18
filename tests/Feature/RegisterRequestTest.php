<?php

namespace Tests\Feature;

use App\Http\Requests\User\RegisterRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Class ResisterRequestTest
 * @package Tests\Feature
 * @covers \App\Http\Requests\User\RegisterRequest
 * ejecutar con: php artisan test --filter RegisterRequestTest
 *
 */
class RegisterRequestTest extends TestCase
{

	protected function setUp(): void
	{
		parent::setUp();

		Config::set('app.registerCheckerF', 'pais,usuario');
	}

	/**
     * Test valid data.
     */
    public function test_valid_data_passes()
    {
        $data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'files_email' => []
        ];

        $request = new RegisterRequest();
        $request->merge($data);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertTrue($validator->passes());
    }

	/**
	 * Test strict password passes.
	 */
	public function test_strict_password_passes()
	{
		Config::set('app.strict_password_validation', true);
		$data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'Secret123$',
        ];

		$request = new RegisterRequest();
        $request->merge($data);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertTrue($validator->passes());
	}

	/**
	 * Test strict password fails.
	 */
	public function test_strict_password_fails()
	{
		Config::set('app.strict_password_validation', true);
		$data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

		$request = new RegisterRequest();
        $request->merge($data);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertFalse($validator->passes());
		$this->assertArrayHasKey('password', $validator->errors()->toArray());
	}

	/**
     * Test invalid data.
     */
    public function test_invalid_data_fails()
    {
        $data = [
            'pri_emp' => 'F',
            'email' => 'invalid-email',
            'password' => '123',
            'dni1' => '',
            'dni2' => '',
            'files_email' => []
        ];

        $request = new RegisterRequest();
        $request->merge($data);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('pais', $validator->errors()->toArray());
        $this->assertArrayHasKey('usuario', $validator->errors()->toArray());
		$this->assertArrayHasKey('email', $validator->errors()->toArray());
		$this->assertArrayHasKey('password', $validator->errors()->toArray());
    }


    /**
     * Test required keys from config.
     */
    public function test_required_keys_from_config()
    {
        $data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'dni1' => '',
            'dni2' => '',
            'files_email' => []
        ];

        Config::set('app.registerCheckerF', 'pais,usuario,dni1');

        $request = new RegisterRequest();
        $request->merge($data);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('dni1', $validator->errors()->toArray());
    }

    /**
     * Test file validation.
     */
    public function test_file_validation()
    {
        $file = UploadedFile::fake()->create('document.pdf', 5000);

        $data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

        $request = new RegisterRequest();
        $request->merge($data);
		$request->files->set('dni1', UploadedFile::fake()->image('dni1.jpg')->size(500));
		$request->files->set('dni2', UploadedFile::fake()->image('dni2.jpg')->size(500));
        $request->files->set('files_email', [$file]);

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertTrue($validator->passes());
    }

	/**
     * Test invalid file size.
     */
    public function test_invalid_file_size()
    {
		$file = UploadedFile::fake()->create('document.pdf', 20001);

		$data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

		$request = new RegisterRequest();
		$request->merge($data);
		$request->files->set('dni1', UploadedFile::fake()->image('dni1.jpg')->size(1000001));
		$request->files->set('dni2', UploadedFile::fake()->image('dni2.jpg')->size(1000001));
		$request->files->set('files_email', [$file]);

		$validator = Validator::make($request->all(), $request->rules());

		$this->assertFalse($validator->passes());
		$this->assertArrayHasKey('files_email.0', $validator->errors()->toArray());
		$this->assertArrayHasKey('dni1', $validator->errors()->toArray());
		$this->assertArrayHasKey('dni2', $validator->errors()->toArray());
	}

	/**
     * Test invalid file type.
     */
    public function test_invalid_file_type()
    {
		$data = [
            'pri_emp' => 'F',
			'pais' => 'ES',
			'usuario' => 'user',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

		$file = UploadedFile::fake()->create('document.txt', 500);

		$request = new RegisterRequest();
		$request->merge($data);
		$request->files->set('dni1', UploadedFile::fake()->create('dni1.txt', 500)); // Invalid file type
		$request->files->set('dni2', UploadedFile::fake()->image('dni2.jpg')->size(500));
		$request->files->set('files_email', [$file]);

		$validator = Validator::make($request->all(), $request->rules());

		$this->assertFalse($validator->passes());
		$this->assertArrayHasKey('dni1', $validator->errors()->toArray());
		$this->assertArrayHasKey('files_email.0', $validator->errors()->toArray());
    }


}
