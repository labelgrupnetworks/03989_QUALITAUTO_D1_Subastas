
@include('includes.head.open_graph_head')

<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="{{ trans($theme.'-app.head.meta_author') }}">

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

	const defaultTheme = 'default_v2';
	const theme = {{ Js::from(config('app.theme')) }};
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

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.gstatic.com" />
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..700&display=swap" rel="stylesheet">


<!-- Global Packages -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}" >

<link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css">
<link rel="stylesheet" type="text/css" href="/css/owl.theme.default.min.css" >
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" >

<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />

<!-- Slick / banner -->
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('vendor/slick/slick-theme.css') }}" />

<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />

<!-- Overwritable files -->
<link href="{{ Tools::urlAssetsCache(public_default_path('css/style.css')) }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/css/style.css') }}" rel="stylesheet" type="text/css">

<link href="{{ Tools::urlAssetsCache(public_default_path('css/banners.css')) }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/banners.css') }}" rel="stylesheet" type="text/css">

@stack('stylesheets')

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

{{--solo se utiliza en ficha de subasta tipo W ¿mover? --}}
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>

<script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ Tools::urlAssetsCache(public_default_path('js/customized_tr_main.js')) }}" ></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/customized_tr_main.js') }}" ></script>

<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
{{-- <script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script> --}}

<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>

<script src="{{ Tools::urlAssetsCache(public_default_path('js/custom.js')) }}"></script>
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom.js') }}"></script>
<script src="{{ Tools::urlAssetsCache(public_default_path('js/shopping_cart.js')) }}" ></script>

<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
{{-- <script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script> --}}

{{-- Se utiliza en customized_tr_main.js --}}
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('js/forms.js') }}"></script>

<script src="{{ URL::asset('vendor/slick/slick.min.js') }}"></script>

@stack('scripts')

<style>
	@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
