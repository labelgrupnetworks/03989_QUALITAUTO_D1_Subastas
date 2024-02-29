@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" =>$data['data']->name_web_page  );
?>


@php

#recogemos el valor de la familia de faqs en $matches[1], en $matches[1] esta todo el código escrito [*FAQS-x*]
preg_match('/\[\*FAQS\-(.*?)\*\]/',$data['data']->content_web_page, $matches );

if(!empty($matches[0])){
	$data['data']->content_web_page = str_replace($matches[0],"",$data['data']->content_web_page );

	$faqs = \App\Models\V5\Web_Faq::where("lang_faq",strtoupper(\Config::get("app.locale")))->where("emp_faq",\Config::get('app.main_emp'))->where("cod_faqcat",$matches[1])->orderBy('position')->get();

}


#recogemos el valor del banner en $matches[1], en $matches[0] esta todo el código escrito [*BANNER-x*]
preg_match_all('/\[\*BANNER\-(.*?)\*\]/',$data['data']->content_web_page, $matches );
$banner = null;
#reemplazamos las claves en el texto
foreach($matches[0] as $key => $replace){
	/* */
	$autoplay="";
	if($key == 'HISTORIA' ){
		$autoplay=" ,autoplay: true";
	}
	$banner = \BannerLib::bannersPorKey($matches[1][$key], "BANNER_".$matches[1][$key],"{dots: false, arrows: true, infinite: true". $autoplay ."}");
	$data['data']->content_web_page = str_replace( $replace, $banner,$data['data']->content_web_page );
}


@endphp

<div class="container">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')
			<div class="container">
				<h1 class="titlePage"><?=$data['data']->name_web_page ?></h1>
				<div class="page-contact color-letter" style="text-align: justify;">

					@if(!empty($banner))
						{!! $data['data']->content_web_page !!}

					@else
						<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido static-page">
							<?php
								echo ($data['data']->content_web_page);
							?>

							@if(!empty($faqs))
								<div id="faq" class="col-xs-12 mt-4 mb-5">
									<div class="row">


										<h4> {{ trans($theme.'-app.foot.faq')}}  </h4>
											<p>
												@foreach ($faqs as $item)
															<div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
																<strong>
																	<a href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')" class="question">
																		<span>+</span>
																		<?= $item->titulo_faq ?>
																	</a>
																</strong>
																<div id="faq{{ $item->cod_faq }}" class="faq" >
																	<?= $item->desc_faq ?>
																	<br>
																</div>
															</div>
														@endforeach
											</p>


									</div>
								</div>
							@endif
						</div>
					@endif

				</div>
			</div>
		</div>
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

