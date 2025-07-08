@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.css') }}" />

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
@if(strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'O' || strtoupper($data['subasta_info']->lote_actual->tipo_sub) == 'P' || $data['subasta_info']->lote_actual->subabierta_sub == 'P')

<script src="{{ Tools::urlAssetsCache('vendor/tiempo-real/tr_main.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>

@endif
<script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-print/jQuery.print.js') }}"></script>

<?php
use App\Models\V5\AucSessionsFiles;
use App\Models\V5\FgRepresentados;

//gardamos el código de licitador para saber is esta logeado o no y mostrar mensaje de que debe logearse
$user = "";
$cod_licit ="null";

$auctionBasesFile = AucSessionsFiles::whereAuctionBases($data['subasta_info']->lote_actual->cod_sub)->get();
$auctionBasesFile = $auctionBasesFile->where('locale', config('app.locale'))->first() ?? $auctionBasesFile->first();
$representedArray = [];

if(Session::has('user')){
	$user = Session::get('user');
	$cod_sub = $data['subasta_info']->lote_actual->cod_sub;
	$ref = $data['subasta_info']->lote_actual->ref_asigl0;
	$cod_licit = $data['js_item']['user']['cod_licit'] ?? "null";
	$representedArray = FgRepresentados::getRepresentedToSelect(Session::get('user.cod'));
}
?>

<script>
var auction_info = $.parseJSON('<?php  echo str_replace("\u0022","\\\\\"",json_encode($data["js_item"],JSON_HEX_QUOT)); ?>');

var cod_sub = '{{$data['subasta_info']->lote_actual->cod_sub}}';
var ref = '{{$data['subasta_info']->lote_actual->ref_asigl0}}';
var imp = '{{$data['subasta_info']->lote_actual->impsalhces_asigl0}}';
var cod_licit = <?=$cod_licit?>;
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
routing.favorites	 = '{{ Config::get('app.url')."/api-ajax/favorites" }}';

$(document).ready(function() {
	$('.add_bid').removeClass('loading');
});

</script>
<?php
	$lote_actual = $data['subasta_info']->lote_actual;

    if(!empty($lote_actual)){

		$typeSub = $lote_actual->tipo_sub;
		$url_subasta=\Tools::url_info_auction($lote_actual->cod_sub,$lote_actual->title_url_subasta);

		$bread = array();
        $bread[] = array("url" => $url_subasta, "name" =>$lote_actual->title_url_subasta  );
        if(!empty($data['seo']->meta_title)){
            $bread[] = array( "name" => $data['seo']->meta_title );
        }else{
            $bread[] = array( "name" => $lote_actual->titulo_hces1 );
        }
    }


?>

<section class="all-aution-title title-content pb-1">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 h1-titl text-center">
				@if(!empty($typeSub) && $typeSub == 'O')
				<h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
				@elseif(!empty($typeSub) && $typeSub == 'V')
				<h1 class="page-title mb-0">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale') }}</h1>
				@endif
			</div>
		</div>
	</div>
</section>

<div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 color-letter">
                    @include('includes.breadcrumb_before_after')
                </div>
            </div>
        </div>
    @include('content.ficha')

<script>
var menuItems = $('.menu-principal-content').find('li')

menuItems.each(function () {
    $(this).find('a').removeClass('color-brand')
})

menuItems = $('.nav-item');

menuItems.each(function () {

    if (this.innerHTML == "{!! trans(\Config::get('app.theme').'-app.foot.online_auction')!!}" && "{!!$typeSub !!}" == "O") {
        $(this).addClass('color-brand');
    }
    else if (this.innerHTML == "{!! trans(\Config::get('app.theme').'-app.foot.direct_sale')!!}"  && "{!!$typeSub !!}" == "V") {
        $(this).addClass('color-brand');
    }
})
</script>
@stop
