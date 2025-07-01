@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@php
	$bread[] = array("name" => $data['name'] );
@endphp

@section('content')

<section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

				<h1 class="titlePage">
				@if ($data['type'] == 'W')
					 {{ trans(\Config::get('app.theme').'-app.foot.presenciales')}}
				@elseif ($data['type'] == 'V')
					 {{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}

				@elseif($data['subc_sub'] == 'H')
					{{ trans(\Config::get('app.theme').'-app.foot.historico')}}
				@elseif ($data['type'] == null)
					{{ trans(\Config::get('app.theme').'-app.foot.online_sales')}}
				@else
					{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}
				@endif
				</h1>
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>

    @include('content.subastas')
@stop
