@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" =>$data['data']->name_web_page  );
?>



    <section class="all-aution-title title-content pb-1">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 h1-titl text-center">
                        <h1 class="page-title mb-0"><?=$data['data']->name_web_page ?></h1>
                    </div>
                </div>
                @if(!empty($data['seo']->meta_description))
                <div class="row">
                    <div class="col-xs-12 h1-titl text-center">
                        <p class="mt-1 mb-1 page-description"><?=$data['seo']->meta_description ?></p>
                    </div>
                </div>
                @endif
            </div>
        </section>

<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
	<div class="container">
		<?php echo ($data['data']->content_web_page); ?>
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

