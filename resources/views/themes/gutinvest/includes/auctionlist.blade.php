

<div class="item-subasta col-xs-12 {{$cat}}">
    <div class="item-subasta-img col-xs-12 col-sm-3 no-padding">
    <a title="{{ $subasta->name }}" href="<?= $url_lotes?>">
            <div class="img-lot">
                    <img
                        src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg"
                        alt="{{ $subasta->name }}"
                        class="img-responsive"
                        style="margin: 0 auto"
                    />
            </div>
    </a>
    </div>
    <div class="item-subasta-data col-sm-9 col-xs-12 no-padding">
        <div class="col-xs-12 no-padding">
            <div class="col-md-6 col-xs-12">
                <div class="item-subasta-data-name" style="margin-bottom: 10px;">
                    <h3><strong>{{ $subasta->name }}</strong></h3>
                </div>
            </div>
            <div class="col-xs-6 col-sm-1 no-padding close-date">
                <div class="item-subasta-data-title">
                    {{ trans(\Config::get('app.theme').'-app.subastas.date_end') }}
                </div>
                <div class="item-subasta-data-text">
                {{date_format($dataEnd, 'd-m-Y')}}
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 auction-type">
                <div class="item-subasta-data-title">
                    {{ trans(\Config::get('app.theme').'-app.subastas.mode_auction') }}
                </div>
                    <div class="item-subasta-data-text">
                        <small>
                @if ($subasta->tipo_sub == 'V')
                    {{ trans(\Config::get('app.theme').'-app.lot_list.direct_sale') }}
                @elseif($subasta->tipo_sub == 'W')
                    {{ trans(\Config::get('app.theme').'-app.lot_list.sell_for_offer') }}
                @elseif($subasta->tipo_sub == 'O')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
                @endif
                        </small>
                </div>
            </div>
             @if(!empty(\Tools::NamePais($subasta->pais_sub)))
            <div class="col-xs-6 col-sm-2 no-padding hidden-xs hidden-sm">
                <div class="item-subasta-data-title" style="text-align: right;">
                    <span>{{ trans(\Config::get('app.theme').'-app.subastas.country') }}</span>
                    <div style="width: 15px; display: inline-block;"></div>
                </div>
                    <div class="item-subasta-data-text" style="text-align: right;">
                        <small style="text-align: right;">
                            @php($url_flat = '')
                            @if(file_exists('img/paises/'.$subasta->pais_sub.'.JPG'))
                            <?php $url_flat = '/img/paises/'.$subasta->pais_sub.'.JPG' ?>
                           @else
                            <?php $url_flat = '/img/paises/'.$subasta->pais_sub.'.jpg' ?>
                           @endif
                            @if(!empty($url_flat))
                       <img style="width: 15px;border-radius: 50%;height: 15px;" src="{{$url_flat}}"></span>
                   @endif
                    @if(!empty(\Tools::NamePais($subasta->pais_sub)))
                   <span>{{ \Tools::NamePais($subasta->pais_sub) }}</span>
                   @endif

                        </small>
                        <div style="width: 15px; display: inline-block;"></div>
                </div>
            </div>
            @endif

        </div>

        <div class="item-subasta-data-desc col-sm-10 col-xs-12">
            <p><strong>{{ trans(\Config::get('app.theme').'-app.subastas.number_sale') }}: </strong>{{ $subasta->cod_sub }}</p>
            {{ $subasta->des_sub }}
        </div>

                     @if(!empty(\Tools::NamePais($subasta->pais_sub)))
            <div class="col-xs-6 col-sm-2 hidden-md hidden-lg">
                <div class="item-subasta-data-title" style="text-align: left;">
                    <span>{{ trans(\Config::get('app.theme').'-app.subastas.country') }}</span>
                    <div style="width: 15px; display: inline-block;"></div>
                </div>
                    <div class="item-subasta-data-text" style="text-align: left;">
                        <small style="text-align: left;">
                            @php($url_flat = '')
                            @if(file_exists('img/paises/'.$subasta->pais_sub.'.JPG'))
                            <?php $url_flat = '/img/paises/'.$subasta->pais_sub.'.JPG' ?>
                           @else
                            <?php $url_flat = '/img/paises/'.$subasta->pais_sub.'.jpg' ?>
                           @endif
                            @if(!empty($url_flat))
                       <img style="width: 15px;border-radius: 50%;height: 15px;" src="{{$url_flat}}"></span>
                   @endif
                    @if(!empty(\Tools::NamePais($subasta->pais_sub)))
                   <span>{{ \Tools::NamePais($subasta->pais_sub) }}</span>
                   @endif

                        </small>
                        <div style="width: 15px; display: inline-block;"></div>
                </div>
            </div>
            @endif
        <div class="col-xs-12 item-subasta-data-location no-padding">
            <div class="col-xs-12 col-sm-7 col-xs-12">
                <div class="item-subasta-data-title">
                    {{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}
                </div>
                <div class="item-subasta-data-text">
                    {{ $subasta->expolocal_sub}}
                </div>
            </div>
            <div class="col-xs-12 col-sm-5 text-right">
                <div class="item-subasta-data-ver-lotes">
                    <a title="{{ $subasta->name }}" href="{{ $url_lotes }}" class="item-subasta-data-ver-lotes-button">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }} ({{$subasta->num_lots}})</a>
                    <span><a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="item-subasta-data-ver-lotes-info">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a></span>
                </div>
            </div>
        </div>
    </div>


</div>

