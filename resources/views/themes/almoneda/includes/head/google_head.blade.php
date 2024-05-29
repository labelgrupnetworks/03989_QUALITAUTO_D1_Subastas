@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE]);
@endphp

@if ($cookiesPreferences->isAnalysisAllowed())
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EDCLLSB2MK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-EDCLLSB2MK');
    </script>
@else
    <script>
		gtag = () => {};
    </script>
@endif
