@php
use App\Models\V5\FgDeposito;

$cerrado = $lote_actual->cerrado_asigl0 == 'S';
$cerrado_N = $lote_actual->cerrado_asigl0 == 'N';
$hay_pujas = count($lote_actual->pujas) > 0;
$devuelto= $lote_actual->cerrado_asigl0 == 'D';
$remate = $lote_actual->remate_asigl0 =='S';
$compra = $lote_actual->compra_asigl0 == 'S';
$subasta_online = ($lote_actual->tipo_sub == 'P' || ($lote_actual->tipo_sub == 'O' &&  $lote_actual->inversa_sub != 'S'));
$subasta_inversa = ($lote_actual->tipo_sub == 'O' && $lote_actual->inversa_sub == 'S');
$subasta_venta = $lote_actual->tipo_sub == 'V';
$subasta_web = $lote_actual->tipo_sub == 'W';
$subasta_make_offer = $lote_actual->tipo_sub == 'M';

$subasta_abierta_O = $lote_actual->subabierta_sub == 'O';
$subasta_abierta_P = $lote_actual->subabierta_sub == 'P';
$retirado = $lote_actual->retirado_asigl0 !='N';
$sub_historica = $lote_actual->subc_sub == 'H';
$sub_cerrada = ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S');
$remate = $lote_actual->remate_asigl0 =='S';
$awarded = \Config::get('app.awarded');
// D = factura devuelta, R = factura pedniente de devolver
$fact_devuelta = ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R');
$fact_N = $lote_actual->fac_hces1=='N';
$start_session = strtotime("now") > strtotime($lote_actual->start_session);
$end_session = strtotime("now")  > strtotime($lote_actual->end_session);

$start_orders =strtotime("now") > strtotime($lote_actual->orders_start);
$end_orders = strtotime("now") > strtotime($lote_actual->orders_end);

$userSession = session('user');
$deposito = (new FgDeposito())->isValid($userSession['cod'] ?? null, $lote_actual->cod_sub, $lote_actual->ref_asigl0);

# listamos los recursos que se hayan puesto en la carpeta de videos para mostrarlos en la imagen principal
$resourcesList = [];
foreach(($lote_actual->videos ?? []) as $key => $video){
	$resource=["src" => $video, "format" => "VIDEO"];
	if(strtolower(substr($video, -4)) == ".gif"){
		$resource  ["format"] = "GIF";
	}
	$resourcesList[] = $resource;
}

$refLot = $lote_actual->ref_asigl0;
#si  tiene el . decimal hay que ver si se debe separar
if(strpos($refLot,'.')!==false){
	$refLot = str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);
	#si hay que recortar
}elseif(config("app.substrRef")){
	#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
	#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
	$refLot = substr($refLot, -\config::get("app.substrRef")) + 0;
}

if($subasta_web){
	$nameCountdown = "countdown";
	$timeCountdown = $lote_actual->start_session;
} else if($subasta_online){
	$nameCountdown = "countdownficha";
	$timeCountdown = $lote_actual->close_at;
}else if($subasta_inversa){
	$nameCountdown = "countdownficha";
	$timeCountdown = $lote_actual->close_at;
}else{
	$nameCountdown = "countdown";
	$timeCountdown = $lote_actual->end_session;
}

@endphp

<div class="ficha-content container">

	<div class="ficha-grid" data-tipe-sub="{{ $lote_actual->tipo_sub }}">

		<section class="ficha-auction-info">
			@include('includes.ficha.header_time')
		</section>

		<section class="ficha-previous-next">
			@include('includes.ficha.previous_next')
		</section>

		<section class="ficha-title">
			<p class="ficha-title-reference">LOTE {{$refLot}}</p>
			<h1>{!!$lote_actual->descweb_hces1 ?? $lote_actual->titulo_hces1!!}</h1>
		</section>

		<section class="ficha-image">
			@include('includes.ficha.ficha_image')
		</section>

		<section class="ficha-pujas">
			@include('includes.ficha.ficha_pujas')
		</section>

		<section class="ficha-description">
			@include('includes.ficha.ficha_description')
		</section>

		<section class="ficha-history">
			@if(($subasta_online || $subasta_inversa || ($subasta_web && $subasta_abierta_P ) || $subasta_make_offer ) && !$cerrado &&  !$retirado)
				@include('includes.ficha.history')
			@endif
		</section>

		<section class="ficha-share">
			@include('includes.ficha.share')
		</section>

		<section class="ficha-files">
			@include('includes.ficha.files')
		</section>

		@includeIf('includes.ficha.custom_sections')

	</div>

	<section class="ficha-recomendados mt-3">
		<div class="lotes_destacados" id="lotes_recomendados-content">
			<h2 class="lotes-destacados-title">{{ trans($theme.'-app.lot.recommended_lots') }}</h2>

			<div class='loader d-none'></div>
			<div id="lotes_recomendados" class="owl-theme owl-carousel"></div>

			<div class="m-0 pl-10" id="navs-arrows">
				<div class="owl-nav">
					<div class="owl-prev"><i class="fas fa-chevron-left"></i></div>
					<div class="owl-next"><i class="fas fa-chevron-right"></i></div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-4">
		<x-contact-section>
			<x-slot:topAddress>
				<h2 class="contact-address-subtitle">{{ trans("$theme-app.pages.contact_subtitle") }}</h2>
				<h3 class="contact-address-title">{{ trans("$theme-app.pages.contact_title") }}</h3>
			</x-slot:topAddress>

		</x-contact-section>
	</section>
</div>

@php
$replace = array(
    'emp' => Config::get('app.emp') ,
    'sec_hces1' => $lote_actual->sec_hces1,
    'num_hces1' => $lote_actual->num_hces1,
	'lin_hces1' => $lote_actual->lin_hces1,
    'lang' => Config::get('app.language_complete')[''.Config::get('app.locale').''] ,
);
$lang = Config::get('app.locale');
@endphp


<script>
const replace = @json($replace);
const key ="lotes_recomendados";
const isContExtra = @json(!empty($lote_actual->contextra_hces1));

$(function() {

	ajax_newcarousel(key, replace, '{{ $lang }}', {
		autoplay: false,
		arrows: true,
		dots: false,
		slidesToShow: 5,
		responsive: homeBannersOptions,
		prevArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-prev" viewBox="0 0 512 512" fill="currentColor"><path d="M177.5 414c-8.8 3.8-19 2-26-4.6l-144-136C2.7 268.9 0 262.6 0 256s2.7-12.9 7.5-17.4l144-136c7-6.6 17.2-8.4 26-4.6s14.5 12.5 14.5 22l0 72 288 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32l-288 0 0 72c0 9.6-5.7 18.2-14.5 22z"/></svg>',
		nextArrow: '<svg xmlns="http://www.w3.org/2000/svg" class="slick-next" viewBox="0 0 512 512" fill="currentColor"><path d="M334.5 414c8.8 3.8 19 2 26-4.6l144-136c4.8-4.5 7.5-10.8 7.5-17.4s-2.7-12.9-7.5-17.4l-144-136c-7-6.6-17.2-8.4-26-4.6s-14.5 12.5-14.5 22l0 72L32 192c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l288 0 0 72c0 9.6 5.7 18.2 14.5 22z"/></svg>',
    });

	//Mostramos la fecha
    $("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));

    if (isContExtra) {
		image360Init();
    }
});
</script>

@include('includes.ficha.modals_ficha')
