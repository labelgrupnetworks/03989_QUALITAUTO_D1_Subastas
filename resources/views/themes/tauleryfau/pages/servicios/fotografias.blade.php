@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php


?>

<section class="principal-bar no-principal services-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans(\Config::get('app.theme').'-app.services.photo_colecction') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<section class="services-page">
    <div class="container">
        <div class="row">

                <div class="col-sm-12 col-xs-12 text-description-tasacion mt-2 mb-4">
                        <?php
                        $key = "fotografias_" . strtoupper(Config::get('app.locale'));
                        $html = "{html}";
                        $content = \Tools::slider($key, $html);
                        ?>
                        <?= $content; ?>

                    </div>
        </div>
    </div>

    <div class="custom-bar-title mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                            <div>{{ trans(\Config::get('app.theme').'-app.services.the_best_photos') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12 text-description-tasacion mt-2 mb-4">
                    <?php
                    $key = "the_best_photos_" . strtoupper(Config::get('app.locale'));
                    $html = "{html}";
                    $content = \Tools::slider($key, $html);
                    ?>
                    <?= $content; ?>

                </div>
            </div>
        </div>
        <div class="custom-bar-title mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                            <div>{{ trans(\Config::get('app.theme').'-app.services.formats') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                    <div class="col-sm-12 col-xs-12 text-description-tasacion mt-2 mb-4">
                            <?php
                            $key = "shipping_deliveri_" . strtoupper(Config::get('app.locale'));
                            $html = "{html}";
                            $content = \Tools::slider($key, $html);
                            ?>
                            <?= $content; ?>

                        </div>
            </div>
        </div>


        <div class="custom-bar-title mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                            <div>{{ trans(\Config::get('app.theme').'-app.services.fill_and_calculate') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-custom-encapsulacion pt-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-xs-12 col-md-offset-1">
                        <form id="form-photographic-adv" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group col-sm-8 col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label">{{ trans(\Config::get('app.theme').'-app.services.label_name') }}</label>
                                <input required type="text" name="nombre" required class="form-control custom-input-encapsulacion">
                            </div>
                            <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label">{{ trans(\Config::get('app.theme').'-app.services.label_phone') }}</label>
                                <input required type="text" name="telf" required class="form-control custom-input-encapsulacion">
                            </div>
                            <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label">{{ trans(\Config::get('app.theme').'-app.services.label_email') }}</label>
                                <input required type="text" name="email"  required class="form-control custom-input-encapsulacion">
                            </div>
                            <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label" >{{ trans(\Config::get('app.theme').'-app.services.label_services') }}</label>
                                <select name="servicio" class="d-block w-100 custom-select">
                                    <option value="foto2d">{{ trans(\Config::get('app.theme').'-app.services.photo2d') }}</option>
                                    <option value="canto">{{ trans(\Config::get('app.theme').'-app.services.photo_cant') }}</option>
                                    <option value="imagen360">{{ trans(\Config::get('app.theme').'-app.services.image360') }}</option>

                                </select>
                            </div>
                            <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label" >{{ trans(\Config::get('app.theme').'-app.services.label_format') }}</label>
                                <select name="formato" class="d-block w-100 custom-select">
                                    <option value="pendrive">{{ trans(\Config::get('app.theme').'-app.services.pendrive') }}</option>
                                    <option value="tarjeton">{{ trans(\Config::get('app.theme').'-app.services.big_card') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-xs-12 mb-1 ">
                                <label class="form-custom-encapsulacion-label">{{ trans(\Config::get('app.theme').'-app.services.label_description') }}</label>
                                <textarea required rows=12 name="asunto"  required class="form-control custom-input-encapsulacion"></textarea>
                            </div>
                            <div class="form-encapsulacion-buttons col-xs-12 mb-4 mt-1">
                                <div class="button-calcular col-xs-12 pl-0 p-0"><button type="submit" class=" w-100">{{ trans(\Config::get('app.theme').'-app.services.send') }}</button></div>
                        </div>
                        <div class="col-xs-12 mb-2">
                                <div class="label label-success labels-form  d-none ">{{ trans(\Config::get('app.theme').'-app.services.form_success') }}</div>
                                <div class="label label-danger labels-form  d-none">{{ trans(\Config::get('app.theme').'-app.services.form_error') }}</div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
</section>

@stop
