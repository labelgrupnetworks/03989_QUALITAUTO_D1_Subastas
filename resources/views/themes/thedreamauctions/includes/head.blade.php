<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">

@yield('seo')

<title>{{ $data['seo']->meta_title ?? $seo->meta_title ?? trans("$theme-app.head.title_app") }}</title>

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
@endif

<meta name="title" content="{{ $data['seo']->meta_title ?? $seo->meta_title ?? trans("$theme-app.head.title_app") }}">
<meta name="description" content="{{ $data['seo']->meta_description ?? $seo->meta_description ?? trans("$theme-app.head.meta_description") }}">

@if(!empty($data['seo']->canonical) || !empty($seo->canonical))
  	<link rel="canonical" href="{{ $data['seo']->canonical ?? $seo->canonical }}" />
@endif

<!-- Packages -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
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
<link rel="stylesheet" href="/css/animate.min.css">

<!-- Overwritable files -->
<link href="{{ Tools::urlAssetsCache('/css/default/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/css/default/grid.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/grid.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/css/default/banners.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/banners.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/css/default/responsive.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/responsive.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ Tools::urlAssetsCache('/css/default/labelframework.css') }}" rel="stylesheet" type="text/css">
<link href='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.css') }}' rel='stylesheet' />

<!--Google -->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
<script src="{{ Tools::urlAssetsCache('/js/actions.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('js/jquery.cookie.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
<script src='{{ URL::asset('vendor/year-calendar/bootstrap-year-calendar.es.js') }}'></script>
<script src="{{ Tools::urlAssetsCache('/js/default/customized_tr_main.js') }}" ></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>
<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/js/default/custom.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

@stack('scripts')


<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
</script>


<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
