@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => $data['title']];
    @endphp

    <main class="container valoracion-page">

        <h1 class="titlePage">{{ trans("$theme-app.valoracion_gratuita.solicitud_valoracion") }}</h1>

        <section class="row gx-lg-5 gy-3 py-3 py-lg-4">
            <div class="col-12 col-lg-7">
                <div class="decoration margin-video">
                    <div class="ratio ratio-16x9 lb-fadeIn lb-fadeInLeft">
                        <video controls="" poster="/themes/jesusvico/assets/img/how_to_sell_poster.jpg" autoplay="">
                            <source src="/files/videos/jesusvico_tasaciones.mp4" type="video/mp4">
                            Tu navegador no soporta HTML5 video.
                        </video>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 ps-lg-5 d-flex flex-column">

                <h3 class="lb-fadeIn lb-fadeInRight mt-lg-5">¿Desea obtener una valoración de sus artículos?</h3>

                <p class="fw-lighter my-auto lb-fadeIn lb-fadeInRight" style="--delay: 0.4s;">
                    Uno de nuestros expertos valorará los objetos que detalle a continuación, y se podrá en contacto con
                    usted para
                    transmitirle los resultados.
                </p>

                {{-- Flecha --}}
                <button class="btn border-0 lb-fadeIn lb-fadeInRight mt-4" style="--delay: 0.6s;"
                    onclick="scrollToElement('#section')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="49.162" height="40.404" viewBox="0 0 49.162 40.404">
                        <g id="Grupo_40" data-name="Grupo 40" transform="translate(-240.156 -563.202)">
                            <path id="Trazado_2" data-name="Trazado 2" d="M140.429,44.608,164.3,68.482l23.874-23.874"
                                transform="translate(100.433 533.71)" fill="none" stroke="#b9b13c" stroke-width="2" />
                            <path id="Trazado_3" data-name="Trazado 3" d="M140.429,44.608,164.3,68.482l23.874-23.874"
                                transform="translate(100.433 519.301)" fill="none" stroke="#b9b13c" stroke-width="2"
                                opacity="0.398" />
                        </g>
                    </svg>
                </button>
            </div>
        </section>

        <hr class="separator">

        <section id="section">
            <div class="row">

                <div class="pt-5 mt-3 mb-5">
                    <h4 class="fw-lighter">Por favor, añada la máxima información posible para garantizar una revisión más
                        precisa.</h4>
                </div>

                <form class="mt-3" id="form-valoracion-adv" action="">
                    @csrf
                    <p class="text-danger h4 hidden msg_valoracion">
                        {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.error') }}</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label h4"
                                    for="name">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
                                <input class="form-control input-text-style" id="name" name="name" type="text"
                                    {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" --}} required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label h4"
                                    for="email">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
                                <input class="form-control input-text-style" id="email" name="email" type="email"
                                    {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" --}} required>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label h4"
                                    for="telf">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
                                <input class="form-control input-text-style" id="telf" name="telf" type="phone"
                                    {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" --}} required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label h4"
                                    for="descripcion">{{ trans("$theme-app.user_panel.description") }}</label>
                                <textarea class="form-control input-text-style" name="descripcion" rows="10" required {{-- placeholder="{{ trans("$theme-app.valoracion_gratuita.description") }}" --}}></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="position-relative" id="dropzone">
                                <p class="text-danger error-dropzone" style="display:none">
                                    <small>{{ trans("$theme-app.msg_error.max_size") }}</small>
                                </p>
                                <p class="text-dropzone">{!! trans("$theme-app.valoracion_gratuita.adj_IMG") !!}</p>
                                <div class="mini-file-content d-flex align-items-center position-relative gap-2 mt-1"></div>
                                <input id="images" name="imagen[]" type="file" multiple />
                            </div>
                        </div>
                    </div>

                    <button class="button-send-valorate btn btn-lb-primary" id="valoracion-adv"
                        type="submit">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
                </form>
            </div>
        </section>

    </main>

    <script>
        var imagesarr = [];

        function myFunction(el) {
            $(el).remove()
        }
    </script>
@stop
