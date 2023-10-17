@extends('layouts.default')

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <main class="private-page">
        <div class="container">
            <h1 class="ff-highlight mb-4">
				Acceso a canal privado
            </h1>

            <form method="POST" action="{{ route('private_chanel.login') }}">
                @csrf

                <div class="form-floating">
                    <input type="text" class="form-control" id="private-user" name="user" autocomplete="off" required
                        placeholder="email@example.com">
                    <label for="private-user">{{ trans("$theme-app.login_register.ph_user") }}</label>
                </div>

                <div class="form-floating input-group">
                    <input type="password" name="password" class="form-control" id="private-password"
                        placeholder="contraseña" autocomplete="off" required>
                    <label for="private-password">{{ trans("$theme-app.login_register.password") }}</label>
                    <span class="input-group-text view_password">
                        <img class="eye-password"
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                    </span>
                </div>

                <button type="submit" class="btn btn-lb-primary btn-fluid">
                    <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                </button>

            </form>
        </div>

    </main>

@stop
