<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<!--<meta name="viewport" content="width=device-width, minimum-scale=0.15, maximum-scale=1.6">-->
<meta name="author" content="{{ trans($theme.'-app.head.meta_author') }}">
<meta name="p:domain_verify" content="4e38f27a47f58c387a339c6cdbd03c9d"/>

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
@if( env('APP_DEBUG') || (!empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true) )
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

<meta name="csrf-token" content="{{ csrf_token() }}">

@if(!empty($data['seo']->canonical))
<link rel="canonical" href="{{ $data['seo']->canonical }}" />
@elseif(!empty($seo->canonical))
<link rel="canonical" href="{{ $seo->canonical }}" />
@endif

<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >

{!! Tools::preloadStylesheets("vendor/font-awesome/4.7.0/css/font-awesome.min.css", true) !!}

<link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css" >
<link rel="stylesheet" type="text/css" href="/css/owl.theme.default.min.css" >
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" >
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" >
<link rel="stylesheet" type="text/css" href="/css/hint.css" >
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<!-- Slick / banner -->
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick-theme.css') }}" />
<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="{{ Tools::urlAssetsCache('vendor/animate/3.5.2/animate.min.css') }}">

<!-- Common Javascript -->
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/responsive.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ Tools::urlAssetsCache('/css/default/labelframework.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

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
<script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>
<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ URL::asset('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

