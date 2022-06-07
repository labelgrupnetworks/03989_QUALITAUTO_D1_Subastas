@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php
$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.foot.departments'),"url" =>\Routing::translateSeo('departamentos')  );
?>


<div style="width: 100% ;height: 360px;background: url('/themes/soleryllach/assets/img/departamentos/department{{$ortsec->lin_ortsec0}}_large.jpg');background-repeat:no-repeat;background-position: center;background-size: cover;"></div>
    @include('includes.breadcrumb')
<div class="container">

    <div class="row" >
        <div class="col-xs-12 col-sm-12 resultok">


                <?php
                    $title ="";
					if(!empty($especialistas)){
                        $title = head($especialistas)->titulo_especial0;
                    }
                ?>
                <h1 class="titlePage">
                    {{$title}}
                </h1>

        </div>
        <div class="col-lg-12">

             <div class="lotes_destacados" style="margin-top: 80px;">
                    <div class="container">
                            <div class="title_lotes_destacados principal-color">
                                      {{ trans("$theme-app.valoracion_gratuita.our_experts") }}
                            </div>
                    </div>
                </div>


            @foreach($especialistas as  $esp)



                    <?php
                    $name_archive = '/img/PER/'.Config::get('app.gemp').$esp->per_especial1.'.jpg';

                    if (file_exists($name_archive)) {
                       $name_archive = '/themes/'.\Config::get('app.theme').'/img/items/no_photo.png';
                    }
                    ?>

                        <div class="col-lg-6" style="margin-top:20px;min-height: 172px;" >
                            <div class="row">
                            <div class="col-lg-5">
                                 <img class="img-responsive" src="<?= $name_archive?>" width="128px">
                            </div>
                            <div class="col-lg-7 margin_30">
                                <p> <?= ucwords($esp->nom_especial1) ?></p>
                                 <a href="mailto:{{strtolower($esp->email_especial1)}}">{{strtolower($esp->email_especial1)}}</a>

                            </div>
                            </div>
                        </div>


            @endforeach
        </div>
    </div>

                <div class="lotes_destacados" style="margin-top: 80px;">
                    <div class="container">
                            <div class="title_lotes_destacados principal-color">
                                     {{ trans("$theme-app.valoracion_gratuita.contact_us") }}
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">

                        <form action="/api-ajax/mail" method="post">
                             <div class="form-group">
                                     <label for="Nombre"><strong>{{ trans("$theme-app.login_register.nombre") }}</strong></label>
                                     <input placeholder="{{ trans("$theme-app.login_register.nombre") }}" class="form-control" type="text" name="nombre" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="E-mail">E-mail</label>
                                             <input placeholder="E-mail" class="form-control" type="text" name="email" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="Teléfono">{{ trans("$theme-app.login_register.phone") }}</label>
                                     <input placeholder="{{ trans("$theme-app.login_register.phone") }}" class="form-control" type="text" name="telefono" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="Comentario">{{ trans("$theme-app.global.coment") }}</label>
                                     <textarea name="comentario"  required aria-required="true" id="" cols="30" rows="4" class="form-control"></textarea>
                             </div>
                            <div class="g-recaptcha" data-sitekey="6LdhD34UAAAAANG9lkke6_b6fyycAsWTpfpm_sTV"  id="html_element" data-callback="recaptcha_callback" ></div>
                                         <div class="checkbox">
                                 <label>
                                     <input name="condiciones" required="" type="checkbox">{!! trans("$theme-app.login_register.read_conditions_politic") !!}</u>

                                 </label>
                               </div>
                            * {{ trans("$theme-app.login_register.all_fields_are_required") }}
                             <button style="cursor:pointer;" id="buttonSend" type="submit" class="btn btn-contact btn-color" disabled="" >{{ trans("$theme-app.login_register.send") }}</button>
                     </form>
                    </div>
                    <div class="col-lg-2"></div>
                </div>


       <!-- Inicio lotes destacados -->
<div class="lotes_destacados" style="margin-top: 80px;">
    <div class="container">
            <div class="title_lotes_destacados principal-color">{{ trans("$theme-app.subastas.current_lots") }}</div>
            <div class="loader"></div>
            <div class="owl-theme owl-carousel" id="lotes_departamentos"></div>
    </div>
</div>


</div>
       <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl={{config('app.locale')}}"
        async defer>
    </script>

    <script type="text/javascript">

    var verifyCallback = function(response) {
        $('#buttonSend').attr('disabled', false)
      };

      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6LdhD34UAAAAANG9lkke6_b6fyycAsWTpfpm_sTV',
          'callback' : verifyCallback,
          'theme' : 'light'
        });
      };



       <?php
        $key = "lotes_departamentos";
        $replace = array(
          'departamento' => $ortsec->lin_ortsec0    ,'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp'),'gemp' => Config::get('app.gemp')
                  );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
     });
    </script>

@stop
