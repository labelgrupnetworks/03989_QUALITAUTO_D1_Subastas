<div class="sales-auctions">

    <div class="sales-header-wrapper">
        <div class="sales-auctions_header">
            <p>Fecha</p>
            <p>Subasta</p>
            <p>Nº Lotes</p>
            <p>Total Precio Salida</p>
            <p>Total Estimado</p>
            <p>Total precio Actual</p>
        </div>
    </div>

    @foreach ($subastas->keys() as $cod_sub)
        <div class="sales-auction-wrapper" data-sub="{{ $cod_sub }}">
            <div class="sales-auction">
                <p>
                    {{ date('d/m/Y', strtotime($subastas[$cod_sub]->first()->start)) }}
                </p>
                <p>{{ $subastas[$cod_sub]->first()->des_sub }}</p>
                <p>{{ $statistics['auction'][$cod_sub]['consigned_lots'] }}</p>
                <p class="js-divisa" value="{{ $statistics['auction'][$cod_sub]['starting_price'] }}">
                    {!! $currency->getPriceSymbol(2, $statistics['auction'][$cod_sub]['starting_price']) !!}
                </p>
                <p class="js-divisa" value="{{ $statistics['auction'][$cod_sub]['estimate_price'] }}">
                    {!! $currency->getPriceSymbol(2, $statistics['auction'][$cod_sub]['estimate_price']) !!}
                </p>
                <p class="js-divisa" value="{{ $statistics['auction'][$cod_sub]['actual_price'] }}">
                    {!! $currency->getPriceSymbol(2, $statistics['auction'][$cod_sub]['actual_price']) !!}
                </p>
                <div class="actions">
                    <a class="btn btn-lb btn-lb-outline" data-toggle="tab" href="#auction-details-{{ $cod_sub }}" role="tab"
                        aria-controls="settings">Ver detalle</a>
                </div>
            </div>
        </div>
    @endforeach

</div>


<section class="tab-content" id="auction-details">

    @foreach ($subastas as $cod_sub => $lotes)
        <div class="tab-pane" id="auction-details-{{ $cod_sub }}" role="tabpanel">
			<h4>{{ $lotes->first()->des_sub }}</h4>

			<div class="sales-lots-wrapper">
				<div class="sales-lots-header">
					<p>Img</p>
					<p>Lote</p>
					<p>Descripción</p>
					<p>Precio Salida</p>
					<p>Precio Actual</p>
					<p>Incremento</p>
					<p>Pujas / Pujadores</p>
				</div>

				@foreach ($lotes as $lote)
					<div class="sales-lot">
						<div class="">
							<img src="" alt="">
						</div>
						<div class="">
							<p>{{ $lote->ref_asigl0 }}</p>
						</div>
						<div class="">
							<p>{!! $lote->descweb_hces1 !!}</p>
						</div>
						<div class="">
							<p class="js-divisa" value="{{ $lote->impsalhces_asigl0 }}">
								{!! $currency->getPriceSymbol(2, $lote->impsalhces_asigl0) !!}
							</p>
						</div>
						<div class="">
							<p class="js-divisa" value="{{ max($lote->implic_hces1, $lote->impsalhces_asigl0) }}">
								{!! $currency->getPriceSymbol(2, max($lote->implic_hces1, $lote->impsalhces_asigl0)) !!}
							</p>
						</div>

						<div class="">
							<p class="">
								{{ ceil(($lote->implic_hces1 / max($lote->impsalhces_asigl0, 1)) * 100) }}
							</p>
						</div>

						<div class="">
							<p>{{ $lote->bids }}</p>
							<p>{{ $lote->licits }}</p>
						</div>
					</div>
				@endforeach
			</div>

        </div>
    @endforeach

</section>
