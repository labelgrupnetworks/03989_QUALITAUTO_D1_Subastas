@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
    ?>
<section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
				@if ($data['type'] == 'W')
					<h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.foot.online_sales')}}</h1>
				@elseif ($data['type'] == null)
					<h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.foot.online_sales')}}</h1>
				@else
					<h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
				@endif
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>


    @include('content.subastas')
@stop
