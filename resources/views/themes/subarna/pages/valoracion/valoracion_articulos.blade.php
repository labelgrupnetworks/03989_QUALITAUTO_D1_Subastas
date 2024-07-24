@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    $specialist = (new App\Models\Enterprise())->getSpecialist('0001');
@endphp

@section('content')
    <div class="tasaciones">
        <div class="background-light-green">

            <div class="container padding-contact-sections">
                <div class="row top-align-items-center">
                    <div class="col-xs-12 col-md-7 col-lg-8">
                        <div class="title-container">
                            <h1>
                                {!! trans("$theme-app.valoracion_gratuita.page_title") !!}
                            </h1>
                        </div>
                    </div>
                    <div class="col-xs-0 col-md-5 col-lg-4">
                        <img class="header-image height-image img-responsive"
                            src="/themes/{{ $theme }}/assets/img/top_banner_image_rounded.png">
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="flex-tasaciones-info mb-2">
                    <section class="tasaciones-info_column">
                        <img class="img-responsive image-column"
                            src="/themes/{{ $theme }}/assets/img/static_pages/smart_cropped.png">
                        <div class="tasaciones-info_content">
                            <p class="text-center">
                                {!! trans("$theme-app.valoracion_gratuita.first_column_desc_info") !!}
                            </p>
                        </div>
                    </section>
                    <section class="tasaciones-info_column">
                        <img class="img-responsive image-column"
                            src="/themes/{{ $theme }}/assets/img/static_pages/pc.png">
                        <div class="tasaciones-info_content">
                            <p class="text-center">
                                {!! trans("$theme-app.valoracion_gratuita.second_column_desc_info") !!}
                            </p>
                        </div>
                    </section>
                    <section class="tasaciones-info_column">
                        <img class="img-responsive image-column"
                            src="/themes/{{ $theme }}/assets/img/static_pages/up-hand.png">
                        <div class="tasaciones-info_content">
                            <p class="text-center">
                                {!! trans("$theme-app.valoracion_gratuita.third_column_desc_info") !!}
                            </p>
                        </div>
                    </section>
                </div>
            </div>

        </div>

        <div class="background-more-light-green">
            <div class="container padding-contact-sections pb-0">
                <form class="form" id="form-valoracion-adv">
                    <div class="hidden hidden-inputs">
                        <input name="email_category" type="hidden" value="tasaciones@subarna.net">
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="input-margin">
                                <label class="d-block" for="texto__1__name">
                                    {{ trans("$theme-app.contact.form_field_name") }}
                                </label>
                                <input class="form-control" id="texto__1__name" name="name" type="text" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="input-margin">
                                <label class="d-block" for="email__1__email">
                                    {{ trans("$theme-app.contact.form_field_email") }}
                                </label>
                                <input class="form-control" id="email__1__email" name="email" type="email" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="input-margin">
                                <label class="d-block" for="texto__1__telf">
                                    {{ trans("$theme-app.contact.form_field_tlfnum") }}
                                </label>
                                <input class="form-control" id="texto__1__telf" name="telf" type="text" required>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-margin">
                                <label class="fs-15em">
                                    <p>{{ trans("$theme-app.valoracion_gratuita.form_field_files") }}</p>
                                </label>
                                <div class="input-files-contact">
                                    <label class="btn btn-lb-primary" for="files">
                                        {{ trans("$theme-app.contact.form_field_files_button") }}
                                    </label>
                                    <div>
                                        <p>{!! trans("$theme-app.contact.form_field_files_info") !!}</p>
                                    </div>
                                </div>
                                <input class="hidden" id="files" name="imagen[]" type="file"
                                    accept="image/png, image/jpeg" multiple required>
                                <div class="contact-images-preview"></div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="input-margin">
                                <label class="d-block fw-normal" for="textogrande__1__descripcion">
                                    {{ trans("$theme-app.valoracion_gratuita.form_field_description") }}
                                </label>
                                <textarea class="form-control effect-16" id="textogrande__1__descripcion" name="descripcion" rows="10"
                                    placeholder="" autocomplete="off"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 text-center">
                            <button class="button-principal submitButton btn btn-lb-primary" id="valoracion-adv"
                                type="submit">
                                <div class='loader hidden'></div>{{ trans($theme . '-app.valoracion_gratuita.send') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('includes.expert-contact', [
            'title' => trans("$theme-app.valoracion_gratuita.talking_on_phone"),
            'specialist' => $specialist,
        ])

    </div>

@stop
