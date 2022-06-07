@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<?php $name = (\Session::get('user.name')); ?>
<div class="register-success color-letter">

	<div class="container min-height">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.login_register.success_register') }}</h1>
			</div>
			<div class="col-xs-12 d-flex flex-wrap button-success-register" style="margin-top: 20px">
				<a  href="/" class="d-flex align-items-center  button-principal col-xs-3">{{ trans(\Config::get('app.theme').'-app.home.home') }}</a>
				<a class="d-flex align-items-center btn_login button-principal" style="width: auto" href="javascript:;">{{ trans(\Config::get('app.theme').'-app.login_register.generic_name') }}</a>

			</div>
		</div>
	</div>

</div>

@stop
