@extends('layouts.default')

@section('title')
    {{ $data['title'] }}
@stop

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

    <main class="autoformulario gray-page contenido-web">

        <div class="container">
            <h1 class="ff-highlight text-center fs-32-40">{{ $data['title'] }}</h1>

            @if (isset($data['content']))
                <div class="autoformulario-content">
                    {!! $data['content'] !!}
                </div>
            @endif
        </div>

        <div class="container mp-4 pt-md-5">
            <form name="autoformulario" id="autoformulario" method="post">

                <input name="subject" type="hidden" value="{{ $data['title'] }}">
                {!! $data['formulario']['_token']['formulario'] !!}
				<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">

                <div class="row g-3">
                    <div class="col-md">
                        <div class="form-floating">
                            {!! $data['formulario']['nomApell']['formulario'] !!}
                            <label for="floatingInput">
                                <b class="text-danger">*</b>
                                {{ trans("$theme-app.global.nomApell") }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            {!! $data['formulario']['email']['formulario'] !!}
                            <label for="floatingInput">
                                <b class="text-danger">*</b>
                                {{ trans("$theme-app.global.email") }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            {!! $data['formulario']['telefono']['formulario'] !!}
                            <label for="floatingInput">
                                <b class="text-danger">*</b>
                                {{ trans("$theme-app.global.telefono") }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            {!! $data['formulario']['mensaje']['formulario'] !!}
                            <label for="floatingInput">
                                {{ trans("$theme-app.global.mensaje") }}
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
						<div class="row mx-0 border">
							<div class="col-md-4 d-flex flex-column gap-2 py-2">
								<label for="file__1__files">
									<b class="text-danger">*</b>
									{{ trans("$theme-app.global.file_curriculum") }}
								</label>
								{!! $data['formulario']['file_curriculum']['formulario'] !!}
							</div>
						</div>

                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="condiciones" value="on"
                                id="bool__1__condiciones" autocomplete="off">
                            <label class="form-check-label" for="bool__1__condiciones">
                                {!! trans("$theme-app.emails.privacy_conditions") !!}
                            </label>
                        </div>
                    </div>

					<div class="col-12">
						<p class="captcha-terms">
							{!! trans("$theme-app.global.captcha-terms") !!}
						</p>
					</div>

					<div class="col-12 text-center">
						{!! $data['submit'] !!}
					</div>

                </div>

            </form>
        </div>

    </main>

@stop
