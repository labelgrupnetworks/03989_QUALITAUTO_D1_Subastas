<div class="container">
    <h1 class="page-title">
        {{ trans("$theme-app.subastas.auctions") }}
    </h1>

    <div class="grid-auctions">
        @foreach ($data['auction_list'] as $subasta)
            <?php
            $url_lotes = \Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
            $url_tiempo_real = \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
            $url_subasta = \Tools::url_info_auction($subasta->cod_sub, $subasta->name);
            ?>
            <div class="item_subasta">
                <a href="{{ $url_subasta }}" title="{{ $subasta->name }}">
                    <div class="img-lot">
                        <img class="img-responsive" src="{{ Tools::url_img_auction('subasta_large', $subasta->cod_sub) }}"
                            alt="{{ $subasta->name }}" />
                    </div>
                </a>

				<div class="item_subasta_item text-center">
                    {{ $subasta->name }}
                </div>
                <?php
                if ($subasta->tipo_sub == 'V') {
                    $url_lotes .= '?only_salable=on';
                }
                ?>

                <a class="btn btn-lotes" href="{{ $url_lotes }}"
                    title="{{ $subasta->name }}">{{ trans($theme . '-app.subastas.see_lotes') }}</a>
                <a class="btn btn-subasta" href="{{ $url_subasta }}"
                    title="{{ $subasta->name }}">{{ trans($theme . '-app.subastas.see_subasta') }}</a>
                @if ($subasta->upcatalogo == 'S')
                    <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                        <a href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat') }}"
                            title="{{ trans($theme . '-app.grid.pdf_catalog') }}"
                            target="_blank">{{ trans($theme . '-app.subastas.pdf_catalog') }}</a> <br>
                    </p>
                @endif
                @if ($subasta->uppreciorealizado == 'S')
                    <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                        <a href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}"
                            title="{{ trans($theme . '-app.grid.pdf_adj') }}"
                            target="_blank">{{ trans($theme . '-app.subastas.pdf_adj') }}</a> <br>
                    </p>
                @endif
                @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                    <p class="text-center" style="background-color:#9e190a;padding: 20px 0; ">
                        <a href="{{ $url_tiempo_real }}"
                            title="{{ trans($theme . '-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans($theme . '-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                            style="color:#FFFFFF" target="_blank">Puja en vivo</a>
                    </p>
                @endif
            </div>
        @endforeach
    </div>
</div>
