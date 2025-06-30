<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="LabelGrup">

<title>@yield('title', 'Admin')</title>

<link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/img/favicon.ico') }}" rel="shortcut icon" />
<meta name="robots" content="noindex">

<meta name="title" content="Backoffice Subastas">
<meta name="description" content="Backoffice Subastas - Administra tus subastas de forma eficiente y segura.">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" rel="stylesheet" />

<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>

<link href="{{ Tools::urlAssetsCache('/themes_admin/v2/stylesheets/app.css') }}" rel="stylesheet">
<link href="{{ Tools::urlAssetsCache('/themes_admin/v2/stylesheets/icon.min.css') }}" rel="stylesheet">

@stack('admin-css')
@stack('stylesheets')

<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-ui/1.13.1/jquery-ui.min.js') }}"></script>

<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ Tools::urlAssetsCache('js/forms.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script defer src="{{ Tools::urlAssetsCache('/themes_admin/v2/javascripts/app.js') }}"></script>
<script defer src="{{ Tools::urlAssetsCache('/themes_admin/v2/javascripts/custom.js') }}"></script>

@stack('admin-js')
@stack('script')
@stack('scripts')
