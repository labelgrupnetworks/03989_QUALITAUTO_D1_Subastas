@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')

@if($data["auction"]->tipo_sub == 'E')
	@include('content.ficha_subastaPrivada')

@else
    <?php

        $bread = array();
        $bread[] = array("url" =>$data["url_bread"], "name" =>$data["name_bread"] );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>

	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')
			<div class="container">
				<h1 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>
			</div>
		</div>
	</div>

	@include('content.ficha_subasta')
@endif


@stop
