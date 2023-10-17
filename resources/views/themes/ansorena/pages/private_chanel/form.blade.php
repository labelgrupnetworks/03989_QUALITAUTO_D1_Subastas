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

            <form method="POST" action="{{ route('private_chanel.form') }}">
                @csrf

                <div class="mb-3">
					<p class="mb-3">Denuncia de:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="impeachment" id="private-radio1" value="ce" checked>
                        <label class="form-check-label" for="private-radio1">
                            Código ético
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="impeachment" id="private-radio2" value="bc">
                        <label class="form-check-label" for="private-radio2">
                            Blanqueo de capitales
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="private-text" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="private-text" rows="4" name="message" required></textarea>
                </div>

                <button type="submit" class="btn btn-lb-primary btn-fluid">
                    <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                </button>

            </form>
        </div>

    </main>

@stop
