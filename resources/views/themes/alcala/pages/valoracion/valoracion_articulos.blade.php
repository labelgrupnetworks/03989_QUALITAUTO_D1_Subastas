@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
<?php

$bread[] = array("name" =>$data['title']  );
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 text-center color-letter">
            <h1 class="titlePage"> {{ trans($theme.'-app.home.free-valuations') }}</h1>
                @include('includes.breadcrumb')
            </div>
        </div>
    </div>
    <div id="" class="free-valuations">
	    <div class="container" id="return-valoracion">
            <div class="row">
                <div class="col-xs-12">
                    <?=  trans($theme.'-app.valoracion_gratuita.desc_assessment')  ?>
                </div>
                <form id="form-valoracion-adv" class="form">
                    <div class=" col-xs-12 col-lg-8 col-lg-offset-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class=" col-xs-12 content-form-valuations">
                        <p class="text-danger valoracion-h4 hidden msg_valoracion">{{ trans($theme.'-app.valoracion_gratuita.error') }}</p>
                        <div class="col-xs-12 col-lg-6  no-padding d-flex flex-direction-column inputs-custom-group">
                            <div class="form-group form-group-custom col-xs-12 col-xs-12">
                                <label class="" for="name"><?=  trans($theme.'-app.valoracion_gratuita.name')  ?></label>
                                <input
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    required=""
                                    type="text"
                                />
                            </div>

                            <div class="form-group form-group-custom col-xs-12 col-xs-12">
                                <label class="" for="name"><?=  trans($theme.'-app.valoracion_gratuita.email')  ?></label>
                                <input
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    required=""
                                    type="email"
                                />
                            </div>

                            <div class="form-group form-group-custom col-xs-12 col-xs-12">
                                <label class="" for="telf"><?=  trans($theme.'-app.valoracion_gratuita.telf')  ?></label>
                                <input
                                    class="form-control"
                                    id="telf"
                                    name="telf"
                                    required=""
                                    type="phone"
                                />
                            </div>




                        </div>
                        <div class="col-lg-6 col-xs-12 inputs-custom-group d-flex flex-column">
                                <label class="" style="font-size: 10px; font-weight: 100"><?=  trans($theme.'-app.user_panel.description')  ?></label>

                            <textarea class="form-control" id="exampleTextarea" rows="3" name="descripcion" required placeholder="{{ trans($theme.'-app.valoracion_gratuita.description') }}"></textarea>
                        </div>
                    </div>
                    <div class="form-group form-group-custom col-xs-12">

                            <div id="dropzone">
                                <small class="text-danger error-dropzone" style="display:none">{{ trans($theme.'-app.msg_error.max_size') }}</small>
                                <div class="color-letter text-dropzone"><?=  trans($theme.'-app.valoracion_gratuita.adj_IMG')  ?></div>
                                <div class="mini-file-content d-flex align-items-center" style="position:relative"></div>

                                    <input id="images" type="file" name="imagen[]" />
                                  </div>
                    </div>
                            <div class="col-xs-12 text-right no-padding">
                                <button type="submit" id="valoracion-adv" class="button-send-valorate button-principal">{{ trans($theme.'-app.valoracion_gratuita.send') }}</button>
                            </div>
                </div>

                <div class="col-xs-12 info">
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
  $('#dropzone').on('dragover', function() {
    $(this).addClass('hover');
  });

  $('#dropzone').on('dragleave', function() {
    $(this).removeClass('hover');
  });

  $('#dropzone #images').on('change', function(e) {

    max_size = 2000;
    var size = 0
    $("#form-valoracion-adv").find('input[type="file"]').each(function (index, element) {
            $(element.files).each(function(index, el){
                size = size + ((el.size / 1024))
            })

        });
    if(Math.floor(size) < max_size){
        var idrandom = 'image-'+Math.random();
        var x = $('#images').clone();
        $(x)
            .attr('id', idrandom)
            .hide()
        $('#dropzone').append(x)
        $('.error-dropzone').hide()
    }else{
        $(this).removeClass('hover');
        $(this).val(null);
        $('.error-dropzone').show()
        return false
    }

    var img = e.target.files
    for(i = 0; i < this.files.length ; i++){

        var file = this.files[i];
    $('#dropzone').removeClass('hover');


    if ((/image\/(gif|png|jpeg|jpg)$/i).test(file.type)) {
        var reader = new FileReader(file);
        reader.readAsDataURL(file);

        reader.onload = function(e) {
        var data = e.target.result,
            $img = $('<img class="img-responsive" />').attr('src', data).fadeIn();
            $div  = $('<div onclick="myFunction(this)" id='+idrandom+' class="mini-upload-image"><div class="delete-img">Delete</div></div>');
            $(x).attr('id', idrandom).hide()
            $('#dropzone').append(x)
            $div.append(x)
            $div.append($img)
            $('#images').val('')
        $('#dropzone .mini-file-content').append($div);

      };
    } else {
      alert('Archivo no permitido')
    }



    }

  });
});

</script>
@stop
