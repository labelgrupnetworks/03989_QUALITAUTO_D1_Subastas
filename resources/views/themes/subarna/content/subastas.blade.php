<div class="container">
	<h1 class="page-title">
		{{ trans("$theme-app.subastas.auctions") }}
	</h1>
    <div class="row">
        @foreach ($data['auction_list'] as  $subasta)
            <?php
            $url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,$subasta->reference);
            $url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
			$url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);
            ?>
            <div class="col-xs-12 col-sm-4 col-lg-3">
                <div class="item_subasta">
                    <a title="{{ $subasta->name }}" href="<?= $url_subasta?>">
                        <div class="img-lot">
                                <img
                                    src="{{ Tools::url_img_auction('subasta_medium', $subasta->cod_sub) }}"
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
                           class="btn btn-lotes">{{ trans($theme.'-app.subastas.see_lotes') }}</a>
                        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-subasta">{{ trans($theme.'-app.subastas.see_subasta') }}</a>
                        @if($subasta->upcatalogo == 'S')
                            <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                <a title="{{ trans($theme.'-app.grid.pdf_catalog') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat')}}">{{ trans($theme.'-app.subastas.pdf_catalog') }}</a> <br>
                            </p>
                        @endif
                        @if( $subasta->uppreciorealizado == 'S')
                            <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                                <a title="{{ trans($theme.'-app.grid.pdf_adj') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'pre')}}">{{ trans($theme.'-app.subastas.pdf_adj') }}</a> <br>
                            </p>
                        @endif
                        @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                            <p class="text-center" style="background-color:#9e190a;padding: 20px 0; ">
                                <a  style="color:#FFFFFF"   href="{{ $url_tiempo_real }}" title="{{ trans($theme.'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans($theme.'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                            </p>
                        @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
