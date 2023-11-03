@php
    use App\Models\Cookies;
    $cookiesPreferences = new Cookies();
@endphp

@if ($cookiesPreferences->isAnalysisAllowed())
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NGJRQZX" style="display:none;visibility:hidden"
            height="0" width="0"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
