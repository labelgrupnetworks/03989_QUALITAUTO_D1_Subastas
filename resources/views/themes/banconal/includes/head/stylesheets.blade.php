<!-- Global Packages -->
{!! Tools::preloadStylesheets("vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css", true) !!}
{!! Tools::preloadStylesheets("/css/owl.carousel.min.css", false) !!}

<link rel="stylesheet" href="/css/owl.theme.default.min.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="/css/owl.theme.default.min.css"></noscript>
<link rel="stylesheet" href="{{ URL::asset('vendor/jquery-ui/1.13.3/jquery-ui.min.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ URL::asset('vendor/jquery-ui/1.13.3/jquery-ui.min.css') }}"></noscript>

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

@stack('styles')

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
