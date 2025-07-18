<?php

namespace App\Services\Content;

use Illuminate\Support\Facades\Session;

/**
 * Class ManagementService
 * Servicio utilizado para gestionar los ajustes y accesos directos del administrador.
 */
class ManagementService
{
	protected static array $data = [];

	public static function add(string $key, mixed $value): void
	{
		if(!Session::get('user.is_super_admin', false)) {
			return;
		}

		if (!isset(self::$data[$key])) {
			self::$data[$key] = [];
		}
		self::$data[$key][] = $value;
	}

	public static function render(): ?\Illuminate\View\View
	{
		// Mientras este en construcción.
		// Una vez finalizado la conción debe ser a cualquier administrador.
		if(!Session::get('user.is_super_admin', false)) {
			return null;
		}

		if (empty(self::$data)) {
			return null;
		}

		//if not exist view, return null
		if (!view()->exists('front::includes.admin_management')) {
			return null;
		}

		return view('front::includes.admin_management', ['data' => self::$data]);
	}
}
