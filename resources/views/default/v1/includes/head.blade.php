<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
//header("Cache-Control: max-age=31536000");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">

@yield('seo')

<title>
@if(!empty($data['seo']->meta_title))
{{ $data['seo']->meta_title }}
@elseif (!empty($seo->meta_title))
{{ $seo->meta_title }}
@else
{{ trans("$theme-app.head.title_app") }}
@endif
</title>

<script>
 	//fecha servidor
	var fecha_server = @json(getdate()[0] * 1000);
	//fecha del sistema del usuario
	var fecha_js = new Date().getTime();
</script>

<link rel="shortcut icon" href="<?='/themes/'.$theme.'/img/favicon.ico'?>" />
@if( env('APP_DEBUG'))
    <meta name="robots" content="noindex">
@elseif( !empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true )
    <meta name="robots" content="noindex,follow">
@elseif(!empty($seo->noindex_follow) && $seo->noindex_follow==true)
	<meta name="robots" content="noindex,follow">
@endif

@if(!empty($data['seo']->meta_title))
<meta name="title" content="{{ $data['seo']->meta_title }}">
@elseif (!empty($seo->meta_title))
<meta name="title" content="{{ $seo->meta_title }}">
@else
<meta name="title" content="{{ trans("$theme-app.head.title_app") }}">
@endif

@if(!empty($data['seo']->meta_description))
<meta name="description" content="{{ $data['seo']->meta_description }}">
@elseif (!empty($seo->meta_description))
<meta name="description" content="{{ $seo->meta_description }}">
@else
<meta name="description" content="{{ trans("$theme-app.head.meta_description") }}">
@endif

@if(!empty($data['seo']->canonical))
<link rel="canonical" href="{{ $data['seo']->canonical }}" />
@elseif(!empty($seo->canonical))
<link rel="canonical" href="{{ $seo->canonical }}" />
@endif


<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Global Packages -->

{!! Tools::preloadStylesheets("vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css", true) !!}
{!! Tools::preloadStylesheets("/css/owl.carousel.min.css", false) !!}


<link rel="stylesheet" href="/css/owl.theme.default.min.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="/css/owl.theme.default.min.css"></noscript>
<link rel="stylesheet" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}"></noscript>

<link rel="stylesheet" href="/css/jquery.jqzoom.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" ></noscript>

<link rel="stylesheet" href="/css/hint.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" type="text/css" href="/css/hint.css" ></noscript>

<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" /></noscript>

<!-- Slick / banner -->
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick.css') }}" /></noscript>

<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick-theme.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick-theme.css') }}" /></noscript>

<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" /></noscript>

<link rel="stylesheet" href="/css/animate.min.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="/css/animate.min.css"></noscript>

<!-- Overwritable files -->
<link href="{{ Tools::urlAssetsCache('/css/default/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/grid.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/css/default/banners.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/banners.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/css/default/responsive.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/responsive.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'">
<link href="{{ Tools::urlAssetsCache('/css/default/labelframework.css') }}" rel="stylesheet" type="text/css">
<link href='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.css') }}' rel='stylesheet' media="print" onload="this.media='all'">

{{-- Icons --}}
{!! Tools::preloadStylesheets("vendor/font-awesome/5.4.2/css/all.css", false) !!}
{!! Tools::preloadStylesheets("vendor/font-awesome/4.7.0/css/font-awesome.min.css", true) !!}

<!--Google -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<script>
    var traduction_large = { {!!trans("$theme-app.global.month_large")!!} };
    var traductions = { {!!trans("$theme-app.global.month")!!} };

	var routing = {
		subastashome: 	"{{ \Routing::slug('subastashome') }}",
		login: 			"{{ \Routing::slug('login') }}",
		registro: 		"{{ \Routing::slug('registro') }}",
		subasta: 		"{{ \Routing::slug('subasta') }}",
        usuario_registrado:        "{{ \Routing::slug('usuario-registrado') }}",
	};

	var messages = {
		'error': @json(trans("$theme-app.msg_error")),
		'success': @json(trans("$theme-app.msg_success")),
		'neutral': @json(trans("$theme-app.msg_neutral"))
	};
</script>

<script src="{{ Tools::urlAssetsCache("/js/lang/". \Config::get('app.locale') . "/$theme-app.js") }}"></script>
<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/common.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/actions.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script defer src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script defer src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script defer src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script defer src="{{ URL::asset('js/validator.js') }}"></script>
<script defer src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script defer src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script defer src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
<script defer src='{{ URL::asset('vendor/year-calendar/bootstrap-year-calendar.es.js') }}'></script>
<script defer src="{{ Tools::urlAssetsCache('/js/default/customized_tr_main.js') }}" ></script>
<script defer src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>
<script defer src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script defer src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script defer src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>
<script defer type="text/javascript" src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/default/custom.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/js/default/shopping_cart.js') }}" ></script>
<script defer src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script defer src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script defer src="{{ URL::asset('js/numeral.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

@stack('scripts')


<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

