@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
<div class="register-success color-letter">

	<div class="container min-height">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
								 <h1 class="titlePage">{{ trans($theme.'-app.login_register.success_register')}}</h1>
	
			</div>
		</div>
	</div>

</div>

@stop
