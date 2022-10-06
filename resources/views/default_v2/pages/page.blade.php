@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
@php
	$bread[] = array("name" =>$data['data']->name_web_page);
@endphp

<div class="container">
	@include('includes.breadcrumb')
	<h1>{{ $data['data']->name_web_page }}</h1>
</div>

<div class="container">
	<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web static-page">
		{!! $data['data']->content_web_page !!}
	</div>
</div>

<script>
	$('#button-map').click( function () {

		if($(this).hasClass('active')){
			$('.maps-house-auction').animate({left: '100%'}, 300)
			$(this)
				.removeClass('active')
				.find('i').addClass('fa-map-marker-alt').removeClass('fa-times')
			}else{
				$('.maps-house-auction').animate({left: 0}, 0)
				$(this)
					.addClass('active')
					.find('i').removeClass('fa-map-marker-alt').addClass('fa-times')
		}

	})


	 $(".input-effect").val("");

		$(".input-effect input").focusout(function(){
			if($(this).val() != ""){
				$(this).addClass("has-content");
			}else{
				$(this).removeClass("has-content");
			}
		})
		$(".input-effect textarea").focusout(function(){
			if($(this).val() != ""){
				$(this).addClass("has-content");
			}else{
				$(this).removeClass("has-content");
			}
		})
</script>
@stop

