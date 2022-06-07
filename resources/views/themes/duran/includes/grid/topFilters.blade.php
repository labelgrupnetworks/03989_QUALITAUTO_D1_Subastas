<div class="auction-lots min-height">
	<div class="container-fluid">
		<div class="row" style="position: relative">
			<div class="order-views col-xs-12 no-padding">
				<div class="col-xs-12 col-lg-9 d-flex align-items-center filtros pull-right">
					{{-- Código de  Quitar filtros --}}



					@if(!empty(request('features')) && is_array(request('features')))
						@foreach(request('features') as $idFeature=> $idValueFeature)
							@if(!empty($idValueFeature))
							<span data-del_filter="#feature_{{$idValueFeature}}" class="del_filter_js filt-act cursor"><i class="fas fa-times"></i>
									@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$idValueFeature]))
										{{$featuresCount[$idFeature][$idValueFeature]["value_caracteristicas_value"]}}
									@else
										{{$idValueFeature}}
									@endif
								</span>
							@endif
						@endforeach

					@endif

					@if(!empty(request('description')))
						<span data-del_filter="#description_filter_grid" class="del_filter_js filt-act"><i class="fas fa-times"></i> {{request('description')}} </span>
					@endif

					@if(!empty(request('reference')))
						<span data-del_filter="#reference_filter_grid" class="del_filter_js  filt-act cursor" ><i class="fas fa-times"></i> {{request('reference')}}      </span>

					@endif
					@if(!empty(request('liveLots')))
						<span data-del_filter="#liveLots" class="del_filter_js  filt-act cursor" ><i class="fas fa-times"></i> {{trans(\Config::get('app.theme').'-app.lot_list.live_lots_filter')}}       </span>

					@endif
					@if(!empty(request('noAward')))
						<span data-del_filter="#no-award" class="del_filter_js  filt-act cursor" ><i class="fas fa-times"></i>  {{trans(\Config::get('app.theme').'-app.lot_list.no_award_filter')}}       </span>

					@endif
					@if(!empty(request('award')))
						<span data-del_filter="#award" class="del_filter_js  filt-act cursor" ><i class="fas fa-times"></i> {{trans(\Config::get('app.theme').'-app.lot_list.award_filter')}}       </span>

					@endif
					<?php //el filtro de tipo de subasta solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
					@if(!empty($filters["typeSub"]) && empty($auction))
						<span class="del_filter_typeSub_js   filt-act cursor" ><i class="fas fa-times"></i> {{$tipos_sub[$filters["typeSub"]]}}      </span>

					@endif

					@if(!empty($filters["category"]) && !empty($infoOrtsec))
						<span class="del_filter_category_js  filt-act cursor" ><i class="fas fa-times"></i> {{$infoOrtsec->des_ortsec0}}      </span>

					@endif

					@if(!empty($filters["section"]) && !empty($infoSec))
						<span class="del_filter_section_js  filt-act cursor" ><i class="fas fa-times"></i> {{ucfirst(mb_strtolower($infoSec->des_sec))}}      </span>

					@endif

					@if(!empty($filters["subsection"]) && !empty($infoSubSec))
						<span class="del_filter_subsection_js  filt-act cursor" ><i class="fas fa-times"></i> {{ucfirst(mb_strtolower($infoSubSec->des_subsec))}}      </span>

					@endif
					<?php //el filtro de casa de subastas solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
					@if(!empty($filters["auchouse"]) && empty($auction) && !empty($aucHouses) && !empty($aucHouses[$filters["auchouse"]]))
						<span class="del_filter_auchouse_js  filt-act cursor" ><i class="fas fa-times"></i> {{$aucHouses[$filters["auchouse"]]["rsoc_auchouse"]}}   </span>

					@endif
					{{-- FIN Quitar filtros --}}

					<span class="cantidad-res">
						{{-- Calcular numero de lotes --}}
						<?php
						$count_lots = 0;
						foreach($tipos_sub as $typeSub =>$desType) {

							$numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);

							if(empty($filters['typeSub'])){
								$count_lots += $numLots;
							}elseif($typeSub == $filters['typeSub']){
								  $count_lots = $numLots;
							}
						}
						   // ponemos puntos de millar            ?>
						{{ Tools::numberformat($count_lots) }}  {{ trans(\Config::get('app.theme').'-app.lot_list.results') }}
					</span>

					{{-- FILTRO DE SUBASTAS HISTÓRICAS --}}
						{{-- estará oculto a no ser que haya lotes en el historico --}}
							<span id="seeHistoricLots_JS" class="gridFilterHistoric hidden">
								{!! trans(\Config::get('app.theme').'-app.lot_list.see_historic_lots') !!}
							</span>



								@if(request('historic'))
									<span id="seeActiveLots_JS" class="gridFilterHistoric">
										{{ trans(\Config::get('app.theme').'-app.lot_list.return_active_lots') }}
									</span>
										{{-- solo haremos la llamada si estamos en categorias y han buscado texto   && !empty(request('description')--}}
								@elseif(empty($auction))
									<script>$(function() { showHistoricLink(); })</script>
								@endif



					{{-- FIN FILTRO DE SUBASTAS HISTÓRICAS --}}

					@if(\Config::get("app.paginacion_grid_lotes"))
						{{ $paginator->links() }}
					@endif
				</div>
				<div class="col-xs-12 col-lg-3 d-flex views widgets-auction pull-right">
					<select class="form-control" id="order_selected" >

						<option value="nameweb" @if ($filters["order"] == 'nameweb') selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
						</option>
						<option value="price_asc" @if ($filters["order"] == 'price_asc') selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
						</option>
						<option value="price_desc" @if ($filters["order"] == 'price_desc') selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
						</option>
						<option value="ref" @if ($filters["order"] == 'ref' || empty($filters["order"]) ) selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
						</option>
						<option value="hbids" @if ($filters["order"] == 'hbids') selected  @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }}
						</option>
						<option value="mbids" @if ($filters["order"] == 'mbids') selected  @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }}
						</option>
						<option value="lastbids" @if ($filters["order"] == 'lastbids') selected  @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}
						</option>
						@if(!empty($auction) && $auction->tipo_sub == 'O')
							<option value="ffin" @if ($filters["order"] == 'ffin') selected @endif >
								{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
							</option>

						@endif

						@if(!empty($auction) && ($auction->tipo_sub == 'O' || $auction->tipo_sub == 'V'  || $auction->tipo_sub == 'E' ))
						<option value="orden_asc" @if ($filters["order"] == 'orden_asc') selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b> {{ trans(\Config::get('app.theme').'-app.lot_list.ordenacion_menor') }}   </b>
						</option>
						<option value="orden_desc" @if ($filters["order"] == 'orden_desc') selected @endif >
							{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b> {{ trans(\Config::get('app.theme').'-app.lot_list.ordenacion_mayor') }}   </b>
						</option>

						@endif

					</select>
				</div>
			</div>
		</div>
	</div>
</div>
