<section class="newsletter-section js-newletter-block">
    <input id="lang-newsletter" name="lang" type="hidden" value="{{ config('app.locale') }}">
    <input name="families[1]" type="hidden" value="1">
    <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden" value="">

    <div class="container">
        <div class="newsletter-grid">
			<p class="newsletter_subtitle">{{ trans("$theme-app.foot.newsletter_title") }}</p>
            <h3 class="newsletter_title">{{ trans("$theme-app.foot.newsletters") }}</h3>
            <p class="newsletter_desc">
                {{ trans("$theme-app.foot.newsletter_description") }}
            </p>

            <div class="newsletter_input position-relative">
                <div class="form-floating">
                    <input class="form-control newsletter-input" id="newsletter-input" name="email" type="email"
                        aria-label="newsletter email" placeholder="email@example.com">
                    <label for="newsletter-input">
                        {{ trans("$theme-app.foot.newsletter_text_input") }}
                    </label>
                </div>
            </div>

            <button class="btn btn-outline-lb-primary rounded-5" type="submit" onclick="newsletterSuscription()">
                {{ trans("$theme-app.foot.newsletter_button") }}
            </button>

            <div class="newsletter_legal">
                <div class="form-check">
                    <input class="form-check-input" id="condiciones" name="condiciones" type="checkbox" type="checkbox">
                    <label class="form-check-label" for="condiciones">{!! trans("$theme-app.login_register.read_conditions_politic") !!}</label>
                </div>
                <span class="captcha-terms">
                    {!! trans("$theme-app.global.captcha-terms") !!}
                </span>
            </div>

			<p class="line-1"></p>
			<p class="line-2"></p>

        </div>
    </div>
</section>
