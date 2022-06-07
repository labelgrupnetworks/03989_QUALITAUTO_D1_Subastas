@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.artist.artists') }}
@stop

@section('content')
<?php
$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.artist.artists')  );
?>


@php



@endphp

<div class="container">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')
			<div class="container">
				<h1 class="titlePage">{{trans(\Config::get('app.theme').'-app.artist.artists')}}</h1>
				<div class="col-xs-12 col-sm-12   px-xs-0 filter-col mb-3 mt-3">
					<form id="artistForm_JS" method="get" action="artistas"  >
						<div class=" col-xs-12 col-md-11 ">
							<input name="description" id="description_filter_grid" class="form-control input-sm search-input search-input_js" type="text" placeholder="{{trans(\Config::get('app.theme').'-app.artist.searchText')}}" value="{{request("description")}}">
							<button   type="submit" class="button-principal button-search search-btn_js"><i class="fas fa-search"></i></button>
						</div>
						<div class=" col-xs-12 col-md-1 ">
							<div class="text-left ml-2" style="font-size: 18px;">
								<input id="orderAZ" type="radio" value="asc" name="order"  @if(request("order") != "desc")  checked @endif>  <label for="orderAZ" >A-Z</label><br/> <input id="orderZA" type="radio" value="desc" name="order" class="ml-1" @if(request("order") != "asc")  checked @endif> <label for="orderZA">Z-A</label>
							</div>
						</div>
					</form>
				</div>


				<div class="col-xs-12 p-0">

					@foreach($artists as $artist)
						@php
							$img_path="/img/autores/".$artist->id_artist.".jpg";
						@endphp
						@include("includes.artists.artist")

					@endforeach
				</div>
				<div class="col-xs-12 d-flex justify-content-center">
					{{ $artists->links() }}
				</div>


				</div>
			</div>
		</div>
	</div>
</div>


<script>
$("input[name=order]").on("click", function(){
	artistForm_JS.submit();
})

</script>

@stop

