@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')
    <script src='https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}'></script>


    <main class="valoracion-page">
        <div class="container" id="return-valoracion">
            <div class="">
                <h1 class="titlePage">
                    {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
            </div>
            <br>
            <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.desc_assessment') ?>
            <form class="form" id="form-valoracion-adv">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <br>
                <div class="form-group form-group-custom form-group-custom-textarea">
                    <textarea class="form-control" id="exampleTextarea" rows="3" name="descripcion" required
                        placeholder="{{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.description') }}"></textarea>
                </div>
                <br>
                <div clas="row">
                    <div class="col-md-4">
                        <br>
                        <input id="files" type="file" accept="image/png, image/jpeg" name="imagen[]" multiple
                            required>
                    </div>
                    <div class="col-md-8">
                        <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.desc_img') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <br>
                        <br>
                        <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.name') ?>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.email') ?>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.telf') ?>
                        <input type="text" name="telf" required class="form-control">
                    </div>
                </div>
                <div clas="row">
                    <?= trans(\Config::get('app.theme') . '-app.valoracion_gratuita.desc_interesting_auctions') ?>

                </div>

                <input type="hidden" name="email_category" value="syl@soleryllach.com">
                <div class="check_term box">
                    <input name="condiciones" required type="checkbox" class="form-control" id="condiciones" />
                    <label for="recibir-newletter" class="condiciones">
                        <?= trans(\Config::get('app.theme') . '-app.login_register.read_conditions_politic') ?>
                    </label>
                </div>
                <div style="margin-top: 20px;" id="recaptcha" data-callback="recaptcha_callback" class="g-recaptcha"
                    data-sitekey="6LdhD34UAAAAANG9lkke6_b6fyycAsWTpfpm_sTV"></div>

                <div clas="row">
                    <div class="col-md-12">

                        <br>
                        <button type="submit" id="valoracion-adv" class="btn-valoracion  btn btn-primary btn-color">
                            <div class='loader hidden' style="height: 10px;width: 10px;margin-top: 0px;margin-bottom: 9px;">
                            </div>{{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.send') }}
                        </button>
                        <br><br>
                        <h4 class="valoracion-h4 hidden msg_valoracion">
                            {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.error') }}</h4>
                        <br>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
