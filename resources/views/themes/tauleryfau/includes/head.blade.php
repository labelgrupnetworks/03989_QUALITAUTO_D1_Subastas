<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=1, maximum-scale=3, user-scalable=yes">
<!--<meta name="viewport" content="width=device-width, minimum-scale=0.15, maximum-scale=1.6">-->
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">


<!-- color de explorador en mobile -->
<!-- Chrome, Firefox OS y Opera -->
<meta name="theme-color" content="#A37A4C" />
<!-- Windows Phone -->
<meta name="msapplication-navbutton-color" content="#A37A4C" />
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="A37A4C" />

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
@elseif( (!empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true) || !empty($data['subasta_info']->lote_actual) )
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
        <link rel="canonical" href="{{$data['seo']->canonical}}" />
    @if(!empty($data['subastas.paginator']) && $data['subastas.paginator']->currentPage() > 1 )
        <link rel="prev" href="{{$data['seo']->canonical."/page-".($data['subastas.paginator']->currentPage() - 1)}}" />
    @endif
    @if(!empty($data['subastas.paginator']) && !empty($data['subastas.paginator']->nextPageUrl()))
        <link rel="next" href="{{$data['seo']->canonical."/page-".($data['subastas.paginator']->nextPageUrl())}}" />
    @endif
@endif

<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css" >
<link rel="stylesheet" type="text/css" href="/css/owl.theme.default.min.css" >
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" >
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" >
<link rel="stylesheet" type="text/css" href="/css/hint.css" >
<link rel="stylesheet" type="text/css" href="/vendor/slick/slick.css" >
<link rel="stylesheet" type="text/css" href="/vendor/slick/slick-theme.css" >
<link rel="stylesheet" type="text/css" href="/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />

<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<!-- Common Javascript -->

@stack('stylesheets')

<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/grid.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/responsive.css') }}" rel="stylesheet" type="text/css" >
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,900|Lato:400,700,900" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


<link href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" rel="stylesheet">
<link href='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.css') }}' rel='stylesheet' />

<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
	async defer>
</script>
@php
	$curency_usd = new \App\libs\Currency;
	$curency_usd->currency(0,'USD',\Config::get('app.money'));
@endphp

<!-- Common Javascript -->
<script>
    var traduction_large = { {!!trans("$theme-app.global.month_large")!!} };
    var traductions = { {!!trans("$theme-app.global.month")!!} };
	var exchanges = {'{{$curency_usd->getCod()}}': {{$curency_usd->getExchange()}}};

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

<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/common.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/actions.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>
<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>

@stack('javascripts')

<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
<script src='{{ URL::asset('vendor/year-calendar/bootstrap-year-calendar.es.js') }}'></script>
<script src="{{ URL::asset('vendor/slick/slick.js') }}"></script>
<script src="{{ URL::asset('js/forms.js') }}"></script>
<script type="text/javascript" src="/vendor/bootstrap-multiselect//bootstrap-multiselect.js"></script>

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
