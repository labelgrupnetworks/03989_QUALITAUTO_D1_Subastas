@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" => $data['data']->name_web_page  );
?>

<div class="page-static-banner">
	{!! BannerLib::bannersPorKey(mb_strtolower($data['data']->key_web_page) . '-bansup', 'banner-page', ['dots' => false, 'arrows' => false, 'autoplay' => true]) !!}
</div>




<div class="container static-page-container">
	<div class="row">
		<div class="col-xs-12">
			<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web">
				{{-- no quieren que salga el volver en esta página --}}
				@if($data['data']->key_web_page != "info-adjudicacion")
					@include('includes.breadcrumb')
				@endif
				{!! $data['data']->content_web_page !!}

				<div class="mt-5">
					<a class="button-principal" href="{{ route('allCategories') }}">{{ trans("$theme-app.home.buscar") }}</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="down-static-banner mb-5">
	{!! BannerLib::bannersPorKey(mb_strtolower($data['data']->key_web_page) . '-baninf', 'banner-page', ['dots' => false, 'arrows' => false, 'autoplay' => true]) !!}
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

