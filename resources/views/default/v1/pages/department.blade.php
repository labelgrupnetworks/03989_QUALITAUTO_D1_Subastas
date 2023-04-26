@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php
	$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.foot.departments'),"url" =>\Routing::translateSeo('departamentos')  );
	$theme = \Config::get('app.theme');
?>


<div style="width: 100% ;height: 360px;background: url('/themes/{{$theme}}/assets/img/departamentos/department{{$departments[0]}}_large.jpg');background-repeat:no-repeat;background-position: center;background-size: cover;"></div>
    @include('includes.breadcrumb')
<div class="container">
    <div class="row" >
        <div class="col-xs-12 col-sm-12 resultok">
            <h1 class="titlePage">{{$ortsec->des_ortsec0 }}</h1>
		</div>
		<div class="col-xs-12 col-sm-12">
			<p>{!!$ortsec->meta_contenido_ortsec0!!}</p>
		</div>


        <div class="col-lg-12">




			@foreach($especialistas as  $esp)

				@if ($loop->first)
				<div class="lotes_destacados" style="margin-top: 80px;">
                    <div class="container">
                        <div class="title_lotes_destacados principal-color">
							<h3 class="title-home text-center">
								{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.our_experts') }}
							</h3>
                        </div>
                    </div>
                </div>
				@endif



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
								<h3 class="title-home text-center">
									{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.contact_us') }}
								</h3>

                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">

                        <form action="/api-ajax/mail" method="post">
                             <div class="form-group">
							 	<label for="Nombre"><strong>{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')}}</strong></label>
                                     <input placeholder="Nombre" class="form-control" type="text" name="nombre" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="E-mail">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')}}</label>
                                             <input placeholder="E-mail" class="form-control" type="text" name="email" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="Teléfono">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')}}</label>
                                     <input placeholder="Teléfono" class="form-control" type="text" name="telefono" required aria-required="true">
                             </div>
                             <div class="form-group">
                                     <label for="Comentario">{{trans(\Config::get('app.theme').'-app.valoracion_gratuita.description')}}</label>
                                     <textarea name="comentario"  required aria-required="true" id="" cols="30" rows="4" class="form-control"></textarea>
                             </div>
                            <div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"  id="html_element" data-callback="recaptcha_callback" ></div>
                                         <div class="checkbox">
                                 <label>
                                     <input name="condiciones" required="" type="checkbox">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions_politic') !!}
                                 </label>
                               </div>
                            * {{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}
							<button style="cursor:pointer;" id="buttonSend" type="submit" class="btn btn-contact btn-color" disabled="" >{{ trans(\Config::get('app.theme').'-app.login_register.acceder')}}</button>
                     </form>
                    </div>
                    <div class="col-lg-2"></div>
                </div>


       <!-- Inicio lotes destacados -->
<div class="lotes_destacados" style="margin-top: 80px;">
    <div class="container">
            <div class="title_lotes_destacados principal-color">
				<h3 class="title-home text-center">
					Lotes vigentes
				</h3>
            </div>
            <div class="loader"></div>
            <div class="owl-theme owl-carousel" id="lotes_departamentos"></div>
    </div>
</div>


</div>
       <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>

    <script type="text/javascript">

    var verifyCallback = function(response) {
        $('#buttonSend').attr('disabled', false)
      };

      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '{{\Config::get('app.codRecaptchaEmailPublico')}}',
          'callback' : verifyCallback,
          'theme' : 'light'
        });
      };



       <?php
        $key = "lotes_departamentos";
        $replace = array(
          'departamento' => $departments[0]    ,'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp'),'gemp' => Config::get('app.gemp')
                  );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
    $( document ).ready(function() {
            ajax_carousel(key,replace);
     });
    </script>

@stop
