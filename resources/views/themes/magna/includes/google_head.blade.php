@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE]);
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

<script async src="https://www.googletagmanager.com/gtag/js?id=GTM-PB5DJJJF"></script>
<script>
    gtag('js', new Date());
    gtag('config', 'GTM-PB5DJJJF');
</script>
