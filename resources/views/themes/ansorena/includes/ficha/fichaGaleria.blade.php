<?php
$name="";
$phone="";
$email="";
if(!empty($data['usuario'])){
	$name=$data['usuario']->nom_cliweb;
	$phone=$data['usuario']->tel1_cli;
	$email=$data['usuario']->email_cliweb;

}

?>

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<link href="{{ Tools::urlAssetsCache('/css/default/galery.css') }}" rel="stylesheet" type="text/css">
<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/galery.css') }}" rel="stylesheet" type="text/css">
<div class="container">
	<div class="row">

			<div class="col-xs-12 galTitle">


			</div>
	</div>
</div>
<div class="ficha-content color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8" >
				<div class="imgFichaGal" aa="{{"img/".\Config::get("app.emp")."/".$lote_actual->num_hces1."/".\Config::get("app.emp")."-".$lote_actual->num_hces1."-".$lote_actual->lin_hces1."_01.jpg"}}">

					@if(!file_exists("img/".\Config::get("app.emp")."/".$lote_actual->num_hces1."/".\Config::get("app.emp")."-".$lote_actual->num_hces1."-".$lote_actual->lin_hces1."_01.jpg"))
						<img  src="{{Tools::url_img_friendly('real',$lote_actual->num_hces1,$lote_actual->lin_hces1,0,\Str::slug($lote_actual->descweb_hces1))}}" alt="{{$lote_actual->descweb_hces1}}">
					@else
						<img  src="{{Tools::url_img_friendly('real',$lote_actual->num_hces1,$lote_actual->lin_hces1,1,\Str::slug($lote_actual->descweb_hces1))}}" alt="{{$lote_actual->descweb_hces1}}">
					@endif

				</div>
				<?php /*
					<div id="img_main" class="img_single">
						<a title="{{$lote_actual->titulo_hces1}}" >
						<img class="img-responsive" src="{{Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1)}}" alt="{{$lote_actual->titulo_hces1}}">
						</a>
					</div>
					*/
				?>
			</div>
			<div class="col-xs-12 col-sm-4" >
				<div class="col-xs-12 tituloFichaGal ">

					@if(!empty($caracteristicas[1]->value_caracteristicas_hces1))
						<a href="{{ route("artistaGaleria",["id_artist" => $caracteristicas[1]->idvalue_caracteristicas_hces1])}}">
							@php
								$galeriaArtecontroller = new App\Http\Controllers\V5\GaleriaArte();
								$Artistname = $galeriaArtecontroller->explodeComillas($caracteristicas[1]->value_caracteristicas_hces1);

							@endphp
							 {{ $caracteristicas[1]->idcar_caracteristicas_hces1}}{!! $Artistname  !!}
							</a>
							 <br>
					@endif

				</div>
				@php
						/**
						 * 1 - Autor
						 * 2 - Técnica
						 * 3 - Medidas
						 * */
					@endphp
				<div class="col-xs-12 descripcionFichaGal">
					{{$lote_actual->descweb_hces1 }}<br/>
					{!! $lote_actual->descdet_hces1 !!}
					{!! !empty($caracteristicas[2]->value_caracteristicas_hces1) ? $caracteristicas[2]->value_caracteristicas_hces1."<br>" : '' !!}
					{!! !empty($caracteristicas[3]->value_caracteristicas_hces1) ? $caracteristicas[3]->value_caracteristicas_hces1."<br>" : '' !!}

				</div>
				<div class="col-xs-12 mt-2 ">
					@include('includes.ficha.share')
				</div>
				<div  class="col-xs-12 mt-2 ">
					<div id="RequestInformationView" class="cursor">
						<strong>
						{{trans(\Config::get('app.theme').'-app.galery.request_information') }}
						</strong>
						<span id="desplegableOFF"><img src="/themes/ansorena/img/flechaDer.png"> </span>
						<span id="desplegableON" class=" hidden"><img src="/themes/ansorena/img/flechaAba.png"> </span>
					</div>
					<div id="formRequest" class="hidden">
						<form name="infoLotForm" id="infoLotForm" method="post" action="javascript:sendInfoLot()">
							<input type="hidden" name="auction" value="{{ $lote_actual->cod_sub}} - {{ $lote_actual->des_sub}}">
							<input type="hidden" name="lot" value="  Obra: {{$lote_actual->ref_asigl0 }} - {{$lote_actual->descweb_hces1 }} ">
							@foreach($caracteristicas as $key => $caracteristica)
								@if($key!=1)
									<input type="hidden" name="{{ $caracteristica->name_caracteristicas }}" value="{{$caracteristica->value_caracteristicas_hces1 }}">

								@else
									<input type="hidden" name="Autor" value="{{$Artistname}}">

								@endif
							@endforeach

								@csrf
							<div class="form-group ">
								<div class="input-effect col-xs-12">
									<label>{{trans(\Config::get('app.theme').'-app.login_register.contact') }}</label>
									<input type="text" class="form-control  " name="nombre" id="texto__1__nombre" value="{{$name}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

								</div>

								<div class="input-effect col-xs-12">
									<label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}</label>
									<input type="text" class="form-control  " name="email" id="email__1__email" value="{{$email}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

								</div>

								<div class="input-effect col-xs-12">
									<label>{{trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
									<input type="text" class="form-control  " name="telefono" id="texto__1__telefono" value="{{$phone}}" onblur="comprueba_campo(this)" data-placement="right" placeholder="" autocomplete="off" data-content="">

								</div>

								<div class="input-effect col-xs-12">
									<label>{{trans(\Config::get('app.theme').'-app.global.coment') }}</label>
									<textarea  class="form-control  " name="comentario"  id="textogrande__0__comentario"   rows="10">  </textarea>

								</div>

								<div class="check_term col-xs-12">
									<div class="row">
										<div class="col-xs-2 col-md-1">
											<input type="checkbox" class="newsletter" name="condiciones" value="on"
												id="bool__1__condiciones" autocomplete="off">
										</div>
										<div class="col-xs-10 col-md-11">
											<label
												for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
										</div>
									</div>
								</div>

								<div class="col-xs-12 mt-3">
									<div class="row">
										<div class="g-recaptcha col-xs-6"
											data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
											data-callback="onSubmit">
										</div>
									</div>
								</div>

								<div class="col-xs-12 mt-3">
									<div class="row">
										<div class="col-xs-6">
											<a onclick="javascript:submit_form(document.getElementById('infoLotForm'),0);" class="button-principal submitButton">Enviar</a>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script>

$(function() {
	$("#RequestInformationView").on("click", function(){
	//	if($("#biographyArtistText").hasClass("hidden"){
			$("#formRequest").toggleClass("hidden");
			$("#desplegableOFF").toggleClass("hidden");
			$("#desplegableON").toggleClass("hidden");
		//}
	})
});

	function sendInfoLot() {


			$.ajax({
				type: "POST",
				data: $("#infoLotForm").serialize(),
				url: '/api-ajax/ask-info-lot',
				success: function (res) {

					showMessage("¡Gracias! Hemos sido notificados.  ");
					$("input[name=nombre]").val('');
					$("input[name=email]").val('');
					$("input[name=telefono]").val('');
					$("textarea[name=comentario]").val('');

				},
				error: function (e) {
					showMessage("Ha ocurrido un error y no hemos podido ser notificados");
				}
			});

	}
	</script>
