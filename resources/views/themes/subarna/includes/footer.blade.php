

@include('includes.footer-section')

@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

