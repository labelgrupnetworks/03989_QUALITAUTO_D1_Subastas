<div class="info-auction">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 col-sm-7 info-auction-img">
                    <div class="info-auction-img-content">
                        <img  width="100%" src="{{ Tools::url_img_auction('subasta_large', $data["auction"]->cod_sub) }}" class="img-responsive">
                    </div>

                    <div class="view-auction-container">
                        @foreach ($data['sessions'] as $session)
                        <a title="Ver lotes" href="<?= Routing::translateSeo('subasta') . $session->auction . "-" . str_slug($session->name . "-" . $session->id_auc_sessions) ?>">
                            <div class="view-auction-button text-center">
                                <span id="view-auction-button-lote">{{ trans($theme.'-app.subastas.see_lotes') }}</span><span>{{ $session->name }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>

                </div>
                <div class="col-xs-12 col-sm-5 info-auction-info">
                    <div class="info-auction-info-content">
                        <div class="info-auction-info-title">
                            <h1 class="">{{ $data["auction"]->des_sub}}</h1>
                            <p><?= empty($data["auction"]->descdet_sub) ? ' ' : $data["auction"]->descdet_sub; ?></p>
                        </div>
                        <br>
                        <div class="info-auction-info-address">
                            @if( (!empty($data["auction"]->expofechas_sub)) ||  (!empty($data["auction"]->expohorario_sub)) || (!empty($data["auction"]->expolocal_sub)) || (!empty($data["auction"]->expomaps_sub)))
                            <div class="info-auction-info-address-title">{{ trans($theme.'-app.subastas.inf_subasta_exposicion') }}</div>
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
                            <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="cómo llegar" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>"><i class="fas fa-map-marker-alt" style="font-size: 17px;"></i></a></p>
                            @endif
                            @if((!empty($data["auction"]->sesfechas_sub)) || (!empty($data["auction"]->seshorario_sub)) || (!empty($data["auction"]->seslocal_sub)) || (!empty($data["auction"]->sesmaps_sub)))
                            <div class="info-auction-info-address-title">{{ trans($theme.'-app.subastas.inf_subasta_subasta') }}</div>
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
                            <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="{{ trans($theme.'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>"><i class="fas fa-map-marker-alt" style="font-size: 17px;"></i></a></p>
                            @endif


                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
