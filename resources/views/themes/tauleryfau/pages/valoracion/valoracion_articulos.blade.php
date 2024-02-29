@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.valoracion_gratuita.solicitud_valoracion') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="tasacion-desc">
    <div class="container">
        <div class="row pt-4 mb-4">
            <div class="col-sm-6 col-xs-12 mb-4">
                <div class="valoracion-imagen">
                    <img class="img-responsive" src="/themes/{{$theme}}/img/servicios/foto-portada-articulo.jpg">
                </div>
            </div>
            <div class="col-sm-6 col-xs-12 text-description-tasacion">

                                <?php
                                $key = "tasacion-valoraciones_".strtoupper(Config::get('app.locale'));
                                $html="{html}";
                                $content = \Tools::slider($key, $html);
                                ?>
                                <?= $content; ?>

            </div>
                </div>
        </div>

        <div class="custom-bar-title">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                            <div>{{ trans($theme.'-app.services.get_valoration') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-content-tasacion">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 pt-4">
                            <div class="add-title mb-3">
                                    <h3>{{ trans($theme.'-app.valoracion_gratuita.valores_su_producto') }}</h3>
                                    <p><?= trans($theme.'-app.valoracion_gratuita.description') ?></p>
                                </div>
                                <div class="add-object-content flex">
                                    <form class="form upload" id="form-valoracion-adv" style="width: 100%; position: relative;">
                                        <div class="loader-container" style="
                                            position:  absolute;
                                            align-items:  center;
                                            justify-content:  center;
                                            height:  100%;
                                            width:  100%;
                                            z-index:  99;
                                            background: white;
                                            display: none;
                                        ">
                                            <div class="loader"></div>
                                        </div>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="add-picture flex valign">
                                            <div class="text-center files1 fill ">
                                                <input id="files1" type="file" class="files"  accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple>
                                                <label for="files1">
                                                    <i class="fa fa-camera-retro"></i>
                                                    <img class="img-responsive" style="max-height: 65px;" />
                                                </label>
                                                <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                            </div>
                                            <div class="text-center files2 fill">
                                                <input id="files2" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                                <label for="files2">
                                                    <i class="fa fa-camera-retro"></i>
                                                    <img class="img-responsive" style="max-height: 65px;" />
                                                </label>
                                                <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                            </div>
                                            <div class="text-center files3 fill">
                                                <input id="files3" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                                <label for="files3">
                                                    <i class="fa fa-camera-retro"></i>
                                                    <img class="img-responsive" style="max-height: 65px;" />
                                                </label>
                                                <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-6 mb-1 ">
                                            <input type="text" name="name" placeholder="<?=  trans($theme.'-app.valoracion_gratuita.name')  ?>" required class="form-control">
                                        </div>
                                        <div class="form-group col-xs-6 mb-1">
                                            <input type="text" name="telf" placeholder="<?=  trans($theme.'-app.valoracion_gratuita.telf')  ?>" required class="form-control">
                                        </div>
                                        <div class="form-group col-xs-12 mb-1">
                                             <input type="email" name="email" placeholder="<?=  trans($theme.'-app.valoracion_gratuita.email')  ?>" required class="form-control">
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="text-area">
                                                <textarea class="form-control" id="exampleTextarea" name="descripcion" required placeholder="{{ trans($theme.'-app.valoracion_gratuita.description') }}"></textarea>
                                            </div>

                                        </div>
                                        <input type="hidden" value="info@tauleryfau.com" name="email_category">

                                        <div class="col-xs-12 send-button" style="margin-top: 0;">
                                            <button type="submit" id="valoracion-adv" class="btn btn-color" ><div class='loader hidden'></div>{{ trans($theme.'-app.valoracion_gratuita.send') }}</button>
                                            <h4 class="valoracion-h4 hidden msg_valoracion">{{ trans($theme.'-app.valoracion_gratuita.error') }}</h4>

                                        </div>

                                    </form>
                                </div>
                    </div>
                </div>
            </div>
        </div>

</section>

<div class="mt-5 mb-3">
	@include('includes.google_reviews')
</div>




@stop






