@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.galery.exhibitions') }}
@stop


@section('content')
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">

			<div class="col-xs-12 galTitle">
				<h1 class="titlePage-custom color-letter text-center">{{ trans(\Config::get('app.theme').'-app.galery.exhibitions') }}</h1>


			</div>
			<div class=" col-xs-12 searchExhibitions">
				<form id="fromSearchExhibitions" >
					<input  type="text" id="searchExhibitions_JS"  name="search" value="{{request("search")}}" size="30" maxlength="128">
					<input  type="hidden"  name="online" value="{{request("online")}}" >
					<button type="submit">	<i class="fa fa-search" aria-hidden="true"></i></button>
				</form>

			</div>

	</div>
</div>

    @include('content.galery.exhibitions')
	<script>
		$("#searchExhibitions_JS").keydown(function(e){
			if (e.keyCode == 13) {
				$("#fromSearchExhibitions").submit();
			}
		})


	</script>
@stop
