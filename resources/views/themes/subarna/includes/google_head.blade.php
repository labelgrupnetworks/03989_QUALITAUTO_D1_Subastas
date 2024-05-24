@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE, Cookies::THIRD_HOTJAR, Cookies::THIRD_LIVEAGENT]);
@endphp

<script async src="https://www.googletagmanager.com/gtag/js?id=AW-463062636"></script>

@if($cookiesPreferences->isAnalysisAllowed())
<!-- Global site tag (gtag.js) - Google Analytics -->
{{-- <script async src="https://www.googletagmanager.com/gtag/js?id=AW-463062636"></script> --}}
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'AW-463062636');
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
@else
<script>
	ga = () => {};
	gtag = () => {};
</script>
@endif
