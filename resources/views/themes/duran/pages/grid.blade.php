@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
@php
#las subastas Online y venta tienen filtros de orden
if(!empty($auction) && count($bread)>0){
	#no hay enlace a info
	$bread[0]["url"]="";
	/*
	if($auction->tipo_sub=="O" ){
		$bread[0]["url"] .="?order=orden_asc";
	}elseif($auction->tipo_sub=="V" ){
		$bread[0]["url"] .="?order=orden_desc";
	}
	*/
}

@endphp
<div class="container container-title">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center">
			@include('includes.breadcrumb')

				<div class="row">
					<div class="col-xs-12 d-flex align-items-end justify-content-space-between titlePage-wrapper">
						<h1 class="titlePage-custom color-letter text-center">

						@if(!empty($infoSubSec))
							{{$infoSubSec->des_subsec}}
						@elseif(!empty($infoSec))
							{{$infoSec->des_sec}}
						@elseif(!empty($infoOrtsec))
							{{$infoOrtsec->des_ortsec0}}
						@endif

							@if(!empty($auction))
								{{$auction->des_sub}}
							@else
								@if(request("historic"))
									{{$seo_data->h1_seo}}
								@else

									{{trans($theme.'-app.lot_list.available_lots')}}
								@endif
							@endif

						</h1>
						@if(!empty($auction) && $auction->tipo_sub !="V" && $auction->subc_sub !="H" )
							<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot pt-1 c_bordered" data-ref="{{ Routing::translateSeo('pagina')."info-pujas-online"  }}?modal=1" data-title="{{ trans($theme.'-app.lot.title_info_pujas') }}">  {{ trans($theme.'-app.lot_list.como_pujar') }} <i class="fas fa-info-circle"></i></a>
						@endif
					</div>
				</div>

		</div>
	</div>
</div>
		@include('content.grid')
@stop

