@php
    use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();

    Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);
@endphp

@if ($cookiesPreferences->isAnalysisAllowed() && !empty(\Config::get('app.google_addwords')))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-140581104-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-140581104-1');
        gtag('config', '{{ \Config::get('app.google_addwords') }}');
    </script>
@else
    <script>
        ga = () => {};
    </script>
@endif
