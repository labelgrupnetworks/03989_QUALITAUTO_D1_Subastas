<div class="all-auctions color-letter">
    <div class="container">
        <div class="row">
            <div class="auctions-list col-xs-12">
                @foreach ($data['auction_list'] as $subasta)
                    <?php

                    $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                    $url_lotes = \Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
                    $url_tiempo_real = \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);

                    $url_subasta = \Tools::url_info_auction($subasta->cod_sub, $subasta->name);

                    //Obtener los archivos de una subasta
                    $sub = new App\Models\Subasta();
                    $files = $sub->getFiles($subasta->cod_sub);

                    ?>
                    <div class="col-xs-12 col-md-6">
                        <div class="col-xs-12 border-lot auction-container no-padding">
                            @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                                <div class="bid-online"></div>
                                <div class="bid-online animationPulseRed"></div>
                            @endif
                            <div class="border-right-light col-xs-12 col-md-8 h-100 no-padding">
                                <div class="auction-list-title text-center">{{ $subasta->name }}</div>

                                <div class="snippet_documentacion" id="docs{{ $subasta->id_auc_sessions }}">
                                    <a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide', {direction:'right'}, 500)"
                                        style="color:#000;font-size:18px;position:absolute;right:10px;top:10px;cursor:pointer;">x</a>
                                    <b>{{ trans(\Config::get('app.theme') . '-app.subastas.documentacion') }}:</b>

                                    @if (!empty($files))
                                        @foreach ($files as $file)
                                            <?php
                                            //Falta guardar icono por tipos y especificar cual sera cada uno.
                                            $path_icon = '';
                                            switch ($file->type) {
                                                case 1:
                                                    $path_icon = '/img/icons/pdf.png';
                                                    break;
                                                case 2:
                                                    $path_icon = '/img/icons/video.png';
                                                    break;
                                                case 3:
                                                    $path_icon = '/img/icons/image.png';
                                                    break;
                                                case 4:
                                                    $path_icon = '/img/icons/document.png';
                                                    break;
                                                default:
                                                    $path_icon = '/img/icons/document.png';
                                                    break;
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-xs-1"></div>
                                                @if ($file->type == 5)
                                                    <div class="col-xs-1 text-center"><i class="fa fa-map-marker"
                                                            aria-hidden="true" style="font-size: 25px;"></i></div>
                                                    <div class="col-xs-10">
                                                        <a style="text-decoration: none;"
                                                            title="{{ $file->description }}" target="_blank"
                                                            href="{{ $file->url }}">{{ $file->description }}</a>
                                                    </div>
                                                @else
                                                    <div class="col-xs-1 text-center"><img src="{{ $path_icon }}"
                                                            width="80%"></div>
                                                    <div class="col-xs-10">
                                                        <a style="text-decoration: none;"
                                                            title="{{ $file->description }}" target="_blank"
                                                            href="/files/{{ $file->path }}">{{ $file->description }}</a>
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-8 no-padding auction-item-img">
                                    <div class="auction-size col-xs-12 no-padding">
                                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                                        <img data-src="{{ \Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
                                            alt="{{ $subasta->name }}" class="img-responsive lazy"
                                            style="display: none" />
                                    </div>
                                </div>
                                <div
                                    class="col-md-6 col-xs-12 col-sm-4 auction-desc-content d-flex justify-content-center flex-direction-column">
                                    <div class="mt-45n">
                                        <p style="font-weight: 600;">
                                            {{ date('d-m-Y', strtotime($subasta->session_start)) }}</p>
                                    </div>
                                    <div class="">
                                        <?php ?>
                                        <small>{{ date('H:i', strtotime($subasta->session_start)) }} h</small>
                                    </div>
                                    <div class="documents">
                                        <ul class="ul-format">
                                            <?php
                                            $pdf_cat = Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat');
                                            $pdf_man = Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'man');
                                            $pdf_pre = Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre');
                                            //Ya no se usa desde el ERP, lo mantengo por si acaso
                                            $pdf_adj = Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'adj');
                                            ?>
                                            @if ($pdf_cat)
                                                <li class="col-md-12 col-xs-6 no-padding">
                                                    <a target="_blank" class="cat-pdf color-letter d-flex"
                                                        href="{{ $pdf_cat }}" role="button">
                                                        <div class="text-center"><i class="fas  fa-file-download"></i>
                                                        </div>
                                                        <small>{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</small>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($pdf_man)
                                                <li class="col-md-12 col-xs-6 no-padding">
                                                    <a target="_blank" class="cat-pdf color-letter d-flex"
                                                        href="{{ $pdf_man }}" role="button">
                                                        <div class="text-center"><i class="fas fa-file-download"></i>
                                                        </div>
                                                        <small>{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_man') }}</small>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($pdf_pre)
                                                <li class="col-md-12 col-xs-6 no-padding">
                                                    <a target="_blank" class="cat-pdf color-letter d-flex"
                                                        href="{{ $pdf_pre }}" role="button">
                                                        <div class="text-center"><i class="fas fa-file-download"></i>
                                                        </div>
                                                        <small>{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_pre') }}</small>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($pdf_adj)
                                                <li class="col-md-12 col-xs-6 no-padding">
                                                    <a target="_blank" class="cat-pdf color-letter d-flex"
                                                        href="{{ $pdf_adj }}" role="button">
                                                        <div class="text-center"><i class="fas fa-file-download"></i>
                                                        </div>
                                                        <small>{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</small>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div
                                class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-offset-0 col-md-4 d-flex justify-content-center h-100 align-items-center">
                                <div class="auction-item-links w-100">
                                    <div class="auction-item-icon-desc d-block">
                                        <a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
                                            class=" btn-view-lots button-principal">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
                                    </div>
                                    <div class="auction-item-icon-desc  d-block">
                                        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
                                            class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}</a>

                                    </div>
                                    <div class="auction-item-icon-desc d-block">
                                        <a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide', {direction:'right'}, 500)"
                                            class="btn-info-auction secondary-button" style="cursor:pointer;">
                                            {{ trans(\Config::get('app.theme') . '-app.subastas.documentacion') }}
                                        </a>
                                    </div>

                                    @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                                        <div class="bid-life d-block">
                                            <a style="color:#FFFFFF" class="btn-bid-life d-block "
                                                href="{{ $url_tiempo_real }}"
                                                title="{{ trans(\Config::get('app.theme') . '-app.lot_list.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme') . '-app.lot_list.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                                                target="_blank">{{ trans(\Config::get('app.theme') . '-app.lot.bid_live') }}</a>
                                        </div>
                                    @endif



									@if($data['calendars'])
                                    @php
                                        $calendarLinks = collect($data['calendars'])
                                            ->where('auction_id', $subasta->id_auc_sessions)
                                            ->first();
                                    @endphp
									@include('components.add_calendar_button', ['links' => $calendarLinks, 'fileName' => $subasta->name])
									@endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
