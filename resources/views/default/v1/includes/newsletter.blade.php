@php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

<div class="newsletter js-newletter-block">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
                <div class="newsletter-tittle">{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_title') }}
                </div>
                <p class="newsletter-subtittle">
                    {{ trans(\Config::get('app.theme') . '-app.foot.newsletter_description') }}
                </p>
            </div>
            <div
                class="col-xs-12 col-sm-6  col-md-5 col-md-offset-1 col-lg-6 col-lg-offset-1 col-xs-offset-0 newsletter-control-input">
                <div class="newsletter-placeholder">
                    {{ trans(\Config::get('app.theme') . '-app.foot.newsletter_text_input') }}
                </div>
                <input class="form-control input-lg newsletter-input" type="text" placeholder="" name="email"
                    aria-label="newsletter email">
                <input type="hidden" id="lang-newsletter" name="lang" value="{{ config('app.locale') }}">

                <button id="newsletter-btn" type="submit"
                    class="button-principal button-newsletter">{{ trans(\Config::get('app.theme') . '-app.foot.newsletter_button') }}</button>
            </div>
            <div
                class="check_term box col-xs-12 col-sm-6  col-md-5 col-md-offset-1 col-lg-6 col-lg-offset-1 col-xs-offset-0">
				@forelse ($newsletters as $id_newsletters => $name_newsletters)
				<div class="form-check">
					<label>
						<input type="checkbox" class="newsletter" name="families[{{$id_newsletters}}]" value="{{$id_newsletters}}">
						{{$name_newsletters}}
					</label>
				</div>
				@empty
				<input type="hidden" name="families[1]" value="1">
				@endforelse
                <div class="form-check">
                    <input name="condiciones" type="checkbox" id="condiciones" type="checkbox" class="form-check-input">
                    <label class="form-check-label" for="condiciones">{!! trans(\Config::get('app.theme') . '-app.login_register.read_conditions_politic') !!}</label>
                </div>
            </div>
        </div>
    </div>
</div>
