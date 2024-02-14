@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php


?>

<section class="principal-bar no-principal services-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.services.encapsulacion') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<section class="services-page">
    <div class="container">
        <div class="row pt-4">

            <div class="col-sm-6 col-xs-12 text-description-encapsulacion mb-3">
                <?php
                $key = "encapsulacion_" . strtoupper(Config::get('app.locale'));
                $html = "{html}";
                $content = \Tools::slider($key, $html);
                ?>
                <?= $content; ?>

            </div>
            <div class="col-sm-6 col-xs-12 ">
                <div class="valoracion-imagen">
                    <img class="img-responsive" src="/themes/{{$theme}}/img/servicios/certificados.png">
                </div>
            </div>
        </div>
    </div>
    <div class="custom-bar-title">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div>{{ trans($theme.'-app.services.rellene_formulario_calcule_presupuesto') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-custom-encapsulacion pt-2">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-xs-12 col-md-offset-1">
                    <form id="encap-service">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_name') }}</label>
                            <input type="text" name="nombre"  required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-8 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_address') }}</label>
                            <input type="text" name="lugar" required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-2 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_cp') }}</label>
                            <input type="text" name="cp" required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_city') }}</label>
                            <input type="text" name="ciudad"  required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-4 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_pro') }}</label>
                            <input type="text" name="provincia" required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-2 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_country') }}</label>
                            <input type="text" name="pais" required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-3 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_phone') }}</label>
                            <input type="text" name="telf"  required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-6 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_email') }}</label>
                            <input type="text" name="email"  required class="form-control custom-input-encapsulacion">
                        </div>
                        <div class="form-group col-sm-3 col-xs-12 mb-1 ">
                            <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_service') }}</label>
                            <select name="servicio" class="d-block w-100">
                                <option value="NGC">NGC</option>
                                <option value="PMG">PMG</option>
                                <option value="NCS">NCS</option>
                                <option value="PCGS">PCGS</option>
                            </select>
                        </div>
                        <div class="programed-table mt-4 col-xs-12 p-0 position-relative">

                            <div class="line-programed-clone col-xs-12 p-0 position-relative d-none position-relative">
                                <div class="form-group col-xs-7 col-md-9 col-sm-8 mb-1 pr-1 position-relative">

                                        <input type="text" name="descripcion[]"  class="form-control custom-input-encapsulacion">
                                    </div>
                                <div class="form-group col-xs-3 mb-1 pl-0">

                                        <input type="text" name="valor[]"  class="form-control custom-input-encapsulacion">
                                    </div>
                                <div onclick="deleteline($(this))" class="add-encapsulacion button-delete d-flex align-items-center justify-content-center"><i class="fas fa-minus"></i></div>
                            </div>




                            <div class="line-programed line-custom line-custom position-relative col-xs-12 p-0">
                                <div class="form-group col-xs-7 col-sm-8 col-md-9 mb-1 pr-1 position-relative">
                                    <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_description') }}</label>
                                    <input type="text" name="descripcion[]"  required class="form-control custom-input-encapsulacion">
                                </div>
                                <div class="form-group col-xs-3 mb-1 pl-0">
                                    <label class="form-custom-encapsulacion-label">{{ trans($theme.'-app.services.label_precio') }}</label>
                                    <input type="text" name="valor[]" required class="form-control custom-input-encapsulacion">
                                </div>
                                <div id="add-new-encap" class="add-encapsulacion d-flex align-items-center justify-content-center"><i class="fas fa-plus"></i></div>
                            </div>
                        </div>
                        <div class="form-encapsulacion-buttons col-xs-12 mb-2 mt-2 ">
							<div class="row">
                            <div class="button-tarifas col-xs-12 col-sm-3 pr-1"> <button data-toggle="modal" data-target="#myModal" type="button" class="w-100">{{ trans($theme.'-app.services.tarifas') }}</button></div>
							<div class="button-calcular col-xs-12 col-sm-9 pl-0"><button type="submit" class=" w-100">{{ trans($theme.'-app.services.calcular_presupuesto') }}</button></div>
							</div>
                        </div>
                        <div class="col-xs-12 mb-2">
                        <div class="label label-success labels-form  d-none ">{{ trans($theme.'-app.services.form_success') }}</div>
                        <div class="label label-danger labels-form  d-none">{{ trans($theme.'-app.services.form_error') }}</div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
                <iframe id="myFrame" width="100%" height="700" src="/themes/{{$theme}}/img/servicios/rates.pdf">

                </iframe>

          </div>
        </div>
      </div>

</section>


<script>

$('#add-new-encap').click(function(){
    var count = $('.line-custom').length

    var clone = $('.line-programed-clone').clone()
    $(clone)
    .removeClass('line-programed-clone')
    .addClass('line-programed-' + count)
    .addClass('line-custom')
    .removeClass('d-none')
    .appendTo('.programed-table')



    if (count > 1){
        console.log($('.line-programed-' + count - 1))
        $('.line-programed-' + (count - 1)).find('.button-delete').hide()
    }



})
function deleteline(el) {
    var count = $('.line-custom').length
    $(el).parent().remove()
    $('.line-programed-' + (count - 2)).find('.button-delete').show()

    if(count === 2){
        $('#add-new-encap').show()
    }

}


    </script>

@stop
