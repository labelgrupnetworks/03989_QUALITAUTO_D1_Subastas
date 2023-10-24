@php
    $newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

<script src='https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}'></script>

<div class="newsletter js-newletter-block">

    <div class="news_home">
		<h2>Newsletter</h2>
        <h4>{{ trans("$theme-app.foot.newsletter_title") }}</h4>
        <form class="form-inline" id="form-newsletter">
            <div class="form-group">
                <label>{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_text_input') }} <label>
                        <input class="newsletter-input form-control" type="text" name="email">
						<input type="hidden" id="lang-newsletter" name="lang" value="{{ config('app.locale') }}">

            </div>

            <button id="newsletter-btn" class="btn-secondary-color btn"
                type="button">{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_button') }}</button>

            <ul class="list-unstyled my-3">
                <div class="list-block">
                    @foreach ($newsletters as $id_newsletters => $name_newsletters)
                        <li>
                            <div class="form-check">
                                <label>
                                    <input type="checkbox" class="newsletter" name="families[{{ $id_newsletters }}]"
                                        value="{{ $id_newsletters }}">
                                    {{ $name_newsletters }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </div>
            </ul>
            <div id="recaptcha" data-callback="recaptcha_callback" class="g-recaptcha"
                data-sitekey="6LdhD34UAAAAANG9lkke6_b6fyycAsWTpfpm_sTV"></div>

            <div class="check_term box">
                <input name="condiciones" required type="checkbox" class="form-control" id="condiciones" />
                <label for="recibir-newletter">
                    <?= trans(\Config::get('app.theme') . '-app.login_register.read_conditions_politic') ?>
                </label>
            </div>
        </form>
    </div>

</div>
