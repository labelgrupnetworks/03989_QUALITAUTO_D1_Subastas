@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
	$title = $data['name'];
    ?>
    <section class="all-aution-title title-content pb-1">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 h1-titl text-center">

					{{-- Historica --}}
					@if ($data['subc_sub'] == 'H')
					<h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.subastas.historic_title') }}</h1>

					{{-- Venta directa --}}
					@elseif($data['type'] == 'V')
					<h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale') }}</h1>

					{{-- Online --}}
					@else
					<h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>

					@endif

                </div>
            </div>
        </div>
    </section>
    <section class="hide">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                        @include('includes.breadcrumb')
                </div>
            </div>
        </div>
    </section>
    @include('content.subastas')
@stop
