@php
    $name = $data['usuario']->nom_cliweb ?? '';
    $phone = $data['usuario']->tel1_cli ?? '';
    $email = $data['usuario']->email_cliweb ?? '';

    $pathImage = 'img/' . \Config::get('app.emp') . '/' . $lote_actual->num_hces1 . '/' . \Config::get('app.emp') . '-' . $lote_actual->num_hces1 . '-' . $lote_actual->lin_hces1 . '_01.jpg';
    $numberImage = !file_exists($pathImage) ? 0 : 1;
    $image = Tools::url_img_friendly('real', $lote_actual->num_hces1, $lote_actual->lin_hces1, $numberImage, Str::slug($lote_actual->descweb_hces1));

    $nbsp = html_entity_decode('&nbsp;');
    $autionTitle = html_entity_decode($lote_actual->title_url_subasta);
    $autionTitle = str_replace($nbsp, '', $autionTitle);
    /**
     * Caracteristicas
     * 1 - Autor
     * 2 - Técnica
     * 3 - Medidas
     * */
    $artistName = Tools::changePositionNamesWithComa($caracteristicas[1]->value_caracteristicas_hces1 ?? '');
@endphp

<div class="container-fluid gx-0">
    <div class="row gx-0">

        <div class="col-lg-7 position-relative">
            <img src="{{ $image }}" alt="{{ $lote_actual->descweb_hces1 }}" width="1100" height="900"
                class="image-gallery" style="width: 100%; height: auto;">

        </div>

        <div class="col-lg-5 ficha-gallery-info p-3 p-md-5">

            <div>
                @if (!empty($caracteristicas[1]->value_caracteristicas_hces1))
                    @if (request()->has('artistaFondoGaleria'))
                        <a
                            href="{{ route('artistaGaleria', ['id_artist' => $caracteristicas[1]->idvalue_caracteristicas_hces1]) }}">
                            <h1 class="page-title d-inline-block">
                                {!! $artistName !!}
                            </h1>
                        </a>
                    @else
                        <h1 class="page-title d-inline-block">
                            {!! $artistName !!}
                        </h1>
                    @endif
                @endif

                <h2 class="ff-highlight fs-24">{{ $autionTitle }}</h2>
            </div>

            <div class="ficha-gallery-description">
                <p>{{ $lote_actual->descweb_hces1 }}</p>
                <p>{!! $lote_actual->descdet_hces1 !!}</p>
                <p>{!! $caracteristicas[2]->value_caracteristicas_hces1 ?? '' !!}</p>
                <p>{!! $caracteristicas[3]->value_caracteristicas_hces1 ?? '' !!}</p>
            </div>

            <a class="lb-link-underline ficha-gallery-see-form" data-bs-toggle="collapse" href="#formRequest"
                role="button" aria-expanded="false" aria-controls="formRequest">
                {{ trans("$theme-app.galery.request_information") }}
                <span></span>
            </a>

            <div class="collapse" id="formRequest">
                <form name="infoLotForm" id="infoLotForm" method="post" onsubmit="sendInfoLot(event)">
                    @csrf
					<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">
                    <input type="hidden" name="auction"
                        value="{{ $lote_actual->cod_sub }} - {{ $lote_actual->des_sub }}">
                    <input type="hidden" name="lot_name"
                        value="  Obra: {{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->descweb_hces1 }} ">

                    @foreach ($caracteristicas as $key => $caracteristica)
                        @if ($key != 1)
                            <input type="hidden" name="{{ $caracteristica->name_caracteristicas }}"
                                value="{{ $caracteristica->value_caracteristicas_hces1 }}">
                        @else
                            <input type="hidden" name="Autor" value="{{ $artistName }}">
                        @endif
                    @endforeach

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input class="form-control" id="texto__1__nombre" name="nombre"
                                    placeholder="{{ trans("$theme-app.login_register.contact") }}" required
                                    type="text" value="{{ $name }}" onblur="comprueba_campo(this)"
                                    autocomplete="off" />
                                <label for="nombre">
                                    {{ trans("$theme-app.login_register.contact") }}
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input class="form-control" id="email__1__email" name="email"
                                    placeholder="{{ trans("$theme-app.login_register.foot.newsletter_text_input") }}"
                                    required type="email" value="{{ $email }}" onblur="comprueba_campo(this)"
                                    autocomplete="off" />
                                <label for="email">
                                    {{ trans("$theme-app.foot.newsletter_text_input") }}
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating">
                                <input class="form-control" id="texto__1__telefono" name="telefono"
                                    placeholder="{{ trans("$theme-app.user_panel.phone") }}" required type="tel"
                                    value="{{ $phone }}" onblur="comprueba_campo(this)" autocomplete="off" />
                                <label for="telefono">
                                    {{ trans("$theme-app.user_panel.phone") }}
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="comentario" id="textogrande__0__comentario" rows="10"></textarea>
                                <label for="comentario">
                                    {{ trans("$theme-app.global.coment") }}
                                </label>
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

                        <div class="text-center text-lg-end">
                            <button type="submit" class="btn btn-lb-primary btn-medium">
                                {{ trans("$theme-app.valoracion_gratuita.send") }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            @if (request('artistaFondoGaleria'))
                @include('includes.breadcrumb_atist_before_after')
            @else
                <div class="prev-next-buttons">
                    @if (!empty($data['previous']))
                        <a class="swiper-button-prev" title="{{ trans("$theme-app.subastas.last") }}"
                            href="{{ $data['previous'] }}">
                        </a>
                    @endif

                    @if (!empty($data['next']))
                        <a class="swiper-button-next" title="{{ trans("$theme-app.subastas.next") }}"
                            href="{{ $data['next'] }}">
                        </a>
                    @endif

                </div>
            @endif

        </div>

    </div>
</div>
