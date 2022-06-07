<?php
$indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
//$url_lotes = \Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference);
$url_tiempo_real = \Tools::url_real_time_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions);
$url_subasta = \Tools::url_info_auction($subasta->cod_sub, $subasta->name);
$sub = new App\Models\Subasta;
$sub->cod = $subasta->cod_sub;
$auction = $sub->getInfSubasta();
$files = $sub->getFiles($subasta->cod_sub);
$subastasExternas = ['NAC'];

?>

<div class="row">
	<div class="col-xs-12 mt-4">

		<div class="row">
			<div class="col-md-2 col-sm-4 col-xs-12 mb-1">
				<a title="{{ $subasta->name }}" href="{{ $url_lotes }}">
					<img
						src="{{\Tools::url_img_session('subasta_medium',$subasta->cod_sub,$subasta->reference)}}"
						data-src="{{\Tools::url_img_session('subasta_medium',$subasta->cod_sub,$subasta->reference)}}"
						alt="{{ $subasta->name }}"
						class="img-responsive img-auction-new" />
				</a>
			</div>

			<div class="col-md-9 col-sm-8 col-xs-12">

				<div class="col-xs-12">
					<div class="bid-large-title">
						<h2 title="{{ $subasta->name }}" href="<?= $url_subasta?>">{{ $subasta->name }}</h2>
					</div>
				</div>


				<div class="col-xs-12 sub-info">
					<div class="snippet_documentacion" id="docs{{$subasta->id_auc_sessions}}">
						<a onclick="javascript:$('#docs{{$subasta->id_auc_sessions}}').hide('slide', {direction:'right'}, 500)"
							style="color:#000;font-size:18px;position:absolute;right:10px;top:10px;cursor:pointer;">x</a>
						<b>{{ trans(\Config::get('app.theme').'-app.subastas.documentacion') }}:</b>

						@if( !empty( $files ) )

						@foreach($files as $file)

						<?php
										//Falta guardar icono por tipos y especificar cual sera cada uno.
										$path_icon = "";
										switch ($file->type) {
											case 1:
												$path_icon = "/img/icons/pdf.png";
												break;
											case 2:
												$path_icon = "/img/icons/video.png";
												break;
											case 3:
												$path_icon = "/img/icons/image.png";
												break;
											default:
												$path_icon = "/img/icons/document.png";
												break;
										}
										?>
						<div class="row">
							<div class="col-xs-1"></div>
							<div class="col-xs-1 text-center"><img src="{{ $path_icon }}" width="80%"></div>
							<div class="col-xs-10"><a style="text-decoration: none;" title="{{ $file->description }}"
									target="_blank" href="/files/{{ $file->path }}">{{ $file->description }}</a>
							</div>
						</div>

						@endforeach

						@endif
					</div>

					<div>
						<p style="font-weight: 600;">
							{{ date("d-m-Y", strtotime($subasta->session_start)) }}&nbsp;&nbsp;{{ date("H:i", strtotime($subasta->session_start)) }}
							h</p>
					</div>
					<div>

						<p style="font-weight: 600;">{{ $auction->description }}</p>
					</div>
				</div>
				<div class="col-xs-12">

					<div class="auction-item-links w-100">

						<div class="row">
							<div class="col-xs-4 col-md-3">

								<div class="auction-item-icon-desc d-block">
									<a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
										class=" btn-view-lots button-principal">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
								</div>

							</div>
							@if( !empty($files) )
							<div class="col-xs-4 col-md-3">

								<div class="auction-item-icon-desc  d-block">
									@if($files[0]->type == '5')
									<a title="{{ $subasta->name }}" target="_blank" href="{{ $files[0]->url }}"
										class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
									@else
									<a title="{{ $subasta->name }}" target="_blank" href="/files/{{ $files[0]->path }}"
										class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
									@endif
								</div>

							</div>
							@endif

							<div class="col-xs-4 col-md-3 hidden">

								<div class="auction-item-icon-desc d-block">
									<a onclick="javascript:$('#docs{{$subasta->id_auc_sessions}}').toggle('slide', {direction:'right'}, 500)"
										class="btn-info-auction secondary-button" style="cursor:pointer;">
										{{ trans(\Config::get('app.theme').'-app.subastas.documentacion') }}
									</a>
								</div>

							</div>
							<div class="col-xs-4 col-md-3">

								@if( $subasta->tipo_sub =='W' && strtotime($subasta->session_end) > time() && !in_array($subasta->cod_sub, $subastasExternas))
								<div class="bid-life d-block">
									<a style="color:#FFFFFF" class="btn-bid-life d-block " href="{{ $url_tiempo_real }}"
										title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}"
										target="_blank">{{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}</a>
								</div>
								@elseif($subasta->tipo_sub =='W' && (strtotime($subasta->session_end) < time() || $subasta_finalizada))
								<div class="bid-life d-block">
									<a style="color:#FFFFFF" class="btn-bid-life d-block " href="{{ $url_lotes }}?noAward=1">
										{{ trans(\Config::get('app.theme').'-app.lot_list.no_award_filter') }}
									</a>
								</div>
								@endif


							</div>

						</div>
						<div class="row">

						</div>






					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="bid-large-separator col-xs-12"></div>

