<div class="tr_user_auction_lots">
    <div class="header_aucions_lots ">
        <button class="arrow-carrousel prev-arrow-carrousel btn btn-default">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
		</button>

        <h4 class="m-0">Siguientes lotes</h4>

        <button class="arrow-carrousel next-arrow-carrousel btn btn-default">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>
    </div>

    <div class="options d-none">
        <div class="carrousel-lots-check d-flex justify-content-end">
            <input class="check-input" id="j-followCarrousel" type="checkbox" value="" checked>
            <label class="check-label" for="j-followCarrousel">
                {{ trans($theme . '-app.sheet_tr.follow') }}
            </label>
        </div>
    </div>

    <div class="lots-carrousel">
        @foreach ($auctionLots as $lote)
            @php
                $img = Tools::url_img('lote_medium', $lote->num_hces1, $lote->lin_hces1);
            @endphp
            <div class="card carrousel-lot-card border-0 j-active-info lots" data-ref_asigl0="{{ $lote->ref_asigl0 }}"
                data-cod_sub="{{ $data['subasta_info']->cod_sub }}" data-order="{{ $loop->index }}" style="order: {{ $loop->index }};">

                <img class="card-img-top py-1 img-contain border" src="{{ $img }}" loading="lazy">

                <div class="card-body py-2 text-center">
                    <h4 class="card-lot-title">{{ trans("$theme-app.lot.lot-name") }} {{ $lote->ref_asigl0 }}</h4>

                    <h6 class="text-lb-gray m-0">
                        {{ trans("$theme-app.lot.lot-price") }}
                    </h6>
                    <p class="text-lb-gray">{{ $lote->impsal_hces1 }} {{ trans("$theme-app.subastas.euros") }}</p>
                </div>

                <div class="card-footer p-0 bg-transparent">
                    <a class="btn w-100 btn-outline-lb-primary lot-btn"
                        href="{{ $lote->url }}">{{ trans("$theme-app.sheet_tr.view") }}</a>
                </div>
            </div>
        @endforeach

    </div>

</div>
