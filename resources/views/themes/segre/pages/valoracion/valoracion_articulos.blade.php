@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => $data['title']];
    @endphp

    <main class="valoracion-page">

        {!! BannerLib::bannerWithView('valoracion-page', 'hero', [
            'title' => trans("$theme-app.valoracion_gratuita.solicitud_valoracion"),
            'breadcrumb' => view('includes.breadcrumb', ['bread' => $bread])->render(),
        ]) !!}

        <div class="container mt-5">

            <p class="optimal-text-lenght m-auto text-center mb-3">
                {!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}
            </p>

            <form class="mt-5" id="form-valoracion-adv" action="">
                @csrf
                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">

                <p class="text-danger h4 hidden msg_valoracion">
                    {{ trans($theme . '-app.valoracion_gratuita.error') }}</p>

                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="mb-3 pb-3">
                            <label class="form-label"
                                for="name">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
                            <input class="form-control form-control-p-lg" id="name" name="name" type="text"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" required>
                        </div>
                        <div class="mb-3 pb-3">
                            <label class="form-label"
                                for="email">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
                            <input class="form-control form-control-p-lg" id="email" name="email" type="email"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="telf">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
                            <input class="form-control form-control-p-lg" id="telf" name="telf" type="phone"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 pb-3 h-100 d-flex flex-column">
                            <label class="form-label"
                                for="descripcion">{{ trans("$theme-app.user_panel.description") }}</label>
                            <textarea class="form-control form-control-p-lg flex-grow-1" name="descripcion" rows="10" required
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.description") }}"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center my-3 pb-3">
                    <div class="col-md-10">
                        <div class="position-relative mb-3 pb-3" id="dropzone">
                            <p class="text-danger error-dropzone" style="display:none">
                                <small>{{ trans("$theme-app.msg_error.max_size") }}</small>
                            </p>
                            <p class="text-dropzone">{!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}</p>
                            <div class="mini-file-content d-flex align-items-center position-relative gap-2 mt-1"></div>
                            <input id="images" name="imagen[]" type="file" multiple />
                        </div>

						<label for="specialist">
							{{ trans('web.valoracion_gratuita.select_department') }}
						</label>
						<select class="form-select mb-3" name="to_specialist"
							id="specialist">
							@foreach ($data['especialistas'] as $especialista)
								<option value="{{ $especialista->per_especial1 }}">{{ $especialista->specialty->title }} - {{ $especialista->description }}</option>
							@endforeach
						</select>

                        <p class="captcha-terms mb-3 pb-3">
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
