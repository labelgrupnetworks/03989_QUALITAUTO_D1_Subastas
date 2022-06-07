<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>
                            @if(!empty($data['auction_list']) && count($data['auction_list']) > 0 &&
                            $data['auction_list'][0]->tipo_sub == 'V')
                            {{ trans(\Config::get('app.theme').'-app.subastas.direct_sale') }}
                            @else
                            {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}
                            @endif
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container body-auctions">
    <div class="row">
            @if(!(!empty($data['auction_list']) && count($data['auction_list']) > 0 && $data['auction_list'][0]->tipo_sub == 'V'))
            <div class="col-xs-12">
                    <div class="auctions-list-title"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.next_auctions') }}</strong></div>
                </div>
            @endif
        <div class="auctions-list col-xs-12 no-padding">
            <?php $finalized = []; ?>
            @foreach ($data['auction_list'] as $subasta)
                <?php
					$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
                    $url_lotes_novendidos = $url_lotes."?noAward=1";
					$url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
					$url_subasta=  \Tools::url_info_auction($subasta->cod_sub,$subasta->name);

                    //ver si la subasta está cerrada
                    $SubastaTR      = new \App\Models\SubastaTiempoReal();
                    $SubastaTR->cod =$subasta->cod_sub;
                    $SubastaTR->session_reference =  $subasta->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
                    $status  = $SubastaTR->getStatus();
                    $subasta_finalizada = false;
                    if(!empty($status) && $status[0]->estado == "ended" ){
                        $subasta_finalizada = true;

                    }
                ?>
                @if($subasta_finalizada)
                    <?php array_push($finalized, $subasta ); ?>
                @else
                    <div class="col-xs-12 bid-large">
                        <div class="col-md-3 col-sm-4 col-xs-12 ">
                            <a title="{{ $subasta->name }}" href="{{ $url_lotes }}">
                                <img src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg" alt="{{ $subasta->name }}" class="img-responsive img-auction-new"  />
                            </a>
                        </div>
                        <div class="col-md-9 col-sm-8 col-xs-12 bid-large-info-content">
                            <?php
                                $fecha = strftime('%d %b %Y',strtotime($subasta->session_start));
                                if(\App::getLocale() != 'en'){
                                    $array_fecha = explode(" ",$fecha);
                                    $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                                    $fecha = $array_fecha[0].' '. str_replace('"', " ",$array_fecha[1]).' '.$array_fecha[2];
                                }
                                $hours =  date("H:i", strtotime($subasta->session_start));
                            ?>
                            <div class="bid-large-title">
                                <h2 title="{{ $subasta->name }}" href="<?= $url_subasta?>">{{ $subasta->name }}</h2>
                            </div>
                            @if( $subasta->tipo_sub !='V')
                                <div class="bid-large-date">
                                    {{ $fecha }}
                                </div>
                                <div class="bid-large-date">
                                    {{ $hours }} {{ trans(\Config::get('app.theme').'-app.subastas.hours') }} -
                                    @if( $subasta->tipo_sub =='O')
                                        {{ trans(\Config::get('app.theme').'-app.subastas.only-online') }}
                                    @else
                                        {{ $subasta->seslocal_sub }}
                                    @endif
                                </div>
                            @else
                                <div class="bid-large-date">
                                    {{ $subasta->seslocal_sub }}
                                </div>
                            @endif
                            <div class="bid-large-buttons col-xs-12" style="position:relative; zIndex: 9999999">
                                <div class="bid-large-button-views">
                                    <a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
                                        class="bid-large-button-view view">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}
                                    </a>
                                </div>
                                <div class="bid-large-button-info">
                                    <a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
                                        class="bid-large-button-view info">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}
                                    </a>
                                </div>
                                @if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() && $subasta_finalizada == false )
                                    <div class="bid-large-button-bid-online">
                                        <?php //Si la puja en tiempo real ha comenzado el boton parpadea ?>
                                            <a class="bid-large-button-view bid-online <?= strtotime($subasta->session_start) > time() ? '' : 'puja-online' ?>"
                                                style="color:#FFFFFF" href="{{ $url_tiempo_real }}"
                                                title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}"
                                                target="_blank"
                                            >
                                                {{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}
                                            </a>
                                    </div>
                                @else
                                    @if( $subasta->tipo_sub =='W' && (strtotime($subasta->session_end) < time() || $subasta_finalizada))
                                        <div class="bid-large-button-bid-online">
                                            <a class="bid-large-button-view bid-online" href="{{ $url_lotes_novendidos }}"
                                                target="_blank"
                                            >
                                                {{ trans(\Config::get('app.theme').'-app.lot.lots_disp') }}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        </div>
                        <div class="bid-large-separator col-xs-12"></div>
                    @endif
                @endforeach
            </div>
        </div>
@if(count($finalized) > 0)
    <div class="row">
            <div class="col-xs-12">
                <div class="auctions-list-title"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.finished_auctions') }}</strong></div>
            </div>
            <div class="auctions-list col-xs-12 no-padding">
                <?php krsort($finalized); ?>
                @foreach ($finalized as $subasta)
                    <?php
						$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
                        $url_lotes_novendidos = $url_lotes."?noAward=1";
						$url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
						$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);
                        //ver si la subasta está cerrada
                        $SubastaTR      = new \App\Models\SubastaTiempoReal();
                        $SubastaTR->cod =$subasta->cod_sub;
                        $SubastaTR->session_reference =  $subasta->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
                        $status  = $SubastaTR->getStatus();
                        $subasta_finalizada = false;
                        if(!empty($status) && $status[0]->estado == "ended" ){
                            $subasta_finalizada = true;

                        }
                    ?>

                        <div class="col-xs-12 bid-large">
                            <div class="col-md-3 col-sm-4 col-xs-12 ">
                                <a title="{{ $subasta->name }}" href="{{ $url_lotes }}">
                                    <img src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg" alt="{{ $subasta->name }}" class="img-responsive img-auction-new"  />
                                </a>
                            </div>
                            <div class="col-md-9 col-sm-8 col-xs-12 bid-large-info-content">
                                <?php
                                    $fecha = strftime('%d %b %Y',strtotime($subasta->session_start));
                                    if(\App::getLocale() != 'en'){
                                        $array_fecha = explode(" ",$fecha);
                                        $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                                        $fecha = $array_fecha[0].' '. str_replace('"', " ",$array_fecha[1]).' '.$array_fecha[2];
                                    }
                                    $hours =  date("H:i", strtotime($subasta->session_start));
                                ?>
                                <div class="bid-large-title">
                                    <h2 title="{{ $subasta->name }}" href="<?= $url_subasta?>">{{ $subasta->name }}</h2>
                                </div>
                                @if( $subasta->tipo_sub !='V')
                                    <div class="bid-large-date">
                                        {{ $fecha }}
                                    </div>
                                    <div class="bid-large-date">
                                        {{ $hours }} {{ trans(\Config::get('app.theme').'-app.subastas.hours') }} -
                                        @if( $subasta->tipo_sub =='O')
                                            {{ trans(\Config::get('app.theme').'-app.subastas.only-online') }}
                                        @else
                                            {{ $subasta->seslocal_sub }}
                                        @endif
                                    </div>
                                @else
                                    <div class="bid-large-date">
                                        {{ $subasta->seslocal_sub }}
                                    </div>
                                @endif
                                <div class="bid-large-buttons col-xs-12" style="position:relative; zIndex: 9999999">
                                    <div class="bid-large-button-views">
                                        <a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
                                            class="bid-large-button-view view">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}
                                        </a>
                                    </div>
                                    <div class="bid-large-button-info">
                                        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
                                            class="bid-large-button-view info">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}
                                        </a>
                                    </div>
                                    @if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() && $subasta_finalizada == false )
                                        <div class="bid-large-button-bid-online">
                                            <?php //Si la puja en tiempo real ha comenzado el boton parpadea ?>
                                                <a class="bid-large-button-view bid-online <?= strtotime($subasta->session_start) > time() ? '' : 'puja-online' ?>"
                                                    style="color:#FFFFFF" href="{{ $url_tiempo_real }}"
                                                    title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}"
                                                    target="_blank"
                                                >
                                                    {{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}
                                                </a>
                                        </div>
                                    @else
                                        @if( $subasta->tipo_sub =='W' && (strtotime($subasta->session_end) < time() || $subasta_finalizada))
                                            <div class="bid-large-button-bid-online">
                                                <a class="bid-large-button-view bid-online" href="{{ $url_lotes_novendidos }}"
                                                    target="_blank"
                                                >
                                                    {{ trans(\Config::get('app.theme').'-app.lot.lots_disp') }}
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            </div>
                            <div class="bid-large-separator col-xs-12"></div>
                    @endforeach
                </div>

            </div>
            @endif
        </div>
@if(!empty($data['auction_list']) && count($data['auction_list']) > 0)
<section>
    <div id='seo_content' class='container content'>
        <div class='row'>
            <div class="col-sm-12">
                <?php
                    $key =($data['auction_list'][0]->tipo_sub == 'V') ? "info_tienda_SEO_".strtoupper(Config::get('app.locale')) : "info_subasta_SEO_".strtoupper(Config::get('app.locale'));
                        $html="{html}";
                        $content = \Tools::slider($key, $html);
                ?>
                {!!$content!!}
            </div>
        </div>
    </div>
</section>
@endif
</div>
