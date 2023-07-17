<div class="cookies-container js-cookie-session" id="cookie_law" token="{{ csrf_token() }}" style="display: none">
	<div class="container-fluid">
		<div class="row d-flex align-items-center flex-wrap">
			<div class="col-12 col-xs-12 col-lg-2 mb-4"></div>
			<div class="col-12 col-xs-12 col-lg-5 mb-4">
				<p>
					{!! trans(\Config::get('app.theme').'-app.msg_neutral.cookie_law') !!}
				</p>
			</div>
			<div class="col-12 col-xs-12 col-lg-5 mb-4 d-flex gap-2 flex-wrap">
				@if (config('app.set_cookies', 1))
				<a class='btn btn-cookies btn-outline-lb-primary btn-medium'
					href="{{route('cookieConfig', ['lang' => \Config::get('app.locale')])}}">
					{{ trans(\Config::get('app.theme').'-app.cookies.configure') }}
				</a>
				@endif

				<button class='btn btn-cookies btn-lb-primary btn-medium' id='accept_all_cookies'>
					{{ trans(\Config::get('app.theme').'-app.cookies.accept') }}
				</button>
			</div>
		</div>
	</div>
</div>
