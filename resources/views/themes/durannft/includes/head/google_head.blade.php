@php
    use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();

	Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);
    Config::set('app.cookies.advertising', [CookieService::THIRD_FACEBOOK]);
@endphp

@if($cookiesPreferences->isAnalysisAllowed())
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109331316-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-109331316-1');
</script>
@else
<script>
	ga = () => {};
	gtag = () => {};
</script>
@endif
