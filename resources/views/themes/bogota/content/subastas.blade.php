<section class="all-auctions color-letter container">
    <div class="row auctions-list">

        @foreach ($data['auction_list'] as $subasta)
            @php
                $url_lotes = \Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference);
                $url_tiempo_real = \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
                $url_subasta = \Tools::url_info_auction($subasta->cod_sub, $subasta->name);

                //Obtener los archivos de una subasta
                $sub = new App\Models\Subasta();
                $files = $sub->getFiles($subasta->cod_sub);
                $filesIcons = [
                    1 => '/img/icons/pdf.png',
                    2 => '/img/icons/video.png',
                    3 => '/img/icons/image.png',
                    4 => '/img/icons/document.png',
                ];

                $noIcon = '/img/icons/document.png';
            @endphp

            <article class="col-xs-12 col-md-6 mb-2">
                <div class="auction-wrapper position-relative" style="border: 1px solid lightgray;">
                    @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                        <div class="bid-online"></div>
                        <div class="bid-online animationPulseRed"></div>
                    @endif

                    <div class="row d-flex flex-wrap m-0">
                        <div class="col-xs-12 col-md-8" style="border-right: 1px solid lightgray;">
                            <h5 class="aution-title">{{ $subasta->name }}</h5>

							<div style="border-bottom: 1px solid lightgray;margin-right: -15px; margin-left: -15px;"></div>

                            <div class="row auction-content-block position-relative">
                                <div class="col-xs-12 col-md-5 auction-image">
                                    <img data-src="{{ \Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
										alt="{{ $subasta->name }}" class="img-responsive lazy m-auto" style="display: none" />
                                </div>
                                <div class="col-xs-12 col-md-7 auction-dates">
                                    <p style="font-weight: 600;">
                                        {{ date('d-m-Y', strtotime($subasta->session_start)) }}
                                    </p>
                                    <small>{{ date('H:i', strtotime($subasta->session_start)) }} h</small>
                                </div>

								<div class="snippet_documentacion" id="docs{{ $subasta->id_auc_sessions }}">
									<a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide', {direction:'right'}, 500)"
										style="color:#000;font-size:18px;position:absolute;right:10px;top:10px;cursor:pointer;">x</a>
									<b>{{ trans(\Config::get('app.theme') . '-app.subastas.documentacion') }}:</b>

									@foreach ($files as $file)
										<div class="row">
											<div class="col-xs-1"></div>
											<div class="col-xs-1 text-center">
												@if ($file->type == 5)
													<i class="fa fa-map-marker" aria-hidden="true" style="font-size: 25px;"></i>
												@else
													<img src="{{ $filesIcons[$file->type] ?? $noIcon }}" width="80%">
												@endif
											</div>
											<div class="col-xs-10">
												<a style="text-decoration: none;" title="{{ $file->description }}"
													target="_blank"
													href="{{ $file->type == 5 ? $file->url : "/files/$file->path" }}">
													{{ $file->description }}
												</a>
											</div>

										</div>
									@endforeach
								</div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="auction-buttons h-100">
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
                                            title="{{ trans(\Config::get('app.theme') . '-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme') . '-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                                            target="_blank">{{ trans(\Config::get('app.theme') . '-app.lot.bid_live') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </article>
        @endforeach
    </div>

</section>
