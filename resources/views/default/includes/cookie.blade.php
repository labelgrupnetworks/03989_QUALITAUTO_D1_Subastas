{{--<div id="cookie_law" token="{{ csrf_token() }}"><span id="text_cookie_law"></span></div>--}}
<div class="col-xs-12 js-cookie-session" id="cookie_law" token="{{ csrf_token() }}" style="display: none">
	<span>
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-xs-12 col-sm-12'>
					<p class="cookie-title">{{ trans(\Config::get('app.theme').'-app.cookies.cookies_policy') }}</p>
					{!! trans(\Config::get('app.theme').'-app.msg_neutral.cookie_law') !!}
				</div>
			</div>

			<div class="row mt-2">
				<div class='col-xs-12 text-center'>

					@if (config('app.set_cookies', 1))
					<a class='btn btn-cookies'
						href="{{route('cookieConfig', ['lang' => \Config::get('app.locale')])}}">
						{{ trans(\Config::get('app.theme').'-app.cookies.configure') }}
					</a>
					@endif

					<a class='btn btn-cookies' id='accept_all_cookies'>
						{{ trans(\Config::get('app.theme').'-app.cookies.accept') }}
					</a>

				</div>
			</div>
		</div>
	</span>
</div>
