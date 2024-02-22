@yield('http_error')

@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<div class="container-fluid container-error">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="error-wrapper p-1">
					<h1 class="mb-2">
						@yield('error_code')
					</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container mt-5 mb-5">

	<div class="row">
		<div class="col-xs-12">
			<div class="container-404-wrapper p-3">
				@yield('error_message')
			</div>
		</div>
	</div>

</div>

@stop
