	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-7">
					<div class="bread">
						@include('includes.bread')
					</div>
				</div>




			</div>

		</div>
	</div>
	@if(\Config::get("app.exchange"))
	<div class="col-xs-12 text-right">
		{{ trans(\Config::get('app.theme').'-app.lot.foreignCurrencies') }}
		<select id="currencyExchange">
			@foreach($data['divisas'] as $divisa)

			<?php //quieren que salgan los dolares por defecto (sin no hay nada o hay euros  ?>
			<option value='{{ $divisa->cod_div }}' <?=($data['js_item']['subasta']['cod_div_cli']==$divisa->cod_div ||
				($divisa->cod_div == 'USD' && ($data['js_item']['subasta']['cod_div_cli'] == 'EUR' ||
				$data['js_item']['subasta']['cod_div_cli'] == '' )))? 'selected="selected"' : '' ?>>
				{{ $divisa->cod_div }}
			</option>

			@endforeach
		</select>

	</div>
	@endif


