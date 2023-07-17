@php
    $img = Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1);
	$euro = trans("$theme-app.subastas.euros");

@endphp

<div class="col">
    <div role="article" class="card lot-card">

		<a href="{{$url}}" class="stretched-link"></a>
		<div class="lot-card-imageblock">
            <div class="lot-card-header">
                <span>{{ $titulo }}</span>
            </div>

            <img src="{{ $img }}" alt="{{ $titulo }}" class="card-img-top">
        </div>

		<div class="card-body lot-card-body">
            <p class="lot-title">{!! $lot->descweb_hces1 !!}</p>

			<div class="lot-data">
				<div class="prominent-lot-prices mt-2 row row-cols-auto gy-1">
					<p>
						{{ trans("$theme-app.lot_list.lot-price") . Tools::moneyFormat($lot->impsalhces_asigl0, $euro, 0) }}
					</p>
					<p>
						{{ trans("$theme-app.lot_list.lot-selled-price") . " " . Tools::moneyFormat($lot->max_puja, $euro, 0) }}
					</p>
				</div>
			</div>
		</div>

    </div>
</div>
