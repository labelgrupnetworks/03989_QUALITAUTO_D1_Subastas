<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;


class AdminUserController extends Controller
{
	#Muestra la pantalla de login si el usuario no está logueado y no es administrador.
	public function login()
	{
		if (!Session::has('user.admin')) {
			return View::make('admin::pages.login');
		} else {
			return Redirect::to('admin');
		}
	}

	#Desloguea al usuario.
	public function logout()
	{
		# Elimina la sesión y redirige a login.
		Session::flush();
		return Redirect::to('admin/login');
	}

	#Loguea al usuario. Solo accesible por post.
	public function login_post(Request $request)
	{
		return (new UserController)->login_post($request);
	}
}
