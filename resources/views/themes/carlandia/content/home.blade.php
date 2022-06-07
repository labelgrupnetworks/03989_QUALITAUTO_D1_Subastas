@php
$caracteristicaEstado = 21;
$categories = \App\Models\V5\FgOrtsec0::getAllFgOrtsec0()->get();
$subCategories = \App\Models\V5\FxSec::joinFgOrtsecFxSec()
    ->select('cod_sec', 'des_sec', 'lin_ortsec1')
    ->whereIn('lin_ortsec1', $categories->pluck('lin_ortsec0'))
    ->get();
$estados = \App\Models\V5\FgCaracteristicas_Value::where('idcar_caracteristicas_value', $caracteristicaEstado)->pluck('value_caracteristicas_value', 'id_caracteristicas_value');
foreach ($estados as $key => $value) {
	if($value == 'ocasion'){
		$estados[$key] = 'ocasión';
	}
}

$gridCarOptions = "{
  'dots': false,
 'arrows': false,
 'rows': 8,
  'slidesPerRow': 7,
  'responsive': [
    {
      'breakpoint': 992,
      'settings': {
     'slidesPerRow': 5,
      }
    },
    {
      'breakpoint': 768,
      'settings': {
        'slidesPerRow': 4,
      }
    },
    {
      'breakpoint': 576,
      'settings': {
        'slidesPerRow': 2,
      }
    }
  ]
 }";


$gridMarcaOptions2 = "{
	'dots': true,
	'arrows': false,
	'slidesToShow': 5,
	'slidesToScroll': 5,
	'autoplay': false,
  	'autoplaySpeed': 3000,
	'responsive': [
   {
    'breakpoint': 768,
    'settings': {
     'slidesToShow': 2,
    }
   }
  ]
}";

$gridMarcaOptions = "{
 'dots': false,
 'arrows': false,
 'rows': 25,
 'slidesPerRow': 5,
  'responsive': [
   {
    'breakpoint': 768,
    'settings': {
     'rows': 50,
     'slidesPerRow': 2,
    }
   }
  ]
 }";

@endphp

<div class="home-banner">
    {!! \BannerLib::bannersPorKey('home-superior', 'home-top-banner', ['arrows' => false, 'dots' => true]) !!}
</div>


