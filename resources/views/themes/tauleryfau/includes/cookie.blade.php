<?php
$clientsCookies = json_decode(\Config::get('app.config_cookies', '{"google":["_ga", "_gid", "_gat"]}'), true);
?>
<div class="col-xs-12" id="cookie_law" token="{{ csrf_token() }}">
	{{--<span id="text_cookie_law"></span>--}}
	<span>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-xs-12 col-sm-12'>
					<p class="cookie-title">{{ trans(\Config::get('app.theme').'-app.cookies.cookies_policy') }}</p>
					{!! trans(\Config::get('app.theme').'-app.msg_neutral.cookie_law') !!}
				</div>
			</div>
			<form class="form-inline" method="POST"
				id="cookie-form" action="{{route('cookieConfig', ['lang' => \Config::get('app.locale')])}}">
				<input type="hidden" name="ajax" value="1">
				@csrf
				<div class="row mt-2 cookies-form" id="form-cookies" style="display: none">
					<div class="col-xs-12">
						<p class="cookie-title">{{ trans(\Config::get('app.theme').'-app.cookies.select_cookies') }}</p>
						<div class="row">
							<div class="col-xs-6 col-md-3">
								<div class="form-check form-check-inline">
									<input class="form-control filled-in" type="checkbox" id="inlineCheckbox_esentyals"
										value="1" checked="checked" disabled>
									<label class="form-check-label d-flex align-items-center cookies-label disabled" for="inlineCheckbox_esentyals">{{ trans(\Config::get('app.theme')."-app.cookies.cookie_name_esentials") }}</label>
								</div>
							</div>
							<div class="col-xs-6 col-md-3">
								<div class="form-check form-check-inline">
									<input class="form-contro filled-in" type="checkbox" id="inlineCheckbox_esentyals"
										value="1" checked="checked" disabled>
									<label class="form-check-label d-flex align-items-center cookies-label disabled" for="inlineCheckbox_esentyals">{{ trans(\Config::get('app.theme')."-app.cookies.cookie_name_preferences") }}</label>
								</div>
							</div>
						</div>
						<div class="row">
							@foreach (array_keys($clientsCookies) as $value)

							<div class="col-xs-6 col-md-3">
								<div class="form-check form-check-inline">
									<input class="form-contro filled-in" name="{{ $value }}" type="checkbox"
										id="inlineCheckbox{{$value}}" value="1" checked="checked">
									<label class="form-check-label d-flex align-items-center cookies-label" for="inlineCheckbox{{$value}}">{{ trans(\Config::get('app.theme')."-app.cookies.cookie_name_$value") }}</label>
								</div>
							</div>

							@endforeach
						</div>
						<div class="row">
							<div class="col-xs-12">
							<p><a href="{{route('cookieConfig', ['lang' => \Config::get('app.locale')])}}">{{trans(\Config::get('app.theme')."-app.cookies.view_more")}}</a></p>
							</div>
						</div>

					</div>
				</div>

				<div class="row mt-2">
					<div class='col-xs-12 text-center'>

						<a class='button-principal btn btn-cookies'
							href="javascript:$('#form-cookies').toggle('slow');">
							{{ trans(\Config::get('app.theme').'-app.cookies.configure') }}
						</a>

						<input type="submit" class='button-principal btn btn-cookies' id='accept_form_cookies'
							value="{{ trans(\Config::get('app.theme').'-app.cookies.accept') }}">

					</div>
				</div>
			</form>
		</div>
	</span>
</div>
