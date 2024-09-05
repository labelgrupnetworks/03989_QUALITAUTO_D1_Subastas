<div class="newsletter js-newletter-block">
    <div class="container">
        <div class="row">
            <div class="newsletter-wrapper">
                <div class="section-title" style="flex: 1">
                    <i class="fa fa-5x fa-envelope"></i>
                    <div class="tit_newsletter">
                        {{ trans($theme . '-app.foot.newsletter_title') }}
                    </div>
                </div>
                <div style="flex: 1">
                    <div class="section-input mb-1">
                        <div class="input-group">
                            <input class="form-control input-lg newsletter-input" type="text"
                                placeholder="{{ trans($theme . '-app.emails.write_email') }}">
                            <div class="input-group-btn">
                                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}"
                                    type="hidden" value="">
                                <input id="lang-newsletter" name="lang" type="hidden"
                                    value="{{ config('app.locale') }}">
                                <input class="newsletter" id="newsletter-input" name="families[]" type="hidden"
                                    value="1">

                                <button class="btn btn-lg btn-custom newsletter-input" id="newsletter-btn"
                                    type="button">{{ trans($theme . '-app.foot.newsletter_button') }}</button>
                            </div>
                        </div>

                    </div>
                    <p class="captcha-terms">
                        {!! trans("$theme-app.global.captcha-terms") !!}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
