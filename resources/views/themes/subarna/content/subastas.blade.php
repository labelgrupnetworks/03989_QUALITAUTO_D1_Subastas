<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
                <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
        </div>
        @foreach ($data['auction_list'] as  $subasta)
            <?php

                 // $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                /*$indices = array();
                if(count($indices) > 0 ){
                    $url_lotes=\Routing::translateSeo('indice-subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                }else{
                    $url_lotes=\Routing::translateSeo('subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                }
              $url_tiempo_real=\Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
			  $url_subasta=\Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
			  */
			$indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
            $url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
            $url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
			$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);

            ?>
            <div class="col-xs-12 col-sm-4 col-lg-3">
                <div class="item_subasta">
                    <a title="{{ $subasta->name }}" href="<?= $url_subasta?>">
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
                    <?php
                           if( $subasta->tipo_sub =='V' ){
                           $url_lotes.='?only_salable=on';
                          }
                          ?>

                        <a title="{{ $subasta->name }}" href="{{ $url_lotes}}"
                           class="btn btn-lotes">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-subasta">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
                        @if($subasta->upcatalogo == 'S')
                            <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_catalog') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat')}}">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</a> <br>
                            </p>
                        @endif
                        @if( $subasta->uppreciorealizado == 'S')
                            <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_adj') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'pre')}}">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</a> <br>
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
    </div>
</div>
