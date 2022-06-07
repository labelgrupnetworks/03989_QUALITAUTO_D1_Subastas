<?php

namespace App\Http\Controllers\admin;

use Request;
use Controller;
use View;
use Session;
use Redirect;
use Validator;
use Input;
use App\Models\User;
use Config;

class AdminUserController extends Controller
{
    #Muestra la pantalla de login si el usuario no está logueado y no es administrador.
    public function login()
    {
	if(!Session::has('user.admin')) {
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
        #Valida los datos de inicio de sessión.
        $rules = array(
            'email'    => 'required|email',
            'password' => 'required|min:5'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
                return Redirect::to('admin/login')->withErrors($validator->errors());
        } else {

            # Carga el modelo
            $user           = new User();
			$user->user     = Request::input('email');
			$user->password =  Request::input('password');

			if(!empty(Config::get('app.multi_key_pass'))){

				$user->email     =$user->user;
				$dataUser = $user->getUserByLogin();

				if(empty($dataUser)){
					return null;
				}
				$passBD= explode(":", $dataUser[0]->pwdwencrypt_cliweb);


				#debe existir la clave de encriptación y el password encriptado
				if(count($passBD) <2){
					return null;
				}

				$seed = $passBD[1];
				#encriptamos el password k mandan y añadimos la semilla para que concuerde con lo que hay guardado en base de datos.
				$user->password =  md5($seed.$user->password).":".$seed;
				$login          = $user->login_encrypt();

			}else{
				$user->password =  md5(Config::get('app.password_MD5').$user->password);

				$login          = $user->login_encrypt();
			}



            # Si existe el usuario.
            if(!empty($login)) {

                //jpalau@labelgrup.com
                # Tipacceso (S) = Admin | (N) Normal | (X) Sin acceso. | (A) AdminConfig
                if($login->tipacceso_cliweb == 'S' || $login->tipacceso_cliweb == 'A') {

                    # Seteamos la sesión
                    Session::put('user.name', $login->nom_cliweb);
                    Session::put('user.cod', $login->cod_cliweb);
                    Session::put('user.emp', $login->emp_cliweb);
                    Session::put('user.gemp', $login->gemp_cliweb);
                    if($login->tipacceso_cliweb == 'S') {
                        Session::push('user.admin', 1);
                    }elseif($login->tipacceso_cliweb == 'A'){
                        Session::push('user.adminconfig', 1);
                    }
                    Session::put('user.tk', $login->tk_cliweb);

                    return Redirect::to('admin');

                } else {

                    # Acceso denegado.
                    $msg_error = trans('admin-app.login.auth_denied');

                }


            } else {

                # El usuario no existe o usuario/contraseña son incorrectos.
                $msg_error = trans('admin-app.login.auth_failed');

            }

            # Redirige en caso de error.
            return Redirect::to('admin/login')->withErrors($msg_error);

        }
    }
}
