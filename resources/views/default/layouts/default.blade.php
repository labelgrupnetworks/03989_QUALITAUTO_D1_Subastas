<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

@php
$cookiesState = \Tools::cookies();
@endphp

<head>

    @include('includes.google_head')
	@include('includes.head')
		@yield('assets_components')
</head>

<body>

    <?php \Tools::personalJsCss();?>

    @if(!env('APP_DEBUG') && !empty(\Config::get('app.login_acces_web')) && !Session::has('user') )
        @include('includes.loggin_web')
    @else
        @include('includes.google_body')
        @include('includes.header')
            @yield('content')
        @include('includes.newsletter')
        @include('includes.footer')
        @include('includes.modals')
    @endif

<div class="button-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>

</div>

 <?php #Tools::querylog(); ?>
 @if(request("openLogin")=="S" && !Session::has('user') )
	<script>
		openLogin();
	</script>

 @endif
</body>

</html>
