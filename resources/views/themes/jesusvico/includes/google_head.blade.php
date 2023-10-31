@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();

    Config::set('app.cookies.analysis', [Cookies::THIRD_GOOGLE]);
@endphp

@if ($cookiesPreferences->isAnalysisAllowed())
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G2F5CY1R1J"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    window.onload = function() {
        for (i = 0; i < document.getElementsByTagName("img").length; i++) {
            if (document.getElementsByTagName("img")[i].getAttribute("alt") == "" || document.getElementsByTagName(
                    "img")[i].getAttribute("alt") == null) {
                var srcim = document.getElementsByTagName("img")[i].getAttribute("src");
                if (srcim != null) {
                    srcim = srcim.split("/");
                    srcim = srcim[srcim.length - 1].replace(/-|.jpg/g, " ");
                    document.getElementsByTagName("img")[i].setAttribute("alt", srcim);
                }

            }
        }
    }

    gtag('js', new Date());
    gtag('config', 'G-G2F5CY1R1J');
</script>
<!-- End Google tag (gtag.js) -->
@else
<script>
	ga = () => {}
</script>
@endif
