@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE, Cookies::THIRD_HOTJAR, Cookies::THIRD_LIVEAGENT]);
@endphp

<script>
	// Define dataLayer and the gtag function.
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
</script>

@if (!$cookiesPreferences->isAnalysisAllowed() )
	<script>
		// Set default consent to 'denied' as a placeholder
		// Determine actual values based on your own requirements
		gtag('consent', 'default', {
			'ad_storage': 'denied',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied',
			'analytics_storage': 'denied'
		});

		ga = () => {};
	</script>
@else
<script>
	gtag('consent', 'default', {
			'ad_storage': 'granted',
			'ad_user_data': 'granted',
			'ad_personalization': 'denied',
			'analytics_storage': 'granted'
		});

</script>

<!-- Hotjar Tracking Code for www.subarna.net -->
<script>
    (function(h, o, t, j, a, r) {
        h.hj = h.hj || function() {
            (h.hj.q = h.hj.q || []).push(arguments)
        };
        h._hjSettings = {
            hjid: 2028619,
            hjsv: 6
        };
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
        a.appendChild(r);
    })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
</script>
@endif

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2W5SBMTKG2"></script>
<script>
	gtag('js', new Date());
	gtag('config', 'G-2W5SBMTKG2');
</script>

