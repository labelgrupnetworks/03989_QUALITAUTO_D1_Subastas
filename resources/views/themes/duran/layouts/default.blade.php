<!doctype html>
<html>

<head>
	@include('includes.open_graph_head')
	@include('includes.google_head')
	@include('includes.head')
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
		<i class="fas fa-arrow-up"></i>

	</div>
	<?php //Tools::querylog(); ?>

	<script>
		window.setTimeout(function(){
			newsletterDay();
		}, 3000);
	</script>
</body>

</html>
