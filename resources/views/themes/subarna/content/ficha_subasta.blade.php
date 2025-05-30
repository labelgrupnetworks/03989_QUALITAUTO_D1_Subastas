@php
    $auction = $data['auction'];
    $imageSrc = Tools::url_img_auction('subasta_large', $auction->cod_sub);
    //$imageSrc = 'https://www.subarna.net' . $imageSrc; // remove this line

    $dateFormat = Tools::getDateFormatDayMonthLocale($auction->start);
    $url_tiempo_real = Tools::url_real_time_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions);
	$urlAuction = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);
@endphp

<div class="container">
    <div class="ficha-subasta_content">
        <div class="img-border-auction">
            <img class="ficha-subasta_image" src="{{ $imageSrc }}">
        </div>
        <section class="ficha-subasta_info">
            <div class="ficha-subasta_title">
                <p class="ficha-subasta_date">{{ $dateFormat }}</p>
                <h1 class="ff-highlight bold">{{ $auction->des_sub }}</h1>
            </div>

            <div class="ficha-subasta-description">
                {!! $auction->descdet_sub !!}
            </div>

            <div class="ficha-suasta-expo">
                <h2>{{ trans("$theme-app.subastas.inf_subasta_exposicion") }}</h2>
                @if ($auction->expofechas_sub)
                    <p>{{ $auction->expofechas_sub }}</p>
                @endif
                @if ($auction->expohorario_sub)
                    <div>
                        {{ trans("$theme-app.subastas.inf_subasta_horario") }} : {!! $auction->expohorario_sub !!}
                    </div>
                @endif
                @if ($auction->expolocal_sub)
                    <p>
                        {{ trans("$theme-app.subastas.inf_subasta_location") . ': ' . $auction->expolocal_sub }}
                    </p>
                @endif
                @if (!empty($auction->expomaps_sub))
                    <p>
                        <a href="https://maps.google.com/?q={{ $auction->expomaps_sub }}"
                            title="{{ trans("$theme-app.subastas.how_to_get") }}" target="_blank">
                            {{ trans("$theme-app.subastas.how_to_get") }}
                        </a>
                    </p>
                @endif

                <h2>{{ trans("$theme-app.subastas.inf_subasta_subasta") }}</h2>
                @if ($auction->sesfechas_sub)
                    <p>{{ $auction->sesfechas_sub }}</p>
                @endif
                @if ($auction->seshorario_sub)
                    <p>{{ trans("$theme-app.subastas.inf_subasta_horario") . ': ' . $auction->seshorario_sub }}</p>
                @endif
                @if (!empty($auction->seslocal_sub))
                    <p>{{ trans("$theme-app.subastas.inf_subasta_location") . ': ' . $auction->seslocal_sub }}</p>
                @endif
            </div>

            @foreach ($data['sessions'] as $session)
                @php
                    $lotsUrl = Tools::url_auction(
                        $session->auction,
                        $session->name,
                        $session->id_auc_sessions,
                        $session->reference,
                    );
                    if ($auction->tipo_sub == 'V') {
                        $lotsUrl .= '?only_salable=on';
                    }
                @endphp
                <div class="ficha-subasta_links">
                    <a class="btn btn-lb-primary btn-light" href="{{ $lotsUrl }}"
                        title="{{ trans("$theme-app.subastas.see_lotes") }}">
                        {{ trans("$theme-app.subastas.see_lotes") }}
                    </a>

                    @if ($session->upcatalogo == 'S')
                        <a class="btn btn-lb-primary btn-light"
                            href="{{ Tools::url_pdf($auction->cod_sub, $session->reference, 'cat') }}"
                            title="{{ trans("$theme-app.subastas.pdf_catalog") }}" target="_blank">
                            {{ trans("$theme-app.subastas.pdf_catalog") }}
                        </a>
                    @endif
                </div>
            @endforeach

            @if ($auction->tipo_sub == 'W' && strtotime($auction->end) > time())
                <div class="ficha-subasta_real_time">
                    <a class="btn btn-block btn-live" href="{{ $url_tiempo_real }}"
                        title="{{ trans("$theme-app.header.from") . ' ' . date_format(date_create_from_format('Y-m-d H:i:s', $auction->start), 'd/m/Y H:i') . ' ' . trans($theme . '-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $auction->end), 'd/m/Y H:i') }}"
                        target="_blank">
                        {{ trans("$theme-app.subastas.live_bidding") }}
                    </a>
                </div>
            @endif

            <div class="share-panel-auction">
                @include('includes.ficha.share_ficha_subasta')
            </div>

        </section>
    </div>

    <div class="ficha-subasta-categories">
        @foreach ($data['categories'] as $category)


            <div class="category-item flex-1">
                <a href="{{ $urlAuction . "?category=" . $category->lin_ortsec0 }}" tabindex="0">
                    <img src="/themes/{{$theme}}//assets/img/categories/{{$category->lin_ortsec0}}.webp"
                        type="image/webp">
                    <span>{{ $category->des_ortsec0 }}</span>
                </a>
            </div>
        @endforeach
    </div>

</div>
