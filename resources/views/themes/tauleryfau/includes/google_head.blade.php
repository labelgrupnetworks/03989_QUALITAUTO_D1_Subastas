@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE, Cookies::THIRD_FACEBOOK]);
@endphp

@if ($cookiesPreferences->isAnalysisAllowed())
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112197559-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-112197559-1');
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NGJRQZX');
    </script>
    <!-- End Google Tag Manager -->
@else
    <script>
        ga = () => {};
    </script>
@endif
