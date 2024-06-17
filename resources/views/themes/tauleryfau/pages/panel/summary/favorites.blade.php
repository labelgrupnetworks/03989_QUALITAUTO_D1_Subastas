<div class="summary-favorites-arrows">
    <div class="arrow-prev"><i class="fa fa-angle-left"></i></div>
    <div class="arrow-next"><i class="fa fa-angle-right "></i></div>
</div>
<section class="summary-favorites">
    @foreach ($lots as $inf_lot)
        @php
            $tileFriendly = str_slug($inf_lot->titulo_hces1);
            $sesionFriendly = str_slug($inf_lot->session_name);
            $lotUrl = "$inf_lot->cod_sub-$sesionFriendly-$inf_lot->id_auc_sessions/$inf_lot->ref_asigl0-$inf_lot->num_hces1-$tileFriendly";
            $lotLiveUrl = "$inf_lot->cod_sub-$sesionFriendly-$inf_lot->id_auc_sessions";

            $url_friendly = Routing::translateSeo('lote') . $lotUrl;
            $urlLive = Routing::translateSeo('api/subasta') . $lotLiveUrl;

            $style = 'other';
            $bid_mine = false;
            if ($inf_lot->cod_licit == $inf_lot->licit_winner_bid) {
                $style = 'mine';
                $bid_mine = true;
            } elseif (!Config::get('app.notice_over_bid') && $inf_lot->tipo_sub == 'W') {
                $style = 'gold';
            }

            $escalado = new \App\Models\Subasta();
            $escalado->cod = $inf_lot->cod_sub;
            $escalado->sin_pujas = true;
            if (!empty($inf_lot->licit_winner_bid)) {
                $escalado->sin_pujas = false;
            }

            $nextScale = $escalado->NextScaleBid($inf_lot->impsalhces_asigl0, $inf_lot->implic_hces1);

            $isClose = $inf_lot->cerrado_asigl0 == 'S' || $inf_lot->retirado_asigl0 == 'S';
            $actualPrice = !empty($inf_lot->implic_hces1) ? $inf_lot->implic_hces1 : $inf_lot->impsalhces_asigl0;
        @endphp

        <a href="{{ $url_friendly }}">
            <div class="summary-lot">
                <div class="lot-image">
					@env('local')
						<img class="img-responsive"
							src="{{ Tools::serverLotUrlImg('subastas.tauleryfau.com', '700', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
							loading="lazy">
					@endenv

					@env(['develop', 'production'])
                    <img class="img-responsive"
                        src="{{ Tools::url_img('lote_medium', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
                        loading="lazy">
					@endenv
                </div>

                <p class="lot-ref">{{ trans("$theme-app.user_panel.lot") }} {{ $inf_lot->ref_asigl0 }}</p>

                <div class="lot-desc">
                    {!! $inf_lot->desc_hces1 !!}
                </div>

                <p class="lot-actual">
                    <span>
                        {{ trans("$theme-app.lot.puja_actual") }}
                    </span>
                    <span class="js-divisa" data-format="0,0" value="{{ $actualPrice }}">
                        {!! $currency->getPriceSymbol(0, $actualPrice) !!}
                    </span>
                </p>

                @if (!$isClose)
                    <button @class(['btn btn-lb btn-lb-secondary', 'bid-mine' => $bid_mine])>
                        @if ($bid_mine)
                            {{ trans("$theme-app.user_panel.higher_bid_es") }}
                        @else
                            {{ trans("$theme-app.sheet_tr.place_bid") }}
                            <span class="js-divisa" data-format="0,0" value="{{ $nextScale }}">
                                {!! $currency->getPriceSymbol(0, $nextScale) !!}
                            </span>
                        @endif
                    </button>
                @else
                    <button @class([
                        'btn btn-lb',
                        'btn-lb-danger' => !$bid_mine,
                        'bid-mine' => $bid_mine,
                    ])>
                        @if ($bid_mine)
                            {{ trans("$theme-app.user_panel.won") }}
                        @else
                            {{ trans("$theme-app.user_panel.lost") }}
                        @endif
                    </button>
                @endif

            </div>
        </a>
    @endforeach

</section>

<script>
    $('.summary-favorites').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        prevArrow: $('.arrow-prev'),
        nextArrow: $('.arrow-next'),
        responsive: [{
                breakpoint: 1200,
                settings: {
                    arrows: true,
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 900,
                settings: {
                    arrows: true,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    })
</script>
