@php
    // Eloy: 28/07/2023
	//Los indices solo los esta utilizando soler y realizamos una query por cada subasta para obtenerlos
	//los comento por si en un futuro los utiliza alguien más, aunque sería mejor que se hiciera una query para todas las subastas
	//$indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
    $url_lotes = Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
    $url_tiempo_real = Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
    $url_subasta = Tools::url_info_auction($subasta->cod_sub, $subasta->name);

    $files = $allFiles->where('auction', $subasta->cod_sub);

	$calendarLinks = $data['calendars'] ? collect($data['calendars'])->where('auction_id', $subasta->id_auc_sessions)->first() : null;
@endphp

<div class="auctions-auction-card" style="position: relative">
    @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
        <div class="bid-online"></div>
        <div class="bid-online animationPulseRed"></div>
    @endif

    <div class="auction-card__info">
        <p class="auction-card_title">{{ $subasta->name }}</p>

        <div class="auction-card__data">
            @include('includes.auction_documents')
            <div class="auction-card__image-wrap">
                <img src="{{ \Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
                    alt="{{ $subasta->name }}" class="img-responsive" />
            </div>

            <div class="auction-card__dates">
                <p style="font-weight: 600;">
                    {{ date('d-m-Y', strtotime($subasta->session_start)) }}
                </p>

                <p>
                    <small>{{ date('H:i', strtotime($subasta->session_start)) }} h</small>
                </p>
            </div>
        </div>
    </div>

    <div class="auction-card__buttons">
        <a title="{{ $subasta->name }}" href="{{ $url_lotes }}" class="btn btn-block auction-card__button_principal">
            {{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}
        </a>

        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-block auction-card__button_secondary">
            {{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}
        </a>

        <button onclick="$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide', {direction:'right'}, 500)"
            class="btn btn-block auction-card__button_secondary">
            {{ trans(\Config::get('app.theme') . '-app.subastas.documentacion') }}
        </button>

        @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
            <a style="color:#FFFFFF" class="btn btn-block btn-bid-life" href="{{ $url_tiempo_real }}"
                title="{{ trans(\Config::get('app.theme') . '-app.lot_list.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme') . '-app.lot_list.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                target="_blank">{{ trans(\Config::get('app.theme') . '-app.lot.bid_live') }}</a>
        @endif

        @if ($calendarLinks)
            @include('components.add_calendar_button', [
                'links' => $calendarLinks,
                'fileName' => $subasta->name,
            ])
        @endif
    </div>
</div>
