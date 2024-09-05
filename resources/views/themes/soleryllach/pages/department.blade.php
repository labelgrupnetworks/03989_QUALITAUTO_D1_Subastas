@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    <div
        style="width: 100% ;height: 360px;background: url('/themes/soleryllach/assets/img/departamentos/department{{ $ortsec->lin_ortsec0 }}_large.jpg');background-repeat:no-repeat;background-position: center;background-size: cover;">
    </div>

    <main class="specialist-page">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 resultok">

                    <?php
                    $title = '';
                    if (!empty($especialistas)) {
                        $title = head($especialistas)->titulo_especial0;
                    }
                    ?>
                    <h1 class="titlePage">
                        {{ $title }}
                    </h1>

                </div>
                <div class="col-lg-12">

                    <div class="lotes_destacados" style="margin-top: 80px;">
                        <div class="container">
                            <div class="title_lotes_destacados">
                                {{ trans("$theme-app.valoracion_gratuita.our_experts") }}
                            </div>
                        </div>
                    </div>

                    @foreach ($especialistas as $esp)
                        <?php
                        $name_archive = '/img/PER/' . Config::get('app.gemp') . $esp->per_especial1 . '.jpg';

                        if (file_exists($name_archive)) {
                            $name_archive = '/themes/' . \Config::get('app.theme') . '/img/items/no_photo.png';
                        }
                        ?>

                        <div class="col-lg-6" style="margin-top:20px;min-height: 172px;">
                            <div class="row">
                                <div class="col-lg-5">
                                    <img class="img-responsive" src="<?= $name_archive ?>" width="128px">
                                </div>
                                <div class="col-lg-7 margin_30">
                                    <p> <?= ucwords($esp->nom_especial1) ?></p>
                                    <a
                                        href="mailto:{{ strtolower($esp->email_especial1) }}">{{ strtolower($esp->email_especial1) }}</a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lotes_destacados" style="margin-top: 80px;">
                <div class="container">
                    <div class="title_lotes_destacados">
                        {{ trans("$theme-app.valoracion_gratuita.contact_us") }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">

                    <form action="/api-ajax/mail" method="post" onsubmit="sendContactForm(event)">
                        <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                            value="">

                        <div class="form-group">
                            <label for="Nombre"><strong>{{ trans("$theme-app.login_register.nombre") }}</strong></label>
                            <input class="form-control" name="nombre" type="text" aria-required="true"
                                placeholder="{{ trans("$theme-app.login_register.nombre") }}" required>
                        </div>
                        <div class="form-group">
                            <label for="E-mail">E-mail</label>
                            <input class="form-control" name="email" type="text" aria-required="true"
                                placeholder="E-mail" required>
                        </div>
                        <div class="form-group">
                            <label for="Teléfono">{{ trans("$theme-app.login_register.phone") }}</label>
                            <input class="form-control" name="telefono" type="text" aria-required="true"
                                placeholder="{{ trans("$theme-app.login_register.phone") }}" required>
                        </div>
                        <div class="form-group">
                            <label for="Comentario">{{ trans("$theme-app.global.coment") }}</label>
                            <textarea class="form-control" id="" name="comentario" aria-required="true" required cols="30"
                                rows="4"></textarea>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input name="condiciones" type="checkbox" required="">{!! trans("$theme-app.login_register.read_conditions_politic") !!}</u>
                            </label>
                        </div>

                        <p>
                            * {{ trans("$theme-app.login_register.all_fields_are_required") }}
                        </p>

                        <div class="mt-1">
                            <p class="captcha-terms">
                                {!! trans("$theme-app.global.captcha-terms") !!}
                            </p>
                        </div>

                        <button class="btn btn-contact btn-color" id="buttonSend" type="submit" style="cursor:pointer;">
                            {{ trans("$theme-app.login_register.send") }}
                        </button>
                    </form>
                </div>
                <div class="col-lg-2"></div>
            </div>

            <!-- Inicio lotes destacados -->
            <div class="lotes_destacados" style="margin-top: 80px;">
                <div class="container">
                    <div class="title_lotes_destacados">{{ trans("$theme-app.subastas.current_lots") }}</div>
                    <div class="loader"></div>
                    <div class="owl-theme owl-carousel" id="lotes_departamentos"></div>
                </div>
            </div>

        </div>
    </main>

    @php
        $replace = [
            'departamento' => $ortsec->lin_ortsec0,
            'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
            'emp' => Config::get('app.emp'),
            'gemp' => Config::get('app.gemp'),
        ];
    @endphp

    <script type="text/javascript">
        var replace = @json($replace);
        $(document).ready(function() {
            ajax_carousel('lotes_departamentos', replace);
        });
    </script>

@stop
