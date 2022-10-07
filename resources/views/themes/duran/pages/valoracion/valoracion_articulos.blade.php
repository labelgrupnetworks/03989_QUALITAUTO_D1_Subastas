@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="breadcrumb-total row">
    <div class="col-xs-12 col-sm-12 text-center color-letter">
            @include('includes.breadcrumb')

    </div>
</div>

    <div id="" class="free-valuations color-letter">
	    <div class="container" id="return-valoracion">
            <div class="row">


                <form id="form-valoracion-adv" class="form">
                    <div class=" col-xs-12 col-lg-8 col-lg-offset-2">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class=" col-xs-12 content-form-valuations no-padding">
                        <p class="text-danger valoracion-h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</p>

						<div class=" col-xs-12 mt-3 mb-5 text-center">
							<strong>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.elija_tipo_consulta') }}<strong><br>
							<select  id="tipo" name="tipo">
								<option value="tasacion" <?= request("tipo") == "tasacion"? "selected='selected'" : "" ?>> {{trans(\Config::get('app.theme').'-app.valoracion_gratuita.tasacion')  }} </option>
								<option value="venta privada" <?= request("tipo") == "ventaprivada"? "selected='selected'" : "" ?>> {{trans(\Config::get('app.theme').'-app.valoracion_gratuita.venta_privada')   }} </option>
								<option value="valoracion" <?= request("tipo") == "valoracion"? "selected='selected'" : "" ?>> {{trans(\Config::get('app.theme').'-app.valoracion_gratuita.valoracion')  }} </option>
							</select>
							<input type="hidden" id="email_category" name="email_category" value="<?= request("tipo") == "ventaprivada"? 'ventaprivada@duran-subastas.com' : ''?>">
						</div>


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




                        </div>
                        <div class="col-lg-6 col-xs-12 no-padding inputs-custom-group d-flex flex-column">
                                <label class="" style="color: lightgray; font-size: 10px; font-weight: 100"><?=  trans(\Config::get('app.theme').'-app.user_panel.description')  ?></label>

                            <textarea class="form-control" id="exampleTextarea" rows="3" name="descripcion" required placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.description') }}"></textarea>
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
							<div style="margin-top: 20px;" id="recaptcha" data-callback="recaptcha_callback" class="g-recaptcha" data-sitekey="{{ Config::get('app.codRecaptchaEmailPublico') }}"></div>
                            <div class="col-xs-12 text-right pb-5">
                                <button type="submit" id="valoracion-adv" class="button-send-valorate button-principal">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send') }}</button>
                            </div>
					</div>
				 </form>




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


  $('#tipo').on('change', function(){
	if($(this).val()=='venta privada'){
		$('#email_category').val('ventaprivada@duran-subastas.com');
	}else{
		$('#email_category').val('');
	}

  })

});

</script>
@stop
