<?php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
?>


<form class="form-inline" id="form-newsletter" method="POST">
    <div class="newsletter js-newletter-block" id="newsletter">
        <div class="col-xs-12 no-padding">
            <label class="grey-color font-100">Email</label>
            <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">
            <input class="form-control newsletter-input newsletter-input-alcala" type="text" placeholder="" style="border:1px solid lightgray" name="email">

        </div>
        <div class="col-xs-12 no-padding">
            <fieldset>
                <legend>{{trans(\Config::get('app.theme').'-app.foot.send_info')}}</legend>

                @foreach ($newsletters  as $id_newsletters => $name_newsletters)
                    <div class="check_term">
						<input id="newsletter{{$id_newsletters}}" type="checkbox" class="newsletter" name="families[{{$id_newsletters}}]" value="{{$id_newsletters}}">
						<label for="newsletter{{$id_newsletters}}">
							{{$name_newsletters}}
						</label>
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
                    <input type="checkbox" name="families[1]" value="1" id="bool__0__comercial" autocomplete="off">
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


