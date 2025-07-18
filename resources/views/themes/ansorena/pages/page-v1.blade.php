
@section('content')


@php
$bread[] = array("name" =>$data['data']->name_web_page  );
$menuEstaticoHtml=null;
#recogemos el valor del banner en $matches[1], en $matches[0] esta todo el código escrito [*BANNER-x*]
$menusEstaticos= array("MENUANSORENA");
foreach($menusEstaticos as $key){
	#si aun no ha encontrado un menu que sustituir
	if(empty($menuEstaticoHtml)){
		$menuEstatico = strpos($data['data']->content_web_page ,"[*".$key."*]");

		if($menuEstatico !== FALSE){
			$menuEstaticoHtml = (new App\Services\Content\PageService())->getPage($key);
			#borramos la clave [*MENUCONDECORACIONES*], por que la pondremos a mano en la página

			$data['data']->content_web_page = str_replace( "[*".$key."*]",$menuEstaticoHtml->content_web_page,$data['data']->content_web_page );
		}
	}
}
@endphp

<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center color-letter">

					<h1 class="titlePage"><?=$data['data']->name_web_page ?></h1>
				@if(empty($menuEstaticoHtml ))
					@include('includes.breadcrumb')
				@endif


			</div>
		</div>
	</div>

<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web">




			@php

				#recogemos el valor del banner en $matches[1], en $matches[0] esta todo el código escrito [*BANNER-x*]
				preg_match_all('/\[\*BANNER\-(.*?)\*\]/',$data['data']->content_web_page, $matches );

				#reemplazamos las claves en el texto
				foreach($matches[0] as $key => $replace){
					/* */
					$autoplay="";
					if($key == 'HISTORIA' ){
						$autoplay=" ,autoplay: true";
					}
					$banner = \BannerLib::bannersPorKey($matches[1][$key], "BANNER_".$matches[1][$key],"{dots: false,pauseOnHover: false, arrows: true, infinite: true". $autoplay ."}");
					$data['data']->content_web_page = str_replace( $replace, $banner,$data['data']->content_web_page );
				}

				#reemplazamos las redes sociales
				$redesSociales = strpos($data['data']->content_web_page ,"[*REDESSOCIALES*]");

					if($redesSociales !== FALSE){
						$data['data']->content_web_page = str_replace( "[*REDESSOCIALES*]","",$data['data']->content_web_page );
					}

			@endphp
			@if(count($matches[0])==0)
			<div class="container">
			@endif
			{!! $data['data']->content_web_page !!}



			@if($redesSociales !== FALSE)

				@include('includes.sharePage')
			@endif
			<br><br>
			@if(count($matches[0])>0)
				</div>
			@endif
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

