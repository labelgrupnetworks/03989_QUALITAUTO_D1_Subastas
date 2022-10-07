@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<div class="container">
    <div class="row">
		{{--
        <div class="col-xs-12 col-sm-12 text-center color-letter">
                @include('includes.breadcrumb')
            </div>
        </div>
		--}}
    </div>
    <div id="" class="free-valuations color-letter">
	    <div class="container" id="return-valoracion">
            <div class="row">
				<div class="col-xs-12 col-lg-11 col-lg-offset-1">
					{{--<div class="col-xs-12 text-center hidden-xs ">
						<a href="mailto:victor.marco@ansorena.com">	<img src="/themes/ansorena/img/banner-valoracion.jpg" width="100%"/></a>
					</div>
					<div class="col-xs-12 text-center hidden-sm hidden-md hidden-lg mb-1">
						<a href="mailto:victor.marco@ansorena.com">	<img src="/themes/ansorena/img/banner-valoracion-text.jpg" width="100%"/></a>
						<img src="/themes/ansorena/img/banner-valoracion-img.jpg" width="100%"/>
					</div>

					<div class="col-xs-12 text-center titlePage mt-5 mb-1" style="font-size: 20px; color: #7e9396;">
						<strong>	¿ESTÁ INTERESADO EN VENDER UNA OBRA DE ARTE ANTIGUA?</strong>
					</div>

					<div class="col-xs-12 col-md-6 mt-3 " style="">
						<p style="padding-right: 5px;">Nuestro experto en <b>Pintura Antigua y S.XIX.</b> estará en <b>Barcelona</b> los días <b>22 y 23 de Septiembre.</b></p>
						<p style="padding-right: 5px;">Valoraciones gratuitas para subasta. Tasación, valoración o gestión de venta privada.</p>
						<p style="padding-right: 5px;">No pierda esta oportunidad y <b>solicite su cita previa.</b></p>

					</div>
					<div class="col-xs-12 col-md-6 mt-2 mb-5 " >
						<div class="col-xs-12 "  style="border: 1px solid #ccc;">
							<div class="col-xs-12 mt-2 mb-2 " >
								<p><b>SOLICITE CITA PREVIA POR TELÉFONO O POR EMAIL</b></p>
								<ul style="padding-left: 15px;color: #7e9396;">
									<li ><a href="mailto:victor.marco@ansorena.com">Victor.marco@ansorena.com</a></li>
									<li style="padding-top: 10px;">699068542</li>
								</ul>
							</div>
						</div>
					</div>
					--}}


					<div class="col-xs-12 text-center mt-1">
						<h1 class="titlePage" style='text-transform: uppercase;font-size: 20px; color: #7e9396;'><b>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.solicitud_valoracion') }}</b></h1>

							{!! \BannerLib::bannersPorKey("BANNER_VALORACION", "BANNER_VALORACION")!!}

					</div>
					<div class="col-xs-12 info">
						<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_assessment')  ?>
					</div>
					<form id="form-valoracion-adv" class="form">
						<div class=" col-xs-12 ">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class=" col-xs-12 content-form-valuations no-padding">
							<p class="text-danger valoracion-h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</p>
							<div class="col-xs-12 col-lg-6  no-padding d-flex flex-direction-column inputs-custom-group">
								<div class="form-group form-group-custom col-xs-12 col-xs-12">
									<label class="" for="name"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')  ?></label>
									<input
										class="form-control"
										id="name"
										name="name"
										placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')  ?>"
										required=""
										type="text"
									/>
								</div>

								<div class="form-group form-group-custom col-xs-12 col-xs-12">
									<label class="" for="name"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')  ?></label>
									<input
										class="form-control"
										id="email"
										name="email"
										placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')  ?>"
										required=""
										type="email"
									/>
								</div>

								<div class="form-group form-group-custom col-xs-12 col-xs-12">
									<label class="" for="telf"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')  ?></label>
									<input
										class="form-control"
										id="telf"
										name="telf"
										placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')  ?>"
										required=""
										type="phone"
									/>
								</div>
								<div class="form-group  form-group-custom col-xs-12 col-xs-12">
									<label for="categoria" class="control-label">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.category')}}</label>

										<select name="categoria">
											<option value="artes_decorativas" selected="">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.artes_decorativas')}}</option>
											<option value="joyas">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.joyas')}}</option>
											<option value="muebles">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.muebles')}}</option>
											<option value="pintura_antigua">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.pintura_antigua')}}</option>
											<option value="pintura">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.pintura')}}</option>
										</select>
								</div>




							</div>
							<div class="col-lg-6 col-xs-12 no-padding inputs-custom-group d-flex flex-column">
									<label class="" style="color: lightgray; font-size: 10px; font-weight: 100"><?=  trans(\Config::get('app.theme').'-app.user_panel.description')  ?></label>

								<textarea class="form-control" id="exampleTextarea" rows="10" name="descripcion" required placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.description') }}"></textarea>
							</div>
						</div>
						<div class="form-group form-group-custom col-xs-12">

								<div id="dropzone">
									<small class="text-danger error-dropzone" style="display:none">{{ trans(\Config::get('app.theme').'-app.msg_error.max_size') }}</small>
									<div class="color-letter text-dropzone"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.adj_IMG')  ?></div>
									<div class="mini-file-content d-flex align-items-center" style="position:relative"></div>

										<input id="images" type="file" name="imagen[]" />
									</div>
						</div>
								<div class="col-xs-12 text-right no-padding">
									<button type="submit" id="valoracion-adv" class="button-send-valorate button-principal">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send') }}</button>
								</div>
					</div>

					<div class="col-xs-12 ">
						{!! trans(\Config::get('app.theme').'-app.global.proteccion_datos_valoración') !!}
					</div>
				</form>
			</div>
        </div>
    </div>
</div>


<script>
      var imagesarr = [];
      function myFunction( el ) {
        $(el).remove()
    }
$(function() {

$('.mini-upload-image').click(function (){
    alert()
})


 });

</script>
@stop
