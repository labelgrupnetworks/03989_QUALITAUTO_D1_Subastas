@php
    $url_lotes = Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
    $url_tiempo_real = Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
    $url_subasta = Tools::url_info_auction($subasta->cod_sub, $subasta->name);
@endphp

<article class="card card-custom-large h-100">
    <div class="row g-0 h-100">
        <div class="col-md-7 d-flex flex-column">

            <div class="card-body d-flex flex-column align-items-start">
                <header>
                    <h5 class="card-title">{{ $subasta->des_sub }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
						{{ strip_tags($subasta->description_det ?? '') }}
                        {{-- {{ trans('web.user_panel.date') . ': ' . date('d-m-Y H:i', strtotime($subasta->session_start)) }} --}}
                    </h6>
                </header>

                {{-- <p class="card-text max-line-3 mb-2">{{ strip_tags($subasta->description ?? '') }}</p> --}}

                <a class="btn btn-lb-primary mt-auto" href="{{ $url_lotes }}">{{ trans('web.subastas.see_all_lots') }}</a>
            </div>

            <footer class="card-footer">
                <div class="row gy-1 card-links">
                    <div class="col">
                        <button class="btn btn-sm btn-outline-border-lb-primary js-auction-files"
                            data-auction="{{ $subasta->cod_sub }}">
                            <x-icon.boostrap icon="folder" size="12" />
                            {{ trans('web.foot.catalogs') }}
                        </button>
                    </div>
                </div>
            </footer>

        </div>

        <div class="col-md-5 card-img-wrapper">

            <div class="activity"></div>

            <img class="w-100 h-100"
                src="{{ Tools::auctionImage($subasta->cod_sub) }}"
                alt="{{ $subasta->name }}" @if ($loop->index > 6) loading="lazy" @endif>

            @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                <div class="btn-live-wrapper">
                    <a class="btn btn-lb-primary" href="{{ $url_tiempo_real }}"
                        title="{{ trans('web.lot_list.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans('web.lot_list.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                        target="_blank">{{ trans('web.lot.bid_live') }}</a>
                </div>
            @endif

        </div>
    </div>
</article>
