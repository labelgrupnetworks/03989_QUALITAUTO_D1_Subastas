@foreach ($data['auction_list'] as  $subasta)
<?php 
 
	$indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
	$url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
	$url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
	$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);

?>
<div class="info-auction-in-list color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="row">
				<div class="col-xs-12 col-sm-6 no-padding">
					<div class="documents documents{{$subasta->cod_sub}}">

						<a onclick="javascript:$('.documents{{$subasta->cod_sub}}').toggle('blind',300)" class="close">
							<img src="/themes/belive/assets/img/shape.png" alt="Close">
						</a>
						<h3>{{ trans(\Config::get('app.theme').'-app.subastas.documentos') }}</h3>
						<br>
							<ul class="ul-format">
								<?php 
									$has_documents = 0;	
									if (is_dir($newfile = str_replace("\\","/",getcwd().'/files/'.$subasta->cod_sub))) {
										$directorio = opendir($newfile = str_replace("\\","/",getcwd().'/files/'.$subasta->cod_sub));
										while ($archivo = readdir($directorio)) 
										{
										    if (!is_dir($archivo))
										    {?>
										    <li class="col-md-12 col-xs-6 no-padding">
												<a target="_blank" class="cat-pdf color-letter d-flex" href="/files/{{$subasta->cod_sub}}/{{$archivo}}" role="button">
													<div class="text-center"><i class="fas  fa-file-download"></i></div>
													&nbsp;&nbsp;{{ $archivo }}
												</a>
											</li>
										    <?php 
										    $has_documents = 1;
											}
										}
									}

									?>
								</ul>
							</div>
					<div class="info-auction-img-content">
						<img 
							data-src="{{\Tools::url_img_session('subasta_large',$subasta->cod_sub,$subasta->reference)}}" 
							alt="{{ $subasta->name }}" 
							class="img-responsive lazy"
							style="display: none"                                
							img-responsive">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">

					<div class="info-auction-info-content">
						<div class="info-auction-info-title">
							<h3>{{$subasta->name }}</h3>
						</div>
						<div class="info-auction-info-address">

							<hr>
							<center>                    
								<span style="font-weight: 600;">{{ date("d-m-Y", strtotime($subasta->session_start)) }}</span>
								&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
								<span style="font-weight: 600;">{{ date("d-m-Y", strtotime($subasta->session_end)) }}</span>
							</center>
						
							<hr>

								




							   <div class="auction-item-icon-desc d-block">
									<a title="{{ $subasta->name }}" href="{{ $url_lotes }}?order=ffin_desc&awardOpen=on" class=" btn-view-lots button-principal">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
								</div>    
								<div class="auction-item-icon-desc  d-block">
									<a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
								</div> 
								@if (isset($has_documents) && !empty($has_documents))
								<div class="auction-item-icon-desc d-block">
									<a onclick="javascript:$('.documents{{$subasta->cod_sub}}').toggle('blind',300);" class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme').'-app.subastas.documentos') }}</a>
								</div> 
								@endif
							   
						</div>

					</div>
					
					<div class="clearfix"></div>
				</div>
			</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<br><br>
@endforeach

											

	