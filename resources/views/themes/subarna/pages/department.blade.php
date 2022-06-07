@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php
	$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.foot.departments'),"url" =>\Routing::translateSeo('departamentos')  );
?>


<div style="width: 100% ;height: 360px;background: url('/themes/{{$theme}}/assets/img/departamentos/department{{$ortsec->lin_ortsec0}}_large.jpg');background-repeat:no-repeat;background-position: center;background-size: cover;"></div>
    @include('includes.breadcrumb')
<div class="container container-department">
    <div class="row" >
        <div class="col-xs-12 col-sm-12 resultok">
            <h1 class="titlePage">{{$ortsec->des_ortsec0 }}</h1>
		</div>
		<div class="col-xs-12 col-sm-12 seo-department">
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

					<div class="col-xs-12 col-md-8 mt-3">

						<p>{!! trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_assessment') !!}</p>

						<form class="form" id="form-valoracion-adv">
						   <input type="hidden" name="_token" value="{{ csrf_token() }}">
						   <textarea class="form-control" id="exampleTextarea" rows="3" name="descripcion" required placeholder="{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.description') }}"></textarea>
						   <div class="row mt-3">
							   <div class="col-md-12 form-group">
								  <input class="form-control" id="files" type="file" accept="image/png, image/jpeg" name="imagen[]" multiple required>
							   </div>
							   <div class="col-md-12">
								   <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.desc_img')  ?>
							   </div>
						   </div>
						   <div class="row mt-3">
							   <div class="col-md-12 form-group">
								   <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.name')  ?>
								   <input type="text" name="name" required class="form-control">
							   </div>
							   <div class="col-md-12 form-group">
								   <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.email')  ?>
								   <input type="email" name="email" required class="form-control">
							   </div>
							   <div class="col-md-12 form-group">
								   <?=  trans(\Config::get('app.theme').'-app.valoracion_gratuita.telf')  ?>
								   <input type="text" name="telf" required class="form-control">
							   </div>
						   </div>
						   <input type="hidden" value="tasaciones@subarna.net" name="email_category">
						  <div class="row" >
							  <div class="col-md-12">
								<div class="g-recaptcha" data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"  id="html_element" data-callback="recaptcha_callback" ></div>
								<div class="checkbox">
									<label>
										<input name="condiciones" required="" type="checkbox">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions_politic') !!}

									</label>
					  			</div>
				   					<div>* {{ trans(\Config::get('app.theme').'-app.login_register.all_fields_are_required') }}</div>
								   <button type="submit" id="valoracion-adv" class="btn-valoracion  btn btn-primary mt-2" disabled=""><div class='loader hidden'></div>{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.send') }}</button>
								   <h4 class="valoracion-h4 hidden msg_valoracion">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.error') }}</h4>
							  </div>
						  </div>
					 </form>
					</div>
					<div class="col-xs-12 col-md-4 mt-3">
						<?php $contacto = \App\Models\V5\Web_Page::where("key_web_page","contacto-departamento")->where("lang_web_page",strtoupper(\Config::get("app.locale")))->where("emp_web_page",\Config::get("app.emp"))->first(); ?>
						{!! $contacto->content_web_page !!}

					</div>
				</div>


       <!-- Inicio lotes destacados -->
<div class="lotes_destacados" style="margin-top: 80px;">
    <div class="container">
            <div class="title_lotes_destacados principal-color">
				<h3 class="title-home text-center">
					{{trans($theme.'-app.valoracion_gratuita.destacados')}}
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
        $('#valoracion-adv').attr('disabled', false)
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
