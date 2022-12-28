<meta charset="utf-8">
<meta name="description" content="LabelAuction">
<meta name="author" content="LabelGrup">
<meta name="robots" content="noindex">
<title>@yield('title', 'Admin')</title>

<meta name="keywords" content="labegrup" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ $base_url }}/vendor/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/font-awesome/css/font-awesome.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/magnific-popup/magnific-popup.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/bootstrap-datepicker/css/datepicker3.css" />
<link rel="stylesheet" href="{{ URL::asset('vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/morris/morris.css" />
{{-- <link rel="stylesheet" href="{{ $base_url }}/vendor/owl-carousel/owl.carousel.css" /> --}}
{{-- <link rel="stylesheet" href="{{ $base_url }}/vendor/owl-carousel/owl.theme.css" /> --}}
<link rel="stylesheet" href="{{ $base_url }}/vendor/dropzone/css/basic.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/dropzone/css/dropzone.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/summernote/summernote.css" />
{{-- <link rel="stylesheet" href="{{ $base_url }}/vendor/summernote/summernote-bs3.css" />--}}
<link rel="stylesheet" href="{{ $base_url }}/vendor/hover-css/hover.css" />
<link rel="stylesheet" href="{{ $base_url }}/vendor/pnotify/pnotify.custom.css" />
<!--<link rel="stylesheet" href="{{ $base_url }}/vendor/datatables/datatables.min.css" />-->
<link rel="stylesheet" href="{{ Tools::urlAssetsCache("/themes_admin/porto/assets/stylesheets/theme.css") }}" />
<link rel="stylesheet" href="{{ $base_url }}/stylesheets/skins/default.css" />
<link rel="stylesheet" href="{{ Tools::urlAssetsCache("/themes_admin/porto/assets/stylesheets/theme-custom.css") }}" />
<link rel="stylesheet" type="text/css" href="/vendor/slick/slick.css" >
<link rel="stylesheet" type="text/css" href="/vendor/slick/slick-theme.css" >
<link rel="stylesheet" type="text/css" href="/vendor/datatables/datatables.css">
<link rel="stylesheet" type="text/css" href="/css/default/labelframework.css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}"/>

@stack('admin-css')

<script src="{{ $base_url }}/vendor/jquery/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script defer src="{{ $base_url }}/vendor/tinymce/tinymce.min.js"></script>
