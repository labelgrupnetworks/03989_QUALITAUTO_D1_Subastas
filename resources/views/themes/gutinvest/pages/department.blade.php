@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
@php
	$bread[] = array("name" =>trans(\Config::get('app.theme').'-app.foot.departments'),"url" =>\Routing::translateSeo('departamentos')  );
	$static_page = \App\Models\V5\Web_Page::select('content_web_page')->where([ ['key_web_page', $ortsec->key_ortsec0], ['lang_web_page', mb_strtoupper(config('app.locale')) ]])->first();
@endphp
{{--
<section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titlePage"> {{$ortsec->des_ortsec0 }}</h1>
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>
--}}
<div
	style="width: 100% ;height: 360px;background: url('/themes/{{$theme}}/assets/img/departamentos/department{{$ortsec->lin_ortsec0}}_large.jpg');background-repeat:no-repeat;background-position: center;background-size: cover;">
</div>
<section class="info-subasta">

    {{--<div class="info-subasta-image" style="background: url(/img/load/subasta_large/AUCTION_{{ $ficha_subasta->emp_sub }}_{{$ficha_subasta->cod_sub }}.jpg) no-repeat center #234575;background-size: cover;"></div>--}}
    <div class="info-subasta-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 resultok">
					@php
						$titles = explode('-', $ortsec->meta_titulo_ortsec0);
					@endphp
					<h1>{{ $titles[0] ?? '' }}</h1>
					<h2>{{ $titles[1] ?? '' }}</h2>
				</div>
				<?php
				$metas = json_decode($ortsec->meta_contenido_ortsec0);
				$lineas = intval(count($metas ?? 0) / 3) + 1;

				$metaFunction = function ($metas) {
					$metasForSearch = [];
					foreach ($metas as $key => $meta) {
						$metasForSearch[$key] = str_replace(' ', '+', mb_strtolower($meta));
					}
					return $metasForSearch;
				};

				$metasForSearch = $metaFunction($metas);

				?>
				<div class="col-xs-12">
					<div class="row">
						@for ($i = 0; $i < 3; $i++)
						<div class="col-xs-12 col-md-4">
							@for ($l = $i * $lineas; $l < ($lineas * ($i + 1)); $l++)

							<h5 style="text-transform: capitalize;"> <a href="{{ route('busqueda'). '?texto=' . ($metasForSearch[$l] ?? '')}}">{{ $metas[$l] ?? ''}}</a></h5>

							@endfor
						</div>
						@endfor
					</div>
				</div>
            </div>
        </div>
    </div>
</section>

@if (!empty($static_page))
<section class="deparment-static-page">
	<div class="container">
		<div class="row col-xs-12">
			{!! $static_page->content_web_page !!}
		</div>
	</div>
</section>
@endif


<div class="container">
	<div class="row">
		<div class="col-lg-12">

			@foreach($especialistas as $esp)

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

			<div class="col-lg-6" style="margin-top:20px;min-height: 172px;">
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

	<!-- Inicio lotes destacados -->
	<div class="lotes_destacados" style="margin-top: 80px;">
		<div class="container">
			<div class="title_lotes_destacados principal-color">
				<h3 class="title-home text-center">
					{{ trans(\Config::get('app.theme').'-app.lot_list.lotes_destacados') }}
				</h3>
			</div>
			<div class="loader"></div>
			<div class="row lotes-home-margin" id="lotes_departamentos">

			</div>
		</div>
	</div>


</div>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
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
		  'departamento' => $ortsec->lin_ortsec0,
		  'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
		  'emp' => Config::get('app.emp'),
		  'gemp' => Config::get('app.gemp')
        );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";
	ajax_lot_grid(key,replace);
</script>

@stop
