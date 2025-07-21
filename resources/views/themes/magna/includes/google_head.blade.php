@php
    use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();

    Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);
    Config::set('app.cookies.advertising', [CookieService::THIRD_FACEBOOK]);
@endphp

<meta name="facebook-domain-verification" content="pyebz5j8ttto2q5xfr0wwbglo34q9l" />

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


@if ($cookiesPreferences->isAdvertisingAllowed())
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '3759094107674977');
        fbq('track', 'PageView');
    </script>
    <noscript><img src="https://www.facebook.com/tr?id=3759094107674977&ev=PageView&noscript=1" style="display:none"
            height="1" width="1" /></noscript>
    <!-- End Meta Pixel Code -->
@endif
