@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

    <main class="valoracion-page">
        <div class="container">

            <h1 class="titlePage">{{ trans("$theme-app.valoracion_gratuita.solicitud_valoracion") }}</h1>

            <p class="optimal-text-lenght">{!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}</p>

            <form class="mt-3" id="form-valoracion-adv" action="">
                @csrf
                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">

                <p class="text-danger h4 hidden msg_valoracion">
                    {{ trans($theme . '-app.valoracion_gratuita.error') }}</p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" name="name" type="text"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" required>
                            <label class="floatingInput"
                                for="name">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" required>
                            <label class="floatingInput"
                                for="email">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="telf" name="telf" type="phone"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" required>
                            <label class="floatingInput"
                                for="telf">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="descripcion" rows="10" required
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.description") }}"></textarea>
                            <label class="floatingInput"
                                for="descripcion">{{ trans("$theme-app.user_panel.description") }}</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-10">
                        <div class="position-relative mb-3" id="dropzone">
                            <p class="text-danger error-dropzone" style="display:none">
                                <small>{{ trans("$theme-app.msg_error.max_size") }}</small>
                            </p>
                            <p class="text-dropzone">{!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}</p>
                            <div class="mini-file-content d-flex align-items-center position-relative gap-2 mt-1"></div>
                            <input id="images" name="imagen[]" type="file" multiple />
                        </div>

                        <p class="captcha-terms mb-3">
                            {!! trans("$theme-app.global.captcha-terms") !!}
                        </p>

                        <button class="button-send-valorate btn btn-lb-primary" id="valoracion-adv"
                            type="submit">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
                    </div>
                </div>

            </form>
        </div>
    </main>

    <script>
        var imagesarr = [];

        function myFunction(el) {
            $(el).remove()
        }
        $(function() {

            $('.mini-upload-image').click(function() {
                alert()
            })


        });
    </script>
@stop
