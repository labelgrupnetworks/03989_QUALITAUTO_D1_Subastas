<?php
header("HTTP/1.0 404 Not Found");
?>
@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<style>

	.container-error{
		background: var(--secondary-color);
		color: #fff;
	}

	.error-wrapper h1{
		font-family: opensanscondbold,Arial,sans-serif;
		font-weight: 600;
		line-height: 1.3;
		display: table-cell;
		vertical-align: middle;
	}

	.container-404-wrapper{
		background-color: #DCE9E6;
		border: 1px solid #CCC;
	}

	.container-404-wrapper h2{
		margin-top: 3px;
    	color: var(--secondary-color);
    	letter-spacing: 2.4px;
	}

	.container-404-wrapper p{
		color: #666666;
    	letter-spacing: 2.7px;
    	line-height: 17px;
	}

</style>

<div class="container-fluid container-error">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="error-wrapper p-1">
					<h1 class="mb-2">Error 404 - Página no encontrada</h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container mt-5 mb-5">

	<div class="row">
		<div class="col-xs-12">
			<div class="container-404-wrapper p-3">
				<h2>Página no encontrada.</h2>
				<p>No se ha encontrado la página a la que intentas acceder. Es posible que el contenido ya no esté disponible o que la dirección no exista.</p>
			</div>
		</div>
	</div>

</div>

@stop
