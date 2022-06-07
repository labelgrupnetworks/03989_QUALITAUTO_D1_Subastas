
	<?php


?>
	<div class="filters-auction-content d-flex  flex-column bloque-fijo-grid">
		<form id="form_lotlist" class="color-text" method="get" action="{{ $url }}">
			{{-- oldpage es la página en la que estabamos antes de ir a la ficha, al volver debemos ir a ella --}}
			<input type="hidden" name="oldpage" id="oldpage" value="{{request('oldpage')}}"   />
			<input type="hidden" name="oldlot" id="oldlot" value="{{request('oldlot')}}"   />
			<input type="hidden" name="order" id="hidden_order" value="{{request('order')}}"   />
			<input type="hidden" name="historic" id="hidden_historic" value="{{request('historic')}}"   />

		<div class="form-group">
			<div class="filters-auction-title d-flex align-items-center justify-content-space-between">
					<span>
						{{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}
						<a class="js-filter hidden-md hidden-lg" href="javascript:$('#js-filters-toogle-container, #js-filters-toogle-container2').toggle('slow')"><i class="fa fa-filter" aria-hidden="true"></i></a>
					</span>

			</div>
			<div id="js-filters-toogle-container">
			<div class=" search-control-input">
				<input  name="description" id="description_filter_grid" class="form-control input-sm search-input search-input_js" type="text" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}" value="{{request('description')}}" >
				<button id="search-btn" type="button" class="button-principal button-search search-btn_js"><i class="fas fa-search"></i></button>
			</div>
			@if(!empty($codSub) && !empty($refSession))
				<div class=" search-control-input">
					<input  name="reference" id="reference_filter_grid" class="form-control input-sm search-input search-input_js" type="text" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" value="{{request('reference')}}" >
					<button id="search-btn" type="button" class="button-principal button-search search-btn_js"><i class="fas fa-search"></i></button>
				</div>
			@endif


			<div class="filters-auction-texts">
				@include('includes.grid.categories_list')

			</div>
			</div>


			@include('includes.grid.features_list')

			@if(!empty($auction) && ( $auction->subc_sub == 'H' || ($auction->tipo_sub == 'W' && strtotime($auction->end) < time()  ) ) )
				<?php #Filtros  de estado del lote ?>
				<div class="filters-auction-texts ">
					<span class="titulo-filtro">{{ trans(\Config::get('app.theme').'-app.lot_list.lots_status') }}</span>


					<div>
						<input type="checkbox" class=" js-check-award" name="noAward" id="no-award" value="1" <?=  !empty(request('noAward'))? 'checked="checked"' : '' ?>  />
						<label for="no-award" class="radio-label">
							{{trans(\Config::get('app.theme').'-app.lot_list.no_award_filter')}}
						</label>
					</div>
					<div>
						<input type="checkbox"   class=" js-check-award" name="award" id="award" value="1" <?=  !empty(request('award'))? 'checked="checked"' : '' ?>  />
						<label for="award" class="radio-label">
							{{trans(\Config::get('app.theme').'-app.lot_list.award_filter')}}
						</label>
					</div>
					<div>
						<input type="checkbox"   class=" js-check-award" name="all" id="all" value="1" <?=  (empty(request('award')) && empty(request('noAward')) )? 'checked="checked"' : '' ?>  />
						<label for="award" class="radio-label">
							{{trans(\Config::get('app.theme').'-app.lot_list.ver_todos')}}
						</label>
					</div>

				</div>
			@endif
			{{-- ocultanmos el filtro de referencia por que de momento no lo queremos
				<div class="filters-auction-texts">
					<div class="filters-auction-divider-small"></div>
					<label class="filters-auction-label" for="input_reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }} (32)</label>
					<input id="input_reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm filter-auction-input" value="{{ app('request')->input('reference') }}">
				</div>
				<div class="filters-auction-divider-big"></div>
				<button class="btn btn-filter color-letter" type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}</button>
			--}}
		</div>
	</form>
	</div>









