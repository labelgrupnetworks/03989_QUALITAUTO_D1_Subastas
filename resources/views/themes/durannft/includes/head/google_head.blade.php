@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

	Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE]);
    Config::set('app.cookies.advertising', [Cookies::THIRD_FACEBOOK]);
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
