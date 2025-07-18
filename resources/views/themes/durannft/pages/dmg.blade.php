@extends('layouts.default')




	@php
		$page  = (new App\Services\Content\PageService())->getPage("dmg");

		$seo= new \stdClass();
		if(!empty($page->webnoindex_web_page) && $page->webnoindex_web_page == 1){
			$seo->noindex_follow = true;
		}else{
			$seo->noindex_follow = false;
		}

		$seo->meta_title = $page->webmetat_web_page;
		$seo->meta_description = $page->webmetad_web_page;

	@endphp






@section('title')
	{{ $page->name_web_page }}
@stop

		@section('content')

<?php
$bread[] = array("name" =>$page->name_web_page  );
?>
<div id="pagina-{{ $page->id_web_page }}" class="contenido contenido-web container-fluid">
	<div class="container">
		<div class="main">
			{!! $page->content_web_page !!}
			<br><br>
		</div>
	</div>
</div>

{{-- Subastas --}}
@php
#	$subastaController = new App\Http\Controllers\SubastaController();
#	$auctionsList = $subastaController->listaSubastasSesiones('S', 'V');
	$subastaObj        = new App\Models\Subasta();
	$auctionsList = $subastaObj->auctionList('S', 'V');
	#ordenamos al reves
	krsort($auctionsList);



@endphp


<div class="all-auctions color-letter">
	<div class="container">
		<div class="row">
				<div class="col-xs-12">
					@foreach ($auctionsList as  $subasta)
						@if($subasta->cod_sub != "CATALOGO")
								<?php
									$fgasigl0 = new App\Models\V5\FgAsigl0;
									$cuantos = $fgasigl0->select("count(ref_asigl0) as cuantos")->where("sub_asigl0",$subasta->cod_sub)->ActiveLotAsigl0()->first();

									$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
								?>
							<div class="col-xs-12 col-sm-6 col-md-4">
								@include('includes.auction')
							</div>
						@endif
					@endforeach
				</div>
		</div>

	</div>
</div>

@stop

