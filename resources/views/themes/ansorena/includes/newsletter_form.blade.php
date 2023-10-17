<div class="newsletter-form">
    <p class="fs-5 lh-sm ff-highlight">{{ trans("$theme-app.foot.newsletter_title") }}</p>
    <input type="hidden" id="lang-newsletter" name="lang" value="{{ config('app.locale') }}">
    <div class="position-relative">
        <div class="form-floating">
            <input type="email" class="form-control newsletter-input" id="newsletter-input" name="email"
                placeholder="email@example.com" aria-label="newsletter email">
            <label for="newsletter-input">{{ trans("$theme-app.foot.newsletter_text_input") }}</label>
        </div>

        <button type="submit" class="btn btn-lb-primary btn-medium newsletter-submit" onclick="newsletterSuscription()">{{ trans("$theme-app.foot.suscribe_me") }}</button>
    </div>

    <div class="form-check">
        <input name="condiciones" type="checkbox" id="condiciones" type="checkbox" class="form-check-input">
        <label class="form-check-label" for="condiciones">{!! trans("$theme-app.login_register.read_conditions_politic") !!}</label>
    </div>

</div>
