@php
	use \App\libs\TradLib;
	use App\Services\Content\CookieService;
    $cookiesPreferences = new CookieService();

	Config::set('app.cookies.analysis', [CookieService::THIRD_GOOGLE]);
    Config::set('app.cookies.advertising', [CookieService::THIRD_LINKEDIN, CookieService::THIRD_SMARTLOOK]);
@endphp

<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">
<title>
    @if( !empty($data['seo']->meta_title) )
        {{$data['seo']->meta_title}}
    @else
        {{ trans(\Config::get('app.theme').'-app.head.title_app') }}
    @endif
</title>
<script>
 //fecha servidor
var fecha_server = {{ getdate()[0] * 1000 }};
//fecha del sistema del usuario
var fecha_js = new Date().getTime();
</script>
<link rel="shortcut icon" href="<?='/themes/'.$theme.'/img/favicon.ico'?>" />
@if( env('APP_DEBUG'))
    <meta name="robots" content="noindex">
@elseif( !empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true )
    <meta name="robots" content="noindex,follow">
@endif
@if(!empty($data['seo']->meta_title))
<meta name="title" content="<?= $data['seo']->meta_title ?>">
@else
<meta name="title" content="{{ trans(\Config::get('app.theme').'-app.head.title_app') }}">
@endif
@if(!empty($data['seo']->meta_description))
    <meta name="description" content="<?= strip_tags(str_replace('"', "'", $data['seo']->meta_description)) ?>">
@else
    <meta name="description" content="{{ trans(\Config::get('app.theme').'-app.head.meta_description') }}">
@endif

@php
	$var_http = isset($_SERVER['HTTPS']) ? "https://" : "http://";
@endphp
@if(!empty($data['seo']->canonical))
    <link rel="canonical" href="{{$var_http.$data['seo']->canonical}}" />
@elseif(!empty($seo->canonical))
    <link rel="canonical" href="{{$var_http.$seo->canonical}}" />
@else
    <link rel="canonical" href="{{str_replace("http:","https:",Request::url())}}" />
@endif

@foreach(Config::get('app.locales') as $key => $value)

	@php
		$ruta = "/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
		if($key == 'es'){
			$defaultRoute = $ruta;
		}
	@endphp

	<link rel="alternate" href="{{$host . $ruta}}" hreflang="{{ $key }}" />

@endforeach
<link rel="alternate" href="{{$host . $defaultRoute}}" hreflang="x-default" />


<!--Http-Equiv-->
<meta http-equiv="title" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.title_app") }}" />
<meta http-equiv="description" content="{{ $data['seo']->meta_description ?? trans("$theme-app.head.meta_description") }}" />
<meta http-equiv="content-language" content="{{ config('app.locale') }}"/>

<!--Dublin Core-->
<meta name="DC.Title" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.title_app") }}" />
<meta name="DC.Description" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.meta_description") }}" />
<meta name="DC.Language" content="{{ config('app.locale') }}" />

<!--Open Graph-->
<meta property="og:locale" content="{{ config('app.locale') }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Gutinvest" />
<meta property="og:title" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.title_app") }}" />
<meta property="og:description" content="{{ $data['seo']->meta_description ?? trans("$theme-app.head.meta_description") }}" />
<meta property="og:url" content="{{str_replace("http:","https:",Request::url())}}" />
<meta property="og:image" content="https://www.gutinvest.es/themes/gutinvest/assets/img/logo.jpg " />

<!--Twitter-->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $data['seo']->meta_title ?? trans("$theme-app.head.title_app") }}" />
<meta name="twitter:description" content="{{ $data['seo']->meta_description ?? trans("$theme-app.head.meta_description") }}" />
<meta name="twitter:image:src" content="https://www.gutinvest.es/themes/gutinvest/assets/img/logo.jpg" />
<meta name="twitter:site" content="@gutinvest" />

@if($cookiesPreferences->isAnalysisAllowed())
<!-- Google Analytics -->
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');



ga('create', '{{ \Config::get('app.google_analytics') }}', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SEM089379G"></script>
<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-SEM089379G'); </script>

<!-- gtag -->
@if(!empty($data["gtag_pageview"]) )
<script> gtag('event', 'page_view', { 'send_to': 'G-SEM089379G', 'user_id': '{{$data["gtag_pageview"]}}'}); </script>
@endif
<!-- gtag -->


@else
<script>
	ga = () => {};
</script>
@endif

@if($cookiesPreferences->isAdvertisingAllowed())
<!-- linkedin_partner -->
<script type="text/javascript">
	_linkedin_partner_id = "600457"; window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || []; window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script>
<script type="text/javascript">
	(function(){var s = document.getElementsByTagName("script")[0]; var b = document.createElement("script"); b.type = "text/javascript";b.async = true; b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js"; s.parentNode.insertBefore(b, s);})();
</script> <noscript> <img height="1" width="1" style="display:none;" alt=""
		src="https://dc.ads.linkedin.com/collect/?pid=600457&fmt=gif" /> </noscript>
<!-- linkedin_partner / -->

<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
	var _smartsupp = _smartsupp || {};
	_smartsupp.key = 'b241777391bc61b0a5d051b1f05eeb9676913477';
	window.smartsupp||(function(d) {
	  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
	  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
	  c.type='text/javascript';c.charset='utf-8';c.async=true;
	  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
	})(document);
</script>
	<!-- Smartsupp Live Chat script -->
@endif
