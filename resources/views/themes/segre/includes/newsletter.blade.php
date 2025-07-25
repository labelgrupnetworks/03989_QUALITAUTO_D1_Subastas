<div class="newsletter newsletter-js">

    <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">
    <input id="lang-newsletter" class="lang-newsletter-js" type="hidden" value="<?= \App::getLocale() ?>">
    <input class="newsletter" id="newsletter-input" name="families[]" type="hidden" value="1">

    <div class="input-group py-2">
        <input class="form-control form-control-sm newsletter-input newsletter-input-email-js" name="email-newsletter" type="email" aria-label="Email"
            placeholder="Email">

        <button class="input-group-text button-newsletter newsletter-btn-js" id="newsletter-btn" type="button">
            {{ trans($theme . '-app.foot.newsletter_button') }}
        </button>
    </div>

    <div class="form-check">
        <input class="form-check-input condiciones-newsletter-js" id="condiciones" name="condiciones" type="checkbox">
        <label class="form-check-label" for="condiciones">{!! trans($theme . '-app.login_register.read_conditions_politic') !!}</label>
    </div>

	<div class="newsletter-terms">
		<p>{{ trans($theme . '-app.foot.newslatter_lopd') }}</p>
		<p>{!! trans("$theme-app.global.captcha-terms") !!}</p>
	</div>

</div>
