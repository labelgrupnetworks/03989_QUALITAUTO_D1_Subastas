<div class="panel-lot-wrapper">
    <div class="panel-lot sales-lot">
        <div class="panel-lot_img">
            <img class="img-responsive" src="{{ Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1) }}"
                alt="" loading="lazy">
        </div>
        <div class="panel-lot_ref">
            <p>
                <span class="panel-lot_label">{{ trans("$theme-app.user_panel.lot") }}</span>
                {{ $lot->ref_asigl0 }}
            </p>
        </div>
        <div class="panel-lot_desc">
            <p>
				{!! $lot->descweb_hces1 ?? strip_tags($lot->desc_hces1) !!}
			</p>
        </div>
        <div class="panel-lot_label label-price-salida">
            <span>{{ trans("$theme-app.user_panel.starting_price_min") }}</span>
        </div>
        <div class="panel-lot_price-salida">
            <p class="js-divisa" value="{{ $lot->impsalhces_asigl0 }}" data-small-format="0,0"></p>
        </div>
        <div class="panel-lot_label label-price-actual">
            <span>
				@if($finish)
					{{ trans("$theme-app.user_panel.awarded") }}
				@else
    	            {{ trans("$theme-app.user_panel.actual_price_min") }}
				@endif
            </span>
        </div>
        <div class="panel-lot_actual-price">
            <p class="js-divisa" value="{{ $lot->implic_hces1 }}" data-small-format="0,0"></p>
        </div>

        <div class="panel-lot_label label-increment">
            <span>
                {{ trans("$theme-app.user_panel.increase") }}
            </span>
        </div>
        <div class="panel-lot_increment">
            <p>
                {{ ceil(($lot->implic_hces1 / max($lot->impsalhces_asigl0, 1)) * 100) }}
                <span> %</span>
            </p>

        </div>

        <div class="panel-lot_bids">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 491.54 491.54" width="15px" height="15px"
                    xml:space="preserve">
                    <path fill="currentColor"
                        d="M282.58 387.484H36.909C16.534 387.484 0 404.016 0 424.394v67.115h319.488v-67.115c0-20.378-16.533-36.91-36.908-36.91m202.336 5.206L260.66 168.433l43.315-43.238c7.142 6.298 18.125 5.99 24.883-.768a18.263 18.263 0 0 0 0-25.728L235.47 5.387c-7.066-7.142-18.586-7.142-25.651 0-7.142 7.066-7.142 18.586 0 25.651l-.768-.768-118.58 118.502.768.844c-7.066-7.142-18.586-7.142-25.651 0-7.142 7.066-7.142 18.586 0 25.652l93.312 93.388c7.142 7.066 18.662 7.066 25.728 0a18.26 18.26 0 0 0 0-25.726l.768.766 43.315-43.238 224.179 224.18c8.832 8.832 23.194 8.832 32.026 0s8.832-23.117 0-31.948" />
                </svg>
                <span>{{ $lot->bids }}</span>
            </p>
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" xml:space="preserve" width="15px"
                    height="15px">
                    <path fill="currentColor"
                        d="M50 43v2.2c0 2.6-2.2 4.8-4.8 4.8H6.8C4.2 50 2 47.8 2 45.2V43c0-5.8 6.8-9.4 13.2-12.2l.6-.3c.5-.2 1-.2 1.5.1 2.6 1.7 5.5 2.6 8.6 2.6s6.1-1 8.6-2.6c.5-.3 1-.3 1.5-.1l.6.3C43.2 33.6 50 37.1 50 43M26 2c6.6 0 11.9 5.9 11.9 13.2S32.6 28.4 26 28.4s-11.9-5.9-11.9-13.2S19.4 2 26 2" />
                </svg>
                <span>{{ $lot->licits }}</span>
            </p>
        </div>
    </div>
</div>
