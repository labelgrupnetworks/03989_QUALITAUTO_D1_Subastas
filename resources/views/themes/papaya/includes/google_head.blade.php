@php
    use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();
    Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);
@endphp

<script>
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
</script>

@if (!$cookiesPreferences->isAnalysisAllowed())
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
@endif

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XCGX0VKDGC"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16772819397"></script>
<script>
    gtag('js', new Date());
    gtag('config', 'G-XCGX0VKDGC');
    gtag('config', 'AW-16772819397');

    gtag('config', 'AW-16772819397/tsCbCIaG4ucZEMXT870-', {
        'phone_conversion_number': '(+34) 91 571 13 77'
    });
</script>
