<?php

$historicas = [];
foreach ($data['auction_list'] as $value) {
    $year = date('Y', strtotime($value->session_start));
    #agrupamos por auction
    $historicas[$year][$value->cod_sub] = $value;
}

?>

<div class="all-auctions color-letter">
    <div class="container">
        <div class="row">

            <div class="auctions-list col-xs-12">
                @foreach ($historicas as $key => $subastas)
                    <div class="col-xs-12 sub-h">
                        <div class="dat year_auctions_list">
                            <strong> {{ $key }} </strong>
                        </div>
                    </div>
                    @foreach ($subastas as $subasta)
                        <?php
                        $url_lotes = \Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
                        $url_tiempo_real = \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);

                        $url_subasta = \Tools::url_info_auction($subasta->cod_sub, $subasta->name);

                        //Obtener los archivos de una subasta
                        $sub = new App\Models\Subasta();
                        $files = $sub->getFiles($subasta->cod_sub);

                        if ($key < 2022) {
                            $url_img_subasta = '/catalogos/' . $subasta->cod_sub;
                        } else {
                            $url_img_subasta = $url_lotes;
                        }

                        ?>
                        <div class="col-xs-12 col-md-3 mb-3">
                            <div class="imgAuctionList">

                                <a href="{{ $url_img_subasta }}" target="_blank">
                                    <img class="img-responsive"
                                        src="{{ \Tools::url_img_auction('subasta_medium', $subasta->cod_sub) }}"
                                        alt="{{ $subasta->name }}" />
                                </a>

                            </div>
                            <div class="textAuctionList">
                                <p class="textAuctionList-auction">
                                    {{ trans($theme . '-app.subastas.inf_subasta_subasta') }}
                                    {{ $subasta->cod_sub }}
                                </p>
                                <p class="textAuctionList-date">
                                    {{ $subasta->des_sub }}
                                </p>
                                <p>
                                    <a href="/catalogos/{{ $subasta->cod_sub }}" class="btn mini-button"
                                        target="_blank">{{ trans("$theme-app.lot_list.ver_catalogo") }}</a>
                                </p>
                                @if (strtotime($subasta->session_end) > time())
                                    <p class="realTime">
                                        <a class=" btn realTimeButton"
                                            href="{{ \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions) }}"
                                            target="_blank">
                                            {{ trans($theme . '-app.lot_list.bid_live') }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

        </div>

    </div>
</div>
