<!doctype html>
<html lang=”<?= \Config::get('app.language_complete')[\Config::get('app.locale')]; ?>“>

<?php $cookiesState = \Tools::cookies();?>

<head>
	@include('includes.google_head')
	@include('includes.head')
</head>

<body>
	<?php \Tools::personalJsCss();?>

	@include('includes.google_body')
	@include('includes.header')

	@yield('content')

	@include('includes.newsletter')

	@include('includes.footer')

	@include('includes.modals')

	<div class="button-up">
		<i class="fa fa-chevron-up" aria-hidden="true"></i>

	</div>
	<?php Tools::querylog();  ?>
	@if(request("openLogin")=="S" && !Session::has('user') )
		<script>
			$(document).ready(function () {
				$('#modalLogin').modal();
			});
		</script>

	@endif
</body>

</html>
