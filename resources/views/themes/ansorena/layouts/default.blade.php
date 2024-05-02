<!doctype html>
<html lang="{{ config('app.language_complete')[config('app.locale')] }}">

<head>
	@include('includes.open_graph_head')
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
        @include('includes.footer')
        @include('includes.modals')
    @endif



@php
//Tools::querylog();
@endphp

<script>
	window.setTimeout(function(){
		newsletterDay();
	}, 3000);
</script>

@if(request("openLogin") == "S" && !Session::has('user') )
	<script>
		openLogin();
	</script>
 @endif
</body>

</html>
