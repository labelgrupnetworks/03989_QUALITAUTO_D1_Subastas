<?php //dd($data); ?>
<div class="carrousel-lots-wrapper">
	<div class="sizes" style="position: absolute">
		<span class="visible-xs-inline-block" id="size-col-xs"></span>
		<span class="visible-sm-inline-block" id="size-col-sm"></span>
		<span class="hidden-xs hidden-sm" id="size-col-md"></span>
	</div>


	<div class="row carrousel-lots-options">
		<div class="carrousel-lots-check d-flex justify-content-end">
			<input class="check-input" type="checkbox" value="" id="j-followCarrousel" checked>
			<label class="check-label" for="j-followCarrousel">
				{{ trans($theme.'-app.sheet_tr.follow') }}
			</label>
		</div>
	</div>

	<div class="row carrousel-lots-container">

		<div class="col-xs-2 col-sm-1 h-100 no-padding d-flex align-items-center">
			<div class="arrow-carrousel prev-arrow-carrousel">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
			</div>
		</div>
		<div class="col-xs-8 col-sm-10 h-100 lots-carrousel" style="">
			@php
			$carrouselLotes = \App\Models\V5\FgAsigl0::JoinFghces1Asigl0()
			->JoinSessionAsigl0()
			->select('NUM_HCES1', 'LIN_HCES1', 'REF_HCES1', 'WEBFRIEND_HCES1', 'TITULO_HCES1',
			'IMPSALHCES_ASIGL0', 'REF_ASIGL0', 'CERRADO_ASIGL0')
			->where('SUB_ASIGL0', $data['subasta_info']->cod_sub)
			->where('auc."reference"', $data['subasta_info']->reference)
			->where('RETIRADO_ASIGL0', 'N')
			->where('OCULTO_ASIGL0', 'N')
			->get();
			@endphp

			@foreach ($carrouselLotes as $lote)
			@php
			$img = \Tools::url_img('lote_medium', $lote->num_hces1, $lote->lin_hces1);
			$url = \Tools::url_lot($data['subasta_info']->cod_sub, $data['subasta_info']->lote_actual->id_auc_sessions,
			$data['subasta_info']->lote_actual->id_auc_sessions, $lote->ref_asigl0, $lote->num_hces1,
			$lote->webfriend_hces1, $lote->titulo_hces1);
			@endphp
			{{-- condicion para llamada ajax. por ahora se muestra siempre @if($lote->cerrado_asigl0 != 'N' || !Session::has('user')) j-active-info @endif --}}
			<div class="lots j-active-info h-100 col-xs-12 col-sm-6 col-md-3" data-ref_asigl0="{{$lote->ref_asigl0}}"
			data-cod_sub="{{$data['subasta_info']->cod_sub}}" data-background-image="url({{$img}})" data-order="{{$loop->index}}">

				{{-- check de favoritos, desactivado por el momento
				@if(Session::has('user'))
				<div class="carrousel-lots-check d-flex justify-content-center j-addFavoriteContainer-{{$lote->ref_asigl0}}" style="@if($lote->cerrado_asigl0 != 'N') display: none; @endif ">
					<input class="check-input" type="checkbox" value="" id="j-addFavoriteCarrousel-{{$lote->ref_asigl0}}">
					<label class="check-label" for="j-addFavoriteCarrousel-{{$lote->ref_asigl0}}">
						{{ trans($theme.'-app.sheet_tr.warn') }}
					</label>
				</div>
				@endif
				--}}

				<div class="lots-content">{{$lote->ref_hces1}}</div>

				<div class="j-lots-data justify-content-center align-items-center">
					<div class="loader" style="display: none"></div>
					<div class="j-lots-data-load h-100 d-flex flex-column justify-content-center align-items-center">
						<p class="j-lots-price m-0">{{ trans($theme.'-app.lot.lot-price') }}:
							<span>{{ $lote->impsalhces_asigl0 ?? 0 }} €</span></p>
						<p class="j-lots-state">{{ trans(Config::get('app.theme').'-app.sheet_tr.not_awarded') }}</p>
						@if(!empty($url))
						<a class="btn btn-info j-btn-custom-add lots-btn" href="{{$url}}" target="_blank">
							<span class="j-text-add"
								style="display: none">{{ trans($theme.'-app.sheet_tr.buy') }}</span>
							<span class="j-text-view">{{ trans($theme.'-app.lot.ver') }}</span>
						</a>
						@endif
					</div>
				</div>

			</div>
			@endforeach

		</div>
		<div class="col-xs-2 col-sm-1 h-100 d-flex align-items-center no-padding justify-content-end">
			<div class="arrow-carrousel next-arrow-carrousel">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</div>
		</div>
	</div>

</div>
