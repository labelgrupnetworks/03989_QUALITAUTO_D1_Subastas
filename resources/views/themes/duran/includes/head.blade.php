<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans($theme.'-app.head.meta_author') }}">

<title>

    @if( !empty($data['seo']->meta_title) )
        {{$data['seo']->meta_title}}
	@elseif(!empty($seo->meta_title))
		{{ $seo->meta_title }}
    @else
        {{ trans($theme.'-app.head.title_app') }}
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
<meta name="title" content="<?= $data['seo']->meta_title ?>">
@elseif(!empty($seo->meta_title))
	<meta name="title" content="{{ $seo->meta_title }}">
@else
<meta name="title" content="{{ trans($theme.'-app.head.title_app') }}">
@endif

@if(!empty($data['seo']->meta_description))
    <meta name="description" content="<?= $data['seo']->meta_description ?>">
@elseif(!empty($seo->meta_description))
	<meta name="description" content="{{ $seo->meta_description }}">
@else
    <meta name="description" content="{{ trans($theme.'-app.head.meta_description') }}">
@endif
@if(!empty($seo->meta_keywords))
	<meta name="keywords" content="{{ $seo->meta_keywords }}">
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

@if(!empty($data['seo']->canonical) || !empty($seo->canonical))
  	<link rel="canonical" href="{{ $data['seo']->canonical ?? $seo->canonical }}" />
@else
	<link rel="canonical" href="{{str_replace("http:","https:",Request::url())}}" />
@endif

<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >
<link rel="stylesheet" href="{{ Tools::urlAssetsCache('vendor/font-awesome/5.4.2/css/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/owl.carousel.min.css') }}" >
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/owl.theme.default.min.css') }}" >
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" >
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" >
<link rel="stylesheet" type="text/css" href="/css/hint.css" >
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<link rel='stylesheet' href='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.css') }}' />
<!-- Slick / banner -->
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick-theme.css') }}" />
<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="/css/animate.min.css">
<!-- Common Javascript -->
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/banners.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/responsive.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ Tools::urlAssetsCache('/css/default/labelframework.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" rel="stylesheet">

@stack('styles')

<!-- Common Javascript -->
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
<script src='{{ URL::asset('vendor/year-calendar/jquery.bootstrap.year.calendar.js') }}'></script>
<script src='{{ URL::asset('vendor/year-calendar/bootstrap-year-calendar.es.js') }}'></script>

<script src="{{ Tools::urlAssetsCache('/js/default/customized_tr_main.js') }}" ></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>

<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>

<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>

<script src="{{ Tools::urlAssetsCache('/js/default/shopping_cart.js') }}" ></script>


<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ URL::asset('js/forms.js') }}"></script>
<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

<script src="https://www.google.com/recaptcha/api.js?render={{config('app.captcha_v3_public')}}"></script>

<script>
	//si pasan la variable view_login = true se mostrara el login
   var view_login = @json(Request::input('view_login', false));
   var resolution_web = $( window ).width();

   if(view_login == true){

	   if(resolution_web >= 1200) {
		   view_login= true;
	   }else{
		   view_login = false;
	   }
   }
</script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons|Teko:300,600"
      rel="stylesheet">

