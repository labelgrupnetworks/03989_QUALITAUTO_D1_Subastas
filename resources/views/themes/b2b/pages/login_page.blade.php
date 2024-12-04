@extends('layouts.login')

@php
	if(Session::has('user')) {
		//redirect to auction page
		header('Location: ' . route('subastas.online'));
		exit;
	}
@endphp

@section('content')

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
			text-align: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

		.form-signin img {
			width: 100%;
			height: auto;
		}

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>


    <main class="form-signin">
        <form id="accerder-user-form">
			@csrf
            <img class="mb-4" src="/themes/{{ $theme }}/assets/img/logo.png" alt="Subalia">
            <h1 class="h3 mb-3 fw-normal">Por favor, inice sesión</h1>

            <div class="form-floating">
                <input class="form-control" id="floatingInput" type="email" name="email" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
            </div>
            <div class="form-floating">
                <input class="form-control" id="floatingPassword" type="password" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>

			<p><span class="message-error-log text-danger seo_h5"></span></p>

            <button id="accerder-user" class="w-100 btn btn-lg btn-lb-primary" type="submit">Iniciar Sesión</button>

			<small class="align-self-end">
				<a class="c_bordered" id="p_recovery" data-ref="{{ \Routing::slug('password_recovery') }}"
					data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
					data-bs-toggle="modal" data-bs-target="#modalAjax" href="javascript:;"
					onclick="cerrarLogin();">
					{{ trans($theme . '-app.login_register.forgotten_pass_question') }}
				</a>
			</small>

        </form>
    </main>
@stop