<div class="container search-container pt-1 pb-2">

    <div style="margin-left: 3px;">
		<span class="home-title-text-last-line text-white">{{ trans("$theme-app.home.buscador-title") }}</span>
	</div>

	<form action="{{ route('allCategories') }}">
	<div class="row d-flex align-items-center flex-wrap">
		<div class="col-xs-12 col-sm-6 col-md-3 ">
			{!! FormLib::Select('category', false, '0', $categories->pluck('des_ortsec0', 'lin_ortsec0'), "", "Marca", $void_value = true) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3 ">
			{!! FormLib::Select('section', false, '0', [], "", "Modelo", $void_value = true) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3 ">
			{!! FormLib::Select('features[21]', false, '0', $estados, "", "Estado", $void_value = true) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3  text-center">
			<button id="buscadorHomeEvent_JS" class="button-principal submitButton home-button-padding">{{ trans("$theme-app.home.buscar") }}</button>
		</div>
	</div>
	</form>

</div>

<div class="container white-container home-container-content">

    {{-- BANNER DEL GRID DE COCHES --}}
    <div class="home-container-row">
        <div class="home-grid-design">
            <div class="home-grid-car">
                {!! \BannerLib::bannersPorKey('banner-carrocerias', 'home-banner-carrocerias', $gridCarOptions) !!}
            </div>
        </div>
    </div>

	<div class="home-banner-pers d-flex mb-3">
		<div class="banner-img">
			<img class="img-responsive" src="/themes/{{$theme}}/assets/img/venta.jpg" alt="Venta Directa">
		</div>
		<div class="banner-content px-1">
			<h2 class="banner-title">COCHES EN VENTA DIRECTA:</h2>
			<h2 class="banner-subtitle">¡<span>HAZ TU CONTRAOFERTA</span>!</h2>
			<p>En Carlandia te ofrecemos una amplía selección de vehículos nuevos, Km 0, seminuevos, de segunda mano y de ocasión para que encuentres el que mejor se ajusta a tus preferencias, necesidades y, sobre todo, a tu presupuesto.</p>
			<p>Y para que puedas encontrar el vehículo que mejor se adapta a tu presupuesto, en Carlandia podrás realizar contraofertas sobre todos los vehículos ofertados en nuestra plataforma, teniendo así la posibilidad de adquirir el vehículo que buscas por un precio único e irrepetible.</p>
			<p>¡No lo dudes más y aprovéchate de las ventajas de Carlandia!</p>
			<div><a id="stockVentadirectaEvent_JS" href="es/subastas?typeSub=V" class="button-principal submitButton home-button-padding">Ver Stock</a></div>
		</div>
	</div>

	<div class="home-banner-pers home-baner-auctions d-flex">
		<div class="banner-content px-1">
			<h2 class="banner-title">COCHES A SUBASTA:</h2>
			<h2 class="banner-subtitle">¡<span>INTRODUCE TU PUJA</span>!</h2>
			<p>Carlandia es la única plataforma de compraventa de vehículos en España que te permite adquirir un vehículo de una forma innovadora: ¡mediante puja!</p>
			<p>Te ofrecemos la posibilidad de acceder a subastas online que te permiten una oportunidad única de adquirir el vehículo que buscas a un precio más bajo que en otros canales tradicionales.</p>
			<p>¡Descubre ya nuestro innovador sistema de pujas y subastas de coches y haz tu puja!</p>
			<div><a id="stockSubastaEvent_JS" href="es/subastas?typeSub=O" class="button-principal submitButton home-button-padding">Ver Stock</a></div>
		</div>
		<div class="banner-img">
			<img class="img-responsive" src="/themes/{{$theme}}/assets/img/subasta.jpg?a=2" alt="Coches a subasta">
		</div>
	</div>



    {{-- APARTADO DE VENTAJAS --}}
    <div class="banner-ventajas">
        <div class="row home-container-row ">

            <div class="padding-right-15px padding-left-15px color-text-white">
                <div class="col-xs-12">
                    <h3 class="home-title-text-last-line home-title-text-border pt-1">{{ trans("$theme-app.home.ventajas-title") }}</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 home-ventaja-padding">
                    <img src="/themes/{{ $theme }}/assets/img/ventajas-1.png" alt="" class="home-ventaja-image">
                    <p class="text-center">{!! trans("$theme-app.home.ventaja-1") !!}</p>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 home-ventaja-padding">
					<img src="/themes/{{ $theme }}/assets/img/ventajas-2.png" alt="" class="home-ventaja-image">
					<p class="text-center">{!! trans("$theme-app.home.ventaja-2") !!}</p>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 home-ventaja-padding">
					<img src="/themes/{{ $theme }}/assets/img/ventajas-3.png" alt="" class="home-ventaja-image">
					<p class="text-center">{!! trans("$theme-app.home.ventaja-3") !!}</p>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 home-ventaja-padding">
					<img src="/themes/{{ $theme }}/assets/img/ventajas-4.png" alt="" class="home-ventaja-image">
					<p class="text-center">{!! trans("$theme-app.home.ventaja-4") !!}</p>
                </div>
            </div>

        </div>
    </div>

    {{-- BANNER DE MARCAS --}}
    <div class="home-container-row">
        <div class="row">
            <div class="padding-right-15px padding-left-15px">
                <div class="col-xs-12">
                    <h3 class="home-title-text-last-line home-title-text-border">{{ trans("$theme-app.home.marcas-title") }}</h3>
                </div>
            </div>
        </div>
        <div class="banner-marcas-container mt-2">
            {!! \BannerLib::bannersPorKey('banner-marcas', 'home-banner-marcas', $gridMarcaOptions) !!}
        </div>
        <div class="row">
            <div class="col-xs-12 text-center mt-4">
                <a href="/es/subastas" class="button-principal submitButton home-marca-button ">{{ trans("$theme-app.home.ver-stock") }}</a>
            </div>
        </div>
    </div>


</div>


<script>
	$(document).on('ready', function () {
		$(".home-banner-marcas a").on('click',function(){
			brand =  $(this).attr("href").split('/')[1].replace("subastas-","");
			ga('send','event','ACCIONES NO LEADS HOME','Botón marca',brand);



		});
	});
    const subCategories = @json($subCategories);
</script>
