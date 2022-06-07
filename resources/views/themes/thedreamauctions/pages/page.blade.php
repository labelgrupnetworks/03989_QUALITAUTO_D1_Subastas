@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" =>$data['data']->name_web_page  );
?>


<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter">
					<h1 class="titlePage"><?=$data['data']->name_web_page ?></h1>

				@include('includes.breadcrumb')
			</div>
		</div>
	</div>

<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web container">

		<?php

                  echo ($data['data']->content_web_page);
		?>
		<br><br>
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

