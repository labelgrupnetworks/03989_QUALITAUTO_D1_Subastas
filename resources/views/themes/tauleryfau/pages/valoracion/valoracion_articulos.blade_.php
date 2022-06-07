@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php 

$bread[] = array("name" =>$data['title']  );
?>
<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.solicitud_valoracion') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="tasacion-desc">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_assessment')  ?>
            </div>
        </div>
    </div>
</section>
<section class="tasacion-slogan flex valign">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-6 no-padding">
                <div class="info-tasaciones">
                    <div 
                        class="img-tasaciones flex" 
                        style="background-image: url(/themes/{{\Config::get('app.theme')}}/assets/img/tasacion.jpg);
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: center;"
                    >
                        <p><?= trans(\Config::get('app.theme').'-app.valoracion_gratuita.contact_text_valoracion') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="add-object">
                    <div class="add-title">
                        <h3>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.valores_su_producto') }}</h3>
                        <p><?= trans(\Config::get('app.theme').'-app.valoracion_gratuita.description') ?></p>
                    </div>
                    <div class="add-object-content flex">
                        <form class="form upload" id="form-valoracion-adv" style="width: 100%; position: relative;">
                            <div class="loader-container" style="
                                position:  absolute;
                                align-items:  center;
                                justify-content:  center;
                                height:  100%;
                                width:  100%;
                                z-index:  99;
                                background: white;
                                display: none;
                            ">
                                <div class="loader"></div>   
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="add-picture flex valign">                                    
                                <div class="text-center files1 fill">
                                    <input id="files1" type="file" class="files"  accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple>
                                    <label for="files1">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>    
                                <div class="text-center files2 fill">
                                    <input id="files2" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                    <label for="files2">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>
                                <div class="text-center files3 fill">
                                    <input id="files3" type="file" class="files" accept="image/png, image/jpeg" name="imagen[]" style="display:none" multiple >
                                    <label for="files3">
                                        <i class="fa fa-camera-retro"></i>
                                        <img class="img-responsive" style="max-height: 65px;" />
                                    </label>
                                    <span><strong></strong><i class="fa fa-minus-circle" style="display:none"></i></span>
                                </div>               
                            </div>
                            <div class="form-group col-xs-6 ">
                                <input type="text" name="name" placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')  ?>" required class="form-control">
                            </div>
                            <div class="form-group col-xs-6">
                                <input type="text" name="telf" placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')  ?>" required class="form-control">
                            </div>
                            <div class="form-group col-xs-12">
                                 <input type="email" name="email" placeholder="<?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')  ?>" required class="form-control">
                            </div>
                            <div class="col-xs-12">
                                <div class="text-area">
                                    <textarea class="form-control" id="exampleTextarea" name="descripcion" required placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.description') }}"></textarea>
                                </div>
                                
                            </div>
                            <input type="hidden" value="info@tauleryfau.com" name="email_category">

                            <div class="col-xs-12 send-button" style="margin-top: 0;">
                                <button type="submit" id="valoracion-adv" class="btn btn-color" ><div class='loader hidden'></div>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send') }}</button>
                                <h4 class="valoracion-h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</h4>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@stop






