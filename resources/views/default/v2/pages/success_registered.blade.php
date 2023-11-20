@extends('layouts.default')

@section('title')
{{ trans("$theme-app.head.title_app") }}
@stop


@section('content')
<main>
	<section class="py-sm-5 my-5 success_registered">
		<div class="container py-sm-5 text-lg-center">
			<h1 class="fs-64 mb-3">{{ trans("$theme-app.login_register.success_register") }}</h1>

			@if(config('app.coregistroSubalia', false))
			<h3>
				{{ trans("$theme-app.login_register.register_subalia") }} <a href="/{{ config('app.locale') }}/login/subalia">{{ trans("$theme-app.login_register.here") }}</a>
			</h3>
			@endif
		</div>
	</section>
</main>
@stop
