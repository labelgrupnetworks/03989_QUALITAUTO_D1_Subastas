<?php

    $families = array();

    $families[2] = trans(\Config::get('app.theme').'-app.subastas.proximas_subastas'); // Próximas subastas
    $families[3] = trans(\Config::get('app.theme').'-app.subastas.abanicos');    // Abanicos
    $families[4] = trans(\Config::get('app.theme').'-app.subastas.alfombras');   // Alfombras
    $families[5] = trans(\Config::get('app.theme').'-app.subastas.ceramica');    // Cerámica
    $families[6] = trans(\Config::get('app.theme').'-app.subastas.contemporaneo');    // Contemporáneo
    $families[7] = trans(\Config::get('app.theme').'-app.subastas.cristal');     // Cristal
    $families[8] = trans(\Config::get('app.theme').'-app.subastas.escultura');   // Escultura
    $families[9] = trans(\Config::get('app.theme').'-app.subastas.joyas');       // Joyas
    $families[10] = trans(\Config::get('app.theme').'-app.subastas.lamparas');   // Lámparas
    $families[11] = trans(\Config::get('app.theme').'-app.subastas.miniaturas'); // Miniaturas
    $families[12] = trans(\Config::get('app.theme').'-app.subastas.muebles');    // Muebles
    $families[13] = trans(\Config::get('app.theme').'-app.subastas.oriental');   // Oriental
    $families[14] = trans(\Config::get('app.theme').'-app.subastas.pintura');    // Pintura
    $families[15] = trans(\Config::get('app.theme').'-app.subastas.plata');      // Plata
    $families[16] = trans(\Config::get('app.theme').'-app.subastas.porcelana');  // Porcelana
    $families[17] = trans(\Config::get('app.theme').'-app.subastas.relojes');    // Relojes
    $families[18] = trans(\Config::get('app.theme').'-app.subastas.tapices');    // Tapices
    $families[19] = trans(\Config::get('app.theme').'-app.subastas.varios');     // Varios

?>


<form class="form-inline" id="form-newsletter" method="POST">
    <div class="newsletter" id="newsletter">
        <div class="col-xs-12 no-padding">
            <label class="grey-color font-100">Email</label>
            <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">
            <input class="form-control newsletter-input newsletter-input-alcala" type="text" placeholder="" style="border:1px solid lightgray" name="email">

        </div>
        <div class="col-xs-12 no-padding">
            <fieldset>
                <legend>{{trans(\Config::get('app.theme').'-app.foot.send_info')}}</legend>

                @foreach ($families as $key => $familie)
                    <div class="check_term">
                    <input type="checkbox" class="newsletter" name="families[]" value="{{$key}}" id="newsletter{{$key}}">
                        <label>{{ $familie }}</label>
                    </div>
                @endforeach

            </fieldset>

            <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}" data-callback="onSubmit"></div>
                </div>
            </div>

            <br>

            <div class="check_term row first_check">
                <div class="col-xs-2 col-sm-1">
                    <input type="checkbox" class="newsletter" name="condiciones" value="on" id="bool__1__condiciones" autocomplete="off">
                </div>
                <div class="col-xs-10 col-sm-11">
                    <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                </div>
            </div>

            <div class="check_term row">
                <div class="col-xs-2 col-sm-1">
                    <input type="checkbox" name="families[]" value="1" id="bool__0__comercial" autocomplete="off">
                </div>
                <div class="col-xs-10 col-sm-11">
                    <label for="bool__0__comercialFooter">{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</label>
                </div>
            </div>

            <br>
            <center><button id="newsletter-btn" type="button" class="btn btn-lg btn-custom newsletter-input">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button></center>
            <br><br>
        </div>
    </div>
</form>


