@php
	$showCountdown = $ficha_subasta->tipo_sub == 'W' && in_array($ficha_subasta->subc_sub, ['A', 'S']) && strtotime($ficha_subasta->start) > time();
	$urlToForm = $urlToForm ?? '';
@endphp

<section class="top-filters">
	@if ($showCountdown)
		<div class="clock-element">
			<span class="clock"></span>
			<span data-countdown="{{ strtotime($ficha_subasta->start) - getdate()[0] }}"
				data-format="<?= \Tools::down_timer($ficha_subasta->start) ?>"
				data-closed="N" class="timer"></span>
		</div>
	@endif

	<form class="form-top-grid" method="get" action="{{ $urlToForm }}">
		<input type="hidden" name="no_award" value="{{ request('no_award') }}">
		<input type="hidden" name="order" value="{{ request('order') }}">
		<input type="hidden" name="lin_ortsec" value="{{ request('lin_ortsec') }}">
		<div class="form-group">
			<label class="m-0">{{ trans("$theme-app.head.search_button") }}</label>
			<div>
				<label for="input_description">{{ trans("$theme-app.lot_list.search") }}</label>
				<input id="input_description"
					placeholder="{{ trans(\Config::get('app.theme') . '-app.lot_list.search_placeholder') }}"
					name="description" type="text" class="form-control input-sm"
					value="{{ app('request')->input('description') }}">
			</div>
			<div>
				<label for="input_reference">{{ trans("$theme-app.lot_list.reference") }}</label>
				<input id="input_reference"
					placeholder="{{ trans(\Config::get('app.theme') . '-app.lot_list.reference') }}"
					name="reference" type="text" class="form-control input-sm"
					value="{{ app('request')->input('reference') }}">
			</div>

			<button type="submit" class="btn btn-custom-search">
				<i class="fa fa-search"></i>
				<div class="loader search-loader"
					style="display:none;position: absolute;top: -62.50px;right:-1px;width: 25px;height: 25px;">
				</div>
			</button>

			<button class="btn btn-filter btn-color"
				type="submit">{{ trans("$theme-app.lot_list.filter") }}</button>

		</div>
	</form>
</section>

<hr class="grid-hr">
