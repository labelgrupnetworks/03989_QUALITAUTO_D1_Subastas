@if (!empty($data["auction"]))
    <div class="container">
	<div class="row it_ro">
            <div class="col-xs-12">
                <div class="col-sm-7 col-xs-12">
                    <div class="img-subasta">
                        <div class="img-border-auction">
                            <img  width="100%" src="/img/load/subasta_large/AUCTION_{{ $data["auction"]->emp_sub }}_{{ $data["auction"]->cod_sub }}.jpg" class="img-responsive">
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="col-xs-12">
                         <h1 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>
                         <p><?= empty($data["auction"]->descdet_sub)? ' ' : $data["auction"]->descdet_sub; ?></p>

                    </div>
                    <div class="col-xs-12 desc-panel-aution">
                        <div class="content-auction-desc">
                            <div class="block-auction exposition-des">
                                @if( (!empty($data["auction"]->expofechas_sub)) ||  (!empty($data["auction"]->expohorario_sub)) || (!empty($data["auction"]->expolocal_sub)) || (!empty($data["auction"]->expomaps_sub)))
                                    <h4>{{ trans($theme.'-app.subastas.inf_subasta_exposicion') }}</h4>
                                @endif
                                @if(!empty($data["auction"]->expofechas_sub))
                                    <p><?= $data["auction"]->expofechas_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->expohorario_sub))
                                    <p>{{ trans($theme.'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->expohorario_sub ?> </p>
                                @endif
                                @if(!empty($data["auction"]->expolocal_sub))
                                    <p>{{ trans($theme.'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->expolocal_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->expomaps_sub))
                                    <p><a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>">{{ trans($theme.'-app.subastas.how_to_get') }}</a></p>
                                @endif
                            </div>
                            <div class="block-auction exposition-des">
                                @if((!empty($data["auction"]->sesfechas_sub)) || (!empty($data["auction"]->seshorario_sub)) || (!empty($data["auction"]->seslocal_sub)) || (!empty($data["auction"]->sesmaps_sub)))
                                    <h4>{{ trans($theme.'-app.subastas.inf_subasta_subasta') }}</h4>
                                @endif
                                @if(!empty($data["auction"]->sesfechas_sub))
                                   <p> <?= $data["auction"]->sesfechas_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->seshorario_sub))
                                    <p>{{ trans($theme.'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->seshorario_sub ?> </p>
                                @endif
                                @if(!empty($data["auction"]->seslocal_sub))
                                    <p>{{ trans($theme.'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->seslocal_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->sesmaps_sub))
                                    <p><a target="_blank" title="{{ trans($theme.'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>">{{ trans($theme.'-app.subastas.how_to_get') }}</a></p>
                                @endif
                            </div>
                        </div>
                        <div class="links-aution">
                            @foreach ($data['sessions'] as $session)
                                <p
                                    style="
                                        text-align: left;
                                        font-weight: 900;
                                        display: inline-flex;
                                        border-radius: 40px;
                                        padding: 6px;
                                        border-bottom-left-radius: 0;
                                        border-top-left-radius: 0;
                                        background: ghostwhite;
                                ">
                                    {{$session->name}}
                                </p>
                                <p
                                    class="text-center"
                                    style="
                                        background-color:#575653;
                                        padding: 20px 0;
                                        max-width: 266px; "
                                >
                                    <a
                                        style="color:#FFFFFF"
                                        @if($data['auction']->tipo_sub =='V')
                                        href="{{\Tools::url_auction($session->auction,$session->name,$session->id_auc_sessions,$session->reference) . '?only_salable=on'}}"
                                        @else
                                        href="{{\Tools::url_auction($session->auction,$session->name,$session->id_auc_sessions,$session->reference)}}"
                                        @endif
                                        title="{{ trans($theme.'-app.subastas.see_lotes') }}" target="_blank">{{ trans($theme.'-app.subastas.see_lotes') }}</a>
                                </p>

                                <?php  $file_code = $session->company . '_' .  $session->auction . '_' . $session->reference ?>
                                @if($session ->upcatalogo == 'S')
                                    <p class="" style="background-color:#ecedef;padding: 5px; margin-top:10px">
                                        <a style="display:block;color:#333" title="{{ trans($theme.'-app.grid.pdf_catalog') }}" target="_blank" href="{{\Tools::url_pdf($data["auction"]->cod_sub,$session->reference,'cat')}}"><i class="fa-2x	fa fa-file-pdf-o" style="color: #333;margin-right:  5px;"></i> {{ trans($theme.'-app.subastas.pdf_catalog') }}</a>
                                    </p>
                                @endif
                                @if( $session ->uppdfadjudicacion == 'S')
                                    <p class=""  style="background-color:#ecedef;padding: 5px; margin-top:10px">
                                        <a style="display:block;color:#333" title="{{ trans($theme.'-app.grid.pdf_adj') }}" target="_blank" href="{{\Tools::url_pdf($data["auction"]->cod_sub,$session->reference,'pre')}}"><i class="fa-2x	fa fa-file-pdf-o" style="color: #333;margin-right:  5px;"></i> {{ trans($theme.'-app.subastas.pdf_adj') }}</a>
                                    </p>
                                @endif
                                <?php
                                    $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['auction']->cod_sub."-".str_slug($data['auction']->name)."-".$data['auction']->id_auc_sessions;
                                ?>
                                @if( $data['auction']->tipo_sub =='W' &&   strtotime($data['auction']->end) > time() )
                                <p
                                    class="text-center"
                                    style="
                                        height: 40px;
                                        background-color: #9e190a;
                                        padding: 11px 0;
                                        max-width: 266px;
                                        font-weight: 900;
                                        border-radius: 40px; "
                                >
                                    <a  style="color:#FFFFFF"   href="{{ $url_tiempo_real }}" title="{{ trans($theme.'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$data['auction']->start),'d/m/Y H:i') }} {{ trans($theme.'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$data['auction']->end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                                </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xs-12 share-panel-auction">
                        @include('includes.ficha.share_ficha_subasta')
                    </div>
                </div>
            </div>
	</div>
    </div>
    @else
    <div class="container">
	<div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="single_auction">
                    <h1 class="titleSingle">{{trans($theme.'-app.lot.auction_not_found')}}</h1>
                </div>
            </div>
        </div>
    </div>
    @endif
