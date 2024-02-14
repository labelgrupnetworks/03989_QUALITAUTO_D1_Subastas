@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<section class="permanentAuctions">
	<div class="container">
		<div class="alert alert-success">
			<?= trans($theme.'-app.login_register.pass_sent') ?>
		</div>
	</div>
</section>

@stop