@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
                             <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.login_register.success_register')}}</h1>

		</div>
	</div>
</div>

@stop
