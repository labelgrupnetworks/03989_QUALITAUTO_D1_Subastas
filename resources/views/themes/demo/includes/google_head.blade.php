@php
    use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();

    Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);

@endphp

@if (!$cookiesPreferences->isAnalysisAllowed() )
	<script>
		// Define dataLayer and the gtag function.
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}

		// Set default consent to 'denied' as a placeholder
		// Determine actual values based on your own requirements
		gtag('consent', 'default', {
		'ad_storage': 'denied',
		'ad_user_data': 'denied',
		'ad_personalization': 'denied',
		'analytics_storage': 'denied'
		});
		</script>
@endif
<script async src="https://www.googletagmanager.com/gtag/js?id=G-710N5ZLNQD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-710N5ZLNQD');
</script>
