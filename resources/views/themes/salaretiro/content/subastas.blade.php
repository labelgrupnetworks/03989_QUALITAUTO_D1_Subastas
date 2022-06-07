<?php
    if($data['subc_sub'] == 'H'){
        foreach($data['auction_list'] as $key => $subasta){
            if(strtotime($subasta->session_end) < strtotime("now - 1 year")){
                unset($data['auction_list'][$key]);
            }
        }
        $historico = array();
        foreach ($data['auction_list'] as $value){
            $year = date("Y", strtotime($value->session_start));
            if(empty($translate[$year])){
                $historico[$year][$value->cod_sub] = array();
            }
            $historico[$year][$value->cod_sub] = $value;

        }
    }
?>
@if($data['subc_sub'] != 'H')
    <div class="container">
        <div class="row">
            @if(!empty($data['auction_list']) && head($data['auction_list'])->tipo_sub != 'V')
                <div class="col-xs-12 col-sm-12">
                    <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
                </div>
            @endif
            @foreach ($data['auction_list'] as  $subasta)
                <?php
                    // $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                    $indices = array();
                    if(count($indices) > 0 ){
                        $url_lotes=\Routing::translateSeo('indice-subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                    }else{
                        $url_lotes=\Routing::translateSeo('subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
					}

                $url_tiempo_real=\Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                $url_subasta=\Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
                  if( $subasta->tipo_sub =='V' ){
                           $url_lotes.='?only_salable=on';
                  }elseif(strtotime($subasta->session_end) < time()){
					$url_lotes.='?no_award=on';
				  }
                ?>
                <div class="col-xs-12 col-sm-4 col-lg-3">
                    <div class="item_subasta">
                        <a title="{{ $subasta->name }}" href="<?= $url_lotes?>">
                            <div class="img-lot">
                                <img
                                    src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg"
                                    alt="{{ $subasta->name }}"
                                    class="img-responsive"
                                />
                            </div>
                        </a>
                        <div class="item_subasta_item text-center">
                               {{ $subasta->name }}
                        </div>
                        <a
                            title="{{ $subasta->name }}"
                            href="{{ $url_lotes }}"
                            class="btn btn-lotes"
                        >
                            {{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}
                        </a>
                        <a
                            title="{{ $subasta->name }}"
                            href="{{ $url_subasta }}"
                            class="btn btn-subasta"
                        >
                            {{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}
                        </a>
                        <?php // 06/02/2019 han pedido mostrar una frase para algunas de las usbastas de tipo V ?>
                        @if($subasta->tipo_sub == 'V')
                            <p style="text-align: justify; color: black; font-style: oblique;">@if(in_array($subasta->cod_sub,array('VDWDEC18','VDWMOB18','VDWESC18','CINE2019','VDWP19'))) {{trans(\Config::get('app.theme').'-app.subastas.auctions_alert') }}  @endif</p>
                        @endif
                        @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                            <p class="text-center" style="background-color:#9e190a;padding: 20px 0; ">
                                <a  style="color:#FFFFFF"   href="{{ $url_tiempo_real }}" title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                            </p>
                        @endif

						<?php $sub = new App\Models\Subasta;
						$files = $sub->getFiles($subasta->cod_sub) ?>
						@if( !empty( $files ) )
							@foreach($files as  $file)
								<!-- no hay iconos de documentos ya que no se iban a usar -->
								@if ($file->type == 5)
											<p class="text-center btn-catalog">
												<a title="{{ $file->description }}" target="_blank" href="{{$file->url}}">{{ $file->description }}</a>
											</p>
									@else
										<p class="text-center  " style="background-color:#ecedef;padding: 5px 0;">
											<a title="{{ $file->description }}" target="_blank" href="/files{{ $file->path }}">{{ $file->description }}</a> <br>
										</p>
									@endif
							@endforeach
						@endif

                        @if( $subasta->uppreciorealizado == 'S')
                            <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_adj') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'pre')}}">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</a> <br>
                            </p>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="container">
        <div class="row">
            @foreach ($historico as $key => $sub)
                <div class="col-xs-12">
                    <div class="dat">
                        {{ $key }}
                    </div>
                </div>
                @foreach($sub as $subasta )
                    <?php
                        // $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                        $indices = array();
                        if(count($indices) > 0 ){
                            $url_lotes=\Routing::translateSeo('indice-subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                        }else{
                            $url_lotes=\Routing::translateSeo('subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                        }
                        $url_tiempo_real=\Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                        $url_subasta=\Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
                    ?>
                    <div class="col-xs-12 col-sm-4 col-lg-3">
                        <div class="item_subasta">
                            <a title="{{ $subasta->name }}" href="<?= $url_lotes?>">
                                <div class="img-lot">
                                    <img
                                        src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg"
                                        alt="{{ $subasta->name }}"
                                        class="img-responsive"
                                    />
                                </div>
                            </a>
                            <div class="item_subasta_item text-center">
                               {{ $subasta->name }}
                            </div>
                            <a title="{{ $subasta->name }}" href="{{ $url_lotes }}" class="btn btn-lotes">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                            <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-subasta">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
                            @if( file_exists("files/". $subasta->file_code ."_cat.pdf"))
                                <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                    <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_catalog') }}" target="_blank" href="/files/{{ $subasta->file_code }}_cat.pdf?a=<?= rand();?>">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</a> <br>
                                </p>
                            @endif
                            @if( file_exists("files/". $subasta->file_code ."_adj.pdf"))
                                <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                    <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_adj') }}" target="_blank" href="/files/{{ $subasta->file_code }}_adj.pdf?a=<?= rand();?>">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</a> <br>
                                </p>
                            @endif
                            @if( file_exists("files/". $subasta->file_code ."_pre.pdf"))
                                <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                    <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_adj') }}" target="_blank" href="/files/{{ $subasta->file_code }}_adj.pdf?a=<?= rand();?>">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_pre') }}</a> <br>
                                </p>
                            @endif
                            @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                                <p class="text-center" style="background-color:#9e190a;padding: 20px 0; ">
                                    <a  style="color:#FFFFFF"   href="{{ $url_tiempo_real }}" title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endif
