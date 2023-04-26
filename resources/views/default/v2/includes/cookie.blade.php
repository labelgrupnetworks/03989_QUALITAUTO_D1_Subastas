<div class="container-fluid py-3 cookies-block" id="cookie_law" token="{{ csrf_token() }}">
    <h6 class="cookie-title">{{ trans(\Config::get('app.theme') . '-app.cookies.cookies_policy') }}</h6>
	<p>
		{!! trans(\Config::get('app.theme') . '-app.msg_neutral.cookie_law') !!}
	</p>

    <div class="text-center mt-3">
        @if (config('app.set_cookies', 1))
            <a class='btn btn-lb-primary' href="{{ route('cookieConfig', ['lang' => \Config::get('app.locale')]) }}">
                {{ trans(\Config::get('app.theme') . '-app.cookies.configure') }}
            </a>
        @endif

        <button class='btn btn-lb-secondary-gold' id='accept_all_cookies'>
            {{ trans(\Config::get('app.theme') . '-app.cookies.accept') }}
        </button>
    </div>
</div>
