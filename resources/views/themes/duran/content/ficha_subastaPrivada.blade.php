@extends('layouts.default')
@php
	$pagina = new App\Models\Page();
	$page  = $pagina->getPagina(\Config::get("app.locale"),"subasta-ventas-privadas");
@endphp
@section('title')
	{{ $page->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" =>$page->name_web_page  );
?>




<div class="container subastaPrivada">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')
			<div class="container">
				<h1 class="titlePage"><?=$page->name_web_page ?></h1>
				<div class="page-contact color-letter" style="text-align: justify;">

					<div id="pagina-{{ $page->id_web_page }}" class="contenido static-page">
						{!! \BannerLib::bannersPorKey('VENTAS_PRIVADAS_TOP', '', ['dots' => false, 'autoplay' => false, 'arrows' => false]) !!}
						<div class="mt-3 mb-3">
						{!!	$page->content_web_page !!}
						</div>
						@php

							//Obtener los archivos de una subasta
							$sub = new App\Models\Subasta;
							$files = $sub->getFiles($data['auction']->cod_sub);

						@endphp
						@if(!empty($files))

							<div class=" ficheros_adj mb-3" >
								<p> {{ trans(\Config::get('app.theme').'-app.lot.documents') }}</p>
								@foreach($files as $file)
								@php
									$path_icon = "";
									switch ($file->type) {
										case 1:
											$path_icon = "/img/icons/pdf.png";
											break;
										case 2:
											$path_icon = "/img/icons/video.png";
											break;
										case 3:
											$path_icon = "/img/icons/image.png";
											break;
										case 4:
											$path_icon = "/img/icons/document.png";
											break;
										default:
											$path_icon = "/img/icons/document.png";
											break;
									}
								@endphp
								<img src="{{$path_icon}}" style="float: left;" width="20px">
								<a role="button" href="/files/{{ $file->path }}" target="_blank" style="display: block; font-weight: 600">{{ $file->description }}</a>
								@endforeach
							</div>
						@endif


						<p class="destacados">Destacados</p>


						@php
							$session = head($data['sessions']);

						@endphp

						<p class="verTodos"> <a href="<?= \Tools::url_auction($session->auction, $session->name, $session->id_auc_sessions) ?>?order=orden_desc"> Ver Todos </a></p>

						{!! \BannerLib::bannersPorUbicacionKeyAsClass('VENTAS_PRIVADAS',["VIDEOS_PRIVADAS" => ['dots' => false, 'autoplay' => false, 'arrows' => false]]) !!}

						<a href="/es/valoracion-articulos?tipo=ventaprivada">
						<button type="button" class="button-como-vender"  >HAZ TU CONSULTA</button>
						</a>

						<br/><br/>

						<p>
							<strong>Información de contacto</strong></p>
						<p>
							<a  href="mailto:entasprivadas@duran-subastas.com">  ventasprivadas@duran-subastas.com</a><br/><br/>+34 91 577 60 91<br/><br/>C/ Goya 19</p>


						@php
							$faqs = \App\Models\V5\Web_Faq::where("lang_faq",strtoupper(\Config::get("app.locale")))->where("emp_faq",\Config::get('app.main_emp'))->where("cod_faqcat",4)->orderBy('position')->get();
						@endphp
						@if(!empty($faqs))
							<div id="faq" class="col-xs-12 mt-4 mb-5">
								<div class="row">


									<h4> Preguntas frecuentes </h4>
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

