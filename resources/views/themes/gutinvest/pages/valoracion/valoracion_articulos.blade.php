@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
<section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.solicitud_valoracion') }}</h1>
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>
<div id="valoracion_gratuita">
    <div class="container" id="return-valoracion">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="title_single_adj">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.adjuntar_activo') }}</h1>
            </div>
            <div class="col-xs-12">
                <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_assessment')  ?>
            </div>
            <div class="col-xs-12">
                <div class="sub_title_single_adj">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.datos_cliente') }}</div>
            </div>
            <div class="col-xs-12">
                <form class="form" id="form-valoracion-adv">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-xs-12 no-padding">
                            <div class="col-md-4 no-padding form-group">
                                <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')  ?></small>
                                <input style="height: 28px;" required type="text" name="name" required class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 no-padding">
                            <div class="col-md-3 no-padding form-group">
                                <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')  ?></small>
                                <input style="height: 28px;" required type="email" name="email" required class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 no-padding">
                            <div class="col-md-3 form-group no-padding">
                                <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')  ?></small>
                                <input  style="height: 28px;" required type="text" name="telf" required class="form-control">
                            </div>
                        </div>
                        <input type="hidden" value="info@gutinvest.es" name="email_category">
                        <div class="col-xs-12 no-padding">
                            <div class="sub_title_single_adj">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.tipo_activo') }}</div>
                            <div class="form-group grupo-tipo-activo">
                                <input class="form-check-input hide" checked=""type="radio" name="Tasacion_tipo" id="otros" value="maquinaria">
                                <label class="form-check-label" for="otros">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.machines') }}</label>
                                <input class="form-check-input hide" type="radio" name="Tasacion_tipo" id="inmobiliaria" value="inmuebles">
                                <label class="form-check-label" for="inmobiliaria">{{ trans(\Config::get('app.theme').'-app.subastas.inmuebles') }}</label>
                              </div>
                        </div>
                        <div class="col-xs-12 no-padding">
                            <div class="col-xs-12  col-sm-4 no-padding adj_archivos ">
                                <div class="sub_title_single_adj adj_title">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.adj_doc') }}</div>
                                <small style="margin-bottom: 15px; display: block;"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_img')  ?></small>
                                <div class="col-xs-6 no-padding">
                                    <label for="files" class="">
                                        <img
                                            src="/themes/{{\Config::get('app.theme')}}/assets/img/up_files.png"
                                            alt="{{(\Config::get( 'app.name' ))}}"
                                            width="80"
                                            class="img-responsive "
                                        >
                                        <p class="text-center">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.up_file') }}</p>
                                    </label>
                                    <input id="files" class="hidden" type="file" accept="image/png, image/jpeg" name="imagen[]" multiple>
                                </div>
                                <div class="col-xs-6">
                                    <p>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.uploaded_img') }}</p>
                                        <ul class="list-upload">

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <div class="sub_title_single_adj adj_title">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.adj_img') }}</div>
                                    <div class="we_transfer_link">
                                        <a target="_blank" href="https://wetransfer.com/">
                                            <img
                                              src="/themes/{{\Config::get('app.theme')}}/assets/img/we_trans.png"
                                            alt="{{(\Config::get( 'app.name' ))}}"
                                            width="80"
                                            class="img-responsive "
                                            >
                                        </a>
                                        <small>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send_to') }}</small>
                                    </div>
                                    <div class="col-xs-12 form-group otro-link">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.otro_upload')  ?></small>
                                        <input type="text" style="height: 28px;" name="link_upload" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 no-padding">
                                </div>
                            </div>
                            <div class="col-xs-12 no-padding">
                                <div class="sub_title_single_adj">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.complete_form') }}</div>
                                <div class="col-xs-6 no-padding">
                                    <div class="col-md-3 no-padding form-group solo-maquinaria">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.n_objetos')  ?></small>
                                        <input style="height: 28px;" type="text" name="N_objetos"  class="form-control">
                                    </div>

                                    <div class="col-md-10 no-padding form-group">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.titulo')  ?></small>
                                        <input style="height: 28px;" type="text" name="Titulo"  class="form-control">
                                    </div>
                                    <div class="col-md-10 no-padding form-group solo-inmobiliaria hidden">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.ref_castral')  ?></small>
                                        <input style="height: 28px;" type="text" name="Ref_castral"  class="form-control">
                                    </div>
                                    <div class="col-md-8 no-padding form-group solo-inmobiliaria hidden">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.provincia')  ?></small>
                                        <input style="height: 28px;" type="text" name="Provincia"  class="form-control">
                                    </div>
                                    <div class="col-md-8 no-padding form-group solo-inmobiliaria hidden">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.poblacion')  ?></small>
                                        <input style="height: 28px;" type="text" name="Poblacion"  class="form-control">
                                    </div>
                                    <div class="col-md-10 no-padding form-group solo-maquinaria">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.fabricante')  ?></small>
                                        <input style="height: 28px;" type="text" name="Fabricante"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="col-md-10 no-padding form-group solo-inmobiliaria hidden">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.direccion')  ?></small>
                                        <input style="height: 28px;" type="text" name="Direccion"  class="form-control">
                                    </div>
                                    <div class="col-md-10 no-padding form-group solo-inmobiliaria hidden">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.sup_total')  ?></small>
                                        <input style="height: 28px;" type="text" name="Sup_total"  class="form-control">
                                    </div>
                                    <div class="col-md-5 no-padding  form-group solo-maquinaria">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.no_serie')  ?></small>
                                        <input style="height: 28px;" type="text" name="N_serie"  class="form-control">
                                    </div>
                                    <div class="col-md-8 no-padding  form-group solo-maquinaria">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.model')  ?></small>
                                        <input style="height: 28px;" type="text" name="Modelo"  class="form-control">
                                    </div>
                                    <div class="col-md-8 no-padding form-group">
                                        <small><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.precio')  ?></small>
                                        <small style="font-size: 8px; display: block"><?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.tasar')  ?></small>
                                        <input style="height: 28px;" type="text" name="Precio"  class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 no-padding solo-inmobiliaria hidden">
                                    <textarea class="form-control"  id="description" rows="3" name="Detalle_inmueble"  placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_inmu') }}"></textarea>

                                </div>
                                <div class="col-xs-12 no-padding solo-maquinaria">
                                    <textarea class="form-control"  id="description" rows="3" name="descripcion"  placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_tecnica') }}"></textarea>

                                </div>
                                <div class="col-xs-12 no-padding solo-maquinaria">
                                    <textarea class="form-control" id="dimension" rows="3" name="dimensiones"  placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_dimen') }}"></textarea>
                                </div>
                                <div class="col-xs-12 no-padding solo-maquinaria">
                                    <div class="checkbox" style="text-align: left;">
                                        <label>
                                            <input name="condiciones" required  style="opacity: 1" type="checkbox">{{ trans(\Config::get('app.theme').'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
</a>                                    </label>
                                    </div>
								</div>
								<div class="col-xs-12 mt-3 mb-3" style="margin: 10px 0px">
									<div class="row">
										<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"  id="html_element" data-callback="recaptcha_callback" ></div>
									</div>
								</div>
                            </div>
                            <div class="col-xs-12 no-padding mt-3">
                                <button type="submit" id="valoracion-adv" class="btn-valoracion  btn btn-primary"><div class='loader hidden'></div>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send') }}</button>
                                <h4 class="valoracion-h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</h4>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

<script>
$('#files').change(function(e){
    console.log($('#files').length)
    $('.list-upload li').remove()
    console.log(e.currentTarget.files)
    for( var i= 0 ; i<e.currentTarget.files.length; i++){
        $('.list-upload').append('<li data-index='+i+'><div  class="file_name">'+ e.currentTarget.files[i].name +'</div></li>')
    }


})

$('#valoracion-adv').attr('disabled', true);

$('input[name=Tasacion_tipo]').click(function() {
    if($(this).attr('id')=== 'inmobiliaria'){
        $('.solo-maquinaria').addClass('hidden')
        $('.solo-maquinaria input'). val('')
                $('.solo-maquinaria textarea'). val('')
        $('.solo-inmobiliaria').removeClass('hidden')
    }
    if($(this).attr('id')=== 'otros'){
        $('.solo-maquinaria').removeClass('hidden')
        $('.solo-inmobiliaria input'). val('')
                $('.solo-inmobiliaria textarea'). val('')
        $('.solo-inmobiliaria').addClass('hidden')
    }
})

var recaptcha_callback = function(response) {
    $('#valoracion-adv').attr('disabled', false)
};


</script>
@stop

