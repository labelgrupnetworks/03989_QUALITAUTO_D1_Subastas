@php
	use App\Models\V5\AucSessionsFiles;

	//Obtener todos los codigos de subasta
	$codsSub = Arr::pluck($data['auction_list'], 'cod_sub');
	$codsSub = array_unique($codsSub);

	//Obtener todos los archivos de las subastas
	$allFiles = AucSessionsFiles::query()
		->select('"auction"', '"description"', '"type"', '"path"', '"order"')
		->whereIn('"auction"', $codsSub)
		->where('"lang"', Tools::getLanguageComplete(Config::get('app.locale')))
		->orderBy('"order"')
		->get();
@endphp

<div class="container">
    <div class="row">

        @foreach ($data['auction_list'] as $subasta)
            <?php
            $sub = new App\Models\Subasta();
            $sub->cod = $subasta->cod_sub;
            $files = $allFiles->where('auction', $subasta->cod_sub);

            $url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
            $url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
            $url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);
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
                            <a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide',{direction:'right'},500)"
                                style="color:#000;font-size:18px;position:absolute;right:10px;top:10px;">x</a>
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
                                        default:
                                            $path_icon = '/img/icons/document.png';
                                            break;
                                    }

                                    ?>
                                    <div class="row">
                                        <div class="col-xs-1"></div>
                                        <div class="col-xs-1 text-center"><img src="{{ $path_icon }}" width="80%">
                                        </div>
                                        <div class="col-xs-10"><a style="text-decoration: none;"
                                                title="{{ $file->description }}" target="_blank"
                                                href="/files/{{ $file->path }}">{{ $file->description }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="infosnippet">
                            <div class="col-md-6 col-xs-12 col-sm-8 no-padding auction-item-img">
                                <div class="auction-size col-xs-12 no-padding">

										<img src="{{ \Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
											@if($loop->iteration > 4) loading="lazy" @endif
                                        	alt="{{ $subasta->name }}" class="img-responsive" />
                                </div>
                            </div>
                            <div
                                class="col-md-6 col-xs-12 col-sm-4 auction-desc-content d-flex justify-content-center flex-direction-column">
                                <div class="mt-45n">
                                    <p style="font-weight: 600;">
                                        {{ date('d-m-Y', strtotime($subasta->session_start)) }}</p>
                                </div>
                                <div class="">
                                    <small>{{ date('H:i', strtotime($subasta->session_start)) }} h</small>
                                </div>

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
                            <div class="auction-item-icon-desc d-block">
                                <a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
                                    class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}</a>
                            </div>
                            <div class="auction-item-icon-desc d-block">
                                <a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide',{direction:'right'},500)"
                                    class="btn-info-auction secondary-button">
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.documentacion') }}
                                </a>
                            </div>
                            @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                                <div class="bid-life d-block">
                                    <a style="color:#FFFFFF" class="btn-bid-life d-block "
                                        href="{{ $url_tiempo_real }}"
                                        title="{{ trans(\Config::get('app.theme') . '-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme') . '-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                                        target="_blank">{{ trans(\Config::get('app.theme') . '-app.lot.bid_live') }}</a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
